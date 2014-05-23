<?php

namespace Application\Form\Element;

use Zend\Form\Element\Text;

class Float extends Text
{
    
	/**
     * Set the element value
     *
     * @param  mixed $value
     * @return Element
     */
    public function setValue($value)
    {

    	$locale = $this->getOption('locale') ? $this->getOption('locale') : 'en_US';

    	$fmt = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);
    	$fmt->setAttribute(\NumberFormatter::DECIMAL_ALWAYS_SHOWN, true);
    	$fmt->setAttribute(\NumberFormatter::FRACTION_DIGITS, 2);

		$this->value = $fmt->format($value);

        return $this;

    }

    /**
     * Retrieve the element value
     *
     * @return mixed
     */
    public function getValue()
    {

        return $this->value;

    }

}
