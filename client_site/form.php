<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Document</title>
  </head>
  <body>
   
      <label for="password">Password:</label>
      <input type="password" id="password" />
      <button type="button" id="togglePassword">Show/Hide</button>

    <script>
      const togglePasswordButton = document.getElementById("togglePassword");
      const passwordInput = document.getElementById("password");

      togglePasswordButton.addEventListener("click", () => {
        if (passwordInput.type === "password") {
          passwordInput.type = "text";
        } else {
          passwordInput.type = "password";
        }
      });
    </script>
  </body>
</html>
