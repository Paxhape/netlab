<?php

/**

 * @file
 * Contains Drupal\netlab\Form\StartTopology.
 */
namespace Drupal\netlab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\netlab\NetlabStorage;

class StartTopology extends FormBase {

  public function getFormId() {
    return 'start_topology_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state){

         $uid = \Drupal::currentUser()->id();

         foreach (NetlabStorage::list_start_reservations($uid) as $record) {
          $rows[]=array(
            $record->reservation_id,
            $record->term_date,
            $record->topo_name,
            $record->description,
          );
         }
         $header=array(t('Id'),t('Reservation date'),t('Name of topology'),t('Description'));
         $form['reservations'] = array(
           '#type' => 'table',
           '#header' => $header,
           '#rows' => $rows,
           '#empty' => t('No reservations'),
         );
        foreach(NetlabStorage::get_only_res_id($uid) as $reser){
          $reservations[]=$reser->reservation_id;
        }
         $form['select'] = array(
           '#type' => 'select',
           '#title' => t('Select topology to start'),
           '#required' => TRUE,
           '#options' => $reservations,
         );
         $form['actions']['#type'] = 'actions';
         $form['actions']['submit'] = array(
             '#type' => 'submit',
             '#value' => t('Start'),
             '#button_type' => 'primary',
         );
         return $form;
  }


  public function validateForm(array &$form, FormStateInterface $form_state) {
    $base="/opt/viro2/topology";
    $user_name = \Drupal::currentUser()->getUsername();
    $uid = \Drupal::currentUser()->id();
    $baseUdp=60000;
    $baseHypervisor=50000;
    $baseConsole=11000;
    foreach(NetlabStorage::get_only_res_id($uid) as $reser){
      $reservations[]=$reser->reservation_id;
    }
    $reservation=$reservations[$form_state->getValue('select')];
    //get $topo_name, net_file,

    foreach (NetlabStorage::start_reservation($reservation) as $record) {
       $topo_name=$record->topo_name;
       $console_count=$record->console_count;
       $net_file=$record->net_file;
    }

    $lastConsole= $baseConsole + $console_count - 1 ;
    $nowDate = date("Y-m-d H:i:s");

    db_insert('running_topology')
    ->fields(array(
      'pid_dynamips' => 0,
      'pid_dynagen' => 0,
      'udp_port' => $baseUdp,
      'hypervisor_port' => $baseHypervisor,
      'console_first' => $baseConsole,
      'console_last' => $lastConsole,
      'reservation_id' => $reservation,
      'started' => $nowDate,
    ))
    ->execute();

    $net_file = str_replace( "udpPort" , $baseUdp, $net_file );
    $net_file = str_replace( "hyperPort" , $baseHypervisor, $net_file );

    for ($i = 1; $i <= $console_count; $i++) {
      $net_file = str_replace( "consPort".$i,$baseConsole+$i-1, $net_file );
    }

    $dir= $base."/".$topo_name."/".$user_name;
    if((file_exists($dir))==FALSE){
      mkdir($dir,0777,true);
      exec('chmod -R 777 '.$dir. '  ');
    }
    file_put_contents($dir.'/'.$topo_name.'.net',$net_file);

    chdir($dir);
    exec('dynamips -H '.$baseHypervisor.'> /tmp/ostriMIPS.txt 2> /tmp/ostriMIPSchyby.txt &');
    //sleep(5);
    exec('dynagen '.$dir.'/'.$topo_name.'.net > /tmp/ostreGen.txt 2> /tmp/ostreGENChyby.txt &');

    $dynamips = shell_exec('ps aux | grep "dynamips -H '.$baseHypervisor.'" | grep -v grep | awk \'{print $2}\'');
    $dynagen = shell_exec('ps aux | grep "'. $dir . '" | grep -v grep | awk \'{print $2}\'');

    db_update('running_topology')
    ->fields(array(
     'pid_dynamips' => $dynamips,
     'pid_dynagen' =>  $dynagen,
    ))
    ->condition('reservation_id',$reservation)
    ->execute();
   }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message(t('Topology has started !'));
    //sleep(2);
    //$form_state->setRedirect('lab/topology/configure');
    //return;
  }


}
