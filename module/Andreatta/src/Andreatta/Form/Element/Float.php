<?php

namespace Andreatta\Form\Element;

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
		$format = new \NumberFormatter($locale, \NumberFormatter::DECIMAL);

		if(intl_is_failure($format->getErrorCode()))
			throw new Exception\InvalidArgumentException("Invalid locale string given");

		$format->setAttribute(\NumberFormatter::DECIMAL_ALWAYS_SHOWN, true);
		$format->setAttribute(\NumberFormatter::FRACTION_DIGITS, 2);

		$this->value = $format->format($value);

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
