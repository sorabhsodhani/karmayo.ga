<?php

namespace Drupal\karmayo_pledge\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a form for deleting a Karmayo Pledge revision.
 *
 * @ingroup karmayo_pledge
 */
class KarmayoPledgeEntityRevisionDeleteForm extends ConfirmFormBase {

  /**
   * The Karmayo Pledge revision.
   *
   * @var \Drupal\karmayo_pledge\Entity\KarmayoPledgeEntityInterface
   */
  protected $revision;

  /**
   * The Karmayo Pledge storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $karmayoPledgeEntityStorage;

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
    $instance->karmayoPledgeEntityStorage = $container->get('entity_type.manager')->getStorage('karmayo_pledge');
    $instance->connection = $container->get('database');
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'karmayo_pledge_revision_delete_confirm';
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
    return new Url('entity.karmayo_pledge.version_history', ['karmayo_pledge' => $this->revision->id()]);
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
  public function buildForm(array $form, FormStateInterface $form_state, $karmayo_pledge_revision = NULL) {
    $this->revision = $this->KarmayoPledgeEntityStorage->loadRevision($karmayo_pledge_revision);
    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->KarmayoPledgeEntityStorage->deleteRevision($this->revision->getRevisionId());

    $this->logger('content')->notice('Karmayo Pledge: deleted %title revision %revision.', ['%title' => $this->revision->label(), '%revision' => $this->revision->getRevisionId()]);
    $this->messenger()->addMessage(t('Revision from %revision-date of Karmayo Pledge %title has been deleted.', ['%revision-date' => format_date($this->revision->getRevisionCreationTime()), '%title' => $this->revision->label()]));
    $form_state->setRedirect(
      'entity.karmayo_pledge.canonical',
       ['karmayo_pledge' => $this->revision->id()]
    );
    if ($this->connection->query('SELECT COUNT(DISTINCT vid) FROM {karmayo_pledge_field_revision} WHERE id = :id', [':id' => $this->revision->id()])->fetchField() > 1) {
      $form_state->setRedirect(
        'entity.karmayo_pledge.version_history',
         ['karmayo_pledge' => $this->revision->id()]
      );
    }
  }

}
