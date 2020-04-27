<?php

/**
 * (1)
 *
 * "view_builder" = "Drupal\Core\Entity\EntityViewBuilder", : Pour le gestionnaire du générateur de vues, nous choisissons de revenir par défaut à la base EntityViewBuildercar il n'y a rien de spécifique que nos produits doivent être rendus. Plusieurs fois , cela suffira, mais vous pouvez également étendre cette classe et créer la vôtre.
 *
 * "list_builder" = "Drupal\products\ProductListBuilder", : Pour le générateur de liste, bien que les choses restent simples, nous avions besoin de notre propre implémentation afin de prendre soin de choses telles que les en-têtes de liste. Nous verrons cette classe bientôt.
 *
 * "form" = { : Le gestionnaire de formulaire pour créer et éditer des  produits est notre propre implementatio n trouvé à l' intérieur du Formnamespace de notre module.
 *
 * "route_provider" = { : Enfin, pour le fournisseur de route, nous avons utilisé la valeur par défaut AdminHtmlRouteProvider, qui prend en charge toutes les routes nécessaires à la gestion d'un type d'entité dans l'interface d'administration. Cela signifie que nous n'avons plus rien à faire pour acheminer les liens référencés dans la linkssection de l'annotation. En parlant de liens, il est logique de les placer sous la admin/structuresection de notre administration pour notre exemple, mais vous pouvez choisir un autre endroit si vous le souhaitez.
 *
 *   base_table = "product", : La table de base de données dans laquelle nos produits seront stockés est products, et l'autorisation nécessaire aux utilisateurs pour les gérer est administer site configuration. J'ai délibérément omis de créer des autorisations spécifiques à ce type d'entité car nous couvrirons ce sujet dans un chapitre dédié à l'accès . S o nous utiliserons cette autorisation qui vient avec le noyau Drupal.
 *
 *   entity_keys = { : Enfin, nous avons également quelques clés d'entité de base à mapper aux champs respectifs.
 */


namespace Drupal\products\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * Defines the Product entity.
 *
 * @ContentEntityType(
 *   id = "product",
 *   label = @Translation("Product"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\products\ProductListBuilder",
 *
 *     "form" = {
 *       "default" = "Drupal\products\Form\ProductForm",
 *       "add" = "Drupal\products\Form\ProductForm",
 *       "edit" = "Drupal\products\Form\ProductForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm",
 *     },
 *    "route_provider" = {
 *      "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider"
 *    }
 *   },
 *   base_table = "product",
 *   admin_permission = "administer site configuration",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/product/{product}",
 *     "add-form" = "/admin/structure/product/add",
 *     "edit-form" = "/admin/structure/product/{product}/edit",
 *     "delete-form" = "/admin/structure/product/{product}/delete",
 *     "collection" = "/admin/structure/product",
 *   }
 * )
 */
class Product extends ContentEntityBase implements ProductInterface {

  /**
   * Comme promis, nous utilisons le EntityChangedTraitpour gérer le changedchamp et implémenter des getters et setters simples
   * pour les valeurs trouvées dans les champs que nous avons définis comme champs de base. Si vous vous souvenez de la TypedDatasection,
   * la façon dont nous accédons à une valeur (puisque la cardinalité est toujours 1 pour ces champs) consiste à exécuter la commande suivante:
   *
   * $this->get('field_name')->value.
   */
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProductNumber() {
    return $this->get('number')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setProductNumber($number) {
    $this->set('number', $number);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRemoteId() {
    return $this->get('remote_id')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRemoteId($id) {
    $this->set('remote_id', $id);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getSource() {
    return $this->get('source')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setSource($source) {
    $this->set('source', $source);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getImage() {
    return $this->get('image')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function setImage($image) {
    $this->set('image', $image);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Product.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('')
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['number'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Number'))
      ->setDescription(t('The Product number.'))
      ->setSettings([
        'min' => 1,
        'max' => 10000
      ])
      ->setDefaultValue(NULL)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'number_unformatted',
        'weight' => -4,
      ])
      ->setDisplayOptions('form', [
        'type' => 'number',
        'weight' => -4,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['remote_id'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Remote ID'))
      ->setDescription(t('The remote ID of the Product.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('');

    $fields['source'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Source'))
      ->setDescription(t('The source of the Product.'))
      ->setSettings([
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setDefaultValue('');

    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Image'))
      ->setDescription(t('The product image.'))
      ->setDisplayOptions('form', array(
        'type' => 'image_image',
        'weight' => 5,
      ))
      ->setDisplayOptions('view', array(
        'type' => 'image',
        'weight' => 10,
        'settings' => [
          'image_style' => 'large'
        ]
      ));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
    /**
     * Tout d'abord, nous devrons hériter des champs de base de la classe parente. Cela inclut des éléments tels que les champs ID et UUID .
     *
     * Deuxièmement, nous définissons nos propres champs, en commençant par le champ du nom du produit, qui est du string type.
     * Ce string type n'est rien d'autre qu'un Field Type plugin ce plugin étend une Type dDataclasse elle-même. Outre l'étiquette et la description évidentes,
     * il a certains paramètres, notamment une longueur maximale pour la valeur, qui est de 255 caractères.
     * Les options d'affichage viewet de formréférence FieldFormatteret les Field Widget plugins, respectivement, qui ensemble avec le FieldType composent un champ.
     * Enfin, avec le setDisplayConfigurable() , nous précisons que certaines des options de ce champ doivent être configurables via l'interface utilisateur.
     * Par exemple, nous pouvons changer l'étiquette dans l'interface utilisateur.
     *
     * Ensuite, nous avons le numberchamp qui est du integertype e  et, pour cet exemple, est limité à un nombre compris entre 1 et 10 000.
     * Ce paramètre de restriction se transforme en contrainte sous le capot. Les autres options sont similaires au champ de nom.
     *
     * Ensuite, nous avons le remote_id champ de chaîne, mais il n'a pas de widget ou de paramètres d'affichage car nous ne voulons pas nécessairement afficher ou modifier cette valeur.
     * Il est principalement destiné à un usage interne pour garder une trace de l'ID produit de la source distante dont il est issu.
     * De même, le sourcechamp de chaîne n'est  ni affiché ni configurable,  car nous voulons l'utiliser pour stocker la source du produit,
     * où il a été importé depuis m, et également pour en assurer le suivi par programme.
     *
     * Enfin, les champs createdet changedsont des champs spéciaux qui stockent les horodatages de la création et de la modification de l'entité.
     * Pas beaucoup plus que cela doit être fait car ces champs définissent automatiquement les horodatages actuels comme valeurs de champ .
     */
  }
}
