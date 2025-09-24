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

function formatMoney(value) {
    return value.toLocaleString("pt-BR", {
        currency: "BRL",
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
};

function parseMoney(value) {
    if (!value) 
        return 0;

    value = value.replace(/[^\d,-]/g, "").replace(/\./g, "").replace(",", ".");
    return parseFloat(value) || 0;
};
