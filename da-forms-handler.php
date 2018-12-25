<?php

//ini_set('display_errors', 'On');

include 'da-forms.php';

$da_forms_init_options = array();
if(!empty(@$_REQUEST['wordpress'])){
    $da_forms_init_options['wordpress'] = true;
}
da_forms::init($da_forms_init_options);

$data = array();
$form_submit = false;
$form_name = htmlspecialchars(stripslashes(@$_REQUEST['form']));
$form_url = htmlspecialchars(stripslashes(@$_REQUEST['url']));
$form_instance = htmlspecialchars(stripslashes(@$_REQUEST['instance']));
$form_json = !empty(htmlspecialchars(stripslashes(@$_REQUEST['json'])));

if (empty($form_name)) {
    die();
}

$data['errors'] = array();
$data['form_errors'] = array();
$data['values'] = array();
$data['instance'] = $form_instance;

if (empty(da_forms::$forms[$form_name])) {
    $data['form_errors']['form_not_found'] = 'Форма не найдена.';
}

$options_override = array();
if(!empty($_SESSION['da_forms_options_override_' . $form_name . '_' . $form_instance])){
    $options_override = $_SESSION['da_forms_options_override_' . $form_name . '_' . $form_instance];
}
da_forms::$forms[$form_name] = array_replace_recursive(@da_forms::$forms[$form_name], $options_override);

$form_settings = @da_forms::$forms[$form_name];
$form_messages = @da_forms::$forms[$form_name]['messages'];
$form_fields = @da_forms::$forms[$form_name]['fields'];
$form_actions = @da_forms::$forms[$form_name]['actions'];

$validation_errors = da_forms::validate($form_fields);

$data['values']['SITE_URL'] = da_forms::siteURL();
$data['values']['REMOTE_ADDR'] = @$_SERVER['REMOTE_ADDR'];
$data['values']['HTTP_X_FORWARDED_FOR'] = @$_SERVER['HTTP_X_FORWARDED_FOR'];

foreach ($form_fields as $field){
    if(!empty(@$field['name'])){
        $data['values'][$field['name']] = htmlspecialchars(stripslashes(trim(@$_REQUEST[$field['name']])));
        if(@$field['type'] == 'file') {
            // https://gist.github.com/ebidel/2410898
            $file_name = @$_FILES[$field['name']]['name'];
            $file_ext_arr = explode(".", $file_name);
            $file_tmp_name = @$_FILES[$field['name']]['tmp_name'];
            $data['values'][$field['name'] . '.name'] = $file_name;
            $data['values'][$field['name'] . '.ext'] = end($file_ext_arr);
            $data['values'][$field['name'] . '.type'] = @$_FILES[$field['name']]['type'];
            $data['values'][$field['name'] . '.size'] = @$_FILES[$field['name']]['size'];
            $data['values'][$field['name'] . '.tmp_name'] = $file_tmp_name;
            $data['values'][$field['name'] . '.error'] = @$_FILES[$field['name']]['error'];
            $data['values'][$field['name'] . '.basename'] = basename(@$_FILES[$field['name']]['tmp_name']);
        }
    }
}

$data['errors'] = array_replace_recursive($data['errors'], $validation_errors);

if(empty($data['errors']) && !empty($form_actions)){
    foreach ($form_actions as $action){
        if(!empty($data['form_errors'])){
            //$data['form_errors']['actions'] = 'action.' . $action['type'] . '.stopped';
            break;
        }
        $action_type = @$action['type'];
        $action_json = json_encode($action);
        $action_json = da_forms::compile_string($action_json, $data['values']);
        $action_data = json_decode($action_json, true);
        $action_file = 'actions/' . $action_type . '.php';
        if(file_exists($action_file)){
            /** @noinspection PhpIncludeInspection */
            include $action_file;
        }
        $function_name = 'action_' . $action_type;
        if(function_exists($function_name)) {
            $action_result = $function_name($action_data);
            if(!empty($action_result['errors'])){
                $data['form_errors'] = array_replace_recursive($data['form_errors'], $action_result['errors']);
            }
            if(!empty($action_result['values'])){
                $data['values'] = array_replace_recursive($data['values'], $action_result['values']);
            }
        }
        else {
            $data['form_errors']['actions'] = 'Action ' . $action_type . ' not found.';
        }
    }
}

if(empty($data['errors']) && empty($data['form_errors'])){
    $data['form_values'] = $data['values'];
    if(!(@$form_settings['keep_values'])){
        unset($data['values']);
    }
}

if (!empty($data['errors'])) {
    foreach ($data['errors'] as $error_field => $error) {
        $error_text = $error;
        if(isset($form_messages[$error])){
            $data['errors'][$error_field] = $form_messages[$error];
        }
    }
}

if (!empty($data['form_errors'])) {
    foreach ($data['form_errors'] as $error_field => $error) {
        $error_text = $error;
        if(isset($form_messages[$error])){
            $data['form_errors'][$error_field] = $form_messages[$error];
        }
    }
}

$data['success'] = empty($data['errors']) && empty($data['form_errors']);

$_SESSION['da_forms_data'][$form_name] = $data;

if ($form_json) {
    header('Content-Type: application/json');
    $data['form_html'] = da_forms::get_form($form_name);
    echo json_encode($data);
} else {
    header('Content-Type: text/html; charset=utf-8');
    header('Location: ' . $form_url);
}

die();
