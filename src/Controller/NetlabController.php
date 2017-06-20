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
   $uid = \Drupal::currentUser()->id();
   $role = reset(\Drupal::currentUser()->getRoles(TRUE));

  foreach ($result=NetlabStorage::reser_load($uid,$role) as $record){
   $rows[]=array(
     $record->name,
     $record->term_date,
     $record->saved_until,
     $record->topo_name,
     $record->description,
     $record->created,
   );
  }
   $header = array(t('Name'),t('Reservation date'),t('Saved until'),t('Name of topology'),t('Description'),t('Created'));
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
  * Konfiguracia topologii
  * Obrazok topologie a vypis zariadeni s ich odkazmi na konzolu
  */

  public function conf_topo(){
   $build='';
   $rows = array();
   $uid=\Drupal::currentUser()->id();
   $role = reset(\Drupal::currentUser()->getRoles(TRUE));
   $runCount=count(NetlabStorage::get_console_needed_info($uid));
   $pubPort = 6080;
<<<<<<< HEAD
   $conBase = 11000;

   if($runCount<1){
     drupal_set_message(t('No started topologies!'), 'error');
     $build['topo_name'] = array(
       '#markup' => t('<br \>  <br \><br \>'),
     );
   }else{

        foreach ($result=NetlabStorage::get_console_needed_info($uid) as $record){
          $vnc_count[]=$record->vnc_count;
          $vnc_first_console[]=$record->vnc_first_console;
          $console_count[]=$record->console_count;
          $console_first[]=$record->console_first;
          $topo_name[]=$record->topo_name;
          $topo_schema[]=$record->topo_schema;
          $description[]=$record->description;
        }

        foreach(NetlabStorage::get_image($topo_schema[0]) as $reser){
          $image=$reser->uri;
        }

        $build['topo']['topo_name'] = array(
          '#markup' => t(' <h3>@toponame</h3> ',array('@toponame' => $topo_name[0])),
        );
        $build['topo']['description'] = array(
          '#markup' => t(' <br \> @desc <br \><br \><br \> ',array('@desc' => $description[0])),
        );

        $build['topo']['topo_image'] = array(
          '#theme' => 'image_style',
          '#style_name' => 'topology',
          '#uri' => $image,
        );
        $build['delimeter'] = array(
          '#markup' => t('<br \> <br \>'),
        );

        for($i = 0 ; $i < $console_count[0]; $i++){
          $newPort = $conBase  + $i ;
          $wwwPort = 81 +$i ;
          $output = shell_exec('shellinaboxd -t -b -p '.$wwwPort.' -s /'.$uid.'-con'.$i.':nobody:nogroup:/:\'telnet 0 '.$newPort.'\' ');
          drupal_set_message('shellinaboxd -t -b -p '.$wwwPort.' -s /'.$uid.'-con'.$i.':nobody:nogroup:/:\'telnet 0 '.$newPort.'\' & '.$output.'');
          $url = Url::fromUri('http://viro2.kis.fri.uniza.sk:81/'.$uid.'-con'.$i);
=======

   if($runCount<1){
     drupal_set_message(t('No started topologies!'), 'error');
     $build['topo_name'] = array(
       '#markup' => t('<br \>  <br \><br \>'),
     );
   }else{

        foreach ($result=NetlabStorage::get_console_needed_info($uid) as $record){
          $vnc_count[]=$record->vnc_count;
          $vnc_first_console[]=$record->vnc_first_console;
          $console_count[]=$record->console_count;
          $console_first[]=$record->console_first;
          $topo_name[]=$record->topo_name;
          $topo_schema[]=$record->topo_schema;
          $description[]=$record->description;
        }

        $build['topo']['topo_name'] = array(
          '#markup' => t('<br \> <h3>@toponame</h3> <br \>',array('@toponame' => $topo_name[0])),
        );

        $build['topo']['topo_image'] = array(
          '#theme' => 'image_style',
          '#style_name' => 'topology',
          '#uri' => 'public://'.$topo_schema[0],
        );
        $build['topo']['description'] = array(
          '#markup' => t('<br \> <br \> @desc <br \> <br \>',array('@desc' => $description[0])),
        );

        for($i = 0 ; $i < $console_count[0]; $i++){


          $newPort = $pubPort + $i ;
          $url = Url::fromUri('http://viro2.kis.fri.uniza.sk:'.$newPort);
>>>>>>> master
          $build['consoles']['console '.$i] = array(
            '#type' => 'link',
            '#url' => $url,
            '#title' => t('Console '.$i),
            '#attributes' => array('target' => '_blank'),
          );

          $build['delimeter'.$i] = array(
            '#markup' => t('<br \> <br \>'),
          );
        }

        for($j = 0; $j < $vnc_count[0]; $j++){
<<<<<<< HEAD
          exec('/opt/viro2/noVNC/utils/launch.sh --vnc localhost:'.$vncPort.' --listen 82  &');
          $url = Url::fromUri('http://viro2.kis.fri.uniza.sk:6080/vnc.html?host=viro2.kis.fri.uniza.sk&port=6080');
          $build['consoles']['console '.$i] = array(
            '#type' => 'link',
            '#url' => $url,
            '#title' => t('Monitor'.$i),
=======
          $url = Url::fromUri('http://viro2.kis.fri.uniza.sk:'.$pubPort);
          $build['consoles']['console '.$i] = array(
            '#type' => 'link',
            '#url' => $url,
            '#title' => t('Console'.$i),
>>>>>>> master
            '#attributes' => array('target' => '_blank'),
          );
        }
  }
  return $build;
  }


  public  function save_topology(){
    drupal_set_message(t('You have started topologies! Please turn them off before leaving'), 'error');
  }

  /**
  * @function
  * Listovanie Dostupnych Topologii
  * Vypis zahrna nazov, popis, autora, d8tum vytvorenia, potrebu pamate a pocet konsole pre topologiu
  */
    public function list_topologies(){

    $build='';
    $topoCount=count(NetlabStorage::topo_load());
    if($topoCount < 1 ){
      $build['conf']['name'] = array(
    	'#type' => 'hidden',
  	  '#value' => $name,
    );
    drupal_set_message(t('No topologies !'), 'error');
    }else{
    $header=array(t('Topology name'),t('Description'),t('Author'),t('Created'),t('Ram resources'),t('Console count'));

    foreach ($result=NetlabStorage::topo_load() as $toporecord){
        $topos[]=$toporecord->topo_name;
        $descr[]=$toporecord->description;
        $names[]=$toporecord->name;
        $creat[]=$toporecord->created;
        $resou[]=$toporecord->ram_resources;
        $conso[]=$toporecord->console_count;
        $topo_schema[]=$toporecord->topo_schema;
      }


      for ($i=0; $i < $topoCount; $i++){
        $rows='';
        $rows[]=array(
          $topos[$i],
          $descr[$i],
          $names[$i],
          $creat[$i],
          $resou[$i],
          $conso[$i],
        );

       foreach(NetlabStorage::get_image($topo_schema[$i]) as $reser){
         $image=$reser->uri;
       }


        $build['name'.$i] = array(
          '#markup' => t('<h3>@toponame </h3><br \>',array('@toponame' => $topos[$i])),
        );

        $build ['image'.$i] = array(
          '#theme' => 'image_style',
          '#style_name' => 'topology',
          '#uri' => $image,
        );
    $build['topologies'.$i]=array(
        '#type' => 'table',
        '#header' => $header,
        '#rows' => $rows,
        '#empty' => t('No topologies'),
    );

    }
    }

  /*  $build['testing']=array(
      '#markup' => '<img alt="Portable"   src="/sites/default/files/inline-images/monitor.png" width="45" />',
    );*/
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
  $resCount=count(NetlabStorage::reser_load($uid,$role));
  $topoCount=count(NetlabStorage::topo_load());
  if ($uid == 0 ) {
    $build['conf']['name'] = array(
  	'#type' => 'hidden',
	  '#value' => $name,
  );
  drupal_set_message(t('Please log in or register to access this page'), 'error');
  }else{

    $build['field_running_topologies']=array(
     '#type' => 'fieldgroup',
     '#title' => $this->t('Running Topologies: @runCount', array('@runCount' => $runCount)),
    );

    $build['field_running_topologies']['table']=NetlabController::list_running();

    $build['field_reservations']=array(
      '#type' => 'fieldgroup',
      '#title' => $this->t('Reservations: @resCount', array('@resCount' => $resCount)),
    );

    $build['field_reservations']['table']=NetlabController::list_reservations();

  $build['field_topologies']=array(
    '#type' => 'fieldgroup',
    '#title' => $this->t('Topologies: @topoCount', array('@topoCount' => $topoCount)),
  );

  $build['field_topologies']['table']=NetlabController::list_topologies();

  if($runCount!=0){
    drupal_set_message(t('You have started topologies! Please turn them off before leaving'), 'error');
  }
}
  return $build;

}

}
