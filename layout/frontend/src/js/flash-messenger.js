function FlashMessenger()
{

	this.display = function(type, message, dismissable)
	{

		dismissable = typeof dismissable == 'undefined' ? true : dismissable;

		$('body').prepend
		(

			'<div class="flash-messenger alert alert-' + type + (dismissable ? ' alert-dismissable' : '') + ' text-center">' + 
				(dismissable ? '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' : '') + 
				message + 
			'</div>'

		);

		this.process();

	};

	this.success = function(message, dismissable)
	{

		this.display('success', message, dismissable);

	};

	this.info = function(message, dismissable)
	{

		this.display('info', message, dismissable);

	};

	this.warning = function(message, dismissable)
	{

		this.display('warning', message, dismissable);

	};

	this.danger = function(message, dismissable)
	{

		this.display('danger', message, dismissable);

	};

	this.process = function()
	{

		$('.flash-messenger').each(function()
		{

			var flashMessage = $(this);

			flashMessage.slideDown();

			if($(this).hasClass('alert-success') && $(this).hasClass('alert-dismissable') && !$(this).hasClass('no-auto-hide'))
			{

				setTimeout(function()
				{

					flashMessage.slideUp('slow', function()
					{

						flashMessage.remove();

					});

				}, 5000);

			}

			if(!$(this).hasClass('no-auto-hide'))
				flashMessage.click(function()
				{

					flashMessage.slideUp('slow', function()
					{

						flashMessage.remove();

					});

				});

		});

	};

}

var FlashMessenger = new FlashMessenger();

$(document).ready(function()
{

	FlashMessenger.process();

});