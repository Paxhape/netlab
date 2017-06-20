<?php

/**
* @FirmaController
*Contains \Drupal\firma\NetlabStorage.
*/

namespace Drupal\netlab;

class NetlabStorage {
/**
*
*RESERVATION DB Functions
*
*/

//Reservations list
  public static function reser_load($uid,$user_role){
    $select = db_select('reservation','r');
    $select->join('users_field_data','u', 'r.user_id=u.uid');
    $select->join('topology','t', 't.topology_id=r.topology_id');
    $select->join('term','d','d.term_id=r.term_id');
    $select->fields('t', array('topo_name','description'))
    ->fields('d', array('term_date'))
    ->fields('r',array('created','saved_until'))
    ->fields('u',array('name'));
    if(strcmp($user_role,'student')==0){
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
    $nowDate=date("Y-m-d H:i:s",time());
    $select  = db_select('term','t');
    $select->fields('t',array('term_date','free_capacity'));
    $select->condition('t.free_capacity',0,'>');
    $select->condition('term_date',$nowDate,'>');
    return $select->execute()->fetchAll();
  }
//all reservations with idCHYBAAAAAAAA
  public static function edit_reserve($user_role,$uid){
    $select = db_select('reservation','r');
    $select->join('term','d','d.term_id=r.term_id');
    $select->join('topology','t','t.topology_id=r.topology_id');
    $select->join('users_field_data','u', 'r.user_id=u.uid');
    $select->fields('r','reservation_id')
    ->fields('t',array('topo_name','description'))
    ->fields('u',array('name','uid'))
    ->fields('d','term_date');
    if((strcmp($user_role,'student'))==0){
      $select->condition('r.user_id',$uid);
    }
    return $select->execute()->fetchAll();
  }
// only reservations id
  public static function get_reservation_id($user_role,$uid){
    $select = db_select('reservation','r');
    $select->join('users_field_data','u','r.user_id=u.uid');
    $select->fields('r','reservation_id');
    if((strcmp($user_role,'student'))==0){
      $select->condition('r.user_id',$uid);
    }
    return $select->execute()->fetchAll();
  }

  public static function get_term_id_by_term_date($term_date){
       $select = db_select('term','t');
       $select->fields('t',array('term_id','term_date'));
       $select->condition('t.term_date',$term_date);
       return $select->execute()->fetchField();
   }

   public static function get_topo_id_by_topo_name($topo_name){
    $select = db_select('topology','t');
    $select->fields('t',array('topology_id','topo_name'));
    $select->condition('t.topo_name',$topo_name);
    return $select->execute()->fetchField();
}

  public static function topo_load() {
    $select = db_select('topology', 't');
    $select->join('users_field_data','u','u.uid=t.author');
    $select->fields('t',array('topo_name','description','created','ram_resources','console_count','topo_schema'))
    ->fields('u',array('name'));
    $select->condition('t.active',1);
    return $select->execute()->fetchAll();
  }

  public static function topo_id_load() {
    $select = db_select('topology', 't');
    $select->fields('t',array('topology_id','topo_name','description','author','created','ram_resources','console_count'));
    return $select->execute()->fetchAll();
  }


  public static function running_load($user_role,$uid){
    $select = db_select('reservation','r');
    $select->join('users_field_data','u', 'r.user_id=u.uid');
    $select->join('topology','t','t.topology_id=r.topology_id');
    $select->join('running_topology','rt','r.reservation_id=rt.reservation_id');
    $select->fields('rt', array('started'))
    ->fields('u', array('name'))
    ->fields('t',array('topo_name','description','ram_resources'));
    if((strcmp($user_role,'student'))==0){
      $select->condition('r.user_id',$uid);
    }
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


  public static function list_start_reservations($uid,$user_role){
    $select = db_select('reservation','r');
    $select->join('users_field_data','u', 'r.user_id=u.uid');
    $select->join('topology','t', 't.topology_id=r.topology_id');
    $select->join('term','d','d.term_id=r.term_id');
    $select->fields('t', array('topo_name','description'))
    ->fields('r',array('reservation_id','saved_until'))
    ->fields('d', array('term_date'))
    ->fields('u',array('name'));
    $select->condition('r.user_id',$uid);
    return $select->execute()->fetchAll();
  }


  public static function get_only_res_id($uid,$role){
    $select = db_select('reservation','r');
    $select->join('users_field_data','u', 'r.user_id=u.uid');
    $select->fields('r',array('reservation_id'));
    if((strcmp($user_role,'student'))==0){
      $select->condition('r.user_id',$uid);
    }
    return $select->execute()->fetchAll();
  }

  public static function get_only_topo_id(){
    $select = db_select('topology','t');
    $select->fields('t',array('topology_id'));
    return $select->execute()->fetchAll();
  }

  public static function list_run_reservations($user_role,$uid){
    $select = db_select('reservation','r');
    $select->join('users_field_data','u', 'r.user_id=u.uid');
    $select->join('topology','t','t.topology_id=r.topology_id');
    $select->join('running_topology','rt','r.reservation_id=rt.reservation_id');
    $select->fields('u',array('name'))
    ->fields('rt',array('running_topology_id','started'))
    ->fields('t',array('topo_name','description','ram_resources'));
    if((strcmp($user_role,'student'))==0){
      $select->condition('r.user_id',$uid);
    }
    return $select->execute()->fetchAll();
  }


  public static function get_only_run_id($uid,$user_role){
    $select = db_select('reservation','r');
    $select->join('running_topology','rt','r.reservation_id=rt.reservation_id');
    $select->join('users_field_data','u', 'r.user_id=u.uid');
    $select->fields('rt',array('running_topology_id'));
    if((strcmp($user_role,'student'))==0){
      $select->condition('r.user_id',$uid);
    }
    return $select->execute()->fetchAll();
  }


  public static function start_reservation($reservation){
    $select = db_select('topology','t');
    $select->join('reservation','r', 'r.topology_id=t.topology_id');
<<<<<<< HEAD
    $select->fields('t',array('virbr_count','topo_name','ram_resources','console_count','net_file','kvm_file'));
=======
    $select->fields('t',array('topo_name','ram_resources','console_count','net_file','kvm_file'));
>>>>>>> master
    $select->condition('r.reservation_id',$reservation);
    return $select->execute()->fetchAll();
  }
  public static function get_ports($reservation){
    $select = db_select('running_topology','rt');
    $select->fields('rt',array('udp_port','hypervisor_port','console_first','vnc_count','virbr_count'));
    $select->condition('rt.reservation_id',$reservation);
    return $select->execute()->fetchAll();
  }

  public static function get_pids($running_topology_id){
    $select = db_select('running_topology','rt');
    $select->fields('rt',array('pid_dynamips','pid_dynagen'));
    $select->condition('rt.running_topology_id',$running_topology_id);
    return $select->execute()->fetchAll();
  }

  public static function get_console_needed_info($uid){
    $select = db_select('reservation','r');
    $select->join('running_topology','rt','r.reservation_id=rt.reservation_id');
    $select->join('topology','t','t.topology_id=r.topology_id');
    $select->fields('rt',array('vnc_first_console','console_first'))
    ->fields('t',array('topo_name','topo_schema','console_count','vnc_count','description'));
    $select->condition('r.user_id',$uid);
    return $select->execute()->fetchAll();
  }

  public static function get_image($fid){
    $select = db_select('file_managed','m');
    $select->fields('m',array('uri'));
    $select->condition('m.fid',$fid);
    return $select->execute()->fetchAll();
  }

  public static function get_only_fid($topology_id){
    $select = db_select('topology','t');
    $select->fields('t',array('topo_schema'));
    $select->condition('t.topology_id',$topology_id);
    return $select->execute()->fetchAll();
  }
<<<<<<< HEAD

  public static function getFreeCapacity($term_date){
    $select = db_select('term','t');
    $select->fields('t',array('free_capacity'));
    $select->condition('t.term_id',$term_date);
    return $select->execute()->fetchAll();
  }

  public static function getRamRecources($toponame){
    $select = db_select('topology','t');
    $select->fields('t',array('ram_resources'));
    $select->condition('t.topo_name',$toponame);
    return $select->execute()->fetchAll();
  }

  public static function editTopo($topology_id){
    $select = db_select('topology','t');
    $select->fields('t',array('topo_name','description','topo_schema','active','ram_resources','net_file','kvm_file'));
    $select->condition('t.topology_id',$topology_id);
=======

  public static function getFreeCapacity($term_date){
    $select = db_select('term','t');
    $select->fields('t',array('free_capacity'));
    $select->condition('t.term_id',$term_date);
    return $select->execute()->fetchAll();
  }

  public static function getRamRecources($toponame){
    $select = db_select('topology','t');
    $select->fields('t',array('ram_resources'));
    $select->condition('t.topology_id',$toponame);
>>>>>>> master
    return $select->execute()->fetchAll();
  }
}
