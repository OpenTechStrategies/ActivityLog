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
    if (is_callable($this->returnObject)) {
      $entry = new ManualLogEntry('activitylog', 'activity');

      list($user, $action, $target, $comment) = call_user_func($this->returnObject, ...$args);

      if (is_null($user)) { throw new Exception('Log user required.'); }
      if (is_null($action)) { throw new Exception('Log action required.'); }
      if (is_null($target)) { throw new Exception('Log target required.'); }

      $entry->setTarget($target);
      $entry->setPerformer($user);
      if ($comment) { $entry->setComment($comment); }

      $entry->setParameters([
        '4::action' => ' ' . $action . ' '
      ]);

      $logid = $entry->insert();
      $entry->publish($logid);
    } else {
      $log = new LogPage('activitylog', false);

      if (is_bool($this->returnObject)) {
        $action = $this->hookName;
      } elseif (is_string($this->returnObject)) {
        $action = $this->returnObject;
      } else {
        throw new Exception('Invalid ActivityLog hook handler.');
      }
      $log->addEntry('simpleactivity', $wgTitle, null, array($action));
    }

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
