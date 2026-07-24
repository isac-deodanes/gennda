<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-menu-button />
        </ion-buttons>
        <ion-title>Historial</ion-title>
      </ion-toolbar>
    </ion-header>
    <ion-content :fullscreen="true">
      <div class="background-container ion-padding">
        <ion-card class="filters-card">
          <ion-item lines="none">
            <ion-label>Mes</ion-label>
            <ion-select v-model="filterMonth" @ionChange="loadEvents" interface="action-sheet">
              <ion-select-option :value="1">Enero</ion-select-option>
              <ion-select-option :value="2">Febrero</ion-select-option>
              <ion-select-option :value="3">Marzo</ion-select-option>
              <ion-select-option :value="4">Abril</ion-select-option>
              <ion-select-option :value="5">Mayo</ion-select-option>
              <ion-select-option :value="6">Junio</ion-select-option>
              <ion-select-option :value="7">Julio</ion-select-option>
              <ion-select-option :value="8">Agosto</ion-select-option>
              <ion-select-option :value="9">Septiembre</ion-select-option>
              <ion-select-option :value="10">Octubre</ion-select-option>
              <ion-select-option :value="11">Noviembre</ion-select-option>
              <ion-select-option :value="12">Diciembre</ion-select-option>
            </ion-select>
          </ion-item>
          <ion-item lines="none">
            <ion-label>Año</ion-label>
            <ion-input type="number" v-model.number="filterYear" placeholder="Escribe el año" @ionChange="loadEvents" class="year-input"></ion-input>
          </ion-item>
          <ion-toolbar class="searchbar-toolbar">
            <ion-searchbar v-model="searchTerm" placeholder="Buscar por nombre..."@ionInput="applyFilters"></ion-searchbar>
          </ion-toolbar>
        </ion-card>
        <ion-card class="summary-card expense">
          <ion-card-header>
            <ion-card-subtitle>Gastos Totales ({{ currentMonthName }})</ion-card-subtitle>
            <ion-card-title>{{ formatCurrency(totalGastos) }}</ion-card-title>
          </ion-card-header>
        </ion-card>
        <ion-list-header>Eventos pasados con gastos ({{ currentMonthName }})</ion-list-header>
        <div v-if="isLoading" class="empty-state">
          <ion-spinner name="crescent"></ion-spinner>
          <ion-label>Cargando gastos...</ion-label>
        </div>
        <div v-if="!isLoading && filteredEvents.length === 0" class="empty-state">
          <ion-icon :icon="archiveOutline"></ion-icon>
          <ion-label>No se encontraron gastos</ion-label>
          <p>No hay eventos pasados con gastos para este período.</p>
        </div>
        <ion-list :inset="true" v-if="!isLoading && filteredEvents.length > 0">
          <ion-item v-for="event in filteredEvents" :key="event.id" button @click="openEventDetails(event)" lines="full">
            <ion-icon slot="start" :icon="calendarOutline" color="primary" class="tx-icon"></ion-icon>
            <ion-label>
              <h2 class="tx-title">{{ event.titulo }}</h2>
              <p class="tx-category">{{ event.categoria?.nombre || 'General' }}</p>
            </ion-label>
            <div slot="end" class="tx-details-end">
              <ion-text color="danger" class="tx-amount">
                -{{ formatCurrency(event.gasto_estimado) }}
              </ion-text>
              <p class="tx-date">{{ formatDate(event.fecha) }}</p>
            </div>
          </ion-item>
        </ion-list>
      </div>
    </ion-content>
    <ion-footer>
          <ion-toolbar>
              <p>© 2025 Gennda - Todos los derechos reservados</p>
            </ion-toolbar>
          </ion-footer>
    <ion-modal :is-open="isModalOpen" @didDismiss="isModalOpen = false" class="event-detail-modal">
      <ion-header>
          <ion-toolbar>
              <ion-title>{{ selectedEvent?.titulo }}</ion-title>
              <ion-buttons slot="end">
                <ion-button @click="isModalOpen = false">Cerrar</ion-button>
              </ion-buttons>
            </ion-toolbar>
          </ion-header>
          <ion-content class="ion-padding modal-content-background">
        <ion-list v-if="selectedEvent" lines="none">
          <ion-item class="detail-item" lines="none">
            <ion-label>
              <p class="detail-label">Gasto Estimado</p>
              <p class="detail-value-expense">{{ formatCurrency(selectedEvent.gasto_estimado) }}</p>
            </ion-label>
          </ion-item>
          <ion-item class="detail-item" lines="none">
            <ion-label>
              <p class="detail-label">Fecha y Hora</p>
              <p class="detail-value">{{ formatDate(selectedEvent.fecha) }} a las {{ selectedEvent.hora || 'N/A' }}</p>
            </ion-label>
          </ion-item>
          <ion-item class="detail-item" lines="none">
            <ion-label>
              <p class="detail-label">Descripción</p>
              <p class="detail-value" style="white-space: pre-wrap;">{{ selectedEvent.descripcion || 'No hay descripción.' }}</p>
            </ion-label>
          </ion-item>
        </ion-list>
      </ion-content>
    </ion-modal>
  </ion-page>
