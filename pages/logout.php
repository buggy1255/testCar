<?
	
	$Auth->logout();

	// ссылка на главную		
	header ("Location: " . $defaultLinks['index']);
    exit;