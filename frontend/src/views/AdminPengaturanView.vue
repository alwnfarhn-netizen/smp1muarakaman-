<template>
  <div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h1>Pengaturan Umum & Konten</h1>
            <div class="breadcrumb">
                <router-link to="/admin">Home</router-link>
                <span>&rsaquo;</span>
                <span>Web Config</span>
                <span>&rsaquo;</span>
                <span style="color: #111827;">Pengaturan Umum</span>
            </div>
        </div>
    </div>

    <div v-if="loading" class="text-center p-4">Memuat data...</div>

    <div v-else>
        <!-- Informasi Umum (Global Config) -->
        <div class="card-panel">
            <div class="card-panel-header">
                <h3>Konfigurasi Sekolah</h3>
            </div>
            <form @submit.prevent="saveSettings">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label class="card-label">Nama Sekolah</label>
                        <input type="text" class="card-input" v-model="settings.school_name" required>
                    </div>
                    <div>
                        <label class="card-label">Moto Sekolah</label>
                        <input type="text" class="card-input" v-model="settings.school_motto" required>
                    </div>
                    <div>
                        <label class="card-label">Email Kontak</label>
                        <input type="email" class="card-input" v-model="settings.contact_email">
                    </div>
                    <div>
                        <label class="card-label">Telepon Kontak</label>
                        <input type="text" class="card-input" v-model="settings.contact_phone">
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label class="card-label">Alamat Lengkap</label>
                        <textarea class="card-input" v-model="settings.address" rows="3"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="background-color: #2563eb; color: white;">Update Konfigurasi</button>
            </form>
        </div>

        <!-- Halaman Profil Editor -->
        <div class="card-panel" style="background-color: transparent; border: none; box-shadow: none; padding: 0;">
            <div class="card-panel-header" style="background: white; padding: 1.5rem; border-radius: 0.5rem; border: 1px solid #e5e7eb; box-shadow: 0 1px 3px 0 rgba(0,0,0,0.1);">
                <h3>Konten Halaman Profil</h3>
            </div>
            
            <!-- Tabs Navigation -->
            <div class="tabs-nav" style="display: flex; gap: 0.5rem; margin: 1rem 0; overflow-x: auto; padding-bottom: 0.5rem;">
                <button v-for="slug in availableSlugs" 
                        :key="slug" 
                        @click="activeTab = slug" 
                        :class="['btn-tab', { active: activeTab === slug }]">
                    {{ slug.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) }}
                </button>
            </div>

            <!-- Tab Content -->
            <div class="card-panel" v-if="activeTab" style="margin-top: 1rem;">
                <div class="card-panel-header">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                        <h3 style="color: #2563eb;">Page Content: {{ activeTab.replace(/-/g, ' ').toUpperCase() }}</h3>
                    </div>
                </div>
                
                <p style="font-size: 0.875rem; color: #6b7280; margin-bottom: 1rem;">Enter localized page content.</p>
                <div style="border: 1px solid #d1d5db; border-radius: 0.375rem; overflow: hidden; background: white; margin-bottom: 1.5rem;">
                    <!-- Quill Editor -->
                    <QuillEditor v-model:content="pages[activeTab]" contentType="html" theme="snow" style="min-height: 400px;" />
                </div>
                
                <div style="background: #f9fafb; padding: 1rem; margin: -1.5rem -1.5rem 0 -1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end;">
                    <button class="btn-primary" style="background-color: #1e3a8a; color: white;" @click="savePage(activeTab)">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.5rem"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                        Update Page
                    </button>
                </div>
            </div>
        </div>

        <!-- Builder Beranda & Footer -->
        <div class="card-panel">
            <div class="card-panel-header">
                <h3>Builder Beranda & Footer</h3>
            </div>
            
            <!-- Slider -->
            <div style="margin-bottom: 2rem;">
                <label class="card-label" style="font-size: 1rem; color: #111827; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">Slider Hero (Gambar Beranda)</label>
                <div v-for="(slide, index) in homepage_sliders" :key="index" class="slide-card mt-2">
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="flex: 1;">
                            <label class="card-label">Gambar Slider</label>
                            <input type="file" accept="image/*" @change="e => uploadSliderImage(e, index)" class="card-input" style="padding: 0.375rem;">
                            <div v-if="slide.image" class="mt-2"><img :src="assetUrl(slide.image)" class="thumb"></div>
                        </div>
                        <div style="flex: 2;">
                            <label class="card-label">Judul Teks</label>
                            <input type="text" class="card-input mb-2" v-model="slide.title">
                            <label class="card-label mt-2">Subjudul Teks</label>
                            <input type="text" class="card-input" v-model="slide.subtitle">
                        </div>
                        <div>
                            <button type="button" class="btn-action delete mt-4" @click="removeSlide(index)" title="Hapus Slide">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-secondary mt-2" @click="addSlide">+ Tambah Slide</button>
            </div>

            <!-- Body Addons -->
            <div style="margin-bottom: 2rem;">
                <label class="card-label" style="font-size: 1rem; color: #111827; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">Body Addons (Konten Tambahan Beranda)</label>
                <div v-for="(addon, index) in homepage_addons" :key="'a'+index" class="slide-card mt-2">
                    <div style="display: flex; gap: 1rem; align-items: flex-start;">
                        <div style="flex: 1;">
                            <label class="card-label">Judul Bagian</label>
                            <input type="text" class="card-input" v-model="addon.title">
                        </div>
                        <div style="flex: 2;">
                            <label class="card-label">Isi Konten (HTML)</label>
                            <textarea class="card-input" v-model="addon.content" rows="3"></textarea>
                        </div>
                        <div>
                            <button type="button" class="btn-action delete mt-4" @click="removeAddon(index)" title="Hapus Bagian">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-secondary mt-2" @click="addAddon">+ Tambah Bagian</button>
            </div>

            <!-- Footer -->
            <div>
                <label class="card-label" style="font-size: 1rem; color: #111827; border-bottom: 1px solid #e5e7eb; padding-bottom: 0.5rem;">Footer 4 Kolom</label>
                <div class="grid-4-cols mt-2">
                    <div>
                        <label class="card-label">Kolom 1: Info Sekolah</label>
                        <textarea class="card-input" v-model="footer_info" rows="6"></textarea>
                    </div>
                    <div>
                        <label class="card-label">Kolom 2: Tautan Cepat</label>
                        <div v-for="(link, i) in footer_links" :key="'l'+i" style="display: flex; gap: 0.25rem; margin-bottom: 0.5rem;">
                            <input type="text" class="card-input" v-model="link.title" placeholder="Teks Link" style="margin:0;">
                            <input type="text" class="card-input" v-model="link.url" placeholder="URL" style="margin:0;">
                            <button type="button" class="btn-action delete" style="margin:0;" @click="removeFooterLink(i)">×</button>
                        </div>
                        <button type="button" class="btn-secondary" style="font-size: 0.75rem; padding: 0.25rem 0.5rem;" @click="addFooterLink">+ Tambah Link</button>
                    </div>
                    <div>
                        <label class="card-label">Kolom 3: Kontak Kami (HTML)</label>
                        <textarea class="card-input" v-model="footer_contact" rows="6"></textarea>
                    </div>
                    <div>
                        <label class="card-label">Kolom 4: Peta Lokasi (Iframe)</label>
                        <textarea class="card-input" v-model="footer_map" rows="6" placeholder='<iframe src="..."></iframe>'></textarea>
                    </div>
                </div>
            </div>

            <div style="background: #f9fafb; padding: 1rem; margin: 1.5rem -1.5rem -1.5rem -1.5rem; border-top: 1px solid #e5e7eb; display: flex; justify-content: flex-end;">
                <button type="button" class="btn-primary" style="background-color: #2563eb; color: white;" @click="saveBuilderSettings">Update Builder</button>
            </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';
