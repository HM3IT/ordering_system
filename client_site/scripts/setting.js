function openChangePasswordForm() {
  document.getElementById("popup-form-overlay").style.display = "flex";
}

function closeChangePasswordForm() {
  document.getElementById("popup-form-overlay").style.display = "none";
}

function validateForm(event) {
  // Prevent form submission
  event.preventDefault();

  var newPassword = document.getElementById("new-password").value;
  var confirmPassword = document.getElementById("confirm-password").value;
  var passwordMatchError = document.getElementById("password-match-error");

  if (newPassword !== confirmPassword) {
    passwordMatchError.innerHTML = "Passwords do not match";
    passwordMatchError.style.visibility = "visible";
    document
      .getElementById("popup-form-change-password")
      .classList.add("shake-animation");
    return;
  }

  document.getElementById("popup-form-change-password").classList.remove("shake-animation");
  passwordMatchError.style.visibility = "hidden";
  document.getElementById("popup-change-password-form").submit();
}
