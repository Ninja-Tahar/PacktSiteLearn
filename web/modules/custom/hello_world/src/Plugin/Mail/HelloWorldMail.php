<?php
/**
 * (custom plugin mail 1)
 */

namespace Drupal\hello_world\Plugin\Mail;

use Drupal\Core\Mail\MailFormatHelper;
use Drupal\Core\Mail\MailInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
/*
 * Nous pouvons remplacer cette valeur par notre ID de plugin, ce qui signifie que tous les e-mails seront envoyés avec notre plugin
 *
 * Nous pouvons ajouter un nouvel enregistrement avec la clé au module_name_key_nameformat, ce qui signifie que tous les e-mails envoyés
 * pour un module avec une clé (ou un modèle ) spécifique utiliseront ce plugin
 *
 *  Nous pouvons ajouter un nouvel enregistrement avec la clé au module_nameformat, ce qui signifie que tous les e-mails envoyés pour un
 * module utiliseront ce plugin (quelle que soit leur clé)
 */
/**
 * Defines the Hello World mail backend.
 *
 * @Mail(
 *   id = "hello_world_mail",
 *   label = @Translation("Hello World mailer"),
 *   description = @Translation("Sends an email using an external API specific to our Hello World module.")
 * )
 */
class HelloWorldMail implements MailInterface, ContainerFactoryPluginInterface {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static();
  }

  /**
   * {@inheritdoc}
   *
   * J'ai mentionné la format()méthode plus tôt et j'ai dit qu'il était responsable d'effectuer certains traitements avant que le message ne soit prêt à être envoyé.
   */
  public function format(array $message) {
// Join the body array into one string.
    $message['body'] = implode("\n\n", $message['body']);
// Convert any HTML to plain-text.
    $message['body'] = MailFormatHelper::htmlToText($message['body']);
// Wrap the mail body for sending.
    $message['body'] = MailFormatHelper::wrapMail($message['body']);

    return $message;
  }

  /**
   * {@inheritdoc}
   */
  public function mail(array $message) {
// Use the external API to send the email based on the $message array
    // constructed via the `hook_mail()` implementation.
  }
}
