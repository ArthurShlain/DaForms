<?php

if (method_exists('da_forms', 'load_form')) {
    da_forms::load_form(array(
        'files' => false,
        'ajax' => true,
        'validation_helper' => false,
        'fields' => array(
            'name' => array(
                'name' => 'name',
                'type' => 'text',
                'placeholder' => 'Your name',
                'validation' => 'required',
                'wrapper_class' => 'form-group input-group',
            ),
            'tel' => array(
                'name' => 'tel',
                'type' => 'text',
                'placeholder' => 'Your phone',
                'validation' => 'required|phone',
                'wrapper_class' => 'form-group input-group',
            ),
            'message' => array(
                'name' => 'message',
                'type' => 'textarea',
                'placeholder' => 'Your message',
                'rows' => 3,
                'value' => '',
            ),
            'agree' => array(
                'name' => 'agree',
                'type' => 'checkbox',
                'value' => 1,
                'label_html' => 'I agree with <a href="#">the privacy policy</a>',
                'validation' => 'required',
                'template' => '%start% %input% %label% %errors% %end%',
            ),
            'submit' => array(
                'type' => 'submit',
                '@icon' => '<i class="fas fa-paper-plane"></i>',
                'caption' => '%@icon% Send',
                'class' => 'btn btn-success'
            ),
            'success' => array(
                'type' => 'success_html',
                '@before' => '<div class="alert alert-success">',
                '@after' => '</div>',
                'html' => '%@before%Thank you, %name%!<br> Your message has been successfully sent.%@after%'
            ),
            'validation' => array(
                'type' => 'validation_errors',
                '@before' => '<div class="alert alert-warning">',
                '@after' => '</div>',
                'html' => '%@before%The following errors were found: <ul>%errors%</ul> %@after%'
            ),
            'row' => array(
                'type' => 'html',
                'html' => '<div class="row">'
            ),
            'col' => array(
                'type' => 'html',
                'html' => '<div class="col-12 col-sm-6">'
            ),
            ';' => array(
                'type' => 'html',
                'html' => '</div>'
            )
        ),
        'template' => '%row%
                       %col% %name% %;%
                       %col% %tel% %;%
                       %;%
                       %message% 
                       %hone%  
                       %agree% 
                       %validation% 
                       %submit% 
                       %success%',
        'messages' => array(
            'name.required' => 'Enter your name',
            'tel.required' => 'Enter the phone number',
            'agree.required' => 'Agree to the privacy policy',
            'tel.invalid' => 'Wrong phone number'
        ),
        'actions' => array(
            'mail_example' => array(
                'type' => 'mail',

                // Server settings
                'SMTPDebug' => 0,
                'isSMTP' => true,
                'Host' => '',
                'SMTPAuth' => true,
                'Username' => '',
                'Password' => '',
                'SMTPSecure' => '', // tls, ssl
                'Port' => 25,

                // Recipients
                'From' => 'sender@test.test',
                'To' => 'recipient@test.test',

                // Content
                'isHTML' => true,
                'Subject' => 'New question',
                'Body' => '<p>New question from website %SITE_URL%</p>
                    <p>Sender name: %name%</p>
                    <p>Phone: %tel%</p>
                    <p>Message: <br>%message%</p>
                    <hr>
                    <p>REMOTE_ADDR: %REMOTE_ADDR%</p>',

            ),
        )
    ));
}