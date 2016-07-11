<?php

/**
 * @file
 * Contains \Drupal\migrate_example\Plugin\migrate\source\BeerNode.
 */

namespace Drupal\bund_drupal_starterkit_dummycontent\Plugin\migrate\source;

use Drupal\migrate_source_csv\Plugin\migrate\source\CSV;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Row;

/**
 * Source plugin for menu items.
 *
 * @MigrateSource(
 *   id = "menu_item"
 * )
 */
class MenuItem extends CSV {

  public function __construct(array $configuration, $plugin_id, $plugin_definition, MigrationInterface $migration) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $migration);

    //override the path to point to the local module directory
    $this->configuration['path'] = drupal_get_path('module', 'bund_drupal_starterkit_dummycontent') . '/data/navigation_menu_item.csv';
    $this->configuration['delimiter'] = ';';
  }

  /**
   * {@inheritdoc}
   */
  public function prepareRow(Row $row) {
    if (parent::prepareRow($row) === FALSE) {
      return FALSE;
    }

    $csv_data = $row->getSource();

    $row->setSourceProperty('name', '');
    $row->setSourceProperty('parent', '');
    // Add empyt link path as no contents yet to link to homepage by default
    $row->setSourceProperty('link_path', '');
    $row->setSourceProperty('options', array());

    for ($i = 4; $i > 0; $i--) {
      if (!empty($csv_data['Level_' . $i])) {
        $row->setSourceProperty('name', $csv_data['Level_' . $i]);
        if ($i > 1) {
          $row->setSourceProperty('parent_name', $csv_data['Level_' . ($i - 1)]);
        }
        break;
      }
    }
  }
}
