<?php

/**
 * @file
 * Contains Drupal\netlab\Form\DoReservation.
 */

namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class DoReservation extends FormBase {

  public function getFormId() {
    return 'do_reservation_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

    $topo_query = db_query("SELECT topo_name FROM `topology` WHERE active=1");
    $term_query = db_query("SELECT term_date FROM `term` WHERE term.free_capacity IS NOT NULL");

    $topo_result = $topo_query->fetchAll();
    $term_result = $term_query->fetchAll();

    foreach ($topo_result as $topo_record) {
      $topo[]=array(
          $topo_record->topo_name,
      );
    }

    $form['topology_select'] = array(
      '#type' => 'select',
      '#title' => t('Select topology'),
      '#options' => $topo , //pozriet
      '#description' => t('Select topology'),
      '#required' => TRUE,
    );

    $form['date_select'] = array(
      '#type' => 'select',
      '#title' => t('Select date & hour'),
      '#description' => t('Select date & hour'),
      '#required' => TRUE,
      '#options' => $opt_date,
    );



  }

}
