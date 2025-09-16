document.addEventListener('DOMContentLoaded', function () {
    const modal = document.querySelector('.popupConfirmation');

    document.querySelectorAll('.open-confirm').forEach(button => {
        button.addEventListener('click', () => {
            modal.style.display = 'flex';
            modal.querySelector('h4').textContent = button.dataset.subtitle;
            document.getElementById('popup-id').value = button.dataset.id;
        });
    });
});

function closePopup() {
    document.querySelector('.popupConfirmation').style.display = 'none';
}