<div id="footer">

	<div class="container">

		<img class="f_logo" src="/images/other/logo_arrow_footer.svg" alt="Go up" />

		<div class="copyright">Copyright © 2025 Car4Rent.md </div>

	</div>

	

	<div class="PlaceContinueCheck">

        <?php

        $pages = array(

            1 => $CCpu->writelink(48),

            2 => $CCpu->writelink(49),

            3 => $CCpu->writelink(50),

            4 => $CCpu->writelink(50)

        );



        $last_valid_num = 0;

        foreach (range(1, 4) as $res_num) {

            if (empty($_SESSION['reserve'][$res_num])) {

                break;

            }



            $last_valid_num = $res_num;

        }



        if ($last_valid_num && isset($pages[$last_valid_num])) {

            echo '<div onclick="location.href=\'' . $pages[$last_valid_num] . '\'" class="PlaceContinueCheckBtn">';

            echo '<div class="intp_form"> ' . $GLOBALS['ar_define_langterms']['MSG_CONTINUE_RESERVATION'] . ' </div>';

            echo '</div>';

        }

        ?>

	</div>

</div>



<div onclick="scrollTopFunction()" id="scroll_top"></div>



<link rel="stylesheet" media="all" href="/css/ws.css" />

<link rel="stylesheet" media="all" href="/css/style.css?u=4" />

<link rel="stylesheet" type="text/css" media="only screen and (max-width: 1199px)" href="/css/mobile.css?u=1" />

<script src="/lib/jquery/jquery.2.2.2.js"></script>





<link rel="stylesheet" media="all" href="/lib/fonts/raleway/stylesheet.css" />



<link href="https://fonts.googleapis.com/css?family=Roboto&amp;subset=cyrillic-ext,latin-ext" rel="stylesheet">

<?/* https://fonts.google.com/?query=Lato&selection.family=Lato:400,700,900|Raleway:400,600,700,900 */?>



<script src="/lib/datepicker/bootstrap-datepicker.js"></script>

    <?

        if ($CCpu->lang != 'en') {

            echo '<script src="/lib/datepicker/locales/bootstrap-datepicker.' . $CCpu->lang . '.js"></script>';

        }

    ?>

<link rel="stylesheet" href="/lib/datepicker/datepicker3.css">



<link rel="stylesheet" href="/lib/datetimepicker/jquery.datetimepicker.css">

<script src="/lib/datetimepicker/jquery.datetimepicker.full.js"></script>





<link rel="stylesheet" media="all" href="/lib/animatecss/animate.css" />



<link rel="stylesheet" media="all" href="/lib/fa/css/font-awesome.min.css" />



<script src="/lib/scroll_to/jquery.scrollTo.js"></script>

<script src="/js/blocks.js"></script>



<?// InputMask ?>

<script src="/lib/input-mask/jquery.inputmask.js"></script>

<script src="/lib/input-mask/jquery.inputmask.extensions.js"></script>

<script src="/lib/intl-tel-input-master/js/intlTelInput.min.js"></script>

<link rel="stylesheet" media="all" href="/lib/intl-tel-input-master/css/intlTelInput.css" />



<script>

    $(function(){

        <?

            if (!empty($show_error)) {

                $show_error = addslashes($show_error);

                echo "show('{$show_error}');";

            }

        ?>



        function leftall()

        {

            var height = $(window).height();

            $('.mn_st').css({"min-height": height+'px', 'height': height+'px'});

            

            var h1 = $('.sc_mn_st').height();

            var h2 = $('.wr_src').height();

            

            h1 = parseInt(h1);

            h2 = parseInt(h2);

            

            var th = h1 + h2;

            

            if( th  > height){

                <?//$('.mn_st').css({"min-height": th +'px'});?>

                $('.sc_mn_st').css({'height':'auto'});

                $('.wr_src').css({'position': 'relative'});

            }else{

                <?//var the = height - h2;?>

                $('.sc_mn_st').css({'height': height +'px'});

                $('.wr_src').css({'position': 'absolute'});

            }

        }

        

        leftall();



        $(window).resize(function(){

            leftall();

        });

        

    });

    

    $(function(){

    	

    	 $('[data-mask]').inputmask();

    	

         $('.datetimepicker').datetimepicker({

             format: 'd/m/y',

             minDate: new Date( <?=date( 'Y' )?> , <?=(date('m')-1)?> , <?=date( 'd' )?> ),

             weekStart: 1,

			 lang: '<?=$CCpu->lang?>',

             timepicker:false,

         });

         

         $('.timepicker').datetimepicker({

             format: 'H:i',

             weekStart: 1,

             lang: '<?=$CCpu->lang?>',

             datepicker:false,

         });



		$.datetimepicker.setLocale('<?=$CCpu->lang?>');

    });

