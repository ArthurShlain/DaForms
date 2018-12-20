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
[See full example](DaForms/forms/question.php)
