# Create Verification Code

**Note:** Verification code instance must created using laravel **service container** or **dependency injection**

```php
$vc = app(\MEkramy\VerificationCode\VerificationCode::class);
```

**Note (Global/Private Mode)** If validation key mode set to private, generated verification code is only available for current ip!

**Note** Last parameter on all methods determine that the verification code is global(set to true) or private(set to false)!

## Create New Verification Code

To create a new code you need to call `put` method.

**Example:** Generate a verification code called **verify_email** with **12345** value and **5** min expiry:

```php
$vc->put('verify_email', '12345', 5); // Global
$vc->put('verify_email_for_current_ip_only', '45678', 5, false); // private
```

## Get Verification Code

**Note:** get method return `null` if code is expired or not exists.

```php
$vc->get('verify_email'); # > 12345
$vc->get('not_exists'); # > null
```

## Check If Verification Code Exists And Not Expired

```php
$vc->exists('verify_email'); # > true
$vc->exists('not_exists'); # > false
```

## Delete Verification Code

```php
$vc->remove('verify_email');
$vc->get('verify_email'); # > null
$vc->exists('verify_email'); # > false
```
