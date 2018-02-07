/* FORM CONTACTS */


function start() {

    // controlli necessari per far sì che tutto funzioni con e senza javascript
    var php_content = document.getElementsByClassName("hide_content");
    var element = php_content[0];
    if (element) {
        element.style.display = "none";
    }

    var js_content = document.getElementsByClassName("hide_js");
    var element = js_content[0];
    if (element)
        element.style.display = "block";
}

function checkEmail() {
    var email = document.getElementById("email").value;
    var email_reg = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i;
    var isFieldCorrect = true;
    if (!email_reg.test(email)) { // la mail non è quindi del formato giusto
        // document.getElementById("email").style.borderColor = "red";
        document.getElementById("mail_error").innerHTML = "Email non è nel formato corretto (es. example@dominio.com)";
        isFieldCorrect = false;
    } else if (!email) {
        document.getElementById("mail_error").innerHTML = "Il campo mail non può essere lasciato vuoto";
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
    var email = checkEmail();
    var check_mail_object = checkMailObject();
    var check_mail_message = checkMailMessage();

    if (email && check_mail_message && check_mail_object)
        return true;
    else
        return false;
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
    return checkUsername() && checkPassword();
}



/* RECUPERO PASSWORD */

function checkEmailRecoverPassword() {
    return checkEmail();
}


/* Wines - Inserimento e Modifica Vino */

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

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_denominazione_error").innerHTML = "Il campo 'Denominazione' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_denominazione_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkGradazione() {
    var field = document.getElementsByName("gradazione")[0].value;
    var gradazione_reg = /^\d{1,2}\.\d$/i;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_gradazione_error").innerHTML = "Il campo 'Gradazione' non può essere vuoto";
        isFieldCorrect = false;
    } else if (field.length != 4 || !gradazione_reg.test(field)) {
        document.getElementById("wine_gradazione_error").innerHTML = "Gradazione non è nel formato corretto (es. 7.5 o 13.5)";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_gradazione_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkFormato() {
    var field = document.getElementsByName("formato")[0].value;
    var formato_reg = /^\d\.\d{2}$/i;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_formato_error").innerHTML = "Il campo 'Formato' non può essere vuoto";
        isFieldCorrect = false;
    } else if (field.length != 4 || !formato_reg.test(field)) {
        document.getElementById("wine_formato_error").innerHTML = "Formato non è nel formato corretto (es. 1.75)";
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

function checkInputPicture() {
    var isFieldCorrect = true;

    if (document.getElementById("select_file").value == "") {
        document.getElementById("wine_picture_error").innerHTML = "Il campo 'Foto' non può essere vuoto";
        isFieldCorrect = false;
    } else document.getElementById("wine_picture_error").innerHTML = " ";

    return isFieldCorrect;
}

function checkWine() {
    var nome = checkNome();
    var tipologia = checkTipologia();
    var vitigno = checkVitigno();
    var denominazione = checkDenominazione();
    var gradazione = checkGradazione();
    var formato = checkFormato();
    var descrizione = checkDescrizione();
    var abbinamento = checkAbbinamento();
    var degustazione = checkDegustazione();
    var picture = checkInputPicture();

    if (nome && tipologia && vitigno && denominazione && gradazione && formato && descrizione && abbinamento && degustazione &&
        picture) return true;
    else return false;

}

function checkModifyWine() {
    var nome = checkNome();
    var tipologia = checkTipologia();
    var vitigno = checkVitigno();
    var denominazione = checkDenominazione();
    var gradazione = checkGradazione();
    var formato = checkFormato();
    var descrizione = checkDescrizione();
    var abbinamento = checkAbbinamento();
    var degustazione = checkDegustazione();

    if (nome && tipologia && vitigno && denominazione && gradazione && formato && descrizione && abbinamento && degustazione)
        return true;
    else return false;

}

/* Rimozione dei vini */

function isAnyWineChecked() {
    var checkBoxes = document.getElementsByTagName("input");

    var isFieldCorrect = false;

    for (var i = 0; i < checkBoxes.length; i++) {
        if (checkBoxes[i].type.toLowerCase() == "checkbox") {
            if (checkBoxes[i].checked) {
                isFieldCorrect = true;
            }
        }
    }

    if (!isFieldCorrect) {
        document.getElementById("check_table").innerHTML = "Nessun elemento è stato selezionato";
    }

    return isFieldCorrect;
}

function checkThemAll() {
    var checkboxes = document.getElementsByClassName("admin_checkboxes");
    // se faccio check su tutti i vini, allora il messaggio d'errore, che indicherà
    // che non ci sono vini selezionati, deve scomparire
    removeErrorMessage();

    if (checkboxes.length > 0) // allora vuol dire che abbiamo siamo nella pagina dei vini
        for (var i = 0; i < checkboxes.length; i++)
            checkboxes[i].checked = true;

}

function uncheckThemAll() {
    var checkboxes = document.getElementsByClassName("admin_checkboxes");

    if (checkboxes.length > 0)
        for (var i = 0; i < checkboxes.length; i++)
            checkboxes[i].checked = false;
}


function deleteSelected() {
    // controllo che almeno un vino sia selezionato
    var check = isAnyWineChecked();
    return check;
}


// Ho bisogno di 2 variabili globali, per capire quale bottone è statto premuto
var isCancelPressed = false;
var isDeletionConfirmed = false;

// 'isCancelPressed' diventa true quando premo il bottone per tornare indietro
function goBackWines() {
    isCancelPressed = true;
}

// 'isDeletionConfirmed' diventa true se c'é almeno un elemento selezionato tra
// le checkbox, false altrimenti
function confirmDeletion() {
    isDeletionConfirmed = deleteSelected();
}

// ritorno 'true' se o si è premuto per tornare indietro oppure se esiste almeno
// un vino selezionato che si vuole eliminare e si ha premuto il tasto apposito.
// In alternativa, la funzione ritorna false, ed la submit non verrà eseguita
function finalDeletion() {
    if (isCancelPressed || isDeletionConfirmed) return true;
    else return false;
}

// rimuovo il messaggio che mi dice che non ci sono vini selezionati quando:
// - ho premuto una qualsiasi checkbox, e quindi ho almeno un vino selezionato
// - ho premuto seleziona tutti, nel menu' principale
function removeErrorMessage() {
    document.getElementById("check_table").innerHTML = " ";
}

/* GESTIONE ANNATE - ADMIN PANEL */

function checkYear() {
    var year = document.getElementById("check_year").value;
    var year_reg = /^\d{4}$/i;
    var isFieldCorrect = true;
    var currentYear = (new Date()).getFullYear();

    if (!year) {
        //document.getElementById("username").style.borderColor = "red";
        document.getElementById("year_error").innerHTML = "Il campo 'Anno' non può essere vuoto";
        isFieldCorrect = false;
    } else if (year.length != 4 || !year_reg.test(year)) {
        document.getElementById("year_error").innerHTML = "Formato sbagliato. Es: '2014'";
        isFieldCorrect = false;
    } else if (year > currentYear) {
        document.getElementById("year_error").innerHTML = "Il campo 'Anno' deve essere minore o uguale dell&apos;anno corrente: " + currentYear;
        isFieldCorrect = false;
    } else if (year <= 1900) {
        document.getElementById("year_error").innerHTML = "Il campo 'Anno' deve essere maggiore dell&apos;anno 1900";
        isFieldCorrect = false;
    } else {
        // document.getElementById("username").style.border = "1px inset";
        document.getElementById("year_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkYearDescription() {
    var description = document.getElementById("check_description").value;
    var isFieldCorrect = true;

    if (!description) {
        document.getElementById("description_error").innerHTML = "Il campo 'Descrizione' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        document.getElementById("description_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkYearQuality() {
    var quality = document.getElementById("check_quality").value;
    var isFieldCorrect = true;

    if (!quality) {
        document.getElementById("quality_error").innerHTML = "Il campo 'Qualità' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        document.getElementById("quality_error").innerHTML = " ";
    }
    return isFieldCorrect;
}

function fullyCheckYear() {
    var year = checkYear();
    var yeardescription = checkYearDescription();
    var yearquality = checkYearQuality();

    if (year && yeardescription && yearquality) return true;
    else return false;
}
/* Users: Admin Panel*/

function checkUserFirstName() {
    var firstname = document.getElementById("firstname").value;
    var isFieldCorrect = true;

    if (!firstname) {
        document.getElementById("firstname_error").innerHTML = "Il campo 'Nome' non può essere lasciato vuoto";
        isFieldCorrect = false;
    } else {
        document.getElementById("firstname_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkPasswordConfirmation() {
    var password = document.getElementById("password").value;
    var password_confirmation = document.getElementById("password_confirmation").value;

    var isFieldCorrect = true;

    if (!password_confirmation) {
        document.getElementById("confirm_password_error").innerHTML = "Il campo 'Conferma Password' non può essere lasciato vuoto";
        isFieldCorrect = false;
    } else if (password != password_confirmation) {
        document.getElementById("confirm_password_error").innerHTML = "Le due password non coincidono";
        isFieldCorrect = false;
    } else {
        document.getElementById("confirm_password_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkPasswordPanel() {
    var password_reg = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/i;
    var password = document.getElementById("password").value;

    var new_password = document.getElementById("new_password");

    var isFieldCorrect = checkPassword();

    if (isFieldCorrect) { // i soliti controlli sono andati a buon fine
        if (!password_reg.test(password)) { // se fallisce il controllo del formato
            document.getElementById("password_error").innerHTML = "La password deve essere lunga almeno 8 caratteri," +
                "contenere almeno una lettera minuscola, almeno una maiuscola e almeno un numero";
            isFieldCorrect = false;
        } else {
            isFieldCorrect = true;
        }
    }

    return isFieldCorrect;
}

function fullyCheckUser() {

    var firstname = checkUserFirstName();
    var username = checkUsername();
    var email = checkEmail();
    var password = checkPasswordPanel();
    var confirm_password = checkPasswordConfirmation();

    if (firstname && username && email && password && confirm_password) {
        return true;
    } else {
        return false;
    }
}

/* Modifica Utenti */

function checkModifyUser() {
    var firstname = checkUserFirstName();
    var username = checkUsername();
    var email = checkEmail();
    var password = checkPasswordPanel();

    if (firstname && username && email && password) {
        return true;
    } else {
        return false;
    }
}

/* Modifica Profilo */

function checkNewPassword() {
    // controllo il solito formato e che NON siano uguali
    var password_reg = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/i;
    var new_password = document.getElementById("new_password").value;
    var password = document.getElementById("password").value;

    var isFieldCorrect = true;

    if (!new_password) {
        //document.getElementById("password").style.borderColor = "red";
        document.getElementById("new_password_error").innerHTML = "Il campo 'Nuova Password' non può essere vuoto";
        isFieldCorrect = false;
    } else {
        // document.getElementById("password").style.border = "1px inset";
        document.getElementById("new_password_error").innerHTML = " ";

        if (!password_reg.test(new_password)) { // se fallisce il controllo del formato
            document.getElementById("new_password_error").innerHTML = "La password deve essere lunga almeno 8 caratteri," +
                "contenere almeno una lettera minuscola, almeno una maiuscola e almeno un numero";
            isFieldCorrect = false;
        } else if (password == new_password) {
            document.getElementById("new_password_error").innerHTML = "La nuova password non può essere uguale a quella precedente";
            isFieldCorrect = false;
        } else {
            isFieldCorrect = true;
        }

    }

    return isFieldCorrect;

}

function checkModifyProfile() {
    var firstname = checkUserFirstName();
    var username = checkUsername();
    var email = checkEmail();
    var password = document.getElementById("password").value;
    var new_password = document.getElementById("new_password").value;
    // se uno dei due campi password risulta inserito, allora devo fare il controllo password

    if (password || new_password) {
        var password = checkPasswordPanel();
        var new_password = checkNewPassword();

        if (firstname && username && email && password && new_password)
            return true;
        else return false;
    }

    if (firstname && username && email)
        return true;
    else
        return false;

}