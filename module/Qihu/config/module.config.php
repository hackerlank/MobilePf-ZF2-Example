<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'Qihu\Controller\Index' => 'Qihu\Controller\IndexController', 
            'Qihu\Controller\User' => 'Qihu\Controller\UserController',
            'Qihu\Controller\Pay' => 'Qihu\Controller\PayController', 
        ),
    ),
    'router' => array(
        'routes' => array(
            'qihu' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/360',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'Qihu\Controller',
                        'action'        => 'index'
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // This route is a sane default when developing a module;
                    // as you solidify the routes for your module, however,
                    // you may want to remove it and replace it with more
                    // specific routes.
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[.php][/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Qihu' => __DIR__ . '/../view',
        ),
        'strategies' => array(
        	'ViewJsonStrategy', 
        ), 
    ),
    'mobilepf' => array(
        '360' => array(
            'app_id' => '201102011', 
            'app_key' => '12345678901234567890123456789012', 
            'app_secret' => '12345678901234567890123456789012', 
        ),
    ),
);
