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

  /*
  * @part
  * REZERVACIA
  * Cast venovana funkcia spojenych s rezervaciou
  */

  /**
  * @function
  * Listovanie rezervacii
  * Vypis zahrna Datum, Meno, topo_name, description a obr_topo
  */

  public function list_reservations(){

   $build='';
   $rows = array();
   $uid=\Drupal::currentUser()->id();
   $role = reset(\Drupal::currentUser()->getRoles(TRUE));

  foreach ($result=NetlabStorage::reser_load() as $record){
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
     '#empty' => t('No reservations'),
   );
   return $build;
  }

  public function edit_reservations(){

  $build='';
  $rows=array();
  $uid=\Drupal::currentUser()->id();
  $role = reset(\Drupal::currentUser()->getRoles(TRUE));

  foreach ($result=NetlabStorage::edit_reserve($role,$uid) as $record) {
   $rows[]=array(
     $record->reservation_id,
     $record->name,
     $record->term_date,
     $record->topo_name,
     $record->description,
   );
  }
  $header = array(t('Reservation_id'),t('Name'),t('Reservation date'),t('Name of topology'),t('Description'));
  $build['reservations'] = array(
    '#type' => 'table',
    '#header' => $header,
    '#rows' => $rows,
    '#empty' => t('No reservations'),
  );
/*
  foreach ($resid_result=NetlabStorage::get_reservation_id($role,$uid) as $resid_record) {
    $resid[]=$resid_record->reservation_id;
  }

  $build['choose_reservation']=array(
    '#type' => 'select',
    '#title' => t('Choose reservation to edit'),
    '#options' => $resid,
  );

*/

  return $build;
  }


  /*
  * @part
  * TOPOLOGIA
  * Cast venovana funkcia spojenych s topologiou
  */

  /**
  * @function
  * Listovanie Dostupnych Topologii
  * Vypis zahrna nazov, popis, autora, d8tum vytvorenia, potrebu pamate a pocet konsole pre topologiu
  */

    public function list_topologies(){

    $build='';

    foreach ($result=NetlabStorage::topo_load() as $toporecord){
        $rows[]=array(
            $toporecord->topo_name,
            $toporecord->description,
            $toporecord->author,
            $toporecord->created,
            $toporecord->ram_resources,
            $toporecord->console_count,
        );
    }
    $header=array(t('Topology name'),t('Description'),t('Author'),t('Created'),t('Ram resources'),t('Console count'));
    $build['topologies']=array(
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => t('No topologies'),
    );
    return $build;
  }

  /**
  * @function
  * Listovanie spustenych Topologii
  * Vypis zahrna nazov, popis, autora, d8tum vytvorenia, potrebu pamate a pocet konsole pre topologiu
  */
  public function list_running(){

       $build='';
       $rows = array();
       $uid=\Drupal::currentUser()->id();
       $role = reset(\Drupal::currentUser()->getRoles(TRUE));

      foreach (NetlabStorage::running_load($role,$uid) as $record){
       $rows[]=array(
         $record->name,
         $record->topo_name,
         $record->description,
         $record->ram_resources,
         $record->started,
       );
      }
       $header = array(t('Name'),t('Name of topology'),t('Description'),t('RAM'),t('Started'));
       $build['running'] = array(
         '#type' => 'table',
         '#header' => $header,
         '#rows' => $rows,
         '#empty' => t('No running topologies'),
       );
       return $build;
  }



    public function term_cron(){

    }

    public function configure(){

    }
}
