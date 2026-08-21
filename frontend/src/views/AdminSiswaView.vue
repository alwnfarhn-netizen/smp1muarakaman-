<script setup>
import { ref, onMounted } from 'vue';
import api from '../plugins/axios';
import QrcodeVue from 'qrcode.vue';

const students = ref([]);
const loading = ref(true);
const showForm = ref(false);

const form = ref({
    name: '',
    nis: '',
    nisn: '',
    class: '',
    gender: 'L',
    parent_phone: ''
});

const generatedPin = ref('');
const createdStudentName = ref('');

const selectedQrToken = ref('');
const selectedStudentName = ref('');
const showQrModal = ref(false);

const openQrModal = (student) => {
    if(!student.qr_token) {
        alert("Siswa ini belum memiliki QR Token (Data Lama).");
        return;
    }
    selectedQrToken.value = student.qr_token;
    selectedStudentName.value = student.name;
    showQrModal.value = true;
};

const fetchStudents = async () => {
    loading.value = true;
    try {
        const response = await api.get('/admin/students');
        students.value = response.data;
    } catch (error) {
        console.error("Gagal memuat data siswa", error);
    } finally {
        loading.value = false;
    }
};

const submitForm = async () => {
    try {
        const response = await api.post('/admin/students', form.value);
        generatedPin.value = response.data.raw_pin;
        createdStudentName.value = response.data.student.name;
        
        showForm.value = false;
        form.value = { name: '', nis: '', nisn: '', class: '', gender: 'L', parent_phone: '' };
        fetchStudents();
    } catch (error) {
        alert(error.response?.data?.message || 'Gagal menyimpan data siswa');
    }
};

const generatePins = async () => {
    if (!confirm('Apakah Anda yakin ingin men-generate PIN untuk semua siswa yang belum memilikinya?')) return;
    
    loading.value = true;
    try {
        const response = await api.post('/admin/students/generate-pins');
        alert(response.data.message);
        fetchStudents();
    } catch (error) {
        alert(error.response?.data?.message || 'Gagal men-generate PIN massal');
    } finally {
        loading.value = false;
    }
};

const resetPin = async (student) => {
    if (!confirm(`Apakah Anda yakin ingin mereset PIN untuk ${student.name}? PIN lama tidak akan berlaku lagi.`)) return;
    
    try {
        const response = await api.put(`/admin/students/${student.id}/reset-pin`);
        
        // Use the existing UI for showing new PINs
        generatedPin.value = response.data.new_raw_pin;
        createdStudentName.value = student.name + " (Reset)";
        
        alert(response.data.message);
        fetchStudents();
    } catch (error) {
        alert(error.response?.data?.message || 'Gagal mereset PIN');
    }
};

onMounted(() => {
    fetchStudents();
});
</script>

