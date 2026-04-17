# Analisis Sistem Ticketing Marketplace

> **Konsep:** Marketplace tiket wisata & pengalaman (seperti Traveloka Experience)
> **Arsitektur:** Multi-merchant / Multi-vendor
> **Stack:** Laravel 12, Sanctum, Midtrans, Filament 3 (akan diganti)
> **Status:** Early Alpha / Proof of Concept

---

## 1. Arsitektur Database Saat Ini

```
users ──(1:1)──→ vendors
  │                 │
  │              (1:M)
  │                 ↓
  │              events ←──(M:1)── event_categories
  │                 │
  │          ┌──────┼──────┐
  │       (1:M)  (1:M)  (1:M)
  │          ↓      ↓      ↓
  └──(1:M)→ orders  skus  tickets
              │       │      │
              │    (1:M)     │
              │      ↓       │
              └──→ order_tickets ←┘
```

### Tabel & Field

| Tabel | Field Utama | Catatan |
|-------|------------|---------|
| `users` | name, email, password, phone, is_vendor | Dual role: customer & vendor |
| `vendors` | user_id, name, description, location, phone, city, verify_status | Status: pending/approved/rejected |
| `event_categories` | name, description | 4 kategori: Pantai, Gunung, Permainan, Budaya |
| `events` | vendor_id, event_category_id, name, description, image, start_date, end_date | Single image only |
| `skus` | event_id, name, category, price, stock, day_type | Varian tiket (Dewasa/Anak/VIP) |
| `tickets` | sku_id, event_id, ticket_code, ticket_date, status | Status: available/booked/sold/redeem |
| `orders` | user_id, event_id, quantity, total_price, status_payment, payment_url, event_date | Payment: pending/success/cancel |
| `order_tickets` | order_id, ticket_id | Pivot table |

---

## 2. Fitur yang Sudah Ada

### Auth & User
- [x] Register (email/password)
- [x] Login (email/password + Google OAuth)
- [x] Logout (Sanctum token revocation)
- [x] Dual role user (customer & vendor via `is_vendor`)

### Event / Pengalaman
- [x] CRUD Event (create, read, update, delete)
- [x] Kategori event
- [x] Filter by kategori
- [x] Upload gambar event
- [x] List event by vendor

### Tiket & SKU
- [x] Multi-SKU per event (Dewasa, Anak, VIP, Bundle, Weekend/Weekday)
- [x] Auto-generate tiket saat buat SKU
- [x] Unique ticket code (10 char alphanumeric)
- [x] Ticket redemption (scan/input kode)

### Order & Payment
- [x] Create order dengan alokasi tiket
- [x] Integrasi Midtrans Snap (payment URL)
- [x] Update status pembayaran
- [x] Riwayat order customer
- [x] Riwayat order vendor
- [x] Total revenue vendor

### Admin Panel (Filament)
- [x] Basic setup dengan auth
- [x] Vendor CRUD (list, create, edit, delete)
- [x] Filter vendor by verify_status

---

## 3. Bug & Masalah Kritis

### CRITICAL

| # | Bug | File | Detail |
|---|-----|------|--------|
| 1 | **Order→Sku relationship broken** | `Order.php` | Model punya `belongsTo(Sku)` tapi migration TIDAK punya kolom `sku_id` |
| 2 | **Duplicate route** | `api.php:22,26` | `GET /api/events` didefinisikan 2x, yang public ter-override oleh auth |
| 3 | **Order ID collision** | `OrderController.php` | `rand(1000, 9999)` untuk Midtrans order_id - PASTI bentrok |
| 4 | **No DB transaction** | `OrderController.php` | Alokasi tiket tidak atomic - bisa race condition |
| 5 | **Status field mismatch** | `Order.php` vs migration | Migration: `status_payment`, Code: `status` |

### HIGH

| # | Bug | File | Detail |
|---|-----|------|--------|
| 6 | **No authorization** | Semua controller | Siapapun bisa update/delete event/order milik orang lain |
| 7 | **Ticket overselling** | `OrderController.php` | Tiket "booked" masih bisa di-book lagi |
| 8 | **SKU stock tidak berkurang** | `OrderController.php` | Field `stock` ada tapi tidak pernah di-decrement |
| 9 | **No file validation** | `EventController.php` | Upload gambar tanpa validasi tipe/ukuran |
| 10 | **Google login bug** | `AuthController.php:54` | Token dibuat sebelum cek user exist |

### MEDIUM

| # | Bug | File | Detail |
|---|-----|------|--------|
| 11 | **No Midtrans webhook** | - | Tidak ada handler untuk notifikasi payment dari Midtrans |
| 12 | **No email verification** | - | Setelah register langsung bisa login |
| 13 | **No soft deletes** | Semua model | Delete = permanent, tidak bisa recovery |
| 14 | **Typo config** | `midtrans.php` | `mercant_id` harusnya `merchant_id` |
| 15 | **Missing model casts** | Semua model | Tanggal, harga tidak di-cast dengan benar |

