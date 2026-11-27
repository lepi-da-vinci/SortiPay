# SortiPay — Aplikasi Web Bank Sampah Digital

**SortiPay** adalah aplikasi web bank sampah digital berbasis poin dan penukaran barang yang dibangun dengan **Laravel 13**, **Blade**, **Tailwind CSS v4**, dan **SQLite**.

## Fitur Utama
- **Portal Nasabah (`USER`)**: Saldo poin real-time, Kartu QR Member ID, katalog tarif sampah per kategori, katalog tukar barang (sembako/voucher), dan riwayat setoran serta tiket klaim QR.
- **POS Petugas (`OFFICER`)**: Pencarian nasabah cepat, input multi-item sampah + kondisi (`100%` / `75%`), kalkulasi poin otomatis, struk digital siap cetak, dan verifikasi kode klaim barang (termasuk pembatalan & refund otomatis).
- **Panel Admin (`ADMIN`)**: Dashboard analitik komposisi sampah & poin, CRUD Master Jenis Sampah (dengan *snapshot* tarif pada histori lama), CRUD Katalog Barang & Stok, manajemen pengguna & reset kata sandi, serta Audit Log Buku Besar Poin (*Immutable Point Ledger*).
- **Keamanan Akun**: Ganti kata sandi mandiri (`/account/password`) untuk semua role.

---

## Prasyarat
- **PHP >= 8.3** (cek dengan `php -v`)
- **Composer**
- **Node.js & NPM**

---

## Cara Install Setelah `git clone`

Folder `vendor/`, `node_modules/`, `public/build/`, dan file `.env` tidak ikut di-push ke GitHub (standar Laravel). Setelah `git clone`, jalankan perintah berikut di terminal (PowerShell / CMD):

```powershell
# 1. Masuk ke folder proyek
cd SortiPay

# 2. Install dependensi PHP (membuat folder vendor/)
composer install

# 3. Salin file konfigurasi .env
copy .env.example .env

# 4. Generate application key
php artisan key:generate

# 5. Buat file database SQLite & jalankan migrasi + data demo
New-Item -ItemType File -Path database\database.sqlite -Force
php artisan migrate --seed

# 6. Install dependensi frontend & build aset Tailwind + QR Code
npm install
npm run build

# 7. Jalankan server lokal
php artisan serve
```

Buka browser dan akses: **http://127.0.0.1:8000**

---

## Akun Demo (Kata Sandi Semua Akun: `password`)

| Role | Email | Member Code |
| :--- | :--- | :--- |
| **Admin** | `admin@sortipay.id` | `SRT-2026-0001` |
| **Petugas Posko** | `petugas@sortipay.id` | `SRT-2026-0002` |
| **Nasabah** | `siti@sortipay.id` | `SRT-2026-0003` |
| **Nasabah** | `andi@sortipay.id` | `SRT-2026-0004` |
| **Nasabah** | `dewi@sortipay.id` | `SRT-2026-0005` |
