<?php

/**
* @FirmaController
*Contains \Drupal\firma\NetlabStorage.
*/

namespace Drupal\netlab;

class NetlabStorage {

/*
* Variables
*/
$student = "student";

/**
*
*RESERVATION DB Functions
*
*/
//Reservations list
  public static function reser_load($user_role){
    $select = db_select('reservation','r');
    $select->join('users_field_data','u', 'r.user_id=u.uid');
    $select->join('topology','t', 't.topology_id=r.topology_id');
    $select->join('term','d','d.term_id=r.term_id');
    $select->fields('t', array('topo_name','description'))
    ->fields('d', array('term_date'))
    ->fields('u',array('name'));
    if((strcmp($user_role,$student))==0){
      $select->condition('r.user_id',$uid);
    }
    return $select->execute()->fetchAll();
  }
//Do Reservation
  public static function topo_reserve(){
    $select  = db_select('topology','t');
    $select->fields('t',array('topo_name'));
    $select->condition('t.active',1);
    return $select->execute()->fetchAll();
  }

  public static function term_reserve(){
    $select  = db_select('term','t');
    $select->fields('t',array('term_date'));
    $select->condition('t.free_capacity',0,'>');
    return $select->execute()->fetchAll();
  }
//all reservations with id
  public static function edit_reserve($user_role){
    $select = db_select('reservation','r');
    $select->join('term','d','d.term_id=r.term_id');
    $select->join('topology','t','t.topology_id=r.topology_id');
    $select->join('users_field_data','u', 'r.user_id=u.uid');
    $select->fields('r','reservation_id')
    ->fields('t',array('topo_name','description'))
    ->fields('u','name')
    ->fields('d','term_date');
    if((strcmp($user_role,$student))==0){
      $select->condition('r.user_id',$uid);
    }
    return $select->execute()->fetchAll();
  }
// only reservations id
  public static function get_reservation_id($user_role){
    $select = db_select('reservation','r');
    $select->join('users_field_data','u','r.user_id=u.uid');
    $select->fields('r','reservation_id');
    if((strcmp($user_role,$student))==0){
      $select->condition('r.user_id',$uid);
    }
    return $select->execute()->fetchAll();
  }



  /**
  *
  *TOPOLOGY DB Functions
  *
  */
  public static function topo_load() {
    $select = db_select('topology', 'n');
    $select->fields('n',array('topo_name','description','author','created','ram_resources','console_count'));
    $select->condition('n.active',1);
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
  public static function stop_topology($user_name){
    $select = db_select('running_topology','rt');
    $select->join('reservation','r','r.reservation_id=rt.reservation_id');
    $select->join('users_field_data','u','u.uid=r.user_id');
    $select->join('topology','t','r.topology_id=t.topology_id');
    $select->fields('t',array('topo_name'));
    $select->condition('u.uid',$user_name);
    return $select->execute()->fetchAll();
  }

}
