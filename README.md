<p align="center">
  <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?auto=format&fit=crop&w=1200&q=80" width="100%" style="border-radius: 16px;" alt="Absolute Store Banner">
</p>

<h1 align="center">⚡ Absolute Store - Global Digital Top-Up & PPOB Ecosystem</h1>

<p align="center">
  Platform E-Commerce Produk Digital & PPOB kelas dunia dengan estetika modern, otomasi H2H Digiflazz, payment gateway Tripay, notifikasi WhatsApp real-time, arsitektur Double-Entry Wallet Ledger, dan sistem multi-bahasa reaktif (ID / EN).
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel">
  <img src="https://img.shields.io/badge/Livewire-3-4E56A6?style=for-the-badge&logo=livewire&logoColor=white" alt="Livewire">
  <img src="https://img.shields.io/badge/Tailwind_CSS-3-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/Alpine.js-3-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=black" alt="Alpine.js">
  <img src="https://img.shields.io/badge/Redis-Cache_%26_Lock-DC382D?style=for-the-badge&logo=redis&logoColor=white" alt="Redis">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📌 Arsitektur Sistem & Gambaran Umum

**Absolute Store** menggabungkan kecepatan render server-side Laravel dengan reaktivitas SPA dari Livewire 3 dan Alpine.js. Platform ini dirancang untuk menangani transaksi volume tinggi dengan keandalan maksimal (*zero-loss, zero-duplicate orders*).

### Pilar Inti Ekosistem:
1. **Multi-Language Engine (ID / EN)**: Dual-layer localization yang menggabungkan file JSON standar Laravel dengan Alpine.js reactive global store untuk perpindahan bahasa instan tanpa refresh halaman.
2. **Unified Profile Flyout**: Card identitas pengguna yang sinkron langsung dengan basis data (`users`, saldo dompet real-time, role, dan tier membership) di Storefront maupun Admin Console.
3. **Double-Entry Wallet Ledger**: Setiap mutasi saldo akun (deposit, pemotongan order, refund) dicatat secara atomik di tabel `wallet_transactions` dengan saldo sebelum dan sesudah (`balance_before`, `balance_after`) menggunakan transaksi database pesimistik (`lockForUpdate`).
4. **H2H Fulfillment Otomatis (Digiflazz API)**: Pengiriman item game, pulsa, atau token listrik PLN diproses 1-3 detik setelah pembayaran terkonfirmasi lunas.
5. **Security Hardening & Idempotency**: Webhook Tripay dan Digiflazz dilindungi tanda tangan kriptografi HMAC SHA256 serta Redis Distributed Lock (`Cache::lock`) untuk menangkal serangan *race condition*.

---

## 🚀 Fitur Unggulan

### 1. Storefront & Checkout (Etalase Konsumen)
- **Glassmorphism Design System**: Tampilan modern Obsidian Dark (`#080C14`) dengan aksen Silicon Violet & Electric Cyan.
- **Card Produk Interaktif**: Efek 3D hover lift, ambient glow, thumbnail zoom halus, status indicator berdenyut, dan tombol "Beli Cepat".
- **Dynamic Language Switcher**: Switcher 🇮🇩 ID / 🇺🇸 EN di navbar dengan persistensi sesi & cookie.
- **Form Pemesanan Cerdas**: Deteksi tipe input tujuan (`id_only`, `id_and_zone`, `phone_number`, `meter_number`), verifikasi nickname pemain, dan pemilihan saluran pembayaran dinamis.
- **Dukungan Pembayaran Ganda**:
  - *Direct Gateway*: QRIS & Virtual Account via Tripay.
  - *Internal Wallet*: Pembayaran instan 1-klik menggunakan saldo akun member tanpa biaya admin tambahan.

### 2. Member Portal (User Dashboard)
- **Quick Wallet Card**: Kartu saldo neon dengan rincian tier dan modal instan deposit saldo.
- **1-Click Copy SN / Voucher**: Salin kode serial number transaksi dengan toast feedback interaktif.
- **Saved Game Accounts**: Daftar akun game favorit untuk mempercepat proses checkout.
- **Pelacak Mutasi Deposit**: Catatan status dan nomor transaksi deposit real-time.

### 3. Command Center (Admin Console)
- **Monitoring Metrik Finansial**: Omset kotor, laba bersih, status pesanan, dan saldo provider Digiflazz.
- **Manajemen Katalog & Master SKU**: Kontrol harga beli, harga jual publik, dan harga tier reseller.
- **Financial Ledger & Dispute Center**: Log audit seluruh mutasi kas dan antarmuka penanganan komplain CS.
- **Sistem Audit Keamanan**: Rekap jejak aktivitas operasional pengguna dan administrator.

---

## 🛠️ Stack Teknologi

| Komponen | Spesifikasi / Teknologi |
| :--- | :--- |
| **Backend Framework** | Laravel 11 (PHP 8.4+) |
| **Reactivity Layer** | Livewire 3 & Alpine.js 3 |
| **Styling & Design** | Tailwind CSS 3, Glassmorphism, Plus Jakarta Sans |
| **Database** | MySQL 8.0 / SQLite (Testing) |
| **Cache & Queue** | Redis 7 |
| **Aggregator PPOB** | Digiflazz API (H2H) |
| **Payment Gateway** | Tripay Payment Gateway (QRIS, VA Bank, E-Wallet) |
| **Notifikasi Instan** | WhatsApp Gateway (Fonnte API) |
| **Web Server** | Nginx & PHP-FPM / Supervisor Daemon |

---

## ⚙️ Panduan Instalasi Lokal & Docker

### 1. Prasyarat Sistem
- Docker & Docker Compose **ATAU** PHP 8.4+, Composer, Node.js 20+, MySQL, Redis.

### 2. Menjalankan via Docker Compose (Rekomendasi)
```bash
# 1. Clone repositori
git clone https://github.com/mhmdhfisali/absolute-store.git
cd absolute-store

# 2. Salin environment
cp .env.example .env

# 3. Jalankan container
docker compose up -d --build

# 4. Install dependencies di dalam container
docker exec absolute_store_app composer install
docker exec absolute_store_app php artisan key:generate
docker exec absolute_store_app php artisan migrate --seed
docker exec absolute_store_app php artisan storage:link

# 5. Build asset frontend
npm install && npm run build
```

---

## 🧪 Eksekusi Testing Otomatis

Platform ini dilengkapi pengujian fitur dan unit komprehensif menggunakan PHPUnit:
```bash
# Menjalankan seluruh test suite
docker exec absolute_store_app php artisan test --compact

# Menguji idempotency dan webhook
docker exec absolute_store_app php artisan test --filter=IdempotencyAndWebhookTest
```

---

## 📜 Lisensi
Dikembangkan oleh **Absolute Store Engineering Team**. Hak Cipta Dilindungi Undang-Undang.
