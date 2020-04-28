<?php
/**
 * (1.6.2).
 *
 * Dans ces entités de configuration, nous voulons stocker, pour l'instant, une URL vers la ressource à partir de laquelle les produits peuvent être récupérés,
 * l'ID du plugin i mporter à utiliser, si nous voulons que les produits existants soient mis à jour s'ils ont déjà été importés et la source des produits.
 * Pour tous ces champs, nous créons des méthodes getter . Vous remarquerez que getUrl()doit renvoyer une Urlinstance.
 * Encore une fois, nous créons une interface bien définie pour l'API publique du type d'entité e comme nous l'avons fait avec le type d'entité produit.
 */

namespace Drupal\products\Entity;

use Drupal\Core\Config\Entity\ConfigEntityInterface;
use Drupal\Core\Url;

/**
 * Importer configuration entity.
 */
interface ImporterInterface extends ConfigEntityInterface {

  /**
   * Returns the Url where the import can get the data from.
   *
   * @return Url
   */
  public function getUrl();

  /**
   * Returns the Importer plugin ID to be used by this importer.
   *
   * @return string
   */
  public function getPluginId();

  /**
   * Whether or not to update existing products if they have already been imported.
   *
   * @return bool
   */
  public function updateExisting();

  /**
   * Returns the source of the products.
   *
   * @return string
   */
  public function getSource();
}
