<?php

/**
 * DaForms PHP Forms Framework
 * @author    Arthur Shlain
 * @note      This program is distributed in the hope that it will be useful - WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE.
 */

require_once 'vendor/autoload.php';

ini_set('display_errors', 'On');

if ('' == session_id()) {
    session_start();
}

/**
 * DaForms Class
 */
class da_forms
{

    /**
     * DaForms initialization flag
     * @var bool
     */
    public static $initialized = false;

    /**
     * DaForms options array
     * @var bool
     */
    public static $options = array();

    /**
     * DaForms handler filename
     * @var string
     */
    public static $handler = 'da-forms-handler.php';

    /**
     * DaForms Array
     * Contains all forms settings with fields, messages and actions
     * @var array
     */
    public static $forms = array();

    /**
     * DaForms default values
     * @var array
     */
    public static $defaults = array(
        'field' => array(
            'template' => array(
                'default' => '%start% %label% %input% %errors% %end%',
                'select' => '%start% %label% %input% %errors% %end%',
                'checkbox' => '%start% %input% %label% %errors% %end%',
                'radio' => '%start% %input% %label% %errors% %end%',
                'submit' => '%start% %input% %errors% %end%'
            )
        ),
        'wrapper' => array(
            'tag' => array(
                'default' => 'div',
            ),
            'class' => array(
                'default' => 'form-group',
                'select' => 'form-group',
                'checkbox' => 'form-group custom-control custom-checkbox',
                'radio' => 'form-group custom-control custom-radio',
                'submit' => 'form-group',
            )
        ),
        'label' => array(
            'class' => array(
                'default' => '',
                'select' => '',
                'checkbox' => 'custom-control-label',
                'radio' => 'custom-control-label',
                'submit' => ''
            )
        ),
        'control' => array(
            'tag' => array(
                'default' => 'input',
                'select' => 'select',
                'textarea' => 'textarea',
                'submit' => 'button'
            ),
            'close_tag' => array(
                'input' => false,
                'textarea' => true,
                'button' => true,
                'select' => true,
            ),
            'class' => array(
                'default' => 'form-control',
                'select' => 'form-control',
                'checkbox' => 'custom-control-input',
                'radio' => 'custom-control-input',
                'submit' => 'btn btn-primary'
            ),
            'type' => array(
                'default' => 'text',
                'select' => '-',
                'password' => 'password',
                'submit' => 'submit',
                'reset' => 'reset',
                'radio' => 'radio',
                'checkbox' => 'checkbox',
                'button' => 'button',
                'color' => 'color',
                'date' => 'date',
                'datetime-local' => 'datetime-local',
                'email' => 'email',
                'month' => 'month',
                'number' => 'number',
                'range' => 'range',
                'search' => 'search',
                'tel' => 'tel',
                'time' => 'time',
                'url' => 'url',
                'week' => 'week',
            )
        )
    );

    /**
     * Get default value
     * @param $control_type
     * @param $property
     * @param $element
     * @param string $default
     * @return mixed
     */
    public static function get_defaults($control_type, $property, $element, $default = '')
    {
        return @$default[$element][$property][$control_type];
    }

    /**
     * Get site URL
     * @return string
     */
    public static function siteURL()
    {
        $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ||
            $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $domainName = $_SERVER['HTTP_HOST'];
        return $protocol . $domainName;
    }

    /**
     * Get DaForms directory
     * @return string
     */
    public static function get_da_forms_dir()
    {
        $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') ? 'https://' : 'http://';
        $url .= $_SERVER['HTTP_HOST'] . '/';
        $path = str_replace('\\', '/', dirname(__FILE__));
        return $url . str_replace($_SERVER['DOCUMENT_ROOT'], '', $path);
    }

    /**
     * Make HTML attributes (key="value") space-separated string from array
     * @param array $attributes
     * @return string
     */
    public static function array_to_attributes($attributes = array())
    {
        return join(' ', array_map(function ($key) use ($attributes) {
            if (is_bool($attributes[$key])) {
                return $attributes[$key] ? $key : '';
            }
            return $key . '="' . $attributes[$key] . '"';
        }, array_keys($attributes)));
    }

