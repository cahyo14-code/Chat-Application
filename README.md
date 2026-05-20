# Chat Application

Aplikasi chat real-time berbasis web yang dibangun dengan Laravel 12. Mendukung private chat, group chat, dan user presence tracking (online/offline) secara real-time tanpa perlu refresh halaman.

---

## Daftar Isi

- [Fitur](#-fitur)
- [Tech Stack](#-tech-stack)
- [Prasyarat](#-prasyarat)
- [Instalasi](#-instalasi)
- [Menjalankan Aplikasi](#-menjalankan-aplikasi)
- [Struktur File](#-struktur-file)
- [Cara Penggunaan](#-cara-penggunaan)
- [Penjelasan Fitur](#-penjelasan-fitur)

---

## Fitur

- User Authentication — Register, Login, Logout
- Private Chat — Chat 1-on-1 antar pengguna
- Group Chat — Buat grup dan chat bersama banyak orang
- Real-time Messaging — Pesan terkirim instan tanpa refresh halaman
- User Presence Tracking — Indikator online/offline pengguna secara real-time

---

## Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/cahyo14-code/Chat-Application.git
cd Chat-Application
```

### 2. Install PHP Dependencies

```bash
composer install
```

### 3. Install Node Dependencies

```bash
npm install
```

### 4. Salin File Environment

```bash
cp .env.example .env
```

### 5. Generate App Key

```bash
php artisan key:generate
```

### 6. Konfigurasi Database

Buka file `.env` dan sesuaikan:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=chat_app
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Konfigurasi Queue

Pastikan di `.env` sudah ada:

```env
QUEUE_CONNECTION=sync
```

### 8. Konfigurasi Reverb (WebSocket)

Pastikan di `.env` sudah ada:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST="localhost"
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

### 9. Buat Database

Buka phpMyAdmin dan buat database baru:

```sql
CREATE DATABASE chat_app;
```

### 10. Jalankan Migration

```bash
php artisan migrate
```

---

## Menjalankan Aplikasi

Buka **3 terminal** secara bersamaan:

Terminal 1 — Laravel Server
```bash
php artisan serve
```

Terminal 2 — WebSocket Server
```bash
php artisan reverb:start
```

Terminal 3 — Frontend Assets
```bash
npm run dev
```

Lalu buka browser: