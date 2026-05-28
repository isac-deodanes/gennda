<template>
 <ion-page :class="themeClass">
    <ion-header>
      <ion-toolbar>
        <ion-buttons slot="start"> <ion-menu-button /> </ion-buttons>
        <ion-title>Agenda para el Mes</ion-title>
        <ion-buttons slot="primary">
            <ion-button @click="changeMonth('prev')" aria-label="Mes anterior"> <ion-icon slot="icon-only" :icon="chevronBackOutline"></ion-icon> </ion-button>
            <ion-button @click="changeMonth('next')" aria-label="Mes siguiente"> <ion-icon slot="icon-only" :icon="chevronForwardOutline"></ion-icon> </ion-button>
        </ion-buttons>
        <ion-buttons slot="end">
          <ion-searchbar placeholder="Buscar..." :debounce="300" style="max-width: 250px;" v-model="searchQuery"></ion-searchbar>
          <ion-button fill="clear" aria-label="Ver Eventos" @click="isSidebarVisible = true" class="ion-hide-lg-up"> <ion-icon :icon="listOutline"></ion-icon> </ion-button> 
          <ion-button shape="round" color="primary" aria-label="Perfil" @click="goToProfile"> <ion-icon :icon="personOutline"></ion-icon> </ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>

    <ion-content class="calendar-page">
      <div class="content-wrapper">
        <div class="main-calendar-area">
          <ion-card class="calendar-card">
            <ion-card-header>
              <div class="calendar-header-content">
                <div class="header-left">
                  <div></div>
                  <div></div>
                  <ion-card-title class="month-title">{{ currentMonthName }}</ion-card-title>
                  
                  <div class="categories-container">
                    <ion-chip 
                      v-for="category in eventCategories" 
                      :key="category.id"
                      :style="{ '--background': category.color }"
                      class="category-chip"
                    >
                      <div class="category-dot" :style="{ backgroundColor: category.color }"></div>
                      <ion-label>{{ category.nombre }}</ion-label>
                      
                      <ion-icon 
                        :icon="pencilOutline"
                        class="action-category-icon"
                        @click.stop="openCategoriaModal(category)"
                      ></ion-icon>
                      <ion-icon 
                        :icon="closeCircleOutline" 
                        class="action-category-icon delete"
                        @click.stop="confirmDeleteCategoria(category)"
                      ></ion-icon>
                    </ion-chip>
                    
                    <ion-button 
                      fill="clear" 
                      size="small" 
                      aria-label="Añadir categoría" 
                      @click="openCategoriaModal()"
                    >
                      <ion-icon :icon="addOutline"></ion-icon>
                    </ion-button>
                    </div>
                </div>
                <div class="balance-section">
                  <ion-text color="medium"> <p class="balance-label">Balance actual</p> </ion-text>
                  <ion-text color="dark"> <h2 class="balance-amount">{{ currentBalance }}</h2> </ion-text>
                  <ion-text color="medium"> <p class="balance-label" style="margin-top: 8px;">Gasto Estimado ({{ currentMonthName }})</p> </ion-text>
                  <ion-text color="danger"> <h2 class="balance-amount">{{ totalGastoEstimadoMes }}</h2> </ion-text>
                </div>
              </div>
            </ion-card-header>
            
            <ion-card-content class="calendar-content">
              <ion-grid class="calendar-grid">
                <ion-row class="week-days-row">
                  <ion-col v-for="(day, idx) in weekDays" :key="idx" class="week-day-col">
                    <ion-text color="medium"> <small>{{ day }}</small> </ion-text>
                  </ion-col>
                </ion-row>
                <ion-row v-for="(week, weekIdx) in weeksArray" :key="weekIdx" class="days-row">
                  <ion-col
                    v-for="(dayData, dayIdx) in week"
                    :key="dayIdx"
                    class="day-col"
                    :class="{
                      'day-empty': !dayData.day,
                      'day-active': dayData.day,
                      'day-today': dayData.isToday,
                      'day-highlight': dayData.day && parseInt(dayData.day, 10) === selectedDay
                    }"
                    @click="selectDay(dayData)"
                  >
                    <div v-if="dayData.day" class="day-content">
                      <ion-text color="dark" class="day-number"> <strong>{{ dayData.day }}</strong> </ion-text>
                      <div class="events-list">
                        <ion-chip
                          v-for="(event, eventIdx) in dayData.events"
                          :key="eventIdx"
                          class="event-chip"
                          :style="{ backgroundColor: event.color, color: event.textColor || '#1F2937' }"
                        >
                          <ion-label class="event-label">{{ event.title }}</ion-label>
                        </ion-chip>
                      </div>
                    </div>
                  </ion-col>
                </ion-row>
              </ion-grid>
            </ion-card-content>
          </ion-card>
        </div>

        <div class="events-sidebar" :class="{ 'is-visible': isSidebarVisible }">
          <div class="events-header">
            <ion-text color="light">
              <h2>Eventos</h2>
              <p>{{ selectedDayTitle }}</p>
            </ion-text>
            <ion-button fill="clear" class="close-sidebar-btn ion-hide-lg-up" @click="isSidebarVisible = false">
              <ion-icon :icon="closeOutline" color="light"></ion-icon>
            </ion-button>
          </div>

          <div class="events-list-container">
            <ion-card
              v-for="(event, idx) in upcomingEvents"
              :key="idx"
              class="event-card"
              :style="{ backgroundColor: event.color }"
            >
              <ion-card-content>
                <div class="event-card-header">
                  <div>
                    <ion-text color="light"> <p class="event-date">{{ event.date }}</p> </ion-text>
                    <ion-text color="light"> <h3 class="event-time">{{ event.time }}</h3> </ion-text>
                  </div>
                  <div class="event-card-buttons">
                    <ion-button 
                      fill="clear" 
                      class="event-action-btn" 
                      aria-label="Editar evento"
                      @click="openEventoModal(event)"
                    >
                      <ion-icon :icon="pencilOutline" color="light"></ion-icon>
                    </ion-button>
                    <ion-button 
                      fill="clear" 
                      class="event-action-btn" 
                      aria-label="Borrar evento"
                      @click="confirmDeleteEvento(event.id)"
                    >
                      <ion-icon :icon="trashOutline" color="light"></ion-icon>
                    </ion-button>
                  </div>
                </div>
                
                <ion-text color="light">
                  <h4 class="event-title">{{ event.title }}</h4>
                  <p class="event-subtitle">{{ event.subtitle }}</p>
                  <p class="event-description">{{ event.description }}</p>
                </ion-text>
              </ion-card-content>
            </ion-card>
          </div>

          <ion-button 
            expand="block" 
            shape="round" 
            color="light" 
            class="add-event-btn"
            @click="openEventoModal()"
            aria-label="Añadir evento"
          >
            <ion-icon :icon="addOutline" slot="icon-only"></ion-icon>
          </ion-button>
        </div>
      </div>
    </ion-content>
    
    <ion-footer>
      <ion-toolbar>
        <p>© 2025 Gennda - Todos los derechos reservados</p>
      </ion-toolbar>
    </ion-footer>
  </ion-page>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import {
  IonPage,
  IonHeader,
  IonToolbar,
  IonTitle,
  IonContent,
  IonButtons,
  IonButton,
  IonIcon,
  IonSearchbar,
  IonCard,
  IonCardHeader,
  IonCardTitle,
  IonCardContent,
  IonGrid,
  IonRow,
  IonCol,
  IonChip,
  IonLabel,
  IonText,
  IonMenuButton,
  IonFooter,
  modalController, 
  toastController,
  alertController  // <-- Importado
} from '@ionic/vue';
import {
  calendarOutline,
  calendarNumberOutline,
  personOutline,
  addOutline,
  peopleOutline,
  chevronBackOutline,
  chevronForwardOutline,
  listOutline,    
  closeOutline,
  closeCircleOutline, // <-- Importado
  trashOutline,       // <-- Importado
  pencilOutline       // <-- Importado
} from 'ionicons/icons';
import axios from 'axios';
import EventoModal from '@/components/EventoModal.vue'; 
import CategoriaModal from '@/components/CategoriaModal.vue'; 

