<?php

namespace Drupal\migration\Plugin\migrate\source;

use Drupal\migration\MigrationsSourceBase;
use Drupal\Component\Serialization\Json;

/**
 * Source for CSV.
 *
 * @MigrateSource(
 *   id = "migration_country"
 * )
 */
class NodeCountryMigrationPlugin extends MigrationsSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getRows() {
    $k = 0;
    $rows = [];
    $filename = "https://restcountries.eu/rest/v2/all";
    $json = file_get_contents($filename);
    $source = Json::decode($json);
    if ($source) {
      foreach ($source as $key => $row) {
        if ($k++ < 700 || !$this->uipage) {
          $id = $row["alpha2Code"];
          $rows[$id] = [
            'uuid' => $id,
            'type' => 'country',
            'sticky' => 0,
            'uid' => 1,
            'status' => 1,
            'title' => $row["name"],
            'changed' => time(),
            'body_value' => FALSE,
            'body_format' => "wysiwyg",
          ];
        }
      }
    }
    $this->debug = TRUE;
    return $rows;
  }

}
