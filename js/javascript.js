/* FORM CONTACTS */
function checkEmail() {
    var email = document.getElementById("email").value;
    var email_reg = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i;

    if(!email_reg.test(email)) { // la mail non è quindi del formato giusto
        document.getElementById("email").style.borderColor = "red";
    } else document.getElementById("email").style.border = "1px inset";
}

function checkMailObject() {
    var oggetto = document.getElementsByName("object")[0].value;

    if(!oggetto) document.getElementsByName("object")[0].style.borderColor = "red";
        else document.getElementsByName("object")[0].style.border = "1px inset";
}

function checkMailMessage() {
    var content = document.getElementById("textarea_form_help").value;

    if(!content) document.getElementById("textarea_form_help").style.borderColor = "red";
        else document.getElementById("textarea_form_help").style.border = "1px inset";
}