    /**
     * Add default form options if not exist.
     * @param $form_options
     * @return mixed
     */
    public static function get_form_defaults($form_options)
    {
        if (empty($form_options['name'])) {
            $form_options['name'] = self::get_new_form_name();
        }
        if (empty($form['method'])) {
            $form_options['method'] = 'post';
        }
        return $form_options;
    }

    /**
     * Add default control options if not exist.
     * @param $form
     * @param $field
     * @return mixed
     */
    public static function get_control_defaults($form, $field)
    {
        // Field Template
        $default['field_template'] = @self::$defaults['field']['template'][@$field['type']];
        if (empty($default['field_template'])) {
            $default['field_template'] = @self::$defaults['field']['template']['default'];
        }
        if (empty($field['field_template'])) {
            $field['field_template'] = $default['field_template'];
        }

        // Wrapper Tag
        $default['wrapper_tag'] = @self::$defaults['wrapper']['tag'][@$field['type']];
        if (empty($default['wrapper_tag'])) {
            $default['wrapper_tag'] = @self::$defaults['wrapper']['tag']['default'];
        }
        if (empty($field['wrapper_tag'])) {
            $field['wrapper_tag'] = $default['wrapper_tag'];
        }

        // Wrapper Class
        $default['wrapper_class'] = @self::$defaults['wrapper']['class'][@$field['type']];
        if (empty($default['wrapper_class'])) {
            $default['wrapper_class'] = @self::$defaults['wrapper']['class']['default'];
        }
        if (empty($field['wrapper_class'])) {
            $field['wrapper_class'] = $default['wrapper_class'];
        }

        // Label Class
        $default['label_class'] = @self::$defaults['label']['class'][@$field['type']];
        if (empty($default['label_class'])) {
            $default['label_class'] = @self::$defaults['label']['class']['default'];
        }
        if (empty($field['label_class'])) {
            $field['label_class'] = $default['label_class'];
        }

        // Control Tag
        $default['tag'] = @self::$defaults['control']['tag'][@$field['type']];
        if (empty($default['tag'])) {
            $default['tag'] = @self::$defaults['control']['tag']['default'];
        }
        if (empty($field['tag'])) {
            $field['tag'] = $default['tag'];
        }

        // Control Close Tag
        $default['close_tag'] = @self::$defaults['control']['close_tag'][@$field['tag']];
        if (empty($default['close_tag'])) {
            $default['close_tag'] = @self::$defaults['control']['close_tag']['default'];
        }
        if (empty($field['close_tag'])) {
            $field['close_tag'] = $default['close_tag'];
        }

        // Control Class
        $default['class'] = @self::$defaults['control']['class'][@$field['type']];
        if (empty($default['class'])) {
            $default['class'] = @self::$defaults['control']['class']['default'];
        }
        if (empty($field['class'])) {
            $field['class'] = $default['class'];
        }

        // Control Type
        $default['type'] = @self::$defaults['control']['type'][@$field['type']];
        if (empty($default['type'])) {
            $default['type'] = @self::$defaults['control']['type']['default'];
        }
        if (empty($field['type'])) {
            $field['type'] = $default['type'];
        }
        if ($default['type'] == '-') {
            unset($default['type']);
        }

        // Control ID
        if (empty($field['id'])) {
            $field['id'] = 'form_' . @$form['name'] . '_' . @$field['name'];
        }

        return $field;
    }

    /**
     * Get field HTML
     * @param $form
     * @param $field
     * @return string
     */
    public static function get_field($form, $field)
    {
        if (@$field['type'] == 'checkbox') {
            return self::get_checkbox($form, $field);
        }
        if (@$field['type'] == 'radio') {
            return self::get_radio($form, $field);
        }
        if (@$field['type'] == 'select') {
            return self::get_select($form, $field);
        }
        if (@$field['type'] == 'submit') {
            return self::get_submit($form, $field);
        }
        if (@$field['type'] == 'html') {
            return self::get_html($field);
        }
        if (@$field['type'] == 'success_html') {
            return self::get_success_html($form, $field);
        }
        if (@$field['type'] == 'validation_errors') {
            return self::get_validation_errors($form, $field);
        }
        if (@$field['type'] == 'form_errors') {
            return self::get_form_errors($form, $field);
        }
        return self::get_input($form, $field);
    }

