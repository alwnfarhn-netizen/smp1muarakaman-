<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';

const galleries = ref([]);
const loading = ref(true);
const activeTab = ref('foto'); // 'foto' or 'video'

onMounted(async () => {
    try {
        const response = await api.get('/galleries');
        galleries.value = response.data;
    } catch (error) {
        console.error("Gagal memuat galeri", error);
    } finally {
        loading.value = false;
    }
});

const getYoutubeId = (url) => {
    if (!url) return null;
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = url.match(regExp);
    return (match && match[2].length === 11) ? match[2] : null;
};

import { computed } from 'vue';
const filteredGalleries = computed(() => {
    return galleries.value.filter(item => {
        if (activeTab.value === 'foto') return item.type === 'image';
        return item.type === 'video';
    });
});
</script>

<template>
  <div class="page-container container">
    <div class="header-section text-center">
        <h2>Galeri Sekolah</h2>
        <p>Dokumentasi kegiatan dan fasilitas SMP Negeri 1 Muara Kaman</p>
    </div>

    <div class="tabs-container text-center mb-4">
        <button 
            class="tab-btn" 
            :class="{ active: activeTab === 'foto' }" 
            @click="activeTab = 'foto'">
            📸 Foto
        </button>
        <button 
            class="tab-btn" 
            :class="{ active: activeTab === 'video' }" 
            @click="activeTab = 'video'">
            🎥 Video
        </button>
    </div>

    <div v-if="loading" class="text-center p-4">Memuat galeri...</div>
    
    <div v-else class="gallery-grid">
        <div v-for="item in filteredGalleries" :key="item.id" class="gallery-item glass-panel">
            <template v-if="item.type === 'video' && getYoutubeId(item.youtube_url)">
                <div class="video-container">
                    <iframe 
                        :src="`https://www.youtube.com/embed/${getYoutubeId(item.youtube_url)}`" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                        allowfullscreen>
                    </iframe>
                </div>
                <div class="item-info">
                    <h4>{{ item.title }}</h4>
                </div>
            </template>
            <template v-else>
                <div class="image-container">
                    <img :src="item.image_path || 'https://via.placeholder.com/400x300?text=No+Image'" :alt="item.title" loading="lazy">
                </div>
                <div class="item-info">
                    <h4>{{ item.title }}</h4>
                </div>
            </template>
        </div>
    </div>
  </div>
</template>

<style scoped>
.page-container {
    padding: 4rem 1.5rem;
    min-height: 70vh;
}

.header-section {
    margin-bottom: 3rem;
}

.header-section h2 {
    font-size: 2.5rem;
    color: var(--primary-navy-dark);
    margin-bottom: 0.5rem;
}

.tabs-container {
    display: flex;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 2rem;
}

.tab-btn {
    padding: 0.75rem 2rem;
    border-radius: 2rem;
    border: 2px solid var(--border-color);
    background: transparent;
    font-weight: 600;
    color: var(--text-secondary);
    transition: var(--transition);
}

.tab-btn:hover {
    border-color: var(--primary-navy-light);
    color: var(--primary-navy-light);
}

.tab-btn.active {
    background-color: var(--primary-navy);
    border-color: var(--primary-navy);
    color: white;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.gallery-item {
    overflow: hidden;
    transition: var(--transition);
}

.gallery-item:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.video-container {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
    height: 0;
    overflow: hidden;
}

.video-container iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.image-container {
    width: 100%;
    height: 200px;
}

.image-container img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-info {
    padding: 1rem;
}

.item-info h4 {
    margin: 0;
    font-size: 1.1rem;
}
.text-center { text-align: center; }
.p-4 { padding: 2rem; }
</style>
