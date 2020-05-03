<?php
/**
 * (entity 4).
 * gestionnaire de formulaire.
 *
 * Tous nos gestionnaires sont maintenant terminés et notre type d'entité produit est opérationnel. Cependant, afin de pouvoir travailler avec lui, créons des liens dans le menu admin pour pouvoir les gérer facilement.
 *
 *  Depuis la création et la modification d'un partage d'entité en termes de ce dont nous avons besoin dans le formulaire, nous utilisons la même chose ProductFormpour ces deux opérations.
 */

namespace Drupal\products\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form for creating/editing Product entities.
 */
class ProductForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   *
   * Vous pouvez voir que nous utilisons la drupal_set_message()fonction globale déconseillée pour imprimer le message à l'utilisateur.
   * Je l'ai fait exprès pour garder les choses courtes. Cependant, comme nous l'avons vu au chapitre 2 , Création de votre premier module ,
   * vous devez plutôt injecter le Messengerservice et l'utiliser. Ne se référer à ce chapitre aussi pour un récapitulatif sur la façon d'injecter des services si vous n'êtes pas sûr.
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Product.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Product.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.product.canonical', ['product' => $entity->id()]);
  }

}
