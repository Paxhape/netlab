<?php

/**
 * @file
 * Contains \Drupal\netlab\Plugin\Block\NetlabMenu.
 */

namespace Drupal\netlab\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * @Block(
 *   id = "netlab_menu"
 * )
 */

class NetlabMenu extends BlockBase {

  public function build(){
  /*
  $url = Url:fromInternalUri('lab/topology/');
    return array(
      'test' => array(
        '#markup' => $url ,
      ),
    );
   */
  }

}
