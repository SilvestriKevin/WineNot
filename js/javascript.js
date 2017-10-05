/*function showSlides(n) {
    var i;
    var slides = document.getElementsByClassName("slides");
    var dots = document.getElementsByClassName("dot");
    if (n > slides.length) {slideIndex = 1} 
    if (n < 1) {slideIndex = slides.length}
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none"; 
    }
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }
    slides[slideIndex-1].style.display = "block"; 
    dots[slideIndex-1].className += " active";
}
/* login utente 

function currentSlide(n) {
    showSlides(slideIndex = n);
}

function start() {
    var slideIndex = 1;
    currentSlide(1);
    var x = document.getElementsByClassName("nascosta");
    var i;
    for (i = 0; i < x.length; i+=1) {
        x[i].style.display = "block";
    }
    showSlides(1);
}

/* slideshow 
function plusSlides(n) {
    showSlides(slideIndex += n);
}

var time = 1;

var interval = setInterval(function() { 
   if (time <= 4) { 
      currentSlide(time);
      time++;
   }
   else { 
       currentSlide(1);
       time=2;
   }
}, 5000);


/* Placeholder*/
function placeHolder(e) {
  if (e.value == 'example@gmail.com' || e.value == 'AAAA-MM-GG') {  
    e.value = '';
  }
}

function hide(object){
          object.children[1].children[2].style.visibility = 'hidden';
}

function show(object){
          object.children[1].children[2].style.visibility = 'visible';
}

/* Disable*/
function remove(aux) {
   
     document.getElementById(aux).show();
    alert('culo');
}

