<?php
use Phalcon\Mvc\View;
use Phalcon\DI\FactoryDefault;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\View\Engine\Volt as VoltEngine;
use Phalcon\Mvc\Model\Metadata\Memory as MetaData;
use Phalcon\Session\Adapter\Files as SessionAdapter;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Events\Manager as EventsManager;
/**
 * The FactoryDefault Dependency Injector automatically register the right services providing a full stack framework
 */
$di = new FactoryDefault();

define("APPLICATION_ENV","DEVELOPMENT");

	$di->set('dispatcher', function() {

    //Create an EventsManager
    $eventsManager = new EventsManager();

    //Attach a listener
    $eventsManager->attach("dispatch:beforeException", function($event, $dispatcher, $exception) {

        //Handle 404 exceptions
        if ($exception instanceof DispatchException) {
	    var_dump($exception);
		echo "here";
		//header("Location:http://www.google.co.uk");
		die();
            /*$dispatcher->forward(array(
                'controller' => 'index',
                'action' => 'show404'
            ));*/
            return false;
        }

    });

    $dispatcher = new \Phalcon\Mvc\Dispatcher();

    //Bind the EventsManager to the dispatcher
    $dispatcher->setEventsManager($eventsManager);

    return $dispatcher;

}, true);
	
	$di->set('view', function(){
		$view = new \Phalcon\Mvc\View();
		$view->setViewsDir('../app/views/');
	
			$view->registerEngines(array(
				'.volt' => function($view, $di) {
				$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
				$volt->setOptions(array(
				  'compiledPath' => '../app/compiled/',
				  'stat' => true,
				  'compileAlways' => true  
				));
				return $volt;
				}
				 ,  ".phtml" => 'Phalcon\Mvc\View\Engine\Volt'
			));
	
		return $view;
	});
	
     $di->set('flash', function() {
        $flash = new \Phalcon\Flash\Session([
            'error' => 'alert alert-danger',
            'success' => 'alert alert-success',
            'notice' => 'alert alert-info',
        ]);

        return $flash;
    });
     

    //Setup a base URI so that all generated URIs include the "tutorial" folder
    $di->set('url', function(){
        $url = new \Phalcon\Mvc\Url();
        $url->setBaseUri('/phalcon/');
        return $url;
    });

