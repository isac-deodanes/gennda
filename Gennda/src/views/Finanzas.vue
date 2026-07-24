<template>
  <ion-page>
    <ion-header>
      <!-- ... (Header sin cambios) ... -->
      <ion-toolbar>
        <ion-buttons slot="start">
          <ion-menu-button />
        </ion-buttons>
        <ion-title>Finanzas</ion-title>
        <ion-buttons slot="end">
          <ion-searchbar placeholder="Gastos del mes" :debounce="300" style="max-width: 200px;"></ion-searchbar>
          <ion-button fill="clear" @click="showNotifications">
            <ion-icon :icon="notifications" color="primary"></ion-icon>
          </ion-button>
          <ion-button fill="outline" size="small" @click="openParametersModal" class="ion-hide-md-down">
             <ion-icon :icon="settingsOutline" slot="start"></ion-icon>
             Parámetros
          </ion-button>
          <ion-button shape="round" color="primary" @click="goToProfile">
            <ion-icon :icon="personOutline"></ion-icon>
          </ion-button>
        </ion-buttons>
      </ion-toolbar>
    </ion-header>

    <ion-content class="finance-page">
      <div class="content-wrapper">
        <!-- ... (Contenido principal: Balance, Summary Cards, etc. sin cambios) ... -->
        
        <!-- Main Finance Area -->
        <div class="main-finance-area">

          <!-- Balance Section -->
          <div class="balance-section">
            <ion-text color="light">
              <p class="balance-label">Balance actual</p>
            </ion-text>
            <ion-text color="light">
              <h1 class="balance-amount">{{ currentBalance }}</h1>
            </ion-text>
            <div class="income-input-group" style="margin-top:16px; display:flex; gap:8px; align-items:center;">
              <ion-input type="number" v-model="balanceInput" placeholder="Actualizar balance"></ion-input>
              <ion-button size="small" color="light" @click="saveBalanceActual">Guardar</ion-button>
            </div>
          </div>

          <!-- Summary Cards -->
          <div class="summary-section">
            <ion-card class="summary-card income-card">
              <ion-card-content>
                <ion-text color="light">
                  <p class="summary-label">Ingreso mensual</p>
                  <h2 class="summary-amount">{{ monthlyIncome }}</h2>
                  <p class="summary-sublabel">{{ incomeDetails }}</p>
                </ion-text>
                <div class="income-input-group" style="margin-top:8px; display:flex; gap:8px; align-items:center;">
                  <ion-input type="number" v-model="incomeInput" placeholder="Saldo Mensual"></ion-input>
                  <ion-button size="small" color="light" @click="saveMonthlyIncome">Guardar</ion-button>
                </div>
              </ion-card-content>
            </ion-card>

            <ion-card class="summary-card expense-card">
              <ion-card-content>
                <ion-text color="light">
                  <p class="summary-label">Gasto Planificado (Este Mes)</p>
                  <h2 class="summary-amount">{{ monthlyExpense }}</h2>
                  <p class="summary-sublabel">{{ expenseDetails }}</p>
                </ion-text>
                 <div style="margin-top: 50px;"></div>
              </ion-card-content>
            </ion-card>

            <ion-card class="summary-card projection-card">
              <ion-card-content>
                <ion-text color="light">
                  <p class="summary-label">Proyección Fin de Mes</p>
                  <h2 class="summary-amount" :style="{ color: projectedBalanceColor }">
                    {{ formattedProjectedBalance }}
                  </h2>
                  <p class="summary-sublabel">Ingreso - Gastos Planificados</p>
                </ion-text>
                <ion-text :color="projectedBalance > 0 ? 'success' : 'danger'" style="margin-top: 16px; display: block;">
                  <p style="font-size: 12px; font-weight: 500;">
                    {{ projectedBalance > 0 ? '¡Vas por buen camino! Terminarás el mes con superávit.' : '¡Cuidado! Tus gastos planificados superan tu ingreso.' }}
                  </p>
                </ion-text>
              </ion-card-content>
            </ion-card>
          </div>

          <!-- Transactions Grid -->
          <div class="transactions-header">
            <ion-text>
              <h3>Detalles de Desembolso (Este Mes)</h3>
            </ion-text>
            <ion-text color="medium">
              <p class="transactions-subtitle">Eventos con gastos estimados</p>
            </ion-text>
            <ion-button fill="clear" size="small">
              <ion-icon :icon="ellipsisHorizontal"></ion-icon>
            </ion-button>
          </div>

          <ion-grid class="transactions-grid">
            <ion-row>
              <ion-col size="12" v-if="transactions.length === 0">
                 <ion-card class="transaction-card-empty">
                  <ion-card-content style="text-align: center; padding: 32px;">
                    <ion-icon :icon="calendarClearOutline" style="font-size: 48px; color: #5B21B6;" />
                    <ion-text>
                       <h3 style="font-weight: 600; color: #333;">Sin Gastos Planificados</h3>
                       <p style="color: #666;">Añade un evento en tu calendario con un "gasto estimado" para verlo aquí.</p>
                    </ion-text>
                  </ion-card-content>
                 </ion-card>
              </ion-col>

              <ion-col size="12" size-md="6" v-for="transaction in transactions" :key="transaction.id">
                <ion-card class="transaction-card" :class="transaction.className">
                  <ion-card-content>
                    <div class="transaction-content">
                      <div class="transaction-info">
                        <ion-text color="light">
                          <h3 class="transaction-amount">{{ transaction.amount }}</h3>
                          <p class="transaction-description">{{ transaction.titulo }}</p>
                        </ion-text>
                      </div>
                      <ion-icon :icon="lockClosed" color="light" class="transaction-icon"></ion-icon>
                    </div>
                    <!-- El botón ahora solo llama a la función simple -->
                    <ion-button expand="block" fill="clear" color="light" size="small" class="detail-btn" @click="showEventDetails(transaction)">
                      Ver detalle del evento
                    </ion-button>
                  </ion-card-content>
                </ion-card>
              </ion-col>
            </ion-row>
          </ion-grid>
        </div>

        <!-- Right Sidebar - History (Sin cambios) -->
        <div class="history-sidebar">
          <div class="history-header">
            <ion-text>
              <h3>Historial de Gastos</h3>
            </ion-text>
            <ion-button fill="clear" size="small">
              <ion-icon :icon="ellipsisHorizontal"></ion-icon>
            </ion-button>
          </div>
          <div v-if="historyItems.length === 0" style="text-align: center; margin-top: 40px;">
             <ion-icon :icon="archiveOutline" style="font-size: 40px; color: white; opacity: 0.8;" />
             <ion-text color="light">
                <p style="opacity: 0.9;">No hay eventos pasados con gastos en tu historial.</p>
             </ion-text>
          </div>
          <div class="history-list">
            <ion-card v-for="(item, idx) in historyItems" :key="idx" class="history-card" :class="item.type">
              <ion-card-content>
                <div class="history-item-header">
                  <ion-icon :icon="lockClosed" color="light" class="history-icon"></ion-icon>
                  <ion-text color="light">
                    <h4 class="history-amount">{{ item.amount }}</h4>
                  </ion-text>
                </div>
                <ion-text color="light">
                  <p class="history-description">{{ item.description }}</p>
                  <p class="history-date">{{ item.date }}</p>
                </ion-text>
              </ion-card-content>
            </ion-card>
          </div>
        </div>
      </div>
    </ion-content>
    
    <ion-footer>
      <ion-toolbar>
        <p>© 2025 Gennda - Todos los derechos reservados</p>
      </ion-toolbar>
    </ion-footer>

    <!-- 
      ¡MODAL AÑADIDO!
      - Este es el nuevo modal de detalles del evento.
      - Se controla con `isModalOpen` y `selectedEvent`.
    -->
    <ion-modal :is-open="isModalOpen" @didDismiss="isModalOpen = false">
      <ion-header>
        <ion-toolbar color="primary">
          <!-- Usamos '?' (optional chaining) por si selectedEvent es null momentáneamente -->
          <ion-title>{{ selectedEvent?.titulo }}</ion-title>
          <ion-buttons slot="end">
            <ion-button @click="isModalOpen = false">Cerrar</ion-button>
          </ion-buttons>
        </ion-toolbar>
      </ion-header>
      <ion-content class="ion-padding">
        <ion-list lines="full" v-if="selectedEvent">
          <ion-item>
            <ion-label>
              <h2><strong>Gasto Estimado</strong></h2>
              <p style="color: var(--ion-color-danger); font-size: 1.2rem; font-weight: 600;">
                {{ formatCurrency(selectedEvent.gasto_estimado) }}
              </p>
            </ion-label>
          </ion-item>
          <ion-item>
            <ion-label>
              <h3><strong>Fecha y Hora</strong></h3>
              <p>{{ formattedEventDate }} a las {{ selectedEvent.hora || 'Hora no definida' }}</p>
            </ion-label>
          </ion-item>
          <ion-item>
            <ion-label>
              <h3><strong>Descripción</strong></h3>
              <p style="white-space: pre-wrap;">
                {{ selectedEvent.descripcion || 'No hay descripción para este evento.' }}
              </p>
            </ion-label>
          </ion-item>
        </ion-list>
      </ion-content>
    </ion-modal>

  </ion-page>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import {
  IonFooter, IonPage, IonHeader, IonToolbar, IonTitle, IonContent, IonButtons,
  IonButton, IonIcon, IonSearchbar, IonCard, IonCardContent, IonGrid,
  IonRow, IonCol, IonChip, IonLabel, IonText, IonMenuButton,
  IonInput,
  loadingController, toastController,
  popoverController,
  // --- ¡IMPORTS DE MODAL AÑADIDOS! ---
  IonModal,
  IonList,
  IonItem,
  // modalController fue eliminado
} from '@ionic/vue';
import {
  notifications,
  personOutline,
  lockClosed,
  ellipsisHorizontal,
  settingsOutline,
  calendarClearOutline,
  archiveOutline
} from 'ionicons/icons';
import axios from 'axios';
import { useRouter } from 'vue-router';

