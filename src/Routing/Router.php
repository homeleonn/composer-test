<?php

namespace Jump\Routing;

use ReflectionMethod;

class Router
{
	private $routes = [
		'' => 'TestController@index2',
		'test2/(\d+)' => 'TestController@index1',
	];
	public $uri;
	
	public function __construct($baseUrl)
	{
		$this->uri = trim(
			explode(
				explode($_SERVER['HTTP_HOST'], $baseUrl)[1],
				explode('?', $_SERVER['REQUEST_URI'])[0]
			)[1],
			'/'
		);
	}
	
	public function run()
	{
		$routeFound = false;
		foreach ($this->routes as $pattern => $route) {
			if (preg_match("~^{$pattern}$~", $this->uri, $params)) {
				unset($params[0]);
				
				[$controller, $action] 	= explode('@', $route);
				
				$controller = '\\App\Controllers\\' . $controller;
				$reflection = new ReflectionMethod($controller, $action);
				
				foreach ($reflection->getParameters() as $param) {
					if ($param->hasType()) {
						$type = $param->getType()->getName();
						array_unshift($params, new $type());
					}
				}
				
				call_user_func_array([new $controller, $action], $params);
				
				$routeFound = true;
				break;
			}
		}
		
		if (!$routeFound) {
			var_dump(404);
		}
	}
}