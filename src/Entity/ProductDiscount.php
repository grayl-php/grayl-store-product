<?php

   namespace Grayl\Store\Product\Entity;

   use Grayl\Mixin\Common\Entity\KeyedDataBag;

   /**
    * Class ProductDiscount
    * The entity for a product's price discount
    *
    * @package Grayl\Store\Product
    */
   class ProductDiscount
   {

      /**
       * The percentage of the discount (i.e. "25.00" for 25% off)
       *
       * @var float
       */
      private float $discount;

      /**
       * Round down product prices after applying discount? (i.e. 149.25 become 149.00)
       *
       * @var bool
       */
      private bool $round_down;

      /**
       * Miscellaneous override product settings (these will override any original product settings)
       *
       * @var KeyedDataBag
       */
      private KeyedDataBag $override_settings;


      /**
       * The class constructor
       *
       * @param float $discount          The percentage discount (i.e. "25.00" for 25% off)
       * @param bool  $round_down        Round down product prices after applying discount? (149.25 become 149.00)
       * @param array $override_settings Miscellaneous product override settings
       */
      public function __construct ( float $discount,
                                    bool $round_down,
                                    array $override_settings )
      {

         // Create the bags
         $this->override_settings = new KeyedDataBag();

         // Set the class data
         $this->setDiscount( $discount );
         $this->setRounddown( $round_down );
         $this->setOverrideSettings( $override_settings );
      }


      /**
       * Get the current discount as a float
       *
       * @return float
       */
      public function getDiscount (): float
      {

         // Return the discount
         return $this->discount;
      }


      /**
       * Set the current discount as a float
       *
       * @param float $discount The percentage discount (i.e. "25.00" for 25% off)
       */
      public function setDiscount ( float $discount ): void
      {

         // Set the discount
         $this->discount = $discount;
      }


      /**
       * Gets the value for rounding down final prices (149.25 become 149.00)
       *
       * @return bool
       */
      public function doRoundDown (): bool
      {

         // Return the value
         return $this->round_down;
      }


      /**
       * Sets the value for round down
       *
       * @param bool $round_down Whether to round down prices or not (149.25 become 149.00)
       */
      public function setRoundDown ( bool $round_down ): void
      {

         // Set the value
         $this->round_down = $round_down;
      }


      /**
       * Retrieves the value of product override setting (these override main product settings)
       *
       * @param string $key The key name for the override setting
       *
       * @return mixed
       */
      public function getOverrideSetting ( string $key )
      {

         // Return the value
         return $this->override_settings->getVariable( $key );
      }


      /**
       * Retrieves the entire array of product override settings (these override main product settings)
       *
       * @return array
       */
      public function getOverrideSettings (): array
      {

         // Return all override settings
         return $this->override_settings->getVariables();
      }


      /**
       * Sets a single product override setting (these override main product settings)
       *
       * @param string $key   The key name for the override setting
       * @param mixed  $value The value of the override setting
       */
      public function setOverrideSetting ( string $key,
                                           $value ): void
      {

         // Set the override setting
         $this->override_settings->setVariable( $key,
                                                $value );
      }


      /**
       * Sets multiple product override settings using a passed array (these override main product settings)
       *
       * @param array $override_settings The associative array of product override settings to set ( key => value )
       */
      public function setOverrideSettings ( array $override_settings ): void
      {

         // Set the override settings
         $this->override_settings->setVariables( $override_settings );
      }

   }