// --- 1. CONFIGURACIÓN DE API (Sin cambios) ---
const API_URL = 'http://127.0.0.1:8000/api';
const authToken = localStorage.getItem('auth_token');
const router = useRouter();

const apiClient = axios.create({
  baseURL: API_URL,
  headers: {
    'Authorization': `Bearer ${authToken}`,
    'Accept': 'application/json'
  }
});

// --- 2. ESTADO REACTIVO (REFS) ---
const user = ref(null);
const eventsThisMonth = ref([]);
const eventsPast = ref([]);
const incomeInput = ref('');
const balanceInput = ref('');

// --- ¡NUEVOS REFS PARA EL MODAL! ---
const isModalOpen = ref(false);
const selectedEvent = ref(null);

// --- 3. FUNCIONES DE API (Sin cambios) ---
onMounted(() => {
  fetchUserData();
  fetchEventsThisMonth();
  fetchPastEvents();
});

const fetchUserData = async () => {
  try {
    const response = await apiClient.get('/user');
    user.value = response.data;
  } catch (error) {
    console.error('Error al cargar datos del usuario:', error);
    if (error.response && error.response.status === 401) {
      localStorage.removeItem('auth_token');
      router.replace('/login');
    }
  }
};

const fetchEventsThisMonth = async () => {
  const today = new Date();
  const mes = today.getMonth() + 1;
  const ano = today.getFullYear();
  try {
    const response = await apiClient.get('/eventos', { params: { mes, ano } });
    eventsThisMonth.value = response.data.sort((a, b) => new Date(a.fecha) - new Date(b.fecha));
  } catch (error) {
    console.error('Error al cargar eventos del mes:', error);
  }
};

