# Scrollify — Database Schema

Lokasi file: `backend/DATABASE_SCHEMA.md`

Database: **MySQL 8.0**, diakses lewat Eloquent ORM (Laravel).

## Prinsip Desain

1. **Konten komik (judul, cover, chapter, gambar) TIDAK disimpan permanen di sini.**
   Semua itu tetap live-fetch dari MangaDex API lewat `MangaDexClient` (lihat
   `PROJECT_STRUCTURE.md`), dan cuma singgah sebentar di cache (Redis), bukan di MySQL.
2. Tabel di database ini isinya murni **data milik Scrollify sendiri**: akun user,
   bookmark, kurasi konten (banner/pengumuman), tracking (view count), dan mapping
   custom (scanlator project/mirror, jadwal rilis).
3. Setiap tabel yang **mereferensikan komik dari MangaDex** menyimpan `manga_id` dalam
   bentuk `CHAR(36)` (format UUID MangaDex), bukan foreign key ke tabel manga lokal —
   karena memang tidak ada tabel manga lokal.
4. Sebagian tabel menyimpan **kolom cache/denormalized** (misal `manga_title`,
   `manga_cover_url`) supaya list (misal Library) bisa render cepat tanpa panggil
   MangaDex API berkali-kali. Kolom ini boleh sedikit basi (stale) — bukan sumber
   kebenaran, cuma optimasi tampilan. Sumber kebenaran tetap MangaDex API.

---

## Daftar Tabel

| Tabel                | Fungsi                             | Dipakai di fitur                                      |
| -------------------- | ---------------------------------- | ----------------------------------------------------- |
| `users`              | Akun pengguna                      | Auth, semua fitur personal                            |
| `bookmarks`          | Komik yang disimpan user           | Library                                               |
| `reading_history`    | Riwayat baca per chapter           | Library ("lanjut baca"), rekomendasi personal (nanti) |
| `banners`            | Konten hero carousel               | Beranda — Hero                                        |
| `announcements`      | Panel pengumuman                   | Beranda — Pengumuman                                  |
| `manga_views`        | Log kunjungan halaman detail manga | Beranda — Populer (Harian/Mingguan/Semua)             |
| `scanlator_mappings` | Mapping grup Project vs Mirror     | Beranda — Update (tab Project/Mirror)                 |
| `release_schedules`  | Jadwal rilis chapter per hari      | Menu Schedule                                         |

Tabel `personal_access_tokens` dan `sessions` dibuat otomatis oleh Laravel Sanctum —
tidak perlu migration manual, cukup jalankan `php artisan install:api`.

---

## 1. `users`

| Kolom                      | Tipe                                  | Keterangan                     |
| -------------------------- | ------------------------------------- | ------------------------------ |
| `id`                       | `BIGINT UNSIGNED, PK, AUTO_INCREMENT` |                                |
| `name`                     | `VARCHAR(255)`                        |                                |
| `email`                    | `VARCHAR(255), UNIQUE`                |                                |
| `email_verified_at`        | `TIMESTAMP, NULLABLE`                 |                                |
| `password`                 | `VARCHAR(255)`                        | hashed                         |
| `avatar_url`               | `VARCHAR(500), NULLABLE`              | avatar profil (ikon di navbar) |
| `remember_token`           | `VARCHAR(100), NULLABLE`              |                                |
| `created_at`, `updated_at` | `TIMESTAMP`                           |                                |

Tidak ada yang istimewa — tabel user standar Laravel. Ditambah `avatar_url` untuk
ikon profil yang muncul di navbar.

---

## 2. `bookmarks`

| Kolom             | Tipe                                                 | Keterangan                           |
| ----------------- | ---------------------------------------------------- | ------------------------------------ |
| `id`              | `BIGINT UNSIGNED, PK, AUTO_INCREMENT`                |                                      |
| `user_id`         | `BIGINT UNSIGNED, FK -> users.id, CASCADE ON DELETE` |                                      |
| `manga_id`        | `CHAR(36)`                                           | UUID manga dari MangaDex             |
| `manga_title`     | `VARCHAR(255)`                                       | cache, untuk render cepat di Library |
| `manga_cover_url` | `VARCHAR(500)`                                       | cache                                |
| `created_at`      | `TIMESTAMP`                                          | dipakai untuk urutan "baru disimpan" |

**Constraint:** `UNIQUE (user_id, manga_id)` — satu user tidak bisa bookmark manga
yang sama dua kali.

**Index:** `INDEX (user_id)` untuk query "semua bookmark milik user X" (dipakai di
halaman Library).

---

## 3. `reading_history`

