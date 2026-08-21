<script setup>
import { ref, onMounted, watch } from 'vue';
import { useRoute } from 'vue-router';
import api from '../plugins/axios';

const route = useRoute();
const page = ref(null);
const settings = ref(null);
const loading = ref(true);

const sidebarTitle = ref('Halaman');
const sidebarLinks = ref([]);

const menuGroups = {
    profil: [
        { title: 'Visi dan Misi', slug: 'visi-misi' },
        { title: 'Sejarah Singkat', slug: 'sejarah' },
        { title: 'Sarana & Prasarana', slug: 'fasilitas' },
        { title: 'Struktur Organisasi', slug: 'struktur-organisasi' },
        { title: 'Kepala Sekolah', slug: 'sambutan-kepsek' },
    ],
    akademik: [
        { title: 'Dir. Guru & Staf', slug: 'tenaga-kependidikan' },
        { title: 'Kalender Akademik', slug: 'kalender-akademik' },
        { title: 'Silabus', slug: 'silabus' },
    ],
    siswa: [
        { title: 'Ekstrakurikuler', slug: 'ekstrakurikuler' },
        { title: 'OSIS', slug: 'osis' },
        { title: 'Beasiswa', slug: 'beasiswa' },
    ],
    layanan: [
        { title: 'Ujian Online (CBT)', slug: 'ujian-online' },
        { title: 'e-Raport', slug: 'e-raport' },
    ]
};

const updateSidebar = (slug) => {
    let groupFound = false;
    for (const [key, links] of Object.entries(menuGroups)) {
        if (links.some(l => l.slug === slug)) {
            sidebarLinks.value = links;
            if (key === 'profil') sidebarTitle.value = 'Profil Sekolah';
            if (key === 'akademik') sidebarTitle.value = 'Akademik';
            if (key === 'siswa') sidebarTitle.value = 'Kesiswaan';
            if (key === 'layanan') sidebarTitle.value = 'Layanan Sekolah';
            groupFound = true;
            break;
        }
    }
    if (!groupFound) {
        sidebarTitle.value = 'Informasi';
        sidebarLinks.value = [];
    }
};

const fetchPage = async () => {
    loading.value = true;
    page.value = null;
    settings.value = null;
    const slug = route.params.slug || 'identitas-sekolah';
    
    try {
        updateSidebar(slug);
        
        if (slug === 'identitas-sekolah') {
            const response = await api.get('/settings');
            settings.value = response.data;
        } else {
            const response = await api.get(`/pages/${slug}`);
            page.value = response.data;
        }
    } catch (error) {
        console.error("Gagal memuat halaman", error);
    } finally {
        loading.value = false;
    }
};

onMounted(fetchPage);

watch(() => route.params.slug, () => {
    fetchPage();
});
</script>

<template>
  <div class="page-container container">
    <div class="sidebar" v-if="sidebarLinks.length > 0">
        <h3>{{ sidebarTitle }}</h3>
        <ul>
            <li v-for="link in sidebarLinks" :key="link.slug">
                <router-link :to="`/profil/${link.slug}`">{{ link.title }}</router-link>
            </li>
        </ul>
    </div>
    <div class="content-area glass-panel">
        <div v-if="loading" class="loading">Memuat...</div>
        
        <div v-else-if="settings && route.params.slug === 'identitas-sekolah' || (!route.params.slug && settings)">
            <h2>Identitas Sekolah</h2>
            <div class="page-content">
                <table class="table-modern">
                    <tbody>
                        <tr><td width="30%"><strong>Nama Sekolah</strong></td><td>{{ settings.school_name }}</td></tr>
                        <tr><td><strong>NPSN</strong></td><td>{{ settings.school_npsn }}</td></tr>
                        <tr><td><strong>Status</strong></td><td>Negeri</td></tr>
                        <tr><td><strong>Akreditasi</strong></td><td>{{ settings.school_accreditation }}</td></tr>
                        <tr><td><strong>Kepala Sekolah</strong></td><td>{{ settings.school_headmaster }}</td></tr>
                        <tr><td><strong>Alamat</strong></td><td>{{ settings.school_address }}</td></tr>
                        <tr><td><strong>Kecamatan / Kabupaten</strong></td><td>{{ settings.school_district }} / {{ settings.school_regency }}</td></tr>
                        <tr><td><strong>Provinsi</strong></td><td>{{ settings.school_province }}</td></tr>
                        <tr><td><strong>Telepon</strong></td><td>{{ settings.school_phone }}</td></tr>
                        <tr><td><strong>Email</strong></td><td>{{ settings.school_email }}</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div v-else-if="page">
            <h2>{{ page.title }}</h2>
            <div class="page-content" v-html="page.content"></div>
        </div>
        
        <div v-else class="error">
            <p>Halaman tidak ditemukan.</p>
        </div>
    </div>
  </div>
</template>

<style scoped>
.page-container {
    display: flex;
    gap: 2rem;
    padding: 4rem 1.5rem;
    min-height: 70vh;
}

.sidebar {
    width: 250px;
    flex-shrink: 0;
}

.sidebar h3 {
    margin-bottom: 1.5rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid var(--accent-amber);
}

.sidebar ul {
    list-style: none;
}

.sidebar li {
    margin-bottom: 0.5rem;
}

.sidebar a {
    display: block;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;
    transition: var(--transition);
    color: var(--text-secondary);
}

.sidebar a:hover, .sidebar a.router-link-active {
    background-color: var(--primary-navy-light);
    color: white;
}

.content-area {
    flex: 1;
    padding: 3rem;
}

.content-area h2 {
    margin-bottom: 2rem;
    font-size: 2rem;
    color: var(--primary-navy-dark);
}

.page-content :deep(h3) {
    margin-top: 1.5rem;
    margin-bottom: 0.5rem;
}

.page-content :deep(p) {
    margin-bottom: 1rem;
}

.page-content :deep(ul), .page-content :deep(ol) {
    margin-left: 1.5rem;
    margin-bottom: 1rem;
}

.page-content :deep(li) {
    margin-bottom: 0.5rem;
}

.table-modern {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.table-modern td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.table-modern tr:last-child td {
    border-bottom: none;
}

@media (max-width: 768px) {
    .page-container {
        flex-direction: column;
    }
    .sidebar {
        width: 100%;
    }
    .content-area {
        padding: 1.5rem;
    }
}
</style>
