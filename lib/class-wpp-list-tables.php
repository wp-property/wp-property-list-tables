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

        wp_register_script( 'wpp-jquery-data-tables', plugins_url( 'static/scripts/jquery.dataTables.min.js', dirname( __DIR__ ) ), array( 'jquery', 'wpp-localization' ) );
        wp_register_style( 'wpp-jquery-data-tables', plugins_url( 'static/styles/wpp.admin.data.tables.css', dirname( __DIR__ ) ) );

        try {

          // Initialize Module.

        }  catch( \Exception $error ) {
          trigger_error($error->getMesage() );
        }

      }

    }

  }

}