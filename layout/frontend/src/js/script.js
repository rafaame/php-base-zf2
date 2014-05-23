$(document).ready(function()
{

	$(document).trigger('responsive');

    $('form[rel="ajax"] [type="submit"]').click(function(e)
    {

        var form = $(this).parents('form[rel="ajax"]');
        var _this = this;

        var customOptions = $(this).data('ajaxOptions');

        var options = 
        {

            dataType: 'json',

            success: function(data)
            {

                if(data.success)
                    FlashMessenger.success(data.message);
                else
                    FlashMessenger.danger(data.message);

                var successCallback = $(_this).data('success-callback');

                if(successCallback)
                    successCallback(data);

            }

        };

        if($(this).attr('formaction'))
            options.url = $(this).attr('formaction');

        if(customOptions)
        {

            if(customOptions.success)
            {

                $(this).data('success-callback', customOptions.success);
                delete customOptions.success;

            }

            options = $.extend(options, customOptions);

        }

        form.ajaxSubmit(options);

        e.preventDefault();

    });

	$('[data-toggle="dialog"]').click(function(e)
    {

        var buttons = null;
        var href = $(this).attr('data-href');

        switch($(this).attr('data-modal-type'))
        {

            case 'confirm':

                buttons = 
                [

                    {

                        label: $(this).attr('data-modal-button-no'),
                        cssClass: 'btn btn-default',
                        autospin: false,
                        action: function(dialog)
                        {   

                            dialog.close();

                        }

                    },

                    {

                        label: $(this).attr('data-modal-button-yes'),
                        cssClass: 'btn btn-primary',
                        autospin: false,
                        action: function(dialog)
                        {   

                            window.location.href = href;

                        }

                    }

                ];

                break;

        }

        BootstrapDialog.show
        ({

            title: $(this).attr('data-modal-title'),
            message: $(this).attr('data-modal-message'),
            buttons: buttons,
            closable: false,

        });

        e.preventDefault();

    });

    //HTML5 forms support for older browsers
    if
    (

        !Modernizr.input.placeholder ||
        !Modernizr.input.required ||
        !Modernizr.input.pattern

    )
    {

        $('form').h5Validate
        ({

            focusout: false,
            focusin: false,
            change: false,
            keyup: false

        });

        $('form').bind('formValidated', function(data)
        {

            var inputsValidated = {};

            $.each(data.target, function(index, target)
            {

                var name = $(target).attr('name');

                if(name)
                {

                    if(inputsValidated[name])
                        return;
                    else
                        inputsValidated[name] = true;

                }

                //Figure out a way to translate this
                if(target.validity.valueMissing)
                    title = 'This field is required.';

                if(!target.validity.valid)
                {

                    var _this = this;

                    $(this)
                    .tooltip
                    ({

                        placement: 'auto',
                        trigger: 'manual',
                        title: title,

                    })
                    .tooltip('show');

                    $('[name="' + name + '"]').bind('click', function()
                    {

                        $(_this).tooltip('destroy');

                    });

                }

            });

        })

    }

});