---

## 4. Fitur yang Belum Ada (Gap Analysis vs Traveloka Experience)

### Must Have (P0)

| # | Fitur | Deskripsi | Prioritas |
|---|-------|-----------|-----------|
| 1 | **Midtrans Webhook Handler** | Endpoint untuk menerima notifikasi pembayaran otomatis | CRITICAL |
| 2 | **Authorization & Policy** | Laravel Policy untuk cek kepemilikan resource | CRITICAL |
| 3 | **Search & Filter Lanjutan** | Search by nama, lokasi, harga, tanggal, rating | HIGH |
| 4 | **Review & Rating** | Customer bisa review setelah redeem tiket | HIGH |
| 5 | **Notifikasi** | Email konfirmasi order, reminder event, status pembayaran | HIGH |
| 6 | **Refund & Cancellation** | Kebijakan pembatalan dan proses refund | HIGH |
| 7 | **Event Image Gallery** | Multiple gambar per event, bukan cuma 1 | HIGH |
| 8 | **Vendor Verification Flow** | Upload dokumen KYC, approval workflow admin | HIGH |

### Should Have (P1)

| # | Fitur | Deskripsi |
|---|-------|-----------|
| 9 | **Promo & Voucher** | Sistem kupon diskon dan promo code |
| 10 | **Wishlist / Favorites** | Simpan event favorit |
| 11 | **Komisi Platform** | Revenue split antara platform dan vendor |
| 12 | **Vendor Dashboard Analytics** | Statistik penjualan, grafik, laporan |
| 13 | **Admin Dashboard** | Overview seluruh platform, GMV, jumlah user, dll |
| 14 | **Event Location (Map)** | Integrasi Google Maps, geolocation search |
| 15 | **Dynamic/Seasonal Pricing** | Harga berbeda untuk peak season, holiday |
| 16 | **Bulk/Group Booking** | Diskon untuk pemesanan grup |

### Nice to Have (P2)

| # | Fitur | Deskripsi |
|---|-------|-----------|
| 17 | **Wallet / Credit System** | Saldo digital untuk customer |
| 18 | **Chat Vendor-Customer** | Komunikasi langsung |
| 19 | **Event Calendar View** | Tampilan kalender untuk jadwal event |
| 20 | **Multi-language** | Support Bahasa Indonesia & English |
| 21 | **QR Code Ticket** | Generate QR code untuk tiket |
| 22 | **Waitlist** | Daftar tunggu saat sold out |
| 23 | **Recommendation Engine** | Rekomendasi berdasarkan history |
| 24 | **Social Sharing** | Share event ke social media |

---

## 5. Rencana Rebuild Dashboard (Tailwind 4 + Alpine.js + Blade)

### Kenapa Ganti Filament?

| Aspek | Filament 3 | Tailwind + Alpine.js + Blade |
|-------|-----------|-------------------------------|
| Customization | Terbatas oleh framework | Full control |
| Bundle size | Besar (Livewire + Filament) | Ringan |
| Learning curve | Harus belajar Filament API | Standard Laravel stack |
| Design | Opinionated | Custom sesuai brand |
| Performance | Heavy (Livewire roundtrips) | Fast (minimal JS) |

### Tech Stack Dashboard

```
Frontend:
├── Tailwind CSS 4 (via Vite)
├── Alpine.js 3 (interactivity)
├── Blade components (templating)
├── Chart.js / ApexCharts (grafik)
└── Vite (bundler)

Backend:
├── Laravel 12 (existing)
├── Blade layouts & components
├── Laravel middleware (role-based)
└── Server-side rendering (SSR)
```

### Struktur File Dashboard

