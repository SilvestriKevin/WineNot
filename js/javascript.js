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
    var gradazione_reg = /\d{2}\.\d|\d\.\d{2}/i;

    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_gradazione_error").innerHTML = "Il campo 'Gradazione' non può essere vuoto";
        isFieldCorrect = false;
    } else if (field.length != 4 || !gradazione_reg.test(field)) {
        document.getElementById("wine_gradazione_error").innerHTML = "Formato sbagliato. Esempio: 7.50 oppure 13.5";
        isFieldCorrect = false;
    } else {
        // document.getElementsByName("object")[0].style.border = "1px inset";
        document.getElementById("wine_gradazione_error").innerHTML = " ";
    }

    return isFieldCorrect;
}

function checkFormato() {
    var field = document.getElementsByName("formato")[0].value;
    var formato_reg = /\d\.\d{2}/i;
    var isFieldCorrect = true;

    if (!field) {
        // document.getElementsByName("object")[0].style.borderColor = "red";
        document.getElementById("wine_formato_error").innerHTML = "Il campo 'Formato' non può essere vuoto";
        isFieldCorrect = false;
    } else if (field.length != 4 || !formato_reg.test(field)) {
        document.getElementById("wine_formato_error").innerHTML = "Formato sbagliato. Esempio: 1.50 oppure 5.00";
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
    var check = checkNome();
    check = checkTipologia();
    check = checkVitigno();
    check = checkDenominazione();
    check = checkGradazione();
    check = checkFormato();
    check = checkDescrizione();
    check = checkAbbinamento();
    check = checkDegustazione();
    check = checkInputPicture();
    return check;
}

function checkModifyWine() {
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
        document.getElementById("check_table").innerHTML = "Nessun vino è stato selezionato";
    }

    return isFieldCorrect;
}

function checkThemAll() {
    var checkboxes = document.getElementsByClassName("admin_wines_checkbox");
    // se faccio check su tutti i vini, allora il messaggio d'errore, che indicherà
    // che non ci sono vini selezionati, deve scomparire
    var checkboxes_years = document.getElementsByClassName("admin_years_checkbox");

    removeErrorMessage();

    if (checkboxes.length > 0) {    // allora vuol dire che abbiamo siamo nella pagina dei vini
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = true;
        }
    } else if (checkboxes_years.length > 0) { // altrimenti siamo nella pagina delle annate
        for (var i = 0; i < checkboxes_years.length; i++) { 
            checkboxes_years[i].checked = true;
        }
    }
}

function uncheckThemAll() {
    var checkboxes = document.getElementsByClassName("admin_wines_checkbox");

    var checkboxes_years = document.getElementsByClassName("admin_years_checkbox");
    
    if(checkboxes.length > 0) {
        for (var i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = false;
        }   
    } else if(checkboxes_years.length > 0) {
        for (var i = 0; i < checkboxes_years.length; i++) {
            checkboxes_years[i].checked = false;
        }
    }
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