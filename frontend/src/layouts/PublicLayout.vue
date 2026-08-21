<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';
import { assetUrl } from '../plugins/config';

const footer = ref({
    info: 'SMP Negeri 1 Muara Kaman',
    links: [],
    contact: 'Jl. Contoh, Muara Kaman',
    map: ''
});
const schoolLogo = ref('');
const schoolName = ref('SMPN 1 Muara Kaman');

const isMenuOpen = ref(false);
const activeDropdown = ref(null);

const toggleMenu = () => {
    isMenuOpen.value = !isMenuOpen.value;
    if (!isMenuOpen.value) activeDropdown.value = null;
};

const closeMenu = () => {
    isMenuOpen.value = false;
    activeDropdown.value = null;
};

const toggleDropdown = (menuName) => {
    if (activeDropdown.value === menuName) {
        activeDropdown.value = null;
    } else {
        activeDropdown.value = menuName;
    }
};

onMounted(async () => {
    try {
        const response = await api.get('/settings');
        if (response.data.school_logo_path) schoolLogo.value = response.data.school_logo_path;
        if (response.data.school_name) schoolName.value = response.data.school_name;
        if (response.data.footer_info) footer.value.info = response.data.footer_info;
        if (response.data.footer_contact) footer.value.contact = response.data.footer_contact;
        if (response.data.footer_map) footer.value.map = response.data.footer_map;
        if (response.data.footer_links) footer.value.links = JSON.parse(response.data.footer_links);
    } catch (error) {
        console.error("Gagal memuat pengaturan footer", error);
    }
});
</script>

<template>
  <div class="public-layout">
    <header class="navbar">
      <div class="container nav-content">
        <router-link to="/" class="logo">
          <img v-if="schoolLogo" :src="assetUrl(schoolLogo)" alt="Logo" class="logo-img" />
          <span class="logo-text">{{ schoolName }}</span>
        </router-link>

        <button class="hamburger-btn" @click="toggleMenu" aria-label="Toggle Navigation">
            <svg v-if="!isMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
        </button>

        <nav class="nav-links" :class="{ 'is-open': isMenuOpen }">
          <router-link to="/" @click="closeMenu">Beranda</router-link>
          
          <div class="dropdown" @mouseenter="activeDropdown = 'profil'" @mouseleave="activeDropdown = null" @click="toggleDropdown('profil')">
            <span class="dropdown-title" :class="{ 'text-amber': activeDropdown === 'profil' }">Profil Sekolah ▾</span>
            <div class="dropdown-content" v-show="activeDropdown === 'profil'">
              <router-link to="/profil/visi-misi" @click="closeMenu">Visi dan Misi</router-link>
              <router-link to="/profil/sejarah" @click="closeMenu">Sejarah Singkat</router-link>
              <router-link to="/profil/fasilitas" @click="closeMenu">Sarana & Prasarana</router-link>
              <router-link to="/profil/struktur-organisasi" @click="closeMenu">Struktur Organisasi</router-link>
              <router-link to="/profil/sambutan-kepsek" @click="closeMenu">Kepala Sekolah</router-link>
            </div>
          </div>

          <div class="dropdown" @mouseenter="activeDropdown = 'akademik'" @mouseleave="activeDropdown = null" @click="toggleDropdown('akademik')">
            <span class="dropdown-title" :class="{ 'text-amber': activeDropdown === 'akademik' }">Akademik ▾</span>
            <div class="dropdown-content" v-show="activeDropdown === 'akademik'">
              <router-link to="/profil/tenaga-kependidikan" @click="closeMenu">Direktori Guru & Staf</router-link>
              <router-link to="/profil/kalender-akademik" @click="closeMenu">Kalender Akademik</router-link>
              <router-link to="/profil/silabus" @click="closeMenu">Silabus</router-link>
            </div>
          </div>

          <div class="dropdown" @mouseenter="activeDropdown = 'siswa'" @mouseleave="activeDropdown = null" @click="toggleDropdown('siswa')">
            <span class="dropdown-title" :class="{ 'text-amber': activeDropdown === 'siswa' }">Siswa ▾</span>
            <div class="dropdown-content" v-show="activeDropdown === 'siswa'">
              <router-link to="/profil/ekstrakurikuler" @click="closeMenu">Ekstrakurikuler</router-link>
              <router-link to="/profil/osis" @click="closeMenu">OSIS</router-link>
              <router-link to="/profil/beasiswa" @click="closeMenu">Beasiswa</router-link>
            </div>
          </div>

          <div class="dropdown" @mouseenter="activeDropdown = 'layanan'" @mouseleave="activeDropdown = null" @click="toggleDropdown('layanan')">
            <span class="dropdown-title" :class="{ 'text-amber': activeDropdown === 'layanan' }">Layanan ▾</span>
            <div class="dropdown-content" v-show="activeDropdown === 'layanan'">
              <router-link to="/ppdb" @click="closeMenu">PPDB Online</router-link>
              <router-link to="/profil/ujian-online" @click="closeMenu">Ujian Online (CBT)</router-link>
              <router-link to="/profil/e-raport" @click="closeMenu">e-Raport</router-link>
              <router-link to="/kiosk" @click="closeMenu">Kiosk Absensi</router-link>
            </div>
          </div>

          <router-link to="/galeri" @click="closeMenu">Galeri</router-link>
        </nav>
      </div>
    </header>
    
    <main>
      <router-view></router-view>
    </main>
    
    <footer class="footer">
      <div class="container footer-grid">
        <div class="footer-col">
            <h3>Tentang Sekolah</h3>
            <p style="white-space: pre-wrap;">{{ footer.info }}</p>
        </div>
        <div class="footer-col">
            <h3>Tautan Cepat</h3>
            <ul>
                <li v-for="(link, index) in footer.links" :key="index">
                    <router-link :to="link.url">{{ link.title }}</router-link>
                </li>
            </ul>
        </div>
        <div class="footer-col">
            <h3>Kontak Kami</h3>
            <div v-html="footer.contact"></div>
        </div>
        <div class="footer-col map-col">
            <h3>Lokasi</h3>
            <div class="map-container" v-html="footer.map"></div>
        </div>
      </div>
      <div class="container text-center footer-bottom">
        <p>&copy; {{ new Date().getFullYear() }} SMP Negeri 1 Muara Kaman. All rights reserved.</p>
      </div>
    </footer>
  </div>
