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
                'validation' => 'required'
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
                'placeholder' => 'Write message',
                'rows' => 3
            ),
            'email' => array(
                'name' => 'email',
                'type' => 'email',
                'placeholder' => 'Email',
                'validation' => 'required|email'
            ),
            'variants' => array(
                'name' => 'variants',
                'type' => 'radio',
                'required' => true,
                'wrapper_class' => 'radio',
                'label_wrap' => true,
                'radio_values' => array(
                    '1' => 'First option',
                    '2' => 'Second option',
                    '3' => 'Third option'
                ),
                'selected_value' => '2',
                'field_template' => '%start% %label_start% %input% %label_html% %label_end% %errors% %end%'
            ),
            'variants2' => array(
                'name' => 'variants',
                'type' => 'radio',
                'required' => true,
                'wrapper_class' => 'radio',
                'label_wrap' => true,
                'radio_values' => array(
                    '4' => 'Four option',
                    '5' => 'Five option',
                ),
                'field_template' => '%start% %label_start% %input% %label_html% %label_end% %errors% %end%'
            ),
            'variants3' => array(
                'name' => 'selectus',
                'type' => 'select',
                'required' => true,
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
                'label_html' => 'Логин',
                'validation' => 'required',
            ),
            'password' => array(
                'name' => 'password',
                'type' => 'password',
                'label_html' => 'Пароль',
                'validation' => 'required',
            ),
            'subscribe' => array(
                'name' => 'subscribe',
                'type' => 'checkbox',
                'wrapper_class' => 'checkbox',
                'label_wrap' => true,
                'value' => 1,
                'checked' => true,
                'label_html' => 'Subscribe our news',
                'field_template' => '%start% %label_start% %input% %label_html% %label_end% %errors% %end%'
            ),
            'agree' => array(
                'name' => 'agree',
                'type' => 'checkbox',
                'wrapper_class' => 'checkbox',
                'value' => 1,
                'label_html' => 'I agree to the processing of <a href="/privacy-policy/">personal data</a>',
                'validation' => 'required',
                'field_template' => '%start% %label_start% %input% %label_html% %label_end% %errors% %end%'
            ),
            'submit' => array(
                'type' => 'submit',
                'caption' => 'Submit',
                'class' => 'btn btn-success'
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
                'html' => '%@before%The following errors were detected:<br> %errors% %@after%'
            )
        ),
        'template' => '<div class="row">
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
                        %login%
                        %password%
                        %agree%
                        %submit%
                        %success%
                        %validation%',
        'messages' => array(
            'name.required' => 'Enter your name',
            'tel.required' => 'Enter the phone number',
            'agree.required' => 'Confirm the personal data processing conditions'
        )
    ));
}