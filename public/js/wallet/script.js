document.querySelectorAll('input[name="main_wallet"]').forEach(function(radio) {
    radio.addEventListener('change', function () {
        if (this.value === '1') {
            document.getElementById('active').checked = true;
            document.getElementById('inactive').checked = false;
            document.getElementById('inactive_label').style.display = 'none';
            document.getElementById('inactive').style.display = 'none';
        } else {
            document.getElementById('inactive_label').style.display = '';
            document.getElementById('inactive').style.display = '';
        }
    });
});