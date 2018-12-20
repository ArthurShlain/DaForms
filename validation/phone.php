<?php

/**
 * Validation Rule: Phone
 * @param $value : input value
 * @param $options : validation rule options
 * @param $field : form field options
 * @param $fields : form fields array
 * @return array
 */
function da_form_validation_phone($value, $options, $field, $fields){
    $errors = array();
    $field_name = @$field['name'];
    if(!empty($value)){
        $phone_value = preg_replace('/[^0-9]/', '', $value);
        if(strlen($phone_value) != 11){
            $errors[$field_name] = $field_name . '.invalid';
        }
    }
    return $errors;
}