const fetchPastEvents = async () => {
  try {
    const [ev1, ev2, ev3] = await Promise.all([
      apiClient.get('/eventos', { params: getMonthYear(-1) }),
      apiClient.get('/eventos', { params: getMonthYear(-2) }),
      apiClient.get('/eventos', { params: getMonthYear(-3) })
    ]);
    const today = new Date().toISOString().split('T')[0];
    eventsPast.value = [...ev1.data, ...ev2.data, ...ev3.data]
      .filter(event => event.fecha < today)
      .sort((a, b) => new Date(b.fecha) - new Date(a.fecha));
  } catch (error) {
    console.error('Error al cargar historial de eventos:', error);
  }
};

const getMonthYear = (monthOffset) => {
  const date = new Date();
  date.setMonth(date.getMonth() + monthOffset);
  return { mes: date.getMonth() + 1, ano: date.getFullYear() };
};

// --- 4. FUNCIONES DE GUARDADO Y NAVEGACIÓN ---

const saveMonthlyIncome = async () => {
  const parsed = parseFloat(String(incomeInput.value));
  if (isNaN(parsed) || parsed < 0) {
     presentToast('Por favor, ingresa un número válido', 'danger');
     return;
  }
  await updateProfileData({ ingreso_mensual: parsed }, 'Ingreso mensual guardado');
};

