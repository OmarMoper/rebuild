<?php

/**
 * @file
 * Module related code.
 */

/**
 * Handles module enable/disable functions.
 */
class Module extends Rebuilder {

  /**
   * Constructor.
   *
   * @param Rebuilder $rebuilder
   *   The Rebuilder class object.
   */
  public function __construct(Rebuilder $rebuilder) {
    $this->rebuilder = $rebuilder;
  }

  /**
   * Start the process of enabling / disabling modules.
   *
   * @param string $op
   *   Valid options are 'enable' or 'disable'.
   */
  public function start($op) {
    if ($this->op == 'enable') {
      // Enable modules.
      if (is_array($this->rebuilder->modules_enable)) {
        drush_log('Enabling modules', 'ok');
        $ret = drush_invoke_process($this->rebuilder->environment, 'pm-enable', $this->rebuilder->modules_enable, array('quiet' => TRUE));
        if ($ret['error_status'] == 0) {
          drush_log(dt('Enabled modules: !module.', array('!module' => implode(",", $this->rebuilder->modules_enable))), 'ok');
        }
        else {
          return drush_set_error(dt('An error occurred. %error', array('%error' => print_r($ret['error_log'], TRUE))));
        }
      }
    }

    if ($this->op == 'disable') {
      // Disable modules.
      if (is_array($this->rebuilder->modules_disable)) {
        drush_log('Disabling modules', 'ok');
        $ret = drush_invoke_process($this->rebuilder->environment, 'pm-disable', $this->rebuilder->modules_disable, array('quiet' => TRUE));
        if ($ret['error_status'] == 0) {
          drush_log(dt('Disabled modules: !module.', array('!module' => implode(", ", $this->rebuilder->modules_disable))), 'ok');
        }
        else {
          return drush_set_error(dt('An error occurred. %error', array('%error' => print_r($ret['error_log'], TRUE))));
        }
      }
    }
    return TRUE;
  }
}