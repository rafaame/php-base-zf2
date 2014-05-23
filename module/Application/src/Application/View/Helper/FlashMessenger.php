<?php
namespace Application\View\Helper;

class FlashMessenger extends \Admin\View\Helper\FlashMessenger
{

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

        return parent::render($namespace, $classes);
        
    }
}
