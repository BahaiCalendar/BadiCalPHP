<?php
	function BadiCalPHP_autoload ($class){
		$parts = explode("\\", $class);
		if(strpos($parts[0], 'BahaiCalendar') !== false && strpos($parts[1], 'BadiCal') !== false){
			$path = __DIR__.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.$parts[2].'.php';
			if(file_exists($path)){
				return include $path;
			}
		}
		return false;
	}
	
	spl_autoload_register('BadiCalPHP_autoload');
?>