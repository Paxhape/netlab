<?php

/**

 * @file
 * Contains Drupal\netlab\Form\EditReservation.
 */
namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

//Variables
$student = 'student';


class EditReservation extends FormBase {

  public function getFormId() {
    return 'edit_reservation_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){
    global $user;
    $user_role = $user->roles;
    $build='';
    $rows = array();


    foreach ($result=NetlabStorage::edit_reserve(\Drupal::currentUser()->id()) as $record) {
      $rows[]=array(
        $record->reservation_id,
        $record->name,
        $record->term_date,
        $record->topo_name,
        $record->description,
      );
    }

    $header = array(t('Reservation Id'),t('Name'),t('Reservation date'),t('Name of topology'),t('Description'));
    $build['reservations'] = array(
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => t('No reservations'),
    );

    foreach ($resID=NetlabStorage::get_reservation_id(\Drupal::currentUser()->id()) as $reservation){
       $res[]=$reservation->reservation_id;
    }
    $reserve=array_values($res);
    $build['reservation_select'] = array(
      '#type' => 'select',
      '#title' => t('Choose reservation id to edit or delete'),
      '#options' => $reserve,
      '#empty' => t('No reservations'),
    );
    $build['actions']['#type'] = 'actions';
    $build['actions']['edit'] = array(
      '#type' => 'button',
      '#value' => t('Edit'),
    );
    $build['actions']['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete'),
    );
  }

  public function editForm()
  {
    drupal_set_message(
      t('Your reservation has been sucessfully saved !')
    );
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    //Uvidime co spravi required
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
  }


}
