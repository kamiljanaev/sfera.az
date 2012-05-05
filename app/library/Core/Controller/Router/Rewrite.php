<?php
class Core_Controller_Router_Rewrite extends Zend_Controller_Router_Rewrite
{
    private $_urlDelimiter = '/';
    private static $_defaultRouteCache;

    public function route(Zend_Controller_Request_Abstract $request)
    {
        $this->_getRoute($request);
        return $request;
    }

    public static function setDefaultRouteCache($routeCache = null)
    {
        self::$_defaultRouteCache = self::_setupRouteCache($routeCache);
    }

    public static function getDefaultRouteCache()
    {
        return self::$_defaultRouteCache;
    }

    protected static function _setupRouteCache($routeCache)
    {
        return null;
        if ($routeCache === null) {
            return null;
        }
        if (is_string($routeCache)) {
            $routeCache = Zend_Registry::get($routeCache);
        }
        if (!$routeCache instanceof Zend_Cache_Core) {
            throw new Exception('Argument must be of type Zend_Cache_Core, or a Registry key where a Zend_Cache_Core object is stored');
        }
        return $routeCache;
    }

    private function _getRoute($request)
    {
        if (null !== self::$_defaultRouteCache) {
            $cacheId = md5($request->getPathInfo());
        }
        $routeName = '';
        if (null !== self::$_defaultRouteCache && !($routeName = self::$_defaultRouteCache->load($cacheId))) {
            $this->_searchRoute($request);
        }
        else {
            if ($routeName != '') {
                try {
                    $route = $this->getRoute($routeName);
                    if (!method_exists($route, 'getVersion') || $route->getVersion() == 1) {
                        $match = $request->getPathInfo();
                    } else {
                        $match = $request;
                    }
                    if ($params = $route->match($match)) {
                        $this->_setRequestParams($request, $params);
                        $this->_currentRoute = $routeName;
                    }
                    else {
                        $this->_searchRoute($request);
                    }
                }
                catch(Exception $ex) {
                    $this->_searchRoute($request);
                }
            }
            else {
                $this->_searchRoute($request);
            }
        }
    }

    private function _searchRoute($request)
    {
        parent::route($request);
        $route = $this->getCurrentRoute();
        $routeName = $this->getCurrentRouteName();
        if (null !== self::$_defaultRouteCache) {
            $cacheId = md5($request->getPathInfo());
            if (!self::$_defaultRouteCache->save($routeName, $cacheId)) {
                throw new Exception('Failed saving routeInfo to Cache');
            }
        }
    }
}