<script setup>
import { ref, onMounted, watch } from 'vue';
import api from '../plugins/axios';

const attendances = ref([]);
const loading = ref(true);
const filterDate = ref(new Date().toISOString().split('T')[0]);

const fetchAttendances = async () => {
    loading.value = true;
    try {
        const response = await api.get(`/admin/attendances?date=${filterDate.value}`);
        attendances.value = response.data;
    } catch (error) {
        console.error("Gagal memuat data absensi", error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchAttendances();
});

watch(filterDate, () => {
    fetchAttendances();
});

const printReport = () => {
    window.print();
};
</script>

<template>
  <div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h1>Rekap Absensi Harian</h1>
            <div class="breadcrumb hide-print">
                <router-link to="/admin">Home</router-link>
                <span>&rsaquo;</span>
                <span>Layanan</span>
                <span>&rsaquo;</span>
                <span style="color: #111827;">Rekap Absensi</span>
            </div>
        </div>
        <button class="btn-primary hide-print" @click="printReport" style="display: flex; align-items: center; gap: 0.5rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Cetak Laporan
        </button>
    </div>

    <div class="card-panel">
        <div class="filter-bar hide-print">
            <div>
                <label class="card-label">Pilih Tanggal:</label>
                <input type="date" class="card-input" style="width: auto;" v-model="filterDate">
            </div>
            <div class="summary-stats">
                <div class="stat">Total Hadir: <strong style="color: #2563eb;">{{ attendances.length }}</strong> Siswa</div>
            </div>
        </div>

        <div v-if="loading" class="text-center mt-4 hide-print">Memuat data...</div>
        
        <div class="print-header show-print">
            <h2>Laporan Kehadiran Siswa</h2>
            <p>Tanggal: {{ new Date(filterDate).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</p>
            <p>Total Hadir: {{ attendances.length }} Siswa</p>
        </div>

        <div class="table-responsive mt-4" v-if="!loading">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jam Masuk</th>
                        <th>Jam Keluar</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(att, index) in attendances" :key="att.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ att.student?.nis }}</td>
                        <td><strong style="color: #111827;">{{ att.student?.name }}</strong></td>
                        <td>{{ att.student?.class }}</td>
                        <td>{{ att.check_in_time }}</td>
                        <td>{{ att.check_out_time || '—' }}</td>
                        <td>
                            <span style="background: #dcfce7; color: #166534; padding: 0.25rem 0.5rem; border-radius: 0.25rem; font-size: 0.75rem; font-weight: 600;">{{ att.status.toUpperCase() }}</span>
                        </td>
                    </tr>
                    <tr v-if="attendances.length === 0">
                        <td colspan="7" class="text-center" style="padding: 3rem;">Belum ada data absensi pada tanggal ini.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
  </div>
</template>

<style scoped>
.admin-page {
    padding-bottom: 2rem;
}

.filter-bar {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.summary-stats {
    background: #eff6ff;
    border: 1px solid #bfdbfe;
    color: #1e3a8a;
    padding: 0.75rem 1.25rem;
    border-radius: 0.5rem;
    font-weight: 500;
}

.table-responsive {
    overflow-x: auto;
}

.text-center { text-align: center; }
.mt-4 { margin-top: 1.5rem; }

.show-print {
    display: none;
}

.print-header {
    text-align: center;
    margin-bottom: 2rem;
}

.print-header h2 {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    color: #000;
}

.print-header p {
    margin: 0.25rem 0;
    color: #333;
}

@media print {
    /* Hide the navbar/sidebar */
    :global(.admin-layout .sidebar),
    :global(.admin-layout .top-navbar) {
        display: none !important;
    }
    
    :global(.admin-layout .main-content) {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        background: white !important;
    }

    body {
        background: white !important;
    }

    .hide-print {
        display: none !important;
    }

    .show-print {
        display: block !important;
    }

    .card-panel {
        box-shadow: none !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .table-modern {
        width: 100% !important;
        border-collapse: collapse !important;
    }

    .table-modern th, .table-modern td {
        border: 1px solid #000 !important;
        padding: 8px !important;
        color: #000 !important;
    }

    .table-modern th {
        background-color: #f3f4f6 !important;
        -webkit-print-color-adjust: exact;
    }
}
</style>
