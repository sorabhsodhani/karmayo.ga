<?php

namespace Drupal\karmayo_pledge\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
use Drupal\Core\Session\AccountInterface;

/**
 * Class UserPlegeListController.
 */
class UserPlegeListController extends ControllerBase {
  
  /**
   * Redirects users to their profile page.
   *
   * This controller assumes that it is only invoked for authenticated users.
   * This is enforced for the 'user.page' route with the '_user_is_logged_in'
   * requirement.
   *
   * @return \Symfony\Component\HttpFoundation\RedirectResponse
   *   Returns a redirect to the profile of the currently logged in user.
   */
  public function userPledgeListPage() {
    $uid = $this->currentUser()->id();
    $user = User::load($uid);
    return $this->redirect('karmayo_pledge.user_pledge_list', ['uid' => $uid]);
  }


  /**
   * Getuserpickedactivities.
   *
   * @return string
   *   Return Hello string.
   */
  public function getUserPickedActivities(AccountInterface $user = NULL) {
    $uid = \Drupal::routeMatch()->getParameter('uid');
    $user_data = User::load($uid);
    dump($user_data);exit;
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: getUserPickedActivities')
    ];
  }

}
