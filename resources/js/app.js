// Flash message dismiss
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-dismiss]').forEach(el => {
        el.addEventListener('click', () => el.closest('[data-alert]').remove());
    });
});
