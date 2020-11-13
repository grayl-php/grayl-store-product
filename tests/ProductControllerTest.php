<?php

   namespace Grayl\Test\Store\Product;

   use Grayl\Store\Product\Controller\ProductController;
   use Grayl\Store\Product\ProductPorter;
   use PHPUnit\Framework\TestCase;

   /**
    * Test class for the Product package
    *
    * @package Grayl\Store\Product
    */
   class ProductControllerTest extends TestCase
   {

      /**
       * Tests the creation of ProductController object
       *
       * @return ProductController
       * @throws \Exception
       */
      public function testCreateProductController (): ProductController
      {

         // Create the test object
         $product = ProductPorter::getInstance()
                                 ->getSavedProductController( 'test' );

         // Check the type of object created
         $this->assertInstanceOf( ProductController::class,
                                  $product );

         // Return it
         return $product;
      }


      /**
       * Tests a ProductData entity
       *
       * @param ProductController $product A ProductController entity to use for testing
       *
       * @depends testCreateProductController
       */
      public function testProductDataData ( ProductController $product ): void
      {

         // Perform some checks on the data
         $this->assertEquals( 'test',
                              $product->getProductSKU() );
         $this->assertNotNull( $product->getProductName() );

         // Test price with and without ProductDiscount
         $this->assertEquals( 50.00,
                              $product->getOriginalProductPrice() );
         $this->assertEquals( 45.00,
                              $product->getCurrentProductPrice() );

         // Test setting with and without ProductDiscount
         $this->assertEquals( 'original',
                              $product->getProductSetting( 'original_setting' ) );
         $this->assertEquals( 'overridden',
                              $product->getProductSetting( 'overridden_setting' ) );
      }


      /**
       * Tests a ProductDiscount entity
       *
       * @param ProductController $product A ProductController entity to use for testing
       *
       * @depends testCreateProductController
       */
      public function testProductDiscountData ( ProductController $product ): void
      {

         // Perform some checks on the data
         $this->assertEquals( 10,
                              $product->getProductDiscountAmount() );

         // Test discount settings
         $this->assertEquals( 'overridden',
                              $product->getProductSetting( 'overridden_setting' ) );
      }

   }