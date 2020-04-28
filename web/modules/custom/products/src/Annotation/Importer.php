<?php
/**
 * (1.3).
 * Cette classe doit être étendue Drupal\Component\Annotation\Plugin, qui est la classe de base pour les annotations et déjà implémentée AnnotationInterface.
 *
 * Pour notre objectif, nous gardons les choses simples. Tout ce dont nous avons besoin est un ID de plugin et une étiquette. Si nous le voulions, nous pourrions ajouter plus de propriétés à cette classe et les décrire.
 * Il est une pratique courante de faire l o parce que sinon il n'y a pas moyen de savoir quelles propriétés une annotation de plug - in peut contenir.
 */

namespace Drupal\products\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines an Importer item annotation object.
 *
 * @see \Drupal\products\Plugin\ImporterManager
 *
 * @Annotation
 */
class Importer extends Plugin {

  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;
}