import { assetUrl } from '../plugins/config';

const availableSlugs = [
    'identitas-sekolah', 'sejarah', 'visi-misi', 'struktur-organisasi', 'fasilitas', 'sambutan-kepsek',
    'program-kerja', 'kondisi-siswa', 'komite-sekolah', 'prestasi-siswa',
    'tenaga-kependidikan', 'silabus', 'ujian-online', 'kalender-akademik',
    'direktori-siswa', 'ekstrakurikuler', 'osis', 'beasiswa', 'e-raport',
    'direktori-alumni', 'info-alumni'
];

const activeTab = ref('identitas-sekolah');

const settings = ref({
    school_name: '',
    school_motto: '',
    contact_email: '',
    contact_phone: '',
    address: ''
});

const pages = ref({});
availableSlugs.forEach(slug => {
    pages.value[slug] = '';
});

const homepage_sliders = ref([]);
const homepage_addons = ref([]);
const footer_info = ref('');
const footer_links = ref([]);
const footer_contact = ref('');
const footer_map = ref('');

const loading = ref(true);

const fetchSettings = async () => {
    try {
        const response = await api.get('/settings');
        Object.keys(response.data).forEach(key => {
            if (settings.value[key] !== undefined) {
                settings.value[key] = response.data[key];
            }
        });
        
        // Parse JSON settings if they exist
        if (response.data.homepage_sliders) {
            homepage_sliders.value = JSON.parse(response.data.homepage_sliders);
        }
        if (response.data.homepage_addons) {
            homepage_addons.value = JSON.parse(response.data.homepage_addons);
        }
        if (response.data.footer_info) footer_info.value = response.data.footer_info;
        if (response.data.footer_links) footer_links.value = JSON.parse(response.data.footer_links);
        if (response.data.footer_contact) footer_contact.value = response.data.footer_contact;
        if (response.data.footer_map) footer_map.value = response.data.footer_map;
        
    } catch (error) {
        console.error("Gagal memuat pengaturan", error);
    }
};

