@props([
    'csNumber' => config('services.whatsapp.cs_number', env('WHATSAPP_CS_NUMBER', '6281234567890')),
])

<div x-data="{
    open: false,
    invoiceNumber: '',
    selectedCategory: 'kendala',
    customMessage: '',
    csNumber: '{{ $csNumber }}',
    
    selectCategory(cat) {
        this.selectedCategory = cat;
        if (cat === 'kendala') {
            this.customMessage = 'Halo CS Absolute Store, saya ingin menanyakan status pesanan saya yang belum masuk.';
        } else if (cat === 'reseller') {
            this.customMessage = 'Halo CS Absolute Store, saya tertarik untuk mendaftar sebagai Member Reseller/VIP dan menanyakan promo harga spesial.';
        } else if (cat === 'deposit') {
            this.customMessage = 'Halo CS Absolute Store, saya ingin konfirmasi deposit saldo dompet akun saya.';
        }
    },

    sendWhatsApp() {
        let msg = '*[LAYANAN BANTUAN CUSTOMER SERVICE - ABSOLUTE STORE]*\n\n';
        if (this.invoiceNumber.trim() !== '') {
            msg += '• *No. Invoice:* `' + this.invoiceNumber.trim() + '`\n';
        }
        msg += '• *Kategori:* ' + (this.selectedCategory === 'kendala' ? 'Kendala Pesanan' : (this.selectedCategory === 'reseller' ? 'Kemitraan / Reseller' : 'Deposit Saldo')) + '\n\n';
        msg += '• *Pesan:* ' + (this.customMessage.trim() || 'Mohon dibantu verifikasi.') + '\n\n';
        msg += '_Dikirim via Widget Bantuan Web Absolute Store_';

        const encoded = encodeURIComponent(msg);
        const url = 'https://wa.me/' + this.csNumber.replace(/[^0-9]/g, '') + '?text=' + encoded;
        window.open(url, '_blank');
        this.open = false;
    }
}" 
x-init="selectCategory('kendala')"
class="fixed bottom-6 right-6 z-40">

    <!-- Popover Card Support Modal -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="opacity-0 translate-y-4 scale-90"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200 transform"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-4 scale-90"
         @click.outside="open = false"
         x-cloak
         class="absolute bottom-16 right-0 w-80 sm:w-96 rounded-3xl border border-slate-800 bg-[#0c101d]/95 backdrop-blur-2xl shadow-2xl shadow-emerald-950/40 p-5 space-y-4 text-slate-100">

        <!-- Header -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-800">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-2xl bg-emerald-500/20 border border-emerald-500/40 flex items-center justify-center text-emerald-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="text-sm font-black text-white">Live Support 24/7</h4>
                    <p class="text-[11px] text-emerald-400 font-semibold flex items-center gap-1.5">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Customer Care Online
                    </p>
                </div>
            </div>
            <button @click="open = false" class="text-slate-400 hover:text-white transition p-1 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Category Selector Quick Buttons -->
        <div class="space-y-1.5">
            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pilih Kebutuhan Anda</label>
            <div class="grid grid-cols-3 gap-1.5">
                <button type="button" @click="selectCategory('kendala')"
                        class="px-2 py-1.5 rounded-xl text-[10px] font-bold text-center border transition cursor-pointer"
                        :class="selectedCategory === 'kendala' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/50' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white'">
                    Kendala Order
                </button>
                <button type="button" @click="selectCategory('reseller')"
                        class="px-2 py-1.5 rounded-xl text-[10px] font-bold text-center border transition cursor-pointer"
                        :class="selectedCategory === 'reseller' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/50' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white'">
                    Reseller / VIP
                </button>
                <button type="button" @click="selectCategory('deposit')"
                        class="px-2 py-1.5 rounded-xl text-[10px] font-bold text-center border transition cursor-pointer"
                        :class="selectedCategory === 'deposit' ? 'bg-emerald-500/20 text-emerald-300 border-emerald-500/50' : 'bg-slate-900 text-slate-400 border-slate-800 hover:text-white'">
                    Deposit Saldo
                </button>
            </div>
        </div>

        <!-- Invoice Input (Optional) -->
        <div class="space-y-1">
            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">No. Invoice (Opsional)</label>
            <input type="text" x-model="invoiceNumber" placeholder="Contoh: INV-20260918-XXXX"
                   class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500 font-mono" />
        </div>

        <!-- Message Field -->
        <div class="space-y-1">
            <label class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Pesan / Pertanyaan</label>
            <textarea x-model="customMessage" rows="2" placeholder="Tuliskan kendala Anda..."
                      class="w-full px-3 py-2 rounded-xl bg-slate-950 border border-slate-800 text-xs text-white placeholder-slate-600 focus:outline-none focus:border-emerald-500 resize-none"></textarea>
        </div>

        <!-- Submit Button -->
        <button type="button" @click="sendWhatsApp()"
                class="w-full flex items-center justify-center gap-2 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-slate-950 font-black text-xs shadow-lg shadow-emerald-500/30 transition-all active:scale-95 cursor-pointer">
            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span>Buka Chat WhatsApp</span>
        </button>
    </div>

    <!-- Floating Circular Trigger Button -->
    <button type="button" @click="open = !open"
            class="relative h-14 w-14 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 hover:from-emerald-500 hover:to-teal-400 text-white shadow-xl shadow-emerald-600/30 flex items-center justify-center hover:scale-110 active:scale-95 transition-all duration-300 group cursor-pointer border border-emerald-400/30">
        
        <!-- Pulse ring indicator -->
        <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-400 border-2 border-[#080C14]"></span>
        </span>

        <!-- WhatsApp SVG Icon -->
        <svg class="w-7 h-7 fill-current transition-transform duration-300 group-hover:rotate-6" viewBox="0 0 24 24">
            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
        </svg>
    </button>
</div>