    public static function get_field_template_parts($options)
    {
        $parts = array();
        foreach ($options['field'] as $option_name => $option_value) {
            if (!empty(strstr($option_name, ':'))) {
                $options['input_attributes'][trim($option_name, ':')] = $option_value;
            }
        }
        $parts['start'] = '<' . $options['wrapper_tag'] . ' ' . self::array_to_attributes($options['wrapper_attributes']) . '>';
        $parts['end'] = '</' . $options['wrapper_tag'] . '>';
        $parts['label_html'] = @$options['label_html'];
        $parts['label_start'] = !empty($parts['label_html']) ? '<label ' . self::array_to_attributes($options['label_attributes']) . '>' : '';
        $parts['label_end'] = !empty($parts['label_html']) ? '</label>' : '';
        $parts['label'] = $parts['label_start'] . $parts['label_html'] . $parts['label_end'];
        $parts['input_start'] = '<' . $options['input_tag'] . ' ' . self::array_to_attributes($options['input_attributes']) . '>';
        $parts['input_html'] = @$options['input_html'];
        $parts['input_end'] = @$options['field']['close_tag'] ? '</' . $options['input_tag'] . '>' : '';
        $parts['input'] = $parts['input_start'] . $parts['input_html'] . $parts['input_end'];
        $parts['errors'] = self::get_error_helper($options['form_name'], $options['field']);
        foreach ($options['field'] as $option_name => $option_value) {
            if (!empty(strstr($option_name, '@'))) {
                $parts[$option_name] = $option_value;
            }
        }
        return $parts;
    }

    /**
     * Get input HTML
     * @param $form
     * @param $field
     * @return string
     */
    public static function get_input($form, $field)
    {
        $field = self::get_control_defaults($form, $field);
        $form_data = @$_SESSION['da_forms_data'][$form['name']];
        $value = @$form_data['values'][@$field['name']];
        if (empty($value)) {
            $value = @$field['value'];
        }
        $wrapper_class = 'da-form-group' . ' ' . @$field['wrapper_class'];
        $input_class = @$field['class'];
        if (!empty($form_data['errors'][@$field['name']])) {
            $wrapper_class .= ' has-error';
            $input_class .= ' is-invalid';
        }
        $options = array(
            'form_name' => $form['name'],
            'field' => $field,
            'field_template' => @$field['field_template'],
            'wrapper_tag' => @$field['wrapper_tag'],
            'wrapper_attributes' => array(
                'class' => $wrapper_class,
            ),
            'input_tag' => @$field['tag'],
            'input_html' => @$field['close_tag'] ? $value : '',
            'input_value' => $value,
            'input_values' => @$field['values'],
            'input_attributes' => array(
                'class' => $input_class,
                'type' => @$field['type'],
                'name' => @$field['name'],
                'id' => @$field['id'],
                'placeholder' => @$field['placeholder'],
                'value' => @$field['close_tag'] ? '' : $value,
            ),
            'label_html' => @$field['label_html'],
            'label_attributes' => array(
                'class' => @$field['label_class'],
                'for' => @$field['id'],
            )
        );
        $parts = da_forms::get_field_template_parts($options);
        return da_forms::compile_string($options['field_template'], $parts);
    }