const fetchPages = async () => {
    for (const slug of availableSlugs) {
        if (slug === 'identitas-sekolah') continue; // Identitas is loaded from settings mostly, wait no, let's just fetch it anyway or ignore it since the actual indentitas is in Settings? Wait, identitas-sekolah is a page in DB? Wait, the frontend `ProfilView` treats `identitas-sekolah` as settings. But it might have a page body too. Let's just fetch all.
        try {
            const response = await api.get(`/pages/${slug}`);
            pages.value[slug] = response.data.content;
        } catch (error) {
            console.error(`Gagal memuat halaman ${slug}`, error);
        }
    }
};

const saveSettings = async () => {
    try {
        await api.post('/admin/settings', settings.value);
        alert('Pengaturan umum berhasil disimpan');
    } catch (error) {
        alert('Gagal menyimpan pengaturan');
    }
};

const savePage = async (slug) => {
    try {
        await api.put(`/admin/pages/${slug}`, {
            title: slug.replace(/-/g, ' ').toUpperCase(),
            content: pages.value[slug]
        });
        alert(`Halaman ${slug} berhasil diupdate!`);
    } catch (error) {
        alert(`Gagal menyimpan halaman ${slug}`);
    }
};

// Builder Actions
const uploadSliderImage = async (event, index) => {
    const file = event.target.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('image', file);
    try {
        const response = await api.post('/admin/upload-image', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        homepage_sliders.value[index].image = response.data.url;
    } catch (error) {
        alert('Gagal mengunggah gambar');
    }
};

const addSlide = () => homepage_sliders.value.push({ image: '', title: '', subtitle: '', link: '' });
const removeSlide = (index) => homepage_sliders.value.splice(index, 1);

const addAddon = () => homepage_addons.value.push({ title: '', content: '' });
const removeAddon = (index) => homepage_addons.value.splice(index, 1);

const addFooterLink = () => footer_links.value.push({ title: '', url: '' });
const removeFooterLink = (index) => footer_links.value.splice(index, 1);

const saveBuilderSettings = async () => {
    try {
        const payload = {
            homepage_sliders: JSON.stringify(homepage_sliders.value),
            homepage_addons: JSON.stringify(homepage_addons.value),
            footer_info: footer_info.value,
            footer_links: JSON.stringify(footer_links.value),
            footer_contact: footer_contact.value,
            footer_map: footer_map.value
        };
        await api.post('/admin/settings', payload);
        alert('Pengaturan Beranda & Footer berhasil disimpan');
    } catch (error) {
        alert('Gagal menyimpan pengaturan Builder');
    }
};

onMounted(async () => {
    loading.value = true;
    await fetchSettings();
    await fetchPages();
    loading.value = false;
});
</script>

<style scoped>
.admin-page { padding-bottom: 2rem; }
.text-center { text-align: center; }

.btn-tab {
    padding: 0.5rem 1rem;
    background: white;
    border: 1px solid #d1d5db;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #4b5563;
    cursor: pointer;
    white-space: nowrap;
    transition: all 0.2s;
}
.btn-tab:hover {
    background: #f3f4f6;
}
.btn-tab.active {
    background: #eff6ff;
    color: #2563eb;
    border-color: #bfdbfe;
    box-shadow: 0 1px 2px 0 rgba(0,0,0,0.05);
}

.slide-card {
    background: #f9fafb;
    border: 1px solid #e5e7eb;
    border-radius: 0.5rem;
    padding: 1rem;
}

.btn-secondary {
    background: white;
    border: 1px solid #d1d5db;
    padding: 0.5rem 1rem;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    color: #374151;
    cursor: pointer;
}
.btn-secondary:hover {
    background: #f3f4f6;
}

.thumb { max-height: 80px; border-radius: 0.25rem; border: 1px solid #d1d5db; }
.grid-4-cols { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem; }
@media (max-width: 1024px) { .grid-4-cols { grid-template-columns: 1fr 1fr; } }
@media (max-width: 768px) { .grid-4-cols { grid-template-columns: 1fr; } }

.mt-2 { margin-top: 0.5rem; }
.mt-4 { margin-top: 1rem; }
.mb-2 { margin-bottom: 0.5rem; }
</style>

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
