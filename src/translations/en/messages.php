<?php

return [

    /*
    |--------------------------------------------------------------------------
    | The Messages
    |--------------------------------------------------------------------------
    |
    | The following language lines are used during OTP authentication for various
    | messages that we need to display to the user.
    |
    */
    "otp_message" => "Your one-time password (OTP) to enter the system is: :password",
    "otp_valid" => "The one-time password (OTP) token is valid.",
    "otp_invalid" => "The one-time password (OTP) token is not valid.",
    "otp_missing" => "The one-time password (OTP) token does not exist.",
    "otp_expired" => "Your one-time password (OTP) token seems to be expired. Please request a new OTP code.",
    "otp_extended" => "Your one-time password (OTP) token expiry was extended by :minutes mins",
    "code_mismatch" => "The one-time password (OTP) token doesn't match any token in database.",

];
