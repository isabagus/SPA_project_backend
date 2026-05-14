# E-Report Project Context (Singapore Piaget Academy)

## 🎯 Project Overview
Sistem E-Report yang dirancang untuk mengelola penilaian siswa dengan standar kurikulum internasional (Cambridge). Sistem ini membagi peran pengguna menjadi Guru (Teacher), Mentor (Wali Kelas), dan Orang Tua (Parent).

## 🏛️ Domain Rules & RBAC
1. **[TEACHER]**: 
   - Akses penuh ke input nilai akademik & deskripsi kualitatif.
   - Mengelola **Master Rubrik**: Merancang kategori penilaian dan sub-kriteria secara bebas.
2. **[MENTOR / CLASS TEACHER]**: 
   - Wali Kelas bertanggung jawab atas satu **Class Level** (misal: Year 1).
   - **Academic Monitoring**: Memantau perkembangan nilai seluruh siswa di kelasnya secara real-time.
   - **Qualitative Feedback**: Memiliki otoritas untuk **mengubah/memperhalus deskripsi penilaian** pada detail rubrik mata pelajaran guna memberikan konteks yang lebih tepat bagi orang tua.
   - Mengelola deskripsi evaluasi perilaku (**Affective Domain**).
3. **[PARENT]**: 
   - Akses baca (Read-only). Melihat ringkasan akademik dan mengunduh raport PDF.

## ⚙️ Backend Standards & Rules
- **Authentication**: Login menggunakan **Email** (backend) atau **Username** (fleksibel di frontend).
- **Data Integrity**: Penilaian (Score) & Rata-rata (`average_value`) menggunakan skala **1.00 - 3.00**.
- **Ownership Check**: Mentor hanya dapat memantau dan mengedit data siswa yang terdaftar dalam kelas perwaliannya (`mentor_id` pada tabel `students`).
- **Religious Studies (RS) Rule**: 
    - **Religion Matching**: Guru hanya dapat menilai murid jika kategori subjek (misal: *Religion Christian*) cocok dengan `religion_name` murid.
    - **Fallback Rule**: Jika tidak ada guru agama spesifik di suatu kelas, Mentor mengambil alih pengisian nilai dan deskripsi.
- **Civics (PKN) Rule**: Diperlakukan seperti **Subject Umum**, di mana deskripsi kriteria diisi secara kolaboratif oleh **Guru Pengampu** dan **Mentor** (Wali Kelas).

## 🤝 Collaborative Assessment (Grouped Subjects)
Memungkinkan beberapa Mata Pelajaran berbagi satu lembar penilaian (misal: RS & PKN).
- **Edit Permission**: Guru hanya bisa mengubah kriteria milik mereka sendiri (`is_mine: true`).
- **Read-Only UI**: Kriteria rekan sejawat tampil sebagai referensi namun terkunci.

## 🧱 Core Architecture: Rubric System
Penilaian berjenjang untuk fleksibilitas kurikulum:
1. **Rubric Category** (Parent): Kategori besar (misal: *English Skills*).
2. **Rubric Criteria** (Child): Detail kompetensi (misal: *Reading Fluency*).
3. **Report Detail** (Scores): Berisi Skor (1-3) dan Deskripsi Kualitatif.

## 🖼️ Frontend Design Standards
- **Accordion Structure**: Halaman detail rubrik (Mentor & Parent) wajib menggunakan struktur Accordion untuk mengelompokkan kriteria berdasarkan kategori rubrik agar informasi terorganisir dengan baik.
- **Visual Feedback**:
  - Skor **>= 2.5**: Warna Emerald (Exceeding).
  - Skor **< 2.5**: Warna Amber (Meeting/Improving).
- **Real-time Status**: Menggunakan indikator status inline ("Tersimpan!", "Gagal") untuk aksi simpan deskripsi tanpa interupsi navigasi.

## 📊 Calculation Logic (Average Value)
- `average_value` dihitung sebagai **rata-rata murni** dari seluruh skor kriteria dalam satu mata pelajaran.
- Nilai akhir tetap berada dalam skala **1.00 - 3.00** untuk menjaga konsistensi dengan skor rubrik individual.

## 🛠️ Development & Testing
- **Data Seeding**: Gunakan `RealDataSeeder` untuk menghasilkan ekosistem data yang lengkap (User, Student, Subject, Rubric, & Scores).
- **Testing Scenario**: Panduan pengujian lintas-role tersedia di `testing_guide.md` (di direktori artifacts).

## 📁 Technical Stack
- **Backend**: Laravel 11, Sanctum Auth, Eloquent ORM.
- **Frontend**: Next.js 14 (App Router), TanStack Query, Tailwind CSS, Lucide Icons.