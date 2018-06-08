'use strict';

$('.message a').click(function () {
    $('form').animate({ height: "toggle", opacity: "toggle" }, "slow");
});

function passwordMatch() {
    var firstPsw = $("#psw1").val();
    var secondPsw = $("#psw2").val();

    if (firstPsw != secondPsw) {
        $("#message").html("Passwords don't match, please enter again");
    }
    else {
        $("#message").html("Passwords match!");
    }
}