    /**
     * Get checkbox HTML
     * @param $form
     * @param $field
     * @return string
     */
    public static function get_checkbox($form, $field)
    {
        $field = self::get_control_defaults($form, $field);
        $template = @$field['field_template'];
        $form_data = @$_SESSION['da_forms_data'][$form['name']];
        $checked = !empty(@$field['checked']) ? 'checked' : '';
        $checked = !empty(@$form_data['values'][@$field['name']]) ? 'checked' : $checked;
        $wrapper_tag = @$field['wrapper_tag'];
        $wrapper_attributes = array();
        $wrapper_attributes['class'] = @$field['wrapper_class'];
        $label_attributes = array();
        $label_attributes['class'] = @$field['label_class'];
        $label_attributes['for'] = @$form['name'] . '_' . @$form['instance'] . '_' . @$field['id'];
        $input_tag = @$field['tag'];
        $input_attributes = array();
        $input_attributes['class'] = @$field['class'];
        $input_attributes['type'] = 'checkbox';
        $input_attributes['name'] = @$field['name'];
        $input_attributes['value'] = @$field['value'];
        $input_attributes['id'] = @$form['name'] . '_' . @$form['instance'] . '_' . @$field['id'];
        $input_attributes['checked'] = $checked;
        if (empty($input_attributes['checked'])) {
            unset($input_attributes['checked']);
        }
        if (!empty($form_data['errors'][@$field['name']])) {
            $wrapper_attributes['class'] .= ' has-error';
            $input_attributes['class'] .= ' is-invalid';
        }
        $parts = array();
        $parts['start'] = '<' . $wrapper_tag . ' ' . da_forms::array_to_attributes($wrapper_attributes) . '>';
        $parts['end'] = '</' . $wrapper_tag . '>';
        $parts['label_start'] = '<label ' . da_forms::array_to_attributes($label_attributes) . '>';
        $parts['label_html'] = @$field['label_html'];
        $parts['label_end'] = '</label>';
        $parts['label'] = $parts['label_start'] . $parts['label_html'] . $parts['label_end'];
        $parts['input_start'] = '<' . $input_tag . ' ' . self::array_to_attributes($input_attributes) . '>';
        $parts['input_html'] = '';
        $parts['input_end'] = '';
        $parts['input'] = $parts['input_start'] . $parts['input_html'] . $parts['input_end'];
        $parts['errors'] = self::get_error_helper($form['name'], $field);
        foreach ($field as $option_name => $option_value) {
            if (!empty(strstr($option_name, '@'))) {
                $parts[$option_name] = $option_value;
            }
        }
        return da_forms::compile_string($template, $parts);
    }

    /**
     * Get radio HTML
     * @param $form
     * @param $field
     * @return string
     */
    public static function get_radio($form, $field)
    {
        $field = self::get_control_defaults($form, $field);
        $template = @$field['field_template'];
        $form_data = @$_SESSION['da_forms_data'][$form['name']];
        $wrapper_tag = @$field['wrapper_tag'];
        $wrapper_attributes = array();
        $wrapper_attributes['class'] = @$field['wrapper_class'];
        $input_tag = @$field['tag'];
        $input_attributes = array();
        $input_attributes['class'] = @$field['class'];
        $input_attributes['type'] = 'radio';
        $input_attributes['name'] = @$field['name'];
        $label_attributes = array();
        $label_attributes['class'] = @$field['label_class'];
        $radio_values = @$field['radio_values'];
        $selected_value = @$field['selected_value'];
        $html = '';
        $i = 0;
        foreach ($radio_values as $radio_value => $radio_text) {
            $i++;
            $radio_id = @$form['name'] . '_' . @$form['instance'] . '_' . @$field['id'] . '_' . md5($radio_value);
            $checked = @$form_data['values'][@$field['name']] === $radio_value;
            if($checked){
                $input_attributes['checked'] = 'checked';
            }
            $parts = array();
            $parts['start'] = '<' . $wrapper_tag . ' ' . da_forms::array_to_attributes($wrapper_attributes) . '>';
            $parts['end'] = '</' . $wrapper_tag . '>';
            $parts['label_start'] = '<label ' . da_forms::array_to_attributes($label_attributes) . ' for="' . $radio_id . '">';
            $parts['label_html'] = $radio_text;
            $parts['label_end'] = '</label>';
            $parts['label'] = $parts['label_start'] . $parts['label_html'] . $parts['label_end'];
            $parts['input_start'] = '<' . $input_tag . ' ' . da_forms::array_to_attributes($input_attributes) . ' value="' . $radio_value . '" ' . ($radio_value == $selected_value ? 'checked="checked"' : '') . ' id="' . $radio_id . '">';
            $parts['input_html'] = '';
            $parts['input_end'] = '';
            $parts['input'] = $parts['input_start'] . $parts['input_html'] . $parts['input_end'];
            $parts['errors'] = $i == count($radio_values) ? self::get_error_helper($form['name'], $field) : '';
            foreach ($field as $option_name => $option_value) {
                if (!empty(strstr($option_name, '@'))) {
                    $parts[$option_name] = $option_value;
                }
            }
            $html .= da_forms::compile_string($template, $parts);
        }
        return $html;
    }

