<?php

class Bag {
	
	public static function d($var1, $mode = 0)
	{
		echo '<pre>';
		if($mode === 1)
			var_dump($var1);
		else
			print_r($var1);
		die();
	}

}