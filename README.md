# Extension:ActivityLog

The ActivityLog extension allows system administrators to set up logging for
arbitrary hooks.  These log messages are sent to the main Log page at
Special:Log

## Installation

* Download and place the file(s) in a directory called ActivityLog in your extensions/ folder
* Add the following line to your LocalSettings.php
```
wfLoadExtension('ActivityLog');
```

## Usage

Enable through adding to the `$wgActivityLogHooksToWatch` global variable,
then viewing through the Special:Log page.

The page title, hook name, and user are logged to the main Log page.

## Parameters

* `$wgActivityLogHooksToWatch` - The hooks to watch

Update by setting the key in the associative array to true, for any hook.
For example, you can log user logins via:

```php
$wgActivityLogHooksToWatch["UserLoginComplete"]
```

All the normal arguments that would be passed to those hook executions are
discarded.

## Internationalization

Currently only has support for English.
