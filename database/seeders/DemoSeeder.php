<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Order;
use App\Models\OrderTicket;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoSeeder extends Seeder
{
    /**
     * Full demo data seeder — membuat system terlihat matang dan production-ready.
     *
     * Skenario yang di-cover:
     * - 10 users (5 vendor, 5 buyer)
     * - 5 vendors (4 approved, 1 pending)
     * - 8 event categories
     * - 20 events (past, ongoing, upcoming)
     * - 2-4 SKUs per event
     * - Tickets auto-generated dari SKU stock
     * - 15+ orders dengan berbagai status (pending, success, cancel)
     * - Order tickets yang terhubung
     * - Beberapa tiket sudah di-redeem (event lalu)
     *
     * Login semua user: password = password
     */
    public function run(): void
    {
        // Available images to cycle through
        $images = [
            '1772976845.jpg', '1772976886.jpg', '1772976900.jpg',
            '1772982456.jpg', '1772983672.jpg', '1772983677.jpg',
            '1772983679.jpg', '1772983922.jpg', '1772983924.jpg',
            '1772983952.jpg', '1772984001.jpg', '1772984005.jpg',
            '1772984007.jpg', '1772984095.jpg', '1772984097.jpg',
            '1772984668.jpg', '1772984669.jpg', '1772984670.jpg',
            '1772984685.jpg', '1772984687.jpg',
        ];

        // ================================================================
        // 1. USERS — 10 users (5 vendor + 5 buyer)
        // ================================================================
        $password = Hash::make('password');

        // --- Super Admin ---
        User::create([
            'name' => 'Admin JagoEvent',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => $password,
            'phone' => '081200000000',
            'is_vendor' => 0,
            'created_at' => now()->subYear(),
            'updated_at' => now()->subYear(),
        ]);

        $users = User::insert([
            // --- Vendors ---
            [
                'name' => 'Andi Pratama',
                'email' => 'andi@JagoEvent.com',
                'email_verified_at' => now(),
                'password' => $password,
                'phone' => '081234567001',
                'is_vendor' => 1,
                'created_at' => now()->subMonths(6),
                'updated_at' => now()->subMonths(6),
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti@JagoEvent.com',
                'email_verified_at' => now(),
                'password' => $password,
                'phone' => '081234567002',
                'is_vendor' => 1,
                'created_at' => now()->subMonths(5),
                'updated_at' => now()->subMonths(5),
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@JagoEvent.com',
                'email_verified_at' => now(),
                'password' => $password,
                'phone' => '081234567003',
                'is_vendor' => 1,
                'created_at' => now()->subMonths(4),
                'updated_at' => now()->subMonths(4),
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@JagoEvent.com',
                'email_verified_at' => now(),
                'password' => $password,
                'phone' => '081234567004',
                'is_vendor' => 1,
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subMonths(3),
            ],
            [
                'name' => 'Rizky Firmansyah',
                'email' => 'rizky@JagoEvent.com',
                'email_verified_at' => now(),
                'password' => $password,
                'phone' => '081234567005',
                'is_vendor' => 1,
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonths(2),
            ],
            // --- Buyers ---
            [
                'name' => 'Mega Putri',
                'email' => 'mega@gmail.com',
                'email_verified_at' => now(),
                'password' => $password,
                'phone' => '081234567006',
                'is_vendor' => 0,
                'created_at' => now()->subMonths(4),
                'updated_at' => now()->subMonths(4),
            ],
            [
                'name' => 'Fajar Nugroho',
                'email' => 'fajar@gmail.com',
                'email_verified_at' => now(),
                'password' => $password,
                'phone' => '081234567007',
                'is_vendor' => 0,
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subMonths(3),
            ],
            [
                'name' => 'Anisa Widya',
                'email' => 'anisa@gmail.com',
                'email_verified_at' => now(),
                'password' => $password,
                'phone' => '081234567008',
                'is_vendor' => 0,
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subMonths(3),
            ],
            [
                'name' => 'Rendi Kurniawan',
                'email' => 'rendi@gmail.com',
                'email_verified_at' => now(),
                'password' => $password,
                'phone' => '081234567009',
                'is_vendor' => 0,
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonths(2),
            ],
            [
                'name' => 'Tari Amelia',
                'email' => 'tari@gmail.com',
                'email_verified_at' => now(),
                'password' => $password,
                'phone' => '081234567010',
                'is_vendor' => 0,
                'created_at' => now()->subMonths(1),
                'updated_at' => now()->subMonths(1),
            ],
        ]);

        // Re-fetch user IDs
        $vendorUsers = User::where('is_vendor', 1)->orderBy('id')->pluck('id')->toArray();
        $buyerUsers = User::where('is_vendor', 0)->orderBy('id')->pluck('id')->toArray();

        // ================================================================
        // 2. VENDORS — 5 vendors (4 approved, 1 pending)
        // ================================================================
        $vendorRecords = [
            [
                'user_id' => $vendorUsers[0],
                'name' => 'JagoEvent Production',
                'description' => 'Event organizer profesional untuk konser musik, festival, dan acara hiburan berskala nasional. Berpengalaman menangani event hingga 10.000 peserta.',
                'location' => 'Jl. Sudirman No. 123, Senayan',
                'phone' => '021-5551234',
                'city' => 'Jakarta',
                'verify_status' => 'approved',
                'created_at' => now()->subMonths(6),
                'updated_at' => now()->subMonths(6),
            ],
            [
                'user_id' => $vendorUsers[1],
                'name' => 'Bali Paradise Events',
                'description' => 'Spesialis event outdoor di Bali — beach party, sunset dinner, water sports festival. Kami hadirkan pengalaman tropis tak terlupakan.',
                'location' => 'Jl. Pantai Kuta No. 45, Kuta',
                'phone' => '0361-761234',
                'city' => 'Bali',
                'verify_status' => 'approved',
                'created_at' => now()->subMonths(5),
                'updated_at' => now()->subMonths(5),
            ],
            [
                'user_id' => $vendorUsers[2],
                'name' => 'Jogja Culture Festival',
                'description' => 'Penyelenggara acara budaya dan seni di Yogyakarta. Festival wayang, batik exhibition, dan kuliner tradisional Jawa.',
                'location' => 'Jl. Malioboro No. 78, Gedongtengen',
                'phone' => '0274-512345',
                'city' => 'Yogyakarta',
                'verify_status' => 'approved',
                'created_at' => now()->subMonths(4),
                'updated_at' => now()->subMonths(4),
            ],
            [
                'user_id' => $vendorUsers[3],
                'name' => 'Bandung Adventure Co.',
                'description' => 'Petualangan seru di alam Bandung — hiking, camping, dan outdoor team building. Cocok untuk keluarga dan korporat.',
                'location' => 'Jl. Dago Atas No. 200, Coblong',
                'phone' => '022-2501234',
                'city' => 'Bandung',
                'verify_status' => 'approved',
                'created_at' => now()->subMonths(3),
                'updated_at' => now()->subMonths(3),
            ],
            [
                'user_id' => $vendorUsers[4],
                'name' => 'Surabaya Sport Arena',
                'description' => 'Event olahraga dan e-sport di Surabaya. Marathon, futsal tournament, dan gaming competition.',
                'location' => 'Jl. Basuki Rahmat No. 99, Tegalsari',
                'phone' => '031-5321234',
                'city' => 'Surabaya',
                'verify_status' => 'pending',
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonths(2),
            ],
        ];

        foreach ($vendorRecords as $v) {
            Vendor::create($v);
        }

        $vendors = Vendor::where('verify_status', 'approved')->orderBy('id')->pluck('id')->toArray();
        $pendingVendor = Vendor::where('verify_status', 'pending')->first()->id;

        // ================================================================
        // 3. EVENT CATEGORIES — 8 categories
        // ================================================================
        DB::table('event_categories')->insert([
            ['name' => 'Musik', 'description' => 'Konser, festival musik, dan pertunjukan live dari artis lokal maupun internasional.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pantai', 'description' => 'Aktivitas dan event seru di tepi pantai — beach party, surfing, dan sunset gathering.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gunung', 'description' => 'Pendakian, hiking, camping, dan petualangan alam pegunungan.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Budaya', 'description' => 'Festival budaya, pameran seni, pertunjukan tradisional, dan warisan lokal.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Olahraga', 'description' => 'Kompetisi olahraga, marathon, turnamen, dan fitness event.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Kuliner', 'description' => 'Festival makanan, food bazaar, cooking class, dan wisata kuliner.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Teknologi', 'description' => 'Seminar IT, hackathon, workshop coding, dan tech conference.', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Keluarga', 'description' => 'Acara ramah anak dan keluarga — playground, karnaval, dan edukasi.', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // ================================================================
        // 4. EVENTS — 20 events (6 past, 4 ongoing, 10 upcoming)
        // ================================================================
        $eventData = [
            // === PAST EVENTS (sudah selesai) ===
            [
                'vendor_id' => $vendors[0], 'event_category_id' => 1, // Musik
                'name' => 'Jakarta Music Festival 2026',
                'description' => 'Festival musik terbesar di Jakarta dengan line-up artis top Indonesia. Dari pop, rock, hingga EDM — semua genre ada di sini. Panggung utama di GBK dengan kapasitas 15.000 penonton.',
                'image' => $images[0],
                'start_date' => now()->subMonths(3)->format('Y-m-d'),
                'end_date' => now()->subMonths(3)->addDays(2)->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[1], 'event_category_id' => 2, // Pantai
                'name' => 'Bali Beach Sunset Party',
                'description' => 'Pesta pantai mewah di Kuta Beach dengan DJ internasional, cocktail bar, dan BBQ seafood. Nikmati sunset terbaik di Bali sambil berdansa di atas pasir.',
                'image' => $images[1],
                'start_date' => now()->subMonths(2)->format('Y-m-d'),
                'end_date' => now()->subMonths(2)->addDay()->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[2], 'event_category_id' => 4, // Budaya
                'name' => 'Jogja Wayang Night',
                'description' => 'Pertunjukan wayang kulit semalam suntuk oleh dalang Ki Manteb Sudarsono. Dilengkapi gamelan live dan hidangan khas Jawa.',
                'image' => $images[2],
                'start_date' => now()->subMonths(2)->subDays(10)->format('Y-m-d'),
                'end_date' => null,
            ],
            [
                'vendor_id' => $vendors[3], 'event_category_id' => 3, // Gunung
                'name' => 'Bandung Highland Trail Run',
                'description' => 'Trail running 21K melewati perkebunan teh dan hutan pinus Bandung Utara. Kategori: 5K Fun Run, 10K, 21K Half Marathon.',
                'image' => $images[3],
                'start_date' => now()->subMonth()->subDays(15)->format('Y-m-d'),
                'end_date' => null,
            ],
            [
                'vendor_id' => $vendors[0], 'event_category_id' => 6, // Kuliner
                'name' => 'Jakarta Food Carnival',
                'description' => 'Festival kuliner terbesar dengan 100+ booth makanan dari seluruh Nusantara. Live cooking demo oleh chef terkenal, eating competition, dan food truck rally.',
                'image' => $images[4],
                'start_date' => now()->subMonth()->subDays(5)->format('Y-m-d'),
                'end_date' => now()->subMonth()->subDays(3)->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[1], 'event_category_id' => 5, // Olahraga
                'name' => 'Bali Surf Competition 2026',
                'description' => 'Kompetisi surfing internasional di Uluwatu. Open category dan professional division. Total hadiah Rp 500 juta.',
                'image' => $images[5],
                'start_date' => now()->subMonth()->format('Y-m-d'),
                'end_date' => now()->subMonth()->addDays(3)->format('Y-m-d'),
            ],

            // === ONGOING EVENTS (sedang berlangsung) ===
            [
                'vendor_id' => $vendors[2], 'event_category_id' => 4, // Budaya
                'name' => 'Jogja Art & Batik Exhibition',
                'description' => 'Pameran seni kontemporer dan batik dari 50+ seniman Yogyakarta. Workshop batik tulis, lukis kanvas, dan craft market setiap hari.',
                'image' => $images[6],
                'start_date' => now()->subDays(3)->format('Y-m-d'),
                'end_date' => now()->addDays(11)->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[0], 'event_category_id' => 7, // Teknologi
                'name' => 'DevFest Jakarta 2026',
                'description' => 'Tech conference 3 hari untuk developer. Talk dari Google, Meta, dan startup unicorn Indonesia. Workshop AI/ML, cloud, dan mobile development.',
                'image' => $images[7],
                'start_date' => now()->subDay()->format('Y-m-d'),
                'end_date' => now()->addDays(2)->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[3], 'event_category_id' => 8, // Keluarga
                'name' => 'Bandung Family Fun Day',
                'description' => 'Hari keluarga di Taman Hutan Raya Juanda. Playground, mini zoo, face painting, dan picnic area. Gratis untuk anak di bawah 3 tahun.',
                'image' => $images[8],
                'start_date' => now()->subDays(1)->format('Y-m-d'),
                'end_date' => now()->addDays(6)->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[1], 'event_category_id' => 6, // Kuliner
                'name' => 'Bali Seafood Festival',
                'description' => 'Festival seafood di Jimbaran Bay. Fresh lobster, crab, dan ikan bakar langsung dari nelayan. Live music dan fire dance performance.',
                'image' => $images[9],
                'start_date' => now()->format('Y-m-d'),
                'end_date' => now()->addDays(4)->format('Y-m-d'),
            ],

            // === UPCOMING EVENTS (akan datang) ===
            [
                'vendor_id' => $vendors[0], 'event_category_id' => 1, // Musik
                'name' => 'Rock In Jakarta 2026',
                'description' => 'Festival rock terbesar! Line-up: Slank, GIGI, Dewa 19 Reunion, J-Rocks, dan special guest international band. 2 stage, 12 jam non-stop.',
                'image' => $images[10],
                'start_date' => now()->addWeeks(2)->format('Y-m-d'),
                'end_date' => now()->addWeeks(2)->addDay()->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[1], 'event_category_id' => 2, // Pantai
                'name' => 'Lombok Island Hopping Adventure',
                'description' => 'Trip island hopping ke 3 gili (Trawangan, Meno, Air). Snorkeling, diving spot, sunset kayaking, dan beach BBQ dinner.',
                'image' => $images[11],
                'start_date' => now()->addWeeks(3)->format('Y-m-d'),
                'end_date' => now()->addWeeks(3)->addDays(2)->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[2], 'event_category_id' => 4, // Budaya
                'name' => 'Festival Ramayana Prambanan',
                'description' => 'Pertunjukan Ballet Ramayana di panggung terbuka Candi Prambanan. Tarian kolosal 200+ penari dengan latar candi bersejarah di bawah cahaya bulan.',
                'image' => $images[12],
                'start_date' => now()->addMonth()->format('Y-m-d'),
                'end_date' => now()->addMonth()->addDays(2)->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[3], 'event_category_id' => 3, // Gunung
                'name' => 'Camping & Stargazing Tangkuban Parahu',
                'description' => 'Camping mewah (glamping) di kaki Tangkuban Parahu. Teleskop stargazing, bonfire, acoustic music, dan trekking pagi ke kawah.',
                'image' => $images[13],
                'start_date' => now()->addMonth()->addWeek()->format('Y-m-d'),
                'end_date' => now()->addMonth()->addWeek()->addDay()->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[0], 'event_category_id' => 5, // Olahraga
                'name' => 'Jakarta Marathon 2026',
                'description' => 'Marathon internasional melalui landmark Jakarta — Monas, Istana, Sudirman-Thamrin. Kategori: 5K, 10K, 21K Half, 42K Full Marathon.',
                'image' => $images[14],
                'start_date' => now()->addMonths(2)->format('Y-m-d'),
                'end_date' => null,
            ],
            [
                'vendor_id' => $vendors[2], 'event_category_id' => 6, // Kuliner
                'name' => 'Gudeg Festival Yogyakarta',
                'description' => 'Festival gudeg terlengkap! 30+ varian gudeg dari seluruh penjuru Jogja. Lomba masak gudeg, mukbang challenge, dan tur wisata kuliner.',
                'image' => $images[15],
                'start_date' => now()->addMonths(2)->addWeek()->format('Y-m-d'),
                'end_date' => now()->addMonths(2)->addWeek()->addDays(2)->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[3], 'event_category_id' => 7, // Teknologi
                'name' => 'Flutter Forward Bandung',
                'description' => 'Conference Flutter & Dart developer. Workshop hands-on, code lab, dan networking session. Speaker dari Google Developer Expert Indonesia.',
                'image' => $images[16],
                'start_date' => now()->addMonths(2)->addWeeks(2)->format('Y-m-d'),
                'end_date' => now()->addMonths(2)->addWeeks(2)->addDay()->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[1], 'event_category_id' => 8, // Keluarga
                'name' => 'Bali Kids Carnival',
                'description' => 'Karnaval anak terbesar di Bali! Magic show, puppet theater, trampoline park, dan arts & crafts workshop. Semua umur welcome.',
                'image' => $images[17],
                'start_date' => now()->addMonths(3)->format('Y-m-d'),
                'end_date' => now()->addMonths(3)->addDays(2)->format('Y-m-d'),
            ],
            [
                'vendor_id' => $vendors[0], 'event_category_id' => 1, // Musik
                'name' => 'Jazz Night Jakarta',
                'description' => 'Malam jazz eksklusif di rooftop hotel bintang 5. Featuring Tompi, Andien, dan Indra Lesmana Trio. Dinner & cocktail included.',
                'image' => $images[18],
                'start_date' => now()->addMonths(3)->addWeek()->format('Y-m-d'),
                'end_date' => null,
            ],
            [
                'vendor_id' => $vendors[3], 'event_category_id' => 5, // Olahraga
                'name' => 'Bandung Mountain Bike Challenge',
                'description' => 'Kompetisi downhill mountain bike di jalur hutan pinus Lembang. Kategori: Amateur, Pro, dan e-Bike. Hadiah total Rp 200 juta.',
                'image' => $images[19],
                'start_date' => now()->addMonths(4)->format('Y-m-d'),
                'end_date' => now()->addMonths(4)->addDay()->format('Y-m-d'),
            ],
        ];

        $eventIds = [];
        foreach ($eventData as $e) {
            $event = Event::create(array_merge($e, [
                'created_at' => now()->subMonths(2),
                'updated_at' => now()->subMonths(2),
            ]));
            $eventIds[] = $event->id;
        }

        // ================================================================
        // 5. SKUs — 2-4 per event, realistic pricing
        // ================================================================
        $skuTemplates = [
            // [name, category, price, stock, day_type]
            'musik_big' => [
                ['Regular', 'Regular', 150000, 200, 'Weekend'],
                ['VIP', 'VIP', 500000, 50, 'Weekend'],
                ['VVIP', 'VVIP', 1500000, 20, 'Weekend'],
                ['Early Bird', 'Promo', 100000, 100, 'Weekend'],
            ],
            'musik_small' => [
                ['Regular', 'Regular', 250000, 100, 'Weekend'],
                ['VIP', 'VIP', 750000, 30, 'Weekend'],
            ],
            'pantai' => [
                ['Dewasa', 'Regular', 200000, 100, 'Weekend'],
                ['Anak-anak (5-12 thn)', 'Regular', 100000, 50, 'Weekend'],
                ['VIP Couple', 'VIP', 750000, 20, 'Weekend'],
            ],
            'gunung' => [
                ['Regular', 'Regular', 175000, 80, 'Weekend'],
                ['VIP (Include Gear)', 'VIP', 450000, 30, 'Weekend'],
            ],
            'budaya' => [
                ['Umum', 'Regular', 75000, 150, 'Weekday'],
                ['Premium (Row 1-5)', 'Premium', 250000, 40, 'Weekday'],
                ['VIP (Meet & Greet)', 'VIP', 500000, 15, 'Weekday'],
            ],
            'olahraga_run' => [
                ['5K Fun Run', 'Regular', 150000, 500, 'Weekend'],
                ['10K', 'Regular', 200000, 300, 'Weekend'],
                ['21K Half Marathon', 'Premium', 350000, 200, 'Weekend'],
                ['42K Full Marathon', 'VIP', 500000, 100, 'Weekend'],
            ],
            'olahraga' => [
                ['Penonton Reguler', 'Regular', 50000, 200, 'Weekend'],
                ['Peserta', 'Regular', 200000, 80, 'Weekend'],
                ['VIP Penonton', 'VIP', 150000, 30, 'Weekend'],
            ],
            'kuliner' => [
                ['Tiket Masuk', 'Regular', 35000, 300, 'Weekend'],
                ['Tiket + Voucher 100K', 'Bundle', 120000, 100, 'Weekend'],
                ['VIP All You Can Eat', 'VIP', 350000, 30, 'Weekend'],
            ],
            'teknologi' => [
                ['Conference Pass', 'Regular', 200000, 200, 'Weekday'],
                ['Workshop + Conference', 'Premium', 500000, 50, 'Weekday'],
                ['VIP (All Access)', 'VIP', 1000000, 20, 'Weekday'],
            ],
            'keluarga' => [
                ['Dewasa', 'Regular', 75000, 200, 'Weekend'],
                ['Anak (3-12 thn)', 'Regular', 50000, 150, 'Weekend'],
                ['Paket Keluarga (2+2)', 'Bundle', 200000, 50, 'Weekend'],
            ],
        ];

        // Map event index to SKU template
        $eventSkuMap = [
            0 => 'musik_big',       // Jakarta Music Festival
            1 => 'pantai',          // Bali Beach Sunset Party
            2 => 'budaya',          // Jogja Wayang Night
            3 => 'gunung',          // Bandung Highland Trail Run
            4 => 'kuliner',         // Jakarta Food Carnival
            5 => 'olahraga',        // Bali Surf Competition
            6 => 'budaya',          // Jogja Art & Batik Exhibition
            7 => 'teknologi',       // DevFest Jakarta
            8 => 'keluarga',        // Bandung Family Fun Day
            9 => 'kuliner',         // Bali Seafood Festival
            10 => 'musik_big',      // Rock In Jakarta
            11 => 'pantai',         // Lombok Island Hopping
            12 => 'budaya',         // Festival Ramayana
            13 => 'gunung',         // Camping Tangkuban Parahu
            14 => 'olahraga_run',   // Jakarta Marathon
            15 => 'kuliner',        // Gudeg Festival
            16 => 'teknologi',      // Flutter Forward
            17 => 'keluarga',       // Bali Kids Carnival
            18 => 'musik_small',    // Jazz Night
            19 => 'olahraga',       // Mountain Bike Challenge
        ];

        $skusByEvent = []; // eventId => [skuId => originalStock]
        foreach ($eventSkuMap as $eventIndex => $templateKey) {
            $eventId = $eventIds[$eventIndex];
            $skusByEvent[$eventId] = [];
            foreach ($skuTemplates[$templateKey] as $tpl) {
                $sku = Sku::create([
                    'name' => $tpl[0],
                    'category' => $tpl[1],
                    'event_id' => $eventId,
                    'price' => $tpl[2],
                    'stock' => $tpl[3],
                    'day_type' => $tpl[4],
                    'created_at' => now()->subMonths(2),
                    'updated_at' => now()->subMonths(2),
                ]);
                $skusByEvent[$eventId][$sku->id] = $tpl[3]; // original stock
            }
        }

        // ================================================================
        // 6. TICKETS — auto-generate from SKU stock
        // ================================================================
        $this->command->info('Generating tickets from SKU stock...');

        $ticketsBySkuId = []; // skuId => [ticketIds]
        foreach ($skusByEvent as $eventId => $skus) {
            $event = Event::find($eventId);
            foreach ($skus as $skuId => $stock) {
                $ticketsBySkuId[$skuId] = [];
                $ticketBatch = [];
                for ($i = 0; $i < $stock; $i++) {
                    $ticketBatch[] = [
                        'sku_id' => $skuId,
                        'event_id' => $eventId,
                        'ticket_code' => 'TKT-' . Str::upper(Str::random(8)),
                        'ticket_date' => $event->start_date,
                        'status' => 'available',
                        'created_at' => now()->subMonths(2),
                        'updated_at' => now()->subMonths(2),
                    ];
                }
                // Insert in chunks for performance
                foreach (array_chunk($ticketBatch, 100) as $chunk) {
                    Ticket::insert($chunk);
                }
                // Collect ticket IDs
                $ticketsBySkuId[$skuId] = Ticket::where('sku_id', $skuId)
                    ->where('status', 'available')
                    ->pluck('id')
                    ->toArray();
            }
        }

        $this->command->info('Tickets generated: ' . Ticket::count());

        // ================================================================
        // 7. ORDERS & ORDER_TICKETS — realistic buyer activity
        // ================================================================
        $this->command->info('Creating orders...');

        $orderScenarios = [
            // PAST EVENTS — orders sukses & tiket sudah redeem
            // Event 0: Jakarta Music Festival (past) — banyak pembeli
            ['buyer' => 0, 'event_idx' => 0, 'sku_offset' => 0, 'qty' => 3, 'status' => 'success', 'redeem' => true],
            ['buyer' => 1, 'event_idx' => 0, 'sku_offset' => 1, 'qty' => 2, 'status' => 'success', 'redeem' => true],
            ['buyer' => 2, 'event_idx' => 0, 'sku_offset' => 0, 'qty' => 4, 'status' => 'success', 'redeem' => true],
            ['buyer' => 3, 'event_idx' => 0, 'sku_offset' => 3, 'qty' => 2, 'status' => 'success', 'redeem' => false], // beli tapi ga datang
            ['buyer' => 4, 'event_idx' => 0, 'sku_offset' => 2, 'qty' => 1, 'status' => 'success', 'redeem' => true],

            // Event 1: Bali Beach Sunset Party (past)
            ['buyer' => 0, 'event_idx' => 1, 'sku_offset' => 2, 'qty' => 1, 'status' => 'success', 'redeem' => true],
            ['buyer' => 2, 'event_idx' => 1, 'sku_offset' => 0, 'qty' => 2, 'status' => 'success', 'redeem' => true],
            ['buyer' => 4, 'event_idx' => 1, 'sku_offset' => 1, 'qty' => 3, 'status' => 'cancel', 'redeem' => false],

            // Event 2: Jogja Wayang Night (past)
            ['buyer' => 1, 'event_idx' => 2, 'sku_offset' => 0, 'qty' => 5, 'status' => 'success', 'redeem' => true],
            ['buyer' => 3, 'event_idx' => 2, 'sku_offset' => 2, 'qty' => 2, 'status' => 'success', 'redeem' => true],

            // Event 4: Jakarta Food Carnival (past)
            ['buyer' => 0, 'event_idx' => 4, 'sku_offset' => 1, 'qty' => 2, 'status' => 'success', 'redeem' => true],
            ['buyer' => 2, 'event_idx' => 4, 'sku_offset' => 0, 'qty' => 3, 'status' => 'success', 'redeem' => true],
            ['buyer' => 4, 'event_idx' => 4, 'sku_offset' => 2, 'qty' => 1, 'status' => 'success', 'redeem' => true],

            // ONGOING EVENTS — orders sukses, tiket sold (belum redeem)
            ['buyer' => 0, 'event_idx' => 6, 'sku_offset' => 0, 'qty' => 2, 'status' => 'success', 'redeem' => false],
            ['buyer' => 1, 'event_idx' => 7, 'sku_offset' => 2, 'qty' => 1, 'status' => 'success', 'redeem' => false],
            ['buyer' => 2, 'event_idx' => 7, 'sku_offset' => 1, 'qty' => 1, 'status' => 'success', 'redeem' => false],
            ['buyer' => 3, 'event_idx' => 8, 'sku_offset' => 2, 'qty' => 2, 'status' => 'success', 'redeem' => false],
            ['buyer' => 4, 'event_idx' => 9, 'sku_offset' => 0, 'qty' => 4, 'status' => 'success', 'redeem' => false],

            // UPCOMING EVENTS — mix pending & success
            ['buyer' => 0, 'event_idx' => 10, 'sku_offset' => 0, 'qty' => 2, 'status' => 'success', 'redeem' => false],
            ['buyer' => 1, 'event_idx' => 10, 'sku_offset' => 1, 'qty' => 2, 'status' => 'success', 'redeem' => false],
            ['buyer' => 2, 'event_idx' => 10, 'sku_offset' => 3, 'qty' => 3, 'status' => 'pending', 'redeem' => false],
            ['buyer' => 3, 'event_idx' => 11, 'sku_offset' => 0, 'qty' => 2, 'status' => 'success', 'redeem' => false],
            ['buyer' => 4, 'event_idx' => 11, 'sku_offset' => 2, 'qty' => 1, 'status' => 'pending', 'redeem' => false],
            ['buyer' => 0, 'event_idx' => 12, 'sku_offset' => 1, 'qty' => 2, 'status' => 'success', 'redeem' => false],
            ['buyer' => 1, 'event_idx' => 13, 'sku_offset' => 0, 'qty' => 3, 'status' => 'success', 'redeem' => false],
            ['buyer' => 2, 'event_idx' => 14, 'sku_offset' => 0, 'qty' => 1, 'status' => 'pending', 'redeem' => false],
            ['buyer' => 3, 'event_idx' => 14, 'sku_offset' => 2, 'qty' => 1, 'status' => 'success', 'redeem' => false],
            ['buyer' => 4, 'event_idx' => 16, 'sku_offset' => 1, 'qty' => 1, 'status' => 'success', 'redeem' => false],
            ['buyer' => 0, 'event_idx' => 17, 'sku_offset' => 2, 'qty' => 2, 'status' => 'pending', 'redeem' => false],
            ['buyer' => 1, 'event_idx' => 18, 'sku_offset' => 0, 'qty' => 2, 'status' => 'success', 'redeem' => false],

            // Cancelled orders (user abandoned)
            ['buyer' => 3, 'event_idx' => 10, 'sku_offset' => 0, 'qty' => 1, 'status' => 'cancel', 'redeem' => false],
            ['buyer' => 4, 'event_idx' => 12, 'sku_offset' => 0, 'qty' => 2, 'status' => 'cancel', 'redeem' => false],
        ];

        foreach ($orderScenarios as $scenario) {
            $eventId = $eventIds[$scenario['event_idx']];
            $event = Event::find($eventId);
            $skuIds = array_keys($skusByEvent[$eventId]);

            // Safety check — make sure sku_offset is valid
            if ($scenario['sku_offset'] >= count($skuIds)) {
                continue;
            }

            $skuId = $skuIds[$scenario['sku_offset']];
            $sku = Sku::find($skuId);
            $qty = $scenario['qty'];
            $userId = $buyerUsers[$scenario['buyer']];

            // Get available tickets for this SKU
            $availableTickets = Ticket::where('sku_id', $skuId)
                ->where('status', 'available')
                ->limit($qty)
                ->pluck('id')
                ->toArray();

            if (count($availableTickets) < $qty) {
                $qty = count($availableTickets);
                if ($qty === 0) continue;
            }

            $totalPrice = $sku->price * $qty;

            // Determine ticket status based on order status
            $ticketStatus = match ($scenario['status']) {
                'success' => $scenario['redeem'] ? 'redeem' : 'sold',
                'cancel' => 'available', // cancelled orders release tickets
                default => 'booked',
            };

            // Create order
            $order = Order::create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'quantity' => $qty,
                'total_price' => $totalPrice,
                'event_date' => $event->start_date,
                'status_payment' => $scenario['status'],
                'payment_url' => $scenario['status'] === 'pending'
                    ? 'https://app.sandbox.midtrans.com/snap/v4/redirection/' . Str::uuid()
                    : null,
                'created_at' => $scenario['status'] === 'cancel'
                    ? now()->subWeeks(rand(2, 8))
                    : now()->subWeeks(rand(1, 6)),
                'updated_at' => now()->subDays(rand(0, 14)),
            ]);

            // Create order_tickets and update ticket status
            foreach ($availableTickets as $ticketId) {
                OrderTicket::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticketId,
                    'created_at' => $order->created_at,
                    'updated_at' => $order->updated_at,
                ]);

                if ($scenario['status'] !== 'cancel') {
                    Ticket::where('id', $ticketId)->update(['status' => $ticketStatus]);
                }
            }

            // Decrement SKU stock for non-cancelled orders
            if ($scenario['status'] !== 'cancel') {
                $sku->decrement('stock', $qty);
            }
        }

        $this->command->info('Orders created: ' . Order::count());
        $this->command->info('OrderTickets created: ' . OrderTicket::count());

        // ================================================================
        // SUMMARY
        // ================================================================
        $this->command->newLine();
        $this->command->info('=== DEMO DATA SUMMARY ===');
        $this->command->info('Users:           ' . User::count());
        $this->command->info('Vendors:         ' . Vendor::count());
        $this->command->info('Event Categories: ' . DB::table('event_categories')->count());
        $this->command->info('Events:          ' . Event::count());
        $this->command->info('SKUs:            ' . Sku::count());
        $this->command->info('Tickets:         ' . Ticket::count());
        $this->command->info('  - Available:   ' . Ticket::where('status', 'available')->count());
        $this->command->info('  - Booked:      ' . Ticket::where('status', 'booked')->count());
        $this->command->info('  - Sold:        ' . Ticket::where('status', 'sold')->count());
        $this->command->info('  - Redeemed:    ' . Ticket::where('status', 'redeem')->count());
        $this->command->info('Orders:          ' . Order::count());
        $this->command->info('  - Pending:     ' . Order::where('status_payment', 'pending')->count());
        $this->command->info('  - Success:     ' . Order::where('status_payment', 'success')->count());
        $this->command->info('  - Cancelled:   ' . Order::where('status_payment', 'cancel')->count());
        $this->command->info('OrderTickets:    ' . OrderTicket::count());
        $this->command->newLine();
        $this->command->info('=== LOGIN CREDENTIALS ===');
        $this->command->info('Password semua user: password');
        $this->command->newLine();
        $this->command->info('VENDOR accounts:');
        foreach (User::where('is_vendor', 1)->get() as $u) {
            $this->command->info("  {$u->email} — {$u->name}");
        }
        $this->command->info('BUYER accounts:');
        foreach (User::where('is_vendor', 0)->get() as $u) {
            $this->command->info("  {$u->email} — {$u->name}");
        }
        $this->command->newLine();
    }
}
