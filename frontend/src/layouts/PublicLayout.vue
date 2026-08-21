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
          <img src="/logo.png" alt="Logo SMPN 1 Muara Kaman" class="logo-img" />
          <div class="logo-text-wrapper">
             <div class="logo-title">SMPN 1 Muara Kaman</div>
             <div class="logo-subtitle">Berakhlak Mulia, Cerdas & Berprestasi</div>
          </div>
        </router-link>

        <button class="hamburger-btn" @click="toggleMenu" aria-label="Toggle Navigation">
            <svg v-if="!isMenuOpen" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1f2937" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1f2937" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
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
  background-color: white;
  color: #1f2937;
  padding: 0.5rem 0;
  position: sticky;
  top: 0;
  z-index: 100;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
  border-top: 4px solid #15803d; /* UI GreenMetric style border */
}

.nav-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  text-decoration: none;
  color: #1f2937;
}

.logo-img {
  height: 80px;
  width: auto;
  object-fit: contain;
}

.logo-text-wrapper {
  display: flex;
  flex-direction: column;
}

.logo-title {
  font-size: 1.25rem;
  font-weight: 800;
  color: #15803d; /* Green color matching the UI */
  line-height: 1.2;
}

.logo-subtitle {
  font-size: 0.75rem;
  color: #4b5563;
  font-weight: 500;
}

.nav-links {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}

.nav-links a {
  color: #374151;
  font-weight: 600;
  font-size: 0.95rem;
  transition: var(--transition);
  text-decoration: none;
}

.nav-links a:hover {
  color: #15803d;
}

.text-amber {
    color: #15803d !important;
}

.dropdown {
    position: relative;
    display: inline-block;
}

.dropdown-title {
    color: #374151;
    font-weight: 600;
    font-size: 0.95rem;
    cursor: pointer;
    padding: 0.5rem 0;
    transition: var(--transition);
}

.dropdown-title:hover {
    color: #15803d;
}

.dropdown-content {
    position: absolute;
    background-color: white;
    border: 1px solid #e5e7eb;
    min-width: 250px;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    z-index: 101;
    border-radius: 0.5rem;
    overflow: hidden;
    top: 100%;
    left: 0;
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
    color: #4b5563;
    padding: 12px 16px;
    text-decoration: none;
    display: block;
    border-bottom: 1px solid #f3f4f6;
    font-size: 0.95rem;
    text-align: left !important;
}

.dropdown-content a:hover {
    background-color: #f3f4f6;
    color: #111827;
}

.btn-kiosk {
  background-color: rgba(0, 0, 0, 0.05);
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  border: 1px solid rgba(0, 0, 0, 0.1);
  color: #1f2937;
}

.hamburger-btn {
    display: none;
    background: none;
    border: none;
    color: #1f2937;
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
        background-color: white;
        flex-direction: column;
        padding: 0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        gap: 0;
        z-index: 99;
        border-top: 1px solid #e5e7eb;
        
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
        border-bottom: 1px solid #f3f4f6;
        box-sizing: border-box;
    }
    
    .dropdown-title {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0;
        color: #374151;
    }
    
    .dropdown-content {
        position: static;
        box-shadow: none;
        background-color: #f9fafb;
        margin-top: 1rem;
        border-radius: 0.5rem;
        width: 100%;
        border: none;
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
