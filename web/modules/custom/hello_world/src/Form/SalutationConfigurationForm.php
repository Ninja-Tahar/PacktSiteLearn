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
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configuration form definition for the salutation message.
 */
class SalutationConfigurationForm extends ConfigFormBase {

  /*
   *  avec  __construct et create methodes (MailMessage 4)
   *
   * Tout d'abord, FormBasedéjà implémenté ContainerInjectionInterface, nous n'avons donc pas besoin de l'implémenter dans notre classe,
   * car nous en étendons quelque part sur toute la ligne. Deuxièmement, la ConfigFormBaseclasse que nous étendons directement a déjà config.factoryinjecté,
   * donc cela nous complique un peu les choses - enfin, pas vraiment. Tout ce que nous devons faire est de copier le constructeur et la create()méthode, ajouter notre
   * propre service, le stocker dans une propriété et passer les services dont le parent a besoin à l'appel du constructeur parent. Il ressemblera à ceci
   *
   * Maintenant, nous avons notre hello_worldcanal d'enregistrement disponible dans notre classe de formulaire de configuration. Alors, utilisons-le.
   */
  /**
   * @var \Drupal\Core\Logger\LoggerChannelInterface
   */
  protected $logger;

  /**
   * SalutationConfigurationForm constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Logger\LoggerChannelInterface $logger
   *   The logger.
   */
  public function __construct(ConfigFactoryInterface $config_factory, LoggerChannelInterface $logger) {
    parent::__construct($config_factory);
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('hello_world.logger.channel.hello_world')
    );
  }

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

    /*
     * (MailMessage 5)
     *
     * Nous enregistrons une information régulièremessage. Cependant, puisque nous voulons également enregistrer le message qui a été défini,
     * nous utilisons le deuxième argument, qui représente un tableau de valeurs de contexte. Sous le capot, l'enregistreur de base de
     * données va extraire les variables de contexte qui commencent par @, !ou %avec les valeurs de l'ensemble du tableau de contexte.
     */
    if (!empty($form_state->getValue('salutation'))) {
      $this->logger->info('The Hello World salutation has been changed to @message.', ['@message' => $form_state->getValue('salutation')]);
    }
    else {
      $this->logger->info('The Hello World salutation @message.', ['@message' => 'Default one']);
    }
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
