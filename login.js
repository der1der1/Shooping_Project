function goto_login() {
    document.getElementById("panel").className = "panel_log";
    document.getElementById("panel_login").className = "panel_login";
    document.getElementById("panel_signup").className = "none";
    document.getElementById("choice_login").className = "choice_login_signup";
    document.getElementById("choice_signup").className = "none";
}

function goto_signup() {
    document.getElementById("panel").className = "panel_sign";
    document.getElementById("panel_login").className = "none";
    document.getElementById("panel_signup").className = "panel_signup_show";
    document.getElementById("choice_login").className = "none";
    document.getElementById("choice_signup").className = "choice_login_signup";
}