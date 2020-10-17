<?php

   namespace Grayl\Store\Product\Entity;

   use Grayl\Mixin\Common\Entity\FlatDataBag;
   use Grayl\Mixin\Common\Entity\KeyedDataBag;

   /**
    * Class ProductData
    * The entity for product data
    *
    * @package Grayl\Store\Product
    */
   class ProductData
   {

      /**
       * A unique product SKU (no spaces or special characters)
       *
       * @var string
       */
      private string $sku;

      /**
       * The display name of the product
       *
       * @var string
       */
      private string $name;

      /**
       * The price of the product (original price without discounts)
       *
       * @var float
       */
      private float $price;

      /**
       * A set of related tags
       *
       * @var FlatDataBag
       */
      private FlatDataBag $tags;

      /**
       * Miscellaneous product settings
       *
       * @var KeyedDataBag
       */
      private KeyedDataBag $settings;


      /**
       * The class constructor
       *
       * @param string   $sku      A unique product SKU (no spaces or special characters)
       * @param string   $name     The display name of this product
       * @param float    $price    The price of the product (original price without discounts)
       * @param string[] $tags     A set of related tags
       * @param array    $settings Miscellaneous product settings
       */
      public function __construct ( string $sku,
                                    string $name,
                                    float $price,
                                    array $tags,
                                    array $settings )
      {

         // Create the bags
         $this->tags     = new FlatDataBag();
         $this->settings = new KeyedDataBag();

         // Set the class data
         $this->setSKU( $sku );
         $this->setName( $name );
         $this->setPrice( $price );
         $this->addTags( $tags );
         $this->setSettings( $settings );
      }


      /**
       * Gets the SKU
       *
       * @return string
       */
      public function getSKU (): string
      {

         // Return the SKU
         return $this->sku;
      }


      /**
       * Sets the SKU
       *
       * @param string $sku A unique product SKU (no spaces or special characters)
       */
      public function setSKU ( string $sku ): void
      {

         // Set the SKU
         $this->sku = $sku;
      }


      /**
       * Gets the product name
       *
       * @return string
       */
      public function getName (): string
      {

         // Return the name
         return $this->name;
      }


      /**
       * Sets the product name
       *
       * @param string $name The product name to set
       */
      public function setName ( string $name ): void
      {

         // Set the name
         $this->name = $name;
      }


      /**
       * Gets the price (without discounts)
       *
       * @return float
       */
      public function getPrice (): float
      {

         // Return the price
         return $this->price;
      }


      /**
       * Sets the price (without discounts)
       *
       * @param float $price The price of the product (original price without discounts)
       */
      public function setPrice ( float $price ): void
      {

         // Set the price
         $this->price = $price;
      }


      /**
       * Gets the array of tags
       *
       * @return array
       */
      public function getTags (): array
      {

         // Return the array of tags
         return $this->tags->getPieces();
      }


      /**
       * Adds a single tag to the product
       *
       * @param string $tag The tag to add to the product
       */
      public function addTag ( string $tag ): void
      {

         // Add the tag
         $this->tags->putPiece( $tag );
      }


      /**
       * Adds multiple tags to the product
       *
       * @param string[] $tags An array of tags to add to the product
       */
      public function addTags ( array $tags ): void
      {

         // Add the tags
         $this->tags->putPieces( $tags );
      }


      /**
       * Retrieves the value of product setting
       *
       * @param string $key The key name for the setting
       *
       * @return mixed
       */
      public function getSetting ( string $key )
      {

         // Return the value
         return $this->settings->getVariable( $key );
      }


      /**
       * Retrieves the entire array of product settings
       *
       * @return array
       */
      public function getSettings (): array
      {

         // Return all setting
         return $this->settings->getVariables();
      }


      /**
       * Sets a single product setting
       *
       * @param string $key   The key name for the setting
       * @param mixed  $value The value of the setting
       */
      public function setSetting ( string $key,
                                   $value ): void
      {

         // Set the setting
         $this->settings->setVariable( $key,
                                       $value );
      }


      /**
       * Sets multiple product settings using a passed array
       *
       * @param array $settings The associative array of product settings to set ( key => value )
       */
      public function setSettings ( array $settings ): void
      {

         // Set the settings
         $this->settings->setVariables( $settings );
      }

   }

