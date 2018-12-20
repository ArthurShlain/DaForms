(function( $ ) {

    var $body = $('body');
    var error_field_wrapper_class = 'has-error';
    var error_field_class = 'is-invalid';

    $body.submit('.da-form-ajax', function (event) {

        var _form = event.target;
        var $form = $(event.target);

        $('body').trigger('da-form-submit', $form);

        if(!$form.hasClass('da-form')) return;
        if(!$form.hasClass('da-form-ajax')) return;

        event.preventDefault();

        var formData = new FormData(_form);
        var action = $form.attr('action');
        var method = $form.attr('method');

        formData.append('json', '1');

        $form.find('[type="submit"]').addClass('disabled progress-bar-striped progress-bar-animated');
        $form.find('.da-form-response').html('');
        $form.find('.da-form-group').removeClass(error_field_wrapper_class);
        $form.find('.da-form-group [name]').removeClass(error_field_class);
        $form.find('.invalid-feedback').remove();

        var xhr = new XMLHttpRequest();
        xhr.responseType = 'json';
        xhr.open(method, action);

        xhr.onload = function (e) {

            $form.find('[type="submit"]').removeClass('disabled progress-bar-striped progress-bar-animated');

            var response = e.currentTarget.response;

            var form_id = $(_form).attr('id');
            var form_$parent = $(_form).parent();

            if(typeof response.form_html !== 'undefined'){
                $form.replaceWith(response.form_html);
            }

            $form = form_$parent.find('#' + form_id);

            $('body').trigger('da-form-xhr-load', $form);

        };
        xhr.send(formData);
    });

})(jQuery);