    /**
     * Get select HTML
     * @param $form
     * @param $field
     * @return string
     */
    public static function get_select($form, $field)
    {
        $field = self::get_control_defaults($form, $field);
        $template = @$field['field_template'];
        $wrapper_tag = @$field['wrapper_tag'];
        $wrapper_attributes = array();
        $wrapper_attributes['class'] = @$field['wrapper_class'];
        $input_tag = @$field['tag'];
        $input_attributes = array();
        $input_attributes['class'] = @$field['class'];
        $input_attributes['name'] = @$field['name'];
        $input_attributes['id'] = @$field['id'];
        $label_attributes['class'] = @$field['label_class'];
        $label_attributes['for'] = @$input_attributes['id'];
        $label_html = @$field['label_html'];
        $select_values = @$field['select_values'];
        $selected_value = @$field['selected_value'];
        $parts = array();
        $parts['start'] = '<' . $wrapper_tag . ' ' . da_forms::array_to_attributes($wrapper_attributes) . '>';
        $parts['end'] = '</' . $wrapper_tag . '>';
        $parts['label_start'] = !empty(@$label_html) ? '<label ' . da_forms::array_to_attributes($label_attributes) . '>' : '';
        $parts['label_html'] = @$label_html;
        $parts['label_end'] = !empty(@$label_html) ? '</label>' : '';
        $parts['label'] = $parts['label_start'] . $parts['label_html'] . $parts['label_end'];
        $parts['input_start'] = '<' . $input_tag . ' ' . self::array_to_attributes($input_attributes) . '>';
        $parts['input_html'] = '';
        foreach ($select_values as $option_value => $option_text) {
            $parts['input_html'] .= '<option value="' . $option_value . '" ' . ($option_value == $selected_value ? 'selected="selected"' : '') . '>';
            $parts['input_html'] .= $option_text;
            $parts['input_html'] .= '</option>';
        }
        $parts['input_end'] = '</' . $input_tag . '>';
        $parts['input'] = $parts['input_start'] . $parts['input_html'] . $parts['input_end'];
        $parts['errors'] = self::get_error_helper($form['name'], $field);
        foreach ($field as $option_name => $option_value) {
            if (!empty(strstr($option_name, '@'))) {
                $parts[$option_name] = $option_value;
            }
        }
        return da_forms::compile_string($template, $parts);
    }

    /**
     * Get submit HTML
     * @param $form
     * @param $field
     * @return string
     */
    public static function get_submit($form, $field)
    {
        $field = self::get_control_defaults($form, $field);
        $template = @$field['field_template'];
        $wrapper_tag = @$field['wrapper_tag'];
        $wrapper_attributes = array();
        $wrapper_attributes['class'] = @$field['wrapper_class'];
        $input_tag = @$field['tag'];
        $input_attributes = array();
        $input_attributes['type'] = @$field['type'];
        $input_attributes['class'] = @$field['class'];
        $input_html = @$field['caption'];
        $parts = array();
        $parts['start'] = '<' . $wrapper_tag . ' ' . self::array_to_attributes($wrapper_attributes) . '>';
        $parts['end'] = '</' . $wrapper_tag . '>';
        $parts['input_start'] = '<' . $input_tag . ' ' . self::array_to_attributes($input_attributes) . '>';
        $parts['input_html'] = $input_html;
        $parts['input_end'] = '</' . $input_tag . '>';
        $parts['input'] = $parts['input_start'] . $parts['input_html'] . $parts['input_end'];
        $parts['errors'] = self::get_error_helper($form['name'], $field);
        foreach ($field as $option_name => $option_value) {
            if (!empty(strstr($option_name, '@'))) {
                $parts[$option_name] = $option_value;
            }
        }
        return da_forms::compile_string($template, $parts);
    }

