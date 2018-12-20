<?php

/**
 * Validation Rule: Email
 * @param $value : input value
 * @param $options : validation rule options
 * @param $field : form field options
 * @param $fields : form fields array
 * @return array
 */
function da_form_validation_honeypot($value, $options, $field, $fields){
    $errors = array();
    $field_name = @$field['name'];
    if(!empty($value)){
        $errors[$field_name] = 'something went wrong';
    }
    return $errors;
}