<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';

const settings = ref({});
const form = ref({ name: '', email: '', message: '' });
const isSubmitting = ref(false);

onMounted(async () => {
    try {
        const response = await api.get('/settings');
        settings.value = response.data;
    } catch (error) {
        console.error("Gagal memuat pengaturan kontak", error);
    }
});

const submitForm = async () => {
    isSubmitting.value = true;
    try {
        await api.post('/contact', form.value);
        alert('Terima kasih, pesan Anda telah terkirim!');
        form.value = { name: '', email: '', message: '' };
    } catch (error) {
        alert('Terjadi kesalahan saat mengirim pesan.');
    } finally {
        isSubmitting.value = false;
    }
};
</script>

<template>
  <div class="page-container container">
    <div class="header-section text-center">
        <h2>Hubungi Kami</h2>
        <p>Silakan tinggalkan pesan atau kunjungi lokasi sekolah kami</p>
    </div>

    <div class="contact-grid">
        <!-- Informasi Kontak & Peta -->
        <div class="glass-panel p-4">
            <h3 class="mb-4">Informasi Kontak</h3>
            <div class="info-list">
                <div class="info-item" v-if="settings.contact_email">
                    <span class="icon">✉️</span>
                    <div>
                        <strong>Email:</strong>
                        <p>{{ settings.contact_email }}</p>
                    </div>
                </div>
                <div class="info-item" v-if="settings.contact_phone">
                    <span class="icon">📞</span>
                    <div>
                        <strong>Telepon / WA:</strong>
                        <p>{{ settings.contact_phone }}</p>
                    </div>
                </div>
                <div class="info-item" v-if="settings.address">
                    <span class="icon">📍</span>
                    <div>
                        <strong>Alamat:</strong>
                        <p>{{ settings.address }}</p>
                    </div>
                </div>
            </div>

            <div class="map-container mt-4" v-if="settings.footer_map" v-html="settings.footer_map"></div>
        </div>

        <!-- Formulir Kontak -->
        <div class="glass-panel p-4">
            <h3 class="mb-4">Kirim Pesan</h3>
            <form @submit.prevent="submitForm">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" v-model="form.name" required>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" v-model="form.email" required>
                </div>
                <div class="form-group">
                    <label>Pesan Anda</label>
                    <textarea v-model="form.message" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn-primary w-full" :disabled="isSubmitting">
                    {{ isSubmitting ? 'Mengirim...' : 'Kirim Pesan' }}
                </button>
            </form>
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

.contact-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

@media (max-width: 768px) {
    .contact-grid { grid-template-columns: 1fr; }
}

.info-list {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.info-item {
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.info-item .icon {
    font-size: 1.5rem;
}

.info-item strong {
    color: var(--primary-navy);
}

.info-item p {
    color: var(--text-secondary);
    margin: 0;
}

.map-container {
    width: 100%;
    border-radius: 0.5rem;
    overflow: hidden;
}

.map-container :deep(iframe) {
    width: 100%;
    height: 300px;
    border: none;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--primary-navy);
}

.form-group input, 
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    font-family: inherit;
    transition: var(--transition);
}

.form-group input:focus, 
.form-group textarea:focus {
    outline: none;
    border-color: var(--accent-amber);
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
}

.w-full { width: 100%; }
.mb-4 { margin-bottom: 1.5rem; }
.mt-4 { margin-top: 1.5rem; }
.p-4 { padding: 2rem; }
.text-center { text-align: center; }
</style>
