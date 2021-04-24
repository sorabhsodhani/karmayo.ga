<?php

namespace Drupal\karmayo_pledge;
use Drupal\karmayo_pledge\Entity\KarmayoPledgeEntity;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\transaction\Entity\Transaction;

/**
 * Class KarmayoCoreService.
 */
class KarmayoCoreService {

  public static function saveKarmayoPledgeEntity ($data, $action = 0) {
    $query = KarmayoPledgeEntity::create([
      'name' => $data['userName'] . ' - ' . $data['activity'] . ' - ' . date('Y-m-d'),
      'activity_points' => $data['points'],
      'activity_status' => $action,
      'node_id' => $data['nid'],
    ]);
    if ($query->save()) {
      return TRUE;
    }
    else {
      \Drupal::logger('karmayo_pledge_entity_save')->error('Saving of karmayo entity failed. Please check');
    }
  }
  
  public static function getUsersTransaction ($search_params, $uid = NULL) {
   $query = \Drupal::entityQuery('transaction')->condition('type','userpoints_default_points')
    ->condition('target_entity__target_type','user')
    ->condition('executed',strtotime($search_params['filter_start_date']),'>=')
    ->condition('executed',strtotime($search_params['filter_end_date']),'<=');
   if ($uid != NULL) {
     $query->condition('target_entity__target_id', $uid);
   }
   $tids = $query->sort('executed' , 'DESC')
    ->execute(); 
   $transactions = Transaction::loadMultiple($tids);
   $results = [];
   foreach ($transactions as $transaction) {
     $results[$transaction->getTargetEntityId()][date('Y-m-d H:m:s',$transaction->getExecutionTime())] = $transaction->get('field_userpoints_default_balance')->value;
   }
   //kint($results);
   $user_points = [];
   foreach ($results as $uid => $result) {
     $user_data = User::load($uid);
     if ($user_data != NULL) {
       $user_points[$uid]['name'] = $user_data->getUsername();
       $user_points[$uid]['points'] = reset($result);
     }
   }
   //kint($user_points);exit;
   return $user_points;
  }
  

}
