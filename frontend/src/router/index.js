import { createRouter, createWebHistory } from 'vue-router';
import PublicLayout from '../layouts/PublicLayout.vue';
import AdminLayout from '../layouts/AdminLayout.vue';
import KioskLayout from '../layouts/KioskLayout.vue';
import HomeView from '../views/HomeView.vue';

const routes = [
    {
        path: '/',
        component: PublicLayout,
        children: [
            { path: '', name: 'home', component: HomeView },
            { path: 'profil/:slug?', name: 'profil', component: () => import('../views/ProfilView.vue') },
            { path: 'galeri', name: 'galeri', component: () => import('../views/GaleriView.vue') },
            { path: 'ppdb', name: 'ppdb', component: () => import('../views/PpdbView.vue') },
            { path: 'kontak', name: 'kontak', component: () => import('../views/ContactView.vue') },
            { path: 'berita', name: 'berita', component: () => import('../views/BeritaView.vue') },
            { path: 'berita/:slug', name: 'berita-detail', component: () => import('../views/BeritaDetailView.vue') },
        ]
    },
    {
        path: '/kiosk',
        component: KioskLayout,
        children: [
            { path: '', name: 'kiosk', component: () => import('../views/KioskView.vue') }
        ]
    },
    {
        path: '/ppdb/print/:registrationNumber',
        name: 'print-ppdb',
        component: () => import('../views/PrintPpdbView.vue')
    },
    {
        path: '/admin/login',
        name: 'admin-login',
        component: () => import('../views/LoginView.vue')
    },
    {
        path: '/admin',
        component: AdminLayout,
        meta: { requiresAuth: true },
        children: [
            { path: '', name: 'admin-dashboard', component: () => import('../views/AdminDashboardView.vue') },
            { path: 'ppdb', name: 'admin-ppdb', component: () => import('../views/AdminPpdbView.vue') },
            { path: 'absensi', name: 'admin-absensi', component: () => import('../views/AdminAbsensiView.vue') },
            { path: 'berita', name: 'admin-berita', component: () => import('../views/AdminBeritaView.vue') },
            { path: 'galeri', name: 'admin-galeri', component: () => import('../views/AdminGaleriView.vue') },
            { path: 'guru', name: 'admin-guru', component: () => import('../views/AdminGuruView.vue') },
            { path: 'siswa', name: 'admin-siswa', component: () => import('../views/AdminSiswaView.vue') },
            { path: 'pengaturan', name: 'admin-pengaturan', component: () => import('../views/AdminPengaturanView.vue') }
        ]
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

import { useAuthStore } from '../stores/auth';

router.beforeEach(async (to, from, next) => {
    const authStore = useAuthStore();
    
    // Check if user is trying to access a protected route
    if (to.meta.requiresAuth) {
        if (!authStore.isAuthenticated) {
            // Attempt to fetch user if state is empty (e.g. page reload)
            await authStore.fetchUser();
        }
        
        if (!authStore.isAuthenticated) {
            next('/admin/login');
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;
