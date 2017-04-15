<?php

/**
 * @file
 * Contains \Drupal\netlab\Controller\NetlabController.
 */

namespace Drupal\netlab\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Drupal\netlab\NetlabStorage;

class NetlabController extends ControllerBase {

  /**
  * @function
  * Listovanie rezervacii
  * Vypis zahrna Datum, Meno, topo_name, description 
  */

  public function list_reservations(){

   $build='';
   $rows = array();
   $uid=\Drupal::currentUser()->id();
   $role = reset(\Drupal::currentUser()->getRoles(TRUE));

  foreach ($result=NetlabStorage::reser_load($role,$uid) as $record) {
   $rows[]=array(
     $record->name,
     $record->term_date,
     $record->topo_name,
     $record->description,
   );
  }

   $header = array(t('Name'),t('Reservation date'),t('Name of topology'),t('Description'));
   $build['reservations'] = array(
     '#type' => 'table',
     '#header' => $header,
     '#rows' => $rows,
     '#empty' => t('No reservations')
   );
   return $build;
  }
 

  /**
  * @function
  * Listovanie Dostupnych Topologii
  * Vypis zahrna nazov, popis, autora, d8tum vytvorenia, potrebu pamate a pocet konsole pre topologiu
  */

    public function list_topologies(){
        
    $build='';
    $rows = array();
    $uid=\Drupal::currentUser()->id();
    $role = reset(\Drupal::currentUser()->getRoles(TRUE));
    
    foreach (NetlabStorage::topo_load() as $toporecord){
        $rows[]=array(
            $toporecord->topo_name,
            $toporecord->description,
            $toporecord->author,
            $toporecord->created,
            $toporecord->ram_resources,
            $toporecord->console_count,
        );
    }
    
    $header(t('Topology name'),t('Description'),t('Author'),t('Created'),t('Ram resources'),t('Console count'));
    $build['topologies']=array(
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => t('No topologies'),
    );
    return build;
    
  }

   /**
   * @function
   * Listovanie rezervacii
   */


  /**
  * @function
  * Listovanie beziacich topologii
  */

  public function list_running(){

    foreach ($result=NetlabStorage::running_load() as $record) {
      $rows[]=array(
        $record->name,
        $record->topo_name,
        $record->description,
        $record->ram_resources,
        $record->pid_dynamips,
        $record->pid_dynagen,
        $record->hypervisor_port,
      );
    }
    $header = array(t('User'),t('Topology'),t('Description'),t('Ram Resources'),t('Dynamips Port'),t('Dynagen Port'),t('Hypervisor'));
    $build['running'] = array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => t('No reservations'),
    );
    return $build;
  }


public function console(){
  global $user;
  global $base_url;

  $content='';

  $pom = '<embed CODEBASE="'.$base_url.'/sites/default/files/" ARCHIVE="jta26.jar" CODE="de.mud.jta.Applet" WIDTH=80 	HEIGHT=35>
  <param name="config" value="admin/applet_config.conf">
  <param name="Socket.port" value = "3000">
  </embed>';
 $rows[] = array('Router ', $pom);

 $header = array(t('Device'),t(''));

 #$content = theme('table',$header, $rows);
 $content['console'] = array(
   '#type' => 'table',
   '#header' => $header,
   '#rows' => $rows,
 );

 return $content;
}

public function topology_start(){
    
    
}

}
