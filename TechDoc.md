
### 📋 Rancangan Prompt: Pengembangan Backend & UI Dasar Issue Tracker

**Konteks Project:**
Bertindaklah sebagai Senior Laravel Developer. Saya sedang membuat web *Issue Tracking System* menggunakan Laravel 12 dan MySQL. Saat ini, fokus UTAMA adalah membuat struktur *Backend* (Database, Relasi, Controller, Middleware) dan Tampilan Dasar (Blade *polosan* tanpa *styling* berlebih).

**Penting:** Abaikan implementasi desain Tailwind CSS dan FontAwesome untuk saat ini. Siapkan saja *class skeleton* atau komentar penanda. Styling akan dilanjutkan pada *prompt* terpisah nanti.

Tolong buatkan kode dan panduan langkah demi langkah untuk sistem dengan spesifikasi berikut:

#### 1. Sistem Autentikasi & Role (Middleware)

Terdapat 3 Role User:

* **Admin:** Memiliki akses penuh, *dashboard* dengan statistik *live*, *group chat*, dan *individual chat*.
* **Worker:** Memiliki *dashboard* berisi tugas yang di-*assign* ke mereka, tidak bisa edit/hapus *issue*, punya akses *group chat* dan *individual chat*.
* **Client:** Memiliki *dashboard* berisi *issue* yang mereka buat. Hanya bisa edit/hapus *issue* **sebelum** statusnya di-*assign*. Akses hanya ke *individual chat*.

#### 2. Struktur Database & Model (*Issue*)

Buat *migration* dan *model* untuk `Issue` dengan kolom-kolom berikut:

* **Issue ID:** Unik (misal format: `ISS-001`).
* **Issue Type:** *Enum* (`Bug`, `Improve`, `Request`).
* **Status:** *Enum* (`Unassigned`, `Assigned`, `In Progress`, `Complete`).
* **Priority:** *Enum* (`Low`, `Medium`, `High`). *Catatan logika:* Hanya Admin yang bisa mengatur *priority*.
* **Subject:** *String* (Nama/Judul Issue).
* **Description:** *Text* (Deskripsi lengkap).
* **Assigned To:** *Foreign Id* (Relasi ke tabel `users`).
* **Attachments:** Mendukung multi-file (Foto, Link Repo, Folder .zip, .rar). Siapkan tabel terpisah `issue_attachments` jika perlu.

#### 3. Struktur Layout Dasar (Sidebar Navigation)

Buat struktur `layouts/app.blade.php` dengan navigasi *Sidebar* di sebelah kiri (bukan *header* atas). Urutan *sidebar*:

1. Dashboard
2. Task
3. History
4. `<hr>` (Garis pemisah)
5. Individual Chat
6. Group Chat *(Sembunyikan menu ini jika role = Client)*
7. `<hr>` (Garis pemisah untuk bagian paling bawah)
8. **Profil User:** Menampilkan Foto Profil dan Nama.
* Jika nama diklik: Muncul *dropdown* (Lihat Profil, Logout).
* Jika foto diklik: Langsung *redirect* ke halaman Profil.



#### 4. Logika Tampilan Halaman (Views)

* **Dashboard:** * Client/Worker: Tampilkan daftar/tabel *issue* milik mereka.
* Admin: Tampilkan statistik (siapkan *placeholder* untuk diagram) dan seluruh *issue*.
* Terdapat bagian *History* pekerjaan.


* **Task List (Tabel Issue):**
Buat struktur tabel HTML dari kiri ke kanan sebagai berikut:
1. **Issue Type**
2. **Issue ID**
3. **Subject**
4. **Assigned To:** Tampilkan foto profil & nama *worker*.
5. **Status:** (Siapkan penanda teks untuk warna: Abu-abu, Oranye, Kuning, Hijau).
6. **Priority:** (Siapkan penanda untuk icon panah: Bawah Hijau, Kanan Kuning, Atas Merah).
7. **Action Menu:** Posisi di kanan atas (titik tiga). Berisi: Detail, Edit, Hapus. Terapkan logika (Gate/Policy) di mana Admin bisa semua, Worker tidak bisa edit/hapus, Client bisa edit/hapus hanya jika status `Unassigned`.



Tolong mulai dengan memberikan **Migration, Model, dan Role/Middleware Setup** terlebih dahulu.