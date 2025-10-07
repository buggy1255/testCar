<?
if (!$Auth->isAuthorized()) {
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
            <div id="page">
                <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/mobile_menu.php")?>
                <div class="container">
                    <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/breadcrumbs.php")?>
                    <h1 class="ant_page"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_CONT_PERSONAL']?></h1>
                    <div class="pers_wrap">
                        <div class="pers_email">
                            <div class="s1_title_pers"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_EMAIL']?> (<?=$GLOBALS['ar_define_langterms']['MSG_ALL__NU_POATE_FI_MODIFICAT']?>)</div>
                            <div class="pers_email_titl"><?=$_SESSION['user_data']['email']?></div>
                        </div>
                        <div class="s1_pers_wrap">
                            <div class="pers_info_txt"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_PERSONAL_AREA']?></div>
                            <div class="space-row-16">
	                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 fl">
	                                <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_NUMELE_PRENUMELE_PATRONIMIC']?></div>
	                                <input type="text" class="s4_inpt_pers" value="<?=$_SESSION['user_data']['name']?>" id="persarea_name" />
	                            </div>
	                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 fl">
	                                <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_ADDRESS_WORD']?></div>
	                                <input type="text" class="s4_inpt_pers" value="<?=$_SESSION['user_data']['address']?>" id="persarea_address" />
	                            </div>
	                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 fl">
	                                <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_PHONE']?></div>
	                                <input type="text" class="s4_inpt_pers" value="<?=$_SESSION['user_data']['phone']?>" id="persarea_phone" />
	                            </div>
	                        </div>
                            <div class="clear"></div>
                        </div>
                        <div class="s1_pers_wrap" id="block_persarea_password">
                            <div class="pers_info_txt"><?
                                if (!empty($_SESSION['user_data']['password'])) {
                                    echo $GLOBALS['ar_define_langterms']['MSG_ALL_MODIFICAREA_PAROLEI'];
                                }
                                else{
                                    echo $GLOBALS['ar_define_langterms']['MSG_ALL_SET_PASSWORD'];
                                }
                                ?></div>
                            <div class="space-row-16">
                                <?
                                    if (!empty($_SESSION['user_data']['password'])) {
                                        ?>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 fl">
                                            <div class="s1_title"><?= $GLOBALS['ar_define_langterms']['MSG_ALL_PAROLA_VECHE'] ?></div>
                                            <input type="password" class="s4_inpt_pers" id="persarea_old_pass"/>
                                        </div>
                                        <?
                                    }
                                ?>
	                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 fl">
	                                <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_NOUA_PAROLA']?></div>
	                                <input type="password" class="s4_inpt_pers" id="persarea_new_pass" />
	                            </div>
	                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 fl">
	                                <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_REPETARE_PAROLA_NOUA']?></div>
	                                <input type="password" class="s4_inpt_pers" id="persarea_new_pass_confirm" />
	                            </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                    </div>

                    <div class="btn_pers">
                        <div class="int_btn_pers" onclick="cancel_personal_data_user()"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_ARUNCATI_MODIFICARILE']?></div>
                        <div class="inp_btn_form_pers" onclick="update_personal_data();"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_SALVEAZA']?></div>
                        <div class="clear"></div>
                    </div>

                    <div class="wrap_social_netw">
                        <div class="pers_info_txt"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_CONECTAREA_CONTULUI_DE_PE_SITE_LA_RETELE_SOCIALE']?></div>
                        <div class="txt_zone"><?=$Main->GetPageIncData('TEXT_SOCIALS' , $CCpu->lang)?></div>
                        <div class="socials_wrappers">
                            <?
                            	$ArrDefine = array(
									'google' => $GLOBALS['ar_define_langterms']['MSG_ALL_PA_G'],
									'facebook' => $GLOBALS['ar_define_langterms']['MSG_ALL_PA_F'],
									'odnoklassniki' => $GLOBALS['ar_define_langterms']['MSG_ALL_PA_OK'],
									'vkontakte' => $GLOBALS['ar_define_langterms']['MSG_ALL_PA_VK'],
									'twitter' => $GLOBALS['ar_define_langterms']['MSG_ALL_PA_TW'],
									'linkedin' => $GLOBALS['ar_define_langterms']['MSG_ALL_PA_LI'],

								);

								$AeeExp = array();
								$AeeExp[$_SESSION['user_data']['network']] = $_SESSION['user_data']['network'];
	                            /** @var mysqli $db */
	                            $getDetails = mysqli_query($db, "SELECT * FROM ws_clients_connector WHERE client_id =".$_SESSION['user_data']['id']);

                                if( mysqli_num_rows($getDetails) > 0 ){
                                    while( $info = mysqli_fetch_assoc($getDetails) ){
	                                   $AeeExp[$info['network']] = $info['network'];
	                         ?>
	                                        <div class="wrap_socntw row">
	                                            <div class="fl"> <?=$ArrDefine[$info['network']]?> </div>
	                                            <div class="soc_disc" onclick="delete_connect(<?=$info['id']?>)"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_STERGE']?></div>
	                                        </div>
	                                        <div class="clear"></div>
	                         <?

									}
								}
                                ?>

                            <div class="placeSocialBtn">
                                <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/social_auth.php")?>
                            </div>
                       </div>
                    </div>
                </div>
            </div>
          </div>
        <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/footer.php")?>
        <script type="text/javascript">
            $("#persarea_phone").intlTelInput({
                // allowDropdown: false,
                // autoHideDialCode: false,
                // autoPlaceholder: "off",
                // dropdownContainer: "body",
                // excludeCountries: ["us"],
                formatOnDisplay: false,
                geoIpLookup: function(callback) {
                    $.get("https://ipinfo.io", function() {}, "jsonp").always(function(resp) {
                        var countryCode = (resp && resp.country) ? resp.country : "";
                        callback(countryCode);
                    });
                },
                hiddenInput: "full_number",
                initialCountry: "auto",
                placeholderNumberType: "MOBILE",
                preferredCountries: ['md', 'ru', 'us', 'it'],
                separateDialCode: true,
                utilsScript: "/lib/intl-tel-input-master/js/utils.js"
            });

            function register_social(e) {
                $.getJSON("//ulogin.ru/token.php?host=" + encodeURIComponent(location.toString()) + "&token=" + e + "&callback=?", function(t) {
                t = $.parseJSON(t.toString()), t.error || $.ajax({
                    type: "POST",
                    url: "<?=$defaultLinks['ajax']?>",
                    data: "task=socialConnect&token=" + e,
                    success: function(e) {
                        "ok" == $.trim(e) ? location.href="<?=$CCpu->writelink( 45 )?>" : alert($.trim(e))}
                   });
               });
            }

            function delete_connect(elem){
                if( !confirm('<?=$GLOBALS['ar_define_langterms']['MSG_ALL_UDALITI_ZAPISI']?>') ){
                    return false;
                }

                $.ajax({
                    method: "POST",
                    url: "<?=$defaultLinks['ajax']?>",
                    data: { task: "delete_connect", id: elem },
                    success: function(msg){
                        if(msg == 'ok'){
                            location.reload();
                        }
                    }
                })
            }

            $(function(){
                var height = $(window).height();

                $('.reserve_form').css({"min-height":height+'px'});

                <? foreach ( $AeeExp as $key => $namSocial ) { ?>
                	setTimeout( function() {
                    	$('[data-uloginbutton="<?=$namSocial?>"]').remove();
                    } , 200 );
               <? } ?>
            });

            function cancel_personal_data_user(){

                $.ajax({
                   method: "POST",
                   url: "<?=$defaultLinks['ajax']?>",
                   data: { task: "cancel_personal_data_user" },
                       success: function(msg){
                            var obj = $.parseJSON(msg);
                            $('#persarea_name').val( obj['name'] );
                            $('#persarea_phone').val( obj['phone'] );
                            $('#persarea_address').val( obj['address'] );
                            $('#block_persarea_password input').val( '' );
                       }
                });
            }

            <?/* обновить личные данные */?>

            function update_personal_data(){

                var name = $.trim($('#persarea_name').val());
                var phone = $.trim($('#persarea_phone').intlTelInput("getNumber"));
				var countryData = $("#persarea_phone").intlTelInput("getSelectedCountryData");
				if (phone === '+' + countryData['dialCode']){
					//if number is empty or have just country code, need to remove this phone.
					phone = '';
				}

                var address = $.trim($('#persarea_address').val());

				var old_pass = false;
				if ($('#persarea_old_pass').length){
                    old_pass = $.trim($('#persarea_old_pass').val());
                }

                var new_pass = $.trim($('#persarea_new_pass').val());
                var new_pass_confirm = $.trim($('#persarea_new_pass_confirm').val());
                var a = 0;

                $('.noborder').removeClass('wrong_field');

                if( name == '' ){
                    $('#persarea_name').addClass('wrong_field');
                    a++;
                }

                if (phone !== '' && !$("#persarea_phone").intlTelInput("isValidNumber")){
                    $('#persarea_phone').addClass('wrong_field');
                    a++;
                }

                if ((old_pass === false || old_pass != '') || new_pass != '' || new_pass_confirm != '' ){
                        if( old_pass !== false && old_pass == '' ){
                            $('#persarea_old_pass').addClass('wrong_field');
                            a++;
                        }

                        if( new_pass == '' ){
                            $('#persarea_new_pass').addClass('wrong_field');
                            a++;
                        }

                        if( new_pass_confirm == '' ){
                            $('#persarea_new_pass_confirm').addClass('wrong_field');
                            a++;
                        }

                        if( new_pass != new_pass_confirm && a == 0 ){
                            $('#persarea_new_pass').addClass('wrong_field');
                            $('#persarea_new_pass_confirm').addClass('wrong_field');
                            show( '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_CHANGE_PASSWORD']?>' , '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_PASSWORDS_DO_NOT_MATCH']?>' );
                            a++;
                        }

                        if(a>0){
                           show( '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_CHANGE_PASSWORD']?>' , '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_OUT_A_REQUIRED_FIELD']?>' );

                           return false;
                       }else{
							$('input').removeClass('wrong_field');
							if (old_pass === false){
                                old_pass = '';
                            }

                            $.ajax({
                               method: "POST",
                               url: "<?=$defaultLinks['ajax']?>",
                               data: { task: "update_personal_password", old_pass: old_pass, new_pass: new_pass, new_pass_confirm: new_pass_confirm },
                               success: function(msg){
                                 if( $.trim(msg) != 'ok'){
                                     show( '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_CHANGE_PASSWORD']?>' , msg );
                                     a++;
                                 }
                               }
                            });
                        }
                }

              setTimeout(function(){
                   if( a > 0 ){
                       return false;
                   }else{
					   $('input').removeClass('wrong_field');
                        loader();
                        $.ajax({
                           method: "POST",
                           url: "<?=$defaultLinks['ajax']?>",
                           data: { task: "update_personal_data", name: name, phone: phone, address: address },
                           success: function(msg){
                            loader_destroy();
                             if( $.trim(msg) == 'ok'){
                                  $('#block_persarea_password input').val( '' );

                                  show( '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_PERSONAL_AREA']?>' , '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_SUCCESSFULLY_CHANGED']?>' );
                             }else{
                                  show( '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_PERSONAL_AREA']?>'  , msg );
                             }
                           }
                        });
                    }
               },200);
            }
        </script>
    </body>
</html>