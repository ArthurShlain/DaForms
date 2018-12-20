<?php

/**
 * Validation Rule: Email
 * @param $value : input value
 * @param $options : validation rule options
 * @param $field : form field options
 * @param $fields : form fields array
 * @return array
 */
function da_form_validation_email($value, $options, $field, $fields){
    $errors = array();
    $field_name = @$field['name'];
    if(!empty($value)){
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$field_name] = $field_name . '.invalid';
        }
    }
    return $errors;
}