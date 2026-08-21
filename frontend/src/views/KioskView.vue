<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import api from '../plugins/axios';
import { QrcodeStream } from 'vue-qrcode-reader';

const nis = ref('');
const pin = ref('');
const step = ref(1);
const message = ref('');
const messageType = ref(''); // 'success' or 'error'
const loading = ref(false);
const scanMode = ref(true); // true: camera scan, false: manual input

const currentTime = ref('');
const currentDate = ref('');
let timer = null;

const updateTime = () => {
    const now = new Date();
    currentTime.value = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
    currentDate.value = now.toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
};

onMounted(() => {
    updateTime();
    timer = setInterval(updateTime, 1000);
});

onUnmounted(() => {
    if (timer) clearInterval(timer);
});

const appendNis = (num) => {
    nis.value += num;
};
const clearNis = () => { nis.value = ''; };
const nextStep = () => { 
    if (nis.value.length > 0) step.value = 2; 
};

const appendPin = (num) => {
    if (pin.value.length < 6) pin.value += num;
};
const clearPin = () => { pin.value = ''; };
const backStep = () => { 
    step.value = 1; 
    pin.value = ''; 
    message.value = '';
};

const submitAbsensi = async () => {
    if (pin.value.length === 0) return;
    loading.value = true;
    message.value = '';
    
    try {
        const response = await api.post('/attendance/checkin', {
            nis: nis.value,
            pin: pin.value
        });
        messageType.value = 'success';
        message.value = `${response.data.message} Selamat belajar, ${response.data.student}!`;
        
        // Reset after 3 seconds
        setTimeout(() => {
            step.value = 1;
            nis.value = '';
            pin.value = '';
            message.value = '';
        }, 3000);
    } catch (error) {
        messageType.value = 'error';
        message.value = error.response?.data?.message || 'Terjadi kesalahan sistem.';
        pin.value = ''; // Reset pin input on error
    } finally {
        loading.value = false;
    }
};

const onDetect = async (detectedCodes) => {
    if (detectedCodes.length > 0) {
        const qrToken = detectedCodes[0].rawValue;
        await submitAbsensiScan(qrToken);
    }
};

const submitAbsensiScan = async (qrToken) => {
    if (loading.value) return;
    loading.value = true;
    message.value = '';
    
    try {
        const response = await api.post('/attendance/scan', {
            qr_token: qrToken
        });
        messageType.value = 'success';
        message.value = `${response.data.message} Selamat belajar, ${response.data.student}!`;
        
        // Reset message after 3 seconds
        setTimeout(() => {
            message.value = '';
        }, 3000);
    } catch (error) {
        messageType.value = 'error';
        message.value = error.response?.data?.message || 'Kode QR tidak valid.';
        setTimeout(() => {
            message.value = '';
        }, 3000);
    } finally {
        loading.value = false;
    }
};
</script>

