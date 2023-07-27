let hamburgerMenu = document.getElementsByClassName("hamburger-menu")[0];
let nav = document.getElementsByClassName("nav-bar")[0];
const hamburgerIcon = document.getElementById("hamburger-icon");

//  Access CSS variables (custom properties) defined in the :root pseudo-class to avoid hard coding
const rootStyles = getComputedStyle(document.documentElement);
const offsetLeft = rootStyles.getPropertyValue("--nav-bar-offset-left");

hamburgerMenu.addEventListener("click", (event) => {
  if (nav.style.left === "0px") {
    nav.style.left = offsetLeft;
    hamburgerIcon.style.color = "black";
    hamburgerIcon.classList.remove("fa-outdent");
    hamburgerIcon.classList.add("fas", "fa-indent");
  } else {
    nav.style.left = "0px";
    hamburgerIcon.style.color = "orangered";
    hamburgerIcon.classList.remove("fa-indent");
    hamburgerIcon.classList.add("fas", "fa-outdent");
  }
});