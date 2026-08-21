<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import api from '../plugins/axios';
import { assetUrl } from '../plugins/config';

const settings = ref({});
const headmasterPage = ref(null);
const latestArticles = ref([]);

const sliders = ref([]);
const addons = ref([]);
const currentSlide = ref(0);
let slideInterval = null;

const nextSlide = () => {
    if (sliders.value.length === 0) return;
    currentSlide.value = (currentSlide.value + 1) % sliders.value.length;
};

onMounted(async () => {
    try {
        const [settingsRes, pageRes, articlesRes] = await Promise.all([
            api.get('/settings').catch(() => ({ data: {} })),
            api.get('/pages/sambutan-kepsek').catch(() => ({ data: null })),
            api.get('/articles').catch(() => ({ data: { data: [] } }))
        ]);
        
        settings.value = settingsRes.data || {};
        headmasterPage.value = pageRes.data;
        latestArticles.value = (articlesRes.data.data || []).slice(0, 3);
        
        if (settingsRes.data.homepage_sliders && settingsRes.data.homepage_sliders !== '[]') {
            sliders.value = JSON.parse(settingsRes.data.homepage_sliders);
        } else {
            // Default slider jika belum ada data di database
            sliders.value = [
                { 
                    title: settings.value.school_name || 'SMP Negeri 1 Muara Kaman', 
                    subtitle: settings.value.school_motto || 'Melahirkan generasi berprestasi, berakhlak mulia, dan berwawasan global.', 
                    image: 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=1200&q=80' 
                },
                { 
                    title: 'Fasilitas Terbaik', 
                    subtitle: 'Mendukung kenyamanan dan kualitas belajar siswa.', 
                    image: 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=1200&q=80' 
                }
            ];
        }

        if (sliders.value.length > 1) {
            slideInterval = setInterval(nextSlide, 5000);
        }

        if (settingsRes.data.homepage_addons) {
            addons.value = JSON.parse(settingsRes.data.homepage_addons);
        }
    } catch (error) {
        console.error("Failed to fetch home data", error);
    }
});

onUnmounted(() => {
    if (slideInterval) clearInterval(slideInterval);
});
</script>

