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





/* Wines - Inserimento Vino */

function checkNome() {
    var field = document.getElementsByName("nome")[0].value;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_name_error").innerHTML = "Il campo 'Nome' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_name_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkTipologia() {
    var field = document.getElementsByName("tipologia")[0].value;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_tipologia_error").innerHTML = "Il campo 'Tipologia' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_tipologia_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkVitigno() {
    var field = document.getElementsByName("vitigno")[0].value;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_vitigno_error").innerHTML = "Il campo 'Vitigno' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_vitigno_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkDenominazione() {
    var field = document.getElementsByName("denominazione")[0].value;
    var isFieldCorrect = true;

    return isFieldCorrect;
}

function checkGradazione() {
    var field = document.getElementsByName("gradazione")[0].value;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_gradazione_error").innerHTML = "Il campo 'Gradazione' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_gradazione_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkFormato() {
    var field = document.getElementsByName("formato")[0].value;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_formato_error").innerHTML = "Il campo 'Formato' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_formato_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkDescrizione() {
    var field = document.getElementsByName("descrizione")[0].value;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_descrizione_error").innerHTML = "Il campo 'Descrizione' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_descrizione_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkAbbinamento() {
    var field = document.getElementsByName("abbinamento")[0].value;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_abbinamento_error").innerHTML = "Il campo 'Abbinamento' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_abbinamento_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkDegustazione() {
    var field = document.getElementsByName("degustazione")[0].value;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_degustazione_error").innerHTML = "Il campo 'Degustazione' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_degustazione_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkAddWine() {
    var check = checkNome();
    check = checkTipologia();
    check = checkVitigno();
    check = checkDenominazione();
    check = checkGradazione();
    check = checkFormato();
    check = checkDescrizione();
    check = checkAbbinamento();
    check = checkDegustazione();
    return check;
}