# Scrollify — Setup & Development

Lokasi file: root `scrollify/README.md`

## Prasyarat

- **XAMPP** (Apache + MySQL) — sudah terpasang, dipakai untuk MySQL saja. Modul
  Apache/PHP bawaan XAMPP **tidak dipakai** untuk menjalankan Laravel — Laravel
  dijalankan lewat `php artisan serve` sendiri (lebih gampang untuk development,
  tidak perlu setup virtual host).
- **PHP 8.2** dan **Composer** — sesuai versi bawaan XAMPP yang dipakai. Cek dengan
  `php -v` di terminal, pastikan yang terbaca adalah PHP dari XAMPP.
- **Laravel 12** — dipilih karena kompatibel dengan PHP 8.2 (Laravel 13 butuh
  PHP 8.3+, yang berarti perlu upgrade XAMPP; tidak diperlukan untuk sekarang).
  Laravel 12 masih dapat bugfix sampai Agustus 2026 dan security update sampai
  Februari 2027.
- **Node.js 20 LTS+** dan **npm** — untuk frontend React (Vite).
- **Git**.

## 1. Setup Database (via XAMPP)

1. Buka **XAMPP Control Panel**, start modul **Apache** dan **MySQL**.
2. Buka `http://localhost/phpmyadmin`.
3. Buat database baru bernama `scrollify`, collation `utf8mb4_unicode_ci`.
4. Tidak perlu bikin tabel manual — semua tabel dibuat lewat migration Laravel
   di langkah berikutnya.

## 2. Setup Backend (Laravel 12)

```bash
cd backend
composer create-project laravel/laravel . "12.*"
composer require laravel/sanctum
php artisan install:api
cp .env.example .env
php artisan key:generate
```

Edit `backend/.env`, sesuaikan bagian database dengan setup XAMPP (default XAMPP:
user `root`, password kosong):

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=scrollify
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migration:

```bash
php artisan migrate
```

Jalankan server Laravel:

```bash
php artisan serve
```

Backend jalan di **`http://localhost:8000`**.

## 3. Setup Frontend (React + TypeScript + Vite)

```bash
cd frontend
npm create vite@latest . -- --template react-ts
npm install
npm install -D tailwindcss @tailwindcss/vite
cp .env.example .env
npm run dev
```

Frontend jalan di **`http://localhost:5173`** (port default Vite).

## 4. Menjalankan Keduanya Bersamaan

Backend dan frontend adalah dua proses terpisah — buka **dua terminal**:

- Terminal 1: `cd backend && php artisan serve`
- Terminal 2: `cd frontend && npm run dev`

Pastikan XAMPP MySQL tetap menyala selama backend jalan.

---

## Daftar `.env` Variable

### `backend/.env` (tambahan di luar default Laravel)

| Variable             | Contoh nilai               | Keterangan                                                         |
| -------------------- | -------------------------- | ------------------------------------------------------------------ |
| `MANGADEX_API_URL`   | `https://api.mangadex.org` | Base URL MangaDex API                                              |
| `MANGADEX_CACHE_TTL` | `600`                      | Detik, lama cache Redis/file untuk hasil MangaDex sebelum re-fetch |
| `FRONTEND_URL`       | `http://localhost:5173`    | Dipakai untuk konfigurasi CORS                                     |

**Keputusan auth:** Scrollify pakai **Sanctum API token (Bearer)**, bukan
cookie-based SPA authentication — alasan lengkap ada di percakapan perencanaan,
intinya: lebih portable ke PWA/mobile nanti, tidak bergantung same-domain/cookie.
Konsekuensinya: **tidak perlu** `SANCTUM_STATEFUL_DOMAINS` atau `SESSION_DOMAIN`,
karena itu khusus pendekatan cookie-based yang tidak dipakai di sini.

**Catatan MangaDex:** endpoint publik (search, manga detail, chapter feed, cover
art) **tidak butuh API key** untuk dibaca. Tidak ada `MANGADEX_API_KEY` yang perlu
diisi untuk fitur-fitur di `API_CONTRACT.md` versi sekarang.

### `frontend/.env`

| Variable            | Contoh nilai                | Keterangan                                            |
| ------------------- | --------------------------- | ----------------------------------------------------- |
| `VITE_API_BASE_URL` | `http://localhost:8000/api` | Base URL backend Laravel, dipakai `lib/api/client.ts` |

Variable di Vite **wajib** diawali `VITE_` supaya ke-expose ke kode frontend —
kalau tidak diberi prefix ini, `import.meta.env` tidak akan bisa membacanya.

---

## Konfigurasi CORS (Laravel)

Karena frontend (`localhost:5173`) dan backend (`localhost:8000`) beda origin,
Laravel wajib diizinkan menerima request dari frontend. Di `backend/config/cors.php`:

```php
'paths' => ['api/*'],
'allowed_methods' => ['*'],
'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:5173')],
'allowed_headers' => ['*'],
'supports_credentials' => false,
```

`supports_credentials` diset `false` karena auth pakai token Bearer di header
`Authorization`, bukan cookie — jadi browser tidak perlu diizinkan kirim credential
otomatis lintas origin.

---

## Urutan Perintah Setelah Clone (Ringkasan)

```bash
# Database
# → nyalakan MySQL via XAMPP Control Panel, buat database "scrollify" di phpMyAdmin

# Backend
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve

# Frontend (terminal baru)
cd frontend
npm install
cp .env.example .env
npm run dev
```
