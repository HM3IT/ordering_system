let currentPath = window.location.pathname;
let currentPageName = currentPath.substring(currentPath.lastIndexOf("/") + 1);
let currentInputs = document.querySelectorAll(".current_page");

currentInputs.forEach(function (input) {
  input.value = currentPageName;
});
