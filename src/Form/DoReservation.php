<?php

/**
 * @file
 * Contains Drupal\netlab\Form\DoReservation.
 */

namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\firma\FirmaStorage;


class DoReservation extends FormBase {

  public function getFormId() {
    return 'do_reservation_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

        foreach ($topo_result=NetlabStorage::topo_reserve() as $topo_record) {
          $topo[]=array(
              $topo_record->topo_name,
          );
        }

        foreach ($term_result=NetlabStorage::term_reserve() as $term_record) {
          $term[]=array(
              $term_record->term_date,
          );
        }
      $final_topo = array_column($topo, 'topo_name');
    $form['topology_select'] = array(
      '#type' => 'select',
      '#title' => t('Select topology'),
      '#options' => $final_topo ,
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
      t('Your reservation has been sucessfully saved !'), 'status'
    );
  }

}
