<template>
  <ion-app>
    <ion-menu content-id="main-content">
      
      <ion-content>
        <div class="menu-header">
          <img src="/logo.png" alt="Gennda Logo" class="menu-logo-circle" />
        </div>

        <ion-list>
          <div class="list-section">
            <ion-label class="section-title">MENU</ion-label>
            
            <ion-item router-link="/calendario" :lines="'none'">
              <ion-icon :icon="calendarOutline" slot="start"></ion-icon>
              <ion-label>Calendario</ion-label>
            </ion-item>
            
            <ion-item router-link="/finanzas" :lines="'none'">
              <ion-icon :icon="walletOutline" slot="start"></ion-icon>
              <ion-label>Finanzas</ion-label>
            </ion-item>
            
            <ion-item router-link="/historial" :lines="'none'">
              <ion-icon :icon="barChartOutline" slot="start"></ion-icon>
              <ion-label>Historial Finanzas</ion-label>
            </ion-item>
          </div>

          <div class="list-section">
            <ion-label class="section-title">PREFERENCIAS</ion-label>

            <ion-item router-link="/perfil" :lines="'none'">
              <ion-icon :icon="personOutline" slot="start"></ion-icon>
              <ion-label>Mi perfil</ion-label>
            </ion-item>
          </div>

          <div class="list-section">
            <ion-label class="section-title">SOPORTE</ion-label>

            <ion-item router-link="/ayuda-soporte" :lines="'none'">
              <ion-icon :icon="helpCircleOutline" slot="start"></ion-icon>
              <ion-label>Ayuda y soporte</ion-label>
            </ion-item>

            <ion-item router-link="/politica" :lines="'none'">
              <ion-icon :icon="shieldCheckmarkOutline" slot="start"></ion-icon>
              <ion-label>Política</ion-label>
            </ion-item>
          </div>
        </ion-list>
      </ion-content>
    </ion-menu>
    
    <div class="ion-page" id="main-content">
      <ion-router-outlet />
    </div>
  </ion-app>
</template>

<script setup lang="ts">
import { IonApp, IonContent, IonList, IonMenu, IonItem, IonRouterOutlet, IonIcon, IonLabel } from '@ionic/vue';
import {homeOutline,walletOutline,calendarOutline,barChartOutline,personOutline,helpCircleOutline,shieldCheckmarkOutline} from 'ionicons/icons';
</script>

<style scoped>
.menu-header {
  padding: 24px 16px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.menu-logo-circle {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
}

ion-list {
  background: transparent;
  padding: 0 16px;
}

.list-section {
  margin-bottom: 20px;
}

.section-title {
  display: block;
  font-size: 0.75rem; 
  font-weight: 500;
  color: rgba(8, 11, 24, 0.7);
  
  text-transform: uppercase;
  letter-spacing: 0.05em;
  padding: 0 16px;
  margin-bottom: 8px;
  border-bottom: 1px solid var(--ion-color-step-150, rgba(255, 255, 255, 0.2)); /* Aclaré la línea */
  padding-bottom: 8px;
}

ion-item {
  --background: transparent;
  --padding-start: 16px;
  --padding-end: 16px;
  --inner-padding-end: 16px;
  --border-radius: 8px;
  margin-bottom: 4px;
}

ion-item ion-icon {
  margin-right: 20px;
  font-size: 1.4rem;
  color: rgba(41, 54, 121, 0.7);
}

ion-item ion-label {
  font-size: 1rem;
  font-weight: 500;
  color: #000000;
}

ion-item.router-link-exact-active {
  --background: rgba(255, 255, 255, 0.9);
  --color: var(--ion-color-primary);
}

ion-item.router-link-exact-active ion-icon {
  color: var(--ion-color-primary);
}

ion-item.router-link-exact-active ion-label {
  color: var(--ion-color-primary);
}
</style>