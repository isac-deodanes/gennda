<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-title>Página no encontrada</ion-title>
            </ion-toolbar>
        </ion-header>
        <ion-content class="ion-padding">
            <div class="error-container">
                <div class="error-card">
                    <h1>404</h1>
                    <h2>Página no encontrada</h2>
                    <p>La ruta que intentas visitar no existe o ha sido movida.</p>
                    <p class="timer-text" v-if="countdown > 0">
                        Serás redirigido al inicio en {{ countdown }} segundos
                    </p>
                    <ion-button @click="goHome" expand="block" class="home-button">
                        Volver al Inicio
                        <ion-icon slot="end" :icon="home"></ion-icon>
                    </ion-button>
                </div>

            </div>
        </ion-content>
    </ion-page>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import {IonPage,IonHeader,IonToolbar,IonTitle,IonContent,IonButton,IonIcon} from '@ionic/vue';
import { home } from 'ionicons/icons';

const router = useRouter();
const countdown = ref(10);
let timer: any = null;

const goHome = () => {
    router.replace('/inicio');
};
onMounted(() => {
    timer = setInterval(() => {
        countdown.value--;
        if (countdown.value <= 0) {
            goHome();
        }
    }, 1000);
});
onUnmounted(() => {
    if (timer) {
        clearInterval(timer);
    }
});
</script>

<style scoped>
.error-container {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    text-align: center;
}

.error-card {
    background: #ffffff;
    padding: 24px 32px;
    border-radius: 20px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
    width: 100%;
    max-width: 450px;
    margin: 20px;
    border: 1px solid rgba(255, 255, 255, 0.5);
}

.error-card h1 {
    font-size: 5.5rem;
    font-weight: 800;
    color: #4babbc;
    margin: 0;
    line-height: 1;
}

.error-card h2 {
    font-size: 1.75rem;
    font-weight: 600;
    margin-top: 10px;
    margin-bottom: 10px;
    color: #000;
}

.error-card p {
    color: #666;
    font-size: 1rem;
    margin-bottom: 24px;
}

.timer-text {
    font-size: 0.9rem;
    color: #888;
    margin-bottom: 20px;
}

.home-button {
    --background: #63c8da;
    --background-activated: #4babbc;
    --color: #000;
    --box-shadow: none;
    font-weight: 600;
}
</style>
