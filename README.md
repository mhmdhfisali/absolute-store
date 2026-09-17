<p align="center">
  <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200&q=80" width="100%" style="border-radius: 16px;" alt="Absolute Store Banner">
</p>

<h1 align="center">⚡ Absolute Store - Digital Top-Up & PPOB Platform</h1>

<p align="center">
  Platform e-commerce layanan digital modern untuk top-up game online, paket data, token listrik PLN, dan voucher digital dengan otomasi pemrosesan real-time, integrasi payment gateway, dan notifikasi instan WhatsApp.
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Livewire-4E56A6?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=black" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Docker-2496ED?style=for-the-badge&logo=docker&logoColor=white" alt="Docker">
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📌 Gambaran Umum (Overview)

**Absolute Store** dibangun dengan arsitektur monolitik modern menggunakan **Laravel 12**, **Livewire 3**, dan **Tailwind CSS**. Sistem dirancang untuk menangani alur transaksi otomatis dari pemesanan storefront, verifikasi pembayaran multi-channel, pemenuhan pesanan ke provider agregator (**Digiflazz H2H**), hingga pengiriman invoice dan kode serial/token via **WhatsApp Gateway (Fonnte)** secara _end-to-end_.

---

## 🚀 Fitur Utama

### 1. Etalase & Pemesanan Publik (Storefront)

- **Katalog Terstruktur**: Pengelompokan kategori otomatis (_Game Populer_, _Pulsa & Data_, _Token PLN_, _E-Wallet_, _Streaming_, _PC & Console_).
- **Formulir Checkout Interaktif**: Dibangun dengan **Livewire 3** untuk pemilihan item nominal, kalkulasi biaya admin dinamis (flat + persentase), dan deteksi tipe input form (`id_and_zone`, `id_only`, `phone_number`, `meter_number`).
- **Promosi Dinamis**: Carousel banner promo slider otomatis dan _running announcement bar_ (marquee) yang dapat dikelola langsung dari panel admin.
- **Halaman Invoice & Pelacakan**: Halaman transaksi interaktif dengan batas waktu hitung mundur, instruksi QRIS/Virtual Account, dan fitur pencarian pelacakan pesanan publik (`/tracking`).

### 2. Otomasi & Integrasi Provider

- **Tripay Payment Gateway**: Penerimaan pembayaran melalui QRIS dan Virtual Account otomatis via HMAC SHA256 Webhook.
- **Digiflazz Fulfillment API (H2H)**: Pemrosesan instan serial number (SN) atau token PLN segera setelah status pembayaran terverifikasi lunas (`PAID`).
- **WhatsApp Notification Engine (Fonnte)**:
    - Notifikasi rincian tagihan invoice baru ke pembeli.
    - Notifikasi bukti pembayaran sukses beserta kode SN / Token.
    - Notifikasi kegagalan pesanan ke pembeli jika terjadi kendala provider.
    - _Emergency Alert_ ke WhatsApp Admin jika saldo deposit Digiflazz berada di bawah batas aman (< Rp 100.000).

### 3. Panel Manajemen & Monitoring (Admin Console)

- **Metrik Finansial & Operasional**: Monitoring total omset, mutasi status pesanan (`paid`, `unpaid`, `expired`, `failed`), dan widget **Saldo Deposit Digiflazz** real-time dengan tombol sinkronisasi AJAX (tanpa reload halaman).
- **Katalog & Margin Keuntungan**: Manajemen SKU produk, harga modal, harga jual, dan upload media gambar (logo thumbnail & banner) langsung ke disk storage lokal Laravel.
- **Konfigurasi Payment Gateway**: Penyesuaian fleksibel untuk biaya admin flat, persentase fee, dan toggle aktif/nonaktif saluran pembayaran.
- **Export Laporan Transaksi**: Streaming ekspor data mutasi transaksi ke format CSV hemat memori.
- **Scheduler Otomatis**: Background cron job untuk pembatalan invoice kedaluwarsa berkala dan pengecekan saldo deposit per jam.

---

## 🛠️ Tech Stack & Ekosistem

