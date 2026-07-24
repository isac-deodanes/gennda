<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-buttons slot="start">
                    <ion-menu-button />
                </ion-buttons>
                <ion-title>Términos y Políticas</ion-title>
                <ion-buttons slot="end">
                </ion-buttons>
            </ion-toolbar>
        </ion-header>

        <ion-content :fullscreen="true">
            <div class="background-container ion-padding">
                <ion-card class="terms-card">
                    <ion-card-header>
                        <ion-card-title class="ion-text-center">Términos y políticas</ion-card-title>
                    </ion-card-header>

                    <ion-card-content class="ion-no-padding">
                        <ion-accordion-group expand="inset">
                            <ion-accordion value="politica-comercial">
                                <ion-item slot="header" class="white-bg-item">
                                    <ion-label class="bold-text">Política Comercial</ion-label>
                                </ion-item>
                                <div class="ion-padding" slot="content">
                                    <p><strong>Objeto:</strong> El servicio se presta bajo un esquema de suscripción
                                        mensual.</p>
                                    <p><strong>Precios:</strong> Los precios están sujetos a cambios con
                                        notificación de 30 días.</p>
                                </div>
                            </ion-accordion>

                            <ion-accordion value="membresias">
                                <ion-item slot="header" class="white-bg-item">
                                    <ion-label class="bold-text">Membresías</ion-label>
                                </ion-item>
                                <div class="ion-padding" slot="content">
                                    <p><strong>Nivel Básico:</strong> Acceso limitado a funciones esenciales.</p>
                                    <p><strong>Nivel Premium:</strong> Acceso completo, soporte 24/7 y descuentos
                                        exclusivos.</p>
                                </div>
                            </ion-accordion>

                            <ion-accordion value="requisitos">
                                <ion-item slot="header" class="white-bg-item">
                                    <ion-label class="bold-text">Requisitos para obtener el servicio</ion-label>
                                </ion-item>
                                <div class="ion-padding" slot="content">
                                    <p>Ser mayor de 18 años, presentar una identificación oficial válida y aceptar
                                        los términos de uso.</p>
                                </div>
                            </ion-accordion>

                            <ion-accordion value="mora">
                                <ion-item slot="header" class="white-bg-item">
                                    <ion-label class="bold-text">Cargo de mora</ion-label>
                                </ion-item>
                                <div class="ion-padding" slot="content">
                                    <p>Se aplicará un cargo del 10% sobre el saldo pendiente si el pago no se
                                        realiza antes de la fecha de corte.</p>
                                </div>
                            </ion-accordion>

                            <ion-accordion value="privacidad">
                                <ion-item slot="header" class="white-bg-item">
                                    <ion-label class="bold-text">Política de privacidad</ion-label>
                                </ion-item>
                                <div class="ion-padding" slot="content">
                                    <p>Tus datos se utilizan exclusivamente para la gestión de la cuenta y el envío
                                        de notificaciones. No se comparten con terceros.</p>
                                </div>
                            </ion-accordion>

                            <ion-accordion value="cobertura">
                                <ion-item slot="header" class="white-bg-item">
                                    <ion-label class="bold-text">Cobertura</ion-label>
                                </ion-item>
                                <div class="ion-padding" slot="content">
                                    <p>El servicio está disponible en Ciudad A, Ciudad B y áreas metropolitanas
                                        colindantes. Verifica tu código postal.</p>
                                </div>
                            </ion-accordion>
                        </ion-accordion-group>

                        
                    </ion-card-content>
                </ion-card>
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
import {
    IonPage,
    IonHeader,
    IonToolbar,
    IonTitle,
    IonButtons,
    IonButton,
    IonMenuButton,
    IonContent,
    IonFooter,
    IonCard,
    IonCardHeader,
    IonCardTitle,
    IonCardContent,
    IonItem,
    IonLabel,
    IonCheckbox,
    IonAccordionGroup,
    IonAccordion
} from '@ionic/vue';

const readTerms = ref(false);
const acceptTerms = ref(false);
</script>

<style scoped>
.bold-text {
    font-weight: bold;
    background: white;
    color: black;
}

.ion-text-center {
    color: black;
    padding-top: 20px;
}

ion-label {
    background: rgb(255, 255, 255);
    color: black;
}

.background-container {
    min-height: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
}

.terms-card {
    width: 90%;
    max-width: 800px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    --background: white;
}

ion-card-title {
    font-weight: bold;
    font-size: 1.5em;
}

.white-bg-item {
    --background: white !important;
    --color: black !important;
}

ion-accordion-group {
    --background: white;
    border-bottom: 1px solid var(--ion-color-step-150, #c8c7cc);
}

ion-accordion div[slot="content"] {
    background: white;
}

ion-accordion ion-item[slot="header"] {
    --color: black;
    background: white;
}

.checkbox-container {
    padding-top: 15px;
}

.required-asterisk {
    color: red;
    font-weight: bold;
    margin-right: 4px;
}

ion-item[lines="none"] {
    --padding-start: 0;
    --inner-padding-end: 0;
    --min-height: 35px;
}

ion-footer ion-toolbar {
    text-align: center;
}

ion-footer p {
    margin: 10px 0;
    font-size: 0.875rem;
    color: #666;
}

@media (min-width: 768px) {
    .terms-card {
        width: 60%;
    }
}
</style>