const saveBalanceActual = async () => {
  const parsed = parseFloat(String(balanceInput.value));
  if (isNaN(parsed)) {
     presentToast('Por favor, ingresa un número válido', 'danger');
     return;
  }
  await updateProfileData({ balance_actual: parsed }, 'Balance actual guardado');
};

const updateProfileData = async (dataToUpdate, successMessage) => {
  const loading = await loadingController.create({ message: 'Guardando...' });
  await loading.present();
  try {
    const response = await apiClient.put('/perfil', dataToUpdate);
    user.value = response.data;
    if (dataToUpdate.hasOwnProperty('ingreso_mensual')) {
      incomeInput.value = '';
    }
    if (dataToUpdate.hasOwnProperty('balance_actual')) {
      balanceInput.value = '';
    }
    presentToast(successMessage, 'success');
  } catch (error) {
    console.error('Error al guardar datos:', error);
    presentToast('No se pudo guardar', 'danger');
  } finally {
    await loading.dismiss();
  }
};

const presentToast = async (message, color) => {
  const toast = await toastController.create({
    message: message,
    duration: 2000,
    color: color,
    position: 'top'
  });
  await toast.present();
};

const goToProfile = () => {
  router.push('/perfil');
};

// --- Notificaciones (Sin cambios) ---
const showNotifications = async (ev) => {
  const today = new Date();
  const upcomingEvents = eventsThisMonth.value.filter(e => {
    const eventDate = new Date(e.fecha + 'T00:00:00'); 
    const todayDate = new Date(today.getFullYear(), today.getMonth(), today.getDate());
    return eventDate >= todayDate;
  });
  const nextEvent = upcomingEvents[0]; 
  let notificationHTML = '';
  if (nextEvent) {
    const eventDate = new Date(nextEvent.fecha + 'T12:00:00').toLocaleDateString('es-ES', { day: 'numeric', month: 'long' });
    notificationHTML = `<ion-item :button="true"><ion-label><p><strong>Gasto Próximo</strong></p><p>Tu evento "${nextEvent.titulo}" es el ${eventDate}.</p></ion-label></ion-item>`;
  } else {
    notificationHTML = `<ion-item><ion-label><p>No tienes eventos próximos este mes.</p></ion-label></ion-item>`;
  }
  const popover = await popoverController.create({
    component: 'div',
    componentProps: {
      innerHTML: `<ion-list><ion-list-header>Notificaciones</ion-list-header>${notificationHTML}<ion-item lines="none"><ion-label color="medium" class="ion-text-center"><p style="font-size: 12px;">No hay más notificaciones</p></ion-label></ion-item></ion-list>`
    },
    event: ev,
    translucent: true,
    alignment: 'end',
    side: 'bottom'
  });
  await popover.present();
};

const openParametersModal = () => {
  presentToast('Función "Gestión de Parámetros" en desarrollo.', 'tertiary');
};


/**
 * ¡FUNCIÓN ACTUALIZADA!
 * Ahora solo actualiza los refs, no crea un modal.
 */
const showEventDetails = (event) => {
  selectedEvent.value = event;
  isModalOpen.value = true;
};


// --- 5. PROPIEDADES COMPUTADAS ---

const formatCurrency = (v) => {
  const n = Number(v);
  if (isNaN(n)) return '$0.00';
  return new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' }).format(n);
};

