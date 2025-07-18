function toggleSubmenu(submenuClass) {
    let submenu = document.querySelector('.' + submenuClass);
    submenu.style.display = submenu.style.display === 'block' ? 'none' : 'block';
}

function toggleDropdown(menuClass) {
    let menu = document.querySelector(menuClass);
    let otherMenu = menuClass === '.logout-options' 
        ? document.querySelector('.notifications-options') 
        : document.querySelector('.logout-options');

    otherMenu.style.display = 'none';
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
}

function toggleLogoutMenu() {
    toggleDropdown('.logout-options');
}

function toggleNotificationsMenu() {
    toggleDropdown('.notifications-options');
}

document.addEventListener('click', function(event) {
    if (!event.target.closest('.menu-item') && !event.target.closest('.notifications-menu')) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => menu.style.display = 'none');
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
