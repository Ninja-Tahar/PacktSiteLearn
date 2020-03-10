<?php
/**
 * Our first block plugin
 */

namespace Drupal\hello_world\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\hello_world\HelloWorldSalutation as HelloWorldSalutationService;

/*
 * La chose la plus étrange que vous remarquerez peut-être est le commentaire DocBlock en haut de la classe.
 * Cela s'appelle une annotation et indique que cette classe est un Blockplugin.
 *
 * Dans ce cas, la définition du plugin dont nous avons besoin est constituée d'un
 * ID et d'une étiquette d'administration.
 */

/**
 * Hello World Salutation block.
 *
 * @Block(
 *  id = "hello_world_salutation_block",
 *  admin_label = @Translation("Hello world salutation"),
 * )
 */
class HelloWorldSalutationBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * The salutation service.
   *
   * @var \Drupal\hello_world\HelloWorldSalutation
   */
  protected $salutation;

  /*
   * $configuration, $plugin_idet $plugin_definition.
   * Le premier contient toutes les valeurs de configuration qui ont été stockées avec le plug-in (ou transmises lors de la construction),
   * le second est l'ID défini dans l'annotation du plug-in (ou un autre mécanisme de découverte)
   * le troisième est un tableau qui contient les métadonnéesde ce plugin (y compris toutes les informations trouvées dans l'annotation).
   */
  /**
   * Construct.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param string $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\hello_world\HelloWorldSalutation $salutation *   The salutation service.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, HelloWorldSalutationService $salutation)
  {
    // use Drupal\hello_world\HelloWorldSalutation as HelloWorldSalutationService;
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->salutation = $salutation;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('hello_world.salutation')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    // $config = $this->getConfiguration(); if we need to use the configuration.
    return [
      '#markup' => $this->salutation->getSalutation(),
    ];
  }

  /**
   * {@inheritdoc}
   *
   * Nous retournons un tableau de clés et de valeurs qui seront dans la configuration.
   * De plus, puisque nous avons dit que nous allons avec un champ booléen,
   * nous utilisons le numéro 1 comme valeur d'une clé fictive nommée enabled.
   */
  public function defaultConfiguration()
  {
    return [
      'enabled' => 1,
    ];
  }

  /**
   * {@inheritdoc}
   *
   * Ensuite, nous aurions besoin d'implémenter la blockForm() méthode
   * qui fournit notre définition de formulaire pour cet élément de configuration:
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $config = $this->getConfiguration();

    $form['enabled'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enabled'),
      '#description' => t('Check this box if you want to enable this feature.'),
      '#default_value' => $config['enabled'],
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   *
   * Enfin, nous aurions besoin du gestionnaire de soumission qui fera
   * le nécessaire pour "stocker" la configuration
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $this->configuration['enabled'] = $form_state->getValue('enabled');
  }

  /**
   * {@inheritdoc}
   *
   * En peut utilisie la foction de validation.
   */
  public function blockValidate($form, FormStateInterface $form_state) {}

}
