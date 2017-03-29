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

  public static function running_load(){
    $select = db_select('running_topology', 'rt');
    $select->join('reservation', 'r', 'r.reservation_id=rt.reservation_id' );
    $select->join('topology', 't', 't.topology_id=r.topology_id');
    $select->join('users_field_data','u','u.uid=r.user_id');
    $select->fields('rt', array('pid_dynamips','pid_dynagen','hypervisor_port'))
    ->fields('u', array('name'))
    ->fields('t',array('topo_name','description','ram_resources'));
    return $select->execute()->fetchAll();
  }

  public static function topo_reserve(){
    $select  = db_select('topology','t');
    $select->fields('t',array('topo_name'));
    $select->condition('t.active',1);
    return $select->execute()->fetchAll();
  }

  public static function term_reserve(){
    $select  = db_select('term','t');
    $select->fields('t',array('term_date'));
    $select->condition('t.free_capacity',0,'<>');
    return $select->execute()->fetchAll();
  }

  public static function cancel_reserve(){
    $select = db_select('reservation','r');
    $select->join('term','d','d.term_id=r.term_id');
    $select->join('topology','t','t.topology_id=r.topology_id');
    $select->fields('d',array('term_date'))
    ->fields('t',array('topo_name','description'));
    return $select->execute()->fetchAll();
  }

}
