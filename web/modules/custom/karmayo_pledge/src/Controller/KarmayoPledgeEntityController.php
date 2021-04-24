<?php

namespace Drupal\karmayo_pledge\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class KarmayoPledgeEntityController.
 *
 *  Returns responses for Karmayo Pledge routes.
 */
class KarmayoPledgeEntityController extends ControllerBase implements ContainerInjectionInterface {

  /**
   * The date formatter.
   *
   * @var \Drupal\Core\Datetime\DateFormatter
   */
  protected $dateFormatter;

  /**
   * The renderer.
   *
   * @var \Drupal\Core\Render\Renderer
   */
  protected $renderer;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->dateFormatter = $container->get('date.formatter');
    $instance->renderer = $container->get('renderer');
    return $instance;
  }

  /**
   * Displays a Karmayo Pledge revision.
   *
   * @param int $karmayo_pledge_revision
   *   The Karmayo Pledge revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($karmayo_pledge_revision) {
    $karmayo_pledge = $this->entityTypeManager()->getStorage('karmayo_pledge')
      ->loadRevision($karmayo_pledge_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('karmayo_pledge');

    return $view_builder->view($karmayo_pledge);
  }

  /**
   * Page title callback for a Karmayo Pledge revision.
   *
   * @param int $karmayo_pledge_revision
   *   The Karmayo Pledge revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($karmayo_pledge_revision) {
    $karmayo_pledge = $this->entityTypeManager()->getStorage('karmayo_pledge')
      ->loadRevision($karmayo_pledge_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $karmayo_pledge->label(),
      '%date' => $this->dateFormatter->format($karmayo_pledge->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Karmayo Pledge.
   *
   * @param \Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityInterface $karmayo_pledge
   *   A Karmayo Pledge object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(KarmayoPledgeEntityInterface $karmayo_pledge) {
    $account = $this->currentUser();
    $karmayo_pledge_storage = $this->entityTypeManager()->getStorage('karmayo_pledge');

    $build['#title'] = $this->t('Revisions for %title', ['%title' => $karmayo_pledge->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all karmayo pledge revisions") || $account->hasPermission('administer karmayo pledge entities')));
    $delete_permission = (($account->hasPermission("delete all karmayo pledge revisions") || $account->hasPermission('administer karmayo pledge entities')));

    $rows = [];

    $vids = $karmayo_pledge_storage->revisionIds($karmayo_pledge);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\karmayo_pledge\KarmayoPledgeEntityInterface $revision */
      $revision = $karmayo_pledge_storage->loadRevision($vid);
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $karmayo_pledge->getRevisionId()) {
          $link = $this->l($date, new Url('entity.karmayo_pledge.revision', [
            'karmayo_pledge' => $karmayo_pledge->id(),
            'karmayo_pledge_revision' => $vid,
          ]));
        }
        else {
          $link = $karmayo_pledge->link($date);
        }

        $row = [];
        $column = [
          'data' => [
            '#type' => 'inline_template',
            '#template' => '{% trans %}{{ date }} by {{ username }}{% endtrans %}{% if message %}<p class="revision-log">{{ message }}</p>{% endif %}',
            '#context' => [
              'date' => $link,
              'username' => $this->renderer->renderPlain($username),
              'message' => [
                '#markup' => $revision->getRevisionLogMessage(),
                '#allowed_tags' => Xss::getHtmlTagList(),
              ],
            ],
          ],
        ];
        $row[] = $column;

        if ($latest_revision) {
          $row[] = [
            'data' => [
              '#prefix' => '<em>',
              '#markup' => $this->t('Current revision'),
              '#suffix' => '</em>',
            ],
          ];
          foreach ($row as &$current) {
            $current['class'] = ['revision-current'];
          }
          $latest_revision = FALSE;
        }
        else {
          $links = [];
          if ($revert_permission) {
            $links['revert'] = [
              'title' => $this->t('Revert'),
              'url' => Url::fromRoute('entity.karmayo_pledge.revision_revert', [
                'karmayo_pledge' => $karmayo_pledge->id(),
                'karmayo_pledge_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.karmayo_pledge.revision_delete', [
                'karmayo_pledge' => $karmayo_pledge->id(),
                'karmayo_pledge_revision' => $vid,
              ]),
            ];
          }

          $row[] = [
            'data' => [
              '#type' => 'operations',
              '#links' => $links,
            ],
          ];
        }

        $rows[] = $row;
    }

    $build['karmayo_pledge_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
