<?php
	/** @var mysqli $db */
	date_default_timezone_set('Europe/Chisinau');
	include($_SERVER['DOCUMENT_ROOT']."/ws/include.php");
	//защита от иньекций
	include($_SERVER['DOCUMENT_ROOT']."/ws/view_access/access.php");

	error_reporting(0);

	/** @var CCpu $CCpu */
	$CCpu->inject();

    if (!isset($_SESSION['var_change'])) {
        $_SESSION['var_change'] = 3;
    }

    // Social Auth (by default this is used by smartphones)
    if (!empty($_GET['ulogin_callback']) && is_string($_GET['ulogin_callback']) && $_GET['ulogin_callback'] === 'register_social'){
        if (!empty($_GET['ulogin_token']) && is_string($_GET['ulogin_token']) && preg_match('/^[a-z0-9]{10,32}$/', $_GET['ulogin_token'])) {
            $answ = strpos($_SERVER['REQUEST_URI'], '?', 1);
            $addr = substr($_SERVER['REQUEST_URI'], 0, $answ);

            try{
                $Auth->socialAuth($_GET['ulogin_token']);

                header("Location: " . $addr);
                exit;
            }
            catch (Exception $e) {
                $show_error = $e->getMessage();
            }
        }
    }

    function ob_html_compress($buf)
    {
        $pattern = '/(?:(?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:(?<!\:|\\\|\')\/\/.*))/';

        return str_replace(array("    ", "   ", "  "), ' ', str_replace(array("\n", "\r", "\t"), '', $buf));
    }
	
	//if( $_SERVER['REMOTE_ADDR'] != '127.0.0.1' ){
		//ob_start('ob_html_compress'); 
	//}
	
	$pageData = $CCpu->GetCPU();
	if(!$pageData){
		header('HTTP/1.0 404 Not Found');
		header('Content-Type: text/html; charset=utf-8');
		include($_SERVER['DOCUMENT_ROOT']."/pages/404.php");
		exit;
	}elseif($pageData==301){
		header('HTTP/1.1 301 Moved Permanently');
		$addr = substr($_SERVER['REQUEST_URI'], 0, (strlen($_SERVER['REQUEST_URI'])-1));
		header("Location: ".$addr);
		exit;
	}
	
	$Main->lang = $CCpu->lang; 
	$GLOBALS['ar_define_langterms'] = $Main->GetDefineLangTerms();
	$page_data = $CCpu->GetPageData($pageData);

	if(!$page_data){
		header('HTTP/1.0 404 Not Found');
	    header('Content-Type: text/html; charset=utf-8');
	    include($_SERVER['DOCUMENT_ROOT']."/pages/404.php");
	    exit;
	}
	
	$defaultLinks = array();
	
	$defaultLinks['ajax'] = $CCpu->writelink( 3 );
	$defaultLinks['index'] = $CCpu->writelink( 1 );

    // список обозначений для месяцев
    if (!isset($_SESSION['months'][$CCpu->lang])) {
        $getMonths = mysqli_query($db, " SELECT id, title_" . $CCpu->lang . " AS title FROM ws_months ");
        while ($M = mysqli_fetch_assoc($getMonths)) {
            $_SESSION['months'][$CCpu->lang][$M['id']] = $M['title'];
        }
    }

	$monthsList = $_SESSION['months'][$CCpu->lang];
	
	mb_internal_encoding("UTF-8");
	header('Content-Type: text/html; charset=utf-8');
	
	//$page_data = $CCpu->GetPageData($pageData);
	
	$_SESSION['last_lang'] = $CCpu->lang;
	
	include($_SERVER['DOCUMENT_ROOT']."/pages".$pageData['page']);  
	exit();
	
	ob_end_flush();
