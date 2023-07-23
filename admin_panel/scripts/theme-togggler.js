const themeToggler = document.getElementsByClassName("theme-toggler")[0];
const lightModeToggle = document.getElementById("light-mode-toggle");
const darkModeToggle = document.getElementById("dark-mode-toggle");

themeToggler.addEventListener("click", () => {
  document.body.classList.toggle("dark-theme-variables");
  lightModeToggle.classList.toggle("active");
  darkModeToggle.classList.toggle("active");

  // Storing the theme mode in local storage
  const isDarkMode = document.body.classList.contains("dark-theme-variables");
  localStorage.setItem("themeMode", isDarkMode ? "dark" : "light");
});


// Retrieving the theme mode from local storage
const storedThemeMode = localStorage.getItem("themeMode");
if (storedThemeMode === "dark") {
  document.body.classList.add("dark-theme-variables");
  darkModeToggle.classList.add("active");
} else {
  document.body.classList.remove("dark-theme-variables");
  lightModeToggle.classList.add("active");
}
