<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';

const articles = ref([]);
const loading = ref(true);

const showForm = ref(false);
const form = ref({
    title: '',
    slug: '',
    content: '',
    status: 'published'
});
const imageFile = ref(null);

const fetchArticles = async () => {
    loading.value = true;
    try {
        const response = await api.get('/articles'); // Reusing public endpoint for simplicity, but admin usually gets all including drafts
        articles.value = response.data.data || response.data;
    } catch (error) {
        console.error("Gagal memuat berita", error);
    } finally {
        loading.value = false;
    }
};

const handleFile = (e) => {
    imageFile.value = e.target.files[0];
};

const submitForm = async () => {
    try {
        // Auto generate slug if empty
        if (!form.value.slug) {
            form.value.slug = form.value.title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)+/g, '');
        }
        
        const formData = new FormData();
        formData.append('title', form.value.title);
        formData.append('slug', form.value.slug);
        formData.append('content', form.value.content);
        formData.append('status', form.value.status);
        if (imageFile.value) {
            formData.append('image', imageFile.value);
        }

        await api.post('/admin/articles', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        showForm.value = false;
        form.value = { title: '', slug: '', content: '', status: 'published' };
        imageFile.value = null;
        fetchArticles();
    } catch (error) {
        alert('Gagal menyimpan berita');
    }
};

const deleteArticle = async (id) => {
    if (confirm('Yakin ingin menghapus berita ini?')) {
        try {
            await api.delete(`/admin/articles/${id}`);
            fetchArticles();
        } catch (error) {
            alert('Gagal menghapus berita');
        }
    }
};

onMounted(() => {
    fetchArticles();
});
</script>

<template>
  <div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h1>Manajemen Berita</h1>
            <div class="breadcrumb">
                <router-link to="/admin">Home</router-link>
                <span>&rsaquo;</span>
                <span>Web Config</span>
                <span>&rsaquo;</span>
                <span style="color: #111827;">Berita & Artikel</span>
            </div>
        </div>
        <button class="btn-primary" @click="showForm = !showForm">
            <svg v-if="!showForm" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.5rem"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            {{ showForm ? 'Batal' : 'Tambah Baru' }}
        </button>
    </div>

    <div v-if="showForm" class="card-panel">
        <div class="card-panel-header">
            <h3>Tambah Berita Baru</h3>
        </div>
        <form @submit.prevent="submitForm">
            <div style="margin-bottom: 1rem;">
                <label class="card-label">Judul Berita</label>
                <input type="text" class="card-input" v-model="form.title" required>
            </div>
            <div style="margin-bottom: 1rem;">
                <label class="card-label">Slug (Opsional, otomatis dibuat dari judul)</label>
                <input type="text" class="card-input" v-model="form.slug">
            </div>
            
            <div style="margin-bottom: 1rem;">
                <label class="card-label">Konten Berita</label>
                <div style="border: 1px solid #d1d5db; border-radius: 0.375rem; overflow: hidden; background: white;">
                    <!-- Quill Editor -->
                    <QuillEditor v-model:content="form.content" contentType="html" theme="snow" style="min-height: 250px;" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="card-label">Gambar Sampul (Opsional)</label>
                    <input type="file" class="card-input" style="padding: 0.375rem;" accept="image/*" @change="handleFile">
                </div>
                <div>
                    <label class="card-label">Status</label>
                    <select class="card-input" v-model="form.status">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>
            
            <button type="submit" class="btn-primary" style="background-color: #2563eb; color: white;">Simpan Berita</button>
        </form>
    </div>

    <div class="card-panel" style="padding: 0; overflow: hidden;">
        <div v-if="loading" class="text-center" style="padding: 2rem;">Memuat data...</div>
        
        <div class="table-responsive" v-else>
            <table class="table-modern">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Tanggal Publikasi</th>
                        <th style="text-align: right; padding-right: 1.5rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(article, index) in articles" :key="article.id">
                        <td>{{ index + 1 }}</td>
                        <td><strong style="color: #111827;">{{ article.title }}</strong></td>
                        <td>
                            <span :style="{
                                background: article.status === 'published' ? '#dcfce7' : '#f3f4f6', 
                                color: article.status === 'published' ? '#166534' : '#374151',
                                padding: '0.25rem 0.5rem',
                                borderRadius: '0.25rem',
                                fontSize: '0.75rem',
                                fontWeight: '600'
                            }">
                                {{ article.status.toUpperCase() }}
                            </span>
                        </td>
                        <td>{{ article.published_at ? new Date(article.published_at).toLocaleDateString('id-ID') : '—' }}</td>
                        <td style="text-align: right; padding-right: 1.5rem;">
                            <!-- Edit not yet implemented, only delete is wired up -->
                            <button @click="deleteArticle(article.id)" class="btn-action delete" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="articles.length === 0">
                        <td colspan="5" class="text-center" style="padding: 3rem;">Belum ada berita.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</template>

<style scoped>
.admin-page { padding-bottom: 2rem; }
.text-center { text-align: center; }
.table-responsive { overflow-x: auto; }
</style>
<!-- Style specifically to adjust quill editor borders to match card inputs -->
<style>
.ql-toolbar.ql-snow {
    border: none !important;
    border-bottom: 1px solid #d1d5db !important;
    background-color: #f9fafb;
}
.ql-container.ql-snow {
    border: none !important;
}
</style>
