<?
if (empty($_SESSION['reserve']['3'])) {
	header('Location:' . $CCpu->writelink( 48 ));
	exit;
}
elseif(empty($_SESSION['reserve']['2'])){
    header('Location:' . $defaultLinks['index']);
    exit;
}

//show( $_SERVER );

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
                <div class="container">
                    <?//include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/breadcrumbs.php")?>
                    
                     <div class="breadcrumbs">
                         <a class="bread" href="<?=$defaultLinks['index']?>"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_HOME_PAGE']?></a>
                         <a class="bread" href="<?=$CCpu->writelink( 47 )?>"><?=$CCpu->writetitle( 47 )?></a>
                         <a class="bread" href="<?=$CCpu->writelink( 48 )?>"><?=$CCpu->writetitle( 48 )?></a>
                         <a class="bread" href="<?=$CCpu->writelink( 49 )?>"><?=$CCpu->writetitle( 49 )?></a>
                         <a class="act_bread" href="<?=$pageData['cpu']?>"><?=$page_data['title']?></a>
                    </div>
                    
                    <h1 class="ant_page"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_PERSONAL_AREA']?></h1>
                    
                    <div class="wrap_step_1">
                        <div class="s1_wrap">
                            <div class="fl col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="s1_title_oblig"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_NAME']?></div>
                                <input style="width: 100%;" type="text" value="<? echo isset($_SESSION['user_data']['name']) ? $_SESSION['user_data']['name'] : '';?>" id="s_name" class="s4_inpt" />
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div class="s1_wrap">
                        	<div class="space-row-16">
	                            <div class="col-md-6 col-sm-6 col-xs-12 fl">
	                                <div class="s1_title_oblig"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_EMAIL']?></div>
	                                <input type="text" id="s_email" value="<? echo isset($_SESSION['user_data']['email']) ? $_SESSION['user_data']['email'] : ''; ?>" class="s4_inpt" />
	                            </div>
	                            <div class="col-md-6 col-sm-6 col-xs-12 fr spec_mob">
	                                <div class="s1_title_oblig"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_PHONE']?></div>
	                                <input type="text" id="s_phone" value="<? echo isset($_SESSION['user_data']['phone']) ? $_SESSION['user_data']['phone'] : ''; ?>" class="s4_inpt" />
	                            </div>
	                        </div>
                            <div class="clear"></div>
                        </div>
                        <div class="s1_wrap">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 fl">
                                <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_COMENTARIU']?></div>
                                <textarea id="s_comment" class="bfq_txt" name="textarea"></textarea>
                            </div>
                            <div class="clear"></div>
                        </div>

                        
                        <div class="wrap_cond">
                            <div class="fil-param accordPolic ">
                            	<?=$GLOBALS['ar_define_langterms']['MSG_ALL_SUNT_DE_ACORD_CU']?> 
                            	<a target="_blank" href="<?=$CCpu->writelink( 34 )?>"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_TERMENII_SI_CONDITIILE']?></a> 								<?=$GLOBALS['ar_define_langterms']['MSG_ALL_SITE-ULUI']?>
                            </div>
                        </div>
                        <?php
                            if (!empty($A['deposit'])) {
                                ?>
                                <div class="wrap_deposit">
                                    <span class="wr_total"><?= $GLOBALS['ar_define_langterms']['MSG_ALL_DEPOSIT'] ?>:</span>
                                    <span class="wr_total_price"><?= $A['deposit'] ?></span>
                                    <span class="wr_total_val"><?= get_ue() ?></span>
                                </div>
                                <div class="clear"></div>
                                <?php
                            }
                        ?>
                        <div class="wrap_total">
                            <span class="wr_total"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_TOTAL_SPRE_PLATA']?>:</span>
                            <span class="wr_total_price"><?=((int)$TotalPrice-$B['discount']+$A['deposit'])?></span>
                            <span class="wr_total_val"><?=get_ue()?></span>
                        </div>
                        <div class="clear"></div>
                        <?
                        	$_SESSION['reserve']['total_sum'] = $TotalPrice;
                        ?>
                        
                        <div class="s1_wrap row ">
                            <div onclick="location.href='<?=$CCpu->writelink( 49 )?>'" class="int_btn_back">
                                <div class="intp_form"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_INAPOI']?></div>
                            </div>
                            <div onclick="reserve4()" class="inp_btn_form_total">
                                <div class="intp_form"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_FINISEAZA']?></div>
                            </div>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            
        </div>
        <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/footer.php")?>
            <script>
                $("#s_phone").intlTelInput({
					// allowDropdown: false,
					// autoHideDialCode: false,
					// autoPlaceholder: "off",
					// dropdownContainer: "body",
					// excludeCountries: ["us"],
					formatOnDisplay: false,
					geoIpLookup: function (callback) {
						$.get("https://ipinfo.io", function () {}, "jsonp").always(function (resp) {
							var countryCode = (resp && resp.country) ? resp.country : "";
							callback(countryCode);
						});
					},
					hiddenInput: "full_number",
					initialCountry: "auto",
					placeholderNumberType: "MOBILE",
					preferredCountries: ['md', 'ru', 'us', 'it', 'ie', 'gb'],
					separateDialCode: true,
					utilsScript: "/lib/intl-tel-input-master/js/utils.js"
				});

				$(function () {
					var height = $(window).height();
					$('.reserve_form').css({"min-height": height + 'px'});

                    if ($('.wrap_deposit').length) {
                        $('.wrap_total').css('margin-top', '2px');
                    }
				});

				$('.fil-param').click(function () {
					$(this).toggleClass('active');
				});


				function reserve4() {

					var a = 0;
					var email_empty = false;
					$('.required_field').removeClass('required_field');

					var name = $.trim($('#s_name').val());
					var email = $.trim($('#s_email').val());
					var phone = $.trim($("#s_phone").intlTelInput("getNumber"));
                    var comment = $.trim($('#s_comment').val());

					if (name.length == 0 || name.length > 350) {
						$('#s_name').addClass('required_field');
						a++;
					}

					if (email.length == 0 || email.length > 350) {
						$('#s_email').addClass('required_field');
                        email_empty = true;
						a++;
					}

					if (phone.length == 0 || phone.length > 350 || !$("#s_phone").intlTelInput("isValidNumber")) {
						$('#s_phone').addClass('required_field');
						a++;
					}

					if (!validateEmail(email) && !email_empty) {
                        $('#s_email').addClass('wrong_field');
                        a++;
						show('<?=$GLOBALS['ar_define_langterms']['MSG_ALL_INTRODUCETI_EMAIL_CORECT']?>');
					}
					else{
                        $('#s_email').removeClass('wrong_field');
                    }

					if (comment.length > 512){
                        $('#s_comment').addClass('wrong_field');
                        show('<?=$GLOBALS['ar_define_langterms']['MSG_ALL_COMMENT_NOT_MORE_THAN_512']?>');
                        a++;
                    }
					else{
                        $('#s_comment').removeClass('wrong_field');
                    }

					if (!$('.accordPolic').hasClass('active')) {
						$('.accordPolic').addClass('required_field');
						a++;
					}

					if (a > 0) {
                        if ($('.required_field').length){
                            $('.required_field').first().focus();
                        }
                        else if($('.wrong_field').length){
                            $('.wrong_field').first().focus();
                        }

						return false;
					}
					else {

						loader();

						var fd = new FormData;
						fd.append('task', 'reservedata4');
						fd.append('name', name);
						fd.append('email', email);
						fd.append('phone', phone);
						fd.append('comment', comment);
						fd.append('step', '4');

						$.ajax({
							url: '<?=$defaultLinks['ajax']?>',
							data: fd,
							processData: false,
							contentType: false,
							type: 'POST',
							success: function (msg) {

								loader_destroy();
								if ($.trim(msg) == 'ok') {
									location.href = "<?=$CCpu->writelink(58)?>";
								}
								else {
									show(msg);
								}
							}
						});
					}
				}
            </script>
    </body>
</html>