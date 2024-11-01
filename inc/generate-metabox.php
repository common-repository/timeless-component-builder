<?php 
/**
 * This function will call the TCB_fields class and generate the HTML output
 * 
 */


function tcb_generate_fields( $post ) {
    ## get all the user defined fields
    $tcb_fields = tcb_get_registered_component();

    # get current post id
    $post_id = $post->ID;

    ## Get current Component Type categories
    $c_cats = get_the_terms($post_id, 'component_type');

    ## Check if current component have any component_type selected
    if( is_array($c_cats) && count($c_cats) > 0 ){

        # Get first component slug/key
        $component_cat = $c_cats[0]->slug;

        # Check if available categories have any field defined
        if(array_key_exists($component_cat, $tcb_fields)){

            $saved_meta_data = get_post_meta( $post_id  ,'tcb_component_data', true );
            
            # Get assigned component fields from the array
            $c_tcb_fields = $tcb_fields[$component_cat];

            ## Add nonce field
            echo '<input type="hidden" name="tcb_nonce" id="tcb_nonce" value="'.wp_create_nonce( 'tcb_nonce'.$post->ID ).'" />';

            ## generate meta box based on assigned field and 
            echo '<div class="tcb_fields_wrapper">';
            foreach($c_tcb_fields as $field):                
                $fields_class = new TCB_fields($field, $saved_meta_data);
                echo $fields_class->render_field();
            endforeach;
            echo "</div>";
        }

    }

}

/**
 * This function will generate shortcode
 *  which user can copy and past
 *  it where they want to use the component * 
 */

function tcb_generate_shortcode(){
    global  $post ;
    $cat_attr = "";
    $cats = get_the_terms( $post->ID, 'component_type' );
    
    if( is_array( $cats ) && count($cats) > 0 ){
        $cat_name = $cats[0]->slug;
        $cat_attr = 'cat="'.$cat_name.'"';
    }

    echo '
        <h4> Shortcode: <pre>[tcb_component id="'.$post->ID.'" '.$cat_attr.' ]</pre> </h4> 
    ';

}