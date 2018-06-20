<?php

namespace Drupal\migration\Controller;

/**
 * FeedsToMigration.
 */
class FeedsToMigration {

  /**
   * Import.
   */
  public static function run($src, $dst, $fid) {
    // $src = "node__feeds_item"; // Example.
    // $dst = "migrate_map_user"; // Example.
    dsm([$src, '>', $dst]);
    $fields = [
      'entity_id',
      'feeds_item_guid',
      'feeds_item_imported',
      'feeds_item_hash',
    ];
    $query = \Drupal::database()->select($src, 'feeds');
    $query->fields('feeds', $fields);
    $query->condition('feeds_item_target_id', $fid);
    $result = $query->execute();
    $output = [];
    $k = 0;
    while ($row = $result->fetchAssoc()) {
      $term_migrated = [
        'source_ids_hash' => $row['feeds_item_hash'],
        'sourceid1' => $row['feeds_item_guid'],
        'destid1' => $row['entity_id'],
        'source_row_status' => 1,
        'rollback_action' => 0,
        'last_imported' => 0,
        'hash' => "",
      ];
      $output[$k] = $term_migrated;
      $k++;
      $hash = hash('sha256', serialize(array_map('strval', [$row['feeds_item_guid']])));
      // Insert the record to table.
      $insert = \Drupal::database()->insert($dst)
        ->fields([
          'source_ids_hash',
          'sourceid1',
          'destid1',
          'source_row_status',
          'rollback_action',
          'last_imported',
          'hash',
        ])
        ->values(array(
          $hash,
          $row['feeds_item_guid'],
          $row['entity_id'],
          0,
          0,
          0,
          "",
        ));
      // $insert->execute();
    }
    dsm($output);
  }

}
