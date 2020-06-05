<?php

class ActivityLogExecutor {
  public $hookName;
  public $returnObject;

  public function __construct($hookName, $returnObject) {
    $this->hookName = $hookName;
    $this->returnObject = $returnObject;
  }

  public function execute(...$args) {
    global $wgTitle;
    $log = new LogPage('activitylog', false);

    if (is_bool($this->returnObject)) {
      $comment = $this->hookName;
    } elseif (is_string($this->returnObject)) {
      $comment = $this->returnObject;
    } elseif (is_callable($this->returnObject)) {
      $comment = call_user_func($this->returnObject, ...$args);
    } else {
      throw new Exception('Invalid ActivityLog hook handler.');
    }

    $log->addEntry('activity', $wgTitle, $comment);

    return true;
  }
}

class ActivityLogHooks {
  public static function onBeforeInitialize(...$args) {
    global $wgActivityLogHooksToWatch, $wgHooks;

    foreach($wgActivityLogHooksToWatch as $hookName => $returnObject) {
      $wgHooks[$hookName][] = [new ActivityLogExecutor($hookName, $returnObject), 'execute'];
    }
  }
}
