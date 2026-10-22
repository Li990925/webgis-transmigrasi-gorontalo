<!-- ==================== WEBGIS CUSTOM BRANDED MODAL POPUP ==================== -->
<div id="webgis-modal-backdrop" class="fixed inset-0 z-[9999] bg-slate-950/65 backdrop-blur-xs flex items-center justify-center p-4 transition-all duration-200 opacity-0 pointer-events-none select-none" style="display: none;" role="dialog" aria-modal="true" aria-labelledby="webgis-modal-title">
    
    <!-- Modal Card -->
    <div id="webgis-modal-card" class="relative bg-white border border-slate-200/90 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden text-center transform transition-all duration-200 scale-95 opacity-0">
        
        <!-- Decorative Top Colored Stripe based on Type -->
        <div id="webgis-modal-stripe" class="h-1.5 w-full bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600 transition-all duration-300"></div>

        <!-- Top Right Close Button (Esc / Close) -->
        <button type="button" id="webgis-modal-close-btn" class="absolute top-3.5 right-3.5 w-8 h-8 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors cursor-pointer" title="Tutup (Esc)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>

        <div class="p-6 pt-5">
            <!-- Icon Container with Glowing Ring -->
            <div id="webgis-modal-icon-container" class="w-16 h-16 mx-auto rounded-2xl flex items-center justify-center mb-4 transition-all duration-300 ring-8 shadow-inner">
                <div id="webgis-modal-icon-svg">
                    <!-- Dynamic SVG injected by JS -->
                </div>
            </div>

            <!-- Category / System Badge -->
            <div class="mb-2">
                <span id="webgis-modal-badge" class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider transition-colors">
                    Sistem PostGIS
                </span>
            </div>

            <!-- Title -->
            <h3 id="webgis-modal-title" class="text-lg font-extrabold text-slate-900 tracking-tight leading-snug">
                Pemberitahuan Sistem
            </h3>

            <!-- Message Description -->
            <p id="webgis-modal-message" class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
                Pesan pemberitahuan sistem.
            </p>

            <!-- Details / Metadata Box (optional) -->
            <div id="webgis-modal-details" class="mt-4 p-3 rounded-xl bg-slate-50 border border-slate-200/80 text-[11px] text-slate-700 text-left space-y-1 font-mono hidden leading-relaxed">
            </div>

            <!-- Action Buttons -->
            <div class="mt-6 flex items-center justify-center gap-2.5">
                <button type="button" id="webgis-modal-cancel-btn" class="hidden px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold border border-slate-200 transition-all cursor-pointer">
                    Batal
                </button>
                <button type="button" id="webgis-modal-confirm-btn" class="flex-1 py-2.5 px-5 rounded-xl text-white text-xs font-bold shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-95">
                    <span id="webgis-modal-confirm-text">OK, Mengerti</span>
                </button>
            </div>
        </div>

        <!-- Official Branding Footer -->
        <div class="bg-slate-50/90 border-t border-slate-100 py-2 px-4 flex items-center justify-between text-[10px] text-slate-400 select-none">
            <div class="flex items-center gap-1.5">
                <img src="/images/logo.png" alt="Gorontalo" class="w-3.5 h-3.5 object-contain opacity-80">
                <span class="font-semibold tracking-wider uppercase text-slate-500">Sistem Informasi Transmigrasi Gorontalo</span>
            </div>
            <span class="font-mono text-[9px] text-slate-400">SRID 4326</span>
        </div>
    </div>
</div>

