<?php 
/**
 * This function will call every time when a post save
 * This function is responsible for saving component 
 * meta data to post
 * 
 */
function tcb_save_metabox( $post_id ) {    

    // verify this came from the our screen and with proper authorization.
    if ( isset($_POST['tcb_nonce']) &&  !wp_verify_nonce( $_POST['tcb_nonce'], 'tcb_nonce'.$post_id )) {
        return $post_id;
    }
     
    // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
        return $post_id;
     
    // Check permissions
    if ( !current_user_can( 'edit_post', $post_id ) )
        return $post_id;

    ## Get current Component Type
    $c_cats = get_the_terms($post_id, 'component_type');

    ## get all the user defined fields

    $tcb_fields = tcb_get_registered_component();

    ## define a empty array where we push the data
    $array_to_save = [];

    if( is_array($c_cats) && count($c_cats) > 0 ){

        # Get first component slug/key
        $component_cat = $c_cats[0]->slug;


        # Check if available categories have any field defined
        if(array_key_exists($component_cat, $tcb_fields)){
            // echo "YYY";

            # Get associative component fields from the array
            $c_tcb_fields = $tcb_fields[$component_cat];



            
            foreach($c_tcb_fields as $field):
                $name = $field['field'];

                if( $field['type'] == 'repeater' ){
                    
                    /*----------- IF Repeater -------------*/
                    $itt =  isset($_POST[$name]) && intval( count($_POST[$name])) ? intval( count($_POST[ $name ]) ) : 0;
                    
                    for( $i=0; $i< $itt ; $i++ ){

                        foreach($field['fields'] as $child_field ){
                            $c_name = $child_field['field'] ;
                            // if text area use different sanitization 
                            if( $child_field['type'] == 'textarea' ) {                                
                                $array_to_save[$name][$i][$c_name] =   wp_kses_post($_POST[$name][$i][$c_name]);
                            }else{
                                $array_to_save[$name][$i][$c_name] = sanitize_text_field($_POST[$name][$i][$c_name]);
                            }
                        }                        
                    }

                    /*----------- END Repeater -------------*/


                }else{
                    ## check if the value present in the post array push the data
                    if( array_key_exists( $name, $_POST  )) :
                        
                        // if text area use different sanitization 
                        if( $field['type'] == 'textarea' ) {        
                            $array_to_save[$name] = wp_kses_post($_POST[$name]);
                        }else{                            
                            $array_to_save[$name] = sanitize_text_field($_POST[$name]);
                        }
                    endif;
                }



            endforeach;

            ## save the data to post meta
            update_post_meta( $post_id, 'tcb_component_data', $array_to_save  );

        }

    }

}

add_action( 'save_post', 'tcb_save_metabox' );