</script>



<link rel="stylesheet" href="/lib/bxslider/jquery.bxslider.min.css">

<script src="/lib/bxslider/jquery.bxslider.min.js"></script>



<script>



	<?/* ПЛАШКА ЧТО БЫ ДАЛЬШЕ ПРОЙТИ РЕЗЕРВАЦИЮ */?>

	function ContinueReservation() {

		$('.PlaceContinueCheck').addClass('active');

	}

	

	<?

    if(!empty($_SESSION['reserve']['1']) && !in_array($page_data['id'], [47, 48, 49, 50, 58])){

    ?>

		setTimeout(function(){

			ContinueReservation();

		},150);

		

	<?}?>



  $('.bxslider').bxSlider({

        auto: true,

        mode: 'fade',

        speed: 400,

        pause: 3500,

        controls: false,

        pager:false

    });



    window.onscroll = function(){ scrollFunction() };

    function scrollTopFunction()

    { 

        $('html, body').animate({ scrollTop: 0 },1000); 

        return false; 

    }



    function scrollFunction() {

        if (document.body.scrollTop > 10 || document.documentElement.scrollTop > 10) {

            document.getElementById("scroll_top").style.display = "block";

        } else {

            document.getElementById("scroll_top").style.display = "none";

        }

    }



    function popupCloseControl(){

        $('.check_click').click( function() {

            $( this ).toggleClass( 'active' );

        });

        <?/* enter popup */?>

        $('.popenterBlock').keyup(function(){

            if(event.keyCode==13){

                $( this ).find('.popenterButton').click();

                return false;

            }

        });

        

        <?/*закрытие всплывающего окна при клике вокруг него*/?>

        $(".popup_overlay").click(function(e){

            if( $(this).attr('data-click') == 'false' ){

                return false;

            }

            var hide = true;

            $(e.target).parents().each(function(){

                if($(this).hasClass("popup_window_wrapper")){

                    hide = false;

                }

            });

            if(hide){

                $(this).fadeOut(300);

                $( this ).remove();

            }

        });

    };

    

    function popup_show( task , hide , arr_param ){

        if( hide != '' && hide != undefined ){

            closePopupName(hide);

        }

        var win_size = $(window).height();

        $.ajax({

           type: "POST",

           url: "<?=$defaultLinks['ajax']?>",

           data: { task: task , arr_param : arr_param },

           success: function(msg){

             setTimeout(function(){

               if( $('.popup_overlay').length == 0 ){

                   $('body').prepend( msg );

                   var WWidth = $( window ).width();

                   $('#'+task).fadeIn( 300 );

                   if( win_size <= 768 ){

                        $('.popup_window_wrapper').css({ 'max-height' : '430px' });

                   }

                }

             },150);

           }

        }); 

    }

    

    function closePopupName(elem){

        $( elem ).fadeOut( 300 );

        setTimeout(function(){

            $( elem ).remove();

        } , 100 );

    }

    

    $('body').on('click', '.menu-btn', function(){

        $('.mn_st').toggleClass('active');

    });

    

    $('body').on('click', '.mtmenu', function(){

        $('.mn_st').toggleClass('active');

    });

</script>

