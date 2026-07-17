# Workroute

Workroute adalah aplikasi manajemen proyek dan pelacakan tugas (*issue tracking*) berbasis Laravel yang dirancang untuk mempermudah alur kerja dan komunikasi tim.

## 🚀 Fitur Utama

Berdasarkan struktur aplikasi ini, Workroute menyediakan berbagai fitur untuk mendukung kolaborasi:

*   **Manajemen Pengguna & Autentikasi:** Dilengkapi dengan sistem *login*, registrasi, manajemen profil, dan pembatasan akses menggunakan sistem *role*.
*   **Pelacakan Issue (Issue Tracking):** Pengguna dapat membuat tugas, menetapkan *deadline*, menambahkan lampiran (*attachment*), dan memantau riwayat perubahan pada setiap *issue*.
*   **Sistem Chat Terintegrasi:** Mendukung komunikasi *real-time* atau asinkron antar pengguna. Terdapat fitur obrolan personal (*individual chat*) maupun pembuatan obrolan grup (*group chat*).
*   **Dashboard Interaktif:** Menyediakan tampilan beranda yang berbeda untuk pengunjung tamu (*guest*) dan pengguna yang telah terautentikasi.
*   **Manajemen Tugas:** Halaman antarmuka yang lengkap untuk membuat, mengedit, melihat detail, dan melacak riwayat tugas[cite: 1].

## 🛠️ Teknologi yang Digunakan

*   **Framework:** [Laravel](https://laravel.com)[cite: 1]
*   **Database:** Mendukung database relasional standar (MySQL/SQLite) yang diatur melalui *schema migrations*[cite: 1].
*   **Frontend:** Dibangun menggunakan Blade templating engine, CSS, dan JavaScript yang di- *bundle* menggunakan Vite[cite: 1].

## ⚙️ Cara Instalasi

Ikuti langkah-langkah berikut untuk menjalankan proyek Workroute di lingkungan lokalmu:

1. **Kloning Repositori**
   ```bash
   git clone [https://github.com/username/workroute.git](https://github.com/username/workroute.git)
   cd workroute-main