// ... (currentBalance, monthlyIncome, monthlyExpenseValue, monthlyExpense sin cambios) ...
const currentBalance = computed(() => {
  if (!user.value) return '$0.00';
  return formatCurrency(user.value.balance_actual);
});

const monthlyIncome = computed(() => {
  if (!user.value) return '$0.00';
  return formatCurrency(user.value.ingreso_mensual);
});
const incomeDetails = ref('Ingreso mensual configurado');

const monthlyExpenseValue = computed(() => {
  return eventsThisMonth.value.reduce((total, event) => {
    return total + parseFloat(event.gasto_estimado || '0');
  }, 0);
});
const monthlyExpense = computed(() => {
  return formatCurrency(monthlyExpenseValue.value);
});
const expenseDetails = computed(() => {
  return `${eventsThisMonth.value.length} gastos planificados`;
});


const transactions = computed(() => {
  return eventsThisMonth.value
    .filter(event => parseFloat(event.gasto_estimado || '0') > 0)
    .map(event => ({
      ...event,
      amount: formatCurrency(event.gasto_estimado),
      className: 'card-purple'
    }));
});

const historyItems = computed(() => {
  return eventsPast.value
    .filter(event => parseFloat(event.gasto_estimado || '0') > 0)
    .map(event => ({
      amount: `-${formatCurrency(event.gasto_estimado)}`,
      description: event.titulo,
      date: new Date(event.fecha + 'T12:00:00').toLocaleDateString('es-ES', { day: 'numeric', month: 'long', year: 'numeric' }),
      type: 'expense'
    }));
});

// ... (Proyección: projectedBalance, formattedProjectedBalance, projectedBalanceColor sin cambios) ...
const projectedBalance = computed(() => {
  if (!user.value) return 0;
  const income = parseFloat(user.value.ingreso_mensual || '0');
  const expenses = monthlyExpenseValue.value;
  return income - expenses;
});
const formattedProjectedBalance = computed(() => {
  return formatCurrency(projectedBalance.value);
});
const projectedBalanceColor = computed(() => {
  if (projectedBalance.value > 0) return '#2dd36f';
  if (projectedBalance.value < 0) return '#eb445a';
  return '#ffffff';
});


/**
 * ¡NUEVA COMPUTADA!
 * Formatea la fecha del evento seleccionado para el modal.
 */
const formattedEventDate = computed(() => {
  if (!selectedEvent.value) return '';
  return new Date(selectedEvent.value.fecha + 'T12:00:00').toLocaleDateString('es-ES', {
    day: 'numeric', month: 'long', year: 'numeric'
  });
});

</script>

<style scoped>
/* ... (Todos los estilos anteriores sin cambios) ... */
/* ... (Solo me aseguro de que el estilo del input sigue aquí) ... */
.summary-card ion-input {
  --color: #ffffff; /* Color del texto */
  --placeholder-color: #e0e0e0; /* Color del placeholder */
  --placeholder-opacity: 0.7;
  background-color: rgba(0, 0, 0, 0.25); /* Fondo ligero para contraste */
  border-radius: 6px;
  padding: 4px 8px !important; /* Asegura el padding */
  font-size: 14px;
}
.balance-section ion-input {
  --color: #ffffff; /* Color del texto */
  --placeholder-color: #e0e0e0; /* Color del placeholder */
  --placeholder-opacity: 0.7;
  background-color: rgba(0, 0, 0, 0.25); /* Fondo ligero para contraste */
  border-radius: 6px;
  padding: 4px 8px !important;
  font-size: 14px;
}

