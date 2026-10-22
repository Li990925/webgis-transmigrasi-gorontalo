import { initWebGIS } from './webgis.js';

document.addEventListener('DOMContentLoaded', () => {
    initWebGIS();

    // Mobile sidebar toggle
    const toggleSidebarBtn = document.getElementById('toggle-sidebar-btn');
    const sidebar = document.getElementById('main-sidebar');
    const closeSidebarBtn = document.getElementById('close-sidebar-btn');

    if (toggleSidebarBtn && sidebar) {
        toggleSidebarBtn.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
        });
    }

    if (closeSidebarBtn && sidebar) {
        closeSidebarBtn.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
        });
    }

    // Modal Statistik Toggle
    const openStatsBtn = document.getElementById('open-stats-btn');
    const statsModal = document.getElementById('stats-modal');
    const closeStatsBtn = document.getElementById('close-stats-btn');

    if (openStatsBtn && statsModal) {
        openStatsBtn.addEventListener('click', () => {
            statsModal.classList.remove('hidden');
        });
    }

    if (closeStatsBtn && statsModal) {
        closeStatsBtn.addEventListener('click', () => {
            statsModal.classList.add('hidden');
        });
    }
});
