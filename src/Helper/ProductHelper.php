<?php

   namespace Grayl\Store\Product\Helper;

   use Grayl\Mixin\Common\Traits\StaticTrait;

   /**
    * A package of miscellaneous top-level functions for working with Products
    * These are kept isolated to maintain separation between the main library and specific user functionality
    *
    * @package Grayl\Store\Product
    */
   class ProductHelper
   {

      // Use the static instance trait
      use StaticTrait;

      /**
       * Formats a float to dollar format ( 0.00 )
       *
       * @param float $number The number to format
       *
       * @return float
       */
      public function formatNumberAsDollars ( float $number ): float
      {

         // Return the formatted number
         return sprintf( '%0.2f',
                         $number );
      }

   }