<script>
(function() {
    // Icons SVG Templates
    const ICONS = {
        success: `
            <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        `,
        error: `
            <svg class="w-8 h-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        `,
        warning: `
            <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
        `,
        info: `
            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        `
    };

    const THEMES = {
        success: {
            stripe: 'bg-gradient-to-r from-emerald-500 via-teal-500 to-emerald-600',
            container: 'bg-emerald-50 border-2 border-emerald-100 ring-8 ring-emerald-50/60 shadow-emerald-100/50',
            badge: 'bg-emerald-100 text-emerald-800 border border-emerald-200',
            btn: 'bg-gradient-to-r from-emerald-600 to-teal-700 hover:from-emerald-500 hover:to-teal-600 text-white shadow-emerald-900/20'
        },
        error: {
            stripe: 'bg-gradient-to-r from-rose-500 via-red-500 to-rose-600',
            container: 'bg-rose-50 border-2 border-rose-100 ring-8 ring-rose-50/60 shadow-rose-100/50',
            badge: 'bg-rose-100 text-rose-800 border border-rose-200',
            btn: 'bg-gradient-to-r from-rose-600 to-red-700 hover:from-rose-500 hover:to-red-600 text-white shadow-rose-900/20'
        },
        warning: {
            stripe: 'bg-gradient-to-r from-amber-400 via-yellow-500 to-amber-500',
            container: 'bg-amber-50 border-2 border-amber-100 ring-8 ring-amber-50/60 shadow-amber-100/50',
            badge: 'bg-amber-100 text-amber-800 border border-amber-200',
            btn: 'bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-400 hover:to-yellow-500 text-slate-950 font-bold shadow-amber-900/20'
        },
        info: {
            stripe: 'bg-gradient-to-r from-[#081e36] via-[#1a73e8] to-[#081e36]',
            container: 'bg-blue-50 border-2 border-blue-100 ring-8 ring-blue-50/60 shadow-blue-100/50',
            badge: 'bg-blue-100 text-blue-800 border border-blue-200',
            btn: 'bg-gradient-to-r from-[#081e36] to-[#0d2a4a] hover:from-[#0d2a4a] hover:to-[#1a73e8] text-white shadow-slate-900/20'
        }
    };

    let activeResolve = null;

    function closeModal(result) {
        const backdrop = document.getElementById('webgis-modal-backdrop');
        const card = document.getElementById('webgis-modal-card');
        if (!backdrop || !card) return;

        card.classList.remove('scale-100', 'opacity-100');
        card.classList.add('scale-95', 'opacity-0');

        backdrop.classList.remove('opacity-100', 'pointer-events-auto');
        backdrop.classList.add('opacity-0', 'pointer-events-none');

        setTimeout(() => {
            backdrop.style.display = 'none';
            if (activeResolve) {
                const res = activeResolve;
                activeResolve = null;
                res(result);
            }
        }, 200);
    }

    /**
     * Show custom branded modal popup
     * @param {Object} options
     * @param {string} [options.type='info'] - 'success', 'error', 'warning', 'info'
     * @param {string} [options.title] - Modal heading
     * @param {string} [options.badge] - Category pill tag
     * @param {string} options.message - Body text / HTML
     * @param {string} [options.details] - Extra technical / PostGIS info
     * @param {string} [options.confirmText='OK, Mengerti'] - Primary button text
     * @param {string} [options.cancelText='Batal'] - Secondary button text
     * @param {boolean} [options.showCancel=false] - Show cancel button
     * @returns {Promise<boolean>}
     */
    window.showWebModal = function(options) {
        return new Promise((resolve) => {
            activeResolve = resolve;

            const opts = typeof options === 'string' ? { message: options } : (options || {});
            const type = opts.type && THEMES[opts.type] ? opts.type : 'info';
            const theme = THEMES[type];

            const backdrop = document.getElementById('webgis-modal-backdrop');
            const card = document.getElementById('webgis-modal-card');
            const stripe = document.getElementById('webgis-modal-stripe');
            const iconContainer = document.getElementById('webgis-modal-icon-container');
            const iconSvg = document.getElementById('webgis-modal-icon-svg');
            const badge = document.getElementById('webgis-modal-badge');
            const title = document.getElementById('webgis-modal-title');
            const message = document.getElementById('webgis-modal-message');
            const details = document.getElementById('webgis-modal-details');
            const cancelBtn = document.getElementById('webgis-modal-cancel-btn');
            const confirmBtn = document.getElementById('webgis-modal-confirm-btn');
            const confirmText = document.getElementById('webgis-modal-confirm-text');

            if (!backdrop) {
                // Fallback to native alert if DOM not present
                alert(opts.message || '');
                resolve(true);
                return;
            }

            // Apply type theme
            stripe.className = 'h-1.5 w-full transition-all duration-300 ' + theme.stripe;
            iconContainer.className = 'w-16 h-16 mx-auto rounded-2xl flex items-center justify-center mb-4 transition-all duration-300 ring-8 shadow-inner ' + theme.container;
            iconSvg.innerHTML = ICONS[type];
            badge.className = 'inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider transition-colors ' + theme.badge;
            confirmBtn.className = 'flex-1 py-2.5 px-5 rounded-xl text-white text-xs font-bold shadow-md hover:shadow-lg transition-all cursor-pointer flex items-center justify-center gap-2 active:scale-95 ' + theme.btn;

            // Set content
            badge.textContent = opts.badge || (
                type === 'success' ? 'PostGIS Berhasil' :
                type === 'error' ? 'Kesalahan Sistem' :
                type === 'warning' ? 'Perhatian Geometri' : 'Sistem Informasi'
            );

            title.textContent = opts.title || (
                type === 'success' ? 'Berhasil Disimpan' :
                type === 'error' ? 'Terjadi Kesalahan' :
                type === 'warning' ? 'Perhatian' : 'Pemberitahuan Sistem'
            );

            message.innerHTML = opts.message || '';

            if (opts.details) {
                details.innerHTML = opts.details;
                details.classList.remove('hidden');
            } else {
                details.innerHTML = '';
                details.classList.add('hidden');
            }

            confirmText.textContent = opts.confirmText || 'OK, Mengerti';

            if (opts.showCancel) {
                cancelBtn.textContent = opts.cancelText || 'Batal';
                cancelBtn.classList.remove('hidden');
            } else {
                cancelBtn.classList.add('hidden');
            }

            // Show backdrop & card with smooth animation
            backdrop.style.display = 'flex';
            requestAnimationFrame(() => {
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100', 'pointer-events-auto');

                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');

                confirmBtn.focus();
            });
        });
    };

    /**
     * Confirmation dialog replacement
     */
    window.showConfirmModal = function(options) {
        const opts = typeof options === 'string' ? { message: options } : (options || {});
        return window.showWebModal({
            type: opts.type || 'warning',
            title: opts.title || 'Konfirmasi Tindakan',
            badge: opts.badge || 'Verifikasi',
            message: opts.message || 'Apakah Anda yakin ingin melanjutkan tindakan ini?',
            confirmText: opts.confirmText || 'Ya, Lanjutkan',
            cancelText: opts.cancelText || 'Batal',
            showCancel: true
        });
    };

    // Event listeners
    document.addEventListener('DOMContentLoaded', function() {
        const backdrop = document.getElementById('webgis-modal-backdrop');
        const card = document.getElementById('webgis-modal-card');
        const closeBtn = document.getElementById('webgis-modal-close-btn');
        const cancelBtn = document.getElementById('webgis-modal-cancel-btn');
        const confirmBtn = document.getElementById('webgis-modal-confirm-btn');

        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                closeModal(true);
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                closeModal(false);
            });
        }

        if (closeBtn) {
            closeBtn.addEventListener('click', function() {
                closeModal(false);
            });
        }

        if (backdrop) {
            backdrop.addEventListener('click', function(e) {
                if (e.target === backdrop) {
                    closeModal(false);
                }
            });
        }

        document.addEventListener('keydown', function(e) {
            if (backdrop && backdrop.style.display !== 'none' && !backdrop.classList.contains('pointer-events-none')) {
                if (e.key === 'Escape') {
                    closeModal(false);
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    closeModal(true);
                }
            }
        });
    });

    // Auto-polyfill window.alert so that ANY legacy alert() call automatically renders this gorgeous web popup
    if (typeof window !== 'undefined') {
        const nativeAlert = window.alert;
        window.originalAlert = nativeAlert;
        window.alert = function(msg) {
            const str = String(msg || '');
            const isSuccess = /berhasil|sukses|berhasil disimpan/i.test(str);
            const isError = /gagal|error|kesalahan|terjadi kesalahan/i.test(str);
            const isWarning = /silakan|peringatan|perhatian|tentukan/i.test(str);

            const type = isSuccess ? 'success' : (isError ? 'error' : (isWarning ? 'warning' : 'info'));
            const title = isSuccess ? 'Berhasil Disimpan' : (isError ? 'Terjadi Kesalahan' : (isWarning ? 'Perhatian' : 'Pemberitahuan Sistem'));
            const badge = isSuccess ? 'PostGIS Berhasil' : (isError ? 'Gagal Simpan' : (isWarning ? 'Peringatan' : 'Sistem WebGIS'));

            return window.showWebModal({
                type: type,
                title: title,
                badge: badge,
                message: str,
                confirmText: 'OK, Mengerti'
            });
        };
    }
})();
</script>
