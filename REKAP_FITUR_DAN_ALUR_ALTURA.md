# REKAP LENGKAP FITUR DAN ALUR SISTEM ALTURA (ALUMNI TUTORIAL)

Dokumen ini memuat rekapitulasi **keseluruhan fitur dan alur kerja (workflow)** yang telah terimplementasi dan aktif pada platform **ALTURA**. Seluruh fitur dikelompokkan secara rapi **per role pengguna** beserta penjelasan fungsional dan diagram alur operasionalnya.

---

## DAFTAR ISI
1. [Ringkasan Eksekutif & Arsitektur Multi-Role](#1-ringkasan-eksekutif--arsitektur-multi-role)
2. [Role 1: Administrator & Front Desk (Manajemen Pusat & Meja Depan)](#2-role-1-administrator--front-desk)
3. [Role 2: Guru & Mentor Akademik (Dual-Role: Pengampu Mapel & Pembimbing 360°)](#3-role-2-guru--mentor-akademik)
4. [Role 3: Guru BK / Konselor (Bimbingan Konseling & EWS)](#4-role-3-guru-bk--konselor)
5. [Role 4: Siswa (Student Portal & CBT Engine)](#5-role-4-siswa-student)
6. [Role 5: Orang Tua / Wali Murid (Parent Monitoring)](#6-role-5-orang-tua--wali-murid)
7. [Mesin Automasi & Layanan Sistem (Under-The-Hood Engines)](#7-mesin-automasi--layanan-sistem)
8. [Matriks Rute & Hak Akses URL](#8-matriks-rute--hak-akses-url)

---

## 1. RINGKASAN EKSEKUTIF & ARSITEKTUR MULTI-ROLE

**ALTURA** dirancang sebagai ekosistem bimbingan belajar komprehensif yang mengintegrasikan pembelajaran akademik, persiapan seleksi PTN/SNBT berbasis CBT, program akselerasi *English Mastery* (Vocab CBT, Wacana, dan Storytelling), serta pendampingan psikologis melalui konseling BK dan keterlibatan aktif orang tua.

### Konsep 5 Role Utama:
* **Admin & Front Desk**: Pengendali master data, penjadwalan, presensi meja depan, kurikulum LMS, bank soal tryout CBT, dan gateway WhatsApp.
* **Guru (Dual-Role)**: Bertindak ganda sebagai **Guru Pengampu Mata Pelajaran** (konten belajar & tentoring) sekaligus **Pembimbing Akademik (Mentor 360°)** bagi sekelompok siswa binaan (mentee).
* **Guru BK (Counselor / Kak Nisa)**: Pengelola kesehatan mental, motivasi belajar, penanganan peringatan dini (Early Warning System / Flagging), dan konsultasi peminatan jurusan kuliah.
* **Siswa**: Peserta belajar yang mengakses LMS video pembelajaran, ujian CBT Tryout & Vocab, pencatatan presensi QR, setoran oral bahasa Inggris, booking konsultasi, dan portofolio rapor 360°.
* **Orang Tua / Wali**: Mitra pemantau perkembangan ananda secara langsung (kehadiran, nilai tryout berkala, akses komunikasi dengan pembimbing, dan booking temu BK).

---

## 2. ROLE 1: ADMINISTRATOR & FRONT DESK

Role Admin memegang kendali operasional sentral bimbel, memastikan master data tersinkronisasi, dan mengelola materi ujian serta presensi harian.

### A. Fitur-Fitur Utama Admin:
1. **Dashboard Eksekutif**:
   * Statistik real-time total siswa aktif, jumlah rombel/kelas, total guru & pembimbing, rekapitulasi presensi harian, dan tryout yang sedang berlangsung.
2. **Master Data Management**:
   * **Jurusan Peminatan**: Pengelolaan rumpun jurusan (Saintek, Soshum, Kedokteran, Kedinasan).
   * **Kelas / Rombel**: Manajemen rombongan belajar, kapasitas kelas, dan penugasan wali kelas/mentor.
   * **Mata Pelajaran (Mapel)**: Pengaturan kurikulum mata pelajaran bimbel.
   * **Data Guru & Mentor**: Manajemen profil guru, kualifikasi, kontak WhatsApp, dan penugasan ganda (Guru Mapel & Pembimbing Mentee).
   * **Data Orang Tua / Wali**: Relasi akun orang tua dengan siswa binaan, nomor telepon, dan alamat.
   * **Data Siswa Terpadu**:
     * Input data manual lengkap (NISN, Nama, Kelas, Peminatan, No. WA Siswa, No. WA Ortu).
     * Auto-generate akun user siswa (login default) dan string identitas QR Code unik.
     * **Fitur Export Data Siswa**: Ekspor database siswa ke dalam format file `.csv`.
     * **Fitur Bulk Import Siswa**: Unggah massal data ratusan siswa baru melalui template Excel (`.xlsx` / `.xls`).
3. **Operasional Akademik & Penjadwalan**:
   * **Jadwal Pelajaran**: Penyusunan jadwal sesi tatap muka per kelas, hari, jam, ruang belajar, dan guru pengampu.
   * **Scanner Meja Depan (Front Desk QR Scanner)**:
     * Antarmuka scanner kamera web / barcode gun di meja lobi depan.
     * Memindai kartu QR siswa saat tiba di bimbel.
     * Pencatatan jam kedatangan instan (Hadir Tepat Waktu vs Terlambat).
     * Memicu log notifikasi WhatsApp otomatis ke nomor orang tua.
4. **Konten Pembelajaran & Bank Ujian**:
   * **ALTER LMS Management**:
     * Membuat Course/Kursus berjenjang.
     * Menambahkan Chapter/Bab dengan tautan video edukasi (YouTube/Vimeo/Internal).
     * Menyusun Interactive Problems/Latihan Soal per bab materi.
   * **English Mastery Bank**:
     * Manajemen 400 Kosakata (Vocab Bank) per modul pembelajaran (Modul 1-8).
     * Manajemen Teks Wacana (Reading Texts) lengkap dengan opsi impor Excel.
     * Manajemen Tema Storytelling naratif untuk target bulanan.
   * **Paket Tryout & Bank Soal CBT**:
     * Pembuatan paket Tryout SNBT (judul, batas waktu pengerjaan, kuota).
     * Pengaturan subtes standar (TPS, Penalaran Umum, Pengetahuan Kuantitatif, Pemahaman Bacaan, Literasi B. Indonesia, Literasi B. Inggris, Penalaran Matematika).
     * **CRUD Butir Soal Tryout**: Pengelolaan teks soal, 5 pilihan opsi (A, B, C, D, E), kunci jawaban benar, bobot nilai, dan penjelasan pembahasan mendalam.
     * **Fitur Bulk Import Soal Tryout**: Unggah ratusan soal sekaligus via file Excel.
5. **Layanan Konsultasi Terpisah (Mapel vs BK)**:
   * **Konsultasi Mapel**: Pengawasan slot tentoring akademik yang dibuat guru mapel.
   * **Konseling BK**: Pengaturan slot konseling terjadwal bersama Guru BK, persetujuan dan monitoring status sesi.
6. **WhatsApp Gateway**:
   * Pengawasan riwayat pengiriman pesan WhatsApp (`WaMessageLog`).
   * Pengujian manual kirim pesan (sandbox test) untuk verifikasi kesiapan API WhatsApp gateway.

### B. Alur Kerja (Workflow) Utama Admin:
* **Alur Pendaftaran & Aktivasi Siswa Baru**:
  1. Admin mengunduh template Excel atau membuka form tambah siswa.
  2. Admin mengunggah file data siswa (nama, kelas, NISN, WA orang tua).
  3. Sistem membuat *record* siswa, mengaitkan dengan kelas, men-generate *credentials* login siswa & orang tua, serta membuat token QR Code unik.
  4. Siswa dan orang tua menerima kredensial akun dan siap masuk ke portal.
* **Alur Penerbitan Tryout CBT**:
  1. Admin membuat paket Tryout baru (contoh: *Tryout Akbar SNBT Seri 1*).
  2. Admin menyusun subtes dan durasi pengerjaan.
  3. Admin mengunggah soal-soal menggunakan template Excel atau menginput manual.
  4. Paket tryout aktif dan otomatis muncul pada dashboard seluruh siswa sasaran.
* **Alur Presensi Meja Depan (Front Desk Check-in)**:
  1. Front Desk membuka menu `/admin/presensi/scan`.
  2. Siswa menunjukkan QR Code pada ponsel atau kartu pelajar.
  3. Scanner membaca data identitas siswa:
     * Jika tepat waktu: Tercatat **Hadir**.
     * Jika melewati jam toleransi: Tercatat **Terlambat**.
  4. Sistem mencatat log kehadiran dan mengirim notifikasi status kedatangan ke WhatsApp orang tua.

---

## 3. ROLE 2: GURU & MENTOR AKADEMIK

Guru di Altura memiliki **Dual-Role Architecture**: satu akun guru menjalankan dua fungsi secara terpadu tanpa perlu berganti akun.

### A. Fitur Guru Mata Pelajaran (Mapel):
1. **Portal Guru Mapel (`/guru/mapel`)**:
   * Menampilkan daftar mata pelajaran yang diampu dan kelas rombel yang diajar.
   * Jadwal mengajar mingguan dan materi ajar.
2. **Presensi Ruang Kelas**:
   * **Scanner Meja Guru**: Pemindaian QR siswa langsung di dalam ruang kelas saat memulai jam pelajaran.
   * **Log Sesi Presensi**: Melihat daftar hadir kelas secara real-time, dengan kontrol manual mengubah status kehadiran (Hadir, Izin, Sakit, Alpa).
3. **Bimbingan Tentoring / Konsultasi Mapel**:
   * Membuka slot konsultasi belajar (tanggal, jam mulai, jam selesai, kapasitas siswa, dan topik bahasan).
   * Melihat daftar siswa yang melakukan booking sesi tentoring.
   * Mengonfirmasi kehadiran dan memberikan asistensi akademik.

### B. Fitur Pembimbing Akademik / Mentor 360° (`/guru/pembimbing`):
1. **Monitoring Komprehensif Siswa Binaan (Mentee)**:
   * Setiap guru menjadi mentor untuk sekelompok siswa binaan tertentu.
   * Memantau daftar seluruh mentee dalam satu layar terpadu.
2. **Matriks Target Bulanan Siswa (Monthly Academic Quota)**:
   * **Target 400 Vocab CBT**: Pemantauan progres pengerjaan Modul 1 sampai Modul 8.
   * **Target 4 Teks Wacana Bulanan**: Rekap setoran membaca dan pemahaman teks bahasa Inggris siswa.
   * **Target 1 Storytelling Bulanan**: Pantauan penyelesaian narasi lisan siswa per bulan.
3. **Sistem Evaluasi Tanggal 20 & Monthly Submission Alert**:
   * Sistem otomatis memeriksa capaian bulanan siswa setiap tanggal 20.
   * Guru mentor dapat memicu tombol evaluasi (*Trigger Monthly Alerts*).
   * Siswa yang belum memenuhi target ditandai merah (*Alert Flag*).
   * Guru mentor melakukan pembinaan/pemanggilan, lalu menandai status tindak lanjut (*Resolve Alert*).
4. **Profil 360° Siswa Binaan (`/guru/bimbingan/siswa/{id}`)**:
   * Menampilkan rapor holistik siswa: riwayat kehadiran persentase, rekapitulasi skor tryout SNBT, histori konsultasi, dan catatan bimbingan khusus.
5. **Oral Evaluation Rubric (Penilaian Setoran Lisan English)**:
   * Formulir digital penilaian saat siswa menyetorkan wacana atau storytelling secara tatap muka / lisan.
   * Kriteria penilaian rubrik standar:
     * *Pronunciation* (Pengucapan & Intonasi)
     * *Fluency* (Kelancaran Berbicara)
     * *Vocabulary Usage* (Ketepatan Kosakata)
     * *Comprehension & Content* (Penguasaan Makna & Pemahaman Konteks)
   * Menyimpan skor evaluasi dan catatan masukan konstruktif bagi siswa.

### C. Alur Kerja (Workflow) Guru:
* **Alur Sesi Mengajar & Presensi Kelas**:
  1. Guru masuk kelas dan membuka menu *Scanner Meja* pada ponsel atau laptop guru.
  2. Siswa menunjukkan QR Code identitas mereka; guru memindai QR siswa.
  3. Siswa yang tidak membawa kartu dapat diabsen secara manual melalui *Log Sesi Presensi*.
* **Alur Penilaian Setoran Wacana / Oral**:
  1. Siswa yang telah mem-booking setoran datang menemui guru mentor.
  2. Guru membuka form *Oral Rubric* terkait booking siswa tersebut.
  3. Guru mendengarkan pelafalan wacana/storytelling siswa dan memberikan penilaian angka (skala rubrik) serta catatan pembinaan.
  4. Guru menyimpan nilai; status setoran siswa otomatis terverifikasi dan masuk ke akumulasi target bulanan siswa.
* **Alur Penanganan Alert Tanggal 20**:
  1. Pada tanggal 20 tiap bulan, guru membuka portal `/guru/pembimbing`.
  2. Guru mengecek siswa mentee yang memiliki tanda peringatan belum setor wacana/vocab.
  3. Guru memberikan bimbingan/pembinaan langsung kepada siswa.
  4. Setelah siswa menyelesaikan kewajibannya, guru mengklik tombol *Resolve* pada kotak alert.

---

## 4. ROLE 3: GURU BK / KONSELOR (BIMBINGAN KONSELING & EWS)

Guru BK (Kak Nisa) berfokus pada pendampingan psikologis, kenyamanan belajar, motivasi, penentuan jurusan PTN, serta intervensi dini terhadap kendala siswa.

### A. Fitur-Fitur Utama Guru BK:
1. **Dashboard & Matriks Konseling**:
   * Matriks siswa berisiko (*At-Risk Students*), ringkasan agenda konseling minggu ini, dan status penanganan masalah siswa.
2. **Early Warning System (EWS Flagging Box)**:
   * **Late Series Detection**: Otomatis mendeteksi siswa yang terlambat hadir 3 kali berturut-turut.
   * **Critical Absence Detection**: Otomatis mendeteksi siswa yang alpa 2 kali berturut-turut atau persentase kehadiran berada di bawah 75%.
   * **Academic Score Drop**: Mendeteksi penurunan performa tryout atau kegagalan berulang pada kuis kosakata.
   * **Tindakan Resolusi (Resolve Flag)**: Kolom tindak lanjut konselor untuk mencatat hasil dialog bersama siswa/wali dan menutup status flag setelah masalah tertangani.
3. **Rekapitulasi Akademik & Presensi**:
   * **Rekap Presensi Siswa**: Analisis komparatif tingkat absensi per kelas/rombel untuk mendeteksi tren penurunan kedisiplinan.
   * **Rekap Target Bulanan**: Tinjauan kepatuhan siswa sekolah dalam menuntaskan target belajar.
4. **Sesi Konsultasi BK Terpadu**:
   * Membuka jadwal slot konseling tatap muka / online.
   * Menerima permintaan janji temu bimbingan dari siswa maupun dari orang tua.
   * Pengelolaan status sesi (*Scheduled, In Progress, Completed, Cancelled*).
   * Dokumentasi catatan konseling privat dan rekomendasi studi lanjut.

### B. Alur Kerja (Workflow) Guru BK:
* **Alur Penanganan Early Warning System (EWS)**:
  1. Sistem *StudentFlaggingService* mendeteksi anomali siswa (misal: 3x terlambat hadir).
  2. Siswa secara otomatis muncul di *Kotak Flagging EWS* Guru BK dengan tanda status *Open*.
  3. Guru BK memanggil siswa untuk sesi konseling personal guna menggali penyebab keterlambatan/kendala belajar.
  4. Guru BK menginput catatan pembinaan pada sistem dan menekan tombol *Resolve*. Status bendera siswa berubah menjadi tertangani (*Resolved*).
* **Alur Konseling Pemilihan Jurusan Kuliah**:
  1. Siswa atau Orang Tua mengajukan jadwal temu bimbingan konseling melalui portal masing-masing.
  2. Guru BK menerima notifikasi jadwal dan menyetujui sesi.
  3. Pertemuan berlangsung dengan meninjau hasil grafik tryout SNBT siswa dari database Altura.
  4. Guru BK menyusun rekomendasi jurusan realistis berdasarkan pemetaan skor subtes siswa.

---

## 5. ROLE 4: SISWA (STUDENT)

Portal Siswa dirancang modern, responsif, dan interaktif guna memfasilitasi kegiatan belajar mandiri, persiapan ujian, dan pemantauan prestasi pribadi.

### A. Fitur-Fitur Utama Siswa:
1. **Dashboard Siswa**:
   * Ringkasan progres belajar: Kartu target 400 Vocab, capaian setoran wacana bulanan, jadwal kelas hari ini, dan skor rata-rata tryout SNBT terakhir.
2. **Presensi Digital Mandiri**:
   * **QR Identitas Siswa (`/siswa/presensi/qr`)**:
     * Menampilkan QR Code dinamis berisi identitas unik siswa.
     * Cukup ditunjukkan ke kamera scanner Front Desk saat datang atau ke scanner guru di kelas.
   * **Riwayat Kehadiran (`/siswa/presensi/riwayat`)**:
     * Rekapitulasi tanggal kedatangan, jam check-in, nama kelas, dan status (Hadir Tepat Waktu, Terlambat, Izin, Sakit, Alpa).
3. **Pembelajaran ALTER LMS (Auto-Enrollment)**:
   * Siswa otomatis terdaftar pada materi sesuai rombel kelasnya.
   * **ALTER Player (`/siswa/alter/ruang-belajar/{chapterId?}`)**: Pemutar video pembelajaran terintegrasi dengan materi pembahasan bab.
   * **Interactive Problems**: Mengerjakan latihan soal langsung di bawah video materi untuk menguji pemahaman konsep secara instan.
   * **Silabus Kurikulum**: Peta rute kurikulum materi dari semester awal hingga persiapan ujian akhir.
4. **Ekosistem English Mastery**:
   * **Vocab CBT (Bank 400 Kata)**:
     * Ujian kosakata bahasa Inggris online terbagi dalam Modul 1 s/d Modul 8 (masing-masing 50 kata).
     * Pilihan ganda interaktif, waktu hitung mundur, pengacakan soal, dan kalkulasi nilai otomatis seketika setelah submit.
   * **Vocab Essay Mode**:
     * Mode latihan menulis arti kata dan struktur kalimat secara esai untuk pendalaman daya ingat.
   * **Task Cards Storytelling**:
     * Kartu tugas naratif tematik bulanan berisi instruksi alur cerita bahasa Inggris yang wajib dipersiapkan siswa.
   * **Booking Setoran Wacana / Oral**:
     * Formulir pendaftaran jadwal setoran lisan teks wacana/storytelling kepada guru pembimbing.
5. **Evaluasi Tryout CBT SNBT**:
   * **Daftar Paket Tryout (`/siswa/tryout`)**: Katalog ujian tryout yang tersedia, riwayat tryout yang telah dikerjakan, dan batas waktu pelaksanaan.
   * **Tryout CBT Engine (`/siswa/tryout/{id}/take`)**:
     * Antarmuka ujian terstandarisasi UTBK/SNBT.
     * Navigasi grid nomor soal lengkap (menampilkan status: belum dijawab, sudah dijawab, ragu-ragu).
     * Timer countdown otomatis di sudut layar; apabila waktu habis, lembar jawaban otomatis tersimpan dan terkirim (*Auto-Submit*).
     * Pengacakan urutan butir soal dan variasi opsi jawaban per peserta.
   * **Review Hasil & Pembahasan Tryout (`/siswa/tryout/{id}`)**:
     * Tampilan skor total dan breakdown nilai tiap subtes (TPS, Penalaran Matematika, Literasi Bahasa).
     * Analisis butir soal: melihat jawaban siswa, kunci jawaban resmi, dan penjelasan/pembahasan lengkap per nomor.
     * **Download Rapor Tryout PDF**: Mengunduh lembar hasil ujian berformat PDF resmi dengan kop Altura.
6. **Layanan Konsultasi Akademik Mapel**:
   * Melihat ketersediaan jadwal slot tentoring yang dibuka guru mata pelajaran.
   * Melakukan booking sesi konsultasi materi sulit secara mandiri.
   * Fitur pembatalan booking jika ada kendala sebelum batas waktu sesi.
7. **Profil Akademik 360° Siswa (`/siswa/profil`)**:
   * Portofolio lengkap data pribadi, kelas, jurusan impian, dan perguruan tinggi target.
   * **Update NISN Mandiri**: Melengkapi nomor induk siswa nasional jika data awal belum terisi.
   * Akumulasi statistik belajar: Rata-rata presensi, total vocab yang dikuasai, dan histori grafik skor tryout.
   * **Download Rapor Perkembangan PDF**: Mengunduh berkas laporan kemajuan belajar holistik siswa.

### B. Alur Kerja (Workflow) Siswa:
* **Alur Hadir di Bimbel (Check-in Presensi)**:
  1. Siswa tiba di bimbel dan membuka menu *QR Identitas Siswa* pada smartphone.
  2. Menunjukkan layar QR Code ke scanner Front Desk.
  3. Bunyi bip konfirmasi berbunyi; status kehadiran siswa seketika tercatat di sistem.
* **Alur Mengikuti Tryout CBT**:
  1. Siswa membuka menu *Evaluasi Tryout SNBT*.
  2. Memilih paket tryout yang berstatus aktif dan menekan tombol *Mulai Ujian*.
  3. Siswa mengerjakan soal pada antarmuka CBT:
     * Menavigasi butir soal melalui daftar nomor.
     * Memilih jawaban A, B, C, D, atau E.
     * Menggunakan tombol *Ragu-Ragu* jika belum yakin.
  4. Timer menghitung mundur. Siswa menekan tombol *Kirim / Submit Jawaban* setelah selesai (atau submit otomatis jika waktu habis).
  5. Layar langsung menampilkan skor nilai, rincian per subtes, pembahasan butir soal, dan tautan unduh Rapor Tryout PDF.
* **Alur Setoran Wacana Bulanan**:
  1. Siswa mempelajari teks wacana pada modul English Mastery.
  2. Siswa membuka menu *Booking Setoran Wacana / Oral* dan memilih waktu temu dengan guru mentor.
  3. Siswa menemui guru mentor sesuai jadwal dan melafalkan wacana.
  4. Guru menilai via *Oral Rubric*; progres setoran siswa otomatis bertambah di dashboard.

---

## 6. ROLE 5: ORANG TUA / WALI MURID (PARENT MONITORING)

Portal Orang Tua dirancang khusus agar wali murid dapat mengawal perkembangan pendidikan ananda secara transparan dari rumah atau tempat kerja.

### A. Fitur-Fitur Utama Orang Tua:
1. **Dashboard Ananda**:
   * Ringkasan informasi utama ananda: nama kelas, persentase kehadiran bulan berjalan, nilai tryout terakhir, dan capaian target English Mastery.
2. **Rekapitulasi Perkembangan Anak**:
   * **Riwayat Presensi Ananda (`/ortu/rekap/presensi`)**:
     * Pantauan kehadiran harian, jam masuk kelas, catatan jika ananda terlambat, izin, atau tanpa keterangan.
   * **Hasil Belajar & Tryout (`/ortu/rekap/hasil`)**:
     * Grafik histori perkembangan nilai tryout ananda dari waktu ke waktu.
     * Pemetaan kekuatan dan kelemahan subtes (misal: kuat di Penalaran Umum, perlu pendampingan di Penalaran Matematika).
3. **Layanan Bimbingan & Konsultasi Wali Murid**:
   * **Booking Konseling BK (`/ortu/konsultasi/booking-bk`)**:
     * Mengajukan jadwal konsultasi khusus dengan Guru BK (Kak Nisa) perihal studi lanjut, peluang lolos PTN, atau kendala psikologis ananda.
4. **Kontak Tim Pembimbing (`/ortu/kontak-pembimbing`)**:
   * Informasi nama, foto profil, dan tombol WhatsApp langsung menuju Guru Pembimbing/Mentor yang mengasuh ananda di kelas.

### B. Alur Kerja (Workflow) Orang Tua:
* **Alur Pemantauan Harian**:
  1. Orang tua menerima notifikasi WhatsApp otomatis saat ananda memindai QR Code masuk bimbel.
  2. Orang tua dapat login ke portal `/ortu/dashboard` sewaktu-waktu untuk melihat detail grafik skor tryout dan kepatuhan target belajar ananda.
* **Alur Pengajuan Konsultasi Masa Depan Ananda**:
  1. Orang tua membuka menu *Booking Konsultasi BK*.
  2. Memilih preferensi tanggal dan jam temu serta mengisi topik diskusi (contoh: *Diskusi strategi pemilihan jurusan Kedokteran vs Teknik*).
  3. Guru BK mengonfirmasi jadwal; sesi konsultasi terlaksana secara tatap muka di bimbel atau via daring.

---

## 7. MESIN AUTOMASI & LAYANAN SISTEM (UNDER-THE-HOOD ENGINES)

Sistem ALTURA diperkuat oleh beberapa engine di latar belakang (services & jobs) yang bekerja secara otomatis:

1. **Smart Role Redirect Engine (`RedirectController`)**:
   * Mengatur arus navigasi setelah login satu pintu (`/login`).
   * Mengarahkan pengguna secara cerdas ke portal spesifik:
     * Admin/Front Desk $\rightarrow$ `/admin/dashboard`
     * Guru $\rightarrow$ `/guru/dashboard`
     * Guru BK $\rightarrow$ `/bk/dashboard`
     * Siswa $\rightarrow$ `/siswa/dashboard`
     * Orang Tua $\rightarrow$ `/ortu/dashboard`
2. **Attendance Calculator Service**:
   * Menghitung rasio kehadiran dinamis dari seluruh sesi wajib.
   * Menghasilkan indikator persentase kehadiran yang dikonsumsi oleh dashboard guru, profil 360°, dan portal orang tua.
3. **Student Flagging Service (Early Warning Engine)**:
   * **Trigger Otomatis 1**: 3 sesi keterlambatan beruntun $\rightarrow$ memicu flag `late_series`.
   * **Trigger Otomatis 2**: 2 sesi alpa beruntun atau kehadiran $< 75\%$ $\rightarrow$ memicu flag `critical_absence` dan mengirim alert ke nomor WhatsApp orang tua.
   * **Trigger Otomatis 3**: Penurunan skor tryout drastis $\rightarrow$ memicu alert evaluasi akademik.
4. **Monthly Target & Alert Evaluator (`MonthlySubmissionAlert`)**:
   * Pada tanggal 20 setiap bulan, engine memeriksa pemenuhan target bulanan siswa:
     * Pengerjaan Vocab CBT di bulan terkait.
     * Minimal 4 setoran wacana bahasa Inggris.
     * Minimal 1 tugas storytelling.
   * Jika ada target yang belum terpenuhi, sistem membuat *alert record* untuk guru mentor agar dilakukan pembinaan sebelum akhir bulan.
5. **CBT Engine (Computer-Based Test)**:
   * Pengacakan urutan soal dan opsi jawaban secara acak per attempt siswa.
   * Penyimpanan jawaban real-time (`TryoutAnswer`).
   * Timer sinkron klien-server dengan proteksi auto-submit saat waktu habis.
   * Kalkulasi skor otomatis per subtes dan penyimpanan riwayat pada tabel `tryout_scores` & `tryout_attempts`.
6. **WhatsApp Gateway Service (`WhatsAppGatewayService`)**:
   * Integrasi pengiriman pesan notifikasi otomatis untuk:
     * Presensi kehadiran & keterlambatan siswa.
     * Notifikasi early warning kepada orang tua.
     * Pengingat jadwal konsultasi tentoring & konseling BK.
   * Logging seluruh status pengiriman pesan ke tabel `wa_message_logs`.
7. **Official PDF Reporting Engine (`PdfReportService`)**:
   * Konversi rapor tryout dan lembar perkembangan akademik 360° siswa ke dokumen PDF siap cetak dengan layout rapi dan kop resmi bimbel.

---

## 8. MATRIKS RUTE & HAK AKSES URL

| Rute URL | Role Pengguna | Keterangan Fitur |
| :--- | :--- | :--- |
| `/login` | Public / Guest | Form login terpadu semua pengguna |
| `/dashboard` | Semua (Auth) | Smart redirector menuju dashboard role masing-masing |
| **PORTAL SISWA** | | |
| `/siswa/dashboard` | Siswa | Dashboard progres target belajar & informasi umum |
| `/siswa/presensi/qr` | Siswa | Tampilan QR Code identitas untuk presensi masuk |
| `/siswa/presensi/riwayat` | Siswa | Riwayat lengkap kehadiran dan jam check-in |
| `/siswa/alter/ruang-belajar` | Siswa | Ruang belajar ALTER LMS (pemutar video & soal) |
| `/siswa/alter/kurikulum` | Siswa | Silabus kurikulum materi bimbel |
| `/siswa/english/vocab-quiz` | Siswa | Ujian Vocab CBT (400 kata, Modul 1-8) |
| `/siswa/english/vocab-quiz/{module}` | Siswa | Lembar pengerjaan kuis kosakata per modul |
| `/siswa/english/vocab-essay` | Siswa | Latihan penulisan kosakata mode esai |
| `/siswa/english/booking-setoran` | Siswa | Booking jadwal setoran wacana / oral English |
| `/siswa/english/digital-tasks` | Siswa | Daftar Task Cards Storytelling bulanan |
| `/siswa/tryout` | Siswa | Katalog paket Tryout SNBT dan riwayat nilai |
| `/siswa/tryout/{id}/take` | Siswa | Lembar ujian Tryout CBT interaktif (timer, navigasi) |
| `/siswa/tryout/{id}` | Siswa | Review skor subtes, kunci jawaban, dan pembahasan |
| `/siswa/konsultasi` | Siswa | Jadwal & booking slot tentoring mapel guru |
| `/siswa/profil` | Siswa | Profil 360° siswa, riwayat belajar, dan update NISN |
| **PORTAL GURU** | | |
| `/guru/dashboard` | Guru | Dashboard jadwal hari ini dan ringkasan bimbingan |
| `/guru/mapel` | Guru | Portal Guru Mapel (jadwal mengajar, rombel kelas) |
| `/guru/pembimbing` | Guru | Portal Mentor 360° (monitoring mentee, target bulanan) |
| `/guru/pembimbing/alerts/trigger` | Guru | Trigger evaluasi capaian bulanan tanggal 20 |
| `/guru/pembimbing/alerts/{id}/resolve`| Guru | Resolusi/tutup status alert siswa mentee |
| `/guru/presensi/scan` | Guru | Scanner QR Meja Guru di ruang kelas |
| `/guru/presensi/log-sesi` | Guru | Log sesi presensi dan kontrol manual status hadir |
| `/guru/bimbingan/siswa/{id}` | Guru | Halaman Profil 360° siswa binaan (mentee) |
| `/guru/bimbingan/oral-rubric/{bookingId}` | Guru | Rubrik evaluasi penilaian setoran wacana/storytelling |
| `/guru/bimbingan/monitoring-vocab` | Guru | Monitoring kemajuan hafalan kosakata siswa mentee |
| `/guru/konsultasi` | Guru | Buka slot dan kelola jadwal tentoring mapel |
| **PORTAL GURU BK** | | |
| `/bk/dashboard` | Guru BK | Dashboard status psikologis & matriks konseling |
| `/bk/flagging` | Guru BK | Kotak Flagging Early Warning System (EWS) |
| `/bk/flagging/resolve/{id}` | Guru BK | Tindak lanjut dan resolusi kasus flagging |
| `/bk/rekap/presensi` | Guru BK | Rekapitulasi absensi siswa per kelas |
| `/bk/rekap/target-bulanan` | Guru BK | Pemantauan target bulanan seluruh siswa |
| `/bk/konsultasi/sesi` | Guru BK | Manajemen jadwal & sesi konseling tatap muka/online |
| **PORTAL ORANG TUA** | | |
| `/ortu/dashboard` | Orang Tua | Dashboard perkembangan ananda secara real-time |
| `/ortu/rekap/presensi` | Orang Tua | Rekapitulasi kehadiran harian dan jam check-in ananda |
| `/ortu/rekap/hasil-belajar` | Orang Tua | Histori grafik nilai tryout dan subtes ananda |
| `/ortu/konsultasi/booking-bk` | Orang Tua | Formulir booking sesi konseling dengan Guru BK |
| `/ortu/kontak-pembimbing` | Orang Tua | Direktori kontak WhatsApp guru mentor ananda |
| **PORTAL ADMIN** | | |
| `/admin/dashboard` | Admin | Dashboard metrik global dan statistik operasional |
| `/admin/jurusan` | Admin | Master data Jurusan Peminatan |
| `/admin/kelas` | Admin | Master data Kelas / Rombel |
| `/admin/mata-pelajaran` | Admin | Master data Mata Pelajaran |
| `/admin/guru` | Admin | Master data Akun Guru & Spesialisasi Mentor |
| `/admin/orang-tua` | Admin | Master data Orang Tua / Wali Murid |
| `/admin/siswa` | Admin | Master data Siswa terpadu |
| `/admin/siswa/export-csv` | Admin | Ekspor database siswa ke CSV |
| `/admin/siswa/import-excel` | Admin | Impor massal siswa baru via file Excel |
| `/admin/jadwal` | Admin | Manajemen Jadwal Pelajaran mingguan |
| `/admin/presensi/scan` | Admin, Front Desk | Scanner Meja Depan (Front Desk Check-in) |
| `/admin/alter` | Admin | Manajemen Kursus, Chapter Video & Soal LMS ALTER |
| `/admin/english` | Admin | Bank Kosakata (400 kata), Teks Wacana & Storytelling |
| `/admin/tryout` | Admin | Manajemen Paket Tryout SNBT dan Subtes |
| `/admin/tryouts/{id}/questions` | Admin | CRUD Butir Soal Tryout & Impor Excel Soal |
| `/admin/konsultasi-mapel` | Admin | Pengawasan layanan konsultasi mapel guru |
| `/admin/konseling-bk` | Admin | Pengawasan layanan konseling BK |
| `/admin/whatsapp` | Admin | Monitoring log pengiriman WA & pengujian gateway |
| **LAYANAN RAPOR & PDF** | | |
| `/reports/tryout/{id}/pdf` | Siswa, Ortu, Admin | Unduh dokumen Rapor Hasil Tryout SNBT (PDF) |
| `/reports/student/{id}/progress-pdf`| Siswa, Guru, Admin | Unduh Rapor Perkembangan Akademik Siswa 360° (PDF) |

---
*Dokumen ini disusun sebagai dokumentasi resmi fitur dan alur kerja aplikasi ALTURA.*
