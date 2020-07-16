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

`$wgActivityLogHooksToWatch` is an associative array that configures how
ActivityLog will log [MediaWiki events](https://www.mediawiki.org/wiki/Manual:Hooks). 

``` php
$wgActivityLogHooksToWatch['ArticleViewHeader'] = true;
// output: 22:55, 1 July 2020 ArticleViewHeader

...

$wgActivityLogHooksToWatch['ArticleViewHeader'] = 'example string'
// output: 22:56, 1 July 2020 example string

// callback is invoked with hook arguments ($article, $outputDone, $pcache in this case)
$wgActivityLogHooksToWatch['ArticleViewHeader'] = function ($article) {
    // callback returns an array with the user (User), target (Title), a messageKey (String), 
    // following any additional parameters you might want that you can reference in the message
    return array($article->getContext()->getUser(), $article->getTitle(), $messageKey,
        $_SERVER['HTTP_REFERER']);
};
// output: 22:51, 1 July 2020 (link to user) (talk|contribs) visited (link to page)

```

For the `$messageKey` above, you'll need to include your own i18n file and let MediaWiki
know about it.  This can be done in the LocalSettings with something like:

```
$wgMessagesDirs['ActivityLogConfiguration'] = 'ActivityLogConfiguration/i18n';
```

You can read more about what those pages look like
[on the MediaWiki Localisation page](https://www.mediawiki.org/wiki/Localisation).

A listing of all the hooks can be found at:
[https://www.mediawiki.org/wiki/Manual:Hooks](https://www.mediawiki.org/wiki/Manual:Hooks)

## Internationalization

Currently only has support for English.
