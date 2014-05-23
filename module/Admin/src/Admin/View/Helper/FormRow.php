<?php

namespace Admin\View\Helper;

use Zend\Form\ElementInterface,
	Zend\Form\View\Helper\FormRow as ZendFormRow,
	Zend\Form\Element;

class FormRow extends ZendFormRow
{
	
    public function __invoke(ElementInterface $element = null, $labelPosition = null, $renderErrors = null, $partial = null)
    {
				
		if($partial === null)
		{

			$partial = 'helper/form-row/admin-';

			switch(true)
			{

				case $element instanceof Element\Text:
				case $element instanceof Element\Email:
				case $element instanceof Element\Password:
				case $element instanceof Element\File:

					$partial .= 'text.phtml';

					break;

				default:

					$partial .= strtolower(substr(get_class($element), strrpos(get_class($element), '\\') + 1)) . '.phtml';

			}

		}
		
		return parent::__invoke($element, $labelPosition, $renderErrors, $partial);
		
	}
	
}