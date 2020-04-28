<?php
/**
 * (1.1)
 *
 * En plus d'étendre leDefaultPluginManager , nous aurons besoin de remplacer le constructeur et de rappeler le constructeur parent avec certains paramètres spécifiques à nos plugins.
 * C'est la partie la plus importante, et dans l'ordre, ce sont les suivantes (en omettant celles qui sont simplement transmises):
 *
 * L'espace de noms relatif où se trouvent les plugins de ce type - dans ce cas, dans le Plugin/Importerdossier.
 * L'interface que chaque plugin de ce type doit implémenter - dans notre cas, le Drupal\products\Plugin\ImporterInterface (que nous devons créer).
 * La annotationclasse utilisée par notre type de plugin (celle dont les propriétés de classe correspondent aux propriétés d'annotation possibles trouvées dans le DocBlock au-dessus de la classe de plugin) - dans notre cas, Drupal\products\Annotation\Importer(que nous devons créer).
 *
 * En plus d'appeler le constructeur parent avec ces options, nous devrons fournir le hook "alter" pour les définitions disponibles.
 * Cela permettra à d'autres modules d'implémenter ce hook et de modifier les définitions de plugin trouvées.
 * Le crochet résultant dans notre cas est hook_products_importer_info_alter.
 *
 */

namespace Drupal\products\Plugin;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;

/**
 * Provides the Importer plugin manager.
 */
class ImporterManager extends DefaultPluginManager {

  /**
   * ImporterManager constructor.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   Cache backend instance to use.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler to invoke the alter hook with.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/Importer', $namespaces, $module_handler, 'Drupal\products\Plugin\ImporterInterface', 'Drupal\products\Annotation\Importer');

    $this->alterInfo('products_importer_info');
    $this->setCacheBackend($cache_backend, 'products_importer_plugins');
  }
}
