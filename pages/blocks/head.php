	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5, user-scalable=no">
	<title><?=$page_data['page_title']?> | <?=$GLOBALS['ar_define_settings']['NAME_SITE']?></title>
	<meta name="keywords" content="<? echo (!empty($page_data['meta_k']) ? $page_data['meta_k'] : '')?>" />
	<meta name="description" content="<? echo (!empty($page_data['meta_d']) ? $page_data['meta_d'] : '')?>"/>
    <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/head_seo.php")?>
	<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="dns-prefetch" href="//fonts.googleapis.com" />
	<script type="text/javascript">
		var close__btn = '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_ZAKRYTI']?>';
	</script>
	
	<? include( $_SERVER['DOCUMENT_ROOT'] . '/pages/blocks/function_script.php' ) ?>
