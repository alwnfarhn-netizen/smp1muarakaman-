<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';
import { assetUrl } from '../plugins/config';

const galleries = ref([]);
const loading = ref(true);
const showForm = ref(false);

const form = ref({
    title: '',
    type: 'image',
    youtube_url: '',
    sort_order: 0
});
const imageFile = ref(null);

const fetchGalleries = async () => {
    loading.value = true;
    try {
        const response = await api.get('/galleries');
        galleries.value = response.data;
    } catch (error) {
        console.error("Gagal memuat galeri", error);
    } finally {
        loading.value = false;
    }
};

const handleFile = (e) => {
    imageFile.value = e.target.files[0];
};

const submitForm = async () => {
    const formData = new FormData();
    formData.append('title', form.value.title);
    formData.append('type', form.value.type);
    formData.append('sort_order', form.value.sort_order);
    
    if (form.value.type === 'video') {
        formData.append('youtube_url', form.value.youtube_url);
    } else if (imageFile.value) {
        formData.append('image', imageFile.value);
    }

    try {
        await api.post('/admin/galleries', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        showForm.value = false;
        form.value = { title: '', type: 'image', youtube_url: '', sort_order: 0 };
        imageFile.value = null;
        fetchGalleries();
    } catch (error) {
        alert('Gagal menyimpan galeri');
    }
};

const deleteGallery = async (id) => {
    if (confirm('Hapus item galeri ini?')) {
        try {
            await api.delete(`/admin/galleries/${id}`);
            fetchGalleries();
        } catch (error) {
            alert('Gagal menghapus');
        }
    }
};

onMounted(() => {
    fetchGalleries();
});
</script>

<template>
  <div class="admin-galeri">
    <div class="header">
        <h2>Manajemen Galeri</h2>
        <button class="btn-primary" @click="showForm = !showForm">
            {{ showForm ? 'Batal' : 'Tambah Galeri' }}
        </button>
    </div>

    <div v-if="showForm" class="glass-panel p-4 mb-4">
        <h3 class="mb-4">Tambah Galeri Baru</h3>
        <form @submit.prevent="submitForm">
            <div class="form-group">
                <label>Judul</label>
                <input type="text" v-model="form.title" required>
            </div>
            <div class="form-group">
                <label>Tipe</label>
                <select v-model="form.type">
                    <option value="image">Gambar/Foto</option>
                    <option value="video">Video YouTube</option>
                </select>
            </div>
            <div class="form-group" v-if="form.type === 'video'">
                <label>URL YouTube</label>
                <input type="url" v-model="form.youtube_url" placeholder="https://www.youtube.com/watch?v=..." required>
            </div>
            <div class="form-group" v-if="form.type === 'image'">
                <label>Upload Gambar</label>
                <input type="file" accept="image/*" @change="handleFile" required>
            </div>
            <div class="form-group">
                <label>Urutan (Sort Order)</label>
                <input type="number" v-model="form.sort_order">
            </div>
            <button type="submit" class="btn-primary">Simpan</button>
        </form>
    </div>

    <div class="glass-panel p-4">
        <div v-if="loading" class="text-center">Memuat data...</div>
        
        <div class="table-responsive" v-else>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Judul</th>
                        <th>Preview</th>
                        <th>Urutan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in galleries" :key="item.id">
                        <td>
                            <span :class="['badge', item.type === 'video' ? 'badge-info' : 'badge-success']">
                                {{ item.type.toUpperCase() }}
                            </span>
                        </td>
                        <td><strong>{{ item.title }}</strong></td>
                        <td>
                            <a v-if="item.type === 'video'" :href="item.youtube_url" target="_blank" class="link">Lihat Video</a>
                            <a v-else-if="item.image_path" :href="assetUrl(item.image_path)" target="_blank" class="link">Lihat Gambar</a>
                            <span v-else>-</span>
                        </td>
                        <td>{{ item.sort_order }}</td>
                        <td>
                            <button @click="deleteGallery(item.id)" class="btn-sm btn-danger">Hapus</button>
                        </td>
                    </tr>
                    <tr v-if="galleries.length === 0">
                        <td colspan="5" class="text-center">Belum ada data galeri.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</template>

<style scoped>
.admin-galeri { padding-bottom: 2rem; }
.header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
.header h2 { font-size: 1.8rem; color: var(--primary-navy-dark); }
.form-group { margin-bottom: 1.5rem; }
.form-group label { display: block; margin-bottom: 0.5rem; font-weight: 500; color: var(--primary-navy); }
.form-group input, .form-group select { width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 0.5rem; font-family: inherit; }
.table-responsive { overflow-x: auto; }
.data-table { width: 100%; border-collapse: collapse; }
.data-table th, .data-table td { padding: 1rem; text-align: left; border-bottom: 1px solid var(--border-color); }
.data-table th { background-color: rgba(10, 25, 47, 0.05); color: var(--primary-navy); font-weight: 600; }
.badge { padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; font-weight: bold; }
.badge-success { background: #f6ffed; color: #52c41a; border: 1px solid #b7eb8f; }
.badge-info { background: #e6f7ff; color: #1890ff; border: 1px solid #91d5ff; }
.link { color: #1890ff; text-decoration: underline; }
.btn-sm { padding: 0.25rem 0.5rem; font-size: 0.75rem; border-radius: 0.25rem; color: white; cursor: pointer; border: none; }
.btn-danger { background-color: #ff4d4f; }
.text-center { text-align: center; }
.p-4 { padding: 1.5rem; background: white; border-radius: 1rem; }
.mb-4 { margin-bottom: 1.5rem; }
</style>
