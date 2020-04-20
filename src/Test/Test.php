<?php

namespace Test;

class Router
{
	private $routes = [
		'' => 'test@index2',
		'test2/(\d+)' => 'test@index1',
	];
	public $uri;
	
	public function __construct()
	{
		$this->uri = trim(explode('?', $_SERVER['REQUEST_URI'])[0], '/');
	}
	
	public function run()
	{
		$routeFound = false;
		
		foreach ($this->routes as $pattern => $route) {
			if (preg_match("~^{$pattern}$~", $this->uri, $params)) {
				unset($params[0]);
				[$controller, $action] = explode('@', $route);
				$controller = ucfirst(strtolower($controller)) . 'Controller';
				var_dump($controller, $action);exit;
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