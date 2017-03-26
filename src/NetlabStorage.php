<?php

/**
* @FirmaController
*Contains \Drupal\firma\FirmaStorage.
*/

namespace Drupal\netlab;

class NetlabStorage {

  public static function topo_load() {
    $select = db_select('topology', 'n');
    $select->fields('n',array('topo_name','description','author','created','ram_resources','console_count'));
    $select->condition('n.active',1);
    return $select->execute()->fetchAll();
  }

  public static function reser_load(){
    $select = db_select('reservation','r');
    $select->join('users_field_data','u', 'r.user_id=u.uid');
    $select->join('topology','t', 't.topology_id=r.topology_id');
    $select->join('term','d','d.term_id=r.term_id');
    $select->fields('t', array('topo_name','description'))
    ->fields('d', array('term_date'))
    ->fields('u',array('name'));
    return $select->execute()->fetchAll();
  }

}
