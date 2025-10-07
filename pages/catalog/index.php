<!DOCTYPE html>

<html lang="<?=$CCpu->lang?>" >
    <head>
        <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/head.php")?>
    </head>
    <body>
        <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/whitefog.php")?>
        <div id="content">
            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/menu.php")?>
            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/filter.php")?>
            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/social.php")?>
            <div id="page">
                <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/mobile_menu.php")?>
				<?php
				/** @var mysqli $db */
				$GetText = mysqli_query($db, " SELECT bottom_text_". $CCpu->lang ." AS bottom_text 
						FROM ws_catalog 
						WHERE id = '". $page_data['id'] ."' ");
					$Text = mysqli_fetch_assoc( $GetText );
				?>
                <div class="container">
                    <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/breadcrumbs.php")?>
                    <h1 class="ant_page">
                    	<?=$page_data['title']?>
                    </h1>
                    <div class="txt_zone">
                        <?=$page_data['text']?>
                    </div>
                    <div class="space-row-20">
                            <?
                            	if(!mysqli_num_rows($GetElems)){
                            	?> <?=$GLOBALS['ar_define_langterms']['MSG_ALL_NICHEGO_NE_NAJDENO']?> <?
                            	}else{
                            	// filter.php
								while( $Elem = mysqli_fetch_assoc( $GetElems ) ){
                            ?>
                            	<div class="col-sm-12 col-md-6">
	                               <a href="<?=$CCpu->writelink( 27 , $Elem['id'] )?>" class=" wrap_cat">
	                                    <img title="<?=$Elem['title']?>" alt="<?=$Elem['title']?>" class="img-responsive" src="/upload/cars/<?=$Elem['image']?>" />
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
                                            <?
                                            $type = '';
                                            if ($Elem['duration_id'] == 1) {
                                                $type = $GLOBALS['ar_define_langterms']['MSG_ALL_ORA'];
                                            } else {
                                                $type = $GLOBALS['ar_define_langterms']['MSG_ALL_ZI'];
                                            }
                                            ?>

	                                        <span class="price_info">
	                                            <span class="price_cat"><?=get_price($Elem['price'])?></span>
	                                            <span class="valute_cat"><?=get_ue()?>/<?=$type?></span>
	                                        </span>
	                                        <span class="see_more">
	                                            <span class="more_txt"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_MAI_MULTE']?></span>
	                                        </span>
	                                    </span>
	                                </a>
                                </div>
                            <?}
                            }?>
                            <div class="clear"></div>
                             <? paginate( $Paginator );?>
                             <div class="clear"></div>
							<div class="txt_zone">
		                        <?=$Text['bottom_text']?>
		                    </div>
                    </div>
                </div>
            </div>
            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/footer.php")?>
            <script>
                function filter_btn(){
                    var height = $(window).height();
                    $('.filter_form').css({"min-height":height+'px'});
                    $('.wrap_fil_form').css("display","block");
                }

                function clos_btn_fil(){
                    $('.wrap_fil_form').css("display","none");
                }

                $('.fil-param').click(function(){
                    $(this).toggleClass('active');
                });

               <? /* элементы сортировки */?>

				$( '.sel_opt' ).change(function(){
					$('#sort_type').val( $(this).val() );
				});

				function fht()
				{
				    var wh = $(window).height();
				    var gh = wh - 200;

				    $('.wrap_filter').css({"height": gh+"px"});
				}

				$(function(){
				    fht();

				    $(window).resize(function(){
				        fht();
				    });
				});
            </script>
        </div>
        <style>
            @media( min-width: 1200px ) and ( max-width: 1800px){
                .filter_btn .btn_fil{
                    display: none;
                }

                .filter_btn{
                    width: 70px;
                    height: 70px;
                    background: #182031 url(/images/common/filtru.svg) no-repeat 49% 50%;
                    z-index: 5;
                }

                .wrap_filter{
                    overflow-y: auto;
                    padding-bottom: 120px;
                }
            }

            .filter_form{
                height: 100%;
            }

            .wrap_filter{
                margin-top: 0;
                padding-top: 50px;
                overflow-y: auto;
            }

            @media (max-width: 1199px){
                .filter_btn {
                    position: absolute;
                    height: 55px;
                    width: 55px;
                    right: 0;
                    background: #313d58 url(/images/common/filtru.svg) no-repeat 50% 50% !important;
                }

                .btn_fil{
                    display: none;
                }

                .wrap_filter{
                    padding-bottom: 120px
                }
            }

            .wrap_cat img.img-responsive{
                width: 100%
            }
        </style>
    </body>
</html>