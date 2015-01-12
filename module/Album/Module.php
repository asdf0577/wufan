<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Album for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Album;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resouce;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Album\Model\Album;
use Album\Model\AlbumTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Authentication\Result;
use Album\Model\ImageUploadTable;
use Album\Model\UserTable;
use Album\Model\UploadTable;
use Album\Model\User;

use Album\Model\StoreOrder;
use Album\Model\StoreProduct ;

//试卷
use Album\Model\ClassName; //班级
use Album\Model\ClassNameTable ;

use Album\Model\Student; //学生
use Album\Model\StudentTable ;

use Album\Model\Question;//试题
use Album\Model\QuestionTable ;

use Album\Model\QuestionType;//试题类型
use Album\Model\QuestionTypeTable;

use Album\Model\WrongQuestionClass;
use Album\Model\WrongQuestionClassTable;
use Album\Model\WrongQuestionUser;
use Album\Model\WrongQuestionUserTable;

use Album\Model\TestPaper;//试卷
use Album\Model\TestPaperTable ;
use Album\Model\TestPaperAcl;//试卷
use Album\Model\TestPaperAclTable ;

use Album\Model\Knowledge;//语法
use Album\Model\KnowledgeTable ;

//商店
use Album\Model\StoreOrderTable;
use Album\Model\StoreProductTable;