    /**
     * Get HTML field
     * @param $field
     * @return mixed
     */
    public static function get_html($field)
    {
        $html = $field['html'];
        $html = da_forms::compile_string($html, $field);
        return $html;
    }

    /**
     * Get Success HTML field
     * @param $form
     * @param $field
     * @return mixed|string
     */
    public static function get_success_html($form, $field)
    {
        $html = '';
        $form_data = @$_SESSION['da_forms_data'][$form['name']];
        if (!empty($form_data['success'])) {
            $html = da_forms::compile_string($field['html'], $form_data['form_values']);
            $html = da_forms::compile_string($html, $field);
        }
        return $html;
    }

    /**
     * Get Validation Errors field
     * @param $form
     * @param $field
     * @return mixed|string
     */
    public static function get_validation_errors($form, $field)
    {
        $html = '';
        $form_data = @$_SESSION['da_forms_data'][$form['name']];
        if (!empty($form_data['errors'])) {
            $errors_html = '';
            foreach ($form_data['errors'] as $error_field => $error) {
                $errors_html .= '<li data-field="' . $error_field . '">' . $error . '</li>';
            }
            $parts['errors'] = $errors_html;
            $html = da_forms::compile_string($field['html'], $parts);
            $html = da_forms::compile_string($html, $field);
        }
        return $html;
    }

    /**
     * Get Form Errors field
     * @param $form
     * @param $field
     * @return mixed|string
     */
    public static function get_form_errors($form, $field)
    {
        $html = '';
        $form_data = @$_SESSION['da_forms_data'][$form['name']];
        if (!empty($form_data['form_errors'])) {
            $errors_html = '';
            foreach ($form_data['form_errors'] as $error_field => $error) {
                $errors_html .= '<li data-field="' . $error_field . '">' . $error . '</li>';
            }
            $parts['form_errors'] = $errors_html;
            $html = da_forms::compile_string($field['html'], $parts);
            $html = da_forms::compile_string($html, $field);
        }
        return $html;
    }

    /**
     * Replace %aliases% to their values in string.
     * @param $template
     * @param $parts
     * @param int $repeat
     * @return mixed
     */
    public static function compile_string($template, $parts, $repeat = 1)
    {
        foreach ($parts as $part_index => $part_value) {
            $template = str_replace('%' . $part_index . '%', $part_value, $template);
        }
        if ($repeat == 0) return $template;
        $repeat--;
        return self::compile_string($template, $parts, $repeat);
    }

    /**
     * Get Error Helper element HTML
     * @param $form_name
     * @param $field
     * @return string
     */
    public static function get_error_helper($form_name, $field)
    {
        $form_show_error_helpers = !empty(@da_forms::$forms[$form_name]['validation_helper']);
        if (!$form_show_error_helpers) return '';
        $helper_tag = 'div';
        $helper_class = 'invalid-feedback help-block';
        if (!empty(da_forms::$forms[$form_name]['validation_helper_tag'])) {
            $helper_tag = da_forms::$forms[$form_name]['validation_helper_tag'];
        }
        if (!empty(da_forms::$forms[$form_name]['validation_helper_class'])) {
            $helper_class = da_forms::$forms[$form_name]['validation_helper_class'];
        }
        $html = '';
        $form_data = @$_SESSION['da_forms_data'][$form_name];
        $error = @$form_data['errors'][@$field['name']];
        if (!empty($error)) {
            $html .= '<' . $helper_tag . ' class="' . $helper_class . '">' . $error . '</' . $helper_tag . '>';
        }
        return $html;
    }

