<?php

namespace Drupal\migration\Hook;

use Drupal\Core\Controller\ControllerBase;
use Drupal\migration\Controller\ExecMigration;

/**
 * Hook Cron.
 */
class Cron extends ControllerBase {

  /**
   * Hook.
   */
  public static function hook() {
    $manager = FALSE;
    try {
      $manager = \Drupal::service('plugin.manager.migration');
    }
    catch (\Exception $e) {
      return FALSE;
    }
    if ($manager) {
      $migration = $manager->createInstance('migration_call');
      ExecMigration::exec('exec');
    }
  }

}
