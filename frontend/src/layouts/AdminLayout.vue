<template>
  <div class="admin-layout">
    <!-- Top Navbar -->
    <header class="admin-navbar">
      <div class="navbar-container">
        <!-- Logo -->
        <div class="logo-area">
          <img src="/logo.png" alt="Logo" class="admin-logo-img" />
          <span class="logo-text">ADMIN<span class="text-primary">-SMPN1</span></span>
        </div>

        <!-- Navigation -->
        <nav class="nav-menu">
          <router-link to="/admin" class="nav-item">Dashboard</router-link>

          <!-- Dropdown: Master Data -->
          <div class="nav-item dropdown" @mouseenter="openDropdown('master')" @mouseleave="closeDropdown">
            <span class="dropdown-trigger">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg>
              Master Data
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chevron"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </span>
            <div class="dropdown-menu" v-show="activeDropdown === 'master'">
              <router-link to="/admin/guru" class="dropdown-item">Guru & Staff</router-link>
              <router-link to="/admin/siswa" class="dropdown-item">Siswa Aktif</router-link>
            </div>
          </div>

          <!-- Dropdown: Layanan -->
          <div class="nav-item dropdown" @mouseenter="openDropdown('layanan')" @mouseleave="closeDropdown">
            <span class="dropdown-trigger">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
              Layanan
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chevron"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </span>
            <div class="dropdown-menu" v-show="activeDropdown === 'layanan'">
              <router-link to="/admin/ppdb" class="dropdown-item">Manajemen PPDB</router-link>
              <router-link to="/admin/absensi" class="dropdown-item">Rekap Absensi</router-link>
            </div>
          </div>

          <!-- Dropdown: Web Config -->
          <div class="nav-item dropdown" :class="{ active: route.path.includes('/admin/pengaturan') }" @mouseenter="openDropdown('config')" @mouseleave="closeDropdown">
            <span class="dropdown-trigger">
              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
              Web Config
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="chevron"><polyline points="6 9 12 15 18 9"></polyline></svg>
            </span>
            <div class="dropdown-menu" v-show="activeDropdown === 'config'">
              <router-link to="/admin/berita" class="dropdown-item">Berita & Artikel</router-link>
              <router-link to="/admin/galeri" class="dropdown-item">Galeri Foto</router-link>
              <router-link to="/admin/pengaturan" class="dropdown-item">Pengaturan Umum</router-link>
            </div>
          </div>
        </nav>

        <!-- Right Side (Logout) -->
        <div class="right-menu">
          <button @click="handleLogout" class="btn-logout" title="Logout">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
          </button>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="admin-content">
      <div class="container mt-4">
        <router-view></router-view>
      </div>
    </main>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter, useRoute } from 'vue-router';

const authStore = useAuthStore();
const router = useRouter();
const route = useRoute();

const activeDropdown = ref(null);

const openDropdown = (menu) => {
    activeDropdown.value = menu;
};
const closeDropdown = () => {
    activeDropdown.value = null;
};

const handleLogout = async () => {
    await authStore.logout();
    router.push('/admin/login');
}
</script>

<style scoped>
.admin-layout {
  display: flex;
  flex-direction: column;
  min-height: 100vh;
  background-color: #f3f4f6;
}

.admin-navbar {
  background-color: white;
  border-bottom: 2px solid var(--primary-navy);
  box-shadow: 0 2px 4px rgba(0,0,0,0.05);
  position: sticky;
  top: 0;
  z-index: 50;
}

.navbar-container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 2rem;
  height: 70px;
  max-width: 1400px;
  margin: 0 auto;
}

.logo-area {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.admin-logo-img {
  height: 48px;
  width: auto;
  object-fit: contain;
}

.logo-text {
  font-weight: 800;
  font-size: 1.25rem;
  color: var(--primary-navy-dark);
}
.text-primary { color: var(--primary-navy); }

.nav-menu {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  flex: 1;
  margin-left: 3rem;
}

.nav-item {
  color: #4b5563;
  font-weight: 600;
  font-size: 0.95rem;
  text-decoration: none;
  padding: 0.5rem 0.75rem;
  border-radius: 0.5rem;
  transition: all 0.2s;
  cursor: pointer;
  position: relative;
}

.nav-item:hover, .nav-item.router-link-active, .nav-item.active {
  background-color: #eff6ff;
  color: #2563eb;
}

.dropdown-trigger {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}
.icon { opacity: 0.7; }
.chevron { opacity: 0.5; margin-left: 0.25rem; }

.dropdown-menu {
  position: absolute;
  top: 100%;
  left: 0;
  min-width: 220px;
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1), 0 4px 6px -2px rgba(0,0,0,0.05);
  padding: 0.5rem;
  border: 1px solid #e5e7eb;
}

/* Pseudo element to create an invisible bridge for mouse hover */
.nav-item.dropdown::after {
  content: '';
  position: absolute;
  top: 100%;
  left: 0;
  width: 100%;
  height: 15px;
  background: transparent;
}

.dropdown-item {
  display: block;
  padding: 0.75rem 1rem;
  color: #4b5563;
  text-decoration: none;
  font-weight: 500;
  font-size: 0.9rem;
  border-radius: 0.375rem;
  transition: all 0.2s;
}

.dropdown-item:hover, .dropdown-item.router-link-active {
  background-color: #f3f4f6;
  color: #111827;
}

.right-menu {
  display: flex;
  align-items: center;
}

.btn-logout {
  background: none;
  border: none;
  color: #ef4444;
  cursor: pointer;
  padding: 0.5rem;
  border-radius: 0.375rem;
  transition: background 0.2s;
  display: flex;
  align-items: center;
  justify-content: center;
}
.btn-logout:hover {
  background-color: #fef2f2;
}

.admin-content {
  flex: 1;
  padding-bottom: 3rem;
}

.mt-4 {
    margin-top: 2rem;
}
</style>
