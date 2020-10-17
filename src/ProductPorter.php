<?php

   namespace Grayl\Store\Product;

   use Grayl\Config\ConfigPorter;
   use Grayl\Config\Controller\ConfigController;
   use Grayl\Mixin\Common\Entity\KeyedDataBag;
   use Grayl\Mixin\Common\Traits\StaticTrait;
   use Grayl\Store\Product\Controller\ProductController;
   use Grayl\Store\Product\Entity\ProductData;
   use Grayl\Store\Product\Entity\ProductDiscount;
   use Grayl\Store\Product\Service\ProductService;
   use Grayl\Store\Sale\SalePorter;

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
       * The name of the config file for the Product package
       *
       * @var string
       */
      private string $config_file = 'store.product.php';

      /**
       * The config instance for the Product package
       *
       * @var ConfigController
       */
      private ConfigController $config;

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

         // Create the config instance from the config file
         $this->config = ConfigPorter::getInstance()
                                     ->newConfigControllerFromFile( $this->config_file );

         // Create a KeyedDataBag for storing products
         $this->saved_products = new KeyedDataBag();
      }


      /**
       * Changes the default config file being used
       *
       * @param string $config_file The new config file to use
       *
       * @throws \Exception
       */
      public function setConfigFile ( string $config_file ): void
      {

         // Set the new config file value
         $this->config_file = $config_file;

         // Create the config instance from the config file
         $this->config = ConfigPorter::getInstance()
                                     ->newConfigControllerFromFile( $config_file );
      }


      /**
       * Creates a new ProductController using data from the product ConfigController
       *
       * @param string $sku The unique SKU of the product to load from the config
       *
       * @return ProductController
       * @throws \Exception
       */
      private function newProductControllerFromConfig ( string $sku ): ProductController
      {

         // Make sure the SKU given has a config
         if ( empty( $this->config->getConfig( $sku ) ) ) {
            // Throw an error and exit
            throw new \Exception( 'Product data could not be found in the config.' );
         }

         // Request a new ProductData entity
         $product_data = new ProductData( $this->config->getConfig( $sku )[ 'sku' ],
                                          $this->config->getConfig( $sku )[ 'name' ],
                                          $this->config->getConfig( $sku )[ 'price' ],
                                          $this->config->getConfig( $sku )[ 'tags' ],
                                          $this->config->getConfig( $sku )[ 'settings' ] );

         // Check for ProductDiscounts
         $product_discount = $this->findProductDiscountFromConfig( $sku );

         // Return a ProductController
         return new ProductController( $product_data,
                                       $product_discount,
                                       new ProductService() );
      }


      /**
       * Checks for a ProductDiscount object for a product
       *
       * @param string $sku The unique SKU of the product to load from the config
       *
       * @return ProductDiscount
       * @throws \Exception
       */
      private function findProductDiscountFromConfig ( string $sku ): ?ProductDiscount
      {

         // Make sure the SKU given has a config
         if ( empty( $this->config->getConfig( $sku ) ) ) {
            // Throw an error and exit
            throw new \Exception( 'Product data could not be found in the config.' );
         }

         // Check for ProductDiscounts if a sale is specified
         if ( ! empty( $this->config->getConfig( $sku )[ 'sale' ] ) ) {
            // Grab the SaleController for this SaleID
            $sale = SalePorter::getInstance()
                              ->getSavedSaleController( $this->config->getConfig( $sku )[ 'sale' ] );

            // Return what was found from the tag search
            return $sale->findProductDiscountFromTags( $this->config->getConfig( $sku )[ 'tags' ] );
         }

         // No product discount found
         return null;
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
            $controller = $this->newProductControllerFromConfig( $sku );

            // Save it for re-use
            $this->saved_products->setVariable( $sku,
                                                $controller );
         }

         // Return the controller
         return $controller;
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