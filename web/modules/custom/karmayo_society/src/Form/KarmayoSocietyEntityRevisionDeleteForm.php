<?php

namespace Drupal\karmayo_society\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Karmayo society entity revision.
 *
 * @ingroup karmayo_society
 */
class KarmayoSocietyEntityRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Karmayo society entity revision.
   *
   * @var \Drupal\karmayo_society\Entity\KarmayoSocietyEntityInterface
   */
  protected $revision;

  /**
   * The Karmayo society entity storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $karmayoSocietyEntityStorage;

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $connection;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $instance = parent::create($container);
    $instance->karmayoSocietyEntityStorage = $container->get('entity_type.manager')->getStorage('karmayo_society');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'karmayo_society_revision_delete_confirm';
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete the revision from %revision-date?', [
      '%revision-date' => format_date($this->revision->getRevisionCreationTime()),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.karmayo_society.version_history', ['karmayo_society' => $this->revision->id()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $karmayo_society_revision = NULL) {
    $this->revision = $this->KarmayoSocietyEntityStorage->loadRevision($karmayo_society_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->KarmayoSocietyEntityStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Karmayo society entity: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of Karmayo society entity %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.karmayo_society.canonical',
       ['karmayo_society' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {karmayo_society_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.karmayo_society.version_history',
         ['karmayo_society' => $this->revision->id()]
      );
    }
  }

}