| Kolom             | Tipe                                                 | Keterangan                                                                            |
| ----------------- | ---------------------------------------------------- | ------------------------------------------------------------------------------------- |
| `id`              | `BIGINT UNSIGNED, PK, AUTO_INCREMENT`                |                                                                                       |
| `user_id`         | `BIGINT UNSIGNED, FK -> users.id, CASCADE ON DELETE` |                                                                                       |
| `manga_id`        | `CHAR(36)`                                           |                                                                                       |
| `manga_title`     | `VARCHAR(255)`                                       | cache                                                                                 |
| `manga_cover_url` | `VARCHAR(500)`                                       | cache                                                                                 |
| `chapter_id`      | `CHAR(36)`                                           | UUID chapter dari MangaDex                                                            |
| `chapter_number`  | `VARCHAR(20)`                                        | disimpan sebagai string karena MangaDex kadang pakai format non-integer, misal "10.5" |
| `last_page_read`  | `SMALLINT UNSIGNED, NULLABLE`                        | untuk fitur "lanjut dari halaman terakhir"                                            |
| `read_at`         | `TIMESTAMP`                                          |                                                                                       |

**Constraint:** `UNIQUE (user_id, manga_id, chapter_id)` — satu baris per kombinasi
user+chapter; kalau dibaca ulang, `UPDATE` baris yang sama (upsert), bukan insert baru.

**Index:** `INDEX (user_id, manga_id)` untuk query "chapter terakhir dibaca dari manga
tertentu" (buat tombol "Lanjut Baca").

---

## 4. `banners`

| Kolom                      | Tipe                                  | Keterangan                                                  |
| -------------------------- | ------------------------------------- | ----------------------------------------------------------- |
| `id`                       | `BIGINT UNSIGNED, PK, AUTO_INCREMENT` |                                                             |
| `manga_id`                 | `CHAR(36), NULLABLE`                  | boleh kosong kalau banner bukan promosi manga tertentu      |
| `title`                    | `VARCHAR(255)`                        | judul besar di banner, misal "Job Change Log"               |
| `subtitle`                 | `VARCHAR(255), NULLABLE`              | teks kecil di atas judul, misal "Job Sistemnya..."          |
| `description`              | `TEXT, NULLABLE`                      | paragraf pendek di bawah judul                              |
| `image_url`                | `VARCHAR(500)`                        | gambar background banner                                    |
| `badge_label`              | `VARCHAR(50), NULLABLE`               | tag kecil seperti "ROMANCE" di pojok                        |
| `link_type`                | `ENUM('manga', 'external', 'none')`   | tujuan klik banner                                          |
| `link_value`               | `VARCHAR(500), NULLABLE`              | `manga_id` lagi, atau URL eksternal, tergantung `link_type` |
| `display_order`            | `SMALLINT UNSIGNED`                   | urutan tampil di carousel                                   |
| `is_active`                | `BOOLEAN, DEFAULT true`               | admin bisa matikan tanpa hapus data                         |
| `starts_at`, `ends_at`     | `TIMESTAMP, NULLABLE`                 | untuk banner musiman/promo terbatas waktu                   |
| `created_at`, `updated_at` | `TIMESTAMP`                           |                                                             |

**Index:** `INDEX (is_active, display_order)` — query utama section Hero adalah
"ambil banner aktif, urut sesuai display_order".

---

## 5. `announcements`

| Kolom                      | Tipe                                  | Keterangan                                         |
| -------------------------- | ------------------------------------- | -------------------------------------------------- |
| `id`                       | `BIGINT UNSIGNED, PK, AUTO_INCREMENT` |                                                    |
| `title`                    | `VARCHAR(255)`                        | misal "Premium Sekarang Cuma 12500!!!"             |
| `thumbnail_url`            | `VARCHAR(500), NULLABLE`              | ikon kecil di kiri item pengumuman                 |
| `published_at`             | `DATE`                                | tanggal yang ditampilkan, misal "16 November 2025" |
| `is_active`                | `BOOLEAN, DEFAULT true`               |                                                    |
| `display_order`            | `SMALLINT UNSIGNED, DEFAULT 0`        |                                                    |
| `created_at`, `updated_at` | `TIMESTAMP`                           |                                                    |

**Index:** `INDEX (is_active, published_at)`.

---

## 6. `manga_views`

Log mentah tiap kali halaman detail manga dibuka. Dipakai untuk hitung ranking
Populer (Harian/Mingguan/Semua) — kebutuhan yang tidak bisa dipenuhi MangaDex API
karena mereka cuma expose total follows/rating, bukan breakdown per periode waktu.

| Kolom       | Tipe                                                            | Keterangan                              |
| ----------- | --------------------------------------------------------------- | --------------------------------------- |
| `id`        | `BIGINT UNSIGNED, PK, AUTO_INCREMENT`                           |                                         |
| `manga_id`  | `CHAR(36)`                                                      |                                         |
| `viewed_at` | `TIMESTAMP`                                                     | waktu persis dibuka                     |
| `user_id`   | `BIGINT UNSIGNED, FK -> users.id, NULLABLE, SET NULL ON DELETE` | boleh null untuk guest yang belum login |

