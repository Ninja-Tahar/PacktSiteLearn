<?php
/**
 *  Apres create the canal on service file.
 *
 * La première chose que nous devrons créer est l' LoggerInterfaceimplémentation,
 * qui va généralement dans le Loggerdossier de notre espace de noms. Alors appelons
 * le nôtre MailLogger. Et cela peut ressembler à ceci: (MailMessage 2)
 */

namespace Drupal\hello_world\Logger;

use Drupal\Core\Logger\RfcLoggerTrait;
use Psr\Log\LoggerInterface;
use Drupal\Core\Logger\LogMessageParserInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Component\Render\FormattableMarkup;
use Drupal\Core\Logger\RfcLogLevel;

/**
 * A logger that sends an email when the log type is "error".
 */
class MailLogger implements LoggerInterface {

  use RfcLoggerTrait;

  /**
   * @var \Drupal\Core\Logger\LogMessageParserInterface
   *
   * (l' envoiles e-mails 3) finish
   */
  protected $parser;

  /**
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * MailLogger constructor.
   *
   * @param \Drupal\Core\Logger\LogMessageParserInterface $parser
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   */
  public function __construct(LogMessageParserInterface $parser, ConfigFactoryInterface $config_factory) {
    $this->parser = $parser;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   *
   *  responsable de la journalisation proprement dite.
   *
   * (l' envoiles e-mails 2) Nous voulions utiliser notre MailLoggerpour envoyer un e-mailchaque fois que nous
   * enregistrons une erreur. Revenons donc à notre classe et ajoutons cette logique.
   */
  public function log($level, $message, array $context = array()) {
    // Nous avons dit que nous ne voulions envoyer des e-mails que pour les erreurs
    if ($level !== RfcLogLevel::ERROR) {
      return;
    }

    //  nous déterminons à qui nous voulons que l'e-mail soit envoyé et le langcode pour l'envoyer (les deux sont des arguments obligatoires pour la mail()
    $to = $this->configFactory->get('system.site')->get('mail');
    $langcode = $this->configFactory->get('system.site')->get('langcode');

    $variables = $this->parser->parseMessagePlaceholders($message, $context);
    $markup = new FormattableMarkup($message, $variables);
    //  La 2 eme paramatre de function de mail() est la clé (ou modèle ) que nous voulons utiliser pour elle (que nous avons définie dans hook_mail()).
    // le cinquième est le $paramstableau que nous avons rencontré hook_mail().
    \Drupal::service('plugin.manager.mail')->mail('hello_world', 'hello_world_log', $to, $langcode, ['message' => $markup]);
  }
}
