<?php

namespace Drupal\karmayo_society\Controller;

use Drupal\Component\Utility\Xss;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Url;
use Drupal\karmayo_society\Entity\KarmayoSocietyEntityInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class KarmayoSocietyEntityController.
 *
 *  Returns responses for Karmayo society entity routes.
 */
class KarmayoSocietyEntityController extends ControllerBase implements ContainerInjectionInterface {

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
   * Displays a Karmayo society entity revision.
   *
   * @param int $karmayo_society_revision
   *   The Karmayo society entity revision ID.
   *
   * @return array
   *   An array suitable for drupal_render().
   */
  public function revisionShow($karmayo_society_revision) {
    $karmayo_society = $this->entityTypeManager()->getStorage('karmayo_society')
      ->loadRevision($karmayo_society_revision);
    $view_builder = $this->entityTypeManager()->getViewBuilder('karmayo_society');

    return $view_builder->view($karmayo_society);
  }

  /**
   * Page title callback for a Karmayo society entity revision.
   *
   * @param int $karmayo_society_revision
   *   The Karmayo society entity revision ID.
   *
   * @return string
   *   The page title.
   */
  public function revisionPageTitle($karmayo_society_revision) {
    $karmayo_society = $this->entityTypeManager()->getStorage('karmayo_society')
      ->loadRevision($karmayo_society_revision);
    return $this->t('Revision of %title from %date', [
      '%title' => $karmayo_society->label(),
      '%date' => $this->dateFormatter->format($karmayo_society->getRevisionCreationTime()),
    ]);
  }

  /**
   * Generates an overview table of older revisions of a Karmayo society entity.
   *
   * @param \Drupal\karmayo_society\Entity\KarmayoSocietyEntityInterface $karmayo_society
   *   A Karmayo society entity object.
   *
   * @return array
   *   An array as expected by drupal_render().
   */
  public function revisionOverview(KarmayoSocietyEntityInterface $karmayo_society) {
    $account = $this->currentUser();
    $karmayo_society_storage = $this->entityTypeManager()->getStorage('karmayo_society');

    $build['#title'] = $this->t('Revisions for %title', ['%title' => $karmayo_society->label()]);

    $header = [$this->t('Revision'), $this->t('Operations')];
    $revert_permission = (($account->hasPermission("revert all karmayo society entity revisions") || $account->hasPermission('administer karmayo society entity entities')));
    $delete_permission = (($account->hasPermission("delete all karmayo society entity revisions") || $account->hasPermission('administer karmayo society entity entities')));

    $rows = [];

    $vids = $karmayo_society_storage->revisionIds($karmayo_society);

    $latest_revision = TRUE;

    foreach (array_reverse($vids) as $vid) {
      /** @var \Drupal\karmayo_society\KarmayoSocietyEntityInterface $revision */
      $revision = $karmayo_society_storage->loadRevision($vid);
        $username = [
          '#theme' => 'username',
          '#account' => $revision->getRevisionUser(),
        ];

        // Use revision link to link to revisions that are not active.
        $date = $this->dateFormatter->format($revision->getRevisionCreationTime(), 'short');
        if ($vid != $karmayo_society->getRevisionId()) {
          $link = $this->l($date, new Url('entity.karmayo_society.revision', [
            'karmayo_society' => $karmayo_society->id(),
            'karmayo_society_revision' => $vid,
          ]));
        }
        else {
          $link = $karmayo_society->link($date);
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
              'url' => Url::fromRoute('entity.karmayo_society.revision_revert', [
                'karmayo_society' => $karmayo_society->id(),
                'karmayo_society_revision' => $vid,
              ]),
            ];
          }

          if ($delete_permission) {
            $links['delete'] = [
              'title' => $this->t('Delete'),
              'url' => Url::fromRoute('entity.karmayo_society.revision_delete', [
                'karmayo_society' => $karmayo_society->id(),
                'karmayo_society_revision' => $vid,
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

    $build['karmayo_society_revisions_table'] = [
      '#theme' => 'table',
      '#rows' => $rows,
      '#header' => $header,
    ];

    return $build;
  }

}
