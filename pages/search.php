<?

if (!empty($_GET)) {
    $ar_get = filter_input_array(INPUT_GET, FILTER_SANITIZE_SPECIAL_CHARS);
    $keySearch = (isset($ar_get['key'])) ? (string)$ar_get['key'] : '';

    if (strlen($keySearch) < 3 || strlen($keySearch) > 20) {
        $keySearch = 'key';
    }

    $type = (isset($ar_get['type'])) ? (string)$ar_get['type'] : '';
    if (!in_array($type, array('news', 'comments', 'pages', 'all'))) {
        $type = 'all';
    }

    $time = (isset($ar_get['time'])) ? (int)$ar_get['time'] : '';
    $order = (isset($ar_get['order'])) ? (string)$ar_get['order'] : '';

    if (!in_array($order, array('news-date', 'news-title', 'comments-qty', 'car-rating'))) {
        $order = 'news-date';
    }

    $direction = (isset($ar_get['direction'])) ? (string)$ar_get['direction'] : '';

    if (!in_array($direction, array('no', 'asc', 'desc'))) {
        $order = 'no';
    }

    $per_page = (isset($ar_get['per-page'])) ? (int)$ar_get['per-page'] : 10;

    if ($per_page > 100){
        $per_page = 100;
    }

    $arTags = [];
    foreach ($_GET as $KK => $VV) {
        if (substr_count($KK, 'tag_') > 0) {
            $arTags[] = (int)$VV;
        }
    }
} else {
    $keySearch = '';
    $type = 'all';
    $time = 0;
    $arTags = array();
    $order = '';
    $direction = '';
    $per_page = 10;
}

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
                    <div class="breadcrumbs">
                        <a class="bread" href="<?=$defaultLinks['index']?>"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_HOME_PAGE']?></a>
                        <a class="act_bread" href=""><?=$page_data['title']?></a>
                    </div>
                    <h1 class="ant_page"><?=$page_data['title']?></h1>
                    <form method="get" action="" id="advanced-form">
                    <div class="res_search_wrap">
                        <div class="space-row-16">
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="field-block">
                                    <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_INTRODUCETI_CUVINTUL_SAU_FRAZA']?></div>
                                    <input type="text" class="s1_inpt" name="key" id="skey" value="<?=$keySearch?>" />
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="field-block">
                                    <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_MODALITATEA_DE_CAUTARE']?></div>
                                    <div class="s1_select">
                                        <select name="type" id="tupp">
                                            <option <?if($type=='news'){echo "selected";}?> value="news"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DUPA_DENUMIREA_NOUTATILOR']?></option>
                                            <option <?if($type=='comments'){echo "selected";}?> value="comments"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DIN_COMENTARII']?></option>
                                            <option <?if($type=='pages'){echo "selected";}?> value="pages"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DE_PE_PAGINELE_TEXTUALE']?></option>
                                            <option <?if($type=='all'){echo "selected";}?> value="all"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_TOATE_OFETELE_SITE-ULUI']?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="field-block">
                                    <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_PERIOADA']?></div>
                                    <div class="s1_select">
                                        <select name="time">
                                            <option value="0"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_TOATA_PERIOADA']?></option>
                                            <option <?if($time==24){echo "selected";}?> value="24"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_UTLIMELE_24_ORE']?></option>
                                            <option <?if($time==168){echo "selected";}?> value="168"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_ULTIMA_SAPTAMANA']?></option>
                                            <option <?if($time==720){echo "selected";}?> value="720"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_ULTIMA_LUNA']?></option>
                                            <option <?if($time==2160){echo "selected";}?> value="2160"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_UTLIMELE_3_LUNI']?></option>
                                            <option <?if($time==4320){echo "selected";}?> value="4320"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_UTLIMELE_6_LUNI']?></option>
                                            <option <?if($time==8760){echo "selected";}?> value="8760"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_ANUL_TRECUT']?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="clear visible-lg visible-md"></div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="field-block s1_select">
                                    <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_SORTARE']?></div>
                                    <select name="order">
                                        <option value="news-date"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DUPA_DATA_PUBLICATII_NOUTATIILOR']?></option>
                                        <option <?if($order=='news-title'){echo "selected";}?> value="news-title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DUPA_DENUMIREA_NOUTATILOR']?></option>
                                        <option <?if($order=='comments-qty'){echo "selected";}?> value="comments-qty"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DUPA_CANTITATEA_COMENTARIILOR']?></option>
                                        <option <?if($order=='car-rating'){echo "selected";}?> value="car-rating"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DUPA_RAITING_LA_AUTOMOBIL']?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="field-block s1_select">
                                    <div class="s1_title hidden-xs">&nbsp;</div>
                                    <select name="direction">
                                        <option <?if($direction=='no'){echo "selected";}?> value="no"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DEFAULT']?></option>
                                        <option <?if($direction=='asc'){echo "selected";}?> value="asc"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DE_LA_MIC_LA_MARE']?></option>
                                        <option <?if($direction=='desc'){echo "selected";}?> value="desc"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_DE_LA_MARE_LA_MIC']?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-4">
                                <div class="field-block">
                                    <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_AFISAREA_REZULTATELOR_PE_PAGINA']?></div>
                                    <div class="s1_select">
                                        <select name="per-page">
                                            <option value="10">10</option>
                                            <option <?if($per_page==25){echo "selected";}?> value="25">25</option>
                                            <option <?if($per_page==50){echo "selected";}?> value="50">50</option>
                                            <option <?if($per_page==75){echo "selected";}?> value="75">75</option>
                                            <option <?if($per_page==100){echo "selected";}?> value="100">100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clear"></div>
                    <div class="res_search_wrap mega-tagz" <?if( $type == 'all' ){?> style="display: block"<?}?>>
                        <div class="s1_title"><?=$GLOBALS['ar_define_langterms']['MSG_ALL__TEGI']?></div>
                        <div class="space-row-16">
                            <?
                            /** @var mysqli $db */
                            $getTags = mysqli_query($db, "SELECT * FROM ws_tags WHERE active = 1 ORDER BY sort DESC");

                            while($tags = mysqli_fetch_assoc($getTags)){ ?>
                            <div class="col-xs-12 col-sm-4 col-md-3 lhm">
                                <label id="tag_<?=$tags['id']?>" class="tag-label">
                                    <input type="checkbox" name="tag_<?=$tags['id']?>" value="<?=$tags['id']?>" <?if( in_array($tags['id'], $arTags) ){echo "checked";} ?> />
                                    <?=$tags['title_'.$CCpu->lang]?>
                                </label>
                            </div>
                            <? } ?>
                        </div>
                    </div>
                    <div class="clear"></div>
                    </form>

                    <div class="btns_search">
                        <a href="<?=$pageData['cpu']?>"><div class="int_btn_back"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_RESETARE']?></div></a>
                        <div class="inp_btn_form_total"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_CAUTARE']?></div>
                        <div class="clear"></div>
                    </div>

                    <div class="row" style="min-height: 650px">
                        <?
                        if( $keySearch!='' ){
                            ?>

                            <a name="results"></a>
                            <h4><?=$GLOBALS['ar_define_langterms']['MSG_ALL_REZULITATY_POISKA']?></h4>
                            <?
                            $resultus = 0;

                            // type - array('news','comments','pages','all')
                            // order - array('news-date','news-title','comments-qty','car-rating')
                            if ($type == 'news') { //order
                                $orderby = "ORDER BY `date` DESC ";

                                if ($order == "news-title") {
                                    $orderby = "ORDER BY title_" . $CCpu->lang . " ASC ";
                                }

                                $getNews = mysqli_query($db, "
                                SELECT id, title_".$CCpu->lang." AS title FROM ws_news WHERE active = 1 
                                AND ( 
                                    title_".$CCpu->lang." LIKE '%$keySearch%' 
                                    OR 
                                    text_".$CCpu->lang." LIKE '%$keySearch%' 
                                    )
                                $orderby
                                ");

                                while($Result = mysqli_fetch_assoc($getNews)){ $resultus++;
                                    ?>
                                    <div>
                                        <a target="_blank" href="<?=$CCpu->writelink(28, $Result['id'])?>" class="search-result"><?=$Result['title']?></a>
                                    </div>
                                    <?
                                }
                            }

                            if( $type == 'comments' ){
                                $orderby = " ORDER BY `id` DESC ";
                                if( $order == "car-rating"){
                                    $orderby = " ORDER BY rating DESC ";
                                }

                                $getElements = mysqli_query($db, "
                                SELECT elem_id, comment FROM ws_rating_products WHERE active = 1 
                                AND (
                                    comment LIKE '%$keySearch%'
                                    )
                                $orderby
                                ");

                                while($Result = mysqli_fetch_assoc($getElements)){ $resultus++;
                                    ?>
                                    <div>
                                        <a target="_blank" href="<?=$CCpu->writelink(27, $Result['elem_id'])?>#comments" class="search-result"><?=mb_substr($Result['comment'], 0, 30)?>...</a>
                                    </div>
                                    <?
                                }
                            }

                            if( $type == 'pages' ){
                                $orderby = " ORDER BY `title_".$CCpu->lang."` ASC ";
                                $getElements = mysqli_query($db, "
                                SELECT id, title_".$CCpu->lang." AS title, meta_d_".$CCpu->lang." AS meta_d FROM ws_pages WHERE  id > 3 AND assoc_table = '' 
                                AND ( 
                                    title_".$CCpu->lang." LIKE '%$keySearch%' 
                                    OR 
                                    text_".$CCpu->lang." LIKE '%$keySearch%' 
                                    )
                                $orderby 
                                ");

                                while($Result = mysqli_fetch_assoc($getElements)){ $resultus++;
                                    ?>

                                    <div>
                                        <a target="_blank" href="<?=$CCpu->writelink($Result['id'])?>" class="search-result"><?=$Result['title']?></a>
                                        <div>
                                            <i><?=$Result['meta_d']?></i>
                                        </div>
                                    </div>
                                    <?
                                }
                            }

                            // cars
                            if( $type == 'all' ){
                                $orderby = " ORDER BY `price` DESC ";
                                if( $order == "car-rating"){
                                    $orderby = " ORDER BY rating DESC ";
                                }

                                if( $order == "comments-qty"){
                                    $orderby = " ORDER BY comments DESC ";
                                }

                                $TAGGER = '';

                                if( count($arTags)>0 ){
                                    $TAGGER = " AND id IN( SELECT elem_id FROM ws_tags_connector WHERE tag_id IN( ".implode(",", $arTags)." ) ) ";
                                }

                                $getElements = mysqli_query($db, "
                                SELECT id, title_".$CCpu->lang." AS title, meta_d_".$CCpu->lang." AS meta_d FROM ws_catalog_elements 
                                WHERE  active = 1 
                                AND ( 
                                    title_".$CCpu->lang." LIKE '%$keySearch%' 
                                    OR 
                                    text_".$CCpu->lang." LIKE '%$keySearch%' 
                                    )
                                $TAGGER
                                $orderby
                                ");

                                while($Result = mysqli_fetch_assoc($getElements)){ $resultus++;

                                    ?>
                                    <div>
                                        <a target="_blank" href="<?=$CCpu->writelink(27, $Result['id'])?>" class="search-result"><?=$Result['title']?></a>
                                        <div>
                                            <i><?=$Result['meta_d']?></i>
                                        </div>
                                    </div>
                                    <?
                                }
                            }

                            if($resultus==0){
                            ?>
                            <p><?=$GLOBALS['ar_define_langterms']['MSG_ALL_NICHEGO_NE_NAJDENO']?></p>
                            <?
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/footer.php")?>
        <script>
            $('body').on('click', '.inp_btn_form_total', function(){
                var key = $.trim($('#skey').val());
                if( key.length < 3 ){
                    show('<?=$GLOBALS['ar_define_langterms']['MSG_ALL_UKAZHITE_KLYUCHEVOE_SLOVO']?>');
                    return false;
                }else{
                    $('#advanced-form').submit()
                }
            });

            $('body').on('change', '#tupp', function(){
                if( $(this).val() == 'all' ){
                    $('.mega-tagz').slideDown(200);
                }else{
                    $('.mega-tagz').slideUp(200);
                }
            });
        </script>

        <style>
            .field-block{
                padding-bottom: 20px;
            }

            .field-block input, .field-block select{
                float: none;
            }

            .tag-label{
                cursor: pointer;
            }

            .lhm{
                padding-bottom: 16px !important;
            }

            .btns_search{
                padding-bottom: 50px
            }

            .search-result{
                font-weight: 600;
                color: #e13d28;
                margin-top: 10px;
                display: inline-block;
            }

            .mega-tagz{
                display: none;
            }
        </style>
    </body>
</html>