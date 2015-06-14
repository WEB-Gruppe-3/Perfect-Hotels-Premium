// --------------------------------- Input placeholder text ---------------------------------
userInput = $("#usernameInput");
passInput = $("#passwordInput");

userInput.val("(brukernavn)");
passInput.val("(passord)");

userInput.focus(function() {
    if(userInput.val() === "(brukernavn)") {
        userInput.val("");
    }
});

userInput.blur(function() {
    if(userInput.val() === "") {
        userInput.val("(brukernavn)");
    }
});

passInput.focus(function() {
    if(passInput.val() === "(passord)") {
        passInput.val("");
    }
});

passInput.blur(function() {
    if(passInput.val() === "") {
        passInput.val("(passord)");
    }
});

// --------------------------------- Validating ---------------------------------
