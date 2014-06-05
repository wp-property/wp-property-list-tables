<?php
/**
 * List_Tables
 *
 * wp-property-list-tables
 * http://github.com/UsabilityDynamics/wp-property-list-tables
 */
namespace UsabilityDynamics\WPP {

  if( !class_exists( 'UsabilityDynamics\WPP\List_Tables' ) ) {

    class List_Tables {

      /**
       * Module Path.
       *
       * @public
       * @static
       * @property $path
       * @type {string}
       */
      static public $path = null;

      /**
       * Intialize List_Tables.
       *
       * @param $parent
       * @param $module
       *
       */
      public function __construct( $parent = array(), $module = array() ) {

        try {

          // Initialize Module.

        }  catch( \Exception $error ) {
          trigger_error($error->getMesage() );
        }

      }

    }

  }

}