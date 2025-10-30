document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.fa-bars').addEventListener('click', function () {
        document.querySelector('.sidebar').classList.toggle('active');
    });
});

function toggleSubmenu(event, submenuClass) {
    let submenu = document.querySelector('.' + submenuClass);
    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';

    const icons = event.target.closest('li').querySelectorAll('i');
    if (icons.length >= 2) {
      const secondIcon = icons[1];
      if (secondIcon.classList.contains('fa-caret-left')) {
          secondIcon.classList.replace("fa-caret-left", "fa-caret-down");
        } else {
          secondIcon.classList.replace("fa-caret-down", "fa-caret-left");
      }
    }
}

function toggleNotificationsMenu() {
    let menu = document.querySelector('.notifications-options');

    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.menu-item')) {
        document.querySelectorAll('.topbar-dropdown').forEach(menu => menu.style.display = 'none');
    }
});

setTimeout(() => {
    const alertBox = document.getElementById("alertBox");
    if (alertBox) {
        alertBox.style.transition = "opacity 0.5s ease-out";
        alertBox.style.opacity = "0";
        
        setTimeout(() => {
            alertBox.remove();
        }, 500);
    }
}, 5000);

function addMonths(date, months) {
    const newDate = (date.length === 0) 
        ? new Date() 
        : new Date(date);

    newDate.setMonth(newDate.getMonth() + months);
    return newDate;
}

document.addEventListener("keydown", function (e) {
    if (e.target.classList.contains("money") && e.key === "-") {
        const input = e.target;
        const raw = input.value.replace(/[^\d,-]/g, "");

        if (raw === "" || raw === "0,00" || raw === "00") {
            e.preventDefault();
            input.value = "-0,00";

            setTimeout(() => {
                input.setSelectionRange(input.value.length, input.value.length);
            }, 0);
        }
    }
});

document.addEventListener("input", function (e) {
    if (e.target.classList.contains("money")) {
        formatMoney(e.target);
    }
});

function formatMoney(input) {
    if (input.value.includes(',')) {
        let value = input.value.replace(/[^\d-]/g, '');
        value = (parseInt(value, 10) / 100).toFixed(2);
        value = value.replace(".", ",");
        input.value = applyBrlMask(value);
    } else {
        let numero = parseFloat(input.value.replace(',', '.'));
        let value = isNaN(numero) ? '' : numero.toFixed(2).replace('.', ',');
        input.value = value;
    }
};

function applyBrlMask(value) {
    return value.toLocaleString("pt-BR", {
        currency: "BRL",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};

function moneyToFloat(value) {
    if (!value) 
        return 0;

    value = value.replace(/[^\d,-]/g, "").replace(/\./g, "").replace(",", ".");
    return parseFloat(value) || 0;
};
