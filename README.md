# User Security Check Extension

A security check for the PyroCMS Users module that ensures users are active, enabled, and authorized before granting access.

## Description

This extension provides an essential security layer for the Users module by validating user account status on each request. It prevents unauthorized access by checking that users are properly activated and enabled before allowing them to proceed with their actions.

## Features

- **Activation Check**: Verifies users have activated their accounts
- **Enabled Status**: Ensures user accounts haven't been disabled
- **Automatic Logout**: Logs out users who fail security checks
- **User Messages**: Provides clear feedback about access denial
- **Request Protection**: Validates on every authenticated request

## How It Works

The security check runs automatically for authenticated users and performs the following validations:

1. **Activation Verification** - Checks if the user account is activated
2. **Enabled Status** - Verifies the account hasn't been disabled by administrators
3. **Failed Check Handling** - Logs out invalid users and redirects with error message

### Security Flow

```
User Request → Security Check → Validation
                                    ↓
                        ┌───────────┴────────────┐
                        ↓                        ↓
                   Pass: Allow              Fail: Logout + Redirect
```

## Validation Checks

### Account Activation
- Ensures the user has completed email verification or activation process
- Prevents unactivated accounts from accessing the system
- Returns error: "Account is not activated"

### Account Enabled Status
- Verifies the account hasn't been manually disabled by an administrator
- Protects against suspended or banned users accessing the system
- Returns error: "Account is disabled"

## Usage

This extension works automatically once installed. No configuration is required.

### Manual Security Check

You can also manually trigger the security check in your code:

```php
use Anomaly\UsersModule\User\Contract\UserInterface;
use Anomaly\UserSecurityCheckExtension\UserSecurityCheckExtension;

$check = app(UserSecurityCheckExtension::class);
$result = $check->check($user);

if ($result !== true) {
    // Security check failed, $result contains redirect response
    return $result;
}
```

### In Middleware or Controllers

```php
use Anomaly\UsersModule\User\Contract\UserInterface;

public function handle($request, Closure $next)
{
    $user = auth()->user();
    
    if ($user instanceof UserInterface) {
        $check = app('Anomaly\UserSecurityCheckExtension\UserSecurityCheckExtension');
        $result = $check->check($user);
        
        if ($result !== true) {
            return $result;
        }
    }
    
    return $next($request);
}
```

## What Gets Checked

### User Activation Status
- **Email Verification**: User has verified their email address
- **Manual Activation**: Admin has activated the account
- **Activation Token**: Activation process completed successfully

### User Enabled Status
- **Administrator Actions**: Account hasn't been disabled by admin
- **Suspension Status**: User hasn't been suspended or banned
- **Account Status**: General enabled/disabled flag

## Error Messages

The extension provides localized error messages:

- `anomaly.extension.user_security_check::message.account_is_not_activated`
- `anomaly.extension.user_security_check::message.account_is_disabled`

These messages can be customized through the language files in `resources/lang/`.

## Integration

This extension integrates with:

- **Users Module** - Provides security checks for user authentication
- **Authentication System** - Validates users during login and requests
- **Message System** - Displays error messages to users
- **Redirect System** - Handles failed check redirections

## Security Best Practices

### When Users Are Checked
- On login attempts
- During authenticated requests
- Before accessing protected resources
- When performing sensitive operations

### Failed Check Actions
1. User is immediately logged out
2. Error message is displayed
3. User is redirected back to previous page
4. Session is cleared

## Use Cases

- **Email Verification**: Ensure users verify emails before access
- **Manual Approval**: Require admin approval before granting access
- **Account Suspension**: Immediately block disabled accounts
- **Security Compliance**: Meet security requirements for user validation
- **Audit Requirements**: Track and enforce account status

## Requirements

- PyroCMS 3.x
- Anomaly Streams Platform ^1.8
- Anomaly Users Module

## Customization

### Custom Error Messages

Publish and modify language files:

```bash
php artisan streams:publish user_security_check-extension
```

Then edit the language files in `resources/lang/[locale]/message.php`.

### Extending the Check

Create your own security check extension:

```php
<?php namespace Acme\CustomSecurityCheckExtension;

use Anomaly\UsersModule\User\Contract\UserInterface;
use Anomaly\UsersModule\User\Security\SecurityCheckExtension;

class CustomSecurityCheckExtension extends SecurityCheckExtension
{
    protected $provides = 'acme.extension.custom_security_check';
    
    public function check(UserInterface $user = null)
    {
        if (!$user) {
            return true;
        }
        
        // Add your custom security logic
        if (!$this->passesCustomCheck($user)) {
            return redirect()->back()->withError('Custom check failed');
        }
        
        return true;
    }
}
```

## Support

- **Email**: support@anomaly.is
- **Website**: http://pyrocms.com/
- **Documentation**: [PyroCMS Documentation](https://pyrocms.com/documentation)

## License

This extension is open-sourced software licensed under the [MIT license](LICENSE.md).

## Authors

- **PyroCMS, Inc.** - [Website](http://pyrocms.com/) - support@pyrocms.com
