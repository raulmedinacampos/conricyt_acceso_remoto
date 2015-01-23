<?php
require_once('config.php');

$resp = recaptcha_check_answer ($privatekey,
                                $_SERVER["REMOTE_ADDR"],
                                trim($_POST["recaptcha_challenge_field"]),
                                trim($_POST["recaptcha_response_field"]));

if ($resp->is_valid) {
    echo 'success';
} else {
    die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." . "(reCAPTCHA said: " . $resp->error . ")");
}

