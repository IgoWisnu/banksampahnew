<!-- Bottom Navigation Bar -->
<div
    class="fixed bottom-0 left-0 right-0 w-full flex justify-center bg-white/95 backdrop-blur-md border-t border-gray-100 shadow-[0_-5px_15px_rgba(0,0,0,0.03)] z-[90]">
    <!-- Safe Area Padding Bottom equivalent -->
    <div class="pb-safe w-full max-w-7xl">
        <div class="flex justify-around md:justify-center md:gap-32 items-center h-20 md:h-16 px-4 pb-1">

            <!-- Riwayat (History) Component -->
            <a href="<?= base_url() ?>riwayat"
                class="flex flex-col items-center justify-center text-gray-400 hover:text-brand-green transition-colors group px-4">
                <div class="p-1 group-hover:bg-green-50 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-semibold mt-0.5 tracking-wide">Riwayat</span>
            </a>

            <!-- Home Component (Prominent Center) -->
            <a href="<?= base_url() ?>home/loadArtikel"
                class="flex flex-col items-center justify-center text-brand-green transition-colors group relative px-4">
                <div
                    class="absolute -top-7 bg-brand-green text-white p-3.5 rounded-full shadow-lg shadow-brand-green/40 hover:bg-brand-dark hover:scale-105 active:scale-95 transition-all outline-[6px] outline-gray-50 outline flex items-center justify-center border-4 border-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                </div>
                <span class="text-[10px] font-semibold mt-8 text-brand-green tracking-wide">Beranda</span>
            </a>

            <!-- Profile Component -->
            <a href="<?= base_url() ?>Profile"
                class="flex flex-col items-center justify-center text-gray-400 hover:text-brand-green transition-colors group px-4">
                <div class="p-1 group-hover:bg-green-50 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </div>
                <span class="text-[10px] font-semibold mt-0.5 tracking-wide">Profil</span>
            </a>

        </div>
    </div>
</div>