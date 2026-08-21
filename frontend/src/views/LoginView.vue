<script setup>
import { ref } from 'vue';
import { useAuthStore } from '../stores/auth';
import { useRouter } from 'vue-router';

const email = ref('');
const password = ref('');
const authStore = useAuthStore();
const router = useRouter();
const loading = ref(false);
const errorMessage = ref('');

const handleLogin = async () => {
    loading.value = true;
    errorMessage.value = '';
    
    try {
        await authStore.login(email.value, password.value);
        router.push('/admin');
    } catch (error) {
        errorMessage.value = error.response?.data?.message || 'Login gagal. Silakan periksa kembali email dan password Anda.';
    } finally {
        loading.value = false;
    }
};
</script>

<template>
  <div class="login-container">
    <div class="login-card glass-panel">
      <div class="login-header">
        <h2 class="text-gradient">Admin Login</h2>
        <p>Sistem Informasi Terpadu SMPN 1 Muara Kaman</p>
      </div>
      
      <div v-if="errorMessage" class="alert error">
        {{ errorMessage }}
      </div>
      
      <form @submit.prevent="handleLogin">
        <div class="form-group">
            <label>Email</label>
            <input type="email" v-model="email" required placeholder="admin@smpn1muarakaman.sch.id">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" v-model="password" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn-primary w-full" :disabled="loading">
            {{ loading ? 'Sedang Masuk...' : 'Masuk' }}
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.login-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: var(--primary-navy);
}

.login-card {
    width: 100%;
    max-width: 400px;
    padding: 2.5rem;
    background: rgba(255, 255, 255, 0.95);
    border-radius: 1rem;
    box-shadow: var(--shadow-lg);
}

.login-header {
    text-align: center;
    margin-bottom: 2rem;
}

.login-header h2 {
    font-size: 2rem;
    margin-bottom: 0.5rem;
}

.login-header p {
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--primary-navy-light);
}

.form-group input {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: 0.5rem;
    font-family: inherit;
    transition: var(--transition);
}

.form-group input:focus {
    outline: none;
    border-color: var(--accent-amber);
    box-shadow: 0 0 0 3px rgba(255, 193, 7, 0.1);
}

.w-full {
    width: 100%;
}

.alert {
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 0.875rem;
}

.alert.error {
    background: rgba(255, 0, 0, 0.1);
    color: #ff4d4f;
    border: 1px solid #ff4d4f;
}
</style>
