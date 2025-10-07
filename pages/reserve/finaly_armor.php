<?
	// 6 - значит что все данные для резервации собраны
    if (!isset($_SESSION['reserve']) || count($_SESSION['reserve']) != 6) {
        header('Location:' . $defaultLinks['index']);
        exit;
    }

	$car_id = $_SESSION['reserve']['2']['elem_id'];
	$ArrOptionsId = make_explode($_SESSION['reserve']['3']['options']);

	//плашка продолжить резервацию
?>
<!DOCTYPE html>
<html lang="<?=$CCpu->lang?>">
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
                    <h1 class="ant_page f-head ">
                    	<?=$page_data['title']?>
                    </h1>
                    <div class="finaly_contetn">
                    	<div class="space-row-20">
	                    	<div class="col-md-5">
	                    		<div class="txt_zone">
	                    			<?=$page_data['text']?>
	                    		</div>
	                    	</div>

	                    	<?
	                    		//машина
		                    /** @var mysqli $db */
		                    $GetCarData = mysqli_query($db, " SELECT * 
	            					FROM ws_catalog_elements 
	            					WHERE id = '". $car_id ."' LIMIT 1 ");

								$CarData = mysqli_fetch_assoc( $GetCarData );


								//доп опции
								$ArrDopExp = array();
								$AddPrice = 0;

								foreach ( $ArrOptionsId as $k => $AddId ) {
									$GetAddData = mysqli_query($db, " SELECT * FROM ws_additional_services WHERE id = '". $AddId ."' LIMIT 1 ");
									$AddData = mysqli_fetch_assoc( $GetAddData );
									$ArrDopExp[$AddData['id']]['title'] = $AddData['title_'.$CCpu->lang];
									$ArrDopExp[$AddData['id']]['price'] = $AddData['price'];
								}

	            			?>

	                    	<div class="col-md-7">
	                    		<div class="table_auto">
	                    			<div class="row">
		                    			<div class="col-xs-12 col-sm-3 col-md-3">
		                    				<div class="top_line">&nbsp;</div>
		                    				<div class="select_car">
		                    					<img src="/upload/cars/<?=$CarData['image']?>"
		                    						title="<?=$CarData['title_'.$CCpu->lang]?>" alt="<?=$CarData['title_'.$CCpu->lang]?>"
		                    						class="img-responsive"/>
		                    					<? // <img src="/upload/armor/car1.jpg" alt="car1" class="img-responsive"/> ?>
		                    				</div>
		                    			</div>

		                    			<div class="col-xs-6 col-sm-4 col-md-4">
		                    				<div class="top_line"> <?=$GLOBALS['ar_define_langterms']['MSG_ALL_AUTOMOBIL']?> </div>
		                    				<div class="select_car">
                                                <span class="name_car"> <?=$CarData['title_'.$CCpu->lang]?> </span>

							                    <?

							                    if (!empty($ArrDopExp)) {
								                    foreach ($ArrDopExp as $id => $AddData) {
									                    ?><span class="detail_car"> <?= $AddData['title'] ?> </span><?
								                    }
							                    }
							                    ?>
		                    				</div>
		                    			</div>

		                    			<?
					                        $B = day_reserve($_SESSION['reserve']['2']['elem_id'], $_SESSION['reserve']['day_reserve']);
                                        ?>

		                    			<div class="col-xs-3 col-sm-2 col-md-2">
		                    				<div class="top_line"> <?=$GLOBALS['ar_define_langterms']['MSG_ALL_RATA_PENTRU_VEHICUL']?></div>
		                    				<div class="select_car">
		                    					<span class="price_day"> <?=$B['price_one_day']?> <span><?=get_ue()?></span></span>
		                    				</div>
		                    			</div>

		                    			<div class="col-xs-3 col-sm-3 col-md-3">
		                    				<div class="top_line"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_TOTAL']?></div>
		                    				<div class="select_car">
		                    					<span class="select_price main_price"> <?=$B['price']?> <span><?=get_ue()?></span></span>

		                    					<?

							                    if (!empty($ArrDopExp)) {
								                    foreach ($ArrDopExp as $id => $AddData) {

		                    					?>

			                    						<span class="select_price">
			                    							<?=get_price($AddData['price']*$_SESSION['reserve']['day_reserve'])?>
			                    							<? $AddPrice += get_price($AddData['price']*$_SESSION['reserve']['day_reserve']); ?>
			                    							<span> <?=get_ue()?> </span>
			                    						</span>
			                    				<?
													}

		                    					}
		                    					?>
		                    				</div>
		                    			</div>
	                    			</div>

	                    			<span class="bottom_line"></span>
	                    			<div class="row">
	                    				<div class="hidden-xs col-sm-3 col-md-3"></div>
	                    				<div class="col-xs-7 col-sm-4 col-md-4">
	                    					<div class="calc_price">
	                    						<span class="type_price"><?=$GLOBALS['ar_define_langterms']['MSG_TOTAL_PRICE_CAR']?></span>
	                    						<span class="type_price"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_OPTIUNI_ADITIONALE']?></span>
	                    						<span class="type_price"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DEPOSIT']?></span>
	                    						<span class="type_price"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_RATA_DE_REDUCERE']?></span>
	                    						<span class="total_calc"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_TOTAL']?>:</span>
	                    					</div>
	                    				</div>

	                    				<div class="col-xs-2 col-sm-2 col-md-2"></div>
	                    				<div class="col-xs-3 col-sm-3 col-md-3">
	                    					<div class="total_price">
	                    						<?
	                    						    $totalPrice = 0;
	                    						?>
	                    						<? $totalPrice += $B['price']; ?>
	                    						<span class="list_price"><span> <?=$B['price']?>  </span> <?=get_ue()?> </span>

	                    						<? $totalPrice += $AddPrice; ?>
	                    						<span class="list_price"><span> <?=$AddPrice?> </span> <?=get_ue()?> </span>

                                                <?
                                                $CarData['deposit'] = get_price($CarData['deposit']);
                                                $totalPrice += $CarData['deposit'];
                                                ?>
                                                <span class="list_price"><span> <?=$CarData['deposit']?> </span> <?=get_ue()?> </span>

	                    						<? $totalPrice -= $B['discount']; ?>

	                    						<span class="list_price"><span> -<?=(int)$B['discount']?> </span>  <?=get_ue()?> </span>
	                    						<span class="sum_price"> <?=$totalPrice?> <span> <?=get_ue()?> </span></span>
	                    					</div>
	                    				</div>
	                    			</div>
	                    		</div>
	                    	</div>
                    	</div>
                    </div>
                    <?
                  		//чистка
                        $_SESSION['reserve']='';
                        unset($_SESSION['reserve']);
					?>

                    <div class="txt_zone">
                    	<?=$Main->GetPageIncData('FINAL_PAGE_BOTTOM_TEXT' , $CCpu->lang)?>
                    </div>
                </div>
            </div>
        </div>
        <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/footer.php")?>
    </body>
</html>