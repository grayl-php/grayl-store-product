<?php

   namespace Grayl\Store\Product\Service;

   use Grayl\Store\Product\Entity\ProductData;
   use Grayl\Store\Product\Entity\ProductDiscount;

   /**
    * Class ProductService
    * The service for working with Product entities
    *
    * @package Grayl\Store\Product
    */
   class ProductService
   {

      /**
       * Returns the correct value for a product setting factoring in ProductDiscount setting overrides
       *
       * @param string           $key      The key name for the setting
       * @param ProductData      $data     The ProductData entity to check for a setting
       * @param ?ProductDiscount $discount An optional ProductDiscount entity to check for the setting
       *
       * @return mixed
       */
      public function getProductSetting ( string $key,
                                          ProductData $data,
                                          ?ProductDiscount $discount )
      {

         // By default use the override settings from the ProductDiscount if it is set
         if ( ! empty( $discount ) && ! empty( $discount->getOverrideSetting( $key ) ) ) {
            // Return the override setting value from the discount
            return $discount->getOverrideSetting( $key );
         }

         // No override from a discount, return the normal product setting
         return $data->getSetting( $key );
      }


      /**
       * Returns true if a ProductDiscount is set
       *
       * @param ?ProductDiscount $discount The ProductDiscount entity to check
       *
       * @return bool
       */
      public function hasProductDiscount ( ?ProductDiscount $discount ): bool
      {

         // If there is a ProductDiscount set
         if ( ! empty( $discount ) ) {
            // Return true
            return true;
         }

         // No discount
         return false;
      }


      /**
       * Returns the current product price with ProductDiscounts considered
       *
       * @param ProductData      $product_data The ProductData entity to use
       * @param ?ProductDiscount $discount     The ProductDiscount entity to use (if there is one)
       *
       * @return float
       */
      public function getCurrentProductPrice ( ProductData $product_data,
                                               ?ProductDiscount $discount ): float
      {

         // Round down the final price if needed
         if ( ! empty( $discount ) ) {
            // Get the price after ProductDiscount has been applied
            return $this->calculateProductPriceWithProductDiscount( $product_data,
                                                                    $discount );
         }

         // No discount - Return the original price
         return $product_data->getPrice();
      }


      /**
       * Calculates a price after a ProductDiscount is applied
       *
       * @param ProductData     $product_data The ProductData entity to use
       * @param ProductDiscount $discount     The ProductDiscount entity to use
       *
       * @return float
       */
      private function calculateProductPriceWithProductDiscount ( ProductData $product_data,
                                                                  ProductDiscount $discount ): float
      {

         // Return the original price with the discount applied: (Price) - (getDiscountAsDollars)
         $discounted_price = bcsub( $product_data->getPrice(),
                                    $this->getProductDiscountInDollars( $product_data,
                                                                        $discount ),
                                    2 );

         // Round down the final price if needed
         if ( $discount->doRoundDown() ) {
            // Round the price down
            $discounted_price = floor( $discounted_price );
         }

         // Return it
         return $discounted_price;
      }


      /**
       * Returns the discount percentage amount ( i.e. 25 for "25%" )
       *
       * @param ?ProductDiscount $discount The ProductDiscount entity to use
       *
       * @return float
       */
      public function getProductDiscountAmount ( ?ProductDiscount $discount ): float
      {

         // If there is a ProductDiscount set
         if ( ! empty( $discount ) ) {
            // Return the discount
            return $discount->getDiscount();
         }

         // No discount
         return (float) 0;
      }


      /**
       * Gets the dollar value of a ProductDiscount from a price ( i.e. 1.55 )
       *
       * @param ProductData      $product_data The ProductData entity to use
       * @param ?ProductDiscount $discount     The ProductDiscount entity to use
       *
       * @return float
       */
      public function getProductDiscountInDollars ( ProductData $product_data,
                                                    ?ProductDiscount $discount ): float
      {

         // Return the dollar amount of the discount: (Price) * (DiscountAsDecimal)
         if ( ! empty( $discount ) ) {
            return bcmul( $product_data->getPrice(),
                          $this->getProductDiscountAsDecimal( $discount ),
                          2 );
         }

         // No discount
         return 0.00;
      }


      /**
       * Gets the decimal value of a ProductDiscount for use in math functions ( i.e. 0.25 )
       *
       * @param ?ProductDiscount $discount The ProductPrice to work with
       *
       * @return float
       */
      private function getProductDiscountAsDecimal ( ?ProductDiscount $discount ): float
      {

         // Return the float discount converted into a decimal percentage of 100: (Discount) / 100
         if ( ! empty( $discount ) ) {
            return bcdiv( $this->getProductDiscountAmount( $discount ),
                          100,
                          4 );
         }

         // No discount
         return 0;
      }

   }