```
resources/views/
├── layouts/
│   ├── dashboard.blade.php          # Layout utama dashboard
│   └── partials/
│       ├── sidebar.blade.php        # Sidebar navigasi
│       ├── navbar.blade.php         # Top navigation
│       └── footer.blade.php         # Footer
│
├── dashboard/
│   ├── index.blade.php              # Overview / Home
│   │
│   ├── admin/                       # Admin pages
│   │   ├── overview.blade.php       # Platform statistics
│   │   ├── users/
│   │   │   ├── index.blade.php      # List users
│   │   │   └── show.blade.php       # Detail user
│   │   ├── vendors/
│   │   │   ├── index.blade.php      # List vendors
│   │   │   ├── show.blade.php       # Detail vendor
│   │   │   └── verify.blade.php     # Verification review
│   │   ├── events/
│   │   │   ├── index.blade.php      # All events
│   │   │   └── show.blade.php       # Event detail
│   │   ├── orders/
│   │   │   ├── index.blade.php      # All orders
│   │   │   └── show.blade.php       # Order detail
│   │   ├── categories/
│   │   │   └── index.blade.php      # Manage categories
│   │   └── reports/
│   │       ├── revenue.blade.php    # Revenue reports
│   │       └── commissions.blade.php
│   │
│   └── vendor/                      # Vendor pages
│       ├── overview.blade.php       # Vendor dashboard
│       ├── events/
│       │   ├── index.blade.php      # My events
│       │   ├── create.blade.php     # Create event
│       │   └── edit.blade.php       # Edit event
│       ├── orders/
│       │   ├── index.blade.php      # My orders
│       │   └── show.blade.php       # Order detail
│       ├── tickets/
│       │   ├── index.blade.php      # My tickets
│       │   └── scan.blade.php       # Scan/redeem ticket
│       ├── skus/
│       │   └── manage.blade.php     # Manage SKUs
│       ├── reviews/
│       │   └── index.blade.php      # Customer reviews
│       └── settings/
│           └── profile.blade.php    # Vendor profile
│
├── components/
│   ├── ui/
│   │   ├── button.blade.php
│   │   ├── card.blade.php
│   │   ├── modal.blade.php
│   │   ├── table.blade.php
│   │   ├── badge.blade.php
│   │   ├── dropdown.blade.php
│   │   ├── input.blade.php
│   │   ├── select.blade.php
│   │   ├── alert.blade.php
│   │   └── pagination.blade.php
│   ├── charts/
│   │   ├── revenue-chart.blade.php
│   │   ├── orders-chart.blade.php
│   │   └── stats-card.blade.php
│   └── forms/
│       ├── event-form.blade.php
│       ├── sku-form.blade.php
│       └── vendor-form.blade.php
```

### Halaman Dashboard

#### Admin Dashboard
1. **Overview** - Total GMV, total user, total vendor, total event, grafik penjualan
2. **User Management** - List, search, filter, detail, ban/unban
3. **Vendor Management** - List, verify/reject, detail, dokumen KYC
4. **Event Management** - Semua event, moderasi konten
5. **Order Management** - Semua transaksi, filter status
6. **Category Management** - CRUD kategori
7. **Reports** - Revenue, komisi, top vendor, top event

#### Vendor Dashboard
1. **Overview** - Total penjualan, tiket terjual, grafik, recent orders
2. **Event Management** - CRUD event saya
3. **SKU Management** - Manage varian tiket per event
4. **Order List** - Pesanan masuk, filter status
5. **Ticket Scanner** - Scan/input kode redeem tiket
6. **Reviews** - Ulasan dari customer
7. **Settings** - Profile vendor, dokumen

---

## 6. Rencana Landing Page (Tailwind 4 + Alpine.js)

### Desain Konsep

```
┌─────────────────────────────────────────────────┐
│  NAVBAR                                         │
│  Logo | Destinasi | Kategori | Cari | Login     │
├─────────────────────────────────────────────────┤
│                                                 │
│  HERO SECTION                                   │
│  ┌─────────────────────────────────────────┐    │
│  │  "Temukan Pengalaman                    │    │
│  │   Wisata Terbaik"                       │    │
│  │                                         │    │
│  │  [🔍 Cari event, destinasi, aktivitas]  │    │
│  │  [Kategori] [Tanggal] [Lokasi] [Cari]   │    │
│  └─────────────────────────────────────────┘    │
│                                                 │
├─────────────────────────────────────────────────┤
│  KATEGORI SECTION                               │
│  ┌────┐ ┌────┐ ┌────┐ ┌────┐                   │
│  │    │ │    │ │    │ │    │                   │
│  │ 🏖 │ │ ⛰ │ │ 🎮 │ │ 🎭 │                   │
│  │Pan │ │Gun │ │Per │ │Bud │                   │
│  │tai │ │ung │ │mai │ │aya │                   │
│  └────┘ └────┘ └────┘ └────┘                   │
│                                                 │
├─────────────────────────────────────────────────┤
│  EVENT POPULER                                  │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐           │
│  │ img  │ │ img  │ │ img  │ │ img  │           │
│  │      │ │      │ │      │ │      │           │
│  │ Nama │ │ Nama │ │ Nama │ │ Nama │           │
│  │ Rp.  │ │ Rp.  │ │ Rp.  │ │ Rp.  │           │
│  │ ★★★★ │ │ ★★★★ │ │ ★★★★ │ │ ★★★★ │           │
│  └──────┘ └──────┘ └──────┘ └──────┘           │
│                                                 │
├─────────────────────────────────────────────────┤
│  KENAPA PILIH KAMI?                             │
│  ┌──────────┐ ┌──────────┐ ┌──────────┐        │
│  │ Terpercaya│ │ Harga    │ │ Mudah    │        │
│  │ 1000+     │ │ Terbaik  │ │ & Cepat  │        │
│  │ vendor    │ │ Garansi  │ │ booking  │        │
│  └──────────┘ └──────────┘ └──────────┘        │
│                                                 │
├─────────────────────────────────────────────────┤
│  PROMO / BANNER SECTION                         │
│  ┌─────────────────────────────────────────┐    │
│  │  Diskon 20% untuk pengalaman pertama!   │    │
│  │  [Gunakan Kode: FIRST20]                │    │
│  └─────────────────────────────────────────┘    │
│                                                 │
├─────────────────────────────────────────────────┤
│  DESTINASI POPULER                              │
│  Bali | Yogyakarta | Bandung | Malang           │
│  ┌──────┐ ┌──────┐ ┌──────┐ ┌──────┐           │
│  │ foto │ │ foto │ │ foto │ │ foto │           │
│  │ 50+  │ │ 30+  │ │ 25+  │ │ 20+  │           │
│  │event │ │event │ │event │ │event │           │
│  └──────┘ └──────┘ └──────┘ └──────┘           │
│                                                 │
├─────────────────────────────────────────────────┤
│  TESTIMONI                                      │
│  "Pengalaman booking sangat mudah..."           │
│  - Ahmad, Jakarta                               │
│                                                 │
├─────────────────────────────────────────────────┤
│  CTA - JADI VENDOR                              │
│  ┌─────────────────────────────────────────┐    │
│  │  Punya bisnis wisata?                   │    │
│  │  Bergabung jadi vendor sekarang!        │    │
│  │  [Daftar Sebagai Vendor]                │    │
│  └─────────────────────────────────────────┘    │
│                                                 │
├─────────────────────────────────────────────────┤
│  FOOTER                                         │
│  Logo | Tentang | Kontak | Syarat | Kebijakan   │
│  Social Media Icons                             │
│  © 2026 Ticketing Marketplace                   │
└─────────────────────────────────────────────────┘
```

