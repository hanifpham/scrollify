# Scrollify — API Contract

Lokasi file: root `scrollify/API_CONTRACT.md`

Dokumen ini adalah **kontrak resmi** antara backend (Laravel) dan frontend (React).
Backend wajib mengembalikan response persis seperti yang didefinisikan di sini.
Frontend wajib menulis TypeScript interface (`frontend/src/lib/types/api.ts`) yang
sama persis dengan bentuk di sini — bukan menebak dari response asli MangaDex.

**Base URL (dev):** `http://localhost:8000/api`
**Format:** semua request/response JSON. Semua endpoint di-prefix `/api`.
**Auth:** endpoint yang butuh login memakai Laravel Sanctum **API token (Bearer)**
— bukan cookie-based SPA authentication. Frontend menyimpan token hasil
`/auth/login` atau `/auth/register`, lalu mengirimkannya di setiap request yang
butuh auth lewat header `Authorization: Bearer {token}`. Dipilih dengan
pertimbangan portabilitas ke PWA dan kemungkinan wrap ke mobile app nanti, di mana
cookie-based auth lebih rapuh.

**Konvensi response sukses (semua endpoint):**

```json
{
  "data": { ... } ,
  "meta": { ... }   // opsional, dipakai untuk pagination
}
```

**Konvensi response error (semua endpoint):**

```json
{
  "message": "Deskripsi error singkat",
  "errors": { "field_name": ["pesan validasi"] } // opsional, khusus 422
}
```

---

## 1. Auth

### `POST /auth/register`

**Body:** `{ "name": string, "email": string, "password": string }`
**Response 201:**

```json
{
  "data": {
    "user": {
      "id": 1,
      "name": "Hanif",
      "email": "hanif@mail.com",
      "avatar_url": null
    },
    "token": "1|abcdef..."
  }
}
```

### `POST /auth/login`

**Body:** `{ "email": string, "password": string }`
**Response 200:** sama seperti register.
**Response 422:** kredensial salah.

### `POST /auth/logout`

**Auth required.** **Response 204**, tidak ada body.

### `GET /auth/me`

**Auth required.** **Response 200:** `{ "data": { "id": 1, "name": "Hanif", "email": "...", "avatar_url": "..." } }`

---

## 2. Manga (proxy MangaDex — hasil sudah dirapikan lewat DTO)

Semua endpoint di bawah ini backend yang panggil MangaDex, cache hasilnya
(Redis, TTL disesuaikan per jenis data), lalu kembalikan dalam bentuk `MangaSummary`
atau `MangaDetail` — **bukan** bentuk mentah MangaDex.

### Bentuk `MangaSummary` (dipakai di list — Rekomendasi, Update, Populer)

```json
{
  "id": "a96676e5-8ae2-425e-b549-7ce1990e6c15",
  "title": "Solo Leveling",
  "cover_url": "https://.../cover.jpg",
  "format": "manhwa", // manhwa | manga | manhua
  "status": "ongoing", // ongoing | completed | hiatus | cancelled
  "rating": 9.5,
  "views_label": "2.5M views",
  "latest_chapter": {
    "id": "...",
    "number": "184",
    "readable_at": "2026-08-01T10:00:00Z"
  },
  "is_new": true,
  "tags": ["action", "fantasy"]
}
```

### `GET /manga/recommendations`

**Query params:** `format` (`manhwa` | `manga` | `manhua`, wajib), `limit` (default 5)
**Response 200:** `{ "data": MangaSummary[] }`

Dipetakan ke section **Rekomendasi** di Beranda — satu request per tab yang aktif.

### `GET /manga/updates`

**Query params:** `type` (`project` | `mirror`, wajib), `page` (default 1), `per_page` (default 30)
**Response 200:**

```json
{
  "data": MangaSummary[],
  "meta": { "current_page": 1, "last_page": 3, "total": 90 }
}
```

Dipetakan ke section **Update**. Backend filter berdasarkan tabel
`scanlator_mappings` (lihat `DATABASE_SCHEMA.md`) untuk menentukan manga mana yang
masuk tab "project" vs "mirror", baru ambil chapter terbarunya dari MangaDex.

### `GET /manga/popular`

**Query params:** `period` (`daily` | `weekly` | `all`, wajib), `limit` (default 5)
**Response 200:** `{ "data": MangaSummary[] }` — array sudah terurut dari rank 1.

Dipetakan ke section **Populer**. Backend agregasi dari tabel `manga_views`.

### `GET /manga/{id}`

**Response 200:** `MangaDetail` (superset dari `MangaSummary`, tambah `description`,
`author`, `artist`, `chapters: ChapterSummary[]`).

