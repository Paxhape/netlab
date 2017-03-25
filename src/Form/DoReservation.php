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

    foreach ($term_result as $term_record) {
      $term=array(
          $term_record->term_date,
      );
    }

    $form['topology_select'] = array(
      '#type' => 'select',
      '#title' => t('Select topology'),
      '#options' => $topo , //pozriet
      '#required' => TRUE,
    );

    $form['date_select'] = array(
      '#type' => 'select',
      '#title' => t('Select date & hour'),
      '#required' => TRUE,
      '#options' => $term,
    );
   $form['actions']['#type'] = 'actions';
   $form['actions']['submit'] = array(
       '#type' => 'submit',
       '#value' => t('Reserve'),
       '#button_type' => 'primary',
   );
  return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    //Uvidime co spravi required
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    global $user;
    db_insert('reservation')->fields(array(
                                    'user_id' => $user->uid,
                                    'term_id' => $form_state['values']['date_select'],
                                    'topology_id' => $form_state['values']['topology_select'],
                                  ))->execute();
    drupal_set_message(
      t('Your reservation has been sucessfully saved !')
    );
  }

}
