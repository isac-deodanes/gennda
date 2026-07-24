<template>
    <ion-page>
        <ion-header>
        </ion-header>
        <ion-content :fullscreen="true" class="ion-padding">
            <div class="profile-card">
                <div class="logo-container">
                    <img class="logo" src="/logo2.png" alt="logo" />
                </div>
                <div class="user-info-container">
                    <ion-item class="info-field-editable">
                        <ion-label position="stacked">Correo</ion-label>
                        <ion-input type="email" v-model="formData.email"></ion-input>
                    </ion-item>
                    <ion-item class="info-field-editable">
                        <ion-label position="stacked">Contraseña</ion-label>
                        <ion-input type="password" v-model="formData.password"></ion-input>
                    </ion-item>
                </div>
                <div class="button-stack">
                    <ion-button @click="handleLogin" color="primary" expand="block">
                        Iniciar Sesión
                        <ion-icon slot="end" :icon="logInOutline"></ion-icon>
                    </ion-button>
                </div>
                <p class="register-text">
                    ¿No tienes cuenta?
                    <router-link to="/registro">Regístrate</router-link>
                </p>
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
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { IonPage, IonFooter, IonContent, IonLabel, IonInput, IonItem, IonButton, IonHeader, IonToolbar, toastController, IonIcon } from '@ionic/vue';
import axios from 'axios';
import { logInOutline } from 'ionicons/icons';

const formData = ref({
    email: '',
    password: ''
});
const router = useRouter();
const API_URL = 'http://127.0.0.1:8000/api';
const presentToast = async (message: string, color: 'success' | 'danger') => {
    const toast = await toastController.create({
        message: message,
        duration: 3000,
        position: 'top',
        color: color,
    });
    await toast.present();
};
const handleLogin = async () => {
    if (!formData.value.email || !formData.value.password) {
        presentToast('Por favor, ingresa correo y contraseña', 'danger');
        return;
    }
    try {
        const response = await axios.post(`${API_URL}/login`, formData.value);
        if (response.status === 200 && response.data.access_token) {
            console.log('Login exitoso:', response.data);
            localStorage.setItem('auth_token', response.data.access_token);
            localStorage.setItem('user_data', JSON.stringify(response.data.user));
            axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.access_token}`;
            presentToast(`¡Bienvenido, ${response.data.user.nombre}!`, 'success');
            router.push('/calendario');
        }
    } catch (error: any) {
        console.error('Error en el login:', error);
        if (error.response && (error.response.status === 401 || error.response.status === 422)) {
            presentToast('Correo o contraseña incorrectos.', 'danger');
        } else {
            presentToast('No se pudo iniciar sesión. Intenta más tarde.', 'danger');
        }
    }
};
</script>

<style scoped>
.profile-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #ffffff;
    border-radius: 20px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    padding: 24px;
    width: 100%;
    max-width: 500px;
    margin: 20px auto;
}
.logo-container {
    text-align: center;
    margin-top: 10px;
    margin-bottom: 20px;
}
.logo-container .logo {
    width: 150px;
    height: auto;
}
.user-info-container {
    width: 100%;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.info-field-editable {
    --background: #f9f9f9;
    border: 1px solid #ebebeb;
    border-radius: 10px;
    padding-bottom: 4px;
}
.info-field-editable.ion-focused {
    border-color: var(--ion-color-primary, #3880ff);
}
.button-stack {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 20px; 
}
.register-text {
    margin-top: 24px;
    font-size: 0.95rem;
    text-align: center;
}
.register-text a {
    color: var(--ion-color-primary, #3880ff);
    text-decoration: none;
    font-weight: 600;
}
.register-text a:hover {
    text-decoration: underline;
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