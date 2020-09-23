<?php

class ActivityLogFormatter extends LogFormatter {

  protected function getActionMessage() {
    $params = $this->getMessageParameters();

    if('simpleactivity' == $this->entry->getSubType() || !isset($params[3]) || !is_string($params[3])) {
      return parent::getActionMessage();
    }
    $msg = $this->msg($params[3]);

    if(!$msg->exists()) {
      return parent::getActionMessage();
    }
    $msg->params($params);
    return $msg;
  }
}

?>
