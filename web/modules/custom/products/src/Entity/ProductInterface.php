<?php

/**
 * (2)
 *
 * Notre Productclasse étend la ContentEntityBaseclasse pour hériter de tout le nécessaire de l'API et implémente la
 * nôtre ProductInterface, qui contiendra toutes les méthodes utilisées pour accéder aux valeurs de champ pertinentes.
 * Créons cette interface très rapidement dans le même Entitydossier:
 *
 * Comme vous pouvez le voir, nous étendons le obligatoire ContentEntityInterfacemais aussi le EntityChangedInterface,
 * qui fournit quelques méthodes pratiques pour gérer la dernière date de modification des entités.
 * Ces implémentations de méthode seront ajoutés à notre Productclasse via leEntityChangedTrait :
 */

namespace Drupal\products\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

interface ProductInterface extends ContentEntityInterface, EntityChangedInterface {

  /**
   * Gets the Product name.
   *
   * @return string
   */
  public function getName();

  /**
   * Sets the Product name.
   *
   * @param string $name
   *
   * @return \Drupal\products\Entity\ProductInterface
   *   The called Product entity.
   */
  public function setName($name);

  /**
   * Gets the Product number.
   *
   * @return int
   */
  public function getProductNumber();

  /**
   * Sets the Product number.
   *
   * @param int $number
   *
   * @return \Drupal\products\Entity\ProductInterface
   *   The called Product entity.
   */
  public function setProductNumber($number);

  /**
   * Gets the Product remote ID.
   *
   * @return string
   */
  public function getRemoteId();

  /**
   * Sets the Product remote ID.
   *
   * @param string $id
   *
   * @return \Drupal\products\Entity\ProductInterface
   *   The called Product entity.
   */
  public function setRemoteId($id);

  /**
   * Gets the Product source.
   *
   * @return string
   */
  public function getSource();

  /**
   * Sets the Product source.
   *
   * @param string $source
   *
   * @return \Drupal\products\Entity\ProductInterface
   *   The called Product entity.
   */
  public function setSource($source);

  /**
   * Gets the Product creation timestamp.
   *
   * @return int
   */
  public function getCreatedTime();

  /**
   * Sets the Product creation timestamp.
   *
   * @param int $timestamp
   *
   * @return \Drupal\products\Entity\ProductInterface
   *   The called Product entity.
   */
  public function setCreatedTime($timestamp);
}
