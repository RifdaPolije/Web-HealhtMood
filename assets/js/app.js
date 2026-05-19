document.addEventListener('DOMContentLoaded', function () {
    const params = new URLSearchParams(window.location.search);
    const toastModal = document.getElementById('toastModal');
    const toastTitle = document.getElementById('toastTitle');
    const toastMessage = document.getElementById('toastMessage');

    function showToast(title, message) {
        if (!toastModal || !toastTitle || !toastMessage) return;
        toastTitle.textContent = title;
        toastMessage.textContent = message;
        toastModal.classList.add('show');
        toastModal.setAttribute('aria-hidden', 'false');
    }

    const successMessages = {
        mood_created: 'Data mood berhasil disimpan.',
        mood_updated: 'Data mood berhasil diedit.',
        mood_deleted: 'Data mood berhasil dihapus.',
        sleep_created: 'Data tidur berhasil disimpan.',
        sleep_updated: 'Data tidur berhasil diedit.',
        sleep_deleted: 'Data tidur berhasil dihapus.'
    };

    if (params.has('success') && successMessages[params.get('success')]) {
        showToast('Berhasil', successMessages[params.get('success')]);
        window.history.replaceState({}, document.title, window.location.pathname + window.location.hash);
    }

    document.querySelectorAll('[data-close-toast]').forEach(function (button) {
        button.addEventListener('click', function () {
            toastModal.classList.remove('show');
            toastModal.setAttribute('aria-hidden', 'true');
        });
    });

    document.querySelectorAll('[data-open-modal]').forEach(function (button) {
        button.addEventListener('click', function () {
            const modal = document.querySelector(button.dataset.openModal);
            if (modal) modal.classList.add('show');
        });
    });

    document.querySelectorAll('[data-close-modal]').forEach(function (button) {
        button.addEventListener('click', function () {
            const modal = button.closest('.modal');
            if (modal) modal.classList.remove('show');
        });
    });

    document.querySelectorAll('.modal').forEach(function (modal) {
        modal.addEventListener('click', function (event) {
            if (event.target === modal) modal.classList.remove('show');
        });
    });

    const logoutModal = document.getElementById('logoutModal');
    const logoutButton = document.querySelector('.logout-trigger');
    if (logoutButton && logoutModal) {
        logoutButton.addEventListener('click', function () {
            logoutModal.classList.add('show');
            logoutModal.setAttribute('aria-hidden', 'false');
        });
    }

    document.querySelectorAll('[data-close-logout]').forEach(function (button) {
        button.addEventListener('click', function () {
            logoutModal.classList.remove('show');
            logoutModal.setAttribute('aria-hidden', 'true');
        });
    });

    document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
        button.addEventListener('click', function () {
            const input = document.querySelector(button.dataset.togglePassword);
            if (!input) return;
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            button.classList.toggle('is-visible', !showing);
            button.setAttribute('aria-label', showing ? 'Lihat password' : 'Sembunyikan password');
        });
    });

    const heroCheckButton = document.querySelector('[data-scroll-checkin]');
    const checkCard = document.getElementById('checkinCard');
    if (heroCheckButton && checkCard) {
        heroCheckButton.addEventListener('click', function (event) {
            event.preventDefault();
            checkCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        });
    }

    const checkinButton = document.querySelector('[data-checkin-action]');
    if (checkinButton) {
        const today = new Date().getDay();
        const dayIndex = today === 0 ? 6 : today - 1;
        const dayBoxes = document.querySelectorAll('.day-box');
        const progressText = document.querySelector('[data-xp-text]');
        const progressBar = document.querySelector('[data-xp-bar]');
        const levelText = document.querySelector('[data-level-text]');
        const checked = localStorage.getItem('healthmood_checkin_date') === new Date().toDateString();

        function renderCheckin(done) {
            dayBoxes.forEach(function (box, index) {
                box.classList.toggle('checked', done && index === dayIndex);
            });
            const xp = done ? 780 : 680;
            if (progressText) progressText.textContent = xp + ' / 1000 XP';
            if (progressBar) progressBar.style.width = xp / 10 + '%';
            if (levelText) levelText.textContent = done ? 'Level 9' : 'Level 8';
        }

        renderCheckin(checked);

        checkinButton.addEventListener('click', function () {
            localStorage.setItem('healthmood_checkin_date', new Date().toDateString());
            renderCheckin(true);
            showToast('Check in berhasil', 'Hari ini sudah ditandai dan XP kamu bertambah.');
        });
    }
});
