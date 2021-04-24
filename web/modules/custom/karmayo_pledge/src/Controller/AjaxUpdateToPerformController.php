<?php

namespace Drupal\karmayo_pledge\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Request;
use Drupal\karmayo_pledge\Entity\KarmayoPledgeEntity;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AjaxUpdateToPerformController.
 */
class AjaxUpdateToPerformController extends ControllerBase {

  /**
   * Update_to_perfrom.
   *
   * @return string
   *   Return Hello string.
   */
  public function update_to_perfrom($kid = NULL) {
    if ($kid != NULL) {
      $kdata = KarmayoPledgeEntity::load($kid);
      if ($kdata != NULL) {
        $kdata->set('activity_status', 2);
        if ($kdata->save()) {
          $data = $kdata;
        }
        else {
          $data['error_message'] = 'error';
        }
      }
      else {
        $data['error_message'] = 'error';
      }
    }
    
    return new JsonResponse([ 'data' => $data, 'method' => 'GET', 'status'=> 200]);
  }

}
