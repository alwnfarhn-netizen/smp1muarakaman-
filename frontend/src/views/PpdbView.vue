<script setup>
import { ref } from 'vue';
import api from '../plugins/axios';

const step = ref(1);
const form = ref({
    full_name: '',
    nisn: '',
    place_of_birth: '',
    date_of_birth: '',
    gender: 'L',
    religion: 'Islam',
    previous_school: '',
    address: '',
    parent_name: '',
    parent_phone: ''
});
const documentFile = ref(null);
const loading = ref(false);
const message = ref('');
const messageType = ref('');
const regNumber = ref('');

const nextStep = () => { 
    if (step.value === 1) {
        if (!form.value.full_name || !form.value.nisn || !form.value.place_of_birth || !form.value.date_of_birth) {
            alert('Mohon lengkapi semua data wajib (Nama, NISN, Tempat & Tanggal Lahir) sebelum melanjutkan.');
            return;
        }
    }
    if (step.value === 2) {
        if (!form.value.previous_school || !form.value.address || !form.value.parent_name || !form.value.parent_phone) {
            alert('Mohon lengkapi data sekolah asal, alamat, dan data orang tua.');
            return;
        }
    }
    if (step.value < 3) step.value++; 
};
const prevStep = () => { if (step.value > 1) step.value--; };

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file && file.type === 'application/pdf') {
        documentFile.value = file;
    } else {
        alert('File harus berupa PDF');
        event.target.value = '';
    }
};

