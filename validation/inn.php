<?php

/**
 * Validation Rule: INN
 * @param $value : input value
 * @param $options : validation rule options
 * @param $field : form field options
 * @param $fields : form fields array
 * @return array
 */
function da_form_validation_inn($value, $options, $field, $fields)
{
    $errors = array();
    $field_name = @$field['name'];
    $valid = true;

    $value = trim($value);

    if (!empty($value)) {
        if (preg_match('/\D/', $value)) {
            $valid = false;
            // ИНН может состоять только из цифр
        } else {
            $inn = (string)$value;
            $len = strlen($inn);

            if ($len === 10) {
                $valid = $inn[9] === (string)(((
                                2 * $inn[0] + 4 * $inn[1] + 10 * $inn[2] +
                                3 * $inn[3] + 5 * $inn[4] + 9 * $inn[5] +
                                4 * $inn[6] + 6 * $inn[7] + 8 * $inn[8]
                            ) % 11) % 10);
            } elseif ($len === 12) {
                $num10 = (string)(((
                            7 * $inn[0] + 2 * $inn[1] + 4 * $inn[2] +
                            10 * $inn[3] + 3 * $inn[4] + 5 * $inn[5] +
                            9 * $inn[6] + 4 * $inn[7] + 6 * $inn[8] +
                            8 * $inn[9]
                        ) % 11) % 10);

                $num11 = (string)(((
                            3 * $inn[0] + 7 * $inn[1] + 2 * $inn[2] +
                            4 * $inn[3] + 10 * $inn[4] + 3 * $inn[5] +
                            5 * $inn[6] + 9 * $inn[7] + 4 * $inn[8] +
                            6 * $inn[9] + 8 * $inn[10]
                        ) % 11) % 10);

                $valid = $inn[11] === $num11 && $inn[10] === $num10;
            } else {
                $valid = false;
                // ИНН может состоять только из 10 или 12 цифр
            }
        }
    }

    if (!$valid) {
        $errors[$field_name] = $field_name . '.invalid';
    }
    return $errors;
}