const router = useRouter();

// --- 1. CONFIGURACIÓN DE API ---
const API_URL = 'http://127.0.0.1:8000/api';
const authToken = localStorage.getItem('auth_token');
const apiClient = axios.create({
  baseURL: API_URL,
  headers: {
    'Authorization': `Bearer ${authToken}`,
    'Accept': 'application/json'
  }
});

// --- 2. INTERFACES DE DATOS ---
interface Categoria { id: number; nombre: string; color: string; }
// ¡Corregido! categoria_id debe ser parte de la interfaz base
interface Evento { id: number; titulo: string; descripcion: string; fecha: string; hora: string; gasto_estimado: string; categoria_id: number | null; categoria: Categoria; }
interface CalendarEvent { title: string; color: string; textColor: string; }
interface DayData { day: string; date: string; isToday: boolean; events: CalendarEvent[]; }
interface UpcomingEvent { 
  id: number;
  date: string; 
  time: string; 
  title: string; 
  subtitle: string; 
  description: string; 
  color: string; 
}

// --- 3. ESTADO REACTIVO (REFS) ---
const isLoading = ref(true);
const currentDate = ref(new Date());
const selectedDay = ref<number>(new Date().getDate());
const user = ref<any>(null);
const eventCategories = ref<Categoria[]>([]);
const allEventsForMonth = ref<Evento[]>([]);
const weekDays = ['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE', 'SÁB', 'DOM'];
const searchQuery = ref('');
const isSidebarVisible = ref(window.innerWidth > 1024);

