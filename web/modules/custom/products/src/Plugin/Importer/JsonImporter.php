<?php
/**
 * (1.7.1)  Le plugin Importer.
 *
 * Vous pouvez immédiatement repérer le p lugin Annotatio n où nous précisons une carte d' identité et une étiquette.
 * Ensuite, en élargissant ImporterBase, nous héritons les services dépendants et veiller à ce que le nécessairel'interface est implémentée.
 * En parlant de cela, nous devons simplement implémenter la import()méthode. Alors, décomposons ce que nous faisons:
 *
 * À l'intérieur de la getData() méthode, nous récupérons les informations produit de la ressource distante.
 * Nous le faisons en obtenant l'URL de l' Importerentité de configuration et en utilisant Guzzle pour faire une demande à cette URL.
 * Nous nous attendons à ce que ce soit du JSON, nous le décodons donc comme tel. Bien sûr, la gestion des erreurs est pratiquement inexistante dans cet exemple, et ce n'est pas bon.
 *
 * Nous parcourons les données produit résultantes et appelons la persistProduct() méthode sur chaque article. Là-bas, nous vérifions d'abord si nous avons déjà l'entité produit.
 * Nous le faisons en utilisant la loadByProperties() méthode simple sur le stockage d'entité de produit et essayons de trouver des produits qui ont la source spécifique et l'ID distant.
 * S'il n'en existe pas, nous le créons. Tout cela devrait être familier du chapitre précédent lorsque nous avons examiné la manipulation des entités.
 * Si le produit existe déjà, nous vérifions d'abord si le r selon la configuration, nous pouvons le mettre à jour et ne le faisons que si cela nous le permet.
 * La loadByProperties() méthode renvoie toujours un tableau d'entités, mais comme nous nous attendons à n'avoir qu'un seul produit avec le même ID distant et la même combinaison source,
 * nous reset()ce tableau pour arriver à cette seule entité. Ensuite, nous définissons simplement le nom et le numéro de produit sur l'entité.
 */

namespace Drupal\products\Plugin\Importer;

use Drupal\products\Plugin\ImporterBase;

/**
 * Product importer from a JSON format.
 *
 * @Importer(
 *   id = "json",
 *   label = @Translation("JSON Importer")
 * )
 */
class JsonImporter extends ImporterBase {

  /**
   * {@inheritdoc}
   */
  public function import() {
    $data = $this->getData();
    if (!$data) {
      return FALSE;
    }

    if (!isset($data->products)) {
      return FALSE;
    }

    $products = $data->products;
    foreach ($products as $product) {
      $this->persistProduct($product);
    }
    return TRUE;
  }

  /**
   * Loads the product data from the remote URL.
   *
   * @return \stdClass
   */
  private function getData() {
    /** @var \Drupal\products\Entity\ImporterInterface $config */
    $config = $this->configuration['config'];
    $request = $this->httpClient->get($config->getUrl()->toString());
    $string = $request->getBody()->getContents();
    return json_decode($string);
  }

  /**
   * Saves a Product entity from the remote data.
   *
   * @param \stdClass $data
   */
  private function persistProduct($data) {
    /** @var \Drupal\products\Entity\ImporterInterface $config */
    $config = $this->configuration['config'];

    $existing = $this->entityTypeManager->getStorage('product')->loadByProperties(['remote_id' => $data->id, 'source' => $config->getSource()]);
    if (!$existing) {
      $values = [
        'remote_id' => $data->id,
        'source' => $config->getSource()
      ];
      /** @var \Drupal\products\Entity\ProductInterface $product */
      $product = $this->entityTypeManager->getStorage('product')->create($values);
      $product->setName($data->name);
      $product->setProductNumber($data->number);
      $product->save();
      return;
    }

    if (!$config->updateExisting()) {
      return;
    }

    /** @var \Drupal\products\Entity\ProductInterface $product */
    $product = reset($existing);
    $product->setName($data->name);
    $product->setProductNumber($data->number);
    $product->save();
  }
}
