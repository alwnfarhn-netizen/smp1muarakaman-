import axios from 'axios';

const api = axios.create({
    baseURL: import.meta.env.VITE_API_URL || 'http://localhost:8000/api',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    withCredentials: true, // Required for Sanctum cookie-based auth
    withXSRFToken: true, // Required in Axios 1.x for cross-origin XSRF
});

// Response interceptor
api.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            // Unauthenticated - clear token or redirect to login
            // For now, we will handle this in the Pinia store / router
        }
        return Promise.reject(error);
    }
);

export default api;
