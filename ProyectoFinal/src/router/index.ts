import { createRouter, createWebHistory } from '@ionic/vue-router';
import { RouteRecordRaw } from 'vue-router';
import VueRouter from 'vue-router'
import Inicio from '../views/Inicio.vue'
import HistorialFinanzas from '../views/HistorialFinanzas.vue'
import Login from '../views/Login.vue'
import politica from '@/views/politica.vue'
import AyudaSoporte from '../views/AyudaSoporte.vue'
import Calendario from '../views/Calendario.vue'
import Finanzas from '../views/Finanzas.vue'
import Registro from '@/views/Registro.vue';
import Perfil from '@/views/Perfil.vue';
import ViewError from '@/views/ViewError.vue';

const routes: Array<RouteRecordRaw> = [
    {
        path: '/',
        redirect: '/inicio'
    },
    {
        path: '/inicio',
        name: 'Inicio',
        component: Inicio
    },
    {
        path: '/historial',
        name: 'Historial',
        component: HistorialFinanzas,
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'Login',
        component: Login
    },
    {
        path: '/politica',
        name: 'politica',
        component: politica,
        meta: { requiresAuth: true }
    },
    {
        path: '/calendario',
        name: 'Calendario',
        component: Calendario,
        meta: { requiresAuth: true }
    },
    {
        path: '/ayuda-soporte',
        name: 'AyudaSoporte',
        component: AyudaSoporte,
        meta: { requiresAuth: true }
    },
    {
        path: '/finanzas',
        name: 'Finanzas',
        component: Finanzas,
        meta: { requiresAuth: true }

    },
    {
        path: '/registro',
        name: 'Registro',
        component: Registro
    },
    {
        path: '/perfil',
        name: 'Perfil',
        component: Perfil,
        meta: { requiresAuth: true }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'ViewError',
        component: ViewError
    }
]

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes
});
router.beforeEach((to, from, next) => {
    const requiresAuth = to.matched.some(record => record.meta.requiresAuth);
    const token = localStorage.getItem('auth_token');
    if (requiresAuth && !token) {
        next({ path: '/login' });
    } else {
        next();
    }
});

export default router