</template>
<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import {IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonButtons, IonMenuButton,IonFooter, IonCard, IonCardHeader, IonCardSubtitle, IonCardTitle,IonLabel, IonSelect, IonSelectOption, IonSearchbar,IonList, IonListHeader, IonItem, IonIcon, IonText, IonSpinner,IonModal, toastController, IonButton,IonInput} from '@ionic/vue';
import { calendarOutline, archiveOutline } from 'ionicons/icons';
import axios from 'axios';
import { useRouter } from 'vue-router';

const API_URL = import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api';
const authToken = localStorage.getItem('auth_token');
const router = useRouter();

const apiClient = axios.create({
  baseURL: API_URL,
  headers: {
    'Authorization': `Bearer ${authToken}`,
    'Accept': 'application/json'
  }
});

const allEvents = ref < any[] > ([]);
const filteredEvents = ref < any[] > ([]);
const isLoading = ref(true);

const now = new Date();
const filterMonth = ref(now.getMonth() + 1);
const filterYear = ref(now.getFullYear());
const searchTerm = ref('');
const todayISO = now.toISOString().split('T')[0];

const isModalOpen = ref(false);
const selectedEvent = ref < any > (null);

onMounted(() => {
  loadEvents();
});

const loadEvents = async () => {
  isLoading.value = true;
  allEvents.value = [];
  filteredEvents.value = [];

  if (!filterYear.value || filterYear.value < 2000 || filterYear.value > 2100) {
      presentToast('Por favor, ingresa un año válido (2000-2100)', 'danger');
      isLoading.value = false;
      return;
  }
  try {
    const response = await apiClient.get('/eventos', {
      params: {
        mes: filterMonth.value,
        ano: filterYear.value,
      }
    });
    const pastEventsWithCost = response.data
      .filter((event: any) =>
        event.fecha < todayISO &&
        parseFloat(event.gasto_estimado) > 0
      )
      .sort((a: any, b: any) => new Date(b.fecha).getTime() - new Date(a.fecha).getTime());
    allEvents.value = pastEventsWithCost;
  } catch (error) {
    console.error('Error al cargar eventos:', error);
    presentToast('Error al cargar historial', 'danger');
    if ((error as any).response?.status === 401) {
      localStorage.removeItem('auth_token');
      router.replace('/login');
    }
  } finally {
    isLoading.value = false;
    applyFilters();
  }
};
watch([filterMonth], loadEvents); 
watch(searchTerm, applyFilters);

function applyFilters() {
  let events = [...allEvents.value];
  if (searchTerm.value.trim() !== '') {
    const search = searchTerm.value.toLowerCase();
    events = events.filter(event =>
      event.titulo.toLowerCase().includes(search) ||
      (event.categoria && event.categoria.nombre.toLowerCase().includes(search))
    );
  }
  filteredEvents.value = events;
};

const totalGastos = computed(() => {
  return filteredEvents.value.reduce((acc, event) => acc + parseFloat(event.gasto_estimado), 0);
});
const currentMonthName = computed(() => {
  return new Date(filterYear.value, filterMonth.value - 1).toLocaleDateString('es-ES', { month: 'long' });
});

