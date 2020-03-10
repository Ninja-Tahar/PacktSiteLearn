<?php

/**
 * $form_state->setErrorByName
 *
 *Par défaut, les messages d'erreur de validation de formulaire sont
 * imprimés en haut de la page. Cependant, avec le module principal Erreurs
 * de formulaire en ligne, nous pouvons imprimer les erreurs de formulaire juste
 * en dessous des éléments réels. C'est beaucoup mieux pour l'accessibilité, ainsi
 * que pour la clarté lorsque vous traitez des formulaires volumineux. Notez que
 * l'installation standard de Drupal 8 n'a pas ce module activé , vous devrez donc
 * l'activer vous-même si vous souhaitez l'utiliser.
 */

/**
 * @file
 *  Form de configuration pour changer le message d'accuie.
 */

namespace Drupal\hello_world\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration form definition for the salutation message.
 */
class SalutationConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   *
   * Avec cette méthode, nous l'informons que nous voulons modifier cet élément de configuration.
   */
  protected function getEditableConfigNames() {
    return ['hello_world.custom_salutation'];
  }

  /**
   * {@inheritdoc}
   *
   * Renvoie un nom unique lisible par machine pour le formulaire.
   */
  public function getFormId() {
    return 'salutation_configuration_form';
  }

  /**
   * {@inheritdoc}
   *
   *  Renvoie la définition du formulaire (un tableau de définitions
   * d'éléments de formulaire et des métadonnées supplémentaires, si nécessaire).
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('hello_world.custom_salutation');

    $form['salutation'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Salutation'),
      '#description' => $this->t('Please provide the salutation you want to use.'),
      '#default_value' => $config->get('salutation'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   *
   *  Le gestionnaire qui est appelé lorsque le formulaire est soumis (si la validation s'est
   * déroulée sans erreur). Il reçoit les mêmes arguments que validateForm(). Vous pouvez effectuer
   * des opérations telles que l'enregistrement des valeurs soumises ou le déclenchement d'un autre type de flux.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('hello_world.custom_salutation')
      ->set('salutation', $form_state->getValue('salutation'))
      ->save();

    parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   *
   * Le gestionnaire qui est appelé pour valider la soumission du formulaire. Il reçoit la définition du formulaire
   * et un $form_state objet qui contient, entre autres, les valeurs soumises. Vous pouvez signaler des valeurs non
   * valides sur leurs éléments de formulaire respectifs, ce qui signifie que le formulaire n'est pas soumis mais a
   * ctualisé (avec les éléments incriminés mis en évidence).
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $salutation = $form_state->getValue('salutation');
    if (strlen($salutation) > 20) {
      $form_state->setErrorByName('salutation', $this->t('This salutation is too long'));
    }
  }

}
