<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../plugins/axios';

const route = useRoute();
const registration = ref(null);
const loading = ref(true);
const error = ref('');

const fetchStatus = async () => {
    loading.value = true;
    error.value = '';
    try {
        const response = await api.get(`/ppdb/status/${route.params.registrationNumber}`);
        registration.value = response.data;
        // Small delay to ensure DOM is updated before printing
        setTimeout(() => {
            window.print();
        }, 500);
    } catch (err) {
        error.value = err.response?.data?.message || 'Data pendaftaran tidak ditemukan. Pastikan nomor pendaftaran benar.';
        console.error(err);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchStatus();
});
</script>

<template>
  <div class="print-container">
    <div v-if="loading" class="text-center" style="padding: 3rem;">
        Memuat data pendaftaran...
    </div>
    
    <div v-else-if="error" class="error-state">
        <h3>Gagal Memuat Bukti</h3>
        <p>{{ error }}</p>
        <button class="btn-primary" @click="$router.push('/ppdb')" style="margin-top: 1rem;">Kembali ke PPDB</button>
    </div>

    <div v-else class="print-card">
        <div class="print-header">
            <img src="/favicon.svg" alt="Logo SMP" class="logo" @error="$event.target.src='https://via.placeholder.com/60'" />
            <div class="header-text">
                <h2>KARTU BUKTI PENDAFTARAN PPDB</h2>
                <h3>SMP NEGERI 1 MUARA KAMAN</h3>
                <p>Tahun Ajaran 2026/2027</p>
            </div>
        </div>

        <hr />

        <div class="print-body">
            <div class="info-group">
                <span class="label">Nomor Pendaftaran:</span>
                <strong class="value highlight">{{ registration.registration_number }}</strong>
            </div>
            
            <div class="info-group">
                <span class="label">Tanggal Daftar:</span>
                <span class="value">{{ new Date(registration.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) }}</span>
            </div>

            <div class="info-group mt-2">
                <span class="label">Nama Lengkap:</span>
                <span class="value uppercase"><strong>{{ registration.full_name }}</strong></span>
            </div>
            
            <div class="info-group">
                <span class="label">NISN:</span>
                <span class="value">{{ registration.nisn }}</span>
            </div>
            
            <div class="info-group">
                <span class="label">Asal Sekolah:</span>
                <span class="value uppercase">{{ registration.previous_school }}</span>
            </div>

            <div class="info-group">
                <span class="label">Jenis Kelamin:</span>
                <span class="value">{{ registration.gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
            </div>

            <div class="info-group">
                <span class="label">Status Saat Ini:</span>
                <span class="value" style="font-weight: bold;">
                    {{ registration.status === 'pending' ? 'MENUNGGU VERIFIKASI' : (registration.status === 'approved' ? 'DITERIMA' : 'DITOLAK') }}
                </span>
            </div>
        </div>

        <div class="print-footer">
            <p><strong>Catatan Penting:</strong></p>
            <ol>
                <li>Simpan kartu bukti pendaftaran ini dengan baik.</li>
                <li>Harap bawa kartu ini beserta dokumen asli (SKHU/Ijazah Sementara, Kartu Keluarga, Akta Kelahiran) pada saat verifikasi berkas ke sekolah.</li>
                <li>Cek status pendaftaran Anda secara berkala melalui website sekolah.</li>
            </ol>
            
            <div class="signature-area">
                <p>Muara Kaman, ..........................</p>
                <br><br><br>
                <p>( ..................................................... )</p>
                <p>Panitia PPDB / Pendaftar</p>
            </div>
        </div>
        
        <div class="no-print actions">
            <button class="btn-primary" @click="window.print()">Cetak Ulang</button>
            <button class="btn-secondary" @click="$router.push('/ppdb')">Kembali</button>
        </div>
    </div>
  </div>
</template>

<style scoped>
.print-container {
    background-color: #f3f4f6;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    padding: 2rem;
    font-family: 'Times New Roman', Times, serif; /* Standard print font */
}

.print-card {
    background: white;
    width: 100%;
    max-width: 800px;
    padding: 3rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    color: black;
}

.print-header {
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1.5rem;
    text-align: center;
    gap: 1.5rem;
}

.logo {
    width: 80px;
    height: auto;
}

.header-text h2 {
    margin: 0;
    font-size: 1.5rem;
    font-weight: bold;
}

.header-text h3 {
    margin: 0.25rem 0;
    font-size: 1.25rem;
}

.header-text p {
    margin: 0;
    font-size: 1rem;
}

hr {
    border: none;
    border-top: 3px solid black;
    border-bottom: 1px solid black;
    height: 2px;
    margin-bottom: 2rem;
}

.print-body {
    margin-bottom: 3rem;
}

.info-group {
    display: flex;
    margin-bottom: 0.75rem;
    font-size: 1.1rem;
}

.label {
    width: 200px;
    flex-shrink: 0;
}

.uppercase {
    text-transform: uppercase;
}

.highlight {
    font-size: 1.25rem;
    letter-spacing: 2px;
}

.mt-2 {
    margin-top: 1.5rem;
}

.print-footer {
    font-size: 1rem;
}

.print-footer ol {
    margin-top: 0.5rem;
    padding-left: 1.5rem;
}

.print-footer li {
    margin-bottom: 0.5rem;
}

.signature-area {
    margin-top: 4rem;
    text-align: right;
    width: 300px;
    float: right;
}

.signature-area p {
    margin: 0.25rem 0;
}

.actions {
    clear: both;
    margin-top: 4rem;
    display: flex;
    justify-content: center;
    gap: 1rem;
}

.error-state {
    text-align: center;
    background: white;
    padding: 3rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
}

/* Print Specific Styles */
@media print {
    body {
        background: white;
    }
    
    .print-container {
        padding: 0;
        background: transparent;
    }
    
    .print-card {
        box-shadow: none;
        padding: 0;
        max-width: 100%;
    }
    
    .no-print {
        display: none !important;
    }
}
</style>
