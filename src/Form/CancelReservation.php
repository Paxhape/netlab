<?php

/**

 * @file
 * Contains Drupal\netlab\Form\CancelReservation.
 */
namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class DeleteReservation extends FormBase {

  public function getFormId() {
    return 'delete_reservation_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

    foreach ($res_result=NetlabStorage::delete_reserve() as $res_record) {
      $reservation[]=array(
          $res_record->term_date,
          $res_record->topo_name,
          $res_record->description,
      );
    }

    $form['reservation'] = array(
      '#type' => 'select',
      '#title' => t('Select reservation'),
      '#required' => TRUE,
      '#options' => &$reservation,
    );
   $form['actions']['#type'] = 'actions';
   $form['actions']['submit'] = array(
       '#type' => 'submit',
       '#value' => t('Delete Reservation'),
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
