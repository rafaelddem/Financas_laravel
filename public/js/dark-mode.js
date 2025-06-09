if (localStorage.getItem("darkMode") === "enabled") {
    document.documentElement.classList.add("dark-mode");
}

function toggleDarkMode() {
    document.documentElement.classList.toggle("dark-mode");

    if (document.documentElement.classList.contains("dark-mode")) {
        localStorage.setItem("darkMode", "enabled");
    } else {
        localStorage.setItem("darkMode", "disabled");
    }
}