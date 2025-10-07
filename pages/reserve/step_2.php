<?
unset($_SESSION['reserve']['2']['elem_id']);

if (!isset($_SESSION['reserve']['1']) || empty($_SESSION['reserve']['1'])) {
	header('Location:' . $defaultLinks['index']);
    exit;
}
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
            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/reserve.php")?>
            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/social.php")?>
            <div id="page">
            	<?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/mobile_menu.php")?>
                <div class="container container_step_page ">
                    <div class="breadcrumbs">
                         <a class="bread" href="<?=$defaultLinks['index']?>"> <?=$GLOBALS['ar_define_langterms']['MSG_ALL_HOME_PAGE']?> </a>
                         <a class="bread" href="<?=$CCpu->writelink( 47 )?>"> <?=$CCpu->writetitle( 47 )?> </a>
                         <a class="act_bread" href="<?=$pageData['cpu']?>"> <?=$page_data['title']?> </a>
                    </div>
                    <h1 class="ant_page"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_ALEGEREA_AUTOMOBILULUI']?></h1>
                    <div class="txt_zone">
                        <div class="container">
                            <?=$page_data['text']?>
                        </div>
                    </div>
                    <div class="container">
                    	<div class="space-row-30">
                        <?
							$dateFrom = explode( '/' , $_SESSION['reserve']['1']['dateFrom'] );
							$dateFrom = $dateFrom['2'] . '-' . $dateFrom['1'] . '-' . $dateFrom['0'];
							$timeFrom = strtotime( $dateFrom );

							$dateReturn = explode( '/' , $_SESSION['reserve']['1']['returnDateFrom'] );
							$dateReturn = $dateReturn['2'] . '-' . $dateReturn['1'] . '-' . $dateReturn['0'];
							$timeReturn = strtotime( $dateReturn );

	                        $hourFrom = $_SESSION['reserve']['1']['hourFrom'];
	                        $hourReturn = $_SESSION['reserve']['1']['returnHourFrom'];

                            $t = get_days($timeFrom, $timeReturn, $hourFrom, $hourReturn);
							$_SESSION['reserve']['day_reserve'] = $t;

	                        /** @var mysqli $db */
	                        $GetElems = mysqli_query($db, " SELECT image , title_".$CCpu->lang." AS title, price, id, driver
								FROM ws_catalog_elements 
								WHERE active = 1 AND section_id <> 5
								ORDER BY sort DESC");

                        	while( $Elem = mysqli_fetch_assoc( $GetElems ) ){
                        ?> 

                               <div class="col-sm-12 col-md-6 col-xs-12 wrap_step_2">
                               	   <div class="step2_cars_block step_car_blocks ">
	                                   <a href="<?=$CCpu->writelink( 27 , $Elem['id'] )?>" class="wrap_cat">
	                                        <img title="<?=$Elem['title']?>" alt="<?=$Elem['title']?>" class="img-responsive" src="/upload/cars/<?=$Elem['image']?>" />
	                                        <div class="col-lg-12 row">
		                                        <span class="col-xs-9 wrap_tit_cat">
		                                            <span class="tit_cat">
		                                            	<?=$Elem['title']?>
		                                            </span>
		                                        </span>

		                                        <span class="col-xs-3 info_aut_cat">
		                                        	<?
														$DataRatin = rating_product( $Elem['id'] );
													?>

		                                            <span class="rat_cars">
		                                                <?$s=$DataRatin['count_nf'];for($i=5;$i>0;$i-- ){?>

			                                        		<?if( $s > 0 ){?>
			                                        			<img src="/images/elements/red_star.svg" />
			                                        		<?}else{?>
			                                        			<img src="/images/elements/grey_star.svg" />
			                                        		<?}?>
			                                        	<?$s--;}?>
		                                            </span>
		                                            <span class="price_info">
		                                            	<?
		                                            		$ArrPric = day_reserve( $Elem['id'] , $t );
		                                            	?>
		                                                <span class="price_cat"><?=$ArrPric['price']?></span>
		                                                <span class="valute_cat"> <?=get_ue()?> </span>
		                                            </span>
		                                            <span class="see_more">
		                                                <span class="more_txt"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_MAI_MULTE']?></span>
		                                            </span>
		                                        </span>
	                                        </div>
	                                    </a>

	                                    <?if($Elem['driver']==1){?>
	                                    	<div class="col-xs-12 col-sm-9 col-md-9 step_2_driver"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_CU_SOFER']?></div>
	                                    <?}else{?>
	                                    	<div style="margin-bottom: 20px;" class="col-xs-12 col-sm-9 col-md-9 col-xs-9 "> &nbsp; </div>
	                                    <?}?>
	                                    <div onclick="reserve2( '<?=$Elem['id']?>' )" class="col-xs-12 col-sm-3 btn_next_step3 elem_<?=$Elem['id']?> ">
	                                        <div class="intp_form"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_SELECTEAZA']?></div>
	                                    </div>
                                    </div>
                               </div>
                            <?}?>
                            </div>
                           <div class="clear"></div>
                    </div>
                </div>
            </div>
            </div>
        <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/footer.php")?>

        <script>
            $(function(){
                var height = $(window).height();
                $('.reserve_form').css({"min-height":height+'px'});
            });

			function reserve2( elem_id ) {
				var a = 0;

			    if(a>0){
			       return false;
				 }else{
				 	loader();
			        var fd = new FormData;

			        fd.append('task', 'reservedata2' );
			        fd.append('elem_id', elem_id );
			        fd.append('step', '2' );

	 					$.ajax({
				            url: '<?=$defaultLinks['ajax']?>',
				            data: fd,
				            processData: false,
				            contentType: false,
				            type: 'POST',
				            success: function ( msg ) {
				              	loader_destroy();

				                 if( $.trim(msg) == 'ok' ){
				                   	 location.href="<?=$CCpu->writelink( 49 )?>";
				                  }else{
				                     show( msg );
						          }
						      }
						 });
			   	 }
			}
		</script>

        <?if( isset( $_GET['m'] ) && (int)$_GET['m'] > 0 ){?>

        	<script type="text/javascript">
				 $(function(){
				 	$( '.elem_'+<?=(int)$_GET['m']?> ).trigger( "click" );
				 });
			</script>
        <?}?>
    </body>
</html>