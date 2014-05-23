<?php

namespace Admin\Mvc\Controller\Plugin;

class FlashMessenger extends \Zend\Mvc\Controller\Plugin\FlashMessenger
{



	/**
     * Html escape helper
     *
     * @var EscapeHtml
     */
    protected $escapeHtmlHelper;
	
	public function addFormMessages($form)
	{
		
		$messages = $form->getMessages();
		
		foreach($messages as $element)
		{
			
			foreach($element as $message)
			{
				
				$this->addErrorMessage($message);
				
			}
			
		}
		
	}

	/**
     * Add a message
     *
     * @param  string         $message
     * @return FlashMessenger Provides a fluent interface
     */
    public function addMessage($message, $autoHide = true, $escapeHtml = true, $charset = 'utf-8')
    {

        $escapeHtmlHelper = $this->getEscapeHtmlHelper();

        if($escapeHtml)
        	$message = $escapeHtmlHelper->escapeHtml($message);

        $message =
        [

            'message' => $message,
            'options' =>
            [

                'auto-hide' => $autoHide,

            ]

        ];

        return parent::addMessage($message);

        /*$container = $this->getContainer();
        $namespace = $this->getNamespace();

        if (!$this->messageAdded) {
            $this->getMessagesFromContainer();
            $container->setExpirationHops(1, null);
        }

        if (!isset($container->{$namespace})
            || !($container->{$namespace} instanceof SplQueue)
        ) {
            $container->{$namespace} = new SplQueue();
        }

        $container->{$namespace}->push($message);

        $this->messageAdded = true;

        return $this;*/
        
    }

    /**
     * Add a message with "info" type
     *
     * @param  string         $message
     * @return FlashMessenger
     */
    public function addInfoMessage($message, $autoHide = true, $escapeHtml = true, $charset = 'utf-8')
    {

        $namespace = $this->getNamespace();
        $this->setNamespace(self::NAMESPACE_INFO);
        $this->addMessage($message, $autoHide, $escapeHtml, $charset);
        $this->setNamespace($namespace);

        return $this;

    }

    /**
     * Add a message with "success" type
     *
     * @param  string         $message
     * @return FlashMessenger
     */
    public function addSuccessMessage($message, $autoHide = true, $escapeHtml = true, $charset = 'utf-8')
    {
        $namespace = $this->getNamespace();
        $this->setNamespace(self::NAMESPACE_SUCCESS);
        $this->addMessage($message, $autoHide, $escapeHtml, $charset);
        $this->setNamespace($namespace);

        return $this;
    }

    /**
     * Add a message with "error" type
     *
     * @param  string         $message
     * @return FlashMessenger
     */
    public function addErrorMessage($message, $autoHide = true, $escapeHtml = true, $charset = 'utf-8')
    {
        $namespace = $this->getNamespace();
        $this->setNamespace(self::NAMESPACE_ERROR);
        $this->addMessage($message, $autoHide, $escapeHtml, $charset);
        $this->setNamespace($namespace);

        return $this;
    }

    /**
     * Retrieve the escapeHtml helper
     *
     * @return EscapeHtml
     */
    protected function getEscapeHtmlHelper($charset = 'utf-8')
    {
        if ($this->escapeHtmlHelper) {
            return $this->escapeHtmlHelper;
        }

        $this->escapeHtmlHelper = new \Zend\Escaper\Escaper($charset);

        return $this->escapeHtmlHelper;
    }
	
}