<template>
  <div class="home-view">
    <!-- Dynamic Slider Hero -->
    <section class="hero-slider" v-if="sliders.length > 0">
      <div v-for="(slide, index) in sliders" :key="index" 
           class="slide-item" 
           :class="{ active: currentSlide === index }"
           :style="{ backgroundImage: `url('${assetUrl(slide.image)}')` }">
        <div class="overlay"></div>
      </div>
      
      <!-- Static Text Overlay -->
      <div class="container hero-content relative z-10">
        <div class="hero-text">
          <h1>Selamat Datang di<br><span class="text-gradient">{{ settings.school_name || 'SMP Negeri 1 Muara Kaman' }}</span></h1>
          <p>{{ settings.school_motto || 'Melahirkan generasi yang beriman, berakhlak mulia, cerdas, kreatif, berprestasi, berbudaya, dan berwawasan global.' }}</p>
          <div class="hero-actions">
            <router-link to="/ppdb" class="btn-primary">Info PPDB</router-link>
            <router-link to="/profil" class="btn-secondary">Jelajahi Profil</router-link>
          </div>
        </div>
      </div>
      
      <!-- Slider Controls -->
      <div class="slider-controls" v-if="sliders.length > 1">
        <div v-for="(_, index) in sliders" :key="'dot-'+index" 
             class="dot" :class="{ active: currentSlide === index }"
             @click="currentSlide = index"></div>
      </div>
    </section>

    <!-- Fallback Static Hero -->
    <section class="hero" v-else>
      <div class="container hero-content">
        <div class="hero-text">
          <h1>Selamat Datang di<br><span class="text-gradient">{{ settings.school_name || 'SMP Negeri 1 Muara Kaman' }}</span></h1>
          <p>{{ settings.school_motto || 'Melahirkan generasi yang beriman, berakhlak mulia, cerdas, kreatif, berprestasi, berbudaya, dan berwawasan global.' }}</p>
          <div class="hero-actions">
            <router-link to="/ppdb" class="btn-primary">Info PPDB</router-link>
            <router-link to="/profil" class="btn-secondary">Jelajahi Profil</router-link>
          </div>
        </div>
      </div>
    </section>

    <!-- Quick Access Portal -->
    <div class="container quick-access">
        <div class="quick-grid">
            <router-link to="/ppdb" class="quick-card glass-panel">
                <div class="quick-icon text-amber">📝</div>
                <h3>PPDB Online</h3>
                <p>Pendaftaran Siswa Baru</p>
            </router-link>
            <router-link to="/profil/e-raport" class="quick-card glass-panel">
                <div class="quick-icon text-amber">📊</div>
                <h3>e-Raport</h3>
                <p>Portal Nilai Akademik</p>
            </router-link>
            <router-link to="/profil/ujian-online" class="quick-card glass-panel">
                <div class="quick-icon text-amber">💻</div>
                <h3>Ujian Online</h3>
                <p>CBT & Try Out Siswa</p>
            </router-link>
            <router-link to="/kiosk" class="quick-card glass-panel">
                <div class="quick-icon text-amber">👆</div>
                <h3>Kiosk Absensi</h3>
                <p>Sistem Kehadiran Digital</p>
            </router-link>
        </div>
    </div>

    <!-- Dynamic Body Addons -->
    <section v-for="(addon, index) in addons" :key="'addon-'+index" class="container mt-4 z-10 relative">
        <div class="glass-panel p-4">
            <h2 class="mb-3">{{ addon.title }}</h2>
            <div class="content" v-html="addon.content"></div>
        </div>
    </section>

    <section class="sambutan container" v-if="headmasterPage">
      <div class="glass-panel p-4 sambutan-wrapper">
        <div class="sambutan-image-col">
            <div class="sambutan-image-placeholder">
                <img src="https://images.unsplash.com/photo-1544717302-de2939b7ef71?w=500&q=80" alt="Kepala Sekolah" class="sambutan-img">
            </div>
        </div>
        <div class="sambutan-content-col">
            <h2 class="text-gradient">{{ headmasterPage.title }}</h2>
            <div class="content mt-2 text-secondary" v-html="headmasterPage.content"></div>
        </div>
      </div>
    </section>

    <!-- Statistik Sekolah -->
    <section class="stats-section mt-5 py-5 text-center">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-number text-gradient">{{ settings.school_student_count || '357' }}</div>
                    <div class="stat-label">Siswa Aktif</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number text-gradient">18</div>
                    <div class="stat-label">Tenaga Pendidik</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number text-gradient">{{ settings.school_accreditation || 'B' }}</div>
                    <div class="stat-label">Akreditasi</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number text-gradient">5+</div>
                    <div class="stat-label">Ekstrakurikuler</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Fasilitas Section -->
    <section class="fasilitas container mt-4">
      <div class="text-center mb-3">
        <h2>Fasilitas Kami</h2>
        <p class="text-secondary">Dukungan sarana dan prasarana terbaik untuk proses belajar mengajar</p>
      </div>
      <div class="fasilitas-grid">
        <div class="fasilitas-card text-center" style="background-image: linear-gradient(to top, rgba(10,25,47,0.9), rgba(10,25,47,0.2)), url('https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=400&q=80');">
            <h3>Ruang Kelas</h3>
        </div>
        <div class="fasilitas-card text-center" style="background-image: linear-gradient(to top, rgba(10,25,47,0.9), rgba(10,25,47,0.2)), url('https://images.unsplash.com/photo-1532094349884-543bc11b234d?w=400&q=80');">
            <h3>Lab. IPA</h3>
        </div>
        <div class="fasilitas-card text-center" style="background-image: linear-gradient(to top, rgba(10,25,47,0.9), rgba(10,25,47,0.2)), url('https://images.unsplash.com/photo-1516321497487-e288fb19713f?w=400&q=80');">
            <h3>Lab. Komputer</h3>
        </div>
        <div class="fasilitas-card text-center" style="background-image: linear-gradient(to top, rgba(10,25,47,0.9), rgba(10,25,47,0.2)), url('https://images.unsplash.com/photo-1568661081541-663806aeb734?w=400&q=80');">
            <h3>Perpustakaan</h3>
        </div>
        <div class="fasilitas-card text-center" style="background-image: linear-gradient(to top, rgba(10,25,47,0.9), rgba(10,25,47,0.2)), url('https://images.unsplash.com/photo-1564683214964-b31c4f107f9c?w=400&q=80');">
            <h3>Musholla</h3>
        </div>
        <div class="fasilitas-card text-center" style="background-image: linear-gradient(to top, rgba(10,25,47,0.9), rgba(10,25,47,0.2)), url('https://images.unsplash.com/photo-1526676037777-05a232554f77?w=400&q=80');">
            <h3>Lapangan</h3>
        </div>
      </div>
    </section>

    <!-- Ekstrakurikuler Showcase -->
    <section class="ekskul container mt-5">
      <div class="text-center mb-3">
        <h2>Ekstrakurikuler Unggulan</h2>
        <p class="text-secondary">Wadah pengembangan bakat dan minat siswa di luar jam akademik</p>
      </div>
      <div class="ekskul-grid">
        <div class="ekskul-card" style="background-image: linear-gradient(to top, rgba(10,25,47,0.9), rgba(10,25,47,0.2)), url('https://images.unsplash.com/photo-1542385151-efd9000785a0?w=500&q=80');">
            <div class="ekskul-content">
                <h3>Pramuka</h3>
                <p>Ekskul Wajib</p>
            </div>
        </div>
        <div class="ekskul-card" style="background-image: linear-gradient(to top, rgba(10,25,47,0.9), rgba(10,25,47,0.2)), url('https://images.unsplash.com/photo-1526232761682-d26e03ac148e?w=500&q=80');">
            <div class="ekskul-content">
                <h3>Olimpiade Sains</h3>
                <p>Pengembangan Akademik</p>
            </div>
        </div>
        <div class="ekskul-card" style="background-image: linear-gradient(to top, rgba(10,25,47,0.9), rgba(10,25,47,0.2)), url('https://images.unsplash.com/photo-1518622112959-1f4864c86cb3?w=500&q=80');">
            <div class="ekskul-content">
                <h3>Olahraga Prestasi</h3>
                <p>Futsal & Bola Voli</p>
            </div>
        </div>
      </div>
      <div class="text-center mt-5">
        <router-link to="/profil/ekstrakurikuler" class="btn-secondary" style="color: var(--primary-navy-dark); border-color: var(--primary-navy-dark);">Lihat Semua Ekstrakurikuler</router-link>
      </div>
    </section>

    <section class="berita-terbaru container mt-4" v-if="latestArticles.length > 0">
      <h2 class="mb-3">Berita Terbaru</h2>
      <div class="articles-grid">
        <div v-for="article in latestArticles" :key="article.id" class="article-card glass-panel">
            <div class="article-image" v-if="article.image_path">
                <img :src="assetUrl(article.image_path)" :alt="article.title" />
            </div>
            <div class="p-3">
                <h3 class="mb-2">{{ article.title }}</h3>
                <p class="text-secondary text-sm mb-2">{{ new Date(article.published_at).toLocaleDateString('id-ID') }}</p>
                <div class="article-excerpt" v-html="article.content"></div>
                <router-link :to="'/profil'" class="read-more mt-2">Baca Selengkapnya</router-link>
            </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
