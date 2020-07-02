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
    // callback returns an array with the user (User), action (string), target (Title), and an 
    // optional comment (string)
    return array($article->getContext()->getUser(), 'visited', $article->getTitle());
};
// output: 22:51, 1 July 2020 (link to user) (talk|contribs) visited (link to page)

```


[https://www.mediawiki.org/wiki/Manual:Hooks](https://www.mediawiki.org/wiki/Manual:Hooks)

## Internationalization

Currently only has support for English.
