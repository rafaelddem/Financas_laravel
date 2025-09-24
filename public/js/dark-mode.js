if (localStorage.getItem("darkMode") === "enabled") {
    document.documentElement.classList.add("dark-mode");
    document.getElementById("darkModeIcon").classList.replace("fa-moon", "fa-sun");
}

function toggleDarkMode() {
    document.documentElement.classList.toggle("dark-mode");

    const icon = document.getElementById("darkModeIcon");
    if (document.documentElement.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
        icon.classList.replace("fa-moon", "fa-sun");
    } else {
        localStorage.setItem("darkMode", "disabled");
        icon.classList.replace("fa-sun", "fa-moon");
    }
}