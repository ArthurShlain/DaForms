(function( $ ) {

    var $body = $('body');
    var error_field_wrapper_class = 'has-error';
    var error_field_class = 'is-invalid';

    $body.submit('.da-form-ajax', function (event) {

        var _form = event.target;
        var form_id = $(_form).attr('id');

        if(!$(_form).hasClass('da-form')) return;
        if(!$(_form).hasClass('da-form-ajax')) return;

        event.preventDefault();

        var formData = new FormData(_form);
        var action = $(_form).attr('action');
        var method = $(_form).attr('method');

        formData.append('json', '1');

        $(_form).find('[type="submit"]').addClass('disabled progress-bar-striped progress-bar-animated');
        $(_form).find('.da-form-response').html('');
        $(_form).find('.da-form-group').removeClass(error_field_wrapper_class);
        $(_form).find('.da-form-group [name]').removeClass(error_field_class);
        $(_form).find('.invalid-feedback').remove();

        var xhr = new XMLHttpRequest();
        xhr.responseType = 'json';
        xhr.open(method, action);

        xhr.onload = function (e) {

            $(_form).find('[type="submit"]').removeClass('disabled progress-bar-striped progress-bar-animated');

            var response = e.currentTarget.response;

            if(typeof response.form_html !== 'undefined'){
                $(_form).replaceWith(response.form_html);
            }

            _form = '#' + form_id;

            if(response.success){
                $(_form).find('.hide-on-success').addClass('d-none');
            }
            
        };
        xhr.send(formData);
    });

})(jQuery);