/* Modify User */
function checkProfile() { //DA RIVEDERE, NON FUNZIA
    var email, datanascita, password;
    var bol = true;

    email = document.getElementById("email").value;
    datanascita = document.getElementById("data_nascita").value;
    password = document.getElementById("password").value;

    document.getElementById("email").style.borderColor = "#DDDDDD";
    document.getElementById("data_nascita").style.borderColor = "#DDDDDD";
    document.getElementById("password").style.borderColor = "#DDDDDD";

    var email_reg_exp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i;
    if (!email_reg_exp.test(email)) {
        document.getElementById("err_email").innerHTML = "formato Email non corretto";
        document.getElementById("email").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_email").innerHTML = "";
    if (!email) {
        document.getElementById("err_email").innerHTML = "inserire l'email";
        document.getElementById("email").style.borderColor = "red";
        bol = false;

    }

    var data_nascita_reg_exp = /^((19\d{2})|(20\d{2}))-(((02)-(0[1-9]|[1-2][0-9]))|(((0(1|[3-9]))|(1[0-2]))-(0[1-9]|[1-2][0-9]|30))|((01|03|05|07|08|10|12)-(31)))$/;
    if (!data_nascita_reg_exp.test(datanascita) && datanascita != "") {
        document.getElementById("err_data_nascita").innerHTML = "formato data non corretto";
        document.getElementById("data_nascita").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_data_nascita").innerHTML = "";
    if (!datanascita) {
        document.getElementById("err_data_nascita").innerHTML = "inserire la data di nascita";
        document.getElementById("data_nascita").style.borderColor = "red";
        bol = false;
    }
    var pwd_reg_exp = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
    if (!pwd_reg_exp.test(password)) {
        document.getElementById("err_pwd").innerHTML = "formato password non corretto";
        document.getElementById("password").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_pwd").innerHTML = "";
    if (!password) {
        document.getElementById("err_pwd").innerHTML = "inserire la password";
        document.getElementById("password").style.borderColor = "red";
        bol = false;
    }
    return bol;
}

function checkLogin() {
    var email, password;
    var bol = true;
    email = document.getElementById("email").value;
    password = document.getElementById("password").value;
    document.getElementById("password").style.borderColor = "#DDDDDD";
    document.getElementById("email").style.borderColor = "#DDDDDD";
    var pwd_reg_exp = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
    if (!pwd_reg_exp.test(password)) {
        document.getElementById("err_pwd").innerHTML = "formato password non corretto";
        document.getElementById("password").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_pwd").innerHTML = "";
    if (!password) {
        document.getElementById("err_pwd").innerHTML = "inserire la password";
        document.getElementById("password").style.borderColor = "red";
        bol = false;
    }

    var email_reg_exp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i;
    if (!email_reg_exp.test(email)) {
        document.getElementById("err_email").innerHTML = "formato email non corretto";
        document.getElementById("email").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_email").innerHTML = "";
    if (!email) {
        document.getElementById("err_email").innerHTML = "inserire l'email";
        document.getElementById("email").style.borderColor = "red";
        bol = false;

    }

    return bol;
}

/* registrazione  */

function checkReg() {
    var nome, cognome, datanascita, email, pwd, conf_pwd;
    var bol = true;
    nome = document.getElementById("nome").value;
    cognome = document.getElementById("cognome").value;
    datanascita = document.getElementById("data_nascita").value;
    email = document.getElementById("email").value;
    pwd = document.getElementById("password").value;
    conf_pwd = document.getElementById("conferma_password").value;

    document.getElementById("nome").style.borderColor = "#DDDDDD";
    document.getElementById("cognome").style.borderColor = "#DDDDDD";
    document.getElementById("data_nascita").style.borderColor = "#DDDDDD";
    document.getElementById("email").style.borderColor = "#DDDDDD";
    document.getElementById("password").style.borderColor = "#DDDDDD";
    document.getElementById("conferma_password").style.borderColor = "#DDDDDD";

    if (!nome) {
        document.getElementById("err_nome").innerHTML = "inserire il nome";
        document.getElementById("nome").style.borderColor = "red";
        bol = false;
    } else document.getElementById("err_nome").innerHTML = "";

    if (!cognome) {
        document.getElementById("err_cognome").innerHTML = "inserire il cognome";
        document.getElementById("cognome").style.borderColor = "red";
        bol = false;
    } else document.getElementById("err_cognome").innerHTML = "";

    var data_nascita_reg_exp = /^((19\d{2})|(20\d{2}))-(((02)-(0[1-9]|[1-2][0-9]))|(((0(1|[3-9]))|(1[0-2]))-(0[1-9]|[1-2][0-9]|30))|((01|03|05|07|08|10|12)-(31)))$/;
    if (!data_nascita_reg_exp.test(datanascita) && datanascita != "") {
        document.getElementById("err_data_nascita").innerHTML = "formato data non corretto";
        document.getElementById("data_nascita").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_data_nascita").innerHTML = "";
    if (!datanascita) {
        document.getElementById("err_data_nascita").innerHTML = "inserire la data di nascita";
        document.getElementById("data_nascita").style.borderColor = "red";
        bol = false;
    }

    var email_reg_exp = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i;
    if (!email_reg_exp.test(email)) {
        document.getElementById("err_email").innerHTML = "formato email non corretto";
        document.getElementById("email").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_email").innerHTML = "";
    if (!email) {
        document.getElementById("err_email").innerHTML = "inserire l'email";
        document.getElementById("email").style.borderColor = "red";
        bol = false;

    }

    var pwd_reg_exp = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
    if (!pwd_reg_exp.test(pwd)) {
        document.getElementById("err_pwd").innerHTML = "formato password non corretto";
        document.getElementById("password").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_pwd").innerHTML = "";
    if (!pwd) {
        document.getElementById("err_pwd").innerHTML = "inserire la password";
        document.getElementById("password").style.borderColor = "red";
        bol = false;
    }

    var confpwd_reg_exp = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
    if (!confpwd_reg_exp.test(conf_pwd)) {
        document.getElementById("err_conf_pwd").innerHTML = "formato password non corretto";
        document.getElementById("conferma_password").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_conf_pwd").innerHTML = "";
    if (!conf_pwd) {
        document.getElementById("err_conf_pwd").innerHTML = "inserire la conferma password";
        document.getElementById("conferma_password").style.borderColor = "red";
        bol = false;
    }


    if (conf_pwd != pwd) {
        document.getElementById("err_no_match").innerHTML = "Le password non coincidono";
        document.getElementById("password").style.borderColor = "red";
        document.getElementById("conferma_password").style.borderColor = "red";
        bol = false;
    } else document.getElementById("err_no_match").innerHTML = "";

    return bol;
}

/* Inserimento immobili  */
function checkPlace() {
    var regione, via, civico, descrizione, dim_immobile, n_piani, regione, provincia, citta;
    var bol = true;
    regione = document.getElementById("regione").value;
    provincia = document.getElementById("provincia").value;
    citta = document.getElementById("citta").value;
    via = document.getElementById("via").value;
    civico = document.getElementById("civico").value;
    descrizione = document.getElementById("textarea_form_place").value;
    dim_immobile = document.getElementById("dimensione").value;
    n_piani = document.getElementById("num_piani").value;


    //#CC0000; color di default degli input in un form

    document.getElementById("regione").style.borderColor = "#DDDDDD;";
    document.getElementById("provincia").style.borderColor = "#DDDDDD;";
    document.getElementById("citta").style.borderColor = "#DDDDDD;";
    document.getElementById("via").style.borderColor = "#DDDDDD";
    document.getElementById("civico").style.borderColor = "#DDDDDD";
    document.getElementById("textarea_form_place").style.borderColor = "#DDDDDD;";
    document.getElementById("dimensione").style.borderColor = "#DDDDDD;";
    document.getElementById("num_piani").style.borderColor = "#DDDDDD;";

    if (!regione) {
        document.getElementById("err_regione").innerHTML = "inserire la regione";
        document.getElementById("regione").style.borderColor = "red";
        bol = false;
    } else document.getElementById("err_regione").innerHTML = "";

    if (!provincia) {
        document.getElementById("err_provincia").innerHTML = "inserire la provincia";
        document.getElementById("provincia").style.borderColor = "red";
        bol = false;
    } else document.getElementById("err_provincia").innerHTML = "";

    if (!citta) {
        document.getElementById("err_citta").innerHTML = "inserire la citta";
        document.getElementById("citta").style.borderColor = "red";
        bol = false;
    } else document.getElementById("err_citta").innerHTML = "";

    if (!via) {
        document.getElementById("err_via").innerHTML = "inserire la via";
        document.getElementById("via").style.borderColor = "red";
        bol = false;
    } else document.getElementById("err_via").innerHTML = "";

    if (!civico) {
        document.getElementById("err_civico").innerHTML = "inserire il civico";
        document.getElementById("civico").style.borderColor = "red";
        bol = false;
    } else document.getElementById("err_civico").innerHTML = "";

    if (!descrizione) {
        document.getElementById("err_descrizione").innerHTML = "inserire la descrizione";
        document.getElementById("textarea_form_place").style.borderColor = "red";
        bol = false;
    } else document.getElementById("err_descrizione").innerHTML = "";

    if (!dim_immobile) {
        document.getElementById("err_dimensione").innerHTML = "inserire la dimensione";
        document.getElementById("dimensione").style.borderColor = "red";
        bol = false;

    } else {
        var dim_immobile_reg_exp = /^[0-9]{1,3}$/;
        if (!dim_immobile_reg_exp.test(dim_immobile) && dim_immobile != "") {
            document.getElementById("err_dimensione").innerHTML = "Deve essere in formato numerico compreso tra 1 e 999";
            document.getElementById("dimensione").style.borderColor = "red";
            bol = false;
        } else if (dim_immobile == '0') {
            document.getElementById("err_dimensione").innerHTML = "Inserire un numero compreso tra 1 e 999";
            document.getElementById("dimensione").style.borderColor = "red";
            bol = false;
        } else document.getElementById("err_dimensione").innerHTML = "";
    }


    if (!n_piani) {
        document.getElementById("err_num_piani").innerHTML = "inserire il numero di piani";
        document.getElementById("num_piani").style.borderColor = "red";
        bol = false;
    } else {
        var num_piani_reg_exp = /^[0-9]{1,2}$/;
        if ((!num_piani_reg_exp.test(n_piani) && n_piani != "")) {
            document.getElementById("err_num_piani").innerHTML = "Deve essere in formato numerico compreso tra 1 e 999";
            document.getElementById("num_piani").style.borderColor = "red";
            bol = false;
        } else if (n_piani == '0') {
            document.getElementById("err_num_piani").innerHTML = "Inserire un numero compreso tra 1 e 99";
            document.getElementById("num_piani").style.borderColor = "red";
            bol = false;
        } else document.getElementById("err_num_piani").innerHTML = "";
    }


    return bol;
}
/*checkAd*/
function checkAd(){
    var data_inizio, data_fine, costo;
    var bol = true;
    data_inizio = document.getElementById("data_inizio").value;
    data_fine = document.getElementById("data_fine").value;
    costo = document.getElementById("costo").value;

    document.getElementById("data_inizio").style.borderColor = "#DDDDDD";
    document.getElementById("data_fine").style.borderColor = "#DDDDDD";
    document.getElementById("costo").style.borderColor = "#DDDDDD";
 
var data_i_reg_exp = /^((19\d{2})|(20\d{2}))-(((02)-(0[1-9]|[1-2][0-9]))|(((0(1|[3-9]))|(1[0-2]))-(0[1-9]|[1-2][0-9]|30))|((01|03|05|07|08|10|12)-(31)))$/;
    if (!data_i_reg_exp.test(data_inizio) && data_inizio != "") {
        document.getElementById("err_data_i").innerHTML = "formato data errato";
        document.getElementById("data_inizio").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_data_i").innerHTML = "";
    if (!data_inizio) {
        document.getElementById("err_data_i").innerHTML = "inserire la data d'inizio";
        document.getElementById("data_inizio").style.borderColor = "red";
        bol = false;
    }


var data_f_reg_exp = /^((19\d{2})|(20\d{2}))-(((02)-(0[1-9]|[1-2][0-9]))|(((0(1|[3-9]))|(1[0-2]))-(0[1-9]|[1-2][0-9]|30))|((01|03|05|07|08|10|12)-(31)))$/;
    if (!data_f_reg_exp.test(data_fine) && data_fine != "") {
        document.getElementById("err_data_f").innerHTML = "formato data errato";
        document.getElementById("data_fine").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_data_f").innerHTML = "";
    if (!data_fine) {
        document.getElementById("err_data_f").innerHTML = "inserire la data di fine";
        document.getElementById("data_fine").style.borderColor = "red";
        bol = false;
    }
    
     var costo_reg_exp = /^[0-9]{1,10}$/;
        if ((!costo_reg_exp.test(costo) && costo != "")) {
            document.getElementById("err_costo").innerHTML = "Deve essere in formato numerico";
            document.getElementById("costo").style.borderColor = "red";
            bol = false;
        }else document.getElementById("err_costo").innerHTML = "";
    if (!costo){
         document.getElementById("err_costo").innerHTML = "Inserire un costo per notte";
            document.getElementById("costo").style.borderColor = "red";
            bol = false;
    }

    return bol;
    
}

function checkRating(){
    var rating;
    bol=true;
    rating = document.getElementById("rating").value;
    document.getElementById("rating").style.borderColor = "#DDDDDD";
    var rating_reg_exp = /^(([0-4].[0-9])|([5].[0]))$|^([0-5])$/;
        if ((!rating_reg_exp.test(rating) && rating != "")) {
            document.getElementById("err_rating").innerHTML = "Deve essere in formato numerico tra 0.0 e 5.0";
            document.getElementById("rating").style.borderColor = "red";
            bol = false;
        }else document.getElementById("err_rating").innerHTML = "";
    if(!rating){
        document.getElementById("err_rating").innerHTML = "Inserire un rating";
            document.getElementById("rating").style.borderColor = "red";
            bol=false;
    }
    return bol;
}

function checkRoom(){
    var dimensione;
    bol=true;
    dimensione = document.getElementById("dimensione").value;
    document.getElementById("dimensione").style.borderColor = "#DDDDDD";
    
        var dim_reg_exp = /^[0-9]{1,3}$/;
        if (!dim_reg_exp.test(dimensione) && dimensione != "") {
            document.getElementById("err_dimensione").innerHTML = "Deve essere in formato numerico compreso tra 1 e 999";
            document.getElementById("dimensione").style.borderColor = "red";
            bol = false;
        } else if (dimensione == '0') {
            document.getElementById("err_dimensione").innerHTML = "Inserire in formato numerico compreso tra 1 e 999";
            document.getElementById("dimensione").style.borderColor = "red";
            bol = false;
        } else document.getElementById("err_dimensione").innerHTML = "";
    return bol;
}

function checkChangePwd(){
    var old_pwd, pwd, conf_pwd;
    var bol = true;
    
    old_pwd = document.getElementById("old_pwd").value;
    pwd = document.getElementById("pwd").value;
    conf_pwd = document.getElementById("conf_pwd").value;

    document.getElementById("old_pwd").style.borderColor = "#DDDDDD";
    document.getElementById("pwd").style.borderColor = "#DDDDDD";
    document.getElementById("conf_pwd").style.borderColor = "#DDDDDD";
    
    var oldpwd_reg_exp = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
    if (!oldpwd_reg_exp.test(old_pwd)) {
        document.getElementById("err_old_pwd").innerHTML = "formato password non corretto";
        document.getElementById("old_pwd").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_old_pwd").innerHTML = "";
        

    if (!pwd) {
        document.getElementById("err_pwd").innerHTML = "inserire la password";
        document.getElementById("pwd").style.borderColor = "red";
        bol = false;
    }
        var pwd_reg_exp = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
    if (!pwd_reg_exp.test(pwd)) {
        document.getElementById("err_pwd").innerHTML = "formato password non corretto";
        document.getElementById("pwd").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_pwd").innerHTML = "";
    if (!pwd) {
        document.getElementById("err_pwd").innerHTML = "inserire la password";
        document.getElementById("pwd").style.borderColor = "red";
        bol = false;
    }

    var confpwd_reg_exp = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
    if (!confpwd_reg_exp.test(conf_pwd)) {
        document.getElementById("err_conf_pwd").innerHTML = "formato password non corretto";
        document.getElementById("conf_pwd").style.borderColor = "red";
        bol = false;

    } else document.getElementById("err_conf_pwd").innerHTML = "";
    
    if (!conf_pwd) {
        document.getElementById("err_conf_pwd").innerHTML = "inserire la conferma password";
        document.getElementById("conf_pwd").style.borderColor = "red";
        bol = false;
    }


    if (conf_pwd != pwd) {
        document.getElementById("err_no_match").innerHTML = "Le password non coincidono";
        document.getElementById("pwd").style.borderColor = "red";
        document.getElementById("conf_pwd").style.borderColor = "red";
        bol = false;
    } else document.getElementById("err_no_match").innerHTML = "";
    
    return bol;
}

function checkPwd(){
    var pwd;

    pwd = document.getElementById("pwd").value;

    document.getElementById("pwd").style.borderColor = "#DDDDDD";
    
    if (!pwd) {
        document.getElementById("err_pwd").innerHTML = "inserire la password";
        document.getElementById("pwd").style.borderColor = "red";
        return false;
    }
        var pwd_reg_exp = /^.*(?=.{8,})(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).*$/;
    if (!pwd_reg_exp.test(pwd)) {
        document.getElementById("err_pwd").innerHTML = "formato password non corretto";
        document.getElementById("pwd").style.borderColor = "red";
        return false;

    } else document.getElementById("err_pwd").innerHTML = "";

return true;
}

















/* testdrive  */
function checkData() {
    var data;
    var espressione = new RegExp(/^\d{4}-\d{2}-\d{2}$/);
    data = document.getElementById("sceltaData").value;
    document.getElementById("sceltaData").style.borderColor = "#DDDDDD";

    document.getElementById("errore").innerHTML = "";
    if (!espressione.test(data) || data === "") {
        document.getElementById("errore").innerHTML = "data inserita con un formato non corretto(YYYY-mm-dd)";
        document.getElementById("sceltaData").style.borderColor = "red";
        return false;
    }
    return true;
}

/* modifica account*/



function checkAcc() {
    var telefono, email, password, password2;
    telefono = document.getElementById("telefono").value;
    email = document.getElementById("email").value;
    password = document.getElementById("password").value;
    password2 = document.getElementById("password2").value;

    document.getElementById("telefono").style.borderColor = "#DDDDDD";
    document.getElementById("email").style.borderColor = "#DDDDDD";
    document.getElementById("password").style.borderColor = "#DDDDDD";
    document.getElementById("password2").style.borderColor = "#DDDDDD";

    document.getElementById("errore").innerHTML = "";
    var conta = 0;
    var contaBis = 0;
    var errore = "";
    if (!telefono || telefono === "") {
        conta++;
        errore = "inserire il telefono";
        document.getElementById("telefono").style.borderColor = "red";
    }
    if (!email || email === "") {
        conta++;
        errore = "inserire l'email";
        document.getElementById("email").style.borderColor = "red";
    }
    if (!password || password === "") {
        conta++;
        errore = "inserire la password";
        document.getElementById("password").style.borderColor = "red";
    }
    if (!password2 || password2 === "") {
        contaBis;
        errore = "inserire la conferma della password";
        document.getElementById("password2").style.borderColor = "red";
    }
    var erroreBis;

    if (password2 != password) {
        contaBis++;
        erroreBis = "le password non coincidono";
        document.getElementById("password2").style.borderColor = "red";
    }
    if (isNaN(telefono) && !telefono === "") {
        contaBis++;
        erroreBis = "telefono non valido";
        document.getElementById("telefono").style.borderColor = "red";
    }
    var email_reg_exp = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-]{2,})+\.)+([a-zA-Z0-9]{2,})+$/;
    if (!email_reg_exp.test(email) && !mail === "") {
        contaBis++;
        erroreBis = "email non valida";
        document.getElementById("email").style.borderColor = "red";
    }
    if (conta === 1 && contaBis === 0) {
        document.getElementById("errore").innerHTML = errore;
    }
    if (conta === 1 && contaBis === 1) {
        document.getElementById("errore").innerHTML = errore + " <br/>" + erroreBis;
    }
    if (conta === 0 && contaBis === 1) {
        document.getElementById("errore").innerHTML = erroreBis;
    }

    if (contaBis >= 2) {
        document.getElementById("errore").innerHTML = "campi compilati in modo scorretto";
    }
    if (conta >= 2)
        document.getElementById("errore").innerHTML = "tutti i campi devono essere compilati";
    if (conta + contaBis === 0)
        return true;
    else
        return false;
}

/* modifica riparazione */

function checkMRip() {
    var data, descr, ora;
    data = document.getElementById("data").value;
    descr = document.getElementById("descr").value;
    ora = document.getElementById("ora").value;
    document.getElementById("data").style.borderColor = "#DDDDDD";
    document.getElementById("descr").style.borderColor = "#DDDDDD";
    document.getElementById("ora").style.borderColor = "#DDDDDD";
    var conta = 0;
    var contaBis = 0;
    var espressione = new RegExp(/^\d{4}-\d{2}-\d{2}$/);
    var espressioneora = new RegExp(/^\d{2}:\d{2}:\d{2}$/);
    var errore, erroreBis;
    if (data === "") {
        conta++;
        errore = "inserire data";
        document.getElementById("data").style.borderColor = "red";
    }

    if (descr === "") {
        conta++;
        errore = "inserire descrizione";
        document.getElementById("descr").style.borderColor = "red";
    }

    if (ora === "") {
        conta++;
        errore = "inserire ora";
        document.getElementById("ora").style.borderColor = "red";
    }

    if (!espressione.test(data) && !data === "") {
        contaBis++;
        erroreBis = "formato data non valido";
        document.getElementById("data").style.borderColor = "red";
    }
    if (!espressioneora.test(ora) && !ora === "") {
        contaBis++;
        erroreBis = "formato ora non valido";
        document.getElementById("ora").style.borderColor = "red";
    }

    if (conta === 1 && contaBis === 0) {
        document.getElementById("errore").innerHTML = errore;
    }
    if (conta === 1 && contaBis === 1) {
        document.getElementById("errore").innerHTML = errore + " <br/>" + erroreBis;
    }
    if (conta === 0 && contaBis === 1) {
        document.getElementById("errore").innerHTML = erroreBis;
    }
    if (contaBis >= 2) {
        document.getElementById("errore").innerHTML = "campi compilati in modo scorretto";
    }
    if (conta >= 2)
        document.getElementById("errore").innerHTML = "tutti i campi devono essere compilati";

    if (conta + contaBis === 0)
        return true;
    else
        return false;
}


/* modifica riparazione */

function checkNTest() {
    var data, ora;
    data = document.getElementById("data").value;
    ora = document.getElementById("ora").value;
    document.getElementById("data").style.borderColor = "#DDDDDD";
    document.getElementById("ora").style.borderColor = "#DDDDDD";
    var conta = 0;
    var contaBis = 0;
    var espressione = new RegExp(/^\d{4}-\d{2}-\d{2}$/);
    var espressioneora = new RegExp(/^\d{2}:\d{2}:\d{2}$/);
    var errore, erroreBis;
    if (data === "") {
        conta++;
        errore = "inserire data";
        document.getElementById("data").style.borderColor = "red";
    }

    if (ora === "") {
        conta++;
        errore = "inserire ora";
        document.getElementById("ora").style.borderColor = "red";
    }

    if (!espressione.test(data) && !data === "") {
        contaBis++;
        erroreBis = "formato data non valido";
        document.getElementById("data").style.borderColor = "red";
    }
    if (!espressioneora.test(ora) && !ora === "") {
        contaBis++;
        erroreBis = "formato ora non valido";
        document.getElementById("ora").style.borderColor = "red";
    }
    if (conta === 1 && contaBis === 0) {
        document.getElementById("errore").innerHTML = errore;
    }
    if (conta === 1 && contaBis === 1) {
        document.getElementById("errore").innerHTML = errore + " <br/>" + erroreBis;
    }
    if (conta === 0 && contaBis === 1) {
        document.getElementById("errore").innerHTML = erroreBis;
    }
    if (contaBis >= 2) {
        document.getElementById("errore").innerHTML = "campi compilati in modo scorretto";
    }
    if (conta >= 2)
        document.getElementById("errore").innerHTML = "tutti i campi devono essere compilati";

    if (conta + contaBis === 0)
        return true;
    else
        return false;
}


function checkAnn() {
    var numTel, marca, modello, prezzo, alim, cilindrata, optional, colore, km, descrizione, file;
    numTel = document.getElementById("numTel").value;
    marca = document.getElementById("marca").value;
    modello = document.getElementById("modello").value;
    prezzo = document.getElementById("prezzo").value;
    alim = document.getElementById("alim").value;
    cilindrata = document.getElementById("cilindrata").value;
    optional = document.getElementById("optional").value;
    colore = document.getElementById("colore").value;;
    km = document.getElementById("km").value;
    descrizione = document.getElementById("descrizione").value;
    file = document.getElementById("file").value;

    document.getElementById("numTel").style.borderColor = "#DDDDDD";
    document.getElementById("marca").style.borderColor = "#DDDDDD";
    document.getElementById("modello").style.borderColor = "#DDDDDD";
    document.getElementById("prezzo").style.borderColor = "#DDDDDD";
    document.getElementById("alim").style.borderColor = "#DDDDDD";
    document.getElementById("cilindrata").style.borderColor = "#DDDDDD";
    document.getElementById("optional").style.borderColor = "#DDDDDD";
    document.getElementById("colore").style.borderColor = "#DDDDDD";
    document.getElementById("km").style.borderColor = "#DDDDDD";
    document.getElementById("descrizione").style.borderColor = "#DDDDDD";
    document.getElementById("file").style.borderColor = "#DDDDDD";

    document.getElementById("errore").innerHTML = "";
    var conta = 0;
    var contaBis = 0;
    var errore = "";
    var erroreBis = "";

    if (numTel === "") {
        conta++;
        errore = "inserire il numero di telaio";
        document.getElementById("numTel").style.borderColor = "red";
    }
    if (marca === "") {
        conta++;
        errore = "inserire la marca";
        document.getElementById("marca").style.borderColor = "red";
    }
    if (modello === "") {
        conta++;
        errore = "inserire il modello";
        document.getElementById("modello").style.borderColor = "red";
    }
    if (prezzo === "") {
        conta++;
        errore = "inserire il prezzo";
        document.getElementById("prezzo").style.borderColor = "red";
    }
    if (alim === "") {
        conta++;
        errore = "inserire alimentazione";
        document.getElementById("alim").style.borderColor = "red";
    }
    if (cilindrata === "") {
        conta++;
        errore = "inserire la cilindrata";
        document.getElementById("cilindrata").style.borderColor = "red";
    }
    if (optional === "") {
        conta++;
        errore = "inserire gli optional";
        document.getElementById("optional").style.borderColor = "red";
    }
    if (colore === "") {
        conta++;
        errore = "inserire il colore";
        document.getElementById("colore").style.borderColor = "red";
    }
    if (km === "") {
        conta++;
        errore = "inserire i km";
        document.getElementById("km").style.borderColor = "red";
    }
    if (descrizione === "") {
        conta++;
        errore = "inserire la descrizione";
        document.getElementById("descrizione").style.borderColor = "red";
    }
    if (marca === "") {
        conta++;
        errore = "inserire la marca";
        document.getElementById("marca").style.borderColor = "red";
    }
    if (isNaN(prezzo) && prezzo !== "") {
        contaBis++;
        erroreBis = "prezzo non valido";
        document.getElementById("prezzo").style.borderColor = "red";
    }
    if (isNaN(km) && km !== "") {
        contaBis++;
        erroreBis = "formato kilometraggio non valido";
        document.getElementById("km").style.borderColor = "red";
    }
    if (isNaN(cilindrata) && cilindrata !== "") {
        contaBis++;
        erroreBis = "cilindrata non valida";
        document.getElementById("cilindrata").style.borderColor = "red";
    }
    if (conta === 1 && contaBis === 0) {
        document.getElementById("errore").innerHTML = errore;
    }
    if (conta === 1 && contaBis === 1) {
        document.getElementById("errore").innerHTML = errore + " <br/>" + erroreBis;
    }
    if (conta === 0 && contaBis === 1) {
        document.getElementById("errore").innerHTML = erroreBis;
    }

    if (contaBis >= 2) {
        document.getElementById("errore").innerHTML = "campi compilati in modo scorretto";
    }
    if (conta >= 2)
        document.getElementById("errore").innerHTML = "tutti i campi devono essere compilati";
    if (conta + contaBis === 0)
        return true;
    else
        return false;
}
