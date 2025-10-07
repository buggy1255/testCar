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
                    <div class="cont_zone">
                        <?/*information*/?>
                        <div class="col-xs-12 col-md-6">
                            <div class="cnt_wrap">
                                <div class="inform cnt_phone"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_PHONE']?></div>
                                <div class="contact_info">
                                	<p>
                                		<?
                                		$Exclude = array( ';' , '+' , ' ' , '-' , '(' , ')' );
                                		$Ctn = explode( '||' , $GLOBALS['ar_define_settings']['PHONE_CNT'] );
										foreach ( $Ctn as $key => $phone ) {
										?>
											<a href="tel:<?=str_replace( $Exclude , '' , $phone )?>">
												<?=$phone?><?if($key<(count($Ctn)-1)){echo';';}?>
											</a>
										<?}?>
                                		<? //$GLOBALS['ar_define_settings']['PHONE_CNT']?>
                                	</p>
                                </div>
                            </div>
                            <div class="cnt_wrap">
                                <div class="inform cnt_viber"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_VIBER']?></div>
                                <div class="contact_info">
                                	<p>
                                		<a href="viber://add?number=<?=str_replace( $Exclude , '' , $GLOBALS['ar_define_settings']['VIBER'] )?>">
                                			<?=$GLOBALS['ar_define_settings']['VIBER']?>
                                		</a>
                                	</p>
                                </div>
                            </div>
                            <div class="cnt_wrap">
                                <div class="inform cnt_what"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_WHATSAPP']?></div>
                                <div class="contact_info">
                                	<p><?//$GLOBALS['ar_define_settings']['WHATSAPP']?></p>
                                	<a href="whatsapp://send?phone=<?=str_replace( $Exclude , '' , $GLOBALS['ar_define_settings']['WHATSAPP'] )?>">
                            			<?=$GLOBALS['ar_define_settings']['WHATSAPP']?>
                            		</a>
                                </div>
                            </div>
                            <div class="cnt_wrap">
                                <div class="inform cnt_mail"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_EMAIL']?></div>
                                <div class="contact_info">
                                	<p>
                                		<a href="mailto:<?=str_replace( $Exclude , '' , $GLOBALS['ar_define_settings']['EMAIL'] )?>">
                                			<?=$GLOBALS['ar_define_settings']['EMAIL']?>
                                		</a>
                                	</p>
                                </div>
                            </div>
                            <div class="cnt_wrap">
                                <div class="inform cnt_skype"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_SKYPE']?></div>
                                <div class="contact_info">
                                	<p>
                                		<a href="skype:<?=str_replace( $Exclude , '' , $GLOBALS['ar_define_settings']['SKYPE'] )?>?chat">
                                			<?=$GLOBALS['ar_define_settings']['SKYPE']?>
                                		</a>
                                	</p>
                                </div>
                            </div>
                            <div class="cnt_wrap">
                                <div class="inform cnt_address"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_ADDRESS_WORD']?></div>
                                <div class="contact_info"><p><?=$GLOBALS['ar_define_langterms']['MSG_ALL_ADDRESS']?></p></div>
                            </div>
                        </div>
                        <?/*forms*/?>
                        <div class="col-xs-12 col-md-6">
                            <div id="form_contact">
                                <div class="space-row-10">
                                    <div class="col-sm-6">
                                        <div class="label_cnt"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_NAME']?></div>
                                        <input onkeyup="removeborder(this)" type="text" class="int_contact noborder"
                                        id="contact_name" value="<?if($Auth->isAuthorized()){echo $_SESSION['user_data']['name'];}?>" />
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="label_cnt"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_EMAIL']?></div>
                                        <input onkeyup="removeborder(this)" type="text" class="int_contact noborder"
                                        id="contact_email" value="<?if($Auth->isAuthorized()){echo $_SESSION['user_data']['email'];}?>" />
                                    </div>
                                </div>
                                <div class="col-xs-12">
                                    <div class="label_cnt"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_THEME']?></div>
                                    <input onkeyup="removeborder(this)" type="text" class="int_contact noborder" id="contact_theme" />
                                </div>
                                <div class="col-xs-12">
                                    <div class="label_cnt"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_MESSAGE']?></div>
                                    <textarea onkeyup="removeborder(this)" class="txt_contact noborder" id="contact_text"></textarea>
                                </div>
                                <div class="regmessage"></div>
                                <div class="btn_cont" onclick="form_contact()">
                                    <div class="intp_form"> <?=$GLOBALS['ar_define_langterms']['MSG_ALL_SEND']?> </div>
                                </div>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div id="map"></div>
                    </div>
                </div>
            </div>
            </div>
            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/footer.php")?>
            <script  defer async="async" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBKCrGMdn9-JlDVFGgiZoJvK-frvYNIHE0&callback=initMap"></script>
            <script>
               <? //проверку валидации email ?>
               function validateEmail(email) {
                   var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                   return re.test(email);
               }

               function removeborder(elem) {
                   $(elem).removeClass('wrong_field');
               }

               function clearmessage(delay) {
                   setTimeout(function () {
                       $('.regmessage').html('');
                   }, delay);
               }

               function form_contact() {
                   var name = $.trim($('#contact_name').val());
                   var email = $.trim($('#contact_email').val());
                   var theme = $.trim($('#contact_theme').val());
                   var message = $.trim($('#contact_text').val());
                   var a = 0;

                   $('.noborder').removeClass('wrong_field');

                   if (name == '') {
                       $('#contact_name').addClass('wrong_field');
                       a++;
                   }

                   if (email == '') {
                       $('#contact_email').addClass('wrong_field');
                       a++;
                   }

                   if (theme == '') {
                       $('#contact_theme').addClass('wrong_field');
                       a++;
                   }

                   if (message == '') {
                       $('#contact_text').addClass('wrong_field');
                       a++;
                   }

                   if (!validateEmail(email)) {
                       $('.regmessage').html('<span class="red"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_INTRODUCETI_EMAIL_CORECT']?></span>');
                       $('#contact_email').addClass('wrong_field');
                       clearmessage(3500);
                       a++;
                   }

                   if (a > 0) {
                       return false;
                   } else {
                       loader();

                       $.ajax({
                           type: "POST",
                           url: "<?=$defaultLinks['ajax']?>",
                           data: "task=form_contact&name=" + name + "&email=" + email + "&theme=" + theme + "&message=" + message,
                           success: function (msg) {
                               loader_destroy();

                               if (msg == 1 || msg == '1') {
                                   $('#form_contact').find('input,textarea').val('');
                                   $('.regmessage').html('<span class="succes_message"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_SUCCES']?></span>');
                                   clearmessage(3500);
                               } else {
                                   $('.regmessage').html(msg);
                                   clearmessage(3500);
                               }
                           }
                       });
                   }
               }

               function initMap() {

                   var uluru = {
                       lat: <?=$GLOBALS['ar_define_settings']['MAPS_LAT']?> ,
                       lng: <?=$GLOBALS['ar_define_settings']['MAPS_LNG']?> };

                   var styleArray = [
                       {
                           "featureType": "water",
                           "elementType": "geometry.fill",
                           "stylers": [
                               {
                                   "color": "#d3d3d3"
                               }
                           ]
                       },
                       {
                           "featureType": "transit",
                           "stylers": [
                               {
                                   "color": "#808080"
                               },
                               {
                                   "visibility": "off"
                               }
                           ]
                       },
                       {
                           "featureType": "road.highway",
                           "elementType": "geometry.stroke",
                           "stylers": [
                               {
                                   "visibility": "on"
                               },
                               {
                                   "color": "#b3b3b3"
                               }
                           ]
                       },
                       {
                           "featureType": "road.highway",
                           "elementType": "geometry.fill",
                           "stylers": [
                               {
                                   "color": "#ffffff"
                               }
                           ]
                       },
                       {
                           "featureType": "road.local",
                           "elementType": "geometry.fill",
                           "stylers": [
                               {
                                   "visibility": "on"
                               },
                               {
                                   "color": "#ffffff"
                               },
                               {
                                   "weight": 1.8
                               }
                           ]
                       },
                       {
                           "featureType": "road.local",
                           "elementType": "geometry.stroke",
                           "stylers": [
                               {
                                   "color": "#d7d7d7"
                               }
                           ]
                       },
                       {
                           "featureType": "poi",
                           "elementType": "geometry.fill",
                           "stylers": [
                               {
                                   "visibility": "on"
                               },
                               {
                                   "color": "#ebebeb"
                               }
                           ]
                       },
                       {
                           "featureType": "administrative",
                           "elementType": "geometry",
                           "stylers": [
                               {
                                   "color": "#a7a7a7"
                               }
                           ]
                       },
                       {
                           "featureType": "road.arterial",
                           "elementType": "geometry.fill",
                           "stylers": [
                               {
                                   "color": "#ffffff"
                               }
                           ]
                       },
                       {
                           "featureType": "road.arterial",
                           "elementType": "geometry.fill",
                           "stylers": [
                               {
                                   "color": "#ffffff"
                               }
                           ]
                       },
                       {
                           "featureType": "landscape",
                           "elementType": "geometry.fill",
                           "stylers": [
                               {
                                   "visibility": "on"
                               },
                               {
                                   "color": "#efefef"
                               }
                           ]
                       },
                       {
                           "featureType": "road",
                           "elementType": "labels.text.fill",
                           "stylers": [
                               {
                                   "color": "#696969"
                               }
                           ]
                       },
                       {
                           "featureType": "administrative",
                           "elementType": "labels.text.fill",
                           "stylers": [
                               {
                                   "visibility": "on"
                               },
                               {
                                   "color": "#737373"
                               }
                           ]
                       },
                       {
                           "featureType": "poi",
                           "elementType": "labels.icon",
                           "stylers": [
                               {
                                   "visibility": "off"
                               }
                           ]
                       },
                       {
                           "featureType": "poi",
                           "elementType": "labels",
                           "stylers": [
                               {
                                   "visibility": "off"
                               }
                           ]
                       },
                       {
                           "featureType": "road.arterial",
                           "elementType": "geometry.stroke",
                           "stylers": [
                               {
                                   "color": "#d6d6d6"
                               }
                           ]
                       },
                       {
                           "featureType": "road",
                           "elementType": "labels.icon",
                           "stylers": [
                               {
                                   "visibility": "off"
                               }
                           ]
                       },
                       {},
                       {
                           "featureType": "poi",
                           "elementType": "geometry.fill",
                           "stylers": [
                               {
                                   "color": "#dadada"
                               }
                           ]
                       }
                   ];

                   var mapOptions = {
                       zoom: 16,
                       center: new google.maps.LatLng( <?=$GLOBALS['ar_define_settings']['MAPS_LAT']?> , <?=$GLOBALS['ar_define_settings']['MAPS_LNG']?> )
                   };

                   var map = new google.maps.Map(document.getElementById('map'), mapOptions);
                   map.setOptions({styles: styleArray});

                   var marker = new google.maps.Marker({
                       position: uluru,
                       map: map,
                       icon: '/images/bg/map_pointer.svg'
                   });
               }
            </script>
    </body>
</html>