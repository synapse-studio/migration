<?php

namespace Drupal\migration\Controller;

/**
 * @file
 * Contains \Drupal\synhelper\Controller\Page.
 */
use Drupal\Core\Controller\ControllerBase;

/**
 * Controller routines for page example routes.
 */
class ExecMigration extends ControllerBase {

  /**
   * Exec.
   */
  public static function exec($mode, $update = FALSE) {
    $config = \Drupal::config('migration.settings');
    $drush = self::getDrush($config);
    $root = DRUPAL_ROOT;
    $result = "";
    $cmd = "{$drush} mim --group=import --root=$root";
    if ($update) {
      $cmd = "$cmd  --update";
    }
    $result .= "CMD: $cmd\n";
    if ($mode == 'nohup') {
      $cmd = "nohup $cmd > nohup.out 2> nohup.err < /dev/null &";
      $result .= "\nEXEC: $cmd\n";
      \Drupal::logger(__CLASS__)->notice("exec: $cmd");
      exec($cmd);
    }
    elseif ($mode == 'exec') {
      $result .= "$cmd\n";
      $result .= shell_exec($cmd);
    }
    elseif ($mode == 'drush') {
      $cmd = "drush --version";
      $result .= "\nEXEC: $cmd\n";
      $result .= shell_exec($cmd);
    }
    elseif ($mode == 'debug') {
      $cmd = "ls";
      $result .= "\nEXEC: $cmd\n";
      $result .= shell_exec($cmd);
    }
    return $result;
  }

  /**
   * Drush.
   */
  public static function getDrush($config) {
    $drush = "/usr/local/bin/drush";
    if ($config->get('drush')) {
      $drush = $config->get('drush');
    }
    return $drush;
  }

}
