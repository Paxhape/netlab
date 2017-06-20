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
         $role = reset(\Drupal::currentUser()->getRoles(TRUE));

         foreach (NetlabStorage::list_start_reservations($uid,$role) as $record) {
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
        $count=count($reservations);
        if($count!=0){
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
         }
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
    $nowDate = date("Y-m-d H:i:s");

    db_insert('running_topology')
    ->fields(array(
      'reservation_id' => $reservation,
      'started' => $nowDate,
    ))
    ->execute();

    foreach (NetlabStorage::start_reservation($reservation) as $record) {
       $topo_name_raw=$record->topo_name;
       $console_count=$record->console_count;
       $net_file=$record->net_file;
       $kvm_file=$record->kvm_file;
       $vnc_count=$record->vnc_count;
       $virbr_count=$record->virbr_count;
       $ram_resources=$record->ram_resources;
    }

    $topo_name=str_replace(' ','_',$topo_name_raw);

    $actualRAM= shell_exec("free -mh | awk '{print $4}' | sed -n '2{p;q}'");
    if($actualRAM < $ram_resources){
      drupal_set_message(t('Server is now fully loaded, please wait a while !'),'error');
    }
    else{
      $dir= $base."/".$topo_name."/".$user_name;
      if((file_exists($dir))==FALSE){
        mkdir($dir,0777,true);
        exec('chmod -R 777 '.$dir. '  ');
      }
    if($virbr_count>0){
      for ($vc = 0; $vc < $virbr_count; $vc++){
        $max_net = 1;
        file_put_contents($dir.'/virtNet'.$max_net.'.xml',
        "<network>
        <name>virtNet$max_net</name>
        <bridge name='virbr$max_net' stp='on' delay='0'/>
        </network>");
        exec('/usr/bin/sudo /usr/bin/virsh net-create '.$dir.'/virtNet'.$max_net.'.xml');
        $max_net++;
      }
    }



    //DYNAGEN
    if((strcmp($net_file,'==='))!=0){
    $lastConsole= $baseConsole + $console_count - 1 ;
    $nowDate = date("Y-m-d H:i:s");
    db_update('running_topology')
    ->fields(array(
      'udp_port' => $baseUdp,
      'hypervisor_port' => $baseHypervisor,
      'console_first' => $baseConsole,
    ))
    ->condition('reservation_id',$reservation)
    ->execute();

    $net_file = str_replace( "udpPort" , $baseUdp, $net_file );
    $net_file = str_replace( "hyperPort" , $baseHypervisor, $net_file );
    if($console_count>0){
    for ($i = 1; $i <= $console_count; $i++){
      $net_file = str_replace( "consPort".$i,$baseConsole+$i-1, $net_file );
        }
    }
    $net_file = str_replace( "iNet" , "NIO_linux_eth:eth0", $net_file );
    $net_file = str_replace("virtNet1" , "NIO_linux_eth:virbr1", $net_file);


    $dir= $base."/".$topo_name."/".$user_name;
    if((file_exists($dir))==FALSE){
      mkdir($dir,0777,true);
      exec('chmod -R 777 '.$dir. '  ');
    }
    file_put_contents($dir.'/'.$topo_name.'.net',$net_file);

    chdir($dir);
    exec('dynamips -H '.$baseHypervisor.'> /tmp/ostriMIPS.txt 2> /tmp/ostriMIPSchyby.txt &');
    sleep(6);
    exec('dynagen '.$dir.'/'.$topo_name.'.net &');
/*
    $dynamips = shell_exec('shell ps aux | grep "dynamips -H '.$baseHypervisor.'" | grep -v grep | awk \'{print $2}\'');
    $dynagen = shell_exec('shell ps aux | grep "'. $dir . '" | grep -v grep | awk \'{print $2}\'');
    drupal_set_message(('dynagen '.$dynagen.' dynamips'.$dynamips),'error');*/
    db_update('running_topology')
    ->fields(array(
     'pid_dynamips' => $dynamips,
     'pid_dynagen' =>  $dynagen,
    ))
    ->condition('reservation_id',$reservation)
    ->execute();
   }
   //_________________________
   //KVM
   //+++++++++++++++++++++++++++++
   if((strcmp($kvm_file,'==='))!=0)
   {
     //KVM Image directory
     $dir= $base."/".$topo_name."/".$user_name."/images";
     if((file_exists($dir))==FALSE){
       mkdir($dir,0777,true);
       exec('chmod -R 777 '.$dir. '  ');
     }

     //cloning VMs
     for($v = 0 ; $v <= $vnc_count ; $v++){
     $VMs=explode("\r\n",$kvm_file);
     $origVMname=explode(" ",$VMs[$v]);
     if(!(file_exists(''.$dir.'/'.$origVMname[0].'-'.$reservation.'.qcow2'))){

     shell_exec('/usr/bin/sudo /usr/bin/virsh destroy '.$origVMname[0].' &');
     //sleep(2);
     exec('/usr/bin/sudo /usr/bin/virt-clone -o '.$origVMname[0].' -n '.$origVMname[0].'-'.$reservation.'-'.$v.' -f '.$dir.'/'.$origVMname[0].'-'.$reservation.'.qcow2 --check path_exists=off & ');
     }
     shell_exec('/usr/bin/sudo /usr/bin/virsh start '.$origVMname[0].'-'.$reservation.'-'.$v.' ');
     //noVNC startup for each VM
     //TO DO function to gether actVM VNC port on localhost


     $thisVMnets=(count($origVMname)-1);
     if($thisVMnets>0){
     for ($n=0;$n<$thisVMnets;$n++){
     exec('virsh attach-interface --domain '.$origVMname[0].'-'.$reservation.'-'.$v.' --type bridge --source '.$origVMname[$n+2].' --model virtio ');
     }
   }
 }
      $vnc_first_console = 82;
     db_update('running_topology')
     ->fields(array(
      '	vnc_first_console' => $vnc_first_console,
     ))
     ->condition('reservation_id',$reservation)
     ->execute();
   }
   }
   }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    drupal_set_message(t('Topology has started !'));
    //sleep(2);
    //$form_state->setRedirect('lab/topology/configure');
    //return;
  }

  public function findFreePort($from){
    $found=FALSE;
    $ap=$from;
    while($found!=TRUE){

  }
   return $ap;
  }


}
