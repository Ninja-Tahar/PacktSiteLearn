<?php
/**
 * @file
 *  Comme je l'ai mentionné plus tôt, je veux que mes salutations soient plus dynamiques
 *  c'est-à-dire que je veux que la salutation dépende de l'heure de la journée Donc,
 *  nous allons créer une HelloWorldSalutation classe ( ) qui est responsable de le
 *  faire et la placer dans le /src dossier
 */

namespace Drupal\hello_world;

use DateTime;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Prepares the salutation to the world.
 */
class HelloWorldSalutation {

  use StringTranslationTrait;

  // apres ajoute le arguiment de @config.factory il faut le cherge sur notre class.
  //Dans la section précédente , nous avons créé un formulaire quipermet aux administrateurs de
  // définir un message de salutation personnalisé à afficher sur la page. Ce message a été stocké
  // dans un objet de configuration que nous pouvons maintenant charger dans notre HelloWorldSalutationservice.
  // Faisons donc cela dans un processus en deux étapes.

  /**
   * étape 1
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * @var \Symfony\Component\EventDispatcher\EventDispatcherInterface
   */
  protected $eventDispatcher;

  /**
   * étape 2
   *
   * HelloWorldSalutation constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
   */
  public function __construct(ConfigFactoryInterface $config_factory, EventDispatcherInterface $eventDispatcher) {
    $this->configFactory = $config_factory;
    $this->eventDispatcher = $eventDispatcher;
  }

  /**
   * Returns the salutation
   */
  public function getSalutation() {

    $config = $this->configFactory->get('hello_world.custom_salutation'); // The id getEditableConfigNames() {return ['hello_world.custom_salutation'];}
    $salutation = $config->get('salutation');
    // Apres ajoute une class SalutationEvent that extend from event and added like event dispatcher service[@argument]
    // Maintenant, nous pouvons utiliser le répartiteur. Donc, au lieu du code suivant :
//    if ($salutation != "") {
//      return $salutation;
//    }

    // Nous pouvons avoir les éléments suivants :
    if ($salutation != "") {
      $event = new SalutationEvent();
      $event->setValue($salutation);
      $event = $this->eventDispatcher->dispatch(SalutationEvent::EVENT, $event);
      return $event->getValue();
    }

    $time = new DateTime();
    if ((int) $time->format('G') >= 00 && (int) $time->format('G') < 12) {
      return $this->t('Good morning world');
    }

    if ((int) $time->format('G') >= 12 && (int) $time->format('G') < 18) {
      return $this->t('Good afternoon world');
    }

    if ((int) $time->format('G') >= 18) {
      return $this->t('Good evening world');
    }
  }

  /**
   * Returns the Salutation render array.
   *
   * (theme 5)
   *
   * (lien-contextuels 3)
   *
   * nous avons ajouté notre #contextual_linksélément de rendu. Deuxièmement, chaque fois que nous devons définir
   * la valeur de la chaîne de salutation ci-dessous, nous le faisons dans un #markupélément cette fois, car, comme nous
   * l'avons vu dans le chapitre précédent, nous avons besoin d'une propriété qui définit la façon dont le tableau de rendu est rendu.
   */
  public function getSalutationComponent() {
    $render = [
      '#theme' => 'hello_world_salutation',
      '#salutation' => [
        '#contextual_links' => [
          'hello_world' => [
            'route_parameters' => []
          ],
        ]
      ]
    ];
//    $render = [
//      '#theme' => 'hello_world_salutation',
//    ];
// (theme 5)

    $config = $this->configFactory->get('hello_world.custom_salutation');
    $salutation = $config->get('salutation');

    if ($salutation != "") {
      $render['#salutation']['#markup'] = $salutation;
      $render['#overridden'] = TRUE;
      return $render;
    }

    $time = new \DateTime();
    $render['#target'] = $this->t('world');

    if ((int) $time->format('G') >= 00 && (int) $time->format('G') < 12) {
      $render['#salutation']['#markup'] = $this->t('Good morning');
      return $render;
    }

    if ((int) $time->format('G') >= 12 && (int) $time->format('G') < 18) {
      $render['#salutation']['#markup'] = $this->t('Good afternoon');
      return $render;
    }

    if ((int) $time->format('G') >= 18) {
      $render['#salutation']['#markup'] = $this->t('Good evening');
      return $render;
    }
  }
}