.hero {
  background-color: var(--primary-navy-light);
  color: white;
  padding: 6rem 0;
  min-height: 80vh;
  display: flex;
  align-items: center;
}

.hero-content {
  display: grid;
  grid-template-columns: 1fr;
  gap: 2rem;
}

.hero-text h1 {
  font-size: 3.5rem;
  margin-bottom: 1.5rem;
  color: white;
}

.hero-text p {
  font-size: 1.25rem;
  color: rgba(255, 255, 255, 0.8);
  margin-bottom: 2rem;
  max-width: 600px;
}

.hero-actions {
  display: flex;
  gap: 1rem;
}

.btn-secondary {
  background-color: transparent;
  color: white;
  border: 1px solid rgba(255, 255, 255, 0.3);
  font-weight: 600;
  padding: 0.75rem 1.5rem;
  border-radius: 0.5rem;
  transition: var(--transition);
  display: inline-flex;
  align-items: center;
  justify-content: center;
}

.btn-secondary:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.sambutan {
  margin-top: 3rem;
  position: relative;
  z-index: 10;
}

.quick-access {
    margin-top: -3rem;
    position: relative;
    z-index: 20;
}

.quick-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
}

.quick-card {
    text-align: center;
    padding: 2rem 1rem;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    color: var(--text-primary);
    will-change: transform;
    backface-visibility: hidden;
}