### Struktur File Landing Page

```
resources/views/
├── layouts/
│   └── landing.blade.php            # Layout landing page
│
├── landing/
│   ├── index.blade.php              # Homepage
│   ├── events.blade.php             # Browse semua event
│   ├── event-detail.blade.php       # Detail event + booking
│   ├── categories.blade.php         # Browse by kategori
│   ├── search.blade.php             # Hasil pencarian
│   ├── about.blade.php              # Tentang kami
│   ├── contact.blade.php            # Kontak
│   ├── vendor-register.blade.php    # Daftar jadi vendor
│   └── partials/
│       ├── hero.blade.php
│       ├── categories-grid.blade.php
│       ├── popular-events.blade.php
│       ├── why-us.blade.php
│       ├── promo-banner.blade.php
│       ├── destinations.blade.php
│       ├── testimonials.blade.php
│       ├── cta-vendor.blade.php
│       └── footer.blade.php
│
├── auth/                            # Auth pages (custom)
│   ├── login.blade.php
│   ├── register.blade.php
│   └── forgot-password.blade.php
│
├── customer/                        # Customer pages
│   ├── orders.blade.php             # Riwayat pesanan
│   ├── order-detail.blade.php       # Detail pesanan
│   ├── tickets.blade.php            # Tiket saya
│   ├── profile.blade.php            # Profile
│   └── wishlist.blade.php           # Favorit
```

### Interaksi Alpine.js

```
Alpine.js digunakan untuk:
├── Mobile menu toggle
├── Search autocomplete
├── Image gallery/slider
├── Quantity selector (booking)
├── Date picker interaction
├── Modal (login prompt, konfirmasi)
├── Tabs (detail event: deskripsi/review/lokasi)
├── Filter sidebar (toggle, apply)
├── Toast notifications
├── Dropdown user menu
├── Infinite scroll / load more
└── Form validation (client-side)
```

---

## 7. Roadmap Implementasi

### Phase 1: Fix & Stabilize (1-2 minggu)
- [ ] Fix semua bug critical (order model, duplicate route, dll)
- [ ] Tambah DB transaction untuk order flow
- [ ] Implement Midtrans webhook handler
- [ ] Tambah authorization (Laravel Policy)
- [ ] Fix SKU stock decrement
- [ ] Tambah proper validation di semua endpoint

### Phase 2: Landing Page (1-2 minggu)
- [ ] Setup Tailwind 4 + Alpine.js + Vite
- [ ] Buat layout & komponen UI dasar
- [ ] Homepage dengan semua section
- [ ] Browse events page
- [ ] Event detail + booking page
- [ ] Auth pages (login, register)
- [ ] Customer pages (orders, tickets, profile)
- [ ] Responsive design (mobile-first)

### Phase 3: Dashboard (2-3 minggu)
- [ ] Remove Filament dependency
- [ ] Dashboard layout (sidebar, navbar)
- [ ] Admin: Overview, Users, Vendors, Events, Orders, Categories, Reports
- [ ] Vendor: Overview, Events CRUD, Orders, Tickets, Scanner, Settings
- [ ] Role-based middleware (admin vs vendor)
- [ ] Charts & statistics

### Phase 4: Fitur Lanjutan (2-4 minggu)
- [ ] Review & Rating system
- [ ] Event image gallery (multi-image)
- [ ] Notifikasi email
- [ ] Promo & voucher system
- [ ] Vendor verification flow (upload dokumen)
- [ ] Advanced search & filter
- [ ] QR Code tiket

