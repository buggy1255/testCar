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
			    
			    <div class="full-container">
			        <div class="bxslider">
			        	
			            <?php
			            /** @var mysqli $db */
			            $GetSlider = mysqli_query($db, " SELECT * FROM ws_slider WHERE active = 1 ORDER BY sort DESC ");
						while( $Slider = mysqli_fetch_assoc($GetSlider) ){
			            ?>
                            <div>
                                <img alt="<?=$Slider['alt_'.$CCpu->lang]?>" title="<?=$Slider['foto_title_'.$CCpu->lang]?>" src="/upload/slider/<?=$Slider['image']?>" />
                            </div>
                            
                        <?}?>
                        
                    </div>
                    <div class="wr_sld_txt row">
                        
                        <div class="row">
                            <h1 class="sld_txt"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_OUR_CAR_YOUR_JOURNEY']?></h1>
                            
                            <div id="overPoint" class="wrap_res_form row">

                                <label class="label_form" for="select_pickup"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_PUNCTUL_DE_PRELUARE']?></label>
                                <div class="clear hidden-lg"></div>
                                
                                
                                <div class="space-row-10">
                                    <div class="col-xs-12 col-sm-8 col-lg-6 select-point">
                                        <div class="wrap_select">
                                            <div class="wrap_slcts">
                                                <select id="select_pickup">
                                                    <option value="0">&nbsp;</option>
                                                    <?
                                                    $GetOverPoint = mysqli_query($db, " SELECT id,title_".$CCpu->lang." AS title 
                                                    	FROM ws_take_over_point
                                                    	WHERE active = 1 ORDER BY sort DESC ");
													while( $OverPoint = mysqli_fetch_assoc( $GetOverPoint ) ){
														$sel = '';
														if(isset($_SESSION['reserve']['1']['overPoint'])){
                                                            if( $_SESSION['reserve']['1']['overPoint'] == $OverPoint['id'] ){
                                                                $sel = 'selected';
                                                            }
                                                        }
                                                    ?>
	                                                    <option <?=$sel?> value="<?=$OverPoint['id']?>">
	                                                    	<?=$OverPoint['title']?>
	                                                    </option>
	                                                <?}?>
	                                                   
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-2 col-lg-3 field-point">
                                        <input type="text" class="date_from datetimepicker dateClass" name="date_from"
                                        	value="<? echo isset($_SESSION['reserve']['1']['dateFrom']) ? $_SESSION['reserve']['1']['dateFrom'] : ''; ?>" onblur="sel_date( 'date_from' )" placeholder="dd/mm/yy"/>
                                    </div>
                                    <div class="col-xs-12 col-sm-2 col-lg-3 field-point">
                                        <input type="text" class="hour_form timepicker timeClass" name="hour_from"
                                        	value="<? echo isset($_SESSION['reserve']['1']['hourFrom']) ? $_SESSION['reserve']['1']['hourFrom'] : ''; ?>" placeholder="00:00"/>
                                    </div>
                                </div>

                            </div>
                            
                            <div id="returnPoint" class="wrap_res_form row">
                            	
                                <label class="label_form" for="select_return"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_THE_RETURN_POINT']?></label>
                                
                                <div class="clear hidden-lg"></div>
                                
                                <div class="space-row-10">
                                    <div class="col-xs-12 col-sm-8 col-lg-6 select-point">
                                        <div class="wrap_select">
                                            <div class="wrap_slcts">
                                            	
                                                <select id="select_return">
                                                    <option value="0">&nbsp;</option>
                                                    <?
                                                    $getReturnPoint = mysqli_query($db, " SELECT id,title_".$CCpu->lang." AS title FROM ws_return_point 
                                                    	WHERE active = 1 ORDER BY sort DESC ");
													while( $ReturnPoint = mysqli_fetch_assoc( $getReturnPoint ) ){
														$sel = '';
                                                        if(isset($_SESSION['reserve']['1']['returnPoint'])){
                                                            if( $_SESSION['reserve']['1']['returnPoint'] == $ReturnPoint['id'] ){
                                                                $sel = 'selected';
                                                            }
                                                        }
                                                    ?>
                                                    	<option <?=$sel?> value="<?=$ReturnPoint['id']?>">
                                                    		<?=$ReturnPoint['title']?>
                                                    	</option>
                                                    	
                                                    <? } ?>
                                                </select>
                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-2 col-lg-3 field-point dateClassBlock ">
                                        <input type="text" class="date_from dateClass" name="return_date_from"
                                        	value="<? echo isset($_SESSION['reserve']['1']['returnDateFrom']) ? $_SESSION['reserve']['1']['returnDateFrom'] : ''; ?>" disabled="" placeholder="-"/>
                                        
                                    </div>
                                    <div class="col-xs-12 col-sm-2 col-lg-3 field-point">
                                        <input type="text" class="hour_form timepicker timeClass" name="return_hour_from"
                                        	value="<? echo isset($_SESSION['reserve']['1']['returnHourFrom']) ? $_SESSION['reserve']['1']['returnHourFrom'] : ''; ?>" disabled="" placeholder="-"/>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="wrap_res_form row">
                                <label class="label_form" for="age"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_VVEDITE_VASH_VOZRAST']?></label>
                                <div class="clear hidden-lg"></div>
                                <div class="space-row-10">
                                    <div class="col-xs-4 col-sm-6">
                                        <input id="age" type="text" name="age" class="inp_form num-field" 
                                        value="<? echo isset($_SESSION['reserve']['1']['age']) ? $_SESSION['reserve']['1']['age'] : ''; ?>"
                                        maxlength="2" style="text-align: center" />
                                    </div>
                                    <div class="col-xs-8 col-sm-6">
                                        <div onclick="reserve1()" class="inp_btn_form">
                                            <div class="intp_form"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_SELECTEAZA_MODEL']?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
			    </div>
			    
			    <div class="container">
			         <div class="ant_zone">
    			        <h2 class="ant_title"><?=$page_data['title']?></h2>
    			        <?=$page_data['text']?>
    			    </div>
			    </div>
			    
			    <div class="container">
    		         <div class="auto_zone">
    		            <div class="space-row-20">
                            <?
                                $getElems = mysqli_query($db, "SELECT image, id, title_".$CCpu->lang." AS title
                                                         FROM ws_catalog_elements
                                                         WHERE active = 1 AND
                                                         on_main = 1
                                                         ORDER BY sort DESC");
                                while ($elems = mysqli_fetch_assoc($getElems)) {
                             ?>
                                <div class="col-xs-12 col-sm-6 at_bl">
                                    <a href="<?=$CCpu->writelink(27,$elems['id'])?>">
                                        <img class="img-responsive" src="/upload/cars/<?=$elems['image']?>" 
                                        	title="<?=$elems['title']?>" alt="<?=$elems['title']?>" width="580" height="384" style="width: 100%" />
                                        <span class="arrow_ctl">
                                            <span class="txt_ctl"><?=$elems['title']?></span>
                                        </span>
                                    </a>
                                </div>
                            <?}?>
                            <div class="clear"></div>
                        </div>
			        </div>
			        
			        <div class="txt_zone">
                        <?=$Main->GetPageIncData('HOME_TXT_BLOCK' , $CCpu->lang)?>
                    </div>
			       
			        
			    </div>
			</div>	
		</div>
		<?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/footer.php")?>
		
		<script type="text/javascript">
			function reserve1() {
				
				var a = 0;
				$('.required_field').removeClass('required_field');
				
				var overPoint = $.trim( $('#overPoint select').val() );
				var dateFrom = $.trim( $('#overPoint .date_from').val() );
				var hourFrom = $.trim( $('#overPoint .hour_form').val() );
				
				var returnPoint = $.trim( $('#returnPoint select').val() );
				var returnDateFrom = $.trim( $('#returnPoint .dateClass').val() );
				var returnHourFrom = $.trim( $('#returnPoint .hour_form').val() );
				
				var age = $.trim( $('#age').val() );
				
			    if( parseInt(overPoint) == 0 || parseInt(returnPoint) == 0 ){ 
			        show( '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_VYBERITE_PUNKT']?>' ); a++;
			    }
			    if( parseInt(age) == 0 || age == '' ){
			    	show( '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_VVEDITE_VASH_VOZRAST']?>' ); a++;
			    }
			   
			    if( returnDateFrom == '' || dateFrom == '' ){
			    	show( '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_VYBERITE_DATU']?>' );a++;
			    }

			    if(a>0){
			     
			      <?/* $('.required_field').first().focus();*/?>
			       return false;
			       
				 }else{
				 	
				 	loader();
			        
			        var fd = new FormData;
			        fd.append('task', 'reservedata1' );
			        fd.append('overPoint', overPoint );
			        fd.append('dateFrom', dateFrom );
			        fd.append('hourFrom', hourFrom );
			        fd.append('returnPoint', returnPoint );
			        fd.append('returnDateFrom', returnDateFrom );
			        fd.append('returnHourFrom', returnHourFrom );
			        fd.append('age', age );
			        fd.append('step', '1' );
			        
	 					$.ajax({
				            url: '<?=$defaultLinks['ajax']?>',
				            data: fd,
				            processData: false,
				            contentType: false,
				            type: 'POST',
				            success: function ( msg ) {
                                loader_destroy();

                                if ($.trim(msg) == 'ok') {
                                    location.href = "<?=$CCpu->writelink(48)?>";
                                } else {
                                    show(msg);
                                }
						      }
						 });
			   	 }
				
			}
		</script>
		
	</body>
</html>