<?php

   namespace Grayl\Store\Product;

   use Grayl\Config\ConfigPorter;
   use Grayl\Mixin\Common\Entity\KeyedDataBag;
   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Store\Product\Controller\ProductController;
   use Grayl\Store\Product\Entity\ProductData;
   use Grayl\Store\Product\Entity\ProductDiscount;
   use Grayl\Store\Product\Service\ProductService;

   /**
    * Front-end for the Product package
    *
    * @package Grayl\Store\Product
    */
   class ProductPorter
   {

      // Use the static instance trait
      use StaticTrait;

      /**
       * The name of the config folder where product config files are stored
       *
       * @var string
       */
      private string $config_folder = 'store-product';

      /**
       * A KeyedDataBag that holds previously created ProductControllers
       *
       * @var KeyedDataBag
       */
      private KeyedDataBag $saved_products;


      /**
       * The class constructor
       *
       * @throws \Exception
       */
      public function __construct ()
      {

         // Create a KeyedDataBag for storing products
         $this->saved_products = new KeyedDataBag();
      }


      /**
       * Loads a ProductController from a config file
       *
       * @param string $sku The unique SKU of the product to load from the config folder
       *
       * @return ProductController
       * @throws \Exception
       */
      private function loadProductControllerFromConfigFile ( string $sku ): ProductController
      {

         // Grab the product's config file
         /** @var  $product_controller ProductController */
         $product_controller = ConfigPorter::getInstance()
                                           ->includeConfigFile( $this->config_folder . '/' . $sku . '.php' );

         // Return the ProductController
         return $product_controller;
      }


      /**
       * Retrieves a previously created ProductController entity if it exists, otherwise a new one is created
       *
       * @param string $sku The product SKU
       *
       * @return ProductController
       * @throws \Exception
       */
      public function getSavedProductController ( string $sku ): ProductController
      {

         // Check to see if there is already a saved ProductController for this SKU
         $controller = $this->saved_products->getVariable( $sku );

         // If we don't have an entity for this controller yet, create one
         if ( empty ( $controller ) ) {
            // Request the ProductController
            $controller = $this->loadProductControllerFromConfigFile( $sku );

            // Save it for re-use
            $this->saved_products->setVariable( $sku,
                                                $controller );
         }

         // Return the controller
         return $controller;
      }


      /**
       * Creates a new ProductController
       *
       * @param string   $sku      A unique product SKU (no spaces or special characters)
       * @param string   $name     The display name of this product
       * @param float    $price    The price of the product (original price without discounts)
       * @param string[] $tags     A set of related tags
       * @param array    $settings Miscellaneous product settings
       *
       * @return ProductController
       */
      public function newProductController ( string $sku,
                                             string $name,
                                             float $price,
                                             array $tags,
                                             array $settings ): ProductController
      {

         // Request a new ProductData entity
         $product_data = new ProductData( $sku,
                                          $name,
                                          $price,
                                          $tags,
                                          $settings );

         // Return a ProductController
         return new ProductController( $product_data,
                                       null,
                                       new ProductService() );
      }


      /**
       * Returns a new ProductDiscount object
       *
       * @param float $discount          The percentage discount (i.e. "25.00" for 25% off)
       * @param bool  $round_down        Round down product prices after applying discount? (149.25 become 149.00)
       * @param array $override_settings Miscellaneous product override settings
       *
       * @return ProductDiscount
       */
      public function newProductDiscount ( float $discount,
                                           bool $round_down,
                                           array $override_settings ): ProductDiscount
      {

         // Return a new ProductDiscount entity
         return new ProductDiscount( $discount,
                                     $round_down,
                                     $override_settings );
      }

   }