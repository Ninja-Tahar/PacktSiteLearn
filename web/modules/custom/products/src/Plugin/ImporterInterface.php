<?php
/**
 * (plugin personnalisé 3). écrivons également l'interface que les plugins doivent implémenter
 *
 *
 */

namespace Drupal\products\Plugin;

use Drupal\Component\Plugin\PluginInspectionInterface;

/**
 * Defines an interface for Importer plugins.
 */
interface ImporterInterface extends PluginInspectionInterface {

  /**
   * Performs the import. Returns TRUE if the import was successful or FALSE otherwise.
   *
   * @return bool
   */
  public function import();
}