// configuracion de estilo para temas segun mes
const themeClass = computed(() => {
  const month = currentDate.value.getMonth(); // getMonth() devuelve 0-11
  
  switch (month) {
    case 1: // Febrero
      return 'theme-february';
    case 4: // Mayo
      return 'theme-may';
    case 9: // Octubre (Halloween)
      return 'theme-october';
    case 11: // Diciembre (Navidad)
      return 'theme-december';
    case 8: // Septiembre (Independencia)
      return 'theme-september';
    
    
    
    
    default: // Tema por defecto (el azul que ya tienes)
      return 'theme-default';
  }
});

// --- 4. FUNCIONES DE API ---
const fetchUserData = async () => {
  try {
    const response = await apiClient.get('/user');
    user.value = response.data;
  } catch (error) {
    console.error('Error al cargar datos del usuario:', error);
  }
};
const fetchCategories = async () => {
  try {
    const response = await apiClient.get('/categorias');
    eventCategories.value = response.data;
  } catch (error) {
    console.error('Error al cargar categorías:', error);
  }
};
const fetchEventsForMonth = async () => {
  isLoading.value = true;
  try {
    const mes = currentDate.value.getMonth() + 1;
    const ano = currentDate.value.getFullYear();
    const response = await apiClient.get('/eventos', {
      params: { mes, ano }
    });
    allEventsForMonth.value = response.data;
  } catch (error) {
    console.error('Error al cargar eventos:', error);
  } finally {
    isLoading.value = false;
  }
};

// --- 5. LÓGICA DE INTERACCIÓN ---
onMounted(() => {
  selectedDay.value = new Date().getDate();
  fetchUserData();
  fetchCategories();
  fetchEventsForMonth();
});
const changeMonth = (direction: 'prev' | 'next') => {
  const newDate = new Date(currentDate.value);
  const newMonth = newDate.getMonth() + (direction === 'prev' ? -1 : 1);
  newDate.setMonth(newMonth, 1);
  currentDate.value = newDate;
  
  const today = new Date();
  if (newDate.getMonth() === today.getMonth() && newDate.getFullYear() === today.getFullYear()) {
    selectedDay.value = today.getDate();
  } else {
    selectedDay.value = 1;
  }
  fetchEventsForMonth();
};
const selectDay = (day: DayData) => {
  if (day.day) {
    selectedDay.value = parseInt(day.day, 10);
    if (window.innerWidth <= 1024) {
      isSidebarVisible.value = true;
    }
  }
};

// --- FUNCIONES DE MODALES (MODIFICADAS) ---

