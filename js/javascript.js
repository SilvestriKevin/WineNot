/* FORM CONTACTS */

function checkEmail() {
    var email = document.getElementById("email").value;
    var email_reg = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i;
    var isFieldCorrect = true;
    if (!email_reg.test(email)) { // la mail non è quindi del formato giusto
        // document.getElementById("email").style.borderColor = "red";
        document.getElementById("mail_error").innerHTML = "Formato 'e-mail' non corretto";
        isFieldCorrect = false;
    } else {
        // document.getElementById("email").style.border = "1px inset";
        document.getElementById("mail_error").innerHTML = " ";
    }
    return isFieldCorrect;
}

function checkMailObject() {
    var oggetto = document.getElementsByName("object")[0].value;
    var isFieldCorrect = true;
    if (!oggetto) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("mail_object_error").innerHTML = "Il campo 'Oggetto' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("mail_object_error").innerHTML = " ";
    }
    return isFieldCorrect;
}

function checkMailMessage() {
    var content = document.getElementById("textarea_form_help").value;
    var isFieldCorrect = true;
    if (!content) {
        // document.getElementById("textarea_form_help").style.borderColor = "red";
        document.getElementById("mail_content_error").innerHTML = "Il campo 'Messaggio' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementById("textarea_form_help").style.border = "1px inset";
        document.getElementById("mail_content_error").innerHTML = " ";
    }

    return isFieldCorrect;
}


function checkContactForm() {
    // invoco le altre tre funzioni per controllare se c'é qualcosa che non va

    var check = checkEmail();
    check = checkMailObject();
    check = checkMailMessage();
    return check;

}

/* FORM LOGIN */

function checkUsername() {
    var username = document.getElementById("username").value;
    var isFieldCorrect = true;

    if (!username) {
        //document.getElementById("username").style.borderColor = "red";
        document.getElementById("username_error").innerHTML = "Il campo 'Username' non può essere vuoto";
        isFieldCorrect = false;
    } else {
       // document.getElementById("username").style.border = "1px inset";
        document.getElementById("username_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkPassword() {
    var password = document.getElementById("password").value;
    var isFieldCorrect = true;

    if (!password) {
        //document.getElementById("password").style.borderColor = "red";
        document.getElementById("password_error").innerHTML = "Il campo 'Password' non può essere vuoto";
        isFieldCorrect = false;
    } else {
       // document.getElementById("password").style.border = "1px inset";
        document.getElementById("password_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkLoginForm() {
    var check = checkUsername();
    check = checkPassword();
    return check;
}

/* RECUPERO PASSWORD */

function checkEmailRecoverPassword() {
    var check = checkEmail();
    return check;
}