# 🍱 Food-Order-system
### Platform pemesanan makanan online berbasis web

[![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3+-777BB4?style=flat-square&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=flat-square&logo=mysql&logoColor=white)](https://mysql.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)](LICENSE)

</div>

---

## 📋 Tentang Proyek

**FoodApp** adalah aplikasi web pemesanan makanan yang memungkinkan pelanggan memesan menu secara online dengan berbagai metode pembayaran. Dilengkapi panel admin untuk mengelola menu, pesanan, dan konfirmasi pembayaran.

---

## ✨ Fitur

### 👤 Pelanggan
- Registrasi & login akun
- Browsing menu dengan gambar dan harga
- Keranjang belanja (cart) dinamis
- Checkout dengan metode pembayaran **QRIS**, **Transfer Bank**, atau **COD**
- Upload bukti pembayaran
- Riwayat & detail pesanan dengan status real-time
- Konfirmasi penerimaan pesanan
- Ulasan menu setelah pesanan selesai

### 🛠️ Admin
- Dashboard ringkasan pesanan & pendapatan
- Manajemen menu (tambah, edit, hapus, stok)
- Manajemen pesanan dengan alur status: `pending → paid → prepared → shipped → delivered`
- Konfirmasi & penolakan pembayaran QRIS / Transfer Bank
- Lihat bukti transfer yang diunggah pelanggan

---

## 🖥️ Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 13, PHP 8.3 |
| Frontend | Blade, Alpine.js, Tailwind CSS |
| Database | MySQL 8 |
| Auth | Laravel Breeze |
| Storage | Laravel Storage (public disk) |
| Queue & Cache | Database driver |

---

## 🗂️ Struktur Database

```
users
 └── pemesanans (orders)
      ├── pemesanan_items  →  menus
      ├── pembayarans (payments)
      └── ulasans (reviews)  →  menus
```

**Status pesanan:** `pending` → `paid` → `prepared` → `shipped` → `delivered` / `cancelled`

**Metode pembayaran:** `qris` · `bank_transfer` · `cod`

---

## 🚀 Instalasi

### Prasyarat
- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL 8

### Langkah Instalasi

**1. Clone repository**
```bash
git clone https://github.com/username/food-order-system.git
cd food-order-system
```

**2. Install dependencies**
```bash
composer install
npm install
```

**3. Konfigurasi environment**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Atur koneksi database di `.env`**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Laravel
DB_USERNAME=root
DB_PASSWORD=
```

**5. Jalankan migrasi & seeder**
```bash
php artisan migrate --seed
```

**6. Buat symlink storage**
```bash
php artisan storage:link
```

**7. Build assets & jalankan server**
```bash
npm run dev
php artisan serve
```

Akses aplikasi di: **http://localhost:8000**

---

## 👥 Akun Default

Setelah menjalankan seeder:

| Role | Email | Password |
|---|---|---|
| Admin | admin@santap.com | password |
| User | user@santap.com | password |

> ⚠️ Ganti password default sebelum deploy ke production.

---

## 📁 Struktur Direktori Penting

```
app/
├── Http/Controllers/
│   ├── Admin/
│   │   ├── DashboardController.php
│   │   ├── MenuController.php
│   │   └── OrderController.php       # konfirmasi pembayaran
│   ├── CheckoutController.php        # proses pesanan & upload bukti
│   ├── CartController.php
│   └── PemesananController.php
├── Models/
│   ├── Pemesanan.php
│   ├── Pembayaran.php
│   ├── Menu.php
│   └── Ulasan.php
resources/views/
├── admin/
│   ├── dashboard.blade.php
│   ├── menu/
│   └── orders/
├── checkout/
├── cart/
└── pemesanan/
public/
└── images/
    └── qris.png                      # ← letakkan gambar QRIS di sini
```

---

## 🔄 Alur Pemesanan

```
Pilih Menu → Keranjang → Checkout → Pilih Metode Bayar
    │
    ├── COD          → Pesanan langsung diproses
    │
    └── QRIS / Bank  → Upload bukti → Admin konfirmasi → Pesanan diproses
```

---

## 📸 Screenshot
<img width="1898" height="967" alt="Screenshot 2026-04-21 180239" src="https://github.com/user-attachments/assets/9d85ac0f-022c-4a14-a3aa-9f2c35bc16b8" />
<img width="1883" height="970" alt="Screenshot 2026-04-21 183054" src="https://github.com/user-attachments/assets/cbc6290f-52ba-4841-9930-804a59d7936b" />
<img width="1861" height="892" alt="Screenshot 2026-04-21 183228" src="https://github.com/user-attachments/assets/21b128f9-3514-4717-b083-b1ee19141062" />
<img width="1917" height="966" alt="Screenshot 2026-04-23 233404" src="https://github.com/user-attachments/assets/38b5036d-e2d7-4f1d-99a8-cfc5f4912c35" />
<img width="1915" height="965" alt="Screenshot 2026-04-23 233427" src="https://github.com/user-attachments/assets/6ba7ba95-6d6f-49e0-b27a-5b3290709a99" />
<img width="1902" height="958" alt="Screenshot 2026-04-23 233452" src="https://github.com/user-attachments/assets/3da8dd56-3b3f-4ee8-a56b-6da5a9725bdc" />
<img width="1919" height="967" alt="Screenshot 2026-04-23 233504" src="https://github.com/user-attachments/assets/0aab0020-589c-4092-81f9-50350ce08bd1" />
<img width="1893" height="973" alt="Screenshot 2026-04-21 183950" src="https://github.com/user-attachments/assets/9f0c9578-b956-4f17-815d-01579ed6572f" />
<img width="1919" height="967" alt="Screenshot 2026-04-23 233504" src="https://github.com/user-attachments/assets/0703c745-6115-41e0-9de0-09c98c56f549" />
<img width="1897" height="912" alt="Screenshot 2026-04-23 233813" src="https://github.com/user-attachments/assets/a8ba3353-58a9-4b4a-b9aa-7f466a7ed81d" />
<img width="1894" height="915" alt="Screenshot 2026-04-23 233826" src="https://github.com/user-attachments/assets/2f0d38a9-1e22-4d75-8fda-794e12fe0ad1" />
<img width="1899" height="909" alt="Screenshot 2026-04-23 233839" src="https://github.com/user-attachments/assets/503cf083-cce3-4b38-9f67-54b60e5dcc43" />
<img width="1894" height="910" alt="Screenshot 2026-04-23 233848" src="https://github.com/user-attachments/assets/23687075-f6d1-4f78-8cfb-82a005118887" />
<img width="1899" height="913" alt="Screenshot 2026-04-23 233905" src="https://github.com/user-attachments/assets/a2c962e1-d375-4cad-9f9d-673108c38da8" />

---

## 📄 Lisensi

Proyek ini menggunakan lisensi [MIT](LICENSE).

---

<div align="center">
  Dibuat dengan ❤️ menggunakan Laravel
</div>
