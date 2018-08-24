<?php
namespace F3;

abstract class Controller extends \Prefab{

	protected $index='/';

	public function index(){

		\F3\Redirect::instance()->to($this->index);

	}

	protected function view($data=[]){

		$backtrace=debug_backtrace()[1];
		$callerClass=strtolower(str_replace('Controller', '', $backtrace['class']));
		$callerAction=$backtrace['function'];
		$ext='html';

		echo \F3\Twig::instance()->render("{$callerClass}/{$callerAction}.{$ext}", $data);

	}

	protected function middleware($name, $args=[]){

		$middleware=\F3\Middleware::instance();
		$params=isset($args['params'])?$args['params']:[];


		if(isset($args['only'])){

			if(in_array(action(), $args['only'])) $middleware->get($name, $params);

		}elseif(isset($args['except'])){

			if(!in_array(action(), $args['except'])) $middleware->get($name, $params);

		}else $middleware->get($name, $params);
				

	}

	protected function validate(array $rules){

		$v=validator(\F3\Input::instance()->all());

		$v->rules($rules);

		if(!$v->validate()){
			\F3\Redirect::instance()->with('validation_alert', ['danger'=>'Validation failed.'])->withInput($v->errors())->toBack();
		}

	}

}