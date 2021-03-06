<?php
use Zend\Mvc\Router\Http\Segment;
return array(
    'controllers' => array(
        'invokables' => array(
            'Album\Controller\Album' => 'Album\Controller\AlbumController',
            'Album\Controller\Admin' => 'Album\Controller\AdminController', 
        	'Album\Controller\Register' => 'Album\Controller\RegisterController',
        	'Album\Controller\Login' => 'Album\Controller\LoginController',
        	'Album\Controller\Usermanager' => 'Album\Controller\UsermanagerController',
        	'Album\Controller\Uploadmanager' => 'Album\Controller\UploadmanagerController',
        	'Album\Controller\Groupchat' => 'Album\Controller\GroupchatController',
            'Album\Controller\Media' => 'Album\Controller\MediaController',
            'Album\Controller\Search' => 'Album\Controller\SearchController',
            'Album\Controller\Store' => 'Album\Controller\StoreController',
            'Album\Controller\Storeadmin' => 'Album\Controller\StoreadminController',
            //试卷分析
            'Album\Controller\TestPaper' => 'Album\Controller\TestPaper\TestPaperController',
            'Album\Controller\ClassManager' => 'Album\Controller\TestPaper\ClassManagerController',
            'Album\Controller\Student' => 'Album\Controller\TestPaper\StudentController',
            'Album\Controller\Question' => 'Album\Controller\TestPaper\QuestionController',
            'Album\Controller\Knowledge' => 'Album\Controller\TestPaper\KnowledgeController',
            'Album\Controller\Logining' => 'Album\Controller\TestPaper\LoginingController',
            //洗车 不做了
            'Album\Controller\CarWash' => 'Album\Controller\CarWashController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'album' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/album[/:action]',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Album\Controller',
                        'controller'    => 'Album',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'login'  => array(
                    		'type' => 'Segment',
                    		'options' => array(
                    				'route'    => '/login[/:action]',
                    		    'constraints'=>array(
                    		    		'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
                    		    ),
                    				'defaults' => array(
                    				    'controller'    => 'Album\Controller\Login',
                    						'action'     => 'index',
                    				),
                    		),
                    ),
                    'register'  => array(
                    		'type' => 'Literal',
                    		'options' => array(
                    				'route'    => '/register',
                    				'defaults' => array(
                    				    'controller'    => 'Register',
                    						'action'     => 'index',
                    				),
                    		),
                    ),
                  ),
                ),
         
            
            'usermanager'=>array( 
            		'type'=>'Segment',
            		'options'=>array(
            				'route'=>'/album/usermanager[/:action[/:id]]',
            				'constraints'=>array(
            						'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
            						'id'=>'[a-zA-Z0-9_-]*',
            				),
            				'defaults'=>array(
            						'controller' => 'Album\Controller\Usermanager',
            						'action' => 'index',)
            		)
            ),
             'uploadmanager'=>array(
            		'type'=>'Segment',
            		'options'=>array(
            				'route'=>'/album/UploadManager[/:action[/:id]]',
            				'constraints'=>array(
            						'action'=>'[a-zA-Z][a-zA-Z0-9_-]*',
            						'id'=>'[a-zA-Z0-9_-]*',
            				),
            				'defaults'=>array(
            						'controller' => 'Album\Controller\UploadManager',
            						'action' => 'index',)
            		)
            ), 
            'groupchat' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/Album/groupchat[/:action[/:id]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\GroupChat',
            						'action' => 'index',
            				),
            		),
            ),
            'media' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/album/media[/:action[/:id[/:subaction]]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[a-zA-Z0-9_-]*',
            						'subaction' => '[a-zA-Z][a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\Media',
            						'action' => 'index',
            				),
            		),
            ),
            'store' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/album/store[/:action[/:id[/:subaction]]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[a-zA-Z0-9_-]*',
            						'subaction' => '[a-zA-Z][a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\Store',
            						'action' => 'index',
            				),
            		),
            ),
            'search' => array(
            		'type'    => 'Segment',
            		'options' => array(
            				'route'    => '/album/search[/:action]',
            				'constraints' => array(
            						'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'album\Controller\Search',
            						'action'     => 'index',
            				),
            		),
            ),
            'Storeadmin' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/album/storeadmin[/:action[/:id[/:subaction]]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[a-zA-Z0-9_-]*',
            						'subaction' => '[a-zA-Z][a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\Storeadmin',
            						'action' => 'index',
            				),
            		),
            ),
            'TestPaper' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/album/testpaper[/:action[/:id]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\TestPaper',
            						'action' => 'index',
            				),
            		),
            ),
            'Question' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/album/question[/:action[&tid=:tid][&cid=:cid]]',
//             				'route' => '/album/question[/:action[/cid=:cid]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'tid' => '[a-zA-Z0-9_-]*',
            						'cid' => '[a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\Question',
            						'action' => 'index',
            				),
            		),
            ),
            
            'ClassManager' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/album/class-manager[/:action[/:id]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\ClassManager',
            						'action' => 'index',
            				),
            		),
            ),
            'Student' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/album/Student[/:action[/:id]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\Student',
            						'action' => 'index',
            				),
            		),
            ),
            'Knowledge' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/album/knowledge[/:action[/:id]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\Knowledge',
            						'action' => 'index',
            				),
            		),
            ),
            'Logining' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/album/logining[/:action[/:id]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\Logining',
            						'action' => 'index',
            				),
            		),
            ),
            'CarWash' => array(
            		'type' => 'Segment',
            		'options' => array(
            				'route' => '/album/carwash[/:action[/:id]]',
            				'constraints' => array(
            						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            						'id' => '[a-zA-Z0-9_-]*',
            				),
            				'defaults' => array(
            						'controller' => 'Album\Controller\CarWash',
            						'action' => 'index',
            				),
            		),
            ),
        		),
        						),
   
    'view_manager' => array(
        'strategies' => array(
        		'ViewJsonStrategy',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array(
        	'layout/myaccount' => __DIR__.'/../view/layout/myaccount-layout.phtml',
        	'layout/testPaper' => __DIR__.'/../view/layout/testPaper-layout.phtml',
        	'layout/testPaper2' => __DIR__.'/../view/layout/testPaper2-layout.phtml',
        ),
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
    ),
    
    'module_config' => array(
    		'upload_location' => __DIR__ . '/../data',
            'image_upload_location' => __DIR__ .'/../data',
            'search_index' => __DIR__.'/../data',
    ),
    'service_manager'=>array(
    ),
   
);