    /**
     * Get form HTML
     * @param $form_name
     * @param array $options_override
     * @return string
     */
    public static function get_form($form_name, $options_override = array())
    {
        $html = '';
        // "flashed" form data from session
        $form_data = @$_SESSION['da_forms_data'][$form_name];
        if (empty(self::$forms[$form_name])) {
            $html .= "<p>Form <strong>$form_name</strong> not found.</p>";
            return $html;
        }
        if(empty(self::$forms[$form_name]['instance'])){
            self::$forms[$form_name]['instance'] = 1;
        }
        else {
            self::$forms[$form_name]['instance']++;
        }
        if(!empty($form_data['instance'])){
            self::$forms[$form_name]['instance'] = $form_data['instance'];
        }
        $form_instance = self::$forms[$form_name]['instance'];
        if(empty($options_override)){
            if(!empty($_SESSION['da_forms_options_override_' . $form_name . '_' . $form_instance])){
                $options_override = $_SESSION['da_forms_options_override_' . $form_name . '_' . $form_instance];
            }
        }
        $_SESSION['da_forms_options_override_' . $form_name . '_' . $form_instance] = $options_override;
        da_forms::$forms[$form_name] = array_replace_recursive(@da_forms::$forms[$form_name], $options_override);
        $form_options = da_forms::get_form_defaults(da_forms::$forms[$form_name]);
        $form_attributes = array();
        $form_attributes['action'] = da_forms::get_da_forms_dir() . '/' . da_forms::$handler . '#form_' . $form_name;
        $form_attributes['id'] = 'form_' . $form_name;
        $form_attributes['class'] = 'da-form ' . $form_name;
        if (!empty($form_options['ajax']))
            $form_attributes['class'] .= ' da-form-ajax';
        if (!empty($form_options['validation_helper']))
            $form_attributes['class'] .= ' da-form-use-feedback';
        if (!empty($form_options['class']))
            $form_attributes['class'] .= ' ' . $form_options['class'];
        $form_attributes['method'] = $form_options['method'];
        if (!empty($form['files'])) {
            $form_attributes['enctype'] = 'multipart/form-data';
        }
        $html .= '<form ' . da_forms::array_to_attributes($form_attributes) . '>';
        $html .= '<input type="hidden" name="form" value="' . $form_name . '">';
        $html .= '<input type="hidden" name="instance" value="' . self::$forms[$form_name]['instance'] . '">';
        $html .= '<input type="hidden" name="url" value="' . $_SERVER['REQUEST_URI'] . '">';
        if (!empty(@$form_options['init_options']['wordpress'])) {
            $html .= '<input type="hidden" name="wordpress" value="1">';
        }
        if(empty(da_forms::$forms[$form_name]['fields']['form_errors'])){
            da_forms::$forms[$form_name]['fields']['form_errors'] = array(
                'type' => 'form_errors',
                '@before' => '<div class="alert alert-danger">',
                '@after' => '</div>',
                'html' => '%@before%An error has occurred: <ul>%form_errors%</ul>%@after%',
            );
        }
        $form_template = @da_forms::$forms[$form_name]['template'];
        /*if (strpos($form_template, '%form_errors%') === false) {
            $form_template .= ' %form_errors%';
        }*/
        $form_body = $form_template;
        if (empty(@da_forms::$forms[$form_name]['template'])) {
            if (!empty(@da_forms::$forms[$form_name]['fields'])) {
                foreach (@da_forms::$forms[$form_name]['fields'] as $field_id => $field) {
                    $form_body .= "%$field_id%";
                }
            }
        }
        foreach (da_forms::$forms[$form_name]['fields'] as $field_id => $field_options) {
            $form_body = str_replace('%' . $field_id . '%', da_forms::get_field($form_options, $field_options), $form_body);
        }
        
        // Search and replace %something% in form body with form options, first level array items only
        foreach (da_forms::$forms[$form_name] as $param_name => $param_value){
            if(is_string($param_value)){
                $form_body = str_replace('%' . $param_name . '%', $param_value, $form_body);
            }
        }
        
        $html .= $form_body;
        $html .= '</form>';
        // Remove "flashed" form data from session
        $form_data = @$_SESSION['da_forms_data'][$form_name];
        if (!empty($form_data)) {
            unset($_SESSION['da_forms_data'][$form_name]);
        }
        return $html;
    }

