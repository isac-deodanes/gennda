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
                        <ion-label position="stacked">Nombre</ion-label>
                        <ion-input type="text" v-model="formData.nombre"></ion-input>
                    </ion-item>
                    <ion-item class="info-field-editable">
                        <ion-label position="stacked">Correo</ion-label>
                        <ion-input type="email" v-model="formData.email"></ion-input>
                    </ion-item>
                    <ion-item class="info-field-editable">
                        <ion-label position="stacked">DUI</ion-label>
                        <ion-input type="text" v-model="formData.dui"placeholder="00000000-0" :maxlength="10" @ionInput="formatDui"></ion-input>
                    </ion-item>
                    <ion-item class="info-field-editable">
                        <ion-label position="stacked">Contraseña</ion-label>
                        <ion-input type="password" v-model="formData.password"></ion-input>
                    </ion-item>
                    <ion-item class="info-field-editable">
                        <ion-label position="stacked">Confirmar Contraseña</ion-label>
                        <ion-input type="password" v-model="formData.password_confirmation"></ion-input>
                    </ion-item>
                </div>
                <div class="button-stack">
                    <ion-button @click="openTermsModal" color="primary" expand="block">
                        <ion-icon slot="start" :icon="personAddOutline"></ion-icon>
                        Registrarse
                    </ion-button>
                </div>
                <p class="register-text">
                    ¿Ya tienes cuenta?
                    <router-link to="/login">Inicia sesión</router-link>
                </p>
            </div>
        </ion-content>
        <ion-footer>
            <ion-toolbar>
                <p>© 2025 Gennda - Todos los derechos reservados</p>
            </ion-toolbar>
        </ion-footer>
        <ion-modal :is-open="showTerms" @didDismiss="showTerms = false">
            <ion-header>
                <ion-toolbar>
                    <ion-title>Términos y Políticas</ion-title>
                    <ion-buttons slot="end">
                        <ion-button @click="closeTerms">Cerrar</ion-button>
                    </ion-buttons>
                </ion-toolbar>
            </ion-header>
            <ion-content class="ion-padding modal-content">
                <ion-accordion-group expand="inset">
                    <ion-accordion id="accordion" value="politica-comercial">
                        <ion-item id="titleAcordion" slot="header" class="white-bg-item">
                            <ion-label class="bold-text">Política Comercial</ion-label>
                        </ion-item>
                        <div class="ion-padding" id="descripcionAcordion" slot="content">
                            <p><strong>Objeto:</strong> El servicio se presta bajo un esquema de suscripción
                                mensual.</p>
                            <p><strong>Precios:</strong> Los precios están sujetos a cambios con
                                notificación de 30 días.</p>
                        </div>
                    </ion-accordion>
                    <ion-accordion id="accordion" value="membresias">
                        <ion-item id="titleAcordion" slot="header" class="white-bg-item">
                            <ion-label class="bold-text">Membresías</ion-label>
                        </ion-item>
                        <div class="ion-padding" id="descripcionAcordion" slot="content">
                            <p><strong>Nivel Básico:</strong> Acceso limitado a funciones esenciales.</p>
                            <p><strong>Nivel Premium:</strong> Acceso completo, soporte 24/7 y descuentos
                                exclusivos.</p>
                        </div>
                    </ion-accordion>
                    <ion-accordion id="accordion" value="requisitos">
                        <ion-item id="titleAcordion" slot="header" class="white-bg-item">
                            <ion-label class="bold-text">Requisitos para obtener el servicio</ion-label>
                        </ion-item>
                        <div class="ion-padding" id="descripcionAcordion" slot="content">
                            <p>Ser mayor de 18 años, presentar una identificación oficial válida y aceptar
                                los términos de uso.</p>
                        </div>
                    </ion-accordion>
                    <ion-accordion id="accordion" value="mora">
                        <ion-item id="titleAcordion" slot="header" class="white-bg-item">
                            <ion-label class="bold-text">Cargo de mora</ion-label>
                        </ion-item>
                        <div class="ion-padding" id="descripcionAcordion" slot="content">
                            <p>Se aplicará un cargo del 10% sobre el saldo pendiente si el pago no se
                                realiza antes de la fecha de corte.</p>
                        </div>
                    </ion-accordion>
                    <ion-accordion id="accordion" value="privacidad">
                        <ion-item id="titleAcordion" slot="header" class="white-bg-item">
                            <ion-label class="bold-text">Política de privacidad</ion-label>
                        </ion-item>
                        <div class="ion-padding" id="descripcionAcordion" slot="content">
                            <p>Tus datos se utilizan exclusivamente para la gestión de la cuenta y el envío
                                de notificaciones. No se comparten con terceros.</p>
                        </div>
                    </ion-accordion>
                    <ion-accordion id="accordion" value="cobertura">
                        <ion-item id="titleAcordion" slot="header" class="white-bg-item">
                            <ion-label class="bold-text">Cobertura</ion-label>
                        </ion-item>
                        <div class="ion-padding" id="descripcionAcordion" slot="content">
                            <p>El servicio está disponible en Ciudad A, Ciudad B y áreas metropolitanas
                                colindantes. Verifica tu código postal.</p>
                        </div>
                    </ion-accordion>
                </ion-accordion-group>
                <div id="accordion" class="checkbox-container ion-padding">
                    <ion-item id="titleAcordion" lines="none" class="white-bg-item">
                        <ion-checkbox slot="end" v-model="readTerms"></ion-checkbox>
                        <ion-label class="bold-text">
                            <span class="required-asterisk">*</span>He leído los términos y condiciones
                        </ion-label>
                    </ion-item>
                    <ion-item id="titleAcordion" lines="none" class="white-bg-item">
                        <ion-checkbox slot="end" v-model="acceptTerms"></ion-checkbox>
                        <ion-label class="bold-text">
                            <span class="required-asterisk">*</span>Acepto términos y condiciones
                        </ion-label>
                    </ion-item>
                </div>
            </ion-content>
            <ion-footer>
                <ion-toolbar>
                    <ion-button id="button-termino" expand="block" color="primary"
                        :disabled="!(acceptTerms && readTerms)" @click="confirmRegister">
                        Aceptar y continuar
                    </ion-button>
                </ion-toolbar>
            </ion-footer>
        </ion-modal>
    </ion-page>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import {IonPage, IonFooter, IonContent, IonLabel, IonInput, IonItem, IonButton, IonHeader, IonToolbar, toastController, IonIcon, IonModal, IonCard, IonCardHeader, IonCardTitle, IonCardContent, IonAccordionGroup, IonAccordion, IonButtons, IonTitle, IonCheckbox} from '@ionic/vue';
