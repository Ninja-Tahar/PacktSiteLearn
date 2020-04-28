<?php
/**
 * (1.6.5). gestionnaire de formulaire et commencer avec le formulaire de création / modification par défaut.
 *
 * Nous étendons  directementEntityForm dans ce cas parce que les entités de configuration n'ont pas de classe de formulaire spécifique comme les entités de contenu.
 * Pour cette raison, nous devons également implémenter les éléments de formulaire pour tous nos champs à l'intérieur de la form() méthode.
 *
 * Mais tout d'abord. Nous savons que nous voulons la configurationpour sélectionner un plugin à utiliser, donc, pour cette raison,
 * nous injectons celui que ImporterManagernous avons créé plus tôt. Nous l'utiliserons pour obtenir toutes les définitions existantes.
 * Et nous injectons également le Messengerservice pour l'utiliser plus tard afin d'imprimer un message à l'utilisateur.
 *
 * A l'intérieur de la form()méthode, nous définissons tous les éléments du formulaire pour les champs. Nous utilisons un textfieldpour l'étiquette et un machine_namechamp pour l'ID de l'entité.
 * Ce dernier est un champ spécial alimenté par JavaScript qui tire sa valeur d'un champ "source" (qui est par défaut le champlabel s'il n'est pas spécifié).
 * Il est également désactivé si nous éditons le formulaire et est utilisons un rappel dynamique pour essayer de charger une entité par l'ID fourni et échouera validation si elle existe.
 * Cela est utile pour garantir que les ID ne se répètent pas. Ensuite, nous avons un urlélément de formulaire, qui effectue une validation et une gestion spécifiques
 * à l'URL pour s'assurer qu'une URL appropriée est ajoutée. Ensuite, nous créons un tableau d' selectoptions d'éléments de tous les i mporter disponiblesdéfinitions de plugin.
 * Pour cela, nous utilisons le gestionnaire de plugins getDefinitions() , à partir duquel nous pouvons obtenir les ID et les étiquettes. Une définition de plugin est un tableau qui
 * contient principalement les données trouvées dans l'annotation et certaines autres données traitées et ajoutées par le gestionnaire (dans notre cas, uniquement les valeurs par défaut).
 * A ce stade, nos plugins ne sont pas encore instanciés . Une deuxième nous utilisons ces options sur la liste de sélection. Enfin, nous avons les éléments simples checkboxet textfieldpour
 * les deux derniers champs, car nous voulons stocker le update_existingchamp en tant que booléen et en sourcetant que chaîne.
 *
 * La save()méthode est à peu près comme dans le formulaire d'entité Produit; nous affichons simplement un message et redirige l'utilisateur vers la page de liste d'entités
 * (en utilisant la toUrl()méthode pratique sur l'entité pour créer l'URL). Puisque nous avons nommé les éléments du formulaire exactement de la même manière que les champs,
 * nous n'avons pas besoin de faire de mappage des valeurs du formulaire avec les noms des champs . Ce chapeau est pris en charge.
 */

namespace Drupal\products\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Url;
use Drupal\products\Plugin\ImporterManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form for creating/editing Importer entities.
 */
class ImporterForm extends EntityForm {

  /**
   * @var \Drupal\products\Plugin\ImporterManager
   */
  protected $importerManager;

  /**
   * ImporterForm constructor.
   *
   * @param \Drupal\products\Plugin\ImporterManager $importerManager
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   */
  public function __construct(ImporterManager $importerManager, MessengerInterface $messenger) {
    $this->importerManager = $importerManager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('products.importer_manager'),
      $container->get('messenger')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    /** @var \Drupal\products\Entity\Importer $importer */
    $importer = $this->entity;

    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name'),
      '#maxlength' => 255,
      '#default_value' => $importer->label(),
      '#description' => $this->t('Name of the Importer.'),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $importer->id(),
      '#machine_name' => [
        'exists' => '\Drupal\products\Entity\Importer::load',
      ],
      '#disabled' => !$importer->isNew(),
    ];

    $form['url'] = [
      '#type' => 'url',
      '#default_value' => $importer->getUrl() instanceof Url ? $importer->getUrl()->toString() : '',
      '#title' => $this->t('Url'),
      '#description' => $this->t('The URL to the import resource'),
      '#required' => TRUE,
    ];

    $definitions = $this->importerManager->getDefinitions();
    $options = [];
    foreach ($definitions as $id => $definition) {
      $options[$id] = $definition['label'];
    }

    $form['plugin'] = [
      '#type' => 'select',
      '#title' => $this->t('Plugin'),
      '#default_value' => $importer->getPluginId(),
      '#options' => $options,
      '#description' => $this->t('The plugin to be used with this importer.'),
      '#required' => TRUE,
    ];

    $form['update_existing'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Update existing'),
      '#description' => $this->t('Whether to update existing products if already imported.'),
      '#default_value' => $importer->updateExisting(),
    ];

    $form['source'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Source'),
      '#description' => $this->t('The source of the products.'),
      '#default_value' => $importer->getSource(),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\products\Entity\Importer $importer */
    $importer = $this->entity;
    $status = $importer->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger->addMessage($this->t('Created the %label Importer.', [
          '%label' => $importer->label(),
        ]));
        break;

      default:
        $this->messenger->addMessage($this->t('Saved the %label Importer.', [
          '%label' => $importer->label(),
        ]));
    }

    $form_state->setRedirectUrl($importer->toUrl('collection'));
  }

}
