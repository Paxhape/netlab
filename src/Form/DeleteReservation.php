<?php

/**

 * @file
 * Contains Drupal\netlab\Form\DeleteReservation.
 */
namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\netlab\NetlabStorage;

class DeleteReservation extends FormBase {

  public function getFormId() {
    return 'delete_reservation_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
             $uid = \Drupal::currentUser()->id();
             $role = reset(\Drupal::currentUser()->getRoles(TRUE));
             foreach (NetlabStorage::list_start_reservations($uid,$role) as $record) {
              $rows[]=array(
                $record->reservation_id,
                $record->name,
                $record->term_date,
                $record->saved_until,
                $record->topo_name,
                $record->description,
              );
             }
             $header=array(t('Id'),t('Name'),t('Reservation date'),t('Saved until'),t('Name of topology'),t('Description'));
             $form['reservations'] = array(
               '#type' => 'table',
               '#header' => $header,
               '#rows' => $rows,
               '#empty' => t('No reservations'),
             );
            foreach(NetlabStorage::get_only_res_id($uid) as $reser){
              $reservations[]=$reser->reservation_id;
            }
            $count=count($reservations);
            if($count!=0){
             $form['select'] = array(
               '#type' => 'select',
               '#title' => t('Select reservation to delete'),
               '#required' => TRUE,
               '#options' => $reservations,
             );
             $form['actions']['#type'] = 'actions';
             $form['actions']['submit'] = array(
                 '#type' => 'submit',
                 '#value' => t('Delete'),
                 '#button_type' => 'primary',
             );
             }
             return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    ///porovnaj Datumy
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {

    $uid = \Drupal::currentUser()->id();
    $role = reset(\Drupal::currentUser()->getRoles(TRUE));

<<<<<<< HEAD
    foreach(NetlabStorage::get_only_res_id($uid,$role) as $reser){
=======
    foreach(NetlabStorage::get_only_res_id($uid) as $reser){
>>>>>>> master
      $reservations[]=$reser->reservation_id;
    }

    $toDelete=$reservations[$form_state->getValue('select')];

    $delete = \Drupal::database()->delete('reservation')
               ->condition('reservation_id',$toDelete)
               ->execute();

    drupal_set_message(t('Reservation has been deleted  !'));
  }


}
