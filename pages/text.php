<?
	/*if( $page_data['id'] == 55 ){
		$_SESSION['reserve']='';unset( $_SESSION['reserve'] );
	}*/
?>

<!DOCTYPE html>
<html lang="<?=$CCpu->lang?>" >
    <head>
        <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/head.php")?>
    </head>
    <body>
        <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/whitefog.php")?>
        <div id="content">
            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/menu.php")?>
            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/social.php")?>
            
            <div id="page">
                
                <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/mobile_menu.php")?>
                
                
                <div class="container">
                    <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/breadcrumbs.php")?>
                    
                    <h1 class="ant_page"><?=$page_data['title']?></h1>
                    <div class="txt_zone"><?=$page_data['text']?></div>
                </div>
            </div>
        </div>
        <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/footer.php")?>
    </body>
</html>