<template>
  <div class="admin-page">
    <div class="admin-page-header">
        <div>
            <h1>Manajemen Siswa & PIN Absensi</h1>
            <div class="breadcrumb">
                <router-link to="/admin">Home</router-link>
                <span>&rsaquo;</span>
                <span>Master Data</span>
                <span>&rsaquo;</span>
                <span style="color: #111827;">Siswa Aktif</span>
            </div>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <button class="btn-secondary" @click="generatePins" style="background: white; border: 1px solid #d1d5db; color: #374151;">
                Generate PIN Massal
            </button>
            <button class="btn-primary" @click="showForm = !showForm">
                <svg v-if="!showForm" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:0.5rem"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                {{ showForm ? 'Batal' : 'Tambah Baru' }}
            </button>
        </div>
    </div>

    <!-- Alert for Newly Generated PIN -->
    <div v-if="generatedPin" class="card-panel alert-success" style="border-color: #b7eb8f; background-color: #f6ffed;">
        <h3 class="mb-2" style="color: #52c41a;">Perhatian: Catat PIN Berikut!</h3>
        <p>Aksi berhasil untuk: <strong>{{ createdStudentName }}</strong>.</p>
        <div class="pin-display mt-4">
            <p style="font-size: 0.875rem;">PIN Absensi (Hanya ditampilkan sekali):</p>
            <h1 style="font-size: 3rem; color: #020C1B; letter-spacing: 5px; margin: 0.5rem 0;">{{ generatedPin }}</h1>
            <p style="font-size: 0.875rem; color: #666;">Harap catat dan berikan PIN ini kepada siswa yang bersangkutan.</p>
        </div>
        <button class="btn-secondary mt-4" style="background: transparent; border: 1px solid #0A192F; padding: 0.5rem 1rem; border-radius: 0.5rem; cursor: pointer;" @click="generatedPin = ''">Tutup</button>
    </div>

    <div v-if="showForm" class="card-panel">
        <div class="card-panel-header">
            <h3>Tambah Data Siswa Baru</h3>
        </div>
        <p style="color: #6b7280; margin-bottom: 1.5rem; font-size: 0.875rem;">Menambahkan siswa otomatis men-generate PIN 4-digit secara acak untuk keperluan absensi.</p>
        <form @submit.prevent="submitForm">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label class="card-label">Nama Lengkap</label>
                    <input type="text" class="card-input" v-model="form.name" required>
                </div>
                <div>
                    <label class="card-label">Kelas</label>
                    <input type="text" class="card-input" v-model="form.class" required placeholder="Contoh: VII A">
                </div>
                <div>
                    <label class="card-label">NIS</label>
                    <input type="text" class="card-input" v-model="form.nis" required>
                </div>
                <div>
                    <label class="card-label">NISN</label>
                    <input type="text" class="card-input" v-model="form.nisn" required>
                </div>
                <div>
                    <label class="card-label">Jenis Kelamin</label>
                    <select class="card-input" v-model="form.gender">
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label class="card-label">No. HP Orang Tua (Untuk WA Absen)</label>
                    <input type="text" class="card-input" v-model="form.parent_phone" required placeholder="08...">
                </div>
            </div>
            <button type="submit" class="btn-primary" style="background-color: #2563eb; color: white;">Simpan & Generate PIN</button>
        </form>
    </div>

    <div class="card-panel" style="padding: 0; overflow: hidden;">
        <div v-if="loading" class="text-center" style="padding: 2rem;">Memuat data...</div>
        
        <div class="table-responsive" v-else>
            <table class="table-modern">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>NIS / NISN</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>L/P</th>
                        <th>No. WA Ortu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(student, index) in students" :key="student.id">
                        <td>{{ index + 1 }}</td>
                        <td>{{ student.nis }} <br> <span style="color: #6b7280; font-size: 0.75rem;">{{ student.nisn }}</span></td>
                        <td><strong style="color: #111827;">{{ student.name }}</strong></td>
                        <td>{{ student.class }}</td>
                        <td>{{ student.gender }}</td>
                        <td>{{ student.parent_phone }}</td>
                        <td>
                            <div style="display: flex; gap: 0.5rem; justify-content: center;">
                                <button class="btn-action edit" @click="openQrModal(student)" title="Lihat QR Code">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><rect x="7" y="7" width="3" height="3"></rect><rect x="14" y="7" width="3" height="3"></rect><rect x="7" y="14" width="3" height="3"></rect><rect x="14" y="14" width="3" height="3"></rect></svg>
                                </button>
                                <button class="btn-action" style="color: #d97706; background-color: #fef3c7;" @click="resetPin(student)" title="Reset PIN Absensi">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path><path d="M3 3v5h5"></path></svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr v-if="students.length === 0">
                        <td colspan="7" class="text-center" style="padding: 3rem;">Belum ada data siswa.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div v-if="showQrModal" class="modal-backdrop" @click="showQrModal = false">
        <div class="modal-content" @click.stop>
            <div class="modal-header">
                <h3>QR Code Siswa</h3>
                <button class="btn-close" @click="showQrModal = false">&times;</button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-4"><strong>{{ selectedStudentName }}</strong></p>
                <div class="qr-container">
                    <qrcode-vue :value="selectedQrToken" :size="200" level="H" />
                </div>
                <p class="mt-4" style="color: #6b7280; font-size: 0.875rem;">Siswa dapat memindai kode ini di perangkat Kiosk untuk melakukan absensi otomatis.</p>
            </div>
            <div class="modal-footer">
                <button class="btn-primary" @click="showQrModal = false">Tutup</button>
            </div>
        </div>
    </div>
  </div>
</template>

<style scoped>
.admin-page { padding-bottom: 2rem; }
.text-center { text-align: center; }
.table-responsive { overflow-x: auto; }
.mb-2 { margin-bottom: 0.5rem; }
.mt-4 { margin-top: 1.5rem; }
.pin-display { background: rgba(255, 193, 7, 0.1); padding: 1.5rem; border-radius: 0.5rem; border: 1px dashed var(--accent-amber); display: inline-block; }

.modal-backdrop { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; align-items: center; justify-content: center; z-index: 1000; }
.modal-content { background: white; border-radius: 1rem; width: 90%; max-width: 400px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); }
.modal-header { padding: 1.5rem; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center; }
.modal-header h3 { margin: 0; font-size: 1.25rem; }
.btn-close { font-size: 1.5rem; background: none; border: none; cursor: pointer; color: #6b7280; }
.modal-body { padding: 2rem 1.5rem; }
.qr-container { display: inline-block; padding: 1rem; background: white; border: 1px solid #e5e7eb; border-radius: 1rem; }
.modal-footer { padding: 1rem 1.5rem; border-top: 1px solid #e5e7eb; text-align: right; }
</style>
