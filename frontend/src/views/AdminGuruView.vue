<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';

const teachers = ref([]);
const loading = ref(true);
const showForm = ref(false);

const form = ref({
    name: '',
    position: '',
    nip: '',
    is_active: true
});

const fetchTeachers = async () => {
    loading.value = true;
    try {
        const response = await api.get('/teachers'); // Reuse public endpoint, admin gets all though public only gets active. For now it's fine.
        teachers.value = response.data;
    } catch (error) {
        console.error("Gagal memuat data guru", error);
    } finally {
        loading.value = false;
    }
};

const submitForm = async () => {
    try {
        await api.post('/admin/teachers', form.value);
        showForm.value = false;
        form.value = { name: '', position: '', nip: '', is_active: true };
        fetchTeachers();
    } catch (error) {
        alert('Gagal menyimpan data guru');
    }
};

const deleteTeacher = async (id) => {
    if (confirm('Hapus data guru ini?')) {
        try {
            await api.delete(`/admin/teachers/${id}`);
            fetchTeachers();
        } catch (error) {
            alert('Gagal menghapus');
        }
    }
};

onMounted(() => {
    fetchTeachers();
});
</script>

<template>
  <div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h1>Manajemen Guru & Staff</h1>
            <div class="breadcrumb">
                <router-link to="/admin">Home</router-link>
                <span>&rsaquo;</span>
                <span>Master Data</span>
                <span>&rsaquo;</span>
                <span style="color: #111827;">Guru & Staff</span>
            </div>
        </div>
        <button class="btn-primary" @click="showForm = !showForm">
            <svg v-if="!showForm" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.5rem"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            {{ showForm ? 'Batal' : 'Tambah Baru' }}
        </button>
    </div>

    <div v-if="showForm" class="card-panel">
        <div class="card-panel-header">
            <h3>Tambah Data Guru</h3>
        </div>
        <form @submit.prevent="submitForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="card-label">Nama Lengkap (beserta gelar)</label>
                    <input type="text" class="card-input" v-model="form.name" required>
                </div>
                <div>
                    <label class="card-label">NIP (Opsional)</label>
                    <input type="text" class="card-input" v-model="form.nip">
                </div>
                <div>
                    <label class="card-label">Jabatan / Mata Pelajaran</label>
                    <input type="text" class="card-input" v-model="form.position" required>
                </div>
                <div>
                    <label class="card-label">Status Aktif</label>
                    <label class="toggle-switch mt-1" style="margin-top: 0.25rem;">
                        <input type="checkbox" v-model="form.is_active">
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
            <button type="submit" class="btn-primary" style="background-color: #2563eb; color: white;">Simpan Data</button>
        </form>
    </div>

    <div class="card-panel" style="padding: 0; overflow: hidden;">
        <div v-if="loading" class="text-center" style="padding: 2rem;">Memuat data...</div>
        
        <div class="table-responsive" v-else>
            <table class="table-modern">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Nama & Gelar</th>
                        <th>NIP</th>
                        <th>Jabatan</th>
                        <th>Active</th>
                        <th style="text-align: right; padding-right: 1.5rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(teacher, index) in teachers" :key="teacher.id">
                        <td>{{ index + 1 }}</td>
                        <td><strong style="color: #111827;">{{ teacher.name }}</strong></td>
                        <td>{{ teacher.nip || '—' }}</td>
                        <td>{{ teacher.position }}</td>
                        <td>
                            <label class="toggle-switch">
                                <input type="checkbox" :checked="teacher.is_active" disabled>
                                <span class="toggle-slider"></span>
                            </label>
                        </td>
                        <td style="text-align: right; padding-right: 1.5rem;">
                            <!-- Edit disabled for now, only delete is implemented in backend -->
                            <button @click="deleteTeacher(teacher.id)" class="btn-action delete" title="Hapus">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="teachers.length === 0">
                        <td colspan="6" class="text-center" style="padding: 3rem;">Belum ada data guru.</td>
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
