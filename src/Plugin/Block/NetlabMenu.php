<?php

/**
 * @file
 * Contains \Drupal\netlab\Plugin\Block\NetlabMenu.
 */

namespace Drupal\netlab\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * @Block(
 *   id = "firma_block"
 * )
 */


class NetlabMenu extends BlockBase {

  public function build(){
    $build='';

    $reservation=

    $select = db_select('node_field_data','n');
    $select->fields('n', array('nid','title'));
    $select->condition('n.status',1)
    ->orderBy('n.created','DESC')
    ->range(0,5);
    $entries = $select->execute()->fetchAll();

    $rows = array();
    foreach ($entries as $entry) {
      $url = Link::createFromRoute($entry->title,
      'entity.node.canonical',
      ['node' => $entry->nid],
      ['attributes' => ['class' => 'my-link-class']]);
      $rows[] = $url;
    }

    return array(
    '#theme' => 'item_list' ,
    '#items' => $rows,
    '#list_type' => 'ol'
    );
  }

}
