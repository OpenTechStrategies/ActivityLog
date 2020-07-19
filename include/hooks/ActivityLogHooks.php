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

      $retn = call_user_func($this->returnObject, ...$args);
      $user = $retn[0];
      $target = $retn[1];
      $messageKey = $retn[2];
      $rest = array_slice($retn, 3);

      if (is_null($user)) { throw new Exception('Log user required.'); }
      if (is_null($target)) { throw new Exception('Log target required.'); }
      if (is_null($messageKey)) { throw new Exception('Log messageKey required.'); }

      $entry->setTarget($target);
      $entry->setPerformer($user);

      $parameters = ['4::' => $messageKey];

      $idx = 5;
      foreach($rest as $param) {
        $parameters["$idx::"] = $param;
      }

      $entry->setParameters($parameters);

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
