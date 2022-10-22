<?php
namespace TencentCloud;

if ( strnatcasecmp(PHP_VERSION, '5.5') < 0 ) {
	exit('PHP version must be newer then 5.5');
}

spl_autoload_register(function($class){
	if(stripos($class, __NAMESPACE__.'\\') === 0){
		$f = str_replace('\\', '/', $class);
		$f = __DIR__.'/'.$f.'.php';
		if(is_file($f)){
			include_once($f);
		}
	}
});