</template>

<style scoped>
.navbar {
  background-color: var(--primary-navy);
  color: var(--text-light);
  padding: 1rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: var(--shadow-md);
}

.nav-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  font-size: 1.5rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 0.75rem;
  text-decoration: none;
  color: var(--text-light);
}

.logo-img {
  height: 40px;
  width: auto;
  object-fit: contain;
}

.logo-text {
  background: linear-gradient(to right, var(--accent-amber), #fff);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
}

.nav-links {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}

.nav-links a {
  color: var(--text-light);
  font-weight: 500;
  transition: var(--transition);
}

.nav-links a:hover {
  color: var(--accent-amber);
}

.text-amber {
    color: var(--accent-amber) !important;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-title {
    color: var(--text-light);
    font-weight: 500;
    cursor: pointer;
    padding: 0.5rem 0;
    transition: var(--transition);
}

.dropdown-title:hover {
    color: var(--accent-amber);
}

.dropdown-content {
    position: absolute;
    background-color: var(--primary-navy-dark);
    min-width: 250px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.5);
    z-index: 101;
    border-radius: 0.5rem;
    overflow: hidden;
    top: 100%;
    left: 0;
    /* Removed margin-top to prevent mouseleave gap issue */
}

/* Pseudo element to create an invisible bridge for mouse hover */
.dropdown::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 0;
    width: 100%;
    height: 15px;
    background: transparent;
}

.dropdown-content a {
    color: var(--text-light);
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    border-bottom: 1px solid rgba(255,255,255,0.05);
    font-size: 0.95rem;
    text-align: left !important;
}

.dropdown-content a:hover {
    background-color: var(--accent-amber);
    color: var(--primary-navy-dark);
}

.btn-kiosk {
  background-color: rgba(255, 255, 255, 0.1);
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.hamburger-btn {
    display: none;
    background: none;
    border: none;
    color: white;
    cursor: pointer;
    padding: 0.5rem;
}

@media (max-width: 768px) {
    .hamburger-btn {
        display: block;
    }
    
    .nav-links {
        display: flex;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        background-color: var(--primary-navy-dark);
        flex-direction: column;
        padding: 0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2);
        gap: 0;
        z-index: 99;
        
        max-height: 0;
        opacity: 0;
        overflow-y: hidden;
        visibility: hidden;
        transition: all 0.3s ease-in-out;
    }
    
    .nav-links.is-open {
        max-height: 80vh;
        overflow-y: auto;
        opacity: 1;
        visibility: visible;
    }
    
    .nav-links > a, .dropdown {
        width: 100%;
        text-align: left;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        box-sizing: border-box;
    }
    
    .dropdown-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0;
    }
    
    .dropdown-content {
        position: static;
        box-shadow: none;
        background-color: rgba(0,0,0,0.2);
        margin-top: 1rem;
        border-radius: 0.5rem;
        width: 100%;
    }
    
    .dropdown::after {
        display: none;
    }
    
    .dropdown-content a {
        text-align: left !important;
        padding: 0.75rem 1rem;
        padding-left: 2rem;
    }
}

.footer {
  background-color: var(--primary-navy-dark);
  color: rgba(255,255,255,0.7);
  padding: 4rem 0 2rem 0;
  margin-top: 4rem;
}

.footer-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    margin-bottom: 2rem;
}

.footer-col h3 {
    color: white;
    margin-bottom: 1.5rem;
    font-size: 1.25rem;
    position: relative;
    padding-bottom: 0.5rem;
}

.footer-col h3::after {
    content: '';
    position: absolute;
    left: 0;
    bottom: 0;
    width: 50px;
    height: 2px;
    background-color: var(--accent-amber);
}

.footer-col ul { list-style: none; padding: 0; margin: 0; }
.footer-col ul li { margin-bottom: 0.75rem; }
.footer-col ul li a { color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.3s ease; }
.footer-col ul li a:hover { color: var(--accent-amber); }

.map-container {
    width: 100%;
    height: 150px;
    border-radius: 0.5rem;
    overflow: hidden;
}
.map-container :deep(iframe) {
    width: 100%;
    height: 100%;
    border: none;
}

.footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 2rem;
}

@media (max-width: 992px) {
    .footer-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 576px) {
    .footer-grid { grid-template-columns: 1fr; }
}
.text-center {
  text-align: center;
}
</style>
