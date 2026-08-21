<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../plugins/axios';
import { assetUrl } from '../plugins/config';

const route = useRoute();
const router = useRouter();

const article = ref(null);
const loading = ref(true);

onMounted(async () => {
    const slug = route.params.slug;
    if (!slug) {
        router.push('/berita');
        return;
    }

    try {
        const response = await api.get(`/articles/${slug}`);
        article.value = response.data;
    } catch (error) {
        console.error("Gagal memuat detail berita", error);
        alert('Berita tidak ditemukan.');
        router.push('/berita');
    } finally {
        loading.value = false;
    }
});
</script>

<template>
  <div class="page-container container">
    <div v-if="loading" class="text-center p-4">Memuat artikel...</div>
    
    <div v-else-if="article" class="article-detail glass-panel">
        <button class="btn-back" @click="router.push('/berita')">&larr; Kembali ke Daftar Berita</button>
        
        <div class="article-header text-center">
            <h1>{{ article.title }}</h1>
            <p class="meta-info">
                Ditulis pada {{ new Date(article.published_at || article.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}
            </p>
        </div>

        <div class="article-image-container" v-if="article.image_path">
            <img :src="assetUrl(article.image_path)" :alt="article.title" class="main-image">
        </div>

        <div class="article-body" v-html="article.content"></div>
    </div>
  </div>
</template>

<style scoped>
.page-container {
    padding: 4rem 1.5rem;
    min-height: 70vh;
    max-width: 900px;
    margin: 0 auto;
}

.article-detail {
    padding: 3rem;
    border-radius: 1rem;
}

@media (max-width: 768px) {
    .article-detail {
        padding: 1.5rem;
    }
}

.btn-back {
    background: transparent;
    border: none;
    color: var(--accent-amber);
    font-weight: 600;
    cursor: pointer;
    margin-bottom: 2rem;
    padding: 0;
    display: inline-flex;
    align-items: center;
}

.btn-back:hover {
    text-decoration: underline;
}

.article-header {
    margin-bottom: 2rem;
}

.article-header h1 {
    font-size: 2.5rem;
    color: var(--primary-navy-dark);
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.meta-info {
    color: var(--text-secondary);
    font-size: 0.95rem;
}

.article-image-container {
    margin-bottom: 2rem;
    border-radius: 0.5rem;
    overflow: hidden;
}

.main-image {
    width: 100%;
    max-height: 500px;
    object-fit: cover;
    display: block;
}

.article-body {
    font-size: 1.1rem;
    line-height: 1.8;
    color: var(--text-primary);
}

/* Styling specifically for v-html content (from Quill) */
.article-body :deep(h1), 
.article-body :deep(h2), 
.article-body :deep(h3) {
    color: var(--primary-navy);
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.article-body :deep(p) {
    margin-bottom: 1rem;
}

.article-body :deep(ul), 
.article-body :deep(ol) {
    margin-bottom: 1rem;
    padding-left: 2rem;
}

.article-body :deep(img) {
    max-width: 100%;
    height: auto;
    border-radius: 0.5rem;
    margin: 1rem 0;
}

.text-center { text-align: center; }
.p-4 { padding: 2rem; }
</style>