<template>
  <div class="kiosk-container">
    <div class="kiosk-card glass-panel">
      <div class="kiosk-header">
        <h1>Kiosk Absensi</h1>
        <p>SMP Negeri 1 Muara Kaman</p>
      </div>

      <div v-if="message" :class="['alert', messageType]">
        {{ message }}
      </div>

      <!-- Mode Toggle -->
      <div class="mode-toggle mb-4">
          <button :class="['btn-toggle', { active: scanMode }]" @click="scanMode = true">Scan Barcode</button>
          <button :class="['btn-toggle', { active: !scanMode }]" @click="scanMode = false">Input Manual</button>
      </div>

      <div v-if="scanMode" class="kiosk-step">
          <h2>Tunjukkan QR Code Anda</h2>
          <p style="color: #ccc; margin-bottom: 1.5rem;">Selamat Datang Semangat Belajar</p>
          <div class="scanner-wrapper">
              <div v-if="loading" class="scanner-overlay">
                  <div class="spinner"></div>
                  <p>Memproses...</p>
              </div>
              <qrcode-stream @detect="onDetect" :track="true" />
          </div>
      </div>

      <!-- STEP 1: Input NIS -->
      <div v-if="!scanMode && step === 1" class="kiosk-step">
        <h2>Masukkan NIS Anda</h2>
        <div class="input-display">{{ nis || '___' }}</div>
        
        <div class="numpad">
            <button v-for="n in 9" :key="n" @click="appendNis(n)">{{ n }}</button>
            <button @click="clearNis" class="btn-clear">C</button>
            <button @click="appendNis(0)">0</button>
            <button @click="nextStep" class="btn-next">OK</button>
        </div>
      </div>

      <!-- STEP 2: Input PIN -->
      <div v-if="!scanMode && step === 2" class="kiosk-step">
        <h2>Masukkan PIN Anda</h2>
        <div class="input-display">{{ '*'.repeat(pin.length) || '___' }}</div>
        
        <div class="numpad">
            <button v-for="n in 9" :key="n" @click="appendPin(n)" :disabled="loading">{{ n }}</button>
            <button @click="backStep" class="btn-clear" :disabled="loading">Kembali</button>
            <button @click="appendPin(0)" :disabled="loading">0</button>
            <button @click="submitAbsensi" class="btn-next" :disabled="loading || pin.length === 0">
                {{ loading ? '...' : 'Submit' }}
            </button>
        </div>
      </div>

      <!-- Clock -->
      <div class="kiosk-clock">
        <p>📅 {{ currentDate }}</p>
        <p>🕐 {{ currentTime }}</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.kiosk-container {
    width: 100%;
    max-width: 500px;
    margin: 0 auto;
    padding: 2rem;
}

.kiosk-card {
    padding: 3rem 2rem;
    text-align: center;
    background: rgba(10, 25, 47, 0.95);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 1.5rem;
    box-shadow: var(--shadow-lg);
}

.kiosk-header {
    margin-bottom: 2rem;
}

.kiosk-header h1 {
    color: var(--accent-amber);
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.kiosk-header p {
    color: rgba(255,255,255,0.7);
}

.input-display {
    font-size: 4rem;
    letter-spacing: 1rem;
    margin: 2rem 0;
    min-height: 5rem;
    color: white;
    font-weight: bold;
}

.numpad {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1rem;
}

.numpad button {
    background: rgba(255,255,255,0.1);
    color: white;
    font-size: 2rem;
    padding: 1.5rem;
    border-radius: 1rem;
    border: 1px solid rgba(255,255,255,0.1);
    transition: var(--transition);
    font-weight: 600;
}

.numpad button:hover:not(:disabled) {
    background: rgba(255,255,255,0.2);
}

.numpad button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.btn-clear {
    background: rgba(255, 0, 0, 0.2) !important;
    color: #ff4d4f !important;
}

.btn-next {
    background: var(--accent-amber) !important;
    color: var(--primary-navy-dark) !important;
    font-weight: bold;
}

.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.success {
    background: rgba(0, 255, 0, 0.1);
    color: #52c41a;
    border: 1px solid #52c41a;
}

.error {
    background: rgba(255, 0, 0, 0.1);
    color: #ff4d4f;
    border: 1px solid #ff4d4f;
}

.kiosk-clock {
    margin-top: 2.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.25rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.mode-toggle {
    display: inline-flex;
    background: rgba(255, 255, 255, 0.1);
    border-radius: 2rem;
    padding: 0.25rem;
    margin-bottom: 2rem;
}

.btn-toggle {
    padding: 0.5rem 1.5rem;
    border-radius: 1.75rem;
    color: rgba(255, 255, 255, 0.7);
    font-weight: 600;
    font-size: 0.9rem;
    transition: all 0.3s;
}

.btn-toggle.active {
    background: var(--accent-amber);
    color: var(--primary-navy-dark);
}

.scanner-wrapper {
    position: relative;
    width: 100%;
    max-width: 300px;
    height: 300px;
    margin: 0 auto;
    border-radius: 1rem;
    overflow: hidden;
    border: 3px solid rgba(255, 255, 255, 0.2);
}

.scanner-overlay {
    position: absolute;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(10, 25, 47, 0.8);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 10;
    color: white;
}

.spinner {
    width: 40px;
    height: 40px;
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid var(--accent-amber);
    border-radius: 50%;
    animation: spin 1s linear infinite;
    margin-bottom: 1rem;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
