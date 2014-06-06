<?php
/**
 * Name: List Tables
 * Class: UsabilityDynamics\WPP\List_Tables
 * Version: 0.1.0
 * Description: Admin list.
 * Slug: wp-property-list-tables
 * URL: http://github.com/UsabilityDynamics/wp-property-list-tables
 *
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
       *
       * @todo Convert "wpp_ajax_list_table" action to be RESTful.
       *
       * @param array $parent
       * @param array $module
       *
       * @throws \Exception
       */
      public function __construct( $parent = array(), $module = array() ) {

        // Validate Module.
        $this->autoload();

        // Enable Module.
        add_action( 'wp_loaded', array( $this, 'wp_loaded' ) );
        add_action( 'admin_init', array( $this, 'admin_init' ) );

      }

      /**
       * @throws \Exception
       */
      public function autoload() {

        if( !file_exists( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' ) ) {
          throw new \Exception( __( 'Unable to determine location of class-wp-list-table.php' ) );
        }

      }

      /**
       *
       */
      public function admin_init() {

        // Admin AJAX API.
        add_action( 'wp_ajax_wpp_ajax_list_table', array( $this, 'ajax_search' ) );

        // Expose Table Classes.
        require_once( 'class-wpp-list-tables-data-table.php' );
        require_once( 'class-wpp-list-tables-property-table.php' );

      }
      public function wp_loaded() {


        wp_register_script( 'wpp-jquery-data-tables', plugins_url( 'static/scripts/jquery.dataTables.min.js', dirname( __DIR__ ) ), array( 'jquery', 'wpp-localization' ) );
        wp_register_style( 'wpp-jquery-data-tables',  plugins_url( 'static/styles/wpp.admin.data.tables.css', dirname( __DIR__ ) ) );


      }


      /**
       * Function for displaying WPP Data Table rows
       *
       * @todo Add output buffer wiping.
       *
       * @since 1.0.0
       */
      public function ajax_search() {
        global $current_screen, $wp_registered_widgets, $wp_registered_widget_controls, $wp_registered_widget_updates, $_wp_deprecated_widgets_callbacks;

        //** Get the paramters we care about */
        $sEcho         = isset( $_REQUEST[ 'sEcho' ] ) ? $_REQUEST[ 'sEcho' ] : null;
        $per_page      = isset( $_REQUEST[ 'iDisplayLength' ] ) ? $_REQUEST[ 'iDisplayLength' ] : null;
        $iDisplayStart = isset( $_REQUEST[ 'iDisplayStart' ] ) ? $_REQUEST[ 'iDisplayStart' ] : null;
        $iColumns      = isset( $_REQUEST[ 'iColumns' ] ) ? $_REQUEST[ 'iColumns' ] : null;
        $sColumns      = isset( $_REQUEST[ 'sColumns' ] ) ? $_REQUEST[ 'sColumns' ] : null;
        $order_by      = isset( $_REQUEST[ 'iSortCol_0' ] ) ? $_REQUEST[ 'iSortCol_0' ] : null;
        $sort_dir      = isset( $_REQUEST[ 'sSortDir_0' ] ) ? $_REQUEST[ 'sSortDir_0' ] : null;

        //$current_screen = $wpi_settings['pages']['main'];

        //** Parse the serialized filters array */
        parse_str( isset( $_REQUEST[ 'wpp_filter_vars' ] ) ? $_REQUEST[ 'wpp_filter_vars' ] : '', $wpp_filter_vars );

        $wpp_search = isset( $wpp_filter_vars[ 'wpp_search' ] ) ? $wpp_filter_vars[ 'wpp_search' ] : array();

        $sColumns = explode( ",", $sColumns );

        //* Init table object */
        $wp_list_table = new \UsabilityDynamics\WPP\List_Tables\Property_Table( array(
          "ajax"           => true,
          "per_page"       => $per_page,
          "iDisplayStart"  => $iDisplayStart,
          "iColumns"       => $iColumns,
          "current_screen" => 'property_page_all_properties'
        ));

        if( isset( $sColumns[ $order_by ] ) && in_array( $sColumns[ $order_by ], $wp_list_table->get_sortable_columns() ) ) {
          $wpp_search[ 'sorting' ] = array(
            'order_by' => $sColumns[ $order_by ],
            'sort_dir' => $sort_dir
          );
        }

        $wp_list_table->prepare_items( $wpp_search );

        if( $wp_list_table->has_items() ) {
          foreach( $wp_list_table->items as $count => $item ) {
            $data[ ] = $wp_list_table->single_row( $item );
          }
        } else {
          $data[ ] = $wp_list_table->no_items();
        }

        wp_send_json( array(
          'ok'                    => true,
          'sEcho'                 => $sEcho,
          'iTotalRecords'         => count( $wp_list_table->all_items ),
          'iTotalDisplayRecords'  => count( $wp_list_table->all_items ),
          'aaData'                => $data
        ));

      }

    }


  }

}