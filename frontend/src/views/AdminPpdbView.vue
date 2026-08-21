<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';
import { assetUrl } from '../plugins/config';

const registrations = ref([]);
const loading = ref(true);

const fetchRegistrations = async () => {
    loading.value = true;
    try {
        const response = await api.get('/admin/ppdb');
        registrations.value = response.data;
    } catch (error) {
        console.error("Gagal memuat data PPDB", error);
    } finally {
        loading.value = false;
    }
};

const updateStatus = async (id, status) => {
    const notes = prompt("Tambahkan catatan (opsional):");
    try {
        await api.put(`/admin/ppdb/${id}/status`, { status, notes });
        fetchRegistrations();
    } catch (error) {
        alert('Gagal mengupdate status');
    }
};

onMounted(() => {
    fetchRegistrations();
});

const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'pending': return 'badge-warning';
        case 'verified': return 'badge-info';
        case 'accepted': return 'badge-success';
        case 'rejected': return 'badge-danger';
        default: return 'badge-secondary';
    }
};
</script>

<template>
  <div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h1>Manajemen PPDB</h1>
            <div class="breadcrumb">
                <router-link to="/admin">Home</router-link>
                <span>&rsaquo;</span>
                <span>Layanan</span>
                <span>&rsaquo;</span>
                <span style="color: #111827;">Manajemen PPDB</span>
            </div>
        </div>
    </div>

    <div class="card-panel" style="padding: 0; overflow: hidden;">
        <div v-if="loading" class="text-center" style="padding: 2rem;">Memuat data...</div>
        
        <div class="table-responsive" v-else>
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>No. Registrasi</th>
                        <th>Nama Siswa</th>
                        <th>Asal Sekolah</th>
                        <th>Dokumen</th>
                        <th>Status</th>
                        <th style="text-align: right; padding-right: 1.5rem;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="reg in registrations" :key="reg.id">
                        <td><strong style="color: #111827;">{{ reg.registration_number }}</strong></td>
                        <td>{{ reg.full_name }}</td>
                        <td>{{ reg.previous_school }}</td>
                        <td>
                            <a v-if="reg.document_path" :href="assetUrl('storage/' + reg.document_path)" target="_blank" class="link-doc">Lihat PDF</a>
                            <span v-else>—</span>
                        </td>
                        <td>
                            <span :class="['badge', getStatusBadgeClass(reg.status)]">{{ reg.status.toUpperCase() }}</span>
                        </td>
                        <td style="text-align: right; padding-right: 1.5rem;">
                            <div class="action-buttons" style="justify-content: flex-end;">
                                <button v-if="reg.status === 'pending'" @click="updateStatus(reg.id, 'verified')" class="btn-sm btn-info" title="Verifikasi">Verifikasi</button>
                                <button v-if="reg.status === 'verified'" @click="updateStatus(reg.id, 'accepted')" class="btn-sm btn-success" title="Terima">Terima</button>
                                <button v-if="reg.status !== 'rejected'" @click="updateStatus(reg.id, 'rejected')" class="btn-sm btn-danger" title="Tolak">Tolak</button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="registrations.length === 0">
                        <td colspan="6" class="text-center" style="padding: 3rem;">Belum ada data pendaftar.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</template>

<style scoped>
.admin-page { padding-bottom: 2rem; }
.table-responsive { overflow-x: auto; }
.badge { padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600; }
.badge-warning { background: #fef08a; color: #854d0e; }
.badge-info { background: #dbeafe; color: #1e40af; }
.badge-success { background: #dcfce7; color: #166534; }
.badge-danger { background: #fee2e2; color: #991b1b; }
.badge-secondary { background: #f3f4f6; color: #374151; }
.link-doc { color: #2563eb; text-decoration: underline; }
.action-buttons { display: flex; gap: 0.5rem; }
.btn-sm { padding: 0.375rem 0.75rem; font-size: 0.75rem; border-radius: 0.375rem; color: white; border: none; cursor: pointer; font-weight: 500;}
.btn-info { background-color: #3b82f6; }
.btn-info:hover { background-color: #2563eb; }
.btn-success { background-color: #10b981; }
.btn-success:hover { background-color: #059669; }
.btn-danger { background-color: #ef4444; }
.btn-danger:hover { background-color: #dc2626; }
.text-center { text-align: center; }
</style>
