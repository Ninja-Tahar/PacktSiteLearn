<?php

/*
 * Assez simple, non? Que peuvent faire les abonnés? C'est très similaire à ce que nous avons vu concernant
 * l'exemple sur les redirections dans la section précédente. Tout ce qu'un abonné doit faire, c'est écouter
 * l' SalutationEvent::EVENTévénement et faire quelque chose en fonction de cela. La principale chose qu'il peut faire
 * est d'utiliser la setValue()méthode sur l'objet événement reçu pour modifier le message de salutation. Il peut également
 * utiliser la stopPropagation()méthode de la Eventclasse de base pour informer le répartiteur d'événements de ne plus déclencher
 * d'autres écouteurs qui se sont abonnés à cet événement.
 */

/**
 * Envoi d'événements
 *
 * L'objectif principal de cette classe d'événements est qu'une instance de celle-ci
 * sera utilisée pour transporter la valeur de notre message de salutation. C'est
 * pourquoi nous avons créé la $messagepropriété sur la classe et ajouté les méthodes
 * getter et setter. De plus, nous l'utilisons pour définir une constante pour le nom
 * réel de l'événement qui sera distribué. Enfin, la classe s'étend de la Eventclasse
 * de base fournie avec le composant Event Dispatcher comme pratique standard. Nous pourrions
 * également utiliser cette classe directement, mais nous ne conserverions pas nos données comme
 * nous le faisons actuellement
 *
 * Ensuite, il est temps d'injecter le service Event Dispatcher dans notre HelloWorldSalutationservice.
 * Nous avons déjà injecté config.factory, nous avons donc juste besoin d'ajouter un nouvel argument à la définition du service:
 */

namespace Drupal\hello_world;

use Symfony\Component\EventDispatcher\Event;

/**
 * Event class to be dispatched from the HelloWorldSalutation service.
 */
class SalutationEvent extends Event {

  const EVENT = 'hello_world.salutation_event';

  /**
   * The salutation message.
   *
   * @var string
   */
  protected $message;

  /**
   * @return mixed
   */
  public function getValue() {
    return $this->message;
  }

  /**
   * @param mixed $message
   */
  public function setValue($message) {
    $this->message = $message;
  }
}