// Modal para crear/editar EVENTO
const openEventoModal = async (event: UpcomingEvent | null = null) => {
  let eventToEdit: Evento | null = null;
  if (event) {
    eventToEdit = allEventsForMonth.value.find(e => e.id === event.id) || null;
  }

  const date = new Date(currentDate.value);
  date.setDate(selectedDay.value);
  date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
  const selectedDateStr = date.toISOString().split('T')[0];
  
  const modal = await modalController.create({
    component: EventoModal,
    componentProps: {
      selectedDate: selectedDateStr,
      categories: eventCategories.value,
      eventoToEdit: eventToEdit
    },
  });
  await modal.present();
  const { data, role } = await modal.onWillDismiss();

  if (role === 'confirm' && data) {
    const index = allEventsForMonth.value.findIndex(e => e.id === data.id);
    if (index > -1) {
      allEventsForMonth.value[index] = data;
      toastController.create({ message: 'Evento actualizado.', duration: 2000, color: 'success' }).then(t => t.present());
    } else {
      allEventsForMonth.value.push(data);
      toastController.create({ message: 'Evento guardado.', duration: 2000, color: 'success' }).then(t => t.present());
    }
  }
};

// Modal para crear/editar CATEGORÍA
const openCategoriaModal = async (category: Categoria | null = null) => {
  const modal = await modalController.create({
    component: CategoriaModal,
    componentProps: {
      categoriaToEdit: category
    },
    initialBreakpoint: 0.5,
    breakpoints: [0, 0.5]
  });
  await modal.present();

  const { data, role } = await modal.onWillDismiss();

  if (role === 'confirm' && data) {
    const index = eventCategories.value.findIndex(c => c.id === data.id);
    if (index > -1) {
      eventCategories.value[index] = data;
      fetchEventsForMonth(); 
      toastController.create({ message: 'Categoría actualizada.', duration: 2000, color: 'success' }).then(t => t.present());
    } else {
      eventCategories.value.push(data);
      toastController.create({ message: 'Categoría creada.', duration: 2000, color: 'success' }).then(t => t.present());
    }
  }
};

// --- FUNCIÓN DE NAVEGACIÓN ---
const goToProfile = () => {
  router.push('/perfil');
};


// --- FUNCIONES DE BORRADO ---

const confirmDeleteEvento = async (eventoId: number) => {
  const alert = await alertController.create({
    header: 'Confirmar',
    message: '¿Estás seguro de que quieres borrar este evento?',
    buttons: [
      { text: 'Cancelar', role: 'cancel' },
      { text: 'Borrar', role: 'confirm', handler: () => deleteEvento(eventoId) },
    ],
  });
  await alert.present();
};

const deleteEvento = async (eventoId: number) => {
  try {
    await apiClient.delete(`/eventos/${eventoId}`);
    allEventsForMonth.value = allEventsForMonth.value.filter(e => e.id !== eventoId);
    const toast = await toastController.create({ message: 'Evento borrado.', duration: 2000, color: 'success' });
    await toast.present();
  } catch (error) {
    console.error('Error al borrar evento:', error);
  }
};

const confirmDeleteCategoria = async (category: Categoria) => {
  const alert = await alertController.create({
    header: 'Confirmar',
    message: `¿Borrar categoría "${category.nombre}"? Los eventos asociados perderán esta categoría.`,
    buttons: [
      { text: 'Cancelar', role: 'cancel' },
      { text: 'Borrar', role: 'confirm', handler: () => deleteCategoria(category) },
    ],
  });
  await alert.present();
};

const deleteCategoria = async (category: Categoria) => {
  try {
    await apiClient.delete(`/categorias/${category.id}`);
    eventCategories.value = eventCategories.value.filter(c => c.id !== category.id);
    await fetchEventsForMonth();
    const toast = await toastController.create({ message: 'Categoría borrada.', duration: 2000, color: 'success' });
    await toast.present();
  } catch (error) {
    console.error('Error al borrar categoría:', error);
  }
};


// --- 6. PROPIEDADES COMPUTADAS ---

