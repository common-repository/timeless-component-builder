<?php
/**
 * Plugin Name: Timeless Component Builder
 * Description: Build reusable Component in minimum amount of time.
 * Author: coder618
 * Author URI: https://coder618.github.io
 * Version: 1.1.0
 * Text Domain : tcb
*/
class TCB_Main{

    public function __construct() {
        $this->reg_hooks();
        $this->load_dependencies();
        
    }

    /* Register Custom Post Type */
    public function register_CPT() {
        register_post_type( 'tcb_component',
            array(
                'labels' => array(
                    'name' => __( 'Components', 'tcb' ),
                    'singular_name' => __( 'Component', 'tcb' )
                ),
                'public' => true,
                'has_archive' => false,
                'publicly_queryable' =>false,
                'show_in_rest' => false,
                'supports' => [ 'title','thumbnail' ],
            )
        );
    }


    ## Register Taxonomy
    public function register_taxonomies() {

        // Add new taxonomy, make it hierarchical (like categories)
        $labels = array(
            'name'              => _x( 'Component Type', 'taxonomy general name', 'tcb' ),
            'singular_name'     => _x( 'Component Type', 'taxonomy singular name', 'tcb' ),
            'search_items'      => __( 'Search Component Type', 'tcb' ),
            'all_items'         => __( 'All Types', 'tcb' ),            
            'add_new_item'      => __( 'Add New Component Type', 'tcb' ),
            'new_item_name'     => __( 'New Component Type Name', 'tcb' ),
            'menu_name'         => __( 'Component Type', 'tcb' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => false,
            // 'rewrite'           => array( 'slug' => 'genre' ),
        );
        register_taxonomy( 'component_type', ['tcb_component'],  $args );
    }


    private function load_dependencies() {
		
        require plugin_dir_path( __FILE__ ) .  'inc/user_function.php';
        require plugin_dir_path( __FILE__ ) .  'inc/class-fields.php';
        require plugin_dir_path( __FILE__ ) .  'inc/add_categories.php';
        require plugin_dir_path( __FILE__ ) .  'inc/add_metabox.php';
        require plugin_dir_path( __FILE__ ) .  'inc/generate-metabox.php';
        require plugin_dir_path( __FILE__ ) .  'inc/save_metabox.php';
        require plugin_dir_path( __FILE__ ) .  'inc/register_shortcode.php';

    }


    
    /**
     * Some Hook of this plugin will register/call within this method
     * 
     */
    private function reg_hooks(){
        // add cpt in hook
        add_action( 'init', [ $this, 'register_CPT' ], 1 );
        
        // add taxonomies hook
        add_action( 'init', [ $this, 'register_taxonomies' ], 2 );        

        // Enqueue hook
        add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );               
        
    }
    /**
     * Enqueue all Necessary assets
     * 
     */
    public function enqueue_assets() {
        wp_enqueue_style( 'tcb_component_style', plugin_dir_url( __FILE__ ). 'dist/tcb-style.css', [], 1, 'all' );
        wp_enqueue_script( 'tcb_component_script', plugin_dir_url( __FILE__ ) . 'dist/tcb-script.js', array(), '1.0' );
        wp_enqueue_script( 'repeater-js', plugin_dir_url( __FILE__ ) . 'dist/repeater.js', array('jquery'), '1.0' );
    }
    
}
new TCB_Main();


