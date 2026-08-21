<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';
import { assetUrl } from '../plugins/config';

const articles = ref([]);
const loading = ref(true);
const page = ref(1);
const totalPages = ref(1);

const fetchArticles = async (p = 1) => {
    loading.value = true;
    try {
        const response = await api.get(`/articles?page=${p}`);
        articles.value = response.data.data || response.data;
        if (response.data.last_page) {
            totalPages.value = response.data.last_page;
            page.value = response.data.current_page;
        }
    } catch (error) {
        console.error("Gagal memuat berita", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchArticles();
});
</script>

<template>
  <div class="page-container container">
    <div class="header-section text-center">
        <h2>Berita & Artikel</h2>
        <p>Informasi terbaru dari SMP Negeri 1 Muara Kaman</p>
    </div>

    <div v-if="loading" class="text-center p-4">Memuat berita...</div>
    
    <div v-else>
        <div class="articles-grid">
            <div v-for="article in articles" :key="article.id" class="article-card glass-panel">
                <div class="article-image" v-if="article.image_path">
                    <img :src="assetUrl(article.image_path)" :alt="article.title" />
                </div>
                <div class="p-3 content-area">
                    <h3 class="mb-2">{{ article.title }}</h3>
                    <p class="text-secondary text-sm mb-2">{{ new Date(article.published_at || article.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
                    <div class="article-excerpt" v-html="article.content"></div>
                    <router-link :to="`/berita/${article.slug}`" class="read-more mt-2">Baca Selengkapnya &rarr;</router-link>
                </div>
            </div>
        </div>

        <div v-if="articles.length === 0" class="text-center p-4">Belum ada berita.</div>

        <!-- Pagination -->
        <div class="pagination mt-4" v-if="totalPages > 1">
            <button class="btn-page" :disabled="page === 1" @click="fetchArticles(page - 1)">&larr; Prev</button>
            <span class="page-info">Halaman {{ page }} dari {{ totalPages }}</span>
            <button class="btn-page" :disabled="page === totalPages" @click="fetchArticles(page + 1)">Next &rarr;</button>
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

.articles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 2rem;
}

.article-card {
    overflow: hidden;
    display: flex;
    flex-direction: column;
    transition: var(--transition);
}

.article-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-lg);
}

.article-image {
    width: 100%;
    height: 220px;
    overflow: hidden;
}

.article-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.article-card:hover .article-image img {
    transform: scale(1.05);
}

.content-area {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.article-excerpt {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-bottom: 1rem;
    color: var(--text-primary);
    flex-grow: 1;
}

.read-more {
    color: var(--accent-amber);
    font-weight: 600;
    display: inline-block;
}

.read-more:hover {
    text-decoration: underline;
}

.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1rem;
    margin-top: 3rem;
}

.btn-page {
    background: white;
    border: 1px solid var(--border-color);
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    cursor: pointer;
    font-weight: 500;
}

.btn-page:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.page-info {
    color: var(--text-secondary);
}

.text-center { text-align: center; }
.p-4 { padding: 2rem; }
.p-3 { padding: 1.5rem; }
.mb-2 { margin-bottom: 0.5rem; }
.mt-2 { margin-top: 0.5rem; }
.mt-4 { margin-top: 2rem; }
.text-sm { font-size: 0.875rem; }
.text-secondary { color: var(--text-secondary); }
</style>
