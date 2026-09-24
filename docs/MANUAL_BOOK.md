# 📖 Buku Panduan Operasional Sistem (Standard Operating Procedures)
## Absolute Store - E-Commerce Digital Goods & PPOB Platform

Dokumen ini disusun khusus sebagai panduan operasional harian bagi **Super Admin**, **Bagian Keuangan (Finance)**, dan **Customer Support (CS)** dalam mengelola platform Absolute Store.

---

## DAFTAR ISI
1. [Struktur Akun & Tingkatan Role (RBAC)](#1-struktur-akun--tingkatan-role-rbac)
2. [Alur Transaksi & Validasi Pembayaran](#2-alur-transaksi--validasi-pembayaran)
3. [Standar Operasional Prosedur (SOP) Customer Support](#3-standar-operasional-prosedur-sop-customer-support)
4. [SOP Penanganan Kendala Pesanan & Retry Transaksi](#4-sop-penanganan-kendala-pesanan--retry-transaksi)
5. [SOP Pengembalian Dana (Refund) ke Saldo Dompet](#5-sop-pengembalian-dana-refund-ke-saldo-dompet)
6. [Manajemen Saldo Deposit Digiflazz](#6-manajemen-saldo-deposit-digiflazz)
7. [Manajemen Master SKU & Sinkronisasi Harga](#7-manajemen-master-sku--sinkronisasi-harga)
8. [Audit Keamanan & Penanganan Insiden](#8-audit-keamanan--penanganan-insiden)

---

## 1. Struktur Akun & Tingkatan Role (RBAC)

Platform memisahkan hak akses menjadi 3 tingkatan utama:

| Role | Batas Akses & Wewenang |
| :--- | :--- |
| **Member (User)** | Melakukan top-up, melihat riwayat transaksi sendiri, top-up saldo deposit, dan menyimpan akun game favorit. |
| **Admin** | Mengakses Command Center, mengelola katalog produk, memantau riwayat transaksi, melakukan retry order yang tertunda, dan meninjau tiket CS. |
| **Super Admin** | Memiliki kontrol penuh termasuk manajemen role user, konfigurasi API gateway (Tripay, Digiflazz, Fonnte), audit log keamanan, dan penyesuaian saldo langsung. |

---

## 2. Alur Transaksi & Validasi Pembayaran

Setiap transaksi pada platform berjalan melalui mesin status otomatis:

```
[Storefront Checkout]
       │
       ▼
[Menunggu Pembayaran (unpaid)]
       │
       ├─ (Kedaluwarsa > 3 Jam) ──────────► [Expired]
       │
       ├─ (Dikonfirmasi Webhook Tripay)
       ▼
[Pembayaran Lunas (paid)]
       │
       ▼
[Fulfillment Digiflazz API]
       │
       ├─ Sukses Provider ────────► [Delivery: success] (Kirim SN via WhatsApp)
       │
       ├─ Pending Provider ───────► [Delivery: processing] (Scheduler polling)
       │
       └─ Ditolak / Gangguan ─────► [Delivery: failed] (Alert ke Dashboard Admin)
```

---

## 3. Standar Operasional Prosedur (SOP) Customer Support

### A. Verifikasi Identitas Pembeli
Ketika pelanggan menghubungi CS terkait pesanan yang belum masuk:
1. Minta **Nomor Invoice** (contoh: `INV-20260918-XXXX` atau `INV-BAL-XXXX`).
2. Buka menu **Customer Service & Dispute Panel** pada panel admin.
3. Cari invoice pada kolom pencarian cepat.
4. Pastikan status pembayaran bernilai **LUNAS (paid)**. Jika status masih `unpaid`, minta bukti mutasi transfer dan cocokkan dengan referensi Tripay.

### B. Membaca Respon Serial Number (SN)
- Jika SN tertera `AS-SN-XXXX` atau kode voucher provider: berarti order telah sukses terkirim dari sisi server.
- Jika SN kosong dan status adalah `failed`: lakukan langkah pada SOP Bagian 4.

---

## 4. SOP Penanganan Kendala Pesanan & Retry Transaksi

Jika status pengiriman `delivery_status = failed` (misalnya karena nomor tujuan salah, server game maintenance, atau provider kehabisan stok):

1. Masuk ke **Dashboard Monitoring Admin**.
2. Klik tombol **Filter: Gagal / Butuh Penanganan**.
3. Temukan baris transaksi yang bermasalah.
4. Klik tombol **"Proses Ulang (Retry Transaksi)"**:
   - Sistem akan mengunci order dan mencoba menembakkan ulang permintaan ke API Digiflazz secara aman.
   - Jika berhasil, nomor SN akan otomatis terisi dan pembeli menerima pesan WhatsApp sukses.
   - Jika tetap ditolak oleh provider, lanjutkan ke **SOP Refund**.

---

## 5. SOP Pengembalian Dana (Refund) ke Saldo Dompet

Kebijakan refund resmi platform Absolute Store adalah **pengembalian 100% dana ke Saldo Wallet Akun Member**:

1. Pastikan pengguna telah terdaftar dan memiliki akun di platform.
2. Super Admin membuka menu **Pelanggan & User RBAC** (`/admin/users`).
3. Cari akun email pelanggan yang bersangkutan.
4. Klik **Edit Akun & Saldo**.
5. Tambahkan nominal pesanan yang gagal ke kolom `balance`.
6. Simpan perubahan. Sistem otomatis mencatat log audit ke tabel `audit_logs` dan `wallet_transactions` dengan keterangan refund transaksi.
7. Hubungi pelanggan via WhatsApp bahwa saldo telah berhasil dikembalikan ke akun mereka untuk digunakan bertransaksi kembali.

---

## 6. Manajemen Saldo Deposit Digiflazz

Kelangsungan transaksi PPOB bergantung pada ketersediaan saldo di akun Digiflazz:
- **Batas Minimum Aman**: **Rp 500.000**.
- **Ambang Batas Kritis**: **Rp 100.000** (Sistem akan mengirim peringatan darurat otomatis ke WhatsApp Super Admin).
- **Prosedur Top-Up Saldo**:
  1. Transfer deposit ke rekening bank resmi PT Digiflazz Interkoneksi Indonesia melalui tiket deposit di portal Digiflazz.
  2. Setelah deposit masuk, buka menu **Dashboard Admin** Absolute Store.
  3. Perhatikan widget **Saldo Supplier Digiflazz** di pojok kanan atas, lalu klik ikon panah melingkar untuk menyinkronkan saldo terbaru.

---

## 7. Manajemen Master SKU & Sinkronisasi Harga

1. Buka menu **Master SKU** (`/admin/sku-management`).
2. Untuk menyesuaikan margin keuntungan massal:
   - Masukkan nominal margin flat (misal: Rp 1.500) atau persentase (misal: 2%).
   - Klik **"Sinkronisasi Daftar Harga Digiflazz"**.
3. Untuk mengubah harga produk spesifik (misal harga promo reseller):
   - Klik tombol **Edit** pada item SKU yang bersangkutan.
   - Perbarui kolom `selling_price` (harga umum) atau `reseller_price` (harga khusus member reseller).
   - Klik **Simpan**.

---

## 8. Audit Keamanan & Penanganan Insiden

1. Menu **Audit Logs Keamanan** (`/admin/audit-logs-viewer`) mencatat setiap aksi administratif:
   - Perubahan harga SKU.
   - Pemrosesan ulang (retry) transaksi.
   - Perubahan role atau saldo user.
   - Ekspor laporan CSV transaksi.
2. Jika ditemukan indikasi kecurangan atau *suspicious activity* (misalnya lonjakan request dengan IP asing):
   - Catat alamat IP dari log audit.
   - Laporkan ke Tim DevOps untuk dilakukan *IP blacklisting* pada firewall Cloudflare / Nginx.
