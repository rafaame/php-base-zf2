<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
	Zend\View\Model\ViewModel;

class IndexController extends BaseController
{

    public function indexAction()
    {

        return new ViewModel();

    }

    public function aboutUsAction()
    {

        return new ViewModel();

    }

    public function contactAction()
    {

        return new ViewModel();

    }

}
