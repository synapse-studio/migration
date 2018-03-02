<?php

namespace Drupal\migration;

use Drupal\migrate\Plugin\migrate\source\SourcePluginBase;
use Drupal\migrate\Plugin\MigrationInterface;

/**
 * Source for Plugins.
 */
class MigrationsSourceBase extends SourcePluginBase {

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);
    // SourcePlugin Settings.
    $this->trackChanges = TRUE;

    $uipage = $this->uiPage();
    $config = self::accessProtected($migration, 'pluginDefinition');
    $this->config = $config['process'];
    // Debug switcher.
    $this->debug = FALSE;
    $rows = $this->getRows();
    $this->rows = $rows;
    if ($this->uipage && $this->debug) {
      if (\Drupal::moduleHandler()->moduleExists('devel')) {
        dsm("{$plugin_id}: ProcessMapping & Rows");
        dsm($this->config);
        dsm($rows);
      }
    }
  }

  /**
   * Get Rows.
   */
  public function getRows() {
    $rows = [];
    $this->rows = $rows;
    return $rows;
  }

  /**
   * UiPage.
   */
  public function uiPage() {
    $uipage = FALSE;
    $statuspage = FALSE;
    if (\Drupal::routeMatch()->getRouteName() == "entity.migration.list") {
      $uipage = TRUE;
    }
    if (\Drupal::routeMatch()->getRouteName() == "cmlmigrations.status") {
      $statuspage = TRUE;
    }
    $this->uipage = $uipage;
    $this->statuspage = $statuspage;
    return $uipage;
  }

  /**
   * Access Protected Obj Property.
   */
  public static function accessProtected($obj, $prop) {
    $reflection = new \ReflectionClass($obj);
    $property = $reflection->getProperty($prop);
    $property->setAccessible(TRUE);
    return $property->getValue($obj);
  }

  /**
   * {@inheritdoc}
   */
  public function initializeIterator() {
    return new \ArrayIterator($this->rows);
  }

  /**
   * Allows class to decide how it will react when it is treated like a string.
   */
  public function __toString() {
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function getIDs() {
    return [
      'uuid' => [
        'type' => 'string',
        'alias' => 'id',
      ],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function fields() {
    $fields = [
      'uuid' => $this->t('UUID Key'),
    ];
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function count() {
    return count($this->rows);
  }

}
