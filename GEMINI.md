# E-Report Project Context (Singapore Piaget Academy)

## 🎯 Project Overview
Sistem E-Report yang dirancang untuk mengelola penilaian siswa dengan standar kurikulum internasional. Sistem ini membagi peran pengguna menjadi Guru (Teacher), Mentor, dan Orang Tua (Parent).

## 🏛️ Domain Rules & RBAC
1. **[TEACHER]**: 
   - Akses penuh ke input nilai akademik & deskripsi evaluasi.
   - Mengelola **Master Rubrik**: Merancang kategori penilaian dan sub-kriteria secara bebas.
2. **[MENTOR]**: 
   - Akses tulis terbatas. Tidak bisa input nilai angka.
   - Hanya mengisi deskripsi evaluasi (Affective Domain).
3. **[PARENT]**: 
   - Akses baca (Read-only). Melihat grafik perkembangan dan mengekspor raport.

## ⚙️ Backend Standards & Rules
- **Authentication**: Login **WAJIB** menggunakan **Email**. Jangan gunakan username untuk proses autentikasi di frontend maupun backend.
- **Data Integrity**: Penilaian (Score) menggunakan skala 1.00 - 3.00 (decimal).
- **Ownership**: Guru hanya dapat mengelola data yang terkait dengan `teacher_id` mereka.
- **Religious Studies (RS) Rule**: Semua mata pelajaran Agama (Islam, Kristen, dll) **WAJIB** menggunakan rubrik statis dengan nama kategori **"Religious Studies / Agama"**. Kriteria di dalamnya bersifat seragam untuk semua agama, yaitu:
  1. *Demonstrates good understanding of subject matter*
  2. *Participates actively in lessons*
- **Collaboration**: Guru Agama yang berbeda mengisi kriteria yang sama (tetapi di subjek masing-masing dalam satu grup `RS_PKN`). Guru **PKN** juga tergabung dalam grup kolaborasi ini.
- **Religious Cross-Check (Benang Merah)**: Guru Agama **HANYA** diizinkan menilai siswa yang agamanya cocok dengan kategori subjek (contoh: Guru Mapel *Religion (Islam)* hanya bisa menilai siswa yang beragama *Islam*). Sistem akan memblokir akses jika terjadi ketidakcocokan.

## 🧱 Core Architecture: Rubric System (Parent-Child)
Ini adalah fitur inti (Core Feature) aplikasi. Penilaian tidak lagi bersifat datar, melainkan berjenjang:

- **Level 1: Rubric Category** (Parent)
  - Contoh: *Reading & Listening*, *Mathematics T1*, *PKN*.
- **Level 2: Rubric Criteria** (Child)
  - Contoh di bawah *Reading*: *Phonics*, *Fluency*, *Comprehension*.
  - Guru dapat menambah/mengedit kriteria ini secara dinamis melalui menu **Perancangan Penilaian**.
- **Level 3: Report Detail** (Scores)
  - Nilai (1.00 - 3.00) dan deskripsi diberikan di level **Criteria**.
  - Nilai rata-rata raport (`average_value`) dihitung otomatis berdasarkan kelengkapan kriteria.

## 📊 Dynamic Score Status Logic
Status kelengkapan nilai siswa ditentukan secara dinamis:
- **Completed**: 100% kriteria yang dirancang telah diisi nilainya.
- **Draft**: Sudah ada nilai masuk, namun ada kriteria baru (atau lama) yang belum terisi (misal: Guru baru saja menambah kriteria di Master Rubrik).
- **None**: Belum ada satu pun kriteria yang dinilai.

## 🛠️ Technical Standards
- **Backend (Laravel)**: 
  - Gunakan `updateOrCreate` untuk menjaga integritas data saat revisi nilai.
  - Pastikan setiap tabel memiliki `id` primary key untuk mendukung update via Eloquent.
  - Relasi: `RubricCategory` hasMany `RubricCriteria` hasMany `ReportDetail`.
- **Frontend (Next.js)**:
  - **State Management**: Gunakan TanStack Query untuk caching data guru dan siswa.
  - **Routing**: Gunakan `router.replace` untuk navigasi kembali agar history browser bersih.
  - **UI/UX**: Gunakan skeleton loader saat fetching data dan loading state pada setiap tombol aksi (Mutations).

## 📂 Data Seeding (Real CSV Context)
Data awal ditarik dari folder `backend/database/real-data-csv`. 
- Prefix `Y`: Mewakili Year (Tingkat Kelas).
- Prefix `T`: Mewakili Term (Periode).
- Logika Seeder: Deskripsi gabungan di CSV (dipisah `;`) harus di-split otomatis menjadi baris `RubricCriteria` tersendiri saat proses seeding.

## 🤝 Collaborative Assessment & Shared Rubrics
Mendukung kasus di mana satu kategori raport (misal: **RS PKN**) diisi oleh beberapa guru (Guru PKN + Guru Agama).
- **Grouping**: Subjek dikelompokkan via `report_group_key`.
- **Ownership**: Form penilaian menampilkan semua kriteria dalam satu grup, namun hanya kriteria milik guru yang login (`is_mine: true`) yang dapat diedit. Kriteria guru lain ditampilkan sebagai **Read-Only**.
- **Status Independence**: Kelengkapan nilai (Draft/Completed) dihitung per-guru berdasarkan kriteria yang mereka miliki dalam grup tersebut.