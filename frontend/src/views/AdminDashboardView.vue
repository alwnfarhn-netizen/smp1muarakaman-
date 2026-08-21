<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';

const stats = ref({
    students: 0,
    teachers: 0,
    articles: 0,
    ppdb: 0
});

onMounted(async () => {
    try {
        const response = await api.get('/admin/dashboard');
        stats.value = response.data;
    } catch (error) {
        console.error("Gagal memuat statistik", error);
    }
});
</script>

<template>
  <div class="dashboard">
    <div class="header">
        <h2>Dashboard Overview</h2>
        <p>Ringkasan sistem informasi sekolah</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card glass-panel">
            <div class="stat-title">Total Siswa</div>
            <div class="stat-value">{{ stats.students }}</div>
        </div>
        <div class="stat-card glass-panel">
            <div class="stat-title">Total Guru</div>
            <div class="stat-value">{{ stats.teachers }}</div>
        </div>
        <div class="stat-card glass-panel">
            <div class="stat-title">Pendaftar PPDB</div>
            <div class="stat-value">{{ stats.ppdb }}</div>
        </div>
        <div class="stat-card glass-panel">
            <div class="stat-title">Total Berita</div>
            <div class="stat-value">{{ stats.articles }}</div>
        </div>
    </div>
    
    <!-- Placeholder for recent activity -->
    <div class="recent-activity glass-panel mt-4">
        <h3>Aktivitas Terbaru</h3>
        <p class="text-secondary mt-2">Belum ada aktivitas terbaru hari ini.</p>
    </div>
  </div>
</template>

<style scoped>
.dashboard {
    padding-bottom: 2rem;
}

.header {
    margin-bottom: 2rem;
}

.header h2 {
    font-size: 1.8rem;
    color: var(--primary-navy-dark);
}

.header p {
    color: var(--text-secondary);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.stat-card {
    padding: 1.5rem;
    background: white;
    border-radius: 1rem;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: var(--shadow-md);
}

.stat-title {
    color: var(--text-secondary);
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary-navy);
}

.recent-activity {
    padding: 1.5rem;
    background: white;
    border-radius: 1rem;
    box-shadow: var(--shadow-sm);
}

.mt-4 { margin-top: 2rem; }
.mt-2 { margin-top: 1rem; }
.text-secondary { color: var(--text-secondary); }
</style>
