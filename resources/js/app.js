import './bootstrap';

/**
 * Requirement upload — PDF-only validation before auto-submitting
 */
window.validateAndSubmit = function (input, reqId) {
    const errorEl = document.getElementById('file-error-' + reqId);
    const file = input.files[0];

    if (!file) return;

    if (file.type !== 'application/pdf') {
        if (errorEl) {
            errorEl.textContent = 'Only PDF files are allowed. Please select a PDF document.';
            errorEl.classList.remove('hidden');
        }
        input.value = '';
        return;
    }

    if (errorEl) errorEl.classList.add('hidden');
    input.form.submit();
};

/**
 * Admin dashboard — live applicant name search
 */
function initApplicantSearch() {
    const searchInput = document.getElementById('applicant-search');
    if (!searchInput) return;

    searchInput.addEventListener('input', function (e) {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.applicant-row').forEach((row) => {
            const nameEl = row.querySelector('.applicant-name');
            if (!nameEl) return;
            const name = nameEl.textContent.toLowerCase();
            row.style.display = name.includes(term) ? '' : 'none';
        });
    });
}

/**
 * Student layout — user menu dropdown
 */
function initUserMenu() {
    const btn = document.getElementById('userMenuBtn');
    const menu = document.getElementById('userMenu');
    if (!btn || !menu) return;

    btn.addEventListener('click', function () {
        menu.classList.toggle('hidden');
    });

    document.addEventListener('click', function (e) {
        if (!menu.contains(e.target) && !btn.contains(e.target)) {
            menu.classList.add('hidden');
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    initApplicantSearch();
    initUserMenu();
});