| Lapisan / Komponen            | Teknologi                                       |
| :---------------------------- | :---------------------------------------------- |
| **Backend Framework**         | Laravel 12 / PHP 8.4                            |
| **Reaktivitas UI**            | Laravel Livewire v3 & Alpine.js                 |
| **Styling & Theme**           | Tailwind CSS (Dark Mode Slate / Indigo Palette) |
| **Autentikasi Admin**         | Laravel Jetstream & Laravel Sanctum             |
| **Database**                  | MySQL 8.0                                       |
| **Infrastruktur / Kontainer** | Docker & Docker Compose                         |
| **Payment Gateway**           | Tripay API                                      |
| **Aggregator Provider**       | Digiflazz API                                   |
| **Notifikasi Gateway**        | Fonnte WhatsApp API                             |

---

## ⚙️ Panduan Instalasi & Menjalankan (Docker Environment)

### 1. Kloning Repositori

```bash
git clone [https://github.com/mhmdhfisali/absolute-store.git](https://github.com/mhmdhfisali/absolute-store.git)
cd absolute-store

```

### 2. Konfigurasi Environment File

Salin template konfigurasi dan sesuaikan nilai variabel di `.env`:

```bash
cp .env.example .env

```

Pastikan variabel utama telah terisi:

```env
APP_NAME="Absolute Store"
APP_URL=http://localhost:8005

DB_CONNECTION=mysql
DB_HOST=as-mysql
DB_PORT=3306
DB_DATABASE=absolute_store
DB_USERNAME=root
DB_PASSWORD=root

# WhatsApp Notification Gateway (Fonnte)
FONNTE_TOKEN=your_fonnte_device_token
STORE_ADMIN_WHATSAPP=085945720329

# Payment Gateway (Tripay)
TRIPAY_API_KEY=your_tripay_api_key
TRIPAY_PRIVATE_KEY=your_tripay_private_key
TRIPAY_MERCHANT_CODE=your_tripay_merchant_code
TRIPAY_MODE=sandbox

# Provider H2H (Digiflazz)
DIGIFLAZZ_USERNAME=your_digiflazz_username
DIGIFLAZZ_API_KEY=your_digiflazz_api_key
DIGIFLAZZ_MODE=development
DIGIFLAZZ_WEBHOOK_SECRET=your_webhook_secret

```

### 3. Jalankan Kontainer Docker

```bash
docker compose up -d --build

```

### 4. Instalasi Dependency & Inisialisasi Database

Jalankan dependensi, migrasi, dan data katalog awal dari dalam container aplikasi:

```bash
# Instalasi vendor PHP
docker exec -it absolute_store_app composer install

# Generate Application Key
docker exec -it absolute_store_app php artisan key:generate

# Migrasi Database & Seeder Katalog Lengkap
docker exec -it absolute_store_app php artisan migrate --seed

# Hubungkan Symlink Penyimpanan Media
docker exec -it absolute_store_app php artisan storage:link

# Compile Asset Frontend
npm install
npm run build

```

### 5. Akses Layanan Lokal

- **Storefront Publik**: `http://localhost:8005`
- **Admin Dashboard**: `http://localhost:8005/admin/dashboard`
- **Email**: `admin@absolutestore.id`
- **Password**: `password`

- **Database Management (phpMyAdmin)**: `http://localhost:8085`

---

## 🧪 Pengujian & Perintah Artisan Bawaan

Sistem dilengkapi beberapa artisan command kustom untuk verifikasi operasional:

```bash
# Uji coba koneksi gateway WhatsApp ke nomor tujuan
docker exec -it absolute_store_app php artisan wa:test 081234567890

# Simulasi pengiriman peringatan saldo darurat ke nomor WhatsApp Admin
docker exec -it absolute_store_app php artisan wa:test-low-balance 45000

# Eksekusi penjadwalan berkala (Scheduler Worker)
docker exec -it absolute_store_app php artisan schedule:work

```

---

## 📄 Lisensi

Proyek ini didistribusikan di bawah lisensi terbuka [MIT License](https://www.google.com/search?q=LICENSE).

````

Ganti seluruh isi file `README.md` lokal Anda dengan teks di atas, lalu lakukan commit dan push ke GitHub:

```bash
git add README.md
git commit -m "docs: revamp README with complete architecture, features, and setup guide"
git push origin main

````
