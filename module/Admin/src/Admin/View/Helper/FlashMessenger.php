<?php
namespace Admin\View\Helper;

use Zend\Mvc\Controller\Plugin\FlashMessenger as PluginFlashMessenger;

/**
 * Helper to proxy the plugin flash messenger
 */
class FlashMessenger extends \Zend\View\Helper\FlashMessenger
{

    /**
     * Render Messages
     *
     * @param  string $namespace
     * @param  array  $classes
     * @return string
     */
    public function render($namespace = PluginFlashMessenger::NAMESPACE_DEFAULT, array $classes = array())
    {

        $this
            ->setMessageOpenFormat
            (
                '<div%s>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">
                    &times;
                    </button>
                    <ul><li>'
            )
            ->setMessageSeparatorString('</li><li>')
            ->setMessageCloseString('</li></ul></div>');

    
        switch($namespace)
        {

            case PluginFlashMessenger::NAMESPACE_ERROR:

                $classes = array_merge(array('flash-messenger', 'alert', 'alert-dismissable', 'alert-danger'), $classes);

            break;

            case PluginFlashMessenger::NAMESPACE_INFO:

                $classes = array_merge(array('flash-messenger', 'alert', 'alert-dismissable', 'alert-info'), $classes);

            break;

            case PluginFlashMessenger::NAMESPACE_DEFAULT:

                $classes = array_merge(array('flash-messenger', 'alert', 'alert-dismissable', 'alert-warning'), $classes);

            break;

            case PluginFlashMessenger::NAMESPACE_SUCCESS:

                $classes = array_merge(array('flash-messenger', 'alert', 'alert-dismissable', 'alert-success'), $classes);

            break;

        }

        $autoHide = true;
        
        $flashMessenger = $this->getPluginFlashMessenger();
        $messages = $flashMessenger->getMessagesFromNamespace($namespace);

        // Flatten message array
        $messagesToPrint = array();

        $translator = $this->getTranslator();
        $translatorTextDomain = $this->getTranslatorTextDomain();

        foreach($messages as $item)
        {

            $msg = $item['message'];

            if ($translator !== null) {
                $msg = $translator->translate(
                    $msg,
                    $translatorTextDomain
                );
            }

            $messagesToPrint[] = $msg;
            $autoHide = $autoHide && $item['options']['auto-hide'];

        }

        /*array_walk_recursive($messages, function ($item) use (&$messagesToPrint, $translator, $translatorTextDomain, &$autoHide) {

            $msg = $item['message'];

            if ($translator !== null) {
                $msg = $translator->translate(
                    $msg,
                    $translatorTextDomain
                );
            }

            $messagesToPrint[] = $msg;
            $autoHide = $msg['options']['auto-hide'];
        });*/

        if (empty($messagesToPrint)) {
            return '';
        }

        if(!$autoHide)
            $classes = array_merge(['no-auto-hide'], $classes);

        // Prepare classes for opening tag
        if (empty($classes)) {
            if (isset($this->classMessages[$namespace])) {
                $classes = $this->classMessages[$namespace];
            } else {
                $classes = $this->classMessages[PluginFlashMessenger::NAMESPACE_DEFAULT];
            }
            $classes = array($classes);
        }

        // Generate markup
        $markup  = sprintf($this->getMessageOpenFormat(), ' class="' . implode(' ', $classes) . '"');
        $markup .= implode(sprintf($this->getMessageSeparatorString(), ' class="' . implode(' ', $classes) . '"'), $messagesToPrint);
        $markup .= $this->getMessageCloseString();

        return $markup;
    }
}
