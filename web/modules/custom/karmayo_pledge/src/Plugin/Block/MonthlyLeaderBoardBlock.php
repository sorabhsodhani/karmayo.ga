<?php

namespace Drupal\karmayo_pledge\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\karmayo_pledge\KarmayoCoreService;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Render\Markup;

/**
 * Provides a 'MonthlyLeaderBoardBlock' block.
 *
 * @Block(
 *  id = "monthly_leaderboard_block",
 *  admin_label = @Translation("Monthly leader board block"),
 * )
 */
class MonthlyLeaderBoardBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
     $search_params['filter_start_date'] = date('Y-m-01 00:00:00');
      $search_params['filter_end_date'] = date('Y-m-t 00:00:00');
    $data = KarmayoCoreService::getUsersTransaction($search_params);
    
     
      $count = 1;
      if (!empty($data)) {
        foreach ($data as $uid => $udata) {
          $user_tasks_url = Url::fromRoute('view.my_tasks.page_1', ['uid' => $uid]);
          $user_tasks_link = Link::fromTextAndUrl($udata['name'], $user_tasks_url);
          $user_tasks_link = $user_tasks_link->toRenderable();
          $operation_links = render($user_tasks_link);
          $rows[] = [
            $count, 
            Markup::create($operation_links),
            $udata['points']
            ];
          $count++;
        }
      }
    $header = [
      'sNo' => t('SNo'),
      'Pledger' => t('Pledger'),
      'Points' => t('Points'),
    ];
    $build['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => t('No data has been found.'),
      '#attributes' => ['id' => ['karmayo-monthly-leaderboard'], 
        'class' => ['init-datatables', 'table', 'table-striped', 'table-bordered', 'no-entry-info']],
      '#attached' => ['library' => ['karmayo_pledge/global_datatables_library']],
      '#cache' => ['max-age' => 0]
    ];
    return $build;
  }

}
