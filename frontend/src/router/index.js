import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: () => import('../views/Home.vue')
    },
    {
        path: '/tours',
        name: 'Tours',
        component: () => import('../views/Tours.vue')
    },
    {
        path: '/tours/:id',
        name: 'TourDetails',
        component: () => import('../views/TourDetails.vue')
    },
    {
        path: '/destinations',
        name: 'Destinations',
        component: () => import('../views/Destinations.vue')
    },
    {
        path: '/destinations/:id',
        name: 'DestinationDetails',
        component: () => import('../views/DestinationDetails.vue')
    },
    {
        path: '/contact',
        name: 'Contact',
        component: () => import('../views/Contact.vue')
    },
    {
        path: '/login',
        name: 'Login',
        component: () => import('../views/Login.vue'),
        meta: { hideLayout: true }
    },
    {
        path: '/register',
        name: 'Register',
        component: () => import('../views/Register.vue'),
        meta: { hideLayout: true }
    },
    {
        path: '/forgot-password',
        name: 'ForgotPassword',
        component: () => import('../views/ForgotPassword.vue'),
        meta: { hideLayout: true }
    },
    {
        path: '/profile',
        name: 'UserProfile',
        component: () => import('../views/UserProfile.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/dashboard',
        name: 'Dashboard',
        component: () => import('../views/Dashboard.vue'),
        meta: { requiresAuth: true }
    },
    {
        path: '/admin',
        name: 'AdminDashboard',
        component: () => import('../views/AdminDashboard.vue'),
        meta: { requiresAuth: true, requiresAdmin: true, hideLayout: true }
    },

    {
        path: '/checkout/:id',
        name: 'Checkout',
        component: () => import('../views/Checkout.vue'),
        meta: { requiresAuth: true, hideLayout: true }
    },
    {
        path: '/payment/success',
        name: 'PaymentSuccess',
        component: () => import('../views/PaymentSuccess.vue'),
        meta: { requiresAuth: true, hideLayout: true }
    },
    {
        path: '/payment/failed',
        name: 'PaymentFailed',
        component: () => import('../views/PaymentFailed.vue'),
        meta: { requiresAuth: true, hideLayout: true }
    },
    {
        path: '/admin/login',
        // ...
        name: 'AdminLogin',
        component: () => import('../views/AdminLogin.vue'),
        meta: { hideLayout: true }
    }
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const authStore = useAuthStore()

    if (to.meta.requiresAuth && !authStore.isAuthenticated) {
        next('/login')
    } else if (to.meta.requiresAdmin && !authStore.isAdmin) {
        next('/')
    } else {
        next()
    }
})

export default router
