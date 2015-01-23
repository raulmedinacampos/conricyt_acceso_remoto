function validateCaptcha()
{
	console.log("caller is " + arguments.callee.caller.toString());
    challengeField = $("input#recaptcha_challenge_field").val();
    responseField = $("input#recaptcha_response_field").val();
    //alert(challengeField);
    //alert(responseField);
    //return false;
    var html = $.ajax({
		type: "POST",
		url: "ajax.recaptcha.php",
		data: {
			recaptcha_challenge_field : challengeField,
			recaptcha_response_field : responseField
		},
		async: false
    }).responseText;

    if (html.replace(/^\s+|\s+$/, '') == "success")
    {
        $("#captchaStatus").html(" ");
        // Uncomment the following line in your application
        return true;
    }
    else
    {
        $("#captchaStatus").html("Your captcha is incorrect. Please try again");
        Recaptcha.reload();
        return false;
    }
}

