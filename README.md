# KarcisDigital — Event Ticketing Marketplace

Platform marketplace tiket event berbasis web. Vendor bisa membuat event, menambah tipe tiket (SKU), dan menjual tiket ke buyer. Dilengkapi dengan panel admin, pembayaran online, dan e-ticket dengan QR code.

## Tech Stack

| Layer | Teknologi |
|-------|-----------|
| Backend | PHP 8.3, Laravel 12 |
| Frontend | Blade, Tailwind CSS v4, Alpine.js |
| Database | MySQL (production), SQLite (testing) |
| Auth API | Laravel Sanctum (token-based) |
| Auth Web | Session-based (Laravel built-in) |
| Payment | Xendit Snap |
| API Docs | L5-Swagger (OpenAPI) |
| Testing | PHPUnit 11 |
| Mobile | Flutter (consumer app) |

## Arsitektur Multi-Role

```
Super Admin (/superadmin)
├── Dashboard & statistik
├── Kelola users, vendors, events, orders
├── Kategori event (CRUD)
└── Laporan & analisis

Vendor (/vendor)
├── Dashboard penjualan
├── CRUD event + upload gambar
├── Kelola SKU (tipe tiket)
├── Lihat orders per event
└── Scan/check tiket (redeem)

Buyer (/)
├── Browse & cari event
├── Checkout tiket (multi-SKU)
├── Pembayaran via Xendit
├── Riwayat pesanan
├── E-ticket dengan QR code
└── Profil pengguna
```

## Fitur Lengkap

### Landing Page
- Hero section dengan CTA "Cari Event" dan "Jual Tiket"
- Smart routing: tombol Jual Tiket menyesuaikan status user (guest → register, logged in → register vendor, vendor → dashboard)
- Design system page (`/design-system`)

### Authentication
- Register & login (email + password)
- Session-based auth untuk web
- Token-based auth (Sanctum) untuk API/Flutter
- Role-based redirect setelah login (admin → /superadmin, vendor → /vendor/dashboard, buyer → /events)
- Register sebagai vendor (setelah login)

### Event Browsing (Public)
- Listing event dengan grid cards dan gambar
- Filter berdasarkan kategori
- Search by nama event
- Pagination dengan custom UI
- Detail event: hero image, info lengkap, deskripsi, info vendor
- Panel pemesanan tiket (pilih tipe & jumlah)

### Checkout & Pembayaran
- Halaman checkout: review pesanan, info pembeli, detail tiket
- Loading overlay saat proses pembayaran
- Integrasi Xendit untuk pembayaran online
- Halaman sukses/gagal pembayaran
- Simulasi webhook untuk development lokal (update status dari frontend)

### Riwayat Pesanan (Buyer)
- List pesanan dengan status badge (Sukses/Pending/Gagal)
- Statistik ringkasan (total order, sukses, pending)
- Detail pesanan dengan link ke e-ticket
- Custom pagination

### E-Ticket
- QR code per tiket (via qrserver.com API)
- Status tiket: Berlaku (sold), Menunggu Bayar (booked), Sudah Digunakan (redeem)
- Info event, tipe tiket, dan kode tiket

### Profil Pengguna
- Lihat & edit profil (nama, email, telepon)

### Vendor Dashboard
- Statistik penjualan
- CRUD event (nama, deskripsi, gambar, tanggal, kategori)
- Kelola SKU per event (nama, harga, stok, kategori, day_type)
- Lihat orders per event
- Scan/check tiket untuk redeem

### Super Admin Panel (`/superadmin`)
- **Dashboard**: 8 stat cards, pesanan terbaru, vendor pending verifikasi
- **Users**: Tabel user dengan search, role badge, jumlah order
- **Vendors**: Tabel vendor dengan filter status, approve/reject dengan confirmation dialog
- **Events**: Tabel event dengan info vendor, kategori, tanggal, status
- **Orders**: Tabel order dengan search pembeli, filter status
- **Kategori**: CRUD kategori event (hapus hanya jika tidak digunakan)
- **Laporan & Analisis**:
  - Summary cards: Total Revenue, Rata-rata Order, Tiket Terjual, Conversion Rate
  - Revenue chart harian (bar chart dengan tooltip, Y-axis, grid lines)
  - Filter periode: 7 hari / 30 hari / 3 bulan / 1 tahun
  - Distribusi status order (stacked bar + breakdown)
  - Distribusi status tiket (stacked bar + breakdown)
  - Revenue per kategori (horizontal bar)
  - Top 10 event berdasarkan revenue
  - Top 10 vendor berdasarkan revenue
- Layout: Sidebar navigasi + topbar dengan avatar dropdown & logout
- Middleware: Cek email `@admin.com` untuk akses admin
- Responsive: Mobile sidebar toggle