### Phase 5: Polish & Launch (1-2 minggu)
- [ ] Performance optimization
- [ ] SEO optimization
- [ ] Testing (unit + integration)
- [ ] API documentation
- [ ] Production deployment setup
- [ ] Monitoring & logging

---

## 8. Database Schema Baru (Tambahan)

### Tabel baru yang dibutuhkan:

```sql
-- Reviews & Ratings
reviews:        id, user_id, event_id, order_id, rating(1-5), comment, timestamps

-- Promo & Voucher
vouchers:       id, code, type(percentage/fixed), value, min_order,
                max_discount, quota, used_count, start_date, end_date,
                vendor_id(nullable), timestamps

-- Wishlist
wishlists:      id, user_id, event_id, timestamps

-- Notifications
notifications:  id, user_id, title, message, type, read_at, data(json), timestamps

-- Event Images (gallery)
event_images:   id, event_id, image_path, sort_order, timestamps

-- Vendor Documents (KYC)
vendor_documents: id, vendor_id, type(ktp/npwp/siup), file_path,
                  status(pending/approved/rejected), timestamps

-- Commission/Revenue Split
commissions:    id, order_id, vendor_id, order_amount, commission_rate,
                commission_amount, vendor_amount, status, timestamps

-- Refunds
refunds:        id, order_id, user_id, amount, reason, status(pending/approved/rejected),
                processed_at, timestamps
```

---

## 9. API Endpoints Baru yang Dibutuhkan

```
# Reviews
POST   /api/reviews                    - Buat review
GET    /api/events/{id}/reviews        - List review event
GET    /api/reviews/vendor/{id}        - Review untuk vendor

# Wishlist
POST   /api/wishlist                   - Toggle wishlist
GET    /api/wishlist                   - List wishlist user

# Voucher
POST   /api/voucher/validate           - Validasi kode voucher
GET    /api/vouchers                   - List voucher aktif

# Notifications
GET    /api/notifications              - List notifikasi
PUT    /api/notifications/{id}/read    - Tandai sudah dibaca

# Midtrans Webhook
POST   /api/payment/webhook            - Handle Midtrans notification

# Search
GET    /api/search?q=&category=&location=&min_price=&max_price=&date=

# Refund
POST   /api/orders/{id}/refund         - Request refund
```

---

## 10. Rekomendasi Production-Grade & Industry-Ready

### 10.1 Arsitektur & Code Quality

#### A. Service Layer Pattern
Saat ini semua business logic ada di controller. Untuk production, pisahkan ke service layer:

```
app/
├── Services/
│   ├── OrderService.php           # Business logic order & payment
│   ├── TicketService.php          # Alokasi tiket, redeem, validasi
│   ├── EventService.php           # CRUD event, image handling
│   ├── VendorService.php          # Vendor registration, verification
│   ├── PaymentService.php         # Midtrans integration & webhook
│   ├── NotificationService.php    # Email, push, in-app notification
│   ├── SearchService.php          # Full-text search & filtering
│   └── CommissionService.php      # Revenue split calculation
├── Repositories/                  # (optional) Data access abstraction
│   ├── EventRepository.php
│   ├── OrderRepository.php
│   └── TicketRepository.php
├── DTOs/                          # Data Transfer Objects
│   ├── CreateOrderDTO.php
│   ├── CreateEventDTO.php
│   └── PaymentCallbackDTO.php
├── Actions/                       # Single-responsibility actions
│   ├── CreateOrderAction.php
│   ├── ProcessPaymentAction.php
│   ├── AllocateTicketsAction.php
│   └── RefundOrderAction.php
└── Enums/                         # PHP 8.1 Enums
    ├── TicketStatus.php           # available, booked, sold, redeemed
    ├── OrderStatus.php            # pending, paid, cancelled, refunded
    ├── VendorStatus.php           # pending, approved, rejected, suspended
    └── PaymentStatus.php          # pending, success, failed, expired
```

#### B. Form Request Validation
Ganti inline validation di controller dengan Form Request class:

```
app/Http/Requests/
├── Auth/
│   ├── LoginRequest.php
│   └── RegisterRequest.php
├── Event/
│   ├── StoreEventRequest.php
│   └── UpdateEventRequest.php
├── Order/
│   ├── CreateOrderRequest.php
│   └── UpdateOrderStatusRequest.php
├── Vendor/
│   └── CreateVendorRequest.php
└── Sku/
    └── StoreSkuRequest.php
```

#### C. Laravel Policy (Authorization)
```
app/Policies/
├── EventPolicy.php       # Hanya vendor pemilik yang bisa edit/delete
├── OrderPolicy.php       # Customer lihat order sendiri, vendor lihat order event-nya
├── VendorPolicy.php      # User hanya bisa edit vendor sendiri
├── TicketPolicy.php      # Vendor hanya bisa redeem tiket event-nya
└── SkuPolicy.php         # Vendor hanya bisa manage SKU event-nya
```

