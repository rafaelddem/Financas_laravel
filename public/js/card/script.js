document.querySelectorAll('input[name="card_type"]').forEach(function(radio) {
    radio.addEventListener('change', function () {
        if (this.value === 'debit') {
            document.getElementById('first_day_month_label').style.display = 'none';
            document.getElementById('first_day_month').style.display = 'none';
            document.getElementById('days_to_expiration_label').style.display = 'none';
            document.getElementById('days_to_expiration').style.display = 'none';
        } else {
            document.getElementById('first_day_month_label').style.display = '';
            document.getElementById('first_day_month').style.display = '';
            document.getElementById('days_to_expiration_label').style.display = '';
            document.getElementById('days_to_expiration').style.display = '';
        }
    });
});
