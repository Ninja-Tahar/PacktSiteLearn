<?php
/**
 * (1.6.4). créons le gestionnaire de générateur de liste qui prendra en charge la liste des entités d'administration
 *
 * Cette tim e nous étendons laConfigEntityListBuilder , qui fournit des fonctionnalités spécifiques aux entités de configuration. Cependant,
 * nous faisons essentiellement la même chose que pour la liste des produits: configurer l'en-tête du tableau et les données de ligne individuelle,
 * rien de majeur. Je vous recommande d'inspecter ConfigEntityListBuilderet de voir ce que vous pouvez faire d'autre dans la sous-classe
 */

namespace Drupal\products;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Importer entities.
 */
class ImporterListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Importer');
    $header['id'] = $this->t('Machine name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    $row['label'] = $entity->label();
    $row['id'] = $entity->id();
    return $row + parent::buildRow($entity);
  }
}
