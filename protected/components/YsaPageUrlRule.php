<?php
class YsaPageUrlRule extends CBaseUrlRule
{
    public $connectionID = 'db';
    
    protected $_route = 'page/view';
    
    public function createUrl($manager, $route, $params, $ampersand) 
    {
//        if ($route === $this->_route) {
//            
//        }
        return false;
    }
    
    public function parseUrl($manager, $request, $pathInfo, $rawPathInfo) 
    {
       $parts = explode('/', $pathInfo);

       if (1 == count($parts)) {
           $slugs = Page::getSlugs(Page::TYPE_GENERAL);
           if (in_array($parts[0], $slugs)) {
               $_GET['slug'] = $parts[0];
               return $this->_route;
           }
       }
       return false;
    }
}