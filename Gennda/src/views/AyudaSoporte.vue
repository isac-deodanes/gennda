<template>
    <ion-page>
        <ion-header>
            <ion-toolbar>
                <ion-buttons slot="start">
                    <ion-menu-button />
                </ion-buttons>
                <ion-title>Ayuda y Soporte</ion-title>
            </ion-toolbar>
        </ion-header>
        <ion-content class="ion-padding">
            <div class="ayuda-container">
                <div class="search-section">
                    <ion-searchbar 
                        v-model="searchQuery"
                        placeholder="¿En qué podemos ayudarte?"
                        :animated="true"
                    ></ion-searchbar>
                </div>

                <div class="section-container">
                    <h2 class="section-title">Preguntas Frecuentes</h2>
                    <div class="faq-grid">
                        <div 
                            v-for="(faq, index) in filteredFaqs" 
                            :key="index"
                            class="faq-card"
                            @click="toggleFaq(index)"
                        >
                            <div class="faq-header">
                                <ion-icon 
                                    :icon="faq.expanded ? chevronDownOutline : chevronForwardOutline"
                                    class="faq-icon"
                                ></ion-icon>
                                <h3>{{ faq.pregunta }}</h3>
                            </div>
                            <div v-show="faq.expanded" class="faq-content">
                                <p>{{ faq.respuesta }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-container">
                    <h2 class="section-title">Categorías de Ayuda</h2>
                    <div class="categories-grid">
                        <div 
                            class="category-card"
                            :class="{ 'category-expanded': isCategoryExpanded(0) }"
                            @click.stop="toggleCategory(0)"
                        >
                            <div class="category-icon" :style="{ backgroundColor: categorias[0].color }">
                                <ion-icon :icon="categorias[0].icon" size="large"></ion-icon>
                            </div>
                            <h3>{{ categorias[0].titulo }}</h3>
                            <p class="category-description">{{ categorias[0].descripcion }}</p>
                            
                            <ion-icon 
                                :icon="isCategoryExpanded(0) ? chevronUpOutline : chevronDownOutline"
                                class="expand-icon"
                            ></ion-icon>
                            
                            <div v-if="isCategoryExpanded(0)" class="category-help-content">
                                <ul>
                                    <li v-for="(item, idx) in categorias[0].ayuda" :key="idx">
                                        {{ item }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div 
                            class="category-card"
                            :class="{ 'category-expanded': isCategoryExpanded(1) }"
                            @click.stop="toggleCategory(1)"
                        >
                            <div class="category-icon" :style="{ backgroundColor: categorias[1].color }">
                                <ion-icon :icon="categorias[1].icon" size="large"></ion-icon>
                            </div>
                            <h3>{{ categorias[1].titulo }}</h3>
                            <p class="category-description">{{ categorias[1].descripcion }}</p>
                            
                            <ion-icon 
                                :icon="isCategoryExpanded(1) ? chevronUpOutline : chevronDownOutline"
                                class="expand-icon"
                            ></ion-icon>
                            
                            <div v-if="isCategoryExpanded(1)" class="category-help-content">
                                <ul>
                                    <li v-for="(item, idx) in categorias[1].ayuda" :key="idx">
                                        {{ item }}
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div 
                            class="category-card"
                            :class="{ 'category-expanded': isCategoryExpanded(2) }"
                            @click.stop="toggleCategory(2)"
                        >
                            <div class="category-icon" :style="{ backgroundColor: categorias[2].color }">
                                <ion-icon :icon="categorias[2].icon" size="large"></ion-icon>
                            </div>
                            <h3>{{ categorias[2].titulo }}</h3>
                            <p class="category-description">{{ categorias[2].descripcion }}</p>
                            
                            <ion-icon 
                                :icon="isCategoryExpanded(2) ? chevronUpOutline : chevronDownOutline"
                                class="expand-icon"
                            ></ion-icon>
                            
                            <div v-if="isCategoryExpanded(2)" class="category-help-content">
                                <ul>
                                    <li v-for="(item, idx) in categorias[2].ayuda" :key="idx">
                                        {{ item }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section-container">
                    <h2 class="section-title">Contacta con Nosotros</h2>
                    <div class="contact-card">
                        <div class="contact-info">
                            <div class="contact-item">
                                <ion-icon :icon="mailOutline" size="large"></ion-icon>
                                <div>
                                    <h4>Correo electrónico</h4>
                                    <p>soporte@gennda.com</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <ion-icon :icon="callOutline" size="large"></ion-icon>
                                <div>
                                    <h4>Teléfono</h4>
                                    <p>+503 7467-7407</p>
                                </div>
                            </div>
                            <div class="contact-item">
                                <ion-icon :icon="timeOutline" size="large"></ion-icon>
                                <div>
                                    <h4>Horario de atención</h4>
                                    <p>Lunes a Viernes: 8:00 AM - 6:00 PM</p>
                                </div>
                            </div>
                        </div>
                        <ion-button expand="block" class="contact-button">
                            Enviar mensaje
                        </ion-button>
                    </div>
                </div>

                <div class="section-container">
                    <h2 class="section-title">Recursos Adicionales</h2>
                    <div class="resources-list">
                        <ion-item 
                            v-for="(recurso, index) in recursos" 
                            :key="index"
                            button
                            class="resource-item"
                        >
                            <ion-icon :icon="recurso.icon" slot="start"></ion-icon>
                            <ion-label>
                                <h3>{{ recurso.titulo }}</h3>
                                <p>{{ recurso.descripcion }}</p>
                            </ion-label>
                            <ion-icon :icon="chevronForwardOutline" slot="end"></ion-icon>
                        </ion-item>
                    </div>
                </div>
            </div>

            <ion-fab vertical="bottom" horizontal="end" slot="fixed" class="whatsapp-fab-container">
                <ion-fab-button 
                    :href="getWhatsAppUrl()"
                    target="_blank"
                    @click="manejarClicBoton"
                    color="success"
                    class="whatsapp-fab"
                >
                    <ion-icon :icon="logoWhatsapp"></ion-icon>
                </ion-fab-button>
            </ion-fab>
        </ion-content>
        <ion-footer>
            <ion-toolbar>
                <p>© 2025 Gennda - Todos los derechos reservados</p>
            </ion-toolbar>
        </ion-footer>
    </ion-page>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import {
    IonPage,
    IonHeader,
    IonToolbar,
    IonButtons,
    IonMenuButton,
    IonTitle,
    IonContent,
    IonFooter,
    IonSearchbar,
    IonIcon,
    IonButton,
    IonItem,
    IonLabel,
    IonFab,
    IonFabButton
} from '@ionic/vue';
import {
    chevronForwardOutline,
    chevronDownOutline,
    chevronUpOutline,
    walletOutline,
    calendarOutline,
    personOutline,
    mailOutline,
    callOutline,
    timeOutline,
    documentTextOutline,
    videocamOutline,
    bookOutline,
    logoWhatsapp
} from 'ionicons/icons';

interface FAQ {
    pregunta: string;
    respuesta: string;
    expanded: boolean;
}

interface Categoria {
    titulo: string;
    descripcion: string;
    icon: string;
    color: string;
    expanded: boolean;
    ayuda: string[];
}

const numeroWhatsApp = '50374677407';
const mensajePredeterminado = 'Hola, estoy en la aplicación Gennda y necesito ayuda con soporte.';

const getWhatsAppUrl = () => {
    const mensajeCodificado = encodeURIComponent(mensajePredeterminado);
    return `https://wa.me/${numeroWhatsApp}?text=${mensajeCodificado}`;
};

const manejarClicBoton = () => {
    console.log('Botón de WhatsApp presionado. Abriendo chat...');
};

const searchQuery = ref('');

const faqs = ref<FAQ[]>([
    {
        pregunta: '¿Cómo puedo registrar un nuevo gasto en Finanzas?',
        respuesta: 'Los gastos se registran automáticamente desde el calendario. Crea un evento y añade un "gasto estimado" para verlo reflejado en tu sección de Finanzas.',
        expanded: false
    },
    {
        pregunta: '¿Cómo funciona el calendario de eventos?',
        respuesta: 'El calendario te permite visualizar todos tus eventos organizados por fecha. Puedes agregar eventos familiares, crear categorías personalizadas con colores, y establecer gastos estimados. Los eventos se muestran con diferentes colores según su categoría.',
        expanded: false
    },
    {
        pregunta: '¿Cómo actualizo mi balance actual?',
        respuesta: 'En la sección de Finanzas, encontrarás un campo para actualizar tu balance actual. Ingresa el monto y presiona "Guardar" para actualizarlo.',
        expanded: false
    },
    {
        pregunta: '¿Qué es la proyección de fin de mes?',
        respuesta: 'La proyección calcula tu balance estimado al final del mes basándose en tu ingreso mensual menos los gastos planificados de tus eventos. Te ayuda a visualizar si terminarás con superávit o déficit.',
        expanded: false
    },
    {
        pregunta: '¿Cómo actualizo mi información de perfil?',
        respuesta: 'Accede a "Mi Perfil" desde el menú lateral. Presiona "Editar Perfil" para modificar tu nombre y correo electrónico. También puedes cambiar tu foto de perfil tocando sobre ella.',
        expanded: false
    },
    {
        pregunta: '¿Puedo ver el historial de mis gastos pasados?',
        respuesta: 'Sí, en la sección "Historial de Finanzas" puedes ver todos los eventos pasados que tenían gastos estimados, organizados cronológicamente.',
        expanded: false
    },
    {
        pregunta: '¿Cómo creo categorías personalizadas para mis eventos?',
        respuesta: 'En el calendario, presiona el botón "+" junto a las categorías existentes. Puedes crear categorías con nombres personalizados y elegir colores para identificarlas fácilmente.',
        expanded: false
    },
    {
        pregunta: '¿El calendario tiene temas especiales?',
        respuesta: 'Sí, el calendario cambia automáticamente su apariencia en meses especiales como febrero (San Valentín), septiembre (Independencia), octubre (Halloween) y diciembre (Navidad).',
        expanded: false
    }
]);

const categorias = ref<Categoria[]>([
    {
        titulo: 'Finanzas',
        descripcion: 'Gestión de ingresos, gastos y balance',
        icon: walletOutline,
        color: '#5B7FE8',
        expanded: false,
        ayuda: [
            'Actualiza tu balance actual ingresando el monto en el campo correspondiente',
            'Configura tu ingreso mensual para calcular la proyección de fin de mes',
            'Visualiza todos los gastos planificados del mes desde tus eventos del calendario',
            'Revisa la proyección de fin de mes para saber si terminarás con superávit o déficit',
            'Haz clic en "Ver detalle del evento" para ver información completa de cada gasto',
            'El gasto estimado de cada evento se suma automáticamente al total mensual',
            'Los eventos con gastos aparecen en tarjetas de colores según su categoría',
            'La sección lateral muestra el historial de gastos de meses anteriores'
        ]
    },
    {
        titulo: 'Calendario',
        descripcion: 'Organización de eventos y recordatorios',
        icon: calendarOutline,
        color: '#B67FE8',
        expanded: false,
        ayuda: [
            'Navega entre meses usando las flechas en la parte superior',
            'Haz clic en cualquier día para ver o agregar eventos',
            'Crea categorías personalizadas con el botón "+" junto a las categorías existentes',
            'Asigna colores diferentes a cada categoría para identificarlas fácilmente',
            'Edita o elimina categorías usando los iconos de lápiz y X',
            'Añade eventos con el botón "+" en la barra lateral derecha',
            'Cada evento puede tener: título, descripción, fecha, hora, categoría y gasto estimado',
            'Los eventos aparecen como chips de colores en el calendario',
            'La barra lateral muestra los eventos del día seleccionado',
            'Edita o elimina eventos usando los iconos en cada tarjeta de evento',
            'El calendario cambia de tema en meses especiales (febrero, septiembre, octubre, diciembre)',
            'Usa la barra de búsqueda para filtrar eventos por título o categoría'
        ]
    },
    {
        titulo: 'Cuenta y Perfil',
        descripcion: 'Configuración de usuario y privacidad',
        icon: personOutline,
        color: '#E87F9B',
        expanded: false,
        ayuda: [
            'Accede a tu perfil desde el botón redondo en la esquina superior derecha',
            'Cambia tu foto de perfil tocando sobre la imagen actual',
            'Recorta tu foto con la herramienta de edición antes de guardarla',
            'Edita tu nombre y correo electrónico presionando "Editar Perfil"',
            'Guarda los cambios o cancela la edición según necesites',
            'Cierra sesión de forma segura con el botón "Cerrar Sesión"',
            'Tu información se guarda automáticamente al presionar "Guardar"',
            'La foto de perfil acepta formatos JPG y PNG con tamaño máximo de 2MB'
        ]
    }
]);

const recursos = ref([
    {
        titulo: 'Guía de inicio rápido',
        descripcion: 'Aprende lo básico en 5 minutos',
        icon: bookOutline
    },
    {
        titulo: 'Tutoriales en video',
        descripcion: 'Videos explicativos paso a paso',
        icon: videocamOutline
    },
    {
        titulo: 'Documentación completa',
        descripcion: 'Manual de usuario detallado',
        icon: documentTextOutline
    }
]);

const filteredFaqs = computed(() => {
    if (!searchQuery.value) return faqs.value;
    
    const query = searchQuery.value.toLowerCase();
    return faqs.value.filter(faq => 
        faq.pregunta.toLowerCase().includes(query) || 
        faq.respuesta.toLowerCase().includes(query)
    );
});

const isCategoryExpanded = (index: number) => {
    return categorias.value[index]?.expanded === true;
};

const toggleFaq = (index: number) => {
    faqs.value[index].expanded = !faqs.value[index].expanded;
};

const toggleCategory = (index: number) => {
    console.log('=== TOGGLE CATEGORY ===');
    console.log('Index clickeado:', index);
    console.log('Estado ANTES:', categorias.value.map((c, i) => ({ index: i, titulo: c.titulo, expanded: c.expanded })));
    
    const wasExpanded = categorias.value[index].expanded;
    
    for (let i = 0; i < categorias.value.length; i++) {
        categorias.value[i].expanded = false;
    }
    
    if (!wasExpanded) {
        categorias.value[index].expanded = true;
    }
    
    console.log('Estado DESPUÉS:', categorias.value.map((c, i) => ({ index: i, titulo: c.titulo, expanded: c.expanded })));
    console.log('=======================');
};
</script>

<style scoped>
.ayuda-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px;
    padding-bottom: 80px;
}

.search-section {
    margin-bottom: 30px;
}

.section-container {
    margin-bottom: 40px;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 20px;
    color: #2c3e50;
}

.faq-grid {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.faq-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    cursor: pointer;
    transition: all 0.3s ease;
}

.faq-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.faq-header {
    display: flex;
    align-items: center;
    gap: 15px;
}

.faq-icon {
    color: #5B7FE8;
    font-size: 24px;
    flex-shrink: 0;
}

.faq-header h3 {
    margin: 0;
    font-size: 1.1rem;
    font-weight: 600;
    color: #2c3e50;
}

.faq-content {
    margin-top: 15px;
    padding-left: 39px;
}

.faq-content p {
    margin: 0;
    color: #666;
    line-height: 1.6;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.category-card {
    background: white;
    border-radius: 12px;
    padding: 25px;
    text-align: center;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease;
    cursor: pointer;
    position: relative;
}

.category-card:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    transform: translateY(-2px);
}

.category-expanded {
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2) !important;
}

.category-icon {
    width: 70px;
    height: 70px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 15px;
    color: white;
}

.category-card h3 {
    margin: 10px 0;
    font-size: 1.2rem;
    font-weight: 600;
    color: #2c3e50;
}

.category-description {
    margin: 0;
    color: #666;
    font-size: 0.9rem;
}

.expand-icon {
    position: absolute;
    top: 25px;
    right: 25px;
    font-size: 24px;
    color: #999;
    transition: all 0.3s ease;
}

.category-expanded .expand-icon {
    color: #5B7FE8;
}

.category-help-content {
    text-align: left;
    margin-top: 20px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.category-help-content ul {
    margin: 0;
    padding-left: 20px;
}

.category-help-content li {
    margin-bottom: 10px;
    color: #555;
    font-size: 0.85rem;
    line-height: 1.6;
}

.contact-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 30px;
    color: white;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 25px;
    margin-bottom: 25px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
}

.contact-item ion-icon {
    color: white;
    flex-shrink: 0;
}

.contact-item h4 {
    margin: 0 0 5px 0;
    font-size: 1rem;
    font-weight: 600;
}

.contact-item p {
    margin: 0;
    opacity: 0.9;
}

.contact-button {
    --background: white;
    --color: #667eea;
    font-weight: 600;
    margin-top: 10px;
}

.resources-list {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.resource-item {
    --padding-start: 20px;
    --padding-end: 20px;
    --inner-padding-end: 0;
}

.resource-item h3 {
    font-size: 1rem;
    font-weight: 600;
    margin-bottom: 5px;
    color: #2c3e50;
}

.resource-item p {
    font-size: 0.875rem;
    color: #666;
    margin: 0;
}

.whatsapp-fab-container {
    right: 60px;
    bottom: 20px;
}

.whatsapp-fab {
    --background: #25D366;
    --background-activated: #20BA5A;
    --background-hover: #20BA5A;
    --box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
    --border-radius: 50%;
    width: 70px;
    height: 70px;
    border-radius: 50% !important;
}

.whatsapp-fab ion-icon {
    font-size: 38px;
}

.whatsapp-fab::part(native) {
    border-radius: 50% !important;
    overflow: hidden;
}

@keyframes pulse {
    0% {
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
    }
    50% {
        box-shadow: 0 6px 30px rgba(37, 211, 102, 0.7);
    }
    100% {
        box-shadow: 0 6px 20px rgba(37, 211, 102, 0.5);
    }
}

.whatsapp-fab:hover {
    animation: pulse 1.5s infinite;
}

ion-footer ion-toolbar {
    text-align: center;
}

ion-footer p {
    margin: 10px 0;
    font-size: 0.875rem;
    color: #666;
}

@media (max-width: 768px) {
    .categories-grid {
        grid-template-columns: 1fr;
    }
    
    .section-title {
        font-size: 1.3rem;
    }

    .ayuda-container {
        padding-bottom: 100px;
    }

    .whatsapp-fab-container {
        right: 20px;
        bottom: 20px;
    }

    .whatsapp-fab {
        width: 65px;
        height: 65px;
    }

    .whatsapp-fab ion-icon {
        font-size: 34px;
    }
}
</style>

