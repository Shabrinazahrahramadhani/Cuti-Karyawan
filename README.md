# Cuti-Karyawan

# **Sistem Manajemen Cuti Karyawan**

Aplikasi berbasis web untuk digitalisasi proses pengajuan, verifikasi, dan persetujuan cuti karyawan secara efektif, transparan, dan terstruktur. Sistem memiliki 4 level pengguna dengan alur persetujuan berlapis sesuai struktur organisasi perusahaan.


---

## **🎯 Tujuan Sistem**

1. Mempermudah proses pengajuan cuti
2. Mengurangi proses manual/verifikasi via chat
3. Transparansi status persetujuan
4. HRD memiliki kontrol penuh dan laporan menyeluruh
5. Otomatisasi penghitungan hari kerja dan kuota cuti
6. Mengelola struktur organisasi (divisi dan ketua divisi)

---

## **👥 Level Pengguna**

### **1. Admin**

* Manajemen data pengguna
* Pengaturan divisi
* Konfigurasi kuota cuti
* Monitoring seluruh pengajuan cuti
* Melihat laporan data cuti

### **2. User (Karyawan)**

* Mengajukan Cuti Tahunan & Cuti Sakit
* Melihat sisa kuota cuti
* Membatalkan pengajuan berstatus Pending
* Melihat history pengajuan pribadi

### **3. Ketua Divisi**

* Approver pertama pengajuan bawahannya
* Menyetujui atau menolak cuti
* Menambahkan catatan penolakan
* Mengajukan cuti pribadi langsung ke HRD

### **4. HRD**

* Approval final semua pengajuan
* Approve/Reject banyak data sekaligus (bulk)
* Membuat catatan penolakan
* Menerbitkan laporan cuti

---

## **📝 Alur Pengajuan Cuti**

### **Alur 1: User → Leader → HRD**

1. User Mengajukan Cuti
2. Leader melakukan Approve/Reject
3. Jika approve → diteruskan ke HRD
4. HRD memberikan persetujuan final
   **Status:** `Pending → Approved by Leader → Approved/Rejected`

### **Alur 2: Leader → HRD**

1. Leader mengajukan cuti pribadi
2. HRD memberikan persetujuan final
   **Status:** `Pending → Approved/Rejected`

---

## **📄 Jenis Cuti**

### **1. Cuti Tahunan**

* Kuota: **12 hari kerja / tahun**
* Sabtu & Minggu tidak dihitung
* Tidak memerlukan lampiran
* Pengajuan minimal **H-3**
* Kuota terpakai, tetapi **dikembalikan** jika:

  * Dibatalan oleh User
  * Rejected Leader/HRD

### **2. Cuti Sakit**

* Wajib melampirkan **Surat Dokter**
* Pengajuan **H-0 s/d H+3**
* Tidak mengurangi kuota tahunan
* Total hari cuti dihitung otomatis

---

## **🗄 Struktur Database Utama**

### **Tables**

* users
* user_profiles
* divisions
* leave_requests

### **Relasi**

* User **hasOne** UserProfile
* UserProfile **belongsTo** Division
* Division **hasMany** UserProfile
* LeaveRequest **belongsTo** User
* Division **belongsTo** Leader (User)

---

## **📦 Modules & Fitur CMS**

### **1. Manajemen Pengguna (Admin)**

**Fitur Utama:**

* List Users
  Filter: Role, Divisi, Status Aktif, Masa Kerja
  Sortir: Nama, Tanggal Bergabung, Divisi
* Create User
* Edit User
* Delete User (role: User & Leader)
* Toggle Active/Inactive
* Default kuota: **12 hari**

---

### **2. Manajemen Divisi (Admin)**

**Fitur:**

* List Divisi + jumlah anggota
* Filter dan Sorting Divisi
* Create / Edit / Delete Divisi
* Assign Ketua Divisi (dropdown)
* Members Page

---

### **3. Manajemen Anggota Divisi**

**Add Member:**

* Hanya untuk role **User**
* User belum terikat divisi

**Remove Member:**

* Lepas dari divisi tanpa hapus akun

---

## **4. Pengajuan Cuti**

### **Fitur Form:**

* Jenis Cuti (Tahunan/Sakit)
* Tanggal Mulai–Selesai
* Total hari cuti otomatis
* Upload surat dokter (pdf/jpg/png)
* Nomor darurat
* Alamat selama cuti

### **Validasi:**

* Tidak overlap cuti approved
* Hari kerja dihitung otomatis
* Minimal H-3 cuti tahunan
* Cuti sakit wajib lampiran dokter

### **Pembatalan:**

* Hanya jika status Pending
* Alasan wajib
* Kuota dikembalikan otomatis

---

## **5. Verifikasi Leader**

* Melihat list pengajuan bawahan
* Approve/Reject melalui modal
* Catatan penolakan disimpan
* Status berubah menjadi:

  * `Approved by Leader`
  * `Rejected by Leader`

---

## **6. Verifikasi HRD**

### **Fitur:**

* Final Approval
* Approve / Reject
* Catatan wajib jika Reject
* `Approver Log`
* Bulk Approval (checkbox multi select)

---

## **📊 Dashboard & UI**

### **1. Admin**

* Total Karyawan aktif/nonaktif
* Total Divisi
* Pengajuan cuti bulan ini
* Pending approval
* Masa kerja < 1 tahun

### **2. User**

* Sisa kuota cuti
* Total pengajuan
* Jumlah cuti sakit
* Informasi Divisi & Ketua

### **3. Leader**

* Pengajuan masuk
* Pending verifikasi
* Anggota divisi
* Sedang cuti minggu ini

### **4. HRD**

* Total pengajuan bulan ini
* Pending final approval
* Sedang cuti bulan ini
* Daftar Divisi

---

## **👩‍💼 Profil Pengguna**

Tampilkan:

* Foto, nama lengkap
* Email, username
* Nomor telepon, alamat
* Role
* Divisi
* Sisa kuota cuti

User dapat:

* Update foto
* Update nomor telepon & alamat
* Ganti password

Admin dapat:

* Edit semua informasi user

---

## **⏱ Perhitungan Hari Kerja**

Hanya menghitung:

* Senin–Jumat
  Tidak menghitung:
* Sabtu & Minggu
* Hari libur nasional (optional)

Fungsi custom menggunakan **Carbon**.

---

## **🔐 Authentication & Authorization**

* Login sistem terproteksi middleware
* Role-based access
* Hanya admin bisa manage users/division
* Hanya leader process bawahan sendiri
* Hanya HRD final approve

---

## **🗂 Laporan**

Menu laporan khusus HRD/Admin:

* Rejected
* Cancelled
* Pending > 7 hari
* Export (opsional)

Filter laporan:

* Jenis cuti
* Status
* Tanggal pengajuan
* Periode cuti

---

## **🚀 Cara Menjalankan**

```bash
git clone <repo>
cd project
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

---

## **💾 Seeder (Opsional)**

Seeder untuk contoh data:

* Admin
* HRD
* Leader
* Users
* Divisions
* LeaveRequests dummy

---

## **✔ Status Workflow**

| Status Code        | Keterangan                   |
| ------------------ | ---------------------------- |
| Pending            | Menunggu approval leader/HRD |
| Approved by Leader | Leader menyetujui            |
| Approved           | HRD final approve            |
| Rejected           | Ditolak Leader/HRD           |
| Cancelled          | Dibatalkan oleh user         |

---