.quick-card:hover {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.quick-icon {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.quick-card h3 {
    font-size: 1.1rem;
    margin-bottom: 0.5rem;
    color: var(--primary-navy-dark);
}

.quick-card p {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.stats-section {
    background-color: var(--primary-navy);
    padding: 4rem 0;
    margin-top: 4rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
}

.stat-item {
    position: relative;
}

@media (min-width: 993px) {
    .stat-item:not(:last-child)::after {
        content: '';
        position: absolute;
        right: -1rem;
        top: 15%;
        height: 70%;
        width: 1px;
        background-color: rgba(255,255,255,0.2);
    }
}

.stat-number {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: rgba(255,255,255,0.8);
    font-size: 1.1rem;
    font-weight: 500;
}

.ekskul-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
}

.ekskul-card {
    height: 250px;
    background-size: cover;
    background-position: center;
    border-radius: 1rem;
    display: flex;
    align-items: flex-end;
    padding: 1.5rem;
    color: white;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    will-change: transform;
    backface-visibility: hidden;
    overflow: hidden;
}

.ekskul-card:hover {
    opacity: 0.95;
}

.ekskul-content h3 {
    color: white;
    margin-bottom: 0.25rem;
}

.ekskul-content p {
    color: rgba(255,255,255,0.8);
    font-size: 0.9rem;
}

.p-4 {
  padding: 2rem;
}
.mt-2 {
  margin-top: 1rem;
}
.mt-4 { margin-top: 2rem; }
.mb-2 { margin-bottom: 0.5rem; }
.mb-3 { margin-bottom: 1.5rem; }
.p-3 { padding: 1.5rem; }
.text-secondary { color: var(--text-secondary); }
.text-sm { font-size: 0.875rem; }
.articles-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 2rem;
}
.fasilitas-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}
.fasilitas-card {
    height: 180px;
    background-size: cover;
    background-position: center;
    border-radius: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
}
.fasilitas-card:hover {
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.fasilitas-card h3 {
    color: white;
    margin: 0;
    font-size: 1.5rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.6);
}

.sambutan-wrapper {
    display: flex;
    gap: 3rem;
    align-items: center;
}
.sambutan-image-col {
    flex: 1;
}
.sambutan-content-col {
    flex: 1.5;
}
.sambutan-image-placeholder {
    width: 100%;
    height: 400px;
    background-color: var(--primary-navy-light);
    border-radius: 1.5rem;
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}
.sambutan-img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}
.sambutan-wrapper:hover .sambutan-img {
    opacity: 0.95;
}

@media (max-width: 992px) {
    .quick-grid { grid-template-columns: repeat(2, 1fr); }
    .stats-grid { grid-template-columns: repeat(2, 1fr); }
    .ekskul-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 768px) {
    .hero-text h1 { font-size: 2.25rem; }
    .hero-text p { font-size: 1rem; margin-bottom: 1.5rem; }
    .hero-slider, .hero { min-height: 60vh; height: 60vh; padding: 4rem 0; }
    .slide-item { height: 100%; }
    .articles-grid { grid-template-columns: 1fr; }
    .fasilitas-grid { grid-template-columns: repeat(2, 1fr); }
    .quick-grid { grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .quick-card { padding: 1.5rem 0.5rem; }
    .quick-icon { font-size: 2rem; }
    .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
    .stat-number { font-size: 2rem; }
    .ekskul-grid { grid-template-columns: 1fr; }
    .sambutan-wrapper { flex-direction: column; text-align: center; }
    .sambutan-image-placeholder { height: 250px; }
}

@media (max-width: 480px) {
    .hero-text h1 { font-size: 1.75rem; }
    .hero-actions { flex-direction: column; width: 100%; }
    .hero-actions a { width: 100%; }
    .quick-grid { grid-template-columns: 1fr; }
    .stats-grid { grid-template-columns: 1fr; }
    .fasilitas-grid { grid-template-columns: 1fr; }
}
.article-card { overflow: hidden; display: flex; flex-direction: column; }
.article-image { width: 100%; height: 200px; overflow: hidden; }
.article-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.3s ease; }
.article-card:hover .article-image img { opacity: 0.95; }
.article-excerpt {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 1rem;
    color: var(--text-primary);
}
.read-more {
    color: var(--accent-amber);
    font-weight: 600;
    display: inline-block;
}
.read-more:hover { text-decoration: underline; }

.hero-slider {
    position: relative;
    height: 80vh;
    overflow: hidden;
    background-color: var(--primary-navy-dark);
    display: flex;
    align-items: center;
}
.slide-item {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background-size: cover;
    background-position: center;
    opacity: 0;
    transition: opacity 1s ease-in-out;
    display: flex;
    align-items: center;
    justify-content: center;
}
.slide-item.active {
    opacity: 1;
    z-index: 1;
}
.overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(10, 25, 47, 0.6);
}
.slide-title {
    font-size: 3.5rem;
    color: white;
    margin-bottom: 1rem;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}
.slide-subtitle {
    font-size: 1.5rem;
    color: rgba(255,255,255,0.9);
    text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
}
.slider-controls {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 1rem;
    z-index: 20;
}
.dot {
    width: 12px; height: 12px;
    border-radius: 50%;
    background: rgba(255,255,255,0.5);
    cursor: pointer;
    transition: background 0.3s;
}
.dot.active {
    background: white;
}
.z-10 { z-index: 10; }
.relative { position: relative; }
</style>
