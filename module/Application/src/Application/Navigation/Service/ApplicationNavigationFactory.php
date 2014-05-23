<?php
namespace Application\Navigation\Service;

use Zend\Navigation\Service\AbstractNavigationFactory;

class ApplicationNavigationFactory extends AbstractNavigationFactory
{

    /**
     * @return string
     */
    protected function getName()
    {

        return 'Application';

    }

}