import axios from 'axios';
import { personAddOutline } from 'ionicons/icons';
const formData = ref({
    nombre: '',
    email: '',
    dui: '',
    password: '',
    password_confirmation: ''
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
const formatDui = (event: Event) => {
    const input = event.target as HTMLIonInputElement;
    let value = (input.value as string).replace(/[^0-9]/g, '');
    if (value.length > 8) {
        value = value.slice(0, 8) + '-' + value.slice(8, 9);
    }
    formData.value.dui = value;
    (event.target as HTMLIonInputElement).value = formData.value.dui;
};
const showTerms = ref<boolean>(false);
const readTerms = ref<boolean>(false);
const acceptTerms = ref<boolean>(false);
const confirmRegister = async () => {
    if (!readTerms.value || !acceptTerms.value) {
        presentToast('Debes aceptar los términos y condiciones', 'danger');
        return;
    }
    showTerms.value = false;
    await handleRegister();
};

const openTermsModal = () => {
    showTerms.value = true;
};
const closeTerms = () => {
    showTerms.value = false;
};
const handleRegister = async () => {
    if (formData.value.password !== formData.value.password_confirmation) {
        presentToast('Las contraseñas no coinciden', 'danger');
        return;
    }
    try {
        const response = await axios.post(`${API_URL}/register`, formData.value);
        if (response.status === 201) {
            console.log('Registro exitoso:', response.data);
            presentToast('¡Registro exitoso! Ahora puedes iniciar sesión.', 'success');
            router.push('/login');
        }
    } catch (error: any) {
        console.error('Error en el registro:', error);
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
            let errorMessage = 'Error de validación:';
            if (errors.nombre) {
                errorMessage += `\n- ${errors.nombre[0]}`;
            }
            if (errors.email) {
                errorMessage += `\n- ${errors.email[0]}`;
            }
            if (errors.dui) {
                errorMessage += `\n- ${errors.dui[0]}`;
            }
            if (errors.password) {
                errorMessage += `\n- ${errors.password[0]}`;
            }
            presentToast(errorMessage.replace(/\n/g, '<br>'), 'danger');
        } else {
            presentToast('No se pudo completar el registro. Intenta más tarde.', 'danger');
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

ion-modal {
    --height: 90%;
    --width: 95%;
    --border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.2);
}

@media (min-width: 768px) {
    ion-modal {
        --height: 80%;
        --width: 40%;
        --max-width: 700px;
    }
}

.modal-content {
    --background: #ffffff;
    --color: #000000;
}

#button-termino {
    margin: 8px 16px;
}

#accordion {
    background: transparent;
}

#descripcionAcordion {
    background: transparent;
}

#titleAcordion {
    font-weight: bold;
    --background: transparent;
}

ion-accordion-group {
    --background: transparent;
}

.checkbox-container ion-item {
    --background: transparent;
}

.bold-text {
    font-weight: 600;
}

.required-asterisk {
    color: var(--ion-color-danger, red);
    margin-right: 4px;
}
</style>