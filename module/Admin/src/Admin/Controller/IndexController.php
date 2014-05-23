<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Admin\Controller;

use Andreatta\Controller\Base,
	Zend\View\Model\ViewModel;

class IndexController extends BaseController
{
	
    public function indexAction()
    {

        return $this->forward()->dispatch('Admin\Controller\Statistics', 
        [

        	'action' => 'monetary', 
        	'range' => 'daily', 
        	'date-from' => ( new \DateTime('now') )->sub( new \DateInterval('P7D') )->format('Y-m-d'),
        	'date-to' => ( new \DateTime('now') )->format('Y-m-d')

        ]);

    }

}