use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdaper;
use Zend\Captcha\Dumb;
class Module implements AutoloaderProviderInterface
{
    
    
    
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
		    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        //初始化ACL
        $this -> initAcl($e);
        //注册一个监听事件，初始化为guest身份，如果是guest，只能进入login页面  
//          $eventManager-> attach('route', array($this, 'checkAcl'));
        //验证登陆人的身份，如果未登录，直接到登陆界面 
        $authAdapter = $e->getApplication()->getServiceManager()->get('TestPaperAuthService()');
        if($authAdapter->hasIdentity() === true){
            //is logged in
//             如何返回
//             echo $authAdapter->getIdentity()->role;
//             echo "yes";
        }else{
//             $response -> getHeaders() -> addHeaderLine('Location', $e -> getRequest() -> getBaseUrl() . '/404');
            //             $response -> setStatusCode(303);
        }
        
        
        
        
        
        
        $sharedEventManager = $eventManager->getSharedManager();
        //attach(作用区域：比如一个人 ，当指定事件发生：当坐公交车时，发生后执行的函数：必须投币，优先值：1)
        $sharedEventManager->attach(__NAMESPACE__,MvcEvent::EVENT_DISPATCH,
                function ($e){
                    $controller = $e->getTarget();
                    $controllerName = $controller->getEvent()->getRouteMatch()->getParam('controller');
                    if (in_array($controllerName, array(
                        'Album\Controller\Register',
                        'Album\Controller\Usermanager',
                        'Album\Controller\Store',
                        'Album\Controller\StoreAdmin',
                        'Album\Controller\UploadManager',
                    ))) 
                    {
                    $controller->layout('layout/myaccount')	;
                    }
                     if(in_array($controllerName,array(
                        'Album\Controller\TestPaper', 
                        'Album\Controller\ClassManager',
                        'Album\Controller\Student',
                        'Album\Controller\Question',
                        'Album\Controller\Grammar',
                    ))){
                        $controller->layout('layout/testPaper')	;
                    } 
                        
                    
                }
    );  
      
    }
    
    public function initAcl(MvcEvent $e) {
    
        $acl = new Acl();
        $acl->addRole(new Role('guest'));
        $acl->addRole(new Role('student'),'guest');
        $acl->addRole(new Role('teacher'),'student');
        $acl->addRole(new Role('admin'),'teacher');
        
        //添加资源 也就是模块
        $acl->addResource('application');
        $acl->addResource('album');
        $acl->addResource('TestPaper');
        $acl->addResource('Logining');
        
        $acl->allow('admin');
        $acl->allow('student','TestPaper');
        $acl->allow('guest','TestPaper');
        $acl->allow('guest');
        
        //testing
//         var_dump($acl->isAllowed('guest','TestPaper','index'));
        //true
    
        //setting to view
        $e -> getViewModel() -> acl = $acl;
    
    }
    
    public function checkAcl(MvcEvent $e) {
        $route = $e -> getRouteMatch() -> getMatchedRouteName();
        var_dump($route);
        $userRole = 'guest';
    
        if (!$e -> getViewModel() -> acl -> isAllowed($userRole, $route)) {
            echo"notallow";
            $response = $e -> getResponse();
            var_dump($response);
            //location to page or what ever
//             $response -> getHeaders() -> addHeaderLine('Location', $e -> getRequest() -> getBaseUrl() . '/404');
//             $response -> setStatusCode(303);
    
        }
    }

    public function getServiceConfig(){
        return array(
        		'factories' => array(
        		    //table
        				'Album\Model\AlbumTable' =>  function($sm) {
        					$tableGateway = $sm->get('AlbumTableGateway');
        					//$table=new albumTable($sm->get('AlbumTableGateway')
        					$table = new AlbumTable($tableGateway);
        					return $table;
        				},
        				'AlbumTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					$resultSetPrototype = new ResultSet();
        					$resultSetPrototype->setArrayObjectPrototype(new Album());//set ����ע��
        					return new TableGateway('albums', $dbAdapter, null, $resultSetPrototype);
        				},
        				'Album\Model\UserTable'=>function ($sm){
        				    $tableGateway=$sm->get('UserTableGateway');
        				    $table =new UserTable($tableGateway);
        				    return $table;
        				},
        				
        				'UserTableGateway'=>function($sm){
        				    $dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
        				    $resultSetPrototype = new ResultSet();
        				    $resultSetPrototype->setArrayObjectPrototype(new User());
        				    return new TableGateway('user',$dbAdapter,null,$resultSetPrototype);
        				} ,
        				'Album\Model\ImageUploadTable'=>function ($sm){
        					$tableGateway=$sm->get('ImageUploadTableGateway');
        					$table =new ImageUploadTable($tableGateway);
        					return $table;
        				},
        				'ImageUploadTableGateway'=>function($sm){
        					$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('image_uploads',$dbAdapter);
        				} ,
        				'ChatMessageTableGateway'=>function($sm){ 
        				    $dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
        				    return new TableGateway('chat_message',$dbAdapter);
        				} ,
        				'Album\Model\UploadTable'=>function ($sm){
        					$tableGateway=$sm->get('UploadTableGateway');
        					$UploadsSharingtableGateway=$sm->get('UploadsSharingTableGateway');
        					$table =new UploadTable($tableGateway,$UploadsSharingtableGateway);
        					return $table;
        				},
        				'UploadTableGateway'=>function($sm){
        					$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('upload',$dbAdapter);
        				} ,
        				'UploadsSharingTableGateway'=>function($sm){
        					$dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('uploads_sharing',$dbAdapter);
        				} ,
        				
        				'ChatMessagesTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('chat_messages', $dbAdapter);
        				},
        				
        				'Album\Model\StoreProductTable' =>  function($sm) {
        					$tableGateway = $sm->get('StoreProductTableGateway');
        					$table = new StoreProductTable($tableGateway);
        					return $table;
        				},
        				'StoreProductTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					$resultSetPrototype = new ResultSet();
        					$resultSetPrototype->setArrayObjectPrototype(new StoreProduct());
        					return new TableGateway('store_products', $dbAdapter, null, $resultSetPrototype);
        				},
        				'Album\Model\StoreOrderTable' =>  function($sm) {
        					$tableGateway = $sm->get('StoreOrderTableGateway');
        					$productTableGateway = $sm->get('StoreProductTableGateway');
        					$table = new StoreOrderTable($tableGateway, $productTableGateway);
        					return $table;
        				},
        				'StoreOrderTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					$resultSetPrototype = new ResultSet();
        					$resultSetPrototype->setArrayObjectPrototype(new StoreOrder());
        					return new TableGateway('store_orders', $dbAdapter, null, $resultSetPrototype);
        				},
        				//考试管理系统
                        
        				'ClassNameTable' =>  function($sm) {//班級
        					$tableGateway = $sm->get('ClassNameTableGateway');
        					$table = new ClassNameTable($tableGateway);
        					return $table;
        				},
        				
        				'ClassNameTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('class_manager', $dbAdapter);
        				},
        				'QuestionTable' =>  function($sm) {//试题
        					$tableGateway = $sm->get('QuestionTableGateway');
        					$table = new QuestionTable($tableGateway);
        					return $table;
        				},
        				
        				'QuestionTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('question', $dbAdapter);
        				},
        				
        				//错题记录
        				
        				'WrongQuestionUserTable' =>  function($sm) {//试题
        					$tableGateway = $sm->get('WrongQuestionUserTableGateway');
        					$table = new WrongQuestionUserTable($tableGateway);
        					return $table;
        				},
        				
        				'WrongQuestionUserTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('wrong_question_user', $dbAdapter);
        				},
        				'WrongQuestionClassTable' =>  function($sm) {//试题
        					$tableGateway = $sm->get('WrongQuestionClassTableGateway');
        					$table = new WrongQuestionClassTable($tableGateway);
        					return $table;
        				},
        				
        				'WrongQuestionClassTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('wrong_question_class', $dbAdapter);
        				},
        				
        				
        				
        				
        				'QuestionTypeTable' =>  function($sm) {//题型
        					$tableGateway = $sm->get('QuestionTypeTableGateway');
        					$table = new QuestionTypeTable($tableGateway);
        					return $table;
        				},
        				
        				'QuestionTypeTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('question_type', $dbAdapter);
        				},
        				'TestPaperTable'=>function($sm){//试卷
        					$tableGateway = $sm->get('TestPaperTableGateway');
        					$table = new TestPaperTable($tableGateway);
        					return $table;
        				},
        				'TestPaperTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					/* $resultSetPrototype = new ResultSet();
        					$resultSetPrototype->setArrayObjectPrototype(new TestPaper()); */
        					return new TableGateway('test_paper', $dbAdapter/* , null, $resultSetPrototype */);
        				},
        				
        				'TestPaperAclTable'=>function($sm){//试卷
        					$tableGateway = $sm->get('TestPaperAclTableGateway');
        					$table = new TestPaperAclTable($tableGateway);
        					return $table;
        				},
        				'TestPaperAclTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					/* $resultSetPrototype = new ResultSet();
        					$resultSetPrototype->setArrayObjectPrototype(new TestPaperAcl()); */
        					return new TableGateway('test_paper_acl', $dbAdapter/* , null, $resultSetPrototype */);
        				},
        				'StudentTable' =>  function($sm) {//试题
        				$tableGateway = $sm->get('StudentTableGateway');
        				$table = new StudentTable($tableGateway);
        				return $table;
        				},
        				
        				'StudentTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('student', $dbAdapter);
        				},
        				'KnowledgeTable' =>  function($sm) {//试题
        				$tableGateway = $sm->get('KnowledgeTableGateway');
        				$table = new KnowledgeTable($tableGateway);
        				return $table;
        				},
        				
        				'KnowledgeTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('Knowledge', $dbAdapter);
        				},
        				
        				
        				//Form

        				'StudentForm'=>function($sm){
        					$form=new \Album\Form\StudentForm();
        					return $form;
        				},
        				
        				'RegisterForm'=>function($sm){
        				    $form=new \Album\Form\RegisterForm();
        				    $form->setInputFilter($sm->get('RegisterFilter'));
        				    return $form;
        				},
        				
        				'QuestionForm'=>function($sm){
        				    $form=new \Album\Form\QuestionForm();
        				    return $form;
        				}, 
        				'testPaperForm'=>function($sm){
        				    $form=new \Album\Form\TestPaperForm();
        				    return $form;//ע�� Ҫ���� 
        				}, 
        				'UserEditForm'=>function($sm){
        				    $form=new \Album\Form\UserEditForm();
        				    $form->setInputFilter($sm->get('UserEditFilter'));
        				    return $form;//ע�� Ҫ���� 
        				}, 
        				'UploadForm'=>function($sm){
        					$form=new \Album\Form\UploadForm();
        					//$form->setInputFilter($sm->get('UserEditFilter'));
        					return $form;//ע�� Ҫ����
        				},
        				'UploadEditForm' => function($sm){
        				    $form= new \Album\Form\UploadEditForm();
        				    return $form;
        				},
        				'InputQuestionForm' => function($sm){
        				    $form= new \Album\Form\InputQuestionForm();
        				    return $form;
        				},
        				
        				'ImageUploadForm' => function ($sm) {
        				    $form =new \Album\Form\ImageUploadForm();
        				    return $form;
        				    
        				},
        				'UploadShareForm'=>function($sm){
        					$form=new \Album\Form\UploadShareForm();
        					//$form->setInputFilter($sm->get('UserEditFilter'));
        					return $form;//ע�� Ҫ����
        				},
        				'OrderForm'=>function($sm){
        					$form=new \Album\Form\OrderForm();
        					//$form->setInputFilter($sm->get('UserEditFilter'));
        					return $form;//ע�� Ҫ����
        				},    
        				'ProductForm'=>function($sm){
        					$form=new \Album\Form\ProductForm();
        					//$form->setInputFilter($sm->get('UserEditFilter'));
        					return $form;//ע�� Ҫ����
        				},
        				
        				//Filter
        				
        				
            			'UploadFilter'=>function ($sm){
        					return new \Album\Form\UploadFilter();
        				},
        				
        			    'UserEditFilter'=>function($sm){
        			      return new \Album\Form\UserEditFilter();  
        			    },
        				'RegisterFilter'=>function ($sm){
        				  return new \Album\Form\registerFilter();  
            				},
            			'AuthService()'=>function ($sm){
        				 
        				    $dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
        				    $dbTableAuthAdapter = new DbTableAuthAdaper($dbAdapter,'user','email','password','MD5(?)');
        				    $authservice = new AuthenticationService();
        				    $authservice->setAdapter($dbTableAuthAdapter);
        				   return $authservice;
        				},
            			'TestPaperAuthService()'=>function ($sm){
        				 
        				    $dbAdapter=$sm->get('Zend\Db\Adapter\Adapter');
        				    $dbTableAuthAdapter = new DbTableAuthAdaper($dbAdapter,'student','name','password','MD5(?)');
        				    $authservice = new AuthenticationService();
        				    $authservice->setAdapter($dbTableAuthAdapter);
        				   return $authservice;
        				},
        		),
        );
        
    }

}
