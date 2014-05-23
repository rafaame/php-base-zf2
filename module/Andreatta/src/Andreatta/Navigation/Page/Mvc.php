<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Andreatta\Navigation\Page;

use Zend\Navigation\Page\Mvc as BasePage,
    Zend\Navigation\Page\AbstractPage,
    Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\Router\RouteMatch,
    Zend\Mvc\Router\RouteStackInterface,
    Zend\Navigation\Exception;

/**
 * This class method isActive() gives support for arrays in either controller or action parameters for navigation.
 */
class Mvc extends BasePage
{
    /**
     * Action name to use when assembling URL
     *
     * @var string|array
     */
    protected $action;

    /**
     * Controller name to use when assembling URL
     *
     * @var string|array
     */
    protected $controller;

    // Accessors:

    /**
     * Returns whether page should be considered active or not
     *
     * This method will compare the page properties against the route matches
     * composed in the object.
     *
     * @param  bool $recursive  [optional] whether page should be considered
     *                          active if any child pages are active. Default is
     *                          false.
     * @return bool             whether page should be considered active or not
     */
    public function isActive($recursive = false)
    {

        if (!$this->active) 
        {

            $reqParams = [];

            if ($this->routeMatch instanceof RouteMatch)
            {

                $reqParams  = $this->routeMatch->getParams();

                if (isset($reqParams[ModuleRouteListener::ORIGINAL_CONTROLLER]))
                    $reqParams['controller'] = $reqParams[ModuleRouteListener::ORIGINAL_CONTROLLER];

                $myParams   = $this->params;

                if (null !== $this->controller)
                    $myParams['controller'] = $this->controller;

                if (null !== $this->action)
                    $myParams['action'] = $this->action;

                if (null !== $this->getRoute()) 
                {

                    $matched = false;

                    $childRoute = $this->getRoute() . '/';
                    $matchedRoute = 
                        (

                            $this->routeMatch->getMatchedRouteName() === $this->getRoute() ||
                            substr($this->routeMatch->getMatchedRouteName(), 0, strlen($childRoute)) == $childRoute

                        );

                    if
                    (

                        $matchedRoute &&
                        (

                            (

                                (

                                    isset($myParams['controller']) && 
                                    !is_array($myParams['controller'])

                                ) ||

                                !isset($myParams['controller'])

                            ) &&
                            (

                                (

                                    isset($myParams['action']) && 
                                    !is_array($myParams['action'])

                                ) ||

                                !isset($myParams['action'])

                            ) &&
                            count(array_intersect_assoc($reqParams, $myParams)) == count($myParams)

                        )

                    )
                        $matched = true;
                    else if
                    (

                        $matchedRoute &&
                        (

                            (

                                
                                isset($myParams['controller']) && 
                                is_array($myParams['controller'])

                            ) &&
                            (

                                (

                                    isset($myParams['action']) && 
                                    !is_array($myParams['action'])

                                ) ||

                                !isset($myParams['action'])
                                
                            )

                        )

                    )
                    {

                        $controllers = $myParams['controller'];
                        unset($myParams['controller']);

                        if
                        (

                            count(array_intersect_assoc($reqParams, $myParams)) == count($myParams) &&
                            in_array($reqParams['controller'], $controllers)

                        )
                            $matched = true;

                    }
                    else if
                    (

                        $matchedRoute &&
                        (

                            (

                                (

                                    isset($myParams['controller']) && 
                                    !is_array($myParams['controller'])

                                ) ||

                                !isset($myParams['controller'])

                            ) &&
                            (

                                isset($myParams['action']) && 
                                is_array($myParams['action'])
                                
                            )

                        )

                    )
                    {

                        $actions = $myParams['action'];
                        unset($myParams['action']);

                        if
                        (

                            count(array_intersect_assoc($reqParams, $myParams)) == count($myParams) &&
                            in_array($reqParams['action'], $actions)

                        )
                            $matched = true;

                    }
                    else if
                    (

                        $matchedRoute &&
                        (

                            (

                                isset($myParams['controller']) &&
                                is_array($myParams['controller'])

                            ) &&
                            (

                                isset($myParams['action']) &&
                                is_array($myParams['action'])
                                
                            )

                        )

                    )
                    {

                        $controllers = $myParams['controller'];
                        $actions = $myParams['action'];
                        unset($myParams['controller']);
                        unset($myParams['action']);

                        if
                        (

                            count(array_intersect_assoc($reqParams, $myParams)) == count($myParams) &&
                            in_array($reqParams['controller'], $controllers) &&
                            in_array($reqParams['action'], $actions)

                        )
                            $matched = true;

                    }

                    if($matched)
                    {

                        $this->active = true;

                        return $this->active;

                    }
                    else
                        return AbstractPage::isActive($recursive);

                }

            }

            $myParams = $this->params;

            if (null !== $this->controller)
                $myParams['controller'] = $this->controller;
            else
                $myParams['controller'] = 'index';

            if (null !== $this->action)
                $myParams['action'] = $this->action;
            else
                $myParams['action'] = 'index';

            if (count(array_intersect_assoc($reqParams, $myParams)) == count($myParams))
            {

                $this->active = true;

                return true;

            }

        }

        return AbstractPage::isActive($recursive);
    }

    /**
     * Sets action name to use when assembling URL
     *
     * @see getHref()
     *
     * @param  string|array|null $action             action name
     * @return Mvc   fluent interface, returns self
     * @throws Exception\InvalidArgumentException  if invalid $action is given
     */
    public function setAction($action)
    {

        if (null !== $action && !is_string($action) && !is_array($action))
            throw new Exception\InvalidArgumentException
            (

                'Invalid argument: $action must be a string, an array or null'

            );

        $this->action    = $action;
        $this->hrefCache = null;

        return $this;
    }

    /**
     * Returns action name to use when assembling URL
     *
     * @see getHref()
     *
     * @return string|array|null  action name
     */
    public function getAction()
    {

        return $this->action;

    }

    /**
     * Sets controller name to use when assembling URL
     *
     * @see getHref()
     *
     * @param  string|array|null $controller    controller name
     * @return Mvc   fluent interface, returns self
     * @throws Exception\InvalidArgumentException  if invalid controller name is given
     */
    public function setController($controller)
    {
        if (null !== $controller && !is_string($controller) && !is_array($controller))
            throw new Exception\InvalidArgumentException
            (

                'Invalid argument: $controller must be a string or null'

            );

        $this->controller = $controller;
        $this->hrefCache  = null;

        return $this;
    }

    /**
     * Returns controller name to use when assembling URL
     *
     * @see getHref()
     *
     * @return string|array|null  controller name or null
     */
    public function getController()
    {

        return $this->controller;

    }

}
