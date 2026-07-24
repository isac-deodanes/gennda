<template>
  <ion-page>
    <ion-header>
      <ion-toolbar>
        <ion-title>Gennda</ion-title>
        <ion-button id="boton-sesion" slot="end" @click="$router.push('/login')">
          Iniciar Sesion
          <ion-icon slot="end" :icon="logInOutline"></ion-icon>
        </ion-button>
      </ion-toolbar>
    </ion-header>
    <ion-content>
      <div class="inicio-container">
        <div class="subcontainer">
          <div class="central-box">
            <div class="sub-logo">
              <img class="logo-inicio" src="/logo.png" alt="logo"></img>
            </div>
            <h1>Bienvenido a Gennda</h1>
            <p>La mejor solución para tu organizacion</p>
            <router-link to="/registro">
              <ion-button id="boton-registro" size="default">
                <ion-icon slot="start" :icon="personAddOutline"></ion-icon>
                Registrase
              </ion-button>
            </router-link>
          </div>
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
import { IonButtons,IonPage, IonButton, IonFooter, IonContent, IonHeader, IonToolbar, IonMenuButton, IonTitle, IonIcon } from '@ionic/vue';
import { logInOutline, personAddOutline } from 'ionicons/icons';
</script>

<style scoped>
.subcontainer {
  display: flex;
  width: 100%;
  align-items: center;
  justify-content: center;
  height: 100%;
}

.inicio-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
  width: 100%;
}
ion-title{
  color: black;
}
.central-box {
  padding: 40px;
  border-radius: 20px;
  text-align: center;
  background: linear-gradient(to bottom right, #c2aadf, #e8bcde, #dca8ef);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.25);
  width: 80%;
  display: flex;
  flex-direction: column;
  gap: 20px;
  margin: 40px;
}

.central-box h1 {
  margin: 0;
}

.central-box p {
  margin: 0;
}

.logo-inicio {
  width: 150px;
  height: 150px;
}
.sub-logo{
  display: flex;
  justify-content: center;
  align-items: center;
  margin-bottom: 20px;
}
#boton-registro{
  width: 150px;
  align-self: center;
  margin-top: 20px;
}
#boton-sesion{
  margin-right: 10px;
}

#boton-sesion ion-icon {
  margin-left: 6px;
}

#boton-registro ion-icon {
  margin-right: 6px;
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

