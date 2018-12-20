<?php

if (method_exists('da_forms', 'load_form')) {
    da_forms::load_form(array(
        'files' => false,
        'ajax' => true,
        'validation_helper' => true,
        'validation_message' => true,
        'fields' => array(
            'name' => array(
                'name' => 'name',
                'type' => 'text',
                'placeholder' => 'Your name',
                'validation' => 'required',
                'field_template' => '%start% %input% %errors% %end%'
            ),
            'tel' => array(
                'name' => 'tel',
                'type' => 'text',
                'placeholder' => '+7 (___) ____-__-__',
                'validation' => 'required|phone'
            ),
            'message' => array(
                'name' => 'message',
                'type' => 'textarea',
                'placeholder' => 'Your message',
                'rows' => 3,
                'value' => 'Some text...',
            ),
            'email' => array(
                'name' => 'email',
                'type' => 'email',
                'placeholder' => 'Email',
                'validation' => 'email'
            ),
            'variants' => array(
                'name' => 'variants',
                'type' => 'radio',
                'radio_values' => array(
                    '1' => 'First option',
                    '2' => 'Second option',
                    '3' => 'Third option'
                ),
                'selected_value' => '2'
            ),
            'variants2' => array(
                'name' => 'variants',
                'type' => 'radio',
                'radio_values' => array(
                    '4' => 'Four option',
                    '5' => 'Five option'
                )
            ),
            'variants3' => array(
                'name' => 'selectus',
                'type' => 'select',
                'select_values' => array(
                    '1' => 'First option',
                    '2' => 'Second option',
                    '3' => 'Third option'
                ),
                'selected_value' => '2'
            ),
            'login' => array(
                'name' => 'login',
                'type' => 'text',
                'label_html' => 'Login',
                'validation' => 'required',
            ),
            'password' => array(
                'name' => 'password',
                'type' => 'password',
                'label_html' => 'Password',
                'validation' => 'required',
            ),
            'subscribe' => array(
                'name' => 'subscribe',
                'type' => 'checkbox',
                'value' => 1,
                'checked' => true,
                'label_html' => 'Subscribe our news',
            ),
            'agree' => array(
                'name' => 'agree',
                'type' => 'checkbox',
                'value' => 1,
                'label_html' => 'Согласен на обработку <a href="#">персональных данных</a>',
                'validation' => 'required'
            ),
            'submit' => array(
                'type' => 'submit',
                'caption' => 'Submit',
                'class' => 'btn btn-success mt-3',
            ),
            'success' => array(
                'type' => 'success_html',
                '@before' => '<div class="alert alert-success">',
                '@after' => '</div>',
                'html' => '%@before%Thanks, %name%!<br> We will contact you shortly.%@after%'
            ),
            'validation' => array(
                'type' => 'validation_errors',
                '@before' => '<div class="alert alert-warning">',
                '@after' => '</div>',
                'html' => '%@before%The following errors were found: %errors% %@after%'
            )
        ),
        'template' => '%login% %password%
                        <div class="row">
                        <div class="col-12 col-sm-6">%name%</div>
                        <div class="col-12 col-sm-6">%tel%</div>
                        </div>
                        %message%
                        %email%
                        <div class="row">
                        <div class="col-12 col-sm-6 col-md-4">%variants%</div>
                        <div class="col-12 col-sm-6 col-md-4">%variants2%</div>
                        </div>
                        %variants3% 
                        %subscribe%
                        %agree%
                        %submit%
                        %success%
                        %validation%',
        'messages' => array(
            'name.required' => 'Укажите Ваше имя',
            'email.required' => 'Укажите Email',
            'tel.required' => 'Укажите номер телефона',
            'password.required' => 'Введите пароль',
            'tel.invalid' => 'Неправильно набран номер',
            'agree.required' => 'Подтвердите условия обработки персональных данных'
        )
    ));
}