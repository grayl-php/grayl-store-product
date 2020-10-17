<?php

   namespace Grayl\Store\Product\Controller;

   use Grayl\Store\Product\Entity\ProductData;
   use Grayl\Store\Product\Entity\ProductDiscount;
   use Grayl\Store\Product\Service\ProductService;

   /**
    * Class ProductController
    * The controller for working with product entities
    *
    * @package Grayl\Store\Product
    */
   class ProductController
   {

      /**
       * The ProductData instance to work with
       *
       * @var ProductData
       */
      private ProductData $product_data;

      /**
       * An optional ProductDiscount entity if applicable
       *
       * @var ?ProductDiscount
       */
      private ?ProductDiscount $product_discount;

      /**
       * The ProductService instance to interact with
       *
       * @var ProductService
       */
      private ProductService $product_service;


      /**
       * The class constructor
       *
       * @param ProductData      $product_data     The ProductData instance to work with
       * @param ?ProductDiscount $product_discount An optional ProductDiscount entity if applicable
       * @param ProductService   $product_service  The ProductService instance to use
       */
      public function __construct ( ProductData $product_data,
                                    ?ProductDiscount $product_discount,
                                    ProductService $product_service )
      {

         // Set the class data
         $this->product_data     = $product_data;
         $this->product_discount = $product_discount;

         // Set the service entity
         $this->product_service = $product_service;
      }


      /**
       * Get the product SKU
       *
       * @return string
       */
      public function getProductSKU (): string
      {

         // Return the SKU
         return $this->product_data->getSKU();
      }


      /**
       * Get the product name
       *
       * @return string
       */
      public function getProductName (): string
      {

         // Return the name
         return $this->product_data->getName();
      }


      /**
       * Returns the original price of a product before any discounts
       *
       * @return float
       */
      public function getOriginalProductPrice (): float
      {

         // Return the original price
         return $this->product_data->getPrice();
      }


      /**
       * Returns the current product price with ProductDiscounts considered
       *
       * @return float
       */
      public function getCurrentProductPrice (): float
      {

         // Return the current price
         return $this->product_service->getCurrentProductPrice( $this->product_data,
                                                                $this->product_discount );
      }


      /**
       * Returns the correct value for a product setting factoring in ProductDiscount setting overrides
       *
       * @param string $key The key name for the setting
       *
       * @return mixed
       */
      public function getProductSetting ( string $key )
      {

         // Use the service to determine where to pull the setting from
         return $this->product_service->getProductSetting( $key,
                                                           $this->product_data,
                                                           $this->product_discount );
      }


      /**
       * Returns true if a ProductController has a ProductDiscount set
       *
       * @return bool
       */
      public function hasProductDiscount (): bool
      {

         // Use the service to check
         return $this->product_service->hasProductDiscount( $this->product_discount );
      }


      /**
       * Returns the discount percentage amount ( i.e. 25 for "25%" )
       *
       * @return float
       */
      public function getProductDiscountAmount (): float
      {

         // Return the discount amount
         return $this->product_service->getProductDiscountAmount( $this->product_discount );
      }


      /**
       * Gets the dollar value of a ProductDiscount from a price ( i.e. 1.55 )
       *
       * @return float
       */
      public function getProductDiscountInDollars (): float
      {

         // Return the discount as dollars
         return $this->product_service->getProductDiscountInDollars( $this->product_data,
                                                                     $this->product_discount );
      }

   }