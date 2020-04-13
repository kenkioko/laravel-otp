# Laravel OTP ▲

## Introduction 🖖

This is a simple package to generate and validate OTPs (One Time Passwords). This can be implemented mostly in Authentication.
This is a fork from [ichtrojan/laravel-otp](https://github.com/ichtrojan/laravel-otp).
In this version the default `$identifier` type is `App\User` instead of `string` type.

## Installation 💽

Install via composer

```bash
composer require kenkioko/laravel-otp
```

Add service provider to the `config/app.php` file

```php
<?php
   /*
    |--------------------------------------------------------------------------
    | Autoloaded Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Feel free to add your own services to
    | this array to grant expanded functionality to your applications.
    |
    */

    'providers' => [
        ...
        Kenkioko\OTP\OTPServiceProvider::class,
    ];
...
```

Add alias to the `config/app.php` file

```php
<?php

   /*
    |--------------------------------------------------------------------------
    | Class Aliases
    |--------------------------------------------------------------------------
    |
    | This array of class aliases will be registered when this application
    | is started. However, feel free to register as many as you wish as
    | the aliases are "lazy" loaded so they don't hinder performance.
    |
    */

    'aliases' => [
        ...
        'OTP' => Kenkioko\OTP\OTP::class,
    ];
...
```

Publish files with:

```bash
php artisan vendor:publish --provider="Kenkioko\OTP\OTPServiceProvider"
```

Run Migrations

```bash
php artisan migrate
```

## Usage 🧨

>**NOTE**</br>
>Response are returned as objects. You can access its attributes with the arrow operator (`->`)

### Generate OTP

```php
<?php

OTP::generate(App\User $identifier, int $digits = 4, int $validity = 5)
```

* `$identifier`: The identity that will be tied to the OTP of type `\App\User::class`.
* `$digit (optional | default = 4)`: The amount of digits to be generated, can be any of 4, 5 and 6.
* `$validity (optional | default = 5)`: The validity period of the OTP in minutes.

#### Sample

```php
<?php

$user = App\User::find(1);
$otp = OTP::generate($user, 6, 15);
```

This will generate a six digit OTP that will be valid for 15 minutes and the success response will be:

```object
{
  "status": true,
  "token": "282581",
  "message": "OTP generated"
}
```

### Validate OTP

```php
<?php

OTP::validate(App\User $identifier, string $token)
```

* `$identifier`: The identity that is tied to the OTP of type `\App\User::class`.
* `$token`: The token tied to the identity.

#### Sample

```php
<?php

$user = App\User::find(1);
$otp = OTP::generate($user, '282581');
```

### Extend Expiry of OTP

```php
<?php

OTP::extend(App\User $identifier, string $token, int $validity = 1)
```

* `$identifier`: The identity that is tied to the OTP of type `\App\User::class`.
* `$token`: The token tied to the identity.
* `$validity (optional | default = 1)`: The validity period of the OTP in minutes.

#### Sample

```php
<?php

$user = App\User::find(1);
$otp = OTP::extend($user, '282581', 5);
```

#### Responses

Uses Laravel's localization features to show the messages.
The translations are found in `translations\en\messages.php` file.
Please use the file as a template for other languages.

**On Success**

```object
{
  "status": true,
  'message' => __("laravel-otp::messages.otp_message", ['password' => '12345']),
  // "otp_message" => "Your one-time password (OTP) to enter the system is: :password"
}
```

**Valid***

```object
{
  "status": false,
  'message' => __("laravel-otp::messages.otp_valid"),
  // "otp_valid" => "The one-time password (OTP) token is valid."
}
```

**Not Valid***

```object
{
  "status": false,
  'message' => __("laravel-otp::messages.otp_invalid"),
  // "otp_invalid" => "The one-time password (OTP) token is  not valid".
}
```

**Expired**

```object
{
  "status": false,
  'message' => __("laravel-otp::messages.otp_expired"),
  // "otp_expired" => "token seems to be expired. Please request a new OTP code."
}
```

**Missing field**

```object
{
  "status": false,
  'message' => __("laravel-otp::messages.otp_missing"),
  // "otp_missing" => "The one-time password (OTP) token does not exist.",
}
```

**Mismatch**

```object
{
  "status": false,
  'message' => __("laravel-otp::messages.otp_mismatch"),
  // "otp_mismatch" => "The one-time password (OTP) token doesn't match any token in database."
}
```

**Expiry extended**

```object
{
  "status": false,
  'message' => __("laravel-otp::messages.otp_extended", ['minutes' => 5 ]),
  // "otp_extended" => "Your one-time password (OTP) token expiry was extended by :minutes minutes."
}
```

## Contribution

This is a fork from [ichtrojan/laravel-otp](https://github.com/ichtrojan/laravel-otp).

If you find an issue with this package or you have any suggestion please help out. I am not perfect.
