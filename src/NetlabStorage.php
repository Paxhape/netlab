<?php

/**
* @FirmaController
*Contains \Drupal\firma\FirmaStorage.
*/

namespace Drupal\netlab;

class NetlabStorage {

  public static function load() {
    $select = db_select('node_field_data', 'n');
    $select->fields('n',array('title','created','changed'));
    $select->condition('n.status',1)
    ->orderBy('n.created','DESC')
    ->range(0,5);
    return $select->execute()->fetchAll();
  }

}