### REST API (untuk Flutter)
- `POST /api/register` — Register user
- `POST /api/login` — Login, return token
- `POST /api/logout` — Logout
- `GET /api/events` — List semua event (dengan tickets grouped by SKU)
- `GET /api/events/user/{userId}` — Event milik vendor
- `POST /api/events` — Buat event (multipart)
- `POST /api/event/update/{id}` — Update event
- `DELETE /api/event/{id}` — Hapus event
- `GET /api/event-categories` — List kategori
- `POST /api/order` — Buat order (multi-SKU)
- `GET /api/orders/user/{userId}` — Order milik buyer
- `GET /api/orders/user/{userId}/vendor` — Order untuk vendor
- `GET /api/orders/user/{userId}/vendor/total` — Total revenue vendor
- `POST /api/sku` — Buat SKU
- `GET /api/skus/user/{userId}` — SKU milik vendor
- `GET /api/tickets/user/{userId}` — Tiket milik vendor
- `POST /api/check-ticket` — Validasi & redeem tiket
- `POST /api/vendor` — Daftar sebagai vendor
- `GET /api/vendors/user/{userId}` — Info vendor
- `POST /api/xendit/webhook` — Xendit payment callback

### Webhook & Payment Flow
```
Buyer checkout → Order created (status: pending) → Tiket di-book
    → Xendit payment URL → Buyer bayar
    → Webhook callback → status: success → Tiket jadi sold
    → Jika gagal/cancel → Tiket dikembalikan ke available
```

## Struktur Direktori

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/                    # REST API (Flutter)
│   │   │   ├── AuthController
│   │   │   ├── EventController
│   │   │   ├── OrderController
│   │   │   ├── SkuController
│   │   │   ├── TicketController
│   │   │   ├── VendorController
│   │   │   └── XenditWebhookController
│   │   └── Web/                    # Web controllers
│   │       ├── AdminController
│   │       ├── AuthController
│   │       ├── EventController
│   │       ├── OrderController
│   │       ├── ProfileController
│   │       ├── TicketController
│   │       ├── VendorController
│   │       ├── VendorDashboardController
│   │       ├── VendorEventController
│   │       ├── VendorOrderController
│   │       ├── VendorSkuController
│   │       └── VendorTicketController
│   └── Middleware/
│       ├── EnsureIsAdmin
│       └── EnsureIsVendor
├── Models/
│   ├── User, Vendor, Event, EventCategory
│   ├── Sku, Ticket, Order, OrderTicket
├── Services/
│   └── Xendit/                     # Payment service
└── Filament/                       # (legacy admin panel)

resources/views/
├── landing.blade.php               # Landing page
├── auth/                           # Login, register
├── events/                         # Browse & detail event
├── orders/                         # Checkout, riwayat, payment pages
├── tickets/                        # E-ticket viewer
├── profile/                        # Profil user
├── vendor/                         # Vendor portal
├── admin/                          # Super admin panel
│   ├── dashboard, users, vendors
│   ├── events, orders, categories
│   └── reports                     # Laporan & analisis
└── components/layouts/
    ├── landing.blade.php           # Public layout (navbar transparent/solid)
    ├── app.blade.php               # Vendor layout
    └── admin.blade.php             # Admin layout (sidebar + topbar)

routes/
├── api.php                         # REST API routes
└── web.php                         # Web routes (public, auth, vendor, admin)
```

## Entity Relationship

```
User (1) ──── (1) Vendor
                   │
                   └── (N) Event ──── (1) EventCategory
                            │
                            ├── (N) Sku ──── (N) Ticket
                            │
                            └── (N) Order ──── (N) OrderTicket ──── Ticket

User (1) ──── (N) Order
```

## Setup & Instalasi

```bash
# Clone repository
git clone <repo-url>
cd laravel-ticketing-backend

# Install dependencies
composer install
npm install

# Environment
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate --seed

# Build assets
npm run build

# Jalankan server
php artisan serve
```

### Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@admin.com | password |

### Environment Variables

```env
# Database
DB_CONNECTION=mysql
DB_DATABASE=KarcisDigital

# Xendit
XENDIT_SECRET_KEY=your_xendit_secret_key

# App
APP_URL=http://localhost:8000
```

## Screenshots

> Halaman yang tersedia:
> - Landing Page (`/`)
> - Event Listing (`/events`)
> - Event Detail (`/events/{id}`)
> - Checkout (`/events/{id}/checkout`)
> - Payment Success/Failed
> - Riwayat Pesanan (`/orders`)
> - E-Ticket (`/tickets/{id}`)
> - Profil (`/profile`)
> - Vendor Dashboard (`/vendor/dashboard`)
> - Super Admin (`/superadmin`)
> - Laporan & Analisis (`/superadmin/reports`)

## License

[MIT License](https://opensource.org/licenses/MIT)

---

Powered by [JagoFlutter.com](https://jagoflutter.com)
