<?php

/**
 * Validation Rule: Required
 * @param $value : input value
 * @param $options : validation rule options
 * @param $field : form field options
 * @param $fields : form fields array
 * @return array
 */
function da_form_validation_required($value, $options, $field, $fields){
    $errors = array();
    $value = trim($value);
    $field_name = @$field['name'];
    if(@$field['type'] == 'file'){
        // https://gist.github.com/ebidel/2410898
        $file_name = $_FILES[$field['name']]['name'];
        if(empty($file_name)){
            $errors[$field_name] = $field_name . '.required';
        }
        return $errors;
    }
    if(empty($value)){
        $errors[$field_name] = $field_name . '.required';
    }
    return $errors;
}