// --- ¡LÓGICA RESTAURADA! ---
const filteredEvents = computed<Evento[]>(() => {
  const query = searchQuery.value.toLowerCase().trim();
  if (!query) { return allEventsForMonth.value; }
  return allEventsForMonth.value.filter(event => {
    const titleMatch = event.titulo.toLowerCase().includes(query);
    const categoryMatch = event.categoria?.nombre.toLowerCase().includes(query);
    return titleMatch || categoryMatch;
  });
});

const currentMonthName = computed(() => {
  return currentDate.value.toLocaleString('es-ES', { month: 'long' }).toUpperCase();
});

const currentBalance = computed(() => {
  if (!user.value || !user.value.balance_actual) { return '$0.00'; }
  return parseFloat(user.value.balance_actual).toLocaleString('es-ES', {
    style: 'currency',
    currency: 'USD'
  });
});

const totalGastoEstimadoMes = computed(() => {
  const total = filteredEvents.value.reduce((sum, event) => {
    return sum + parseFloat(event.gasto_estimado || '0');
  }, 0);
  return total.toLocaleString('es-ES', {
    style: 'currency',
    currency: 'USD'
  });
});

const selectedDayTitle = computed(() => {
  const date = new Date(currentDate.value);
  date.setDate(selectedDay.value);
  return date.toLocaleString('es-ES', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  });
});

// --- ¡LÓGICA RESTAURADA! ---
const weeksArray = computed<DayData[][]>(() => {
  const weeks: DayData[][] = [];
  const year = currentDate.value.getFullYear();
  const month = currentDate.value.getMonth();
  const firstDayOfMonth = new Date(year, month, 1);
  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const startDayOfWeek = (firstDayOfMonth.getDay() + 6) % 7; 
  let flatDays: DayData[] = [];
  const today = new Date();
  
  for (let i = 0; i < startDayOfWeek; i++) {
    flatDays.push({ day: '', date: '', isToday: false, events: [] });
  }
  for (let d = 1; d <= daysInMonth; d++) {
    const fullDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(d).padStart(2, '0')}`;
    const isToday = d === today.getDate() && 
                    month === today.getMonth() && 
                    year === today.getFullYear();
    const eventsOnThisDay: CalendarEvent[] = filteredEvents.value
      .filter(event => event.fecha === fullDate)
      .map(event => ({
        title: event.titulo,
        color: event.categoria?.color || '#D1D5DB',
        textColor: '#1F2937'
      }));
    flatDays.push({
      day: String(d),
      date: fullDate,
      isToday: isToday,
      events: eventsOnThisDay
    });
  }
  let week: DayData[] = [];
  flatDays.forEach(day => {
    week.push(day);
    if (week.length === 7) {
      weeks.push(week);
      week = [];
    }
  });
  if (week.length > 0) {
    while (week.length < 7) {
      week.push({ day: '', date: '', isToday: false, events: [] });
    }
    weeks.push(week);
  }
  return weeks;
});

// --- ¡LÓGICA RESTAURADA! ---
const upcomingEvents = computed<UpcomingEvent[]>(() => {
  return filteredEvents.value
    .filter(event => {
      const eventDay = new Date(event.fecha + 'T12:00:00').getDate();
      return eventDay === selectedDay.value;
    })
    .map(event => {
      let time = '';
      if (event.hora) {
        const [h, m] = event.hora.split(':');
        const d = new Date();
        d.setHours(parseInt(h), parseInt(m));
        time = d.toLocaleString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
      }
      return {
        id: event.id,
        date: selectedDayTitle.value,
        time: time,
        title: event.titulo,
        subtitle: event.categoria?.nombre || 'Sin Categoría',
        description: event.descripcion || '',
        color: event.categoria?.color || '#D1D5DB'
      };
    });
});
</script>

<style scoped>
/* (Estilos de layout responsivo sin cambios) */
.calendar-page { --background: linear-gradient(135deg, #A5F3FC 0%, #67E8F9 100%); }
.content-wrapper { display: flex; flex-direction: row; height: 100%; padding: 16px; gap: 16px; position: relative; overflow-x: hidden; }
.main-calendar-area { 
  flex: 1; 
  min-width: 0;
  position: relative; 
  z-index: 10; }
.calendar-card { 
  background: linear-gradient(135deg, #DBEAFE 0%, #E9D5FF 100%);
  border: 4px solid #A78BFA; 
  border-radius: 24px; 
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.15);
  position: relative;
  overflow: hidden; }
.calendar-header-content { 
  display: flex;
  justify-content: space-between;
  align-items: flex-start; 
  flex-wrap: wrap; 
  gap: 16px;
  position: relative; }
.header-left { display: flex; align-items: center; gap: 24px; flex-wrap: wrap; }
.month-title { font-size: 28px; font-weight: bold; color: #1E3A8A; letter-spacing: 4px; margin: 0; }
.categories-container { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }

.category-chip { 
  height: 28px; 
  font-size: 11px; 
  position: relative;
  padding-right: 48px; 
}
.category-dot { width: 12px; height: 12px; border-radius: 50%; margin-right: 4px; }
.action-category-icon {
  position: absolute;
  top: 5px;
  font-size: 18px;
  color: rgba(0,0,0,0.4);
  cursor: pointer;
}
.action-category-icon:hover {
  color: rgba(0,0,0,0.8);
}
.action-category-icon.delete {
  right: 5px;
}
.action-category-icon:not(.delete) {
  right: 25px; 
}
.balance-section { text-align: right; flex-shrink: 0; }
.balance-label { font-size: 12px; margin: 0 0 4px 0; }
.balance-amount { font-size: 22px; font-weight: bold; margin: 0; }
.calendar-content { background: white; border-radius: 16px; padding: 16px; }
.calendar-grid { padding: 0; width: 100%; }
.week-days-row { margin-bottom: 12px; }
.week-day-col { text-align: center; padding: 8px 4px; }
.week-day-col small { font-size: 12px; font-weight: 700; text-transform: uppercase; }
.days-row { margin-bottom: 8px; }
.day-col { min-height: 120px; padding: 8px; border-radius: 8px; cursor: pointer; transition: all 0.2s ease-in-out; }
.day-empty { background: transparent; cursor: default; }
.day-active { background: #F9FAFB; border: 1px solid #E5E7EB; }
.day-active:hover { background: #F3F4F6; }
.day-today { background: #F0F9FF; border: 1px solid #7DD3FC; }
.day-highlight { border: 2px solid #A78BFA !important; box-shadow: 0 0 0 2px rgba(167, 139, 250, 0.3); background: #F3E8FF !important; }
.day-content { height: 100%; display: flex; flex-direction: column; gap: 4px; }
.day-number { font-size: 16px; font-weight: bold; margin-bottom: 6px; display: block; color: #1F2937; }
.events-list { display: flex; flex-direction: column; gap: 3px; overflow: hidden; }
.event-chip { height: auto; min-height: 24px; margin: 0; padding: 4px 8px; border-radius: 6px; }
.event-label { font-size: 10px !important; line-height: 1.3; margin: 0; white-space: normal; font-weight: 500; }

.events-sidebar {
  background: linear-gradient(135deg, #4babb8 0%, #22D3EE 100%);
  border-radius: 16px;
  padding: 20px;
  display: flex;
  flex-direction: column;
  position: absolute;
  top: 16px;
  bottom: 16px;
  right: 16px;
  width: 320px;
  max-width: 90%;
  z-index: 1000;
  transform: translateX(110%);
  transition: transform 0.3s ease-in-out;
  box-shadow: -5px 0 15px rgba(0,0,0,0.2);
  overflow: hidden;
}
.events-sidebar.is-visible {
  transform: translateX(0);
}
@media (min-width: 1024px) {
  .events-sidebar {
    position: relative;
    top: auto;
    bottom: auto;
    right: auto;
    width: 320px;
    max-width: 320px;
    flex-shrink: 0;
    transform: translateX(0);
    transition: none;
    box-shadow: none;
    height: 100%;
    z-index: 10;
  }
}
.events-header {
  margin-bottom: 20px;
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  position: relative; 
  z-index: 20;
}
.close-sidebar-btn {
  margin: -10px -10px 0 0;
}
.events-list-container {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 12px;
  overflow-y: auto;
}
.event-card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); margin: 0; }
.event-card ion-card-content { padding: 12px; }
.event-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 8px; }
.event-date { font-size: 11px; opacity: 0.9; margin: 0 0 4px 0; }
.event-time { font-size: 18px; font-weight: bold; margin: 0; }

.event-card-buttons {
  display: flex;
  gap: 0px;
}
.event-action-btn { --background: rgba(255, 255, 255, 0.2); --border-radius: 50%; margin: 0; height: 32px; width: 32px; }
.event-title { font-size: 13px; font-weight: 600; margin: 0 0 4px 0; }
.event-subtitle { font-size: 11px; opacity: 0.9; margin: 0 0 6px 0; }
.event-description { font-size: 11px; opacity: 0.85; margin: 0; }
.add-event-btn { margin-top: 16px; --background: white; --color: #22D3EE; font-size: 28px; height: 48px; flex-shrink: 0; }
ion-searchbar { --background: white; --border-radius: 8px; padding: 4px 8px; }
ion-chip { --background: #E5E7EB; }
ion-footer ion-toolbar { text-align: center; }
ion-footer p { margin: 10px 0; font-size: 0.875rem; color: #666; }
.card-content-md { -webkit-padding-start: 16px; padding-inline-start: 16px; -webkit-padding-end: 16px; padding-inline-end: 16px; padding-top: 13px; padding-bottom: 13px; font-size: 0.875rem; line-height: 1.5; }

/*
|--------------------------------------------------------------------------
| --- TEMAS DE TEMPORADA 
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| --- Febrero ---
|--------------------------------------------------------------------------
*/

.theme-february .calendar-page {
  /* Un fondo rosa muy pálido */
  --background: linear-gradient(135deg, #FFF5F7 0%, #FFE4E1 100%);
}
.theme-february .calendar-card {
  /* Borde rojo pasión */
  background: #FFFFFF;
  border: 4px solid #E53E3E; 
}
.theme-february .events-sidebar {
  /* Sidebar en un rosa fuerte */
  background: linear-gradient(135deg, #D53F8C 0%, #B83280 100%);
}

/* --- Ajustes de Texto --- */
.theme-february .month-title {
  color: #B83280; /* Título "FEBRERO" en rosa */
}
.theme-february .balance-label {
  color: #555;
}
.theme-february .balance-amount {
  color: #B83280 !important; /* Montos en rosa */
}
.theme-february .week-day-col small {
  color: #B83280; /* Días "LUN, MAR..." en rosa */
}
.theme-february .day-today {
  border: 1px solid #F56565; /* Hoy borde rojo claro */
}
.theme-february .day-highlight {
  border: 2px solid #D53F8C !important; /* Selección en rosa */
  background: #FFF5F7 !important;
}

/* --- Decoraciones --- */

/* 1. Chocolates */
.theme-february .events-sidebar::after {
  content: '';
  position: absolute;
  /* Misma posición que el Torogoz */
  left: 180px;
  width: 120px;
  height: 120px;
  background-image: url('/february/chocolates.png');
  background-repeat: no-repeat;
  background-size: contain;
  pointer-events: none;
  z-index: 25;
  opacity: 1;
}

/* 2. Corazones */
.theme-february .calendar-card::after {
  content: '';
  position: absolute;
  bottom: 10px;
  right: 15px;
  width: 120px;
  height: 120px;
  background-image: url('/february/hearts.png');
  background-repeat: no-repeat;
  background-size: contain;
  pointer-events: none;
  z-index: 25; 


}

/*
|--------------------------------------------------------------------------
| --- MAYO---
|--------------------------------------------------------------------------
*/

.theme-may .calendar-page {
  --background: linear-gradient(135deg, #FFF0F5 0%, #FFC0CB 100%);
}
.theme-may .calendar-card {
  background: #ffffff;
  border: 4px solid #F472B6;
}
.theme-may .events-sidebar {
  background: linear-gradient(135deg, #F9A8D4 0%, #F472B6 100%);
}
/*
|--------------------------------------------------------------------------
| ---  Septiembre ---
|--------------------------------------------------------------------------
*/
.theme-september .calendar-page {
  --background: #FFFFFF;
}
.theme-september .calendar-card {
  background: #FFFFFF;
  border: 4px solid #0047AB;
}
.theme-september .events-sidebar {
  background: linear-gradient(135deg, #0047AB 0%, #003A8C 100%);
}
.theme-september .month-title {
  color: #0047AB;
}
.theme-september .balance-label {
  color: #555;
}
.theme-september .balance-amount {
  color: #0047AB !important;
}
.theme-september .week-day-col small {
  color: #0047AB;
}
.theme-september .day-today {
  border: 1px solid #0047AB;
}
.theme-september .day-highlight {
  border: 2px solid #0047AB !important;
  background: #E0EFFF !important;
}

/* --- torogoz --- */
.theme-september .events-sidebar::after {
  content: '';
  position: absolute;
  left: 200px;
  width: 120px;
  height: 120px;
  background-image: url('/september/torogoz.png');
  background-repeat: no-repeat;
  background-size: contain;
  pointer-events: none;
  z-index: 25;
  opacity: 1;
  transform: scaleX(-1);
}
/*
|--------------------------------------------------------------------------
| --- Octubre ---
|--------------------------------------------------------------------------
*/

.theme-october .events-sidebar::after {
  content: '';
  position: absolute;
  left: 200px;
  width: 120px;
  height: 120px;
  background-image: url('/halloween/bats.png'); 
  background-repeat: no-repeat;
  background-size: contain;
  pointer-events: none;
  z-index: 25;
  opacity: 0.9;
}
.theme-october .calendar-page {
  --background: linear-gradient(135deg, #1A1A1A 0%, #333333 100%);
}
.theme-october .calendar-card {
  background: linear-gradient(135deg, #4A0404 0%, #3D0303 100%);
  border: 4px solid #FF6600;
  color: #E0E0E0;
}
.theme-october .calendar-content {
  background: #2a2a2a;
}
.theme-october .day-number {
  color: #E0E0E0 !important;
}
.theme-october .day-active {
  background: #333;
  border: 1px solid #555;
}
.theme-october .day-active:hover { background: #444; }
.theme-october .day-today { border: 1px solid #FF6600; }
.theme-october .day-highlight { border: 2px solid #FF6600 !important; background: #553311 !important; }

.theme-october .events-sidebar {
  background: linear-gradient(135deg, #E65C00 0%, #D95400 100%);
}
.theme-october .calendar-card::after {
  content: '';
  position: absolute;
  bottom: 10px;
  right: 15px;
  width: 120px;
  height: 120px;
  background-image: url('/halloween/pumpkin.png'); 
  background-repeat: no-repeat;
  background-size: contain;
  pointer-events: none;
  z-index: 25; 
}

.theme-october .month-title {
  color: #ffffff;
}
.theme-october .balance-label {
  color: #E0E0E0;
}
.theme-october .balance-amount {
  color: #ffffff !important;
}

/*
|--------------------------------------------------------------------------
| --- Diciembre ---
|--------------------------------------------------------------------------
*/
.theme-december .calendar-page {
  --background: #f4f4f4;
}
.theme-december .calendar-card {
  background: #ffffff;
  border: 4px solid #D90429;
}
.theme-december .events-sidebar {
  background: linear-gradient(135deg, #2E7D32 0%, #1B5E20 100%);
}

.theme-december .calendar-header-content::before {
  content: '';
  position: absolute;
  top: -40px;
  left: -25px;    
  width: 200px;
  height: 200px;
  background-image: url('/christmas/mistletoe.png'); /* ¡RUTA CORREGIDA! */
  background-repeat: no-repeat;
  background-size: contain;
  z-index: 50;
  pointer-events: none;
}
.theme-december .events-header::after {
  content: '';
  position: absolute;
  top: -30px;
  right: -10px;
  width: 100%;
  height: 100px;
  background-image: url('/christmas/lights.png'); /* ¡RUTA CORREGIDA! */
  background-size: 150px;
  background-repeat: repeat-x;
  opacity: 0.9;
  pointer-events: none;
}
</style>