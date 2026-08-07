# Scrollify — Project Structure

Dokumen ini adalah acuan struktur folder & file untuk seluruh project Scrollify.
Taruh file ini di **root** (`scrollify/PROJECT_STRUCTURE.md`) — sejajar dengan folder
`backend/` dan `frontend/`. Setiap kali membuat file baru, cek dulu ke dokumen ini
supaya lokasinya konsisten dan tidak nyasar.

Prinsip pembagian: `backend/` = sumber data & business logic (Laravel + MySQL),
`frontend/` = tampilan & interaksi user (React + Vite). Tidak ada logic bisnis di
frontend, tidak ada styling/markup di backend.

---

## Struktur Root

```
scrollify/
├── backend/                    # Laravel API
├── frontend/                   # React (Vite) app
├── API_CONTRACT.md             # Kontrak endpoint — dipakai backend & frontend
├── PROJECT_STRUCTURE.md        # Dokumen ini
└── README.md                   # Cara jalanin project (setup, env, run dev)
```

`API_CONTRACT.md` sengaja di root, bukan di salah satu folder, karena dua sisi
(backend yang implementasi, frontend yang konsumsi) sama-sama butuh baca dokumen
yang **sama persis** — kalau ditaruh di satu folder saja, gampang jadi tidak sinkron.

---

