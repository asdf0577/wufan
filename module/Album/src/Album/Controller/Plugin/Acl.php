<?php 
namespace Album\Controller\Plugin;
//引用插件
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
// 引用session
use Zend\Session\Container as SessionContainer;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resouse;

class MyAclPlugin extends AbstractPlugin {

    protected $sessioncontainer;
    
    private function getSessionContainer(){
        if(!$this->sessioncontainer){
            $this->sessioncontainer = new SessionContainer('zf');
        }
        return $this->sessioncontainer;
    }
    
    public function doAuthrization($e){
        $acl = new Acl();
        $roleGuest = new Role('guest');
        $acl->addRole($roleGuest);
        $acl->addRole(new Role('student'),$roleGuest);
        $acl->addRole(new Role('teacher'),'student');
        $acl->addRole(new Role('admin'),'teacher');
        
        //添加资源 也就是模块
        $acl->addResource('application');
        $acl->addResource('album'); 
        
        $acl->allow('admin');
        $acl->allow('student','album','testPaper:index');
        
        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        //从路由中得到的$conrollerClass返回moduleName，并转化为小写
        $moduleName = strtolower($controllerClass,0,strpos($controllerClass, '\\'));
        $role = (!$this->getSessionContainer()->role)?'guest':$this->getSessionContainer()->role;
        $routeMatch = $e->getRouteMatch();
        $actionName = strtolower($routeMatch->getParam())
        
        
    }
  

}




?>