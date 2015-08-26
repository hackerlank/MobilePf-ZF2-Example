<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'W91\Controller\Index' => 'W91\Controller\IndexController', 
            'W91\Controller\User' => 'W91\Controller\UserController', 
            'W91\Controller\Pay' => 'W91\Controller\PayController', 
        ),
    ),
    'router' => array(
        'routes' => array(
            'w91' => array(
                'type'    => 'Literal',
                'options' => array(
                    // Change this to something specific to your module
                    'route'    => '/91',
                    'defaults' => array(
                        // Change this value to reflect the namespace in which
                        // the controllers for your module are found
                        '__NAMESPACE__' => 'W91\Controller',
                        'action'        => 'index',
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
            'W91' => __DIR__ . '/../view',
        ),
        'strategies' => array(
        		'ViewJsonStrategy',
        ),
    ),
    'mobilepf' => array(
    	'91' => array(
            'app_id' => '100010', 
    	    'app_key' => 'C28454605B9312157C2F76F27A9BCA2349434E546A6E9C75',
        ),
    ),
);
