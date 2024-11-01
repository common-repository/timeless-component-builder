<?php 
/**
 * This class will generate html field fields
 * receive argument: $data, $saved_meta_data
 * $data: a field information 
 * example: 
 * [  type: text, field: title, label: User Seen Label as string ]
 * 
 * $saved_meta_data : current saved component meta data 
 */


class TCB_fields{
    public $name;
    public $placeholder;
    public $value;
    public $label;
    public $type;
    public $repeating_fields;
    public $columns;

    // assign the data to the variable
    function __construct($data,$meta_data){ 
        $this->name         = esc_html(isset($data['field']) ? $data['field'] : '');
        $this->placeholder  = esc_html(isset($data['placeholder']) ? $data['placeholder'] : '');
        $this->label        = esc_html(isset($data['label']) ? $data['label'] : '');
        $this->type         = esc_html(isset($data['type']) ? $data['type'] : '');   
        $this->columns      = esc_html(  isset($data['columns']) && !empty($data['columns']) ? $data['columns'] : ''   );
        
        $this->repeating_fields = isset($data['fields']) ? $data['fields'] : [];   

        $this->get_value($meta_data);

    }

    
    public function get_value ($saved_meta_data){  
        // var_dump($saved_meta_data)     ;
            
        ## if have any old metadata saved
        if($saved_meta_data){                
            $saved_meta_data =  $saved_meta_data ; 
            $value = is_array($saved_meta_data) && count($saved_meta_data) > 0 && array_key_exists($this->name,$saved_meta_data )? $saved_meta_data[$this->name]: '';
            $this->value = $value;
        }
    }


    public function render_field( $a_type = false ){       
        $type = $a_type ? $a_type : $this->type;

        if( $this->type == 'textarea' ){ 
            return $this->textarea();
        }

        if( $this->type == 'text' ){
            return $this->text();
        }

        if( $this->type == 'file' ){
            return $this->file_field();
        }

        if( $this->type == 'repeater' ){
            return $this->repeater();
        }


    }

    function repeater(){

        $child_fields = '';        
        $items_currently_have  = is_array($this->value) ? count($this->value) : 1  ;
        $name = $this->name;
        
        
        // print_r($this->value);

        for( $i=0;  $items_currently_have > $i ; $i++ )  {
            $child_fields .= '<div class="single-item-wrapper" data-repeater-item>';
                foreach ($this->repeating_fields as $k => $single_field){
                    
                    // 
                    $c_name = $single_field['field'];

                    // modify the variable before print child component 
                    $single_field['field'] = "{$name}[$i][$c_name]";

                    if( isset($this->value[$i][$c_name]) ) {
                        $single_field['value'] = $this->value[$i][$c_name];
                    }else{
                        $single_field['value'] = "";
                    }

                    if( $single_field['type'] == 'textarea' ){
                        $child_fields .= $this->textarea($single_field);
                    }
            
                    if( $single_field['type'] == 'text' ){
                        $child_fields .= $this->text($single_field);
                    }
                    if( $single_field['type'] == 'file' ){   
                        // var_dump($this->value);
                        $child_fields .= $this->file_field($single_field);
                    }
                    


                }
                $child_fields .='<input class="button item-delete-button" data-repeater-delete type="button" value="Delete"/>';

            $child_fields .= '</div>';

        }

        $html = '
            <div class="repeater-fileds">
                <h3 class="multiple-items-title">'.$this->label.'</h3>
                <div class="multiple-fileds-wrapper" data-repeater-list="'.$this->name.'">
                    '.$child_fields.'            
                </div>

                <div class="add-button-wrapper">
                    <input class="button  button-primary item-add-button" data-repeater-create type="button" value="ADD ITEM"/>               
                </div>
            </div>        
        ';
        return $html;
        
    }


    public function file_field($field_data = []){

        $name       = esc_html(  isset( $field_data['field'] ) ? $field_data['field'] : $this->name );
        $value      = esc_html( isset( $field_data['value'] ) ? $field_data['value'] : $this->value );
        $label      = esc_html(  isset( $field_data['label'] ) ? $field_data['label'] : $this->label );
        $columns    = esc_html(  isset( $field_data['columns'] ) ? $field_data['columns'] : $this->columns );
        

        $html =  '
            <div class="single-field-wrapper col-'.$this->columns .'" ><div>
                <label for="'.$name.'" >'.$label.'</label>     
                <input  type="hidden" name="'.$name.'" value="'.$value.'" id="'.$name.'" />   
        ';

        // if any value present
        if($value){
            $html .= '<p class="selected-file">'.$value.'<button class="reset-file-field" type="button">X</button></p>';
        }else{
            $html .= '<p class="selected-file"></p>';
        }
                

        $html .= '
                <button type="button" class="button media-uplooad-btn">Upload Media</button>
            </div></div>   
        ';


        return $html;

    }

    public function text( $field_data = [] ){

        $name           = esc_html(  isset( $field_data['field'] ) ? $field_data['field'] : $this->name );
        $value          = esc_html(isset( $field_data['value'] ) ? $field_data['value'] : $this->value);
        $placeholder    = esc_html(isset( $field_data['placeholder'] ) ? $field_data['placeholder'] : $this->placeholder);
        $label          = esc_html(isset( $field_data['label'] ) ? $field_data['label'] : $this->label);
        $columns        = esc_html(  isset( $field_data['columns'] ) ? $field_data['columns'] : $this->columns );

        return'
        <div class="single-field-wrapper col-'.$this->columns .'" ><div>
            <label for="'.$name.'" >'.$label.'</label>
            <input type="text" name="'.$name.'" id="'.$name.'" placeholder="'.$placeholder.'" value="'.$value.'" /> 
        </div></div>
        ';
    }


    public function textarea($field_data = []){
        $name           = esc_html(isset( $field_data['field'] ) ? $field_data['field'] : $this->name);
        $value          = esc_html(isset( $field_data['value'] ) ? $field_data['value'] : $this->value);
        $placeholder    = esc_html(isset( $field_data['placeholder'] ) ? $field_data['placeholder'] : $this->placeholder);
        $label          = esc_html(isset( $field_data['label'] ) ? $field_data['label'] : $this->label);
        $columns        = esc_html(isset( $field_data['columns'] ) ? $field_data['columns'] : $this->columns );

        return '
        <div class="single-field-wrapper col-'.$this->columns .'" ><div>
            <label for="'.$name.'" >'.$label.'</label>
            <textarea type="text" id="'.$name.'" name="'.$name.'" placeholder="'.$placeholder.'" >'.$value.'</textarea>
        </div></div>
        ';
    }




}