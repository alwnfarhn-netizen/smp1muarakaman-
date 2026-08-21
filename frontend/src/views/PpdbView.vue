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
const documentFiles = ref({
    skl: null,
    kk: null,
    akta: null,
    ktp: null,
    photo: null
});
const fileUrls = ref({
    skl: null,
    kk: null,
    akta: null,
    ktp: null,
    photo: null
});
const loading = ref(false);
const message = ref('');
const messageType = ref('');
const regNumber = ref('');
const validationError = ref('');

const nextStep = () => { 
    validationError.value = '';
    if (step.value === 1) {
        if (!form.value.full_name || !form.value.nisn || !form.value.place_of_birth || !form.value.date_of_birth) {
            validationError.value = 'Mohon lengkapi semua data wajib (ditandai dengan *) sebelum melanjutkan.';
            return;
        }
    }
    if (step.value === 2) {
        if (!form.value.previous_school || !form.value.address || !form.value.parent_name || !form.value.parent_phone) {
            validationError.value = 'Mohon lengkapi data sekolah asal, alamat, dan data orang tua (ditandai dengan *).';
            return;
        }
    }
    if (step.value < 3) step.value++; 
};
const prevStep = () => { 
    validationError.value = '';
    if (step.value > 1) step.value--; 
};

const handleFileUpload = (event, type) => {
    const file = event.target.files[0];
    if (!file) return;

    const isImage = type === 'photo' && ['image/jpeg', 'image/png', 'image/jpg'].includes(file.type);
    const isPdf = type !== 'photo' && file.type === 'application/pdf';

    if (isImage || isPdf) {
        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran file maksimal 2MB');
            event.target.value = '';
            documentFiles.value[type] = null;
            if (fileUrls.value[type]) URL.revokeObjectURL(fileUrls.value[type]);
            fileUrls.value[type] = null;
        } else {
            documentFiles.value[type] = file;
            if (fileUrls.value[type]) URL.revokeObjectURL(fileUrls.value[type]);
            fileUrls.value[type] = URL.createObjectURL(file);
        }
    } else {
        alert(type === 'photo' ? 'File harus berupa JPG/PNG' : 'File harus berupa PDF');
        event.target.value = '';
        documentFiles.value[type] = null;
        if (fileUrls.value[type]) URL.revokeObjectURL(fileUrls.value[type]);
        fileUrls.value[type] = null;
    }
};

const submitForm = async () => {
    loading.value = true;
    message.value = '';
    
    const formData = new FormData();
    for (const key in form.value) {
        formData.append(key, form.value[key]);
    }
    if (documentFiles.value.skl) formData.append('document_skl', documentFiles.value.skl);
    if (documentFiles.value.kk) formData.append('document_kk', documentFiles.value.kk);
    if (documentFiles.value.akta) formData.append('document_akta', documentFiles.value.akta);
    if (documentFiles.value.ktp) formData.append('document_ktp', documentFiles.value.ktp);
    if (documentFiles.value.photo) formData.append('document_photo', documentFiles.value.photo);

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
            
            <div v-if="validationError" class="alert error">
                {{ validationError }}
            </div>

            <div v-if="step === 1" class="form-step">
                <h4>Langkah 1: Data Diri Siswa</h4>
                <div class="form-group">
                    <label>Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" v-model="form.full_name" required>
                </div>
                <div class="form-group">
                    <label>NISN <span class="text-danger">*</span></label>
                    <input type="text" v-model="form.nisn" required>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Tempat Lahir <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.place_of_birth" required>
                    </div>
                    <div class="form-group">
                        <label>Tanggal Lahir <span class="text-danger">*</span></label>
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
                        <label>Jenis Kelamin <span class="text-danger">*</span></label>
                        <select v-model="form.gender">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Agama <span class="text-danger">*</span></label>
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
                    <label>Asal Sekolah (SD/MI) <span class="text-danger">*</span></label>
                    <input type="text" v-model="form.previous_school" required>
                </div>
                <div class="form-group">
                    <label>Alamat Lengkap <span class="text-danger">*</span></label>
                    <textarea v-model="form.address" rows="3" required></textarea>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Nama Orang Tua / Wali <span class="text-danger">*</span></label>
                        <input type="text" v-model="form.parent_name" required>
                    </div>
                    <div class="form-group">
                        <label>No. HP / WhatsApp aktif <span class="text-danger">*</span></label>
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
                <p class="mb-4 text-sm text-secondary">Silakan unggah dokumen persyaratan berikut (Maksimal 2MB per file).</p>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Surat Keterangan Lulus (PDF) <span class="text-danger">*</span></label>
                        <input type="file" accept="application/pdf" @change="(e) => handleFileUpload(e, 'skl')" required>
                        <div v-if="fileUrls.skl" class="file-preview">
                            <span class="file-name">{{ documentFiles.skl.name }}</span>
                            <a :href="fileUrls.skl" target="_blank" class="preview-link">🔍 Cek File</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Kartu Keluarga (PDF) <span class="text-danger">*</span></label>
                        <input type="file" accept="application/pdf" @change="(e) => handleFileUpload(e, 'kk')" required>
                        <div v-if="fileUrls.kk" class="file-preview">
                            <span class="file-name">{{ documentFiles.kk.name }}</span>
                            <a :href="fileUrls.kk" target="_blank" class="preview-link">🔍 Cek File</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Akta Kelahiran (PDF) <span class="text-danger">*</span></label>
                        <input type="file" accept="application/pdf" @change="(e) => handleFileUpload(e, 'akta')" required>
                        <div v-if="fileUrls.akta" class="file-preview">
                            <span class="file-name">{{ documentFiles.akta.name }}</span>
                            <a :href="fileUrls.akta" target="_blank" class="preview-link">🔍 Cek File</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>KTP Orang Tua/Wali (PDF) <span class="text-danger">*</span></label>
                        <input type="file" accept="application/pdf" @change="(e) => handleFileUpload(e, 'ktp')" required>
                        <div v-if="fileUrls.ktp" class="file-preview">
                            <span class="file-name">{{ documentFiles.ktp.name }}</span>
                            <a :href="fileUrls.ktp" target="_blank" class="preview-link">🔍 Cek File</a>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Pas Foto 3x4 (JPG/PNG) <span class="text-danger">*</span></label>
                    <input type="file" accept="image/jpeg,image/png,image/jpg" @change="(e) => handleFileUpload(e, 'photo')" required>
                    <div v-if="fileUrls.photo" class="file-preview image-preview">
                        <img :src="fileUrls.photo" alt="Preview Foto">
                    </div>
                </div>
                <div class="form-actions between">
                    <button type="button" class="btn-secondary" @click="prevStep">&larr; Kembali</button>
                    <button type="button" class="btn-primary" @click="submitForm" :disabled="loading || !documentFiles.skl || !documentFiles.kk || !documentFiles.akta || !documentFiles.ktp || !documentFiles.photo">
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
.text-danger { color: #ff4d4f; }

.file-preview {
    margin-top: 0.5rem;
    padding: 0.75rem;
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 0.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.875rem;
    animation: fadeIn 0.3s ease;
}
.file-name {
    color: #166534;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 65%;
}
.preview-link {
    color: #047857;
    text-decoration: none;
    font-weight: 600;
    background: white;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    border: 1px solid #86efac;
    transition: var(--transition);
}
.preview-link:hover {
    background: #dcfce7;
}
.image-preview {
    justify-content: center;
    padding: 1rem;
    background: #f9fafb;
    border-color: #e5e7eb;
}
.image-preview img {
    max-height: 150px;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-5px); }
    to { opacity: 1; transform: translateY(0); }
}

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
