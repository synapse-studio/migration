<?php

namespace Drupal\migration\Plugin\migrate\source;

use Drupal\migration\MigrationsSourceBase;
use Drupal\migration\Controller\FeedsToMigration;
use Drupal\Component\Serialization\Json;
use Symfony\Component\Yaml\Yaml;

/**
 * Source for CSV.
 *
 * @MigrateSource(
 *   id = "migration_n_invoice"
 * )
 */
class NodeBillingInvoice extends MigrationsSourceBase {

  /**
   * {@inheritdoc}
   */
  public function getRows() {
    $rows = [];
    $file = './invoices.json';
    $json = file_get_contents($path);
    $data = Json::decode($json);
    $rows = self::parce($data);
    return $rows;
  }

  /**
   * Parce.
   */
  public static function parce($rows = FALSE) {
    $fields = [];
    if (!empty($rows)) {
      foreach ($rows as $id => $doc) {
        $products = [
          'list' => $doc['products'],
          'total' => $doc['products_total'],
        ];
        $date = format_date((int) $doc['invoice']['date'], 'custom', 'Y-m-d\Th:i:s');
        $fields[$id] = [
          'id' => $id,
          'uid' => $doc['partner']['nid'],
          'type' => 'billing_invoice',
          'title' => $doc['invoice']['title'],
          'status' => 1,
          'created' => (int) $doc['invoice']['date'],
          'field_migration_id' => $id,
          'info' => [
            'status' => self::statusVal($doc['invoice']['status']),
            'total' => $doc['invoice']['total'],
            'date' => $date,
            'number' => $doc['invoice']['no'],
            'partner' => $doc['partner']['nid'],
            'from' => $doc['from']['nid'],
            'manager' => $doc['invoice']['mng'],
            'billing_type' => $doc['invoice']['option'],
          ],
          'invoice' => self::encode($doc['invoice']),
          'products' => self::encode($products),
          'customer' => self::encode($doc['partner']),
          'provider' => self::encode($doc['from']),
          'contract' => self::encode($doc['dogovor']),
          'destination' => 'na',
        ];
      }
      if (FALSE) {
        $src = 'node__feeds_item';
        $dst = 'migrate_map_node_invoice';
        $fid = 5;
        FeedsToMigration::run($src, $dst, $fid);
      }
    }
    return $fields;
  }

  /**
   * Encode.
   */
  public static function encode($data, $type = 'unicode') {
    if ($type == 'unicode') {
      return json_encode($data, JSON_UNESCAPED_UNICODE);
    }
    elseif ($type == 'yml') {
      return Yaml::dump($data, 4);
    }
    else {
      return Json::encode($data);
    }
  }

}
