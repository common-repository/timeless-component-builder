<?php 
/**
 * This function will add categories (taxonomy terms) based to the provided 
 * associative array keys
 * 
 */
function tcb_add_categories($tcb_fields){
    
    // collect the Registered term
    $registered_component_type = get_terms( array(
        'taxonomy' => 'component_type',
        'hide_empty' => false,
    ));
    
    // collect all the component type in a index base array
    $custom_component_slugs = array_keys($tcb_fields);
    
    // empty array for pushing all the currently registered slug
    $registered_component_slug = [];
    // collect all the Registered slug in a index base array
    foreach($registered_component_type as $single_registered_tax){
        $registered_component_slug[] = $single_registered_tax->slug ;
    }    
    
    foreach( $custom_component_slugs as $single_slug ){
    
        if( ! array_key_exists( $single_slug , $registered_component_slug ) ){
            // Add taxonomy term if not exist
            wp_insert_term( 
                ucwords(str_replace("_"," ", $single_slug )),
                'component_type',
                ['slug' =>  strtolower($single_slug)]
            );
        }  
    }
  
}