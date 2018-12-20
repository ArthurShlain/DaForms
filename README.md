# DaForms
PHP Forms Framework

## Using

Loading init file:
<code>require 'da-forms-init.php';</code>

Call form with name "question":
```php 
da_forms::the_form('question');
```

Add client script (jQuery required)

```html
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="inc/da-forms/da-forms.js"></script>
```

### Making new forms

For example, create `question.php` file in `forms` directory and paste this code:
```php
da_forms::load_form(
  array(
    'files' => false,
    'ajax' => true,
    'validation_helper' => false,
    'fields' => array(),
    'messages' => array(),
    'actions' => array()
  )
));
```

- **files** (true/false) - if true, param `enctype="multipart/form-data"` will be added to the `<form>` element.
- **ajax** (true/false) - if true, ajax will be used to submit the form
- **validation_helper** (true/false) - if true, error description text will be added below the fields
- **fields** (array) - array of form fields
- **messages** (array) - array of form messages strings
- **actions** (array) - array of form actions

See [full example](forms/question.php)

### Making validation rules

Validation rule files located in `validation` directory.

Email validation rule example:

```php
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
```

See [available validation rules](validation)