## `backend/` — Laravel

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/
│   │   │       ├── MangaController.php        # search, detail, by-tag (rekomendasi)
│   │   │       ├── ChapterController.php      # feed chapter terbaru (section Update)
│   │   │       ├── BookmarkController.php     # CRUD bookmark user (Library)
│   │   │       ├── BannerController.php       # hero carousel
│   │   │       ├── AnnouncementController.php # panel pengumuman
│   │   │       ├── PopularController.php      # ranking harian/mingguan/semua
│   │   │       └── AuthController.php         # login, register, logout (Sanctum)
│   │   ├── Middleware/
│   │   │   └── EnsureMangaDexCacheFresh.php   # (opsional) validasi cache sebelum request
│   │   └── Requests/
│   │       └── StoreBookmarkRequest.php        # validasi input, satu per action penting
│   │
│   ├── Models/
│   │   ├── User.php
│   │   ├── Bookmark.php
│   │   ├── Banner.php
│   │   ├── Announcement.php
│   │   ├── MangaView.php                       # log view harian per manga (buat Populer)
│   │   └── ScanlatorMapping.php                # mapping Project vs Mirror
│   │
│   ├── Services/
│   │   └── MangaDex/
│   │       ├── MangaDexClient.php              # satu-satunya tempat panggil HTTP ke MangaDex
│   │       ├── MangaDexCacheService.php        # wrapper Cache::remember() per endpoint
│   │       └── DTOs/
│   │           ├── MangaData.php               # bentuk data manga yang SUDAH dirapikan
│   │           └── ChapterData.php             # bentuk data chapter yang sudah dirapikan
│   │
│   └── Providers/
│       └── AppServiceProvider.php
│
├── config/
│   └── mangadex.php                            # base URL, rate limit, cache TTL — bukan hardcode
│
├── database/
│   ├── migrations/                              # satu file per tabel, sesuai DATABASE_SCHEMA.md
│   └── seeders/
│       └── DemoDataSeeder.php                   # data dummy buat testing lokal
│
├── routes/
│   └── api.php                                  # SEMUA route lewat sini, prefix /api
│
├── tests/
│   └── Feature/
│       └── MangaControllerTest.php              # minimal test untuk endpoint kritikal
│
├── .env.example
├── composer.json
└── DATABASE_SCHEMA.md                           # skema lengkap tabel (khusus backend)
```

**Kenapa ada folder `Services/MangaDex/`:** ini bagian paling penting secara arsitektur.
Semua komunikasi ke MangaDex API **wajib** lewat `MangaDexClient.php` — controller
tidak boleh panggil `Http::get()` ke MangaDex langsung. Alasannya: kalau nanti rate
limit berubah, endpoint MangaDex berubah, atau kamu nambah provider API kedua, kamu
cuma edit satu tempat, bukan bongkar semua controller.

**Kenapa ada folder `DTOs/`:** response asli dari MangaDex itu nested dan agak
berantakan (relationships, attributes bertingkat). DTO ini adalah "bentuk bersih"
yang dikembalikan ke frontend, sesuai apa yang didefinisikan di `API_CONTRACT.md`.
Frontend tidak pernah lihat bentuk asli response MangaDex.

---

## `frontend/` — React + Vite

```
frontend/
├── src/
│   ├── components/
│   │   ├── ui/                          # primitives generic, reusable di mana saja
│   │   │   ├── Button.tsx
│   │   │   ├── Tag.tsx
│   │   │   ├── Input.tsx
│   │   │   └── Card.tsx                 # base card, dipakai turunan di bawah
│   │   │
│   │   ├── comic/                       # komponen spesifik konten komik
│   │   │   ├── ComicCard.tsx            # dipakai di section Rekomendasi
│   │   │   ├── UpdateCard.tsx           # dipakai di section Update (compact shadow)
│   │   │   ├── PopularCard.tsx          # dipakai di section Populer (ranking)
│   │   │   └── RatingBadge.tsx          # badge bintang, pakai token accent-amber
│   │   │
│   │   └── layout/
│   │       ├── Navbar.tsx
│   │       ├── Footer.tsx
│   │       └── SectionHeader.tsx        # header berwarna (REKOMENDASI/UPDATE/POPULER)
│   │
│   ├── pages/
│   │   ├── Home.tsx
│   │   ├── Library.tsx
│   │   ├── Schedule.tsx
│   │   ├── MangaDetail.tsx
│   │   └── Reader.tsx                   # halaman baca chapter
│   │
│   ├── lib/
│   │   ├── api/
│   │   │   ├── client.ts                # instance fetch/axios dasar ke backend Laravel
│   │   │   ├── manga.ts                 # fungsi getRecommendations(), getManga(id), dst
│   │   │   └── bookmarks.ts
│   │   │
│   │   └── types/
│   │       └── api.ts                   # TypeScript interface, HARUS sama persis dgn API_CONTRACT.md
│   │
│   ├── hooks/
│   │   └── useBookmarks.ts
│   │
│   ├── styles/
│   │   └── tokens.css                   # variabel CSS turunan dari DESIGN.md (warna, spacing)
│   │
│   ├── App.tsx
│   └── main.tsx
│
├── public/
├── DESIGN.md                            # sudah ada — token desain neobrutalism
├── package.json
├── vite.config.ts
└── tailwind.config.js                   # extend theme pakai token dari DESIGN.md, jangan hardcode hex
```

**Kenapa `lib/types/api.ts` penting:** ini yang saya sebut sebelumnya — tulis dulu
interface TypeScript dari bentuk response backend (yang formatnya sudah dijamin
konsisten oleh DTO di sisi Laravel), sebelum bikin komponen yang konsumsi data itu.
Ini mencegah agent AI menebak-nebak field yang ada di response.

**Kenapa `components/ui/` dan `components/comic/` dipisah:** `ui/` isinya elemen
generic yang tidak tahu-menahu soal komik (Button, Tag, Input) — bisa dipakai ulang
di halaman mana saja. `comic/` isinya komponen yang sudah tahu bentuk data manga
(ComicCard butuh `title`, `coverUrl`, `rating`, dst). Pemisahan ini bikin AI agent
tidak bingung mau taruh komponen baru di mana.

---

## Urutan Pembuatan File (ringkas dari diskusi sebelumnya)

1. `backend/DATABASE_SCHEMA.md` → migration → model
2. `backend/app/Services/MangaDex/` (client + cache + DTO)
3. `backend/app/Http/Controllers/Api/` (satu per satu, sesuai `API_CONTRACT.md`)
4. `frontend/src/lib/types/api.ts` (samakan dengan response backend yang sudah jalan)
5. `frontend/src/components/ui/` (primitives dulu)
6. `frontend/src/components/comic/` (baru setelah primitives siap)
7. `frontend/src/pages/Home.tsx` (rakit semua komponen jadi halaman)