**Index:** `INDEX (manga_id, viewed_at)` — kombinasi ini yang dipakai query
agregasi ranking (`GROUP BY manga_id WHERE viewed_at BETWEEN ...`).

**Catatan skala:** tabel ini akan tumbuh cepat (satu baris per view). Untuk MVP,
raw log + `GROUP BY` cukup. Kalau traffic sudah besar nanti, pertimbangkan tabel
agregat tambahan (`manga_view_daily_counts`: `manga_id`, `view_date`, `view_count`)
yang di-increment via job terjadwal, supaya query ranking tidak scan jutaan baris
tiap kali Beranda dibuka. Tidak perlu dibuat sekarang — cukup dicatat di sini
sebagai rencana optimasi lanjutan.

---

## 7. `scanlator_mappings`

Menyimpan keputusan kurasi kamu: untuk manga tertentu, grup scanlator mana yang
dianggap "Project" (sumber utama yang kamu track) vs "Mirror" (cadangan). Konsep
ini tidak ada di MangaDex, jadi harus dikelola manual di sini.

| Kolom                      | Tipe                                  | Keterangan                                            |
| -------------------------- | ------------------------------------- | ----------------------------------------------------- |
| `id`                       | `BIGINT UNSIGNED, PK, AUTO_INCREMENT` |                                                       |
| `manga_id`                 | `CHAR(36)`                            |                                                       |
| `scanlation_group_id`      | `CHAR(36)`                            | UUID grup scanlator dari MangaDex                     |
| `group_type`               | `ENUM('project', 'mirror')`           | menentukan manga ini masuk tab mana di section Update |
| `priority`                 | `SMALLINT UNSIGNED, DEFAULT 0`        | kalau ada beberapa grup "mirror", urutan preferensi   |
| `created_at`, `updated_at` | `TIMESTAMP`                           |                                                       |

**Constraint:** `UNIQUE (manga_id, scanlation_group_id)`.

**Index:** `INDEX (manga_id, group_type)` — dipakai saat filter feed chapter per tab.

---

## 8. `release_schedules`

Untuk menu **Schedule** — jadwal rilis chapter per hari, per judul.

| Kolom                      | Tipe                                                                           | Keterangan                             |
| -------------------------- | ------------------------------------------------------------------------------ | -------------------------------------- |
| `id`                       | `BIGINT UNSIGNED, PK, AUTO_INCREMENT`                                          |                                        |
| `manga_id`                 | `CHAR(36)`                                                                     |                                        |
| `manga_title`              | `VARCHAR(255)`                                                                 | cache untuk render cepat               |
| `manga_cover_url`          | `VARCHAR(500)`                                                                 | cache                                  |
| `release_day`              | `ENUM('monday','tuesday','wednesday','thursday','friday','saturday','sunday')` |                                        |
| `release_time`             | `TIME, NULLABLE`                                                               | jam perkiraan rilis, kalau ada         |
| `is_active`                | `BOOLEAN, DEFAULT true`                                                        | matikan kalau judul sudah tamat/hiatus |
| `created_at`, `updated_at` | `TIMESTAMP`                                                                    |                                        |

**Index:** `INDEX (release_day, is_active)` — query utama adalah "tampilkan semua
judul yang rilis hari Senin".

---

## Ringkasan Relasi

```
users 1───* bookmarks
users 1───* reading_history

(manga_id dari MangaDex, tanpa FK lokal)
        ├── bookmarks.manga_id
        ├── reading_history.manga_id
        ├── banners.manga_id (nullable)
        ├── manga_views.manga_id
        ├── scanlator_mappings.manga_id
        └── release_schedules.manga_id
```

Tidak ada tabel `manga` lokal yang jadi pusat foreign key — semua tabel di atas
menyimpan `manga_id` sebagai referensi longgar ke MangaDex, bukan relasi database
formal. Ini konsekuensi wajar dari arsitektur "konten live-fetch dari API eksternal"
yang sudah kita sepakati di awal.

---

## Urutan Migration yang Disarankan

1. `create_users_table` (sudah default Laravel, tinggal tambah kolom `avatar_url`)
2. `create_bookmarks_table`
3. `create_reading_history_table`
4. `create_banners_table`
5. `create_announcements_table`
6. `create_manga_views_table`
7. `create_scanlator_mappings_table`
8. `create_release_schedules_table`
9. Install Sanctum (`personal_access_tokens` otomatis)

Urutan ini tidak strict karena tidak ada foreign key antar tabel `manga_id` (cuma
`user_id` yang FK ke `users`, jadi `users` harus dibuat duluan) — tapi ikuti urutan
ini biar sesuai prioritas fitur yang kita rencanakan (Beranda dulu → Library →
Schedule).
