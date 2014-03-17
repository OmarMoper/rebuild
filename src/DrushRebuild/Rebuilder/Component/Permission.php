<?php

/**
 * @file
 * Permissions related code.
 */

/**
 * Handles permission grant/revoke functions.
 *
 * Compatible with Drupal 6/7/8.
 */
class Permissions extends Rebuilder {

  /**
   * Constructor.
   */
  public function __construct() {
    $this->config = parent::getConfig();
    $this->environment = parent::getEnvironment();
    // Build permissions_grant and permissions_revoke arrays.
    $this->permissions_grant = array();
    $this->permissions_revoke = array();
    if (isset($this->config['drupal']['permissions'])) {
      foreach ($this->config['drupal']['permissions'] as $role => $permissions) {
        if (isset($permissions['grant'])) {
          $this->permissions_grant[$role] = implode(", ", $permissions['grant']);
        }
        if (isset($permissions['revoke'])) {
          $this->permissions_revoke[$role] = implode(", ", $permissions['revoke']);
        }
      }
    }
  }

  /**
   * Start the process of granting / revoking permissions.
   *
   * @param string $op
   *   Valid options are 'grant' or 'revoke'.
   *
   * @return bool
   *   Return TRUE/FALSE on success/error.
   */
  protected function execute($op) {
    if ($op == 'grant') {
      // Grant permissions.
      if (isset($this->permissions_grant) && !empty($this->permissions_grant)) {
        drush_log('Granting permissions', 'ok');

        // Loop through the user roles and build an array of permissions.
        foreach ($this->permissions_grant as $role => $permissions_string) {
          // Check if multiple permission strings are defined for the role.
          if (strpos($permissions_string, ",") > 0) {
            $permissions = explode(",", $permissions_string);
            foreach ($permissions as $perm) {
              // Grant the permission.
              drush_log(dt('Granting "!perm" to the "!role" role.', array('!perm' => trim($perm), '!role' => $role)), 'ok');
              parent::drushInvokeProcess($this->environment, 'role-add-perm', array(
                sprintf('"%s"', $role),
                sprintf('"%s"', trim($perm)),
              ));
            }
          }
          else {
            // Grant the permission.
            drush_log(dt('Granting "!perm" to the "!role" role.', array('!perm' => trim($permissions_string), '!role' => $role)), 'ok');
            parent::drushInvokeProcess($this->environment, 'role-add-perm', array(
              sprintf('"%s"', $role),
              sprintf('"%s"', trim($permissions_string)),
              ));
          }
        }

      }
    }

    if ($op == 'revoke') {
      // Revoke permissions.
      if (isset($this->permissions_revoke) && !empty($this->permissions_revoke)) {
        drush_log('Revoking permissions', 'ok');
        // Loop through the user roles and build an array of permissions.
        foreach ($this->permissions_revoke as $role => $permissions_string) {
          // Check if multiple permission strings are defined for the role.
          if (strpos($permissions_string, ",") > 0) {
            $permissions = explode(",", $permissions_string);
            foreach ($permissions as $perm) {
              // Revoke the permission.
              drush_log(dt('Revoking "!perm" for the "!role" role.', array('!perm' => trim($perm), '!role' => $role)), 'ok');
              parent::drushInvokeProcess($this->environment, 'role-remove-perm', array(
                sprintf('"%s"', $role),
                sprintf('"%s"', trim($perm)),
              ));
            }
          }
          else {
            // Revoke the permission.
            drush_log(dt('Revoking "!perm" for the "!role" role.', array('!perm' => trim($permissions_string), '!role' => $role)), 'ok');
            parent::drushInvokeProcess($this->environment, 'role-remove-perm', array(
              sprintf('"%s"', $role),
              sprintf('"%s"', trim($permissions_string)),
            ));
          }
        }
      }
    }
    return TRUE;
  }
}