#### D. API Resource & Response Consistency
Gunakan Laravel API Resource untuk response yang konsisten:

```
app/Http/Resources/
├── EventResource.php
├── EventCollection.php
├── OrderResource.php
├── TicketResource.php
├── VendorResource.php
├── UserResource.php
└── ReviewResource.php
```

Response format standar:
```json
{
  "success": true,
  "message": "Order created successfully",
  "data": { ... },
  "meta": {
    "current_page": 1,
    "total": 100,
    "per_page": 15
  }
}
```

---

### 10.2 Security Hardening

#### A. Authentication & Authorization
```
Implementasi yang dibutuhkan:
├── Rate limiting pada login (max 5 attempt/menit)
├── Email verification setelah register
├── Password reset flow
├── Token expiration (set di sanctum.php, misal 30 hari)
├── Refresh token mechanism
├── Role & Permission (spatie/laravel-permission)
│   ├── Roles: super_admin, admin, vendor, customer
│   └── Permissions: manage_events, manage_orders, verify_vendor, dll
├── Two-factor authentication (2FA) untuk vendor & admin
└── Session management (logout from all devices)
```

#### B. Input Validation & Sanitization
```
Yang harus ditambahkan:
├── File upload validation (max size, mime type, dimensions)
│   └── 'image' => 'required|image|mimes:jpg,png,webp|max:2048'
├── XSS prevention (gunakan e() di Blade, strip_tags di input)
├── SQL injection protection (sudah pakai Eloquent, tapi audit raw query)
├── CSRF protection untuk web routes
├── Request throttling per endpoint
│   ├── Login: 5 req/min
│   ├── Register: 3 req/min
│   ├── Order create: 10 req/min
│   └── General API: 60 req/min
└── Input length limits pada semua field
```

#### C. Data Protection
```
├── Encrypt sensitive data (payment info, personal data)
├── Hash semua password dengan bcrypt (sudah ada)
├── Mask sensitive data di logs (card numbers, tokens)
├── GDPR-ready: user data export & deletion
├── Audit trail untuk semua aksi penting
│   └── Package: owen-it/laravel-auditing
└── Backup database otomatis (spatie/laravel-backup)
```

---

### 10.3 Payment & Financial

#### A. Midtrans Integration yang Proper
```
Yang harus diimplementasi:
├── Webhook handler (POST /api/payment/notification)
│   ├── Verify signature hash dari Midtrans
│   ├── Update order status berdasarkan transaction_status
│   ├── Handle: capture, settlement, pending, deny, cancel, expire, refund
│   └── Idempotent (handle duplicate notification)
├── Order ID yang unique
│   └── Format: "ORD-{timestamp}-{random}" atau UUID
├── Payment expiry (misal 24 jam)
│   └── Scheduled job untuk expire pending orders
├── Retry mechanism untuk failed API calls
├── Payment receipt generation
└── Reconciliation report (daily matching Midtrans vs DB)
```

#### B. Commission & Settlement
```
Platform commission model:
├── Configurable rate per vendor (default 10-15%)
├── Commission calculation pada setiap order success
├── Settlement period (misal: T+7 hari setelah event selesai)
├── Vendor payout tracking
│   ├── Status: pending, processing, completed
│   └── Minimum payout threshold (misal Rp 100.000)
├── Tax calculation (PPN 11%)
└── Financial reports (daily, weekly, monthly)
```

#### C. Refund System
```
├── Refund policy per event (configurable by vendor)
│   ├── Full refund: > 7 hari sebelum event
│   ├── 50% refund: 3-7 hari sebelum event
│   └── No refund: < 3 hari sebelum event
├── Refund request flow
│   ├── Customer request → Admin review → Process via Midtrans
│   └── Auto-refund untuk cancelled events
├── Refund to original payment method
└── Refund tracking & notification
```

---

### 10.4 Performance & Scalability

#### A. Database Optimization
```
├── Indexing strategy
│   ├── events: INDEX(vendor_id, event_category_id, start_date)
│   ├── tickets: INDEX(sku_id, status), INDEX(ticket_code)
│   ├── orders: INDEX(user_id, status_payment), INDEX(event_id)
│   └── skus: INDEX(event_id, day_type)
├── Query optimization
│   ├── Eager loading (with()) di semua relationship query
│   ├── Select specific columns, bukan SELECT *
│   ├── Pagination untuk list endpoints (15-25 per page)
│   └── Chunk processing untuk bulk operations
├── Database driver
│   ├── Development: SQLite (current)
│   ├── Production: MySQL 8 / PostgreSQL 15+
│   └── Connection pooling untuk high traffic
└── Read replica untuk query-heavy operations (optional)
```

