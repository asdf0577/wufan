<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Album for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Album;

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
use Album\Model\Question;
use Album\Model\QuestionTable ;

use Album\Model\QuestionType;
use Album\Model\QuestionTypeTable;

use Album\Model\TestPaper;
use Album\Model\TestPaperTable ;

use Album\Model\StoreOrderTable;
use Album\Model\StoreProductTable;


use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdaper;
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
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $sharedEventManager = $eventManager->getSharedManager();
        $sharedEventManager->attach(__NAMESPACE__,MvcEvent::EVENT_DISPATCH,
                function ($e){
                    $controller = $e->getTarget();
                    $controllerName = $controller->getEvent()->getRouteMatch()->getParam('controller');//�� ȡ��ǰ·�ɵĿ��������
                    if (!in_array($controllerName, array('Album\Controller\Register','Album\Controller\Usermanager','Album\Controller\Usermanager',))) 
                    {
                    $controller->layout('layout/myaccount')	;
                    }//���ǰ�Ŀ�������Ʋ���Register��Login��UserManager����У���ô���õ�ǰ��������layoutģ��Ϊmyaccount
                    
                }
    ); 
        
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
        				
        				
        				'QuestionTable' =>  function($sm) {
        					$tableGateway = $sm->get('QuestionTableGateway');
        					$table = new QuestionTable($tableGateway);
        					return $table;
        				},
        				
        				'QuestionTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('question', $dbAdapter);
        				},
        				'QuestionTypeTable' =>  function($sm) {
        					$tableGateway = $sm->get('QuestionTypeTableGateway');
        					$table = new QuestionTypeTable($tableGateway);
        					return $table;
        				},
        				
        				'QuestionTypeTableGateway' => function ($sm) {
        					$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
        					return new TableGateway('question_type', $dbAdapter);
        				},
        				'TestPaperTable'=>function($sm){
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
        				
        				
        				//Form
        				
        				
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
        		),
        );
        
    }

}
