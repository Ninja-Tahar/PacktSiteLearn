<?php
/**
 * (plugin personnalisé 4) cette classe de base (abstraite) peut ressembler à ceci.
 *
 * les plugins de tout type doivent être étendus Plugin Base car ils contiennent une multitude de méthodes obligatoires implémentées (telles que celles que j'ai mentionnées précédemment).
 * Cependant, il est également recommandé que le module qui introduit un type de plugin fournisse également une classe de plugin de base que les plugins peuvent étendre.
 * Son objectif est d'étendre PluginBase et de fournir également toute la logique nécessaire à tous les plugins de ce type
 *
 * Nous implémentons ImporterInterface(renommé pour éviter les collisions) pour exiger que les sous-classes aient la import()méthode.
 * Cependant, nous informons également le conteneur de plugins et injectons déjà certains services utiles.
 * La première est EntityTypeManagerque nous nous attendons à ce que tous les importateurs en aient besoin.
 * L'autre est le client HTTP Guzzle que nous utilisons dans Drupal 8 pour effectuer des requêtes PSR-7 vers des ressources externes.
 */

namespace Drupal\products\Plugin;

use Drupal\Component\Plugin\Exception\PluginException;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\products\Entity\ImporterInterface;
use Drupal\products\Plugin\ImporterInterface as ImporterPluginInterface;
use GuzzleHttp\Client;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for Importer plugins.
 */
abstract class ImporterBase extends PluginBase implements ImporterPluginInterface, ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * {@inheritdoc}
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManager $entityTypeManager, Client $httpClient) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
    $this->httpClient = $httpClient;

    if (!isset($configuration['config'])) {
      throw new PluginException('Missing Importer configuration.');
    }

    if (!$configuration['config'] instanceof ImporterInterface) {
      throw new PluginException('Wrong Importer configuration.');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $container->get('http_client')
    );
  }
}