#### B. Caching Strategy
```
├── Cache driver: Redis (production)
├── Cache layers:
│   ├── Event list: 5 menit (invalidate on create/update)
│   ├── Event categories: 1 jam (rarely changes)
│   ├── Event detail: 10 menit
│   ├── Vendor profile: 15 menit
│   ├── Search results: 5 menit
│   └── Dashboard stats: 5 menit
├── HTTP caching headers (ETag, Last-Modified)
└── Query result caching (remember())
```

#### C. Queue & Background Jobs
```
├── Queue driver: Redis (production)
├── Jobs:
│   ├── SendOrderConfirmationEmail
│   ├── SendTicketEmail (with QR code)
│   ├── ProcessPaymentNotification
│   ├── GenerateVendorReport
│   ├── ExpirePendingOrders (scheduled)
│   ├── SendEventReminder (scheduled, H-1)
│   ├── ProcessRefund
│   └── SyncMidtransTransactions (scheduled, daily)
├── Queue monitoring: Laravel Horizon
└── Failed job handling & retry policy
```

#### D. File Storage
```
├── Development: local disk (current)
├── Production: S3 / DigitalOcean Spaces / Cloudflare R2
├── Image processing:
│   ├── Resize on upload (thumbnail, medium, large)
│   ├── WebP conversion untuk performance
│   ├── Max file size: 2MB per image
│   └── CDN untuk serving images
├── Organized path: events/{vendor_id}/{event_id}/{filename}
└── Temporary upload untuk draft events
```

---

### 10.5 Monitoring, Logging & Observability

#### A. Error Tracking
```
├── Sentry integration (real-time error tracking)
│   └── composer require sentry/sentry-laravel
├── Custom error pages (404, 500, 503)
├── Exception handler yang proper
│   ├── API: JSON error response
│   └── Web: Error page
└── Alert notification (Slack/Telegram) untuk critical errors
```

#### B. Logging Strategy
```
├── Structured logging (JSON format)
├── Log levels:
│   ├── ERROR: Payment failures, system errors
│   ├── WARNING: Failed login attempts, rate limit hits
│   ├── INFO: Order created, payment received, ticket redeemed
│   └── DEBUG: API requests/responses (dev only)
├── Audit log untuk:
│   ├── Semua payment transactions
│   ├── Vendor verification status changes
│   ├── Admin actions
│   └── User data changes
├── Log rotation (daily, max 30 hari)
└── Centralized logging (ELK Stack / Grafana Loki) untuk production
```

#### C. Health Checks & Monitoring
```
├── Health endpoint: GET /up (sudah ada)
├── Extended health checks:
│   ├── Database connectivity
│   ├── Redis connectivity
│   ├── Queue worker status
│   ├── Disk space
│   └── Midtrans API status
├── Uptime monitoring (UptimeRobot / Better Stack)
├── Performance monitoring (Laravel Telescope / Debugbar dev only)
└── Scheduled task monitoring
```

---

### 10.6 DevOps & Deployment

#### A. Environment Setup
```
├── .env management
│   ├── .env.example (documented, up-to-date)
│   ├── .env.testing (for CI/CD)
│   └── Production: environment variables (tidak file .env)
├── Docker setup
│   ├── Dockerfile (PHP 8.3 + extensions)
│   ├── docker-compose.yml
│   │   ├── app (Laravel)
│   │   ├── nginx (web server)
│   │   ├── mysql (database)
│   │   ├── redis (cache + queue)
│   │   └── meilisearch (full-text search, optional)
│   └── docker-compose.prod.yml
└── Laravel Sail (sudah ada, untuk local dev)
```

#### B. CI/CD Pipeline
```
GitHub Actions workflow:
├── On PR:
│   ├── Run PHP CS Fixer (pint)
│   ├── Run PHPStan (static analysis)
│   ├── Run tests (phpunit)
│   └── Build assets (vite)
├── On merge to main:
│   ├── Run all tests
│   ├── Build production assets
│   ├── Deploy to staging
│   └── Run smoke tests
├── On release tag:
│   ├── Deploy to production
│   ├── Run migrations
│   ├── Clear & warm caches
│   └── Notify team (Slack)
└── Scheduled:
    ├── Security audit (composer audit)
    └── Dependency updates check
```

#### C. Production Server
```
Rekomendasi setup:
├── Server: VPS (DigitalOcean / AWS EC2 / Hetzner)
│   ├── Minimum: 2 vCPU, 4GB RAM
│   └── Recommended: 4 vCPU, 8GB RAM
├── Web server: Nginx + PHP-FPM (PHP 8.3)
├── Database: MySQL 8 (managed / self-hosted)
├── Cache/Queue: Redis 7
├── SSL: Let's Encrypt (auto-renew)
├── Firewall: UFW (only 80, 443, 22)
├── Process manager: Supervisor (queue workers)
├── Deployment: Laravel Forge / Deployer / GitHub Actions
└── Backup: automated daily (DB + uploads)
```

---

### 10.7 Testing Strategy