### `GET /manga/{id}/chapters/{chapterId}`

**Response 200:**

```json
{
  "data": {
    "id": "...",
    "manga_id": "...",
    "number": "184",
    "title": "The Hunter's Guild",
    "pages": ["https://.../page1.jpg", "https://.../page2.jpg"],
    "translated_language": "id"
  }
}
```

Dipakai halaman **Reader**.

---

## 3. Beranda — konten kurasi sendiri

### `GET /banners`

**Response 200:**

```json
{
  "data": [
    {
      "id": 1,
      "title": "Job Change Log",
      "subtitle": "Job Sistemnya...",
      "description": "Joy dan Max adalah rekan kerja pada suatu perusahaan Start-Up...",
      "image_url": "https://.../banner1.jpg",
      "badge_label": "ROMANCE",
      "link_type": "manga",
      "link_value": "a96676e5-..."
    }
  ]
}
```

Hanya banner dengan `is_active = true`, terurut sesuai `display_order`.

### `GET /announcements`

**Response 200:**

```json
{
  "data": [
    {
      "id": 1,
      "title": "Premium Sekarang Cuma 12500!!!",
      "thumbnail_url": "...",
      "published_at": "2025-11-16"
    }
  ]
}
```

---

## 4. Library (butuh auth)

### `GET /bookmarks`

**Auth required.** **Response 200:** `{ "data": MangaSummary[] }` — diambil dari tabel
`bookmarks`, digabung info terbaru dari cache MangaDex (cover/judul terbaru kalau
berubah, fallback ke kolom cache kalau MangaDex sedang tidak bisa diakses).

### `POST /bookmarks`

**Auth required.** **Body:** `{ "manga_id": string }`
**Response 201:** `{ "data": { "id": 12, "manga_id": "...", "created_at": "..." } }`
**Response 409:** kalau sudah pernah di-bookmark.

### `DELETE /bookmarks/{mangaId}`

**Auth required.** **Response 204.**

### `GET /reading-history`

**Auth required.** **Response 200:**

```json
{
  "data": [
    {
      "manga_id": "...",
      "manga_title": "...",
      "manga_cover_url": "...",
      "chapter_number": "184",
      "last_page_read": 12,
      "read_at": "..."
    }
  ]
}
```

### `PUT /reading-history`

**Auth required.** **Body:** `{ "manga_id": string, "chapter_id": string, "chapter_number": string, "last_page_read": number }`
**Response 200.** Upsert — kalau sudah ada baris untuk kombinasi user+manga+chapter,
di-update, bukan insert baru (lihat constraint di `DATABASE_SCHEMA.md`).

---

## 5. Schedule

### `GET /schedules`

**Query params:** `day` (opsional, `monday`...`sunday` — kalau kosong, kembalikan semua hari)
**Response 200:**

```json
{
  "data": {
    "monday": [ { "manga_id": "...", "manga_title": "...", "manga_cover_url": "...", "release_time": "10:00" } ],
    "tuesday": [ ... ]
  }
}
```

---

## Aturan Tambahan untuk Implementasi

- **Semua endpoint list yang menampilkan komik pakai bentuk `MangaSummary` yang sama
  persis** — jangan bikin bentuk berbeda-beda per endpoint (misal `updates` punya
  field beda dari `recommendations`). Konsistensi ini yang bikin frontend bisa
  reuse satu komponen `ComicCard` untuk berbagai section.
- Field `is_new` di `MangaSummary` dihitung backend (misal: chapter terbit dalam
  7 hari terakhir), bukan dikirim mentah dari MangaDex — MangaDex tidak punya
  konsep ini.
- Semua timestamp pakai format ISO 8601 UTC (`2026-08-01T10:00:00Z`). Konversi ke
  waktu lokal/format tampilan dilakukan di frontend, bukan di backend.
- Rate limit dari MangaDex ditangani sepenuhnya di `MangaDexCacheService` — endpoint
  di atas **tidak pernah** meneruskan error rate-limit MangaDex mentah-mentah ke
  frontend. Kalau MangaDex sedang bermasalah, backend kembalikan data dari cache
  terakhir yang valid (stale-while-revalidate), bukan error 500.

---

## Checklist Sinkronisasi

Setiap kali menambah/mengubah endpoint di backend:

1. Update dokumen ini dulu.
2. Update `frontend/src/lib/types/api.ts` supaya cocok.
3. Baru implementasi controller & komponen yang konsumsi.

Jangan pernah balik urutannya — dokumen ini yang jadi sumber kebenaran, bukan kode
yang ditulis lebih dulu lalu dokumen menyesuaikan belakangan.
