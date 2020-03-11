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

/**
 * A logger that sends an email when the log type is "error".
 */
class MailLogger implements LoggerInterface {

  use RfcLoggerTrait;

  /**
   * {@inheritdoc}
   *
   *  responsable de la journalisation proprement dite.
   */
  public function log($level, $message, array $context = array()) {
// Log our message to the logging system.
  }
}
