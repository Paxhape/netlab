<?php

/**
 * @file
 * Contains \Drupal\netlab\Controller\NetlabController.
 */

namespace Drupal\netlab\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class NetlabController extends ControllerBase {
  public function ukazka() {
    $build = array(
    );
   return $build;
  }

  public function list_topologies(){

    $build='';
    $rows = array();

    $select = db_query("SELECT topo_name,description,author,created,ram_resources FROM `topology` WHERE active=1");
    $result = $select->fetchAll();
    foreach ($result as $record){
        $rows[]=array(
                    $record->topo_name,
                          $record->description,
              $record->author,
              $record->created,
              $record->ram_resources,
                     );
    }

    $header = array(t('Name'),t('Description'),t('Author'),t('Created'),t('Ram resources'));
    $build['topologies'] = array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => t('No topologies available'),
    );
    return $build;
   }


   /**
   * @function
   * Listovanie rezervacii
   */

   public function list_reservations(){

    $build='';
    $rows = array();

    $select = db_query("SELECT name, term_date, topo_name, description FROM users_field_data, topology, term, reservation WHERE reservation.term_id=term.term_id AND topology.topology_id=reservation.topology_id AND users_field_data.uid=reservation.user_id")
    $result = $select->fetchAll();
    foreach ($result as $record) {
      $rows[]=array(
        $record->name,
        $record->term_date,
        $record->topo_name,
        $record->description,
      );
      $rows[]=
    }
    $header = array(t('Name'),t('Reservation date'),t('Name of topology'),t('Description'))
    $build['reservations'] = array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => t('No reservations'),
    );
    return $build;
  }

  /**
  * @function
  * Listovanie rezervacii
  */

  public function list_running(){

    $select = db_query()
    $result = $select->fetchAll();

    foreach ($result as $record) {
      $rows[]=array(
        $record->reservation_id,
        $record->term_date,
        $record->topo_name,
        $record->description,
        $record->name,
      );
    }
    $header = array(t('ID'),t('Reservation date'),t('Name of topology'),t('Description'),t('Reservator'))
    $build['reservations'] = array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => t('No reservations'),
    );
    return $build;
  }


}
