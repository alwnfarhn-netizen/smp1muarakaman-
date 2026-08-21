# SMPN 1 Muara Kaman — Frontend

Aplikasi SPA (Single Page Application) untuk Sistem Informasi Terpadu SMP Negeri 1 Muara Kaman.

## Tech Stack

- **Vue 3** (Composition API + `<script setup>`)
- **Vite** (build tool & dev server)
- **Pinia** (state management)
- **Vue Router** (routing)
- **Axios** (HTTP client)
- **Quill Editor** (rich text editor untuk admin)

## Struktur Folder

```
src/
├── assets/css/       # CSS global (main.css)
├── components/       # Komponen reusable (jika ada)
├── layouts/          # Layout: PublicLayout, AdminLayout, KioskLayout
├── plugins/          # Axios instance & config helper
├── router/           # Definisi rute
├── stores/           # Pinia auth store
└── views/            # Semua halaman (Home, Admin, PPDB, dll.)
```

## Environment Variables

Buat file `.env` di root folder `frontend/`:

```env
VITE_API_URL=http://localhost:8000/api
VITE_BACKEND_URL=http://localhost:8000
VITE_APP_URL=http://localhost:5173
```

## Menjalankan

```bash
npm install
npm run dev
```

## Build Production

```bash
npm run build
```

Output tersimpan di folder `dist/`.
