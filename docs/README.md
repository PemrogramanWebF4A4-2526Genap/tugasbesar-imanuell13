# Digital Zone - UAS Pemrograman Web

Digital Zone adalah aplikasi e-commerce produk elektronik berbasis PHP Native yang dikembangkan sebagai proyek UAS Pemrograman Web. Aplikasi mendukung tiga role pengguna yaitu **Admin**, **Penjual**, dan **Pembeli**.

## Deskripsi

Digital Zone merupakan aplikasi marketplace elektronik berbasis web yang menyediakan fitur transaksi jual beli produk elektronik dengan sistem multi-role (Admin, Penjual, dan Pembeli). Aplikasi dilengkapi dengan manajemen produk, transaksi, pembayaran, review produk, garansi, dashboard analitik, serta sistem otomasi untuk mendukung proses bisnis e-commerce.

---

## Teknologi

- PHP 8.x (Native)
- MySQL 8.x
- Bootstrap 5
- HTML5
- CSS3
- JavaScript
- AJAX
- Laragon

---

## Struktur Folder

```
UAS_INFO2425_202410715013_IMANUEL/
│
├── index.php
├── login.php
├── register.php
├── cart.php
├── checkout.php
├── logout.php
├── payment.php
├── product_detail.php
│
├── src/
│   │
│   ├── config/
│   │   └── database.php
│   │
│   ├── controllers/
│   │   ├── AuthController.php
│   │   ├── ProductController.php
│   │   ├── CartController.php
│   │   ├── OrderController.php
│   │   ├── ReviewController.php
│   │   ├── PaymentController.php
│   │   └── AdminController.php
│   │
│   ├── models/
│   │   ├── User.php
│   │   ├── Product.php
│   │   ├── Category.php
│   │   ├── Order.php
│   │   ├── Payment.php
│   │   ├── Review.php
│   │   ├── Notification.php
│   │   └── Warranty.php
│   │
│   ├── views/
│   │   │
│   │   ├── admin/
│   │   │   ├── dashboard.php
│   │   │   ├── users.php
│   │   │   ├── products.php
│   │   │   ├── settings.php
│   │   │   └── orders.php
│   │   │
│   │   ├── seller/
│   │   │   ├── dashboard.php
│   │   │   ├── add_product.php
│   │   │   ├── edit_product.php
│   │   │   └── orders.php
│   │   │
│   │   ├── buyer/
│   │   │   ├── dashboard.php
│   │   │   ├── orders.php
│   │   │   └── review.php
│   │   │
│   │   └── public/
│   │       ├── navbar.php
│   │       ├── footer.php
│   │       └── home.php
│   │
│   ├── assets/
│   │   │
│   │   ├── css/
│   │   │   └── style.css
│   │   │
│   │   ├── js/
│   │   │   └── script.js
│   │   │
│   │   └── images/
│   │       ├── logo.png
│   │       └── banner.jpg
│   │
│   └── uploads/
│       ├── products/
│       ├── payments/
│       ├── reviews/
│       └── warranty/
│
├── database/
│   └── database.sql
│
├── docs/
│   ├── README.md
│   ├── USER_MANUAL.md
│   └── DATABASE_SCHEMA.pdf
│
├── presentation/
│   └── PRESENTASI_UAS.pptx
│
└── TESTING_REPORT.pdf
```

---

## Database

Database menggunakan **MySQL 8.x** dengan **11 tabel**:

- users
- categories
- products
- orders
- order_items
- payments
- reviews
- notifications
- warranties
- email_logs
- system_settings

---

## Fitur

### Pembeli

- Register & Login
- Browse Produk
- Search Produk
- Filter Produk berdasarkan kategori
- Keranjang Belanja (AJAX)
- Checkout
- Upload Bukti Pembayaran
- Tracking Pesanan
- Review & Rating Produk

### Penjual

- Dashboard Penjualan
- CRUD Produk
- Kelola Pesanan Produk
- Update Status Pengiriman
- Statistik Penjualan

### Admin

- Dashboard Admin
- Manage User
- Verifikasi Seller
- Manage Produk & Kategori
- Manage Semua Pesanan
- Verifikasi Pembayaran
- Report & Analytics
- System Settings

### System Automation

- Auto Generate Invoice
- Auto Calculate Shipping Cost
- Auto Reduce Product Stock
- Auto Update Order Status
- Auto Create Notification
- Auto Create Email Notification Log

---

## Security

- Password Hashing menggunakan **bcrypt**
- SQL Injection Prevention menggunakan **Prepared Statement**
- XSS Prevention menggunakan **htmlspecialchars()**
- Session Management
- Validasi Upload File

---

## Instalasi

1. Salin folder project ke:

```
C:\laragon\www
```

2. Jalankan **Laragon**, kemudian klik **Start All**.

3. Buat database dengan nama:

```
electroshop_db
```

4. Import file:

```
database/database.sql
```

5. Jalankan aplikasi melalui browser:

```
http://localhost/UAS_INFO2425_202410715013_IMANUEL/
```

---

## Akun Demo

Password seluruh akun demo:

```
password
```

| Role | Email |
|------|-------|
| Admin | admin@digitalzone.com |
| Seller | seller@digitalzone.com |
| Buyer | buyer@digitalzone.com |

---

## Author

**Imanuel Melandri Manik**  
NIM: **202410715013**

---

## License

Project ini dibuat sebagai tugas **UAS Pemrograman Web** dan digunakan hanya untuk keperluan akademik.