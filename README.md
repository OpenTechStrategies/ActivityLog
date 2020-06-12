# Extension:ActivityLog

The ActivityLog extension allows system administrators to set up logging for
arbitrary hooks.  These log messages are currently sent to the main Log page 
at `Special:Log`.

## Installation

* Download and place the file(s) in a directory called ActivityLog in your extensions/ folder
* Add the following line to your LocalSettings.php
```php
wfLoadExtension('ActivityLog');
```

## Usage

Configure new hooks by adding them to the `$wgActivityLogHooksToWatch` array in
LocalSettings.php.

``` php
$wgActivityLogHooksToWatch['ArticleViewHeader'] = function ($article) {
    return 'Visited article "' . $article->getTitle() . '"';
};
```

`$wgActivityLogHooksToWatch` takes booleans, strings, and anonymous functions. A
`true` value will log the hook name, A string will log the string itself, and a
callback will be invoked with all the arguments that are passed by the hook.

[https://www.mediawiki.org/wiki/Manual:Hooks](https://www.mediawiki.org/wiki/Manual:Hooks)

## Internationalization

Currently only has support for English.
