<?php
/**
 * (3)
 *
 * Passons maintenant à l' annotation du plugin de type entity et créons les gestionnaires auxquels nous avons fait référence.
 * Nous pouvons commencer avec le générateur de liste, que nous pouvons placer à la racine de notre espace de noms:
 *
 * Le but de ce gestionnaire est de créer la page d'administration qui répertorie les entités disponibles. Sur cette page,
 * nous aurons alors quelques informations à leur sujet, ainsi que des liens d'opération pour modifier et supprimer et tout ce dont nous pourrions avoir besoin.
 * Pour nos produits, nous étendons simplement la EntityListBuilderclasse par défaut , mais nous remplaçons les méthodes buildHeader()et builderRow()
 * pour ajouter des informations spécifiques à nos produits. Les noms de ces méthodes sont explicites, mais une chose à garder à l'esprit est
 * que les clés du $headertableau que nous renvoyons doivent correspondre aux clés du $rowtableau que nous renvoyons. En outre, bien sûr,
 * les tableaux doivent avoir le même nombre d'enregistrements afin que l'en-tête du tableau corresponde aux lignes individuelles. Si vous regardez à l'intérieurEntityListBuilder,
 * vous pouvez noter d'autres méthodes pratiques que vous voudrez peut-être remplacer, comme celle qui génère la requêteet celui qui charge les entités. Pour nous, cela suffit.
 *
 * Notre générateur de liste de produits n'aura, pour l'instant, que deux colonnes: l'ID et le nom. Pour ce dernier, chaque ligne sera en fait un lien vers la p roduit URL canonique
 * (l'URL principale de cette entité dans Drupal). Enfin, vous vous souvenez, à partir du chapitre 2 , Création de votre premier module , comment créer des liens avec la Linkclasse, non?
 */

namespace Drupal\products;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * EntityListBuilderInterface implementation responsible for the Product entities.
 */
class ProductListBuilder extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Product ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\products\Entity\Product */
    $row['id'] = $entity->id();
    $row['name'] = Link::fromTextAndUrl(
      $entity->label(),
      new Url(
        'entity.product.canonical', [
          'product' => $entity->id(),
        ]
      )
    );
    return $row + parent::buildRow($entity);
  }

}