    /**
     * echo form HTML
     * @param $form_name
     * @param array $options
     */
    public static function the_form($form_name, $options = array())
    {
        echo self::get_form($form_name, $options);
    }

    /**
     * Get SESSION form response
     * @param $form_name
     * @return string
     */
    public static function get_form_response($form_name)
    {
        $html = '<div class="da-form-response">';
        $form_data = @$_SESSION['da_forms_data'][$form_name];
        $form_message = @$form_data['message'];
        if (!empty($form_message)) {
            $html .= '<p>' . $form_message . '</p>';
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Validate form data
     * @param $form_fields
     * @return array
     */
    public static function validate($form_fields)
    {
        $errors = array();
        foreach ($form_fields as $field_id => $field) {
            if (empty($field['name']) || empty($field['validation'])) continue;
            $validation_rules = explode('|', @$field['validation']);
            foreach ($validation_rules as $rule_str) {
                $v = explode(':', $rule_str);
                $rule = @$v[0];
                $options = explode(',', @$v[1]);
                $errors = array_merge($errors, self::validate_rule($rule, $options, $field, $form_fields));
            }
        }
        return $errors;
    }

    public static function validate_rule($rule, $options, $field, $fields)
    {
        $debug = false;
        $errors = array();
        $field_name = @$field['name'];
        $value = htmlspecialchars(stripslashes(@$_REQUEST[$field_name]));
        if (function_exists('da_form_validation_' . $rule)) {
            $errors = call_user_func("da_form_validation_$rule", $value, $options, $field, $fields);
        }
        if ($debug) {
            echo 'field:' . $field_name . '; rule: ' . $rule . '. ';
        }
        return $errors;
    }

    /**
     * Generate form name
     * @return string
     */
    public static function get_new_form_name()
    {
        return 'da_form_' . (count(self::$forms) + 1);
    }

    /**
     * Add form options to Main Forms Array
     * @param $form_data
     */
    public static function load_form($form_data)
    {
        global $da_form_file_name;
        $form_name = @$form_data['name'];
        if (empty($form_name) && !empty($da_form_file_name)) {
            $form_name = $da_form_file_name;
        }
        if (empty($form_name)) {
            $form_name = self::get_new_form_name();
        }
        $form_data['name'] = $form_name;
        $form_data['init_options'] = self::$options;
        $da_form_file_name = '';
        self::$forms[$form_name] = $form_data;
    }

    public static function init($init_options = array())
    {

        if (self::$initialized) {
            return false;
        }

        self::$initialized = true;

        self::$options = $init_options;

        if (@self::$options['wordpress']) {
            if (!defined('ABSPATH')) {
                $wp_load_files = array(
                    $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php',
                    $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php',
                    $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php',
                    $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/pluggable.php'
                );
                foreach ($wp_load_files as $file) {
                    if (file_exists($file)) {
                        /** @noinspection PhpIncludeInspection */
                        include_once $file;
                    }
                }
            }
        }

        /**
         * Add validation rules from folder
         */
        foreach (glob(__DIR__ . "/validation/*.php") as $filename) {
            /** @noinspection PhpIncludeInspection */
            include $filename;
        }

        /**
         * Add forms from folder
         */
        foreach (glob(__DIR__ . "/forms/*.php") as $filename) {
            global $da_form_file_name;
            $da_form_file_name = basename($filename);
            $da_form_file_name = basename($da_form_file_name, ".php");
            /** @noinspection PhpIncludeInspection */
            include $filename;
        }

        return true;
    }

}
