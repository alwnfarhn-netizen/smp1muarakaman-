/**
 * Centralized configuration for the SMPN 1 Muara Kaman application.
 * All environment-dependent values should be accessed through this module.
 */

export const BACKEND_URL = import.meta.env.VITE_BACKEND_URL || 'http://localhost:8000';

/**
 * Resolves a relative asset path to a full backend URL.
 * If the path is already an absolute URL (starts with http), returns it as-is.
 * @param {string} path - The relative path (e.g. 'storage/uploads/photo.jpg')
 * @returns {string} Full URL to the asset
 */
export function assetUrl(path) {
    if (!path) return '';
    if (path.startsWith('http')) return path;
    return `${BACKEND_URL}/${path}`;
}