```
tests/
├── Unit/
│   ├── Services/
│   │   ├── OrderServiceTest.php
│   │   ├── TicketServiceTest.php
│   │   ├── PaymentServiceTest.php
│   │   └── CommissionServiceTest.php
│   ├── Models/
│   │   ├── EventTest.php
│   │   ├── OrderTest.php
│   │   └── TicketTest.php
│   └── Helpers/
│       └── UniqueCodeHelperTest.php
│
├── Feature/
│   ├── Auth/
│   │   ├── LoginTest.php
│   │   ├── RegisterTest.php
│   │   └── GoogleLoginTest.php
│   ├── Api/
│   │   ├── EventApiTest.php
│   │   ├── OrderApiTest.php
│   │   ├── TicketApiTest.php
│   │   ├── VendorApiTest.php
│   │   └── PaymentWebhookTest.php
│   └── Dashboard/
│       ├── AdminDashboardTest.php
│       └── VendorDashboardTest.php
│
└── Integration/
    ├── OrderFlowTest.php          # Full order → payment → ticket flow
    ├── VendorOnboardingTest.php   # Register → create vendor → verify
    └── TicketRedemptionTest.php   # Order → get ticket → redeem

Coverage target: minimum 80% untuk critical paths (order, payment, ticket)
```

---

### 10.8 API Documentation

```
Rekomendasi:
├── Tool: Scramble (dedoc/scramble) - auto-generate dari code
│   └── composer require dedoc/scramble
├── Atau manual: OpenAPI 3.0 spec
├── Endpoint: GET /docs/api
├── Fitur yang harus didokumentasi:
│   ├── Semua endpoints dengan request/response example
│   ├── Authentication flow
│   ├── Error codes & messages
│   ├── Rate limiting info
│   ├── Webhook payload format
│   └── Pagination format
└── Postman collection export untuk tim mobile
```

---

### 10.9 SEO & Marketing (Landing Page)

```
├── Technical SEO:
│   ├── Meta tags (title, description, og:image) per halaman
│   ├── Structured data (Schema.org - Event, Offer, Review)
│   ├── Sitemap.xml (auto-generate)
│   ├── robots.txt
│   ├── Canonical URLs
│   ├── Breadcrumbs
│   └── Page speed optimization (Core Web Vitals)
│
├── Social Media:
│   ├── Open Graph tags untuk sharing
│   ├── Twitter Card tags
│   └── WhatsApp preview optimization
│
├── Analytics:
│   ├── Google Analytics 4
│   ├── Google Search Console
│   ├── Facebook Pixel (optional)
│   └── Custom event tracking (view event, add to cart, purchase)
│
└── Content:
    ├── Blog / artikel wisata (SEO content)
    ├── FAQ page
    ├── Terms & conditions
    └── Privacy policy
```

---

### 10.10 Kompetitor & Benchmark

| Fitur | Traveloka Xperience | Tiket.com | Klook | Sistem Ini (Target) |
|-------|---------------------|-----------|-------|---------------------|
| Multi-vendor | Ya | Ya | Ya | Ya |
| Review & Rating | Ya | Ya | Ya | Belum (Phase 4) |
| Instant Booking | Ya | Ya | Ya | Ya (sudah) |
| Refund Policy | Ya | Ya | Ya | Belum (Phase 4) |
| Multi-payment | Ya | Ya | Ya | Midtrans (multi) |
| QR Ticket | Ya | Ya | Ya | Belum (Phase 4) |
| Promo/Voucher | Ya | Ya | Ya | Belum (Phase 4) |
| Map Integration | Ya | Ya | Ya | Belum (Phase 4) |
| Mobile App | Ya | Ya | Ya | Flutter (separate) |
| Admin Dashboard | Internal | Internal | Internal | Phase 3 |
| Vendor Dashboard | Ya | Ya | Ya | Phase 3 |
| Chat Support | Ya | Ya | Ya | Phase 5+ |
| Recommendation | Ya | Ya | Ya | Phase 5+ |
| Multi-language | Ya | Ya | Ya | Phase 5+ |

---

### 10.11 Checklist Production-Ready

#### Pre-Launch
- [ ] Semua bug critical sudah fixed
- [ ] Authorization & policy di semua endpoint
- [ ] Midtrans webhook handler + signature verification
- [ ] Rate limiting di semua endpoint
- [ ] Input validation & sanitization
- [ ] File upload validation (type, size)
- [ ] Error handling yang proper (try-catch, custom exceptions)
- [ ] DB transactions untuk operasi multi-step
- [ ] Logging & audit trail
- [ ] Environment variables documented
- [ ] Database indexes optimized
- [ ] Redis untuk cache & queue
- [ ] SSL certificate
- [ ] CORS configured
- [ ] API documentation

#### Post-Launch
- [ ] Monitoring & alerting setup
- [ ] Backup automation (DB + files)
- [ ] Performance baseline established
- [ ] Security audit completed
- [ ] Load testing passed (target: 100 concurrent users)
- [ ] Disaster recovery plan documented
- [ ] Runbook untuk common operations
- [ ] On-call rotation (jika tim)
