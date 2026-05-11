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

## 🧱 Core Architecture: Rubric System (Parent-Child)
Ini adalah fitur inti (Core Feature) aplikasi. Penilaian tidak lagi bersifat datar, melainkan berjenjang:

- **Level 1: Rubric Category** (Parent)
  - Contoh: *Reading & Listening*, *Mathematics T1*, *Social Skills*.
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