const submitForm = async () => {
    loading.value = true;
    message.value = '';
    
    const formData = new FormData();
    for (const key in form.value) {
        formData.append(key, form.value[key]);
    }
    if (documentFile.value) {
        formData.append('document', documentFile.value);
    }

    try {
        const response = await api.post('/ppdb/register', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        messageType.value = 'success';
        message.value = response.data.message;
        regNumber.value = response.data.registration_number;
        step.value = 4; // Success step
    } catch (error) {
        messageType.value = 'error';
        message.value = error.response?.data?.message || 'Terjadi kesalahan saat mendaftar.';
    } finally {
        loading.value = false;
    }
};

const checkRegNumber = ref('');
const checkResult = ref(null);
const checkLoading = ref(false);

const checkStatus = async () => {
    if (!checkRegNumber.value) return;
    checkLoading.value = true;
    checkResult.value = null;
    try {
        const response = await api.get(`/ppdb/status/${checkRegNumber.value}`);
        checkResult.value = response.data;
    } catch (error) {
        alert(error.response?.data?.message || 'Data tidak ditemukan');
    } finally {
        checkLoading.value = false;
    }
};

const printPage = () => {
    window.print();
};
</script>

<template>
  <div class="page-container container">
    <div class="header-section text-center">
        <h2>Penerimaan Peserta Didik Baru (PPDB)</h2>
        <p>Tahun Ajaran 2026/2027</p>
    </div>

    <div class="ppdb-grid">
        <!-- Form Pendaftaran -->
        <div class="glass-panel p-4">
            <h3 class="mb-4">Form Pendaftaran</h3>
            
            <div v-if="message && messageType === 'error'" class="alert error">
                {{ message }}
            </div>

            <div v-if="step === 1" class="form-step">
                <h4>Langkah 1: Data Diri Siswa</h4>
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" v-model="form.full_name" required>
                </div>
                <div class="form-group">
                    <label>NISN</label>
                    <input type="text" v-model="form.nisn" required>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Tempat Lahir</label>
                        <input type="text" v-model="form.place_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir</label>
                        <input type="date" v-model="form.date_of_birth" required>
                    </div>
                </div>
                <div class="form-actions right">
                    <button type="button" class="btn-primary" @click="nextStep">Selanjutnya &rarr;</button>
                </div>
            </div>

            <div v-if="step === 2" class="form-step">
                <h4>Langkah 2: Data Tambahan & Orang Tua</h4>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select v-model="form.gender">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Agama</label>
                        <select v-model="form.religion">
                            <option value="Islam">Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Asal Sekolah (SD/MI)</label>
                    <input type="text" v-model="form.previous_school" required>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap</label>
                    <textarea v-model="form.address" rows="3" required></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Nama Orang Tua / Wali</label>
                        <input type="text" v-model="form.parent_name" required>
                    </div>
                    <div class="form-group">
                        <label>No. HP / WhatsApp aktif</label>
                        <input type="text" v-model="form.parent_phone" required>
                    </div>
                </div>
                <div class="form-actions between">
                    <button type="button" class="btn-secondary" @click="prevStep">&larr; Kembali</button>
                    <button type="button" class="btn-primary" @click="nextStep">Selanjutnya &rarr;</button>
                </div>
            </div>

            <div v-if="step === 3" class="form-step">
                <h4>Langkah 3: Upload Berkas</h4>
                <p class="mb-4 text-sm text-secondary">Silakan gabungkan semua berkas persyaratan (SKL, KK, Akta Kelahiran, dll) menjadi <strong>1 file PDF</strong> berukuran maksimal 5MB.</p>
                <div class="form-group">
                    <label>Upload File PDF</label>
                    <input type="file" accept="application/pdf" @change="handleFileUpload" required>
                </div>
                <div class="form-actions between">
                    <button type="button" class="btn-secondary" @click="prevStep">&larr; Kembali</button>
                    <button type="button" class="btn-primary" @click="submitForm" :disabled="loading || !documentFile">
                        {{ loading ? 'Mengirim...' : 'Kirim Pendaftaran' }}
                    </button>
                </div>
            </div>

            <div v-if="step === 4" class="form-step text-center py-4">
                <div class="success-icon mb-4">✅</div>
                <h3>Pendaftaran Berhasil!</h3>
                <p class="mb-4">Terima kasih, pendaftaran Anda telah kami terima.</p>
                <div class="reg-card">
                    <p class="text-sm">Nomor Registrasi Anda:</p>
                    <h2 class="reg-number">{{ regNumber }}</h2>
                    <p class="text-sm text-secondary">Harap simpan nomor registrasi ini untuk mengecek status pendaftaran Anda.</p>
                </div>
                <div class="print-actions">
                    <button type="button" class="btn-secondary mt-4" @click="step = 1; form = {}">Daftar Lagi</button>
                    <button type="button" class="btn-primary mt-4 ml-2" @click="$router.push(`/ppdb/print/${regNumber}`)">🖨️ Cetak Bukti</button>
                </div>
            </div>
        </div>

        <!-- Cek Status -->
        <div class="glass-panel p-4">
            <h3 class="mb-4">Cek Status Pendaftaran</h3>
            <p class="text-sm text-secondary mb-4">Masukkan Nomor Registrasi (contoh: PPDB-2026-0001) untuk melihat status pendaftaran Anda.</p>
            
            <div class="form-group">
                <input type="text" v-model="checkRegNumber" placeholder="Nomor Registrasi" @keyup.enter="checkStatus">
            </div>
            <button class="btn-primary w-full" @click="checkStatus" :disabled="checkLoading">
                {{ checkLoading ? 'Mengecek...' : 'Cek Status' }}
            </button>

            <div v-if="checkResult" class="status-result mt-4">
                <h4>Hasil Pencarian:</h4>
                <table class="status-table">
                    <tr>
                        <td>Nama</td>
                        <td><strong>{{ checkResult.full_name }}</strong></td>
                    </tr>
                    <tr>
                        <td>Status</td>
                        <td>
                            <span :class="['badge', checkResult.status]">{{ checkResult.status.toUpperCase() }}</span>
                        </td>
                    </tr>
                </table>
                <div v-if="checkResult.notes" class="notes mt-2 text-sm text-secondary">
                    <strong>Catatan Panitia:</strong> {{ checkResult.notes }}
                </div>
                <button type="button" class="btn-secondary w-full mt-4 print-btn" @click="$router.push(`/ppdb/print/${checkResult.registration_number}`)">🖨️ Cetak Bukti Pendaftaran</button>
            </div>
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

/* Glass panel needs proper padding since p-4 is not global */
.glass-panel {
    padding: 2rem;
    background: #ffffff;
    border-radius: 1rem;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid var(--border-color);
}

.glass-panel h3 {
    font-size: 1.5rem;
    color: var(--primary-navy);
    border-bottom: 2px solid var(--border-color);
    padding-bottom: 1rem;
    margin-bottom: 1.5rem;
}

.form-step h4 {
    font-size: 1.25rem;
    color: var(--primary-navy-light);
    margin-bottom: 1.5rem;
}

.ppdb-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

@media (max-width: 768px) {
    .ppdb-grid {
        grid-template-columns: 1fr;
    }
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
.form-group select, 
.form-group textarea {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    font-family: inherit;
    font-size: 1rem;
    background-color: #f9fafb;
    color: #111827;
    transition: var(--transition);
}

.form-group input:focus, 
.form-group select:focus, 
.form-group textarea:focus {
    outline: none;
    border-color: var(--accent-amber);
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
}

.grid-2 {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-actions {
    display: flex;
    margin-top: 2rem;
}

.form-actions.right { justify-content: flex-end; }
.form-actions.between { justify-content: space-between; }

.form-actions button {
    padding: 0.75rem 2rem;
    font-size: 1rem;
}

.btn-secondary {
    background-color: transparent;
    color: var(--primary-navy);
    border: 1px solid var(--primary-navy);
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    cursor: pointer;
    transition: var(--transition);
}
.btn-secondary:hover {
    background-color: rgba(10, 25, 47, 0.05);
}

.w-full { width: 100%; }
.mb-4 { margin-bottom: 1rem; }
.mt-4 { margin-top: 1rem; }
.mt-2 { margin-top: 0.5rem; }
.py-4 { padding-top: 2rem; padding-bottom: 2rem; }
.text-center { text-align: center; }
.text-sm { font-size: 0.875rem; }
.text-secondary { color: var(--text-secondary); }

.reg-card {
    background: rgba(255, 193, 7, 0.1);
    padding: 2rem;
    border-radius: 1rem;
    border: 1px dashed var(--accent-amber);
    display: inline-block;
}

.reg-number {
    font-size: 2.5rem;
    color: var(--primary-navy-dark);
    margin: 1rem 0;
    letter-spacing: 2px;
}

.success-icon {
    font-size: 4rem;
}

.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}
.alert.error {
    background: rgba(255, 0, 0, 0.1);
    color: #ff4d4f;
    border: 1px solid #ff4d4f;
}

.status-result {
    padding: 1.5rem;
    background: var(--bg-gray);
    border-radius: 0.5rem;
}

.status-table {
    width: 100%;
    margin-top: 1rem;
    border-collapse: collapse;
}

.status-table td {
    padding: 0.5rem 0;
    border-bottom: 1px solid var(--border-color);
}

.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: bold;
}
.badge.pending { background: #fffbe6; color: #faad14; border: 1px solid #ffe58f; }
.badge.verified { background: #e6f7ff; color: #1890ff; border: 1px solid #91d5ff; }
.badge.accepted { background: #f6ffed; color: #52c41a; border: 1px solid #b7eb8f; }
.badge.rejected { background: #fff2f0; color: #ff4d4f; border: 1px solid #ffa39e; }

.ml-2 { margin-left: 0.5rem; }

/* Print Stylesheet */
@media print {
    body * {
        visibility: hidden;
    }
    
    .page-container,
    .page-container * {
        visibility: visible;
    }

    /* Hide non-essential elements for printing */
    .header-section,
    .form-group,
    input, select, textarea, button,
    .print-actions,
    .print-btn,
    nav, footer {
        display: none !important;
    }

    .glass-panel {
        background: transparent !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
    }

    .reg-card, .status-result {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
        visibility: visible;
        border: 1px solid #000 !important;
        background: #fff !important;
        padding: 2rem !important;
    }

    .reg-card *, .status-result * {
        visibility: visible;
        color: #000 !important;
    }

    .reg-number {
        font-size: 3rem !important;
    }
}
</style>
