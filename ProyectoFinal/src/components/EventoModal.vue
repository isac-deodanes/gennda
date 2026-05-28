<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-button color="medium" @click="handleCancel">Cancelar</ion-button>
        </ion-buttons>
        <ion-title>{{ isEditMode ? 'Editar' : 'Nuevo' }} Evento</ion-title>
        <ion-buttons slot="end">
          <ion-button :strong="true" @click="handleSave" :disabled="!eventoData.titulo">Guardar</ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <ion-list :inset="true">
        <ion-item>
          <ion-input
            label="Título"
            label-placement="stacked"
            placeholder="Título del evento"
            v-model="eventoData.titulo"
          ></ion-input>
        </ion-item>
        
        <ion-item>
          <ion-label>Fecha</ion-label>
          <ion-datetime-button datetime="datetime-fecha"></ion-datetime-button>
        </ion-item>

        <ion-item>
          <ion-label>Hora</ion-label>
          <ion-datetime-button datetime="datetime-hora"></ion-datetime-button>
        </ion-item>

        <ion-item>
          <ion-select
            label="Categoría"
            placeholder="Seleccionar"
            v-model="eventoData.categoria_id"
          >
            <ion-select-option :value="null">Sin Categoría</ion-select-option>
            <ion-select-option
              v-for="cat in categories"
              :key="cat.id"
              :value="cat.id"
            >
              {{ cat.nombre }}
            </ion-select-option>
          </ion-select>
        </ion-item>

        <ion-item>
          <ion-input
            label="Gasto Estimado"
            label-placement="stacked"
            type="number"
            placeholder="$0.00"
            v-model="eventoData.gasto_estimado"
          ></ion-input>
        </ion-item>
        
        <ion-item>
          <ion-textarea
            label="Descripción"
            label-placement="stacked"
            placeholder="Añade una descripción..."
            :auto-grow="true"
            v-model="eventoData.descripcion"
          ></ion-textarea>
        </ion-item>
      </ion-list>
    </ion-content>

    <ion-modal :keep-contents-mounted="true">
      <ion-datetime
        id="datetime-fecha"
        presentation="date"
        v-model="fechaValue" 
      ></ion-datetime>
    </ion-modal>
    <ion-modal :keep-contents-mounted="true">
      <ion-datetime
        id="datetime-hora"
        presentation="time"
        v-model="horaValue"
      ></ion-datetime>
    </ion-modal>
    </ion-page>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted } from 'vue';
import {
  IonPage,
  IonHeader,
  IonToolbar,
  IonButtons,
  IonButton,
  IonTitle,
  IonContent,
  IonList,
  IonItem,
  IonInput,
  IonTextarea,
  IonSelect,
  IonSelectOption,
  IonLabel,
  IonDatetime,
  IonDatetimeButton,
  IonModal,
  modalController,
  loadingController,
  toastController
} from '@ionic/vue';
import axios from 'axios';

// --- Interfaces y Props ---
interface Categoria {
  id: number;
  nombre: string;
  color: string;
}
interface Evento {
  id: number;
  titulo: string;
  descripcion: string;
  fecha: string;
  hora: string;
  gasto_estimado: string;
  categoria_id: number | null; // Correcto
  categoria?: Categoria;
}

const props = defineProps({
  selectedDate: {
    type: String,
    required: true,
  },
  categories: {
    type: Array as () => Categoria[],
    default: () => [],
  },
  eventoToEdit: {
    type: Object as () => Evento,
    default: null
  }
});

// --- Configuración de API ---
const API_URL = 'http://127.0.0.1:8000/api';
const authToken = localStorage.getItem('auth_token');
const apiClient = axios.create({
  baseURL: API_URL,
  headers: {
    'Authorization': `Bearer ${authToken}`,
    'Accept': 'application/json'
  }
});

// --- LÓGICA DE HORA Y ESTADO ---
const getLocalTimeHHMM = (date: Date): string => {
  const hours = String(date.getHours()).padStart(2, '0');
  const minutes = String(date.getMinutes()).padStart(2, '0');
  return `${hours}:${minutes}`;
};

// --- ¡NUEVA INTERFAZ PARA EL FORMULARIO! ---
interface EventoFormData {
  titulo: string;
  fecha: string;
  hora: string;
  descripcion: string;
  gasto_estimado: number | string | null;
  categoria_id: number | null; // <-- Tipo explícito
}

// Valores por defecto (para modo "Crear")
const now = new Date(); 
const fechaValue = ref(props.selectedDate);
const horaValue = ref(getLocalTimeHHMM(now));

// --- ¡MODIFICADO! Se usa la interfaz EventoFormData ---
const eventoData = ref<EventoFormData>({
  titulo: '',
  fecha: props.selectedDate,
  hora: getLocalTimeHHMM(now),
  descripcion: '',
  gasto_estimado: null,
  categoria_id: null, 
});

// --- Lógica de Edición ---
const isEditMode = computed(() => !!props.eventoToEdit);

onMounted(() => {
  if (isEditMode.value) {
    eventoData.value.titulo = props.eventoToEdit.titulo;
    eventoData.value.fecha = props.eventoToEdit.fecha;
    eventoData.value.hora = props.eventoToEdit.hora.substring(0, 5);
    eventoData.value.descripcion = props.eventoToEdit.descripcion;
    eventoData.value.gasto_estimado = props.eventoToEdit.gasto_estimado as any;
    
    // ¡ESTA LÍNEA AHORA ES VÁLIDA!
    eventoData.value.categoria_id = props.eventoToEdit.categoria_id; 
    
    fechaValue.value = props.eventoToEdit.fecha;
    horaValue.value = props.eventoToEdit.hora.substring(0, 5);
  }
});

// --- Watchers ---
watch(fechaValue, (newVal) => {
  if (newVal) {
    eventoData.value.fecha = newVal.split('T')[0];
  }
});

watch(horaValue, (newVal) => {
  if (newVal && newVal.includes('T')) {
    const date = new Date(newVal);
    eventoData.value.hora = getLocalTimeHHMM(date);
  } else if (newVal) {
    eventoData.value.hora = newVal;
  }
});

// --- Métodos del Modal ---
const handleCancel = () => {
  modalController.dismiss(null, 'cancel');
};

const handleSave = async () => {
  const loading = await loadingController.create({ message: 'Guardando...' });
  await loading.present();

  try {
    let response;
    if (isEditMode.value) {
      response = await apiClient.put(`/eventos/${props.eventoToEdit.id}`, eventoData.value);
    } else {
      response = await apiClient.post('/eventos', eventoData.value);
    }
    
    await loading.dismiss();
    modalController.dismiss(response.data, 'confirm');

  } catch (error: any) {
    await loading.dismiss();
    const toast = await toastController.create({
      message: 'Error al guardar. Revisa los campos.',
      duration: 3000,
      color: 'danger'
    });
    await toast.present();
    console.error('Error en API:', error.response?.data);
  }
};
</script>

<style scoped>
ion-list { background: transparent; }
ion-item { margin-bottom: 8px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
</style>