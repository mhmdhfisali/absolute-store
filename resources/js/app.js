// Absolute Store Global Frontend Engine & Localization Store
document.addEventListener('alpine:init', () => {
    Alpine.store('i18n', {
        locale: localStorage.getItem('locale') || document.documentElement.lang || 'id',
        dict: {
            id: {
                home: 'Beranda',
                tracking: 'Lacak Pesanan',
                catalog: 'Katalog Layanan',
                dashboard: 'Dashboard',
                my_dashboard: 'Dashboard Saya',
                order_history: 'Riwayat Transaksi',
                account_settings: 'Pengaturan Akun & Profil',
                theme_mode: 'Ganti Mode Tema',
                language: 'Bahasa',
                logout: 'Keluar Akun',
                login: 'Masuk Akun',
                register: 'Daftar Akun',
                wallet_balance: 'Saldo Wallet',
                instant_topup_wallet: 'Tambah Saldo Instan',
                all_services: 'Semua Layanan',
                active_products: 'produk digital aktif',
                showing: 'Menampilkan',
                search_placeholder: 'Cari Mobile Legends, Free Fire, PLN...',
                product_not_found: 'Produk Tidak Ditemukan',
                reset_search: 'Reset Pencarian',
                starting_from: 'Mulai dari',
                available: 'Tersedia',
                quick_buy: 'Beli Cepat',
                topup_now: 'Top Up Sekarang',
                verify_account: 'Verifikasi Akun',
                secure_checkout_now: 'Selesaikan Pembayaran Aman',
                pay_with_balance: 'Bayar Instan Saldo',
                instant_1_3_seconds: 'Instan 1-3 Detik',
                legal_and_secure: '100% Legal & Aman',
                complete_payment: 'Metode Terlengkap',
                cs_support: 'Dukungan CS 24/7',
                promo_coupon: 'Kupon Promo Diskon',
                apply_coupon: 'Klaim Kupon',
                total_bill: 'Total Tagihan'
            },
            en: {
                home: 'Home',
                tracking: 'Track Order',
                catalog: 'Services Catalog',
                dashboard: 'Dashboard',
                my_dashboard: 'My Dashboard',
                order_history: 'Order History',
                account_settings: 'Account Settings & Profile',
                theme_mode: 'Toggle Theme Mode',
                language: 'Language',
                logout: 'Sign Out',
                login: 'Sign In',
                register: 'Sign Up',
                wallet_balance: 'Wallet Balance',
                instant_topup_wallet: 'Instant Wallet Top-Up',
                all_services: 'All Services',
                active_products: 'active digital products',
                showing: 'Showing',
                search_placeholder: 'Search Mobile Legends, Free Fire, PLN...',
                product_not_found: 'Product Not Found',
                reset_search: 'Reset Search',
                starting_from: 'Starting from',
                available: 'Available',
                quick_buy: 'Quick Purchase',
                topup_now: 'Top Up Now',
                verify_account: 'Verify Player ID',
                secure_checkout_now: 'Secure Checkout Now',
                pay_with_balance: 'Pay with Wallet Balance',
                instant_1_3_seconds: 'Instant 1-3 Secs',
                legal_and_secure: '100% Legal & Safe',
                complete_payment: 'Comprehensive Payments',
                cs_support: '24/7 CS Support',
                promo_coupon: 'Promo Voucher Discount',
                apply_coupon: 'Apply Voucher',
                total_bill: 'Total Payable'
            }
        },
        t(key, fallback = '') {
            const loc = this.locale === 'en' ? 'en' : 'id';
            return this.dict[loc]?.[key] || fallback || key;
        },
        setLocale(newLocale) {
            if (newLocale !== 'id' && newLocale !== 'en') return;
            this.locale = newLocale;
            localStorage.setItem('locale', newLocale);
            document.documentElement.lang = newLocale;

            // Notify backend asynchronously so session & cookie match
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            fetch('/switch-locale/' + newLocale, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': token
                }
            }).catch(() => {});
        }
    });
});
