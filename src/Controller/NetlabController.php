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

  foreach ($result=NetlabStorage::reser_load($uid,$role) as $record){
   $rows[]=array(
     $record->name,
     $record->term_date,
     $record->topo_name,
     $record->description,
     $recort->created,
   );
  }
   $header = array(t('Name'),t('Reservation date'),t('Name of topology'),t('Description'),t('Creted'));
   $build['reservations'] = array(
     '#type' => 'table',
     '#header' => $header,
     '#rows' => $rows,
     '#empty' => t('No reservations'),
   );
   return $build;
  }


  /*
  * @part
  * TOPOLOGIA
  * Cast venovana funkcia spojenych s topologiou
  */
  /**
  * @function
  * Listovanie spustenych Topologii
  * Vypis zahrna nazov, popis, autora, d8tum vytvorenia, potrebu pamate a pocet konsole pre topologiu
  */

  public function conf_topo(){
   $build='';
   $rows = array();
   $uid=\Drupal::currentUser()->id();
   $role = reset(\Drupal::currentUser()->getRoles(TRUE));

   $build['runn']=array(
     '#type' => 'markup',
     '#markup' => t('Comming soon'),
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
  /**
  * @function
  * Listovanie spustenych Topologii
  * Vypis zahrna nazov, popis, autora, d8tum vytvorenia, potrebu pamate a pocet konsole pre topologiu
  */
  public function dashboard(){
  $build='';

  $uid=\Drupal::currentUser()->id();
  $role = reset(\Drupal::currentUser()->getRoles(TRUE));
  $runCount=count(NetlabStorage::running_load($role,$uid));

      $build['runn']=array(
        '#type' => 'markup',
        '#markup' => t('Welcome to ViRo2'),
      );
  if($runCount!=0){
    drupal_set_message(t('You have started topologies! Please turn them off before leaving'), 'error');
  }
  return $build;
}


  /**
  * @function
  * Listovanie spustenych Topologii
  * Vypis zahrna nazov, popis, autora, d8tum vytvorenia, potrebu pamate a pocet konsole pre topologiu
  */
    public function term_cron(){

    }


}
