<?php
/**
 * (entity de configuration 5)
 *
 * Comme je l'ai mentionné précédemment, pour les entités de configuration, nous devrons implémenter ce gestionnaire de formulaire nous-mêmes. Cependant,
 * ce n'est pas un grand dea l parce que nous pouvons étendre EntityConfirmFormBaseet juste mettre en œuvre des méthodes simples:
 *
 * En getQuestion() nous retourne la chaîne à utiliser comme la question du formulaire de confirmation.
 *
 * À la fin, nous getConfirmText()renvoyons l'étiquette du bouton Supprimer .
 *
 * Dans getCancelUrl()w e fournir l'URL de redirection pour l'utilisateur soit après une annulation ou d' un succès de suppression.
 *
 * Dans submitForm()w e supprimer l'entité, imprimer un message de réussite, et rediriger vers l'URL que nous fixons dans le getCancelUrl() .
 */

namespace Drupal\products\Form;

use Drupal\Core\Entity\EntityConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for deleting Importer entities.
 */
class ImporterDeleteForm extends EntityConfirmFormBase {

  /**
   * ImporterDeleteForm constructor.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return $this->t('Are you sure you want to delete %name?', ['%name' => $this->entity->label()]);
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelUrl() {
    return new Url('entity.importer.collection');
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
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->entity->delete();

    $this->messenger->addMessage($this->t('Deleted @entity importer.', ['@entity' => $this->entity->label()]));

    $form_state->setRedirectUrl($this->getCancelUrl());
  }

}
