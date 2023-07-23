let password = document.getElementById("password");
let togglePasswordButton =document.getElementById("togglePassword");
console.log(togglePasswordButton)
togglePasswordButton.onclick = function(){
    if(password.type == "password"){
        password.type = "text";
        console.log("click")
    }else{
        // password.type = "password";
        console.log("none")
    }
    };