const formatCurrency = (v: any) => {
  const n = Number(v);
  if (isNaN(n)) return '$0.00';
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(n);
};
const formatDate = (dateString: string) => {
  return new Date(dateString + 'T12:00:00').toLocaleDateString('es-ES', { day: '2-digit', month: 'long', year: 'numeric' });
};
const presentToast = async (message: string, color: 'success' | 'danger') => {
  const toast = await toastController.create({
    message,
    duration: 2000,
    color,
    position: 'top'
  });
  await toast.present();
};
const openEventDetails = (event: any) => {
  selectedEvent.value = event;
  isModalOpen.value = true;
};
</script>

<style scoped>
.filters-card {
  margin: 0 0 16px 0;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  --background: #ffffff;
  background: #ffffff;
}
.filters-card ion-item {
  --background: transparent;
  --color: #000000;
}
.year-input {
  text-align: right;
  --padding-end: 0px; 
}

.filters-card ion-select {
  width: 100%;
  justify-content: flex-end;
}
.searchbar-toolbar {
  --background: transparent;
  --min-height: auto;
  padding: 8px;
}
.filters-card ion-searchbar {
  --background: #f4f5f8;
  --border-radius: 10px;
  --box-shadow: none;
  --placeholder-color: #000000;
  padding: 0;
}
.summary-card {
  margin: 0 0 24px 0;
  border-radius: 16px;
  color: rgb(0, 0, 0);
  background: linear-gradient(135deg, #5B8DEF 0%, #0F52BA 100%);
  text-align: center;
  padding: 16px;
}
.summary-card ion-card-subtitle {
  color: rgb(0, 0, 0);
  opacity: 0.9;
  font-size: 0.9rem;
  font-weight: 500;
  text-transform: uppercase;
}
.summary-card ion-card-title {
  font-size: 2.5rem;
  font-weight: 700;
  color: #000000;
}

ion-list-header {
  font-size: 1.1rem;
  font-weight: 700;
  color: #000;
  opacity: 0.7;
  padding-left: 8px;
  margin-bottom: 8px;
}

ion-list {
  background: transparent;
  padding: 0;
}
ion-list ion-item {
  --border-radius: 12px;
  --padding-start: 16px;
  --padding-end: 16px;
  --inner-padding-end: 0px;
  --min-height: 80px;
  margin: 0 0 12px 0;
}

.tx-title {
  font-weight: 600;
  color: #1f2937;
  font-size: 1rem;
  margin-bottom: 2px;
}
.tx-category {
  font-size: 0.85rem;
  color: #000000;
}
.tx-details-end {
  text-align: right;
  flex-shrink: 0;
  padding-right: 16px;
}
.tx-amount {
  font-size: 1.1rem;
  font-weight: 700;
  color: var(--ion-color-danger);
  margin-bottom: 2px;
}
.tx-date {
  font-size: 0.8rem;
  color: #000000;
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 48px 24px;
  background: #ffffff;
  border-radius: 16px;
  text-align: center;
  color: #000000;
  min-height: 250px;
  margin: 16px 0;
  box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}
.empty-state ion-icon {
  font-size: 4rem;
  color: var(--ion-color-primary);
}
.empty-state {
  font-size: 1.25rem;
  font-weight: 600;
  margin-top: 16px;
  color: #1f2937;
}
.empty-state p {
  font-size: 0.9rem;
  max-width: 300px;
  margin-top: 4px;
}

.event-detail-modal {
  --width: 95%;
  --height: auto;
  --max-height: 450px;
  --border-radius: 16px;
  box-shadow: 0 4px 16px rgba(0,0,0,0.2);
}
@media (min-width: 768px) {
  .event-detail-modal {
    --width: 500px; 
  }
}
.modal-content-background {
  --background: #ffffff;
}
.detail-item {
  --background: #f4f5f8;
  --border-radius: 12px;
  --padding-start: 16px;
  --padding-end: 16px;
  margin-bottom: 12px;
}
.detail-label {
  font-size: 0.8rem;
  font-weight: 600;
  color: #000000;
  text-transform: uppercase;
  margin-bottom: 4px;
}
.detail-value {
  font-size: 1rem;
  color: #1f2937;
  font-weight: 500;
}
.detail-value-expense {
  font-size: 1.3rem;
  color: var(--ion-color-danger, #eb445a);
  font-weight: 700;
}

ion-footer ion-toolbar {
  text-align: center;
}
ion-footer p {
  margin: 10px 0;
    font-size: 0.875rem;
    color: #666;
}
</style>