/* Ajustes al layout principal */
.content-wrapper {
  display: flex;
  height: 100%;
  padding: 20px;
  gap: 20px;
  flex-wrap: nowrap;
}
.main-finance-area {
  flex: 1;
  overflow-y: auto;
  min-width: 400px;
}
.history-sidebar {
  width: 340px;
  background: linear-gradient(135deg, #7C3AED 0%, #5B21B6 100%);
  border-radius: 16px;
  padding: 20px;
  overflow-y: auto;
  display: flex;
  flex-direction: column;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
  flex-shrink: 0;
}

/* Media query */
@media (max-width: 992px) {
  .content-wrapper {
    flex-direction: column;
  }
  .main-finance-area {
    min-width: 100%;
  }
  .history-sidebar {
    width: 100%;
    max-height: 400px;
  }
  .history-list {
    flex-direction: row;
    overflow-x: auto;
    gap: 12px;
  }
  .history-card {
    flex: 0 0 250px;
  }
  .ion-hide-md-down {
    display: none;
  }
}
.balance-section {
  background: linear-gradient(135deg, #5B8DEF 0%, #0F52BA 100%);
  border-radius: 16px;
  padding: 24px;
  margin-bottom: 20px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}
.balance-label {
  font-size: 14px;
  margin: 0 0 8px 0;
  opacity: 0.9;
}
.balance-amount {
  font-size: 48px;
  font-weight: bold;
  margin: 0;
}
.transactions-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 16px;
  padding: 0 8px;
}
.transactions-header h3 {
  font-size: 18px;
  font-weight: 600;
  margin: 0;
  color: #1F2937;
}
.transactions-subtitle {
  font-size: 12px;
  margin: 4px 0 0 0;
}
.transactions-grid {
  padding: 0;
}
.transaction-card {
  border-radius: 12px;
  margin: 0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  height: 100%;
}
.transaction-card-empty {
  border-radius: 12px;
  margin: 0;
  height: 100%;
  background: #f4f5f8;
}
.card-purple {
  background: linear-gradient(135deg, #8B5CF6 0%, #6D28D9 100%);
}
.transaction-card ion-card-content {
  padding: 16px;
  display: flex;
  flex-direction: column;
  height: 100%;
}
.transaction-content {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  flex: 1;
  margin-bottom: 12px;
}
.transaction-amount {
  font-size: 22px;
  font-weight: bold;
  margin: 0 0 8px 0;
}
.transaction-description {
  font-size: 13px;
  margin: 0;
  opacity: 0.9;
}
.transaction-icon {
  font-size: 24px;
}
.detail-btn {
  --background: rgba(255, 255, 255, 0.2);
  margin: 0;
  font-size: 12px;
  height: 32px;
}
.history-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}
.history-header h3 {
  font-size: 20px;
  font-weight: bold;
  color: white;
  margin: 0;
}
.history-list {
  flex: 1;
  display: flex;
  flex-direction: column;
  gap: 12px;
}
.history-card {
  border-radius: 12px;
  margin: 0;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}
.history-card.expense {
  background: linear-gradient(135deg, #6366F1 0%, #4F46E5 100%);
}
.history-card ion-card-content {
  padding: 12px;
}
.history-item-header {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 8px;
}
.history-icon {
  font-size: 20px;
}
.history-amount {
  font-size: 18px;
  font-weight: bold;
  margin: 0;
}
.history-description {
  font-size: 12px;
  margin: 0 0 4px 0;
  opacity: 0.9;
}
.history-date {
  font-size: 11px;
  margin: 0;
  opacity: 0.8;
}
.summary-section {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 16px;
  margin-bottom: 24px;
}
.summary-card {
  border-radius: 12px;
  margin: 0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
}
.summary-card ion-card-content {
  padding: 16px;
  display: flex;
  flex-direction: column;
  flex: 1;
}
.income-card {
  background: linear-gradient(135deg, #7C3AED 0%, #5B21B6 100%);
}
.expense-card {
  background: linear-gradient(135deg, #7C3AED 0%, #5B21B6 100%);
}
.projection-card {
  background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);
}
.summary-label {
  font-size: 12px;
  margin: 0 0 8px 0;
  opacity: 0.9;
  font-weight: 500;
}
.summary-amount {
  font-size: 24px;
  font-weight: bold;
  margin: 0 0 4px 0;
}
.summary-sublabel {
  font-size: 11px;
  margin: 0 0 12px 0;
  opacity: 0.8;
  flex-grow: 1;
}
ion-searchbar {
  --background: white;
  --border-radius: 20px;
  padding: 4px 8px;
  width: 250px;
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