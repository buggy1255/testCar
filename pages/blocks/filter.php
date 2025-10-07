<?php

/* /////////////////////// ПАРАМЕТРЫ /////////////////////////////////////////// */

$ids = array(0);
/** @var mysqli $db */
$GetElems = mysqli_query($db, " SELECT id FROM ws_catalog_elements WHERE section_id = '" . $page_data['id'] . "' ");

while ($Elems = mysqli_fetch_assoc($GetElems)) {
    $ids[] = $Elems['id'];
}

//show( $ids );

$GetElemsConn = mysqli_query($db, " SELECT elem_id FROM ws_catalog_connector WHERE section_id = '" . $page_data['id'] . "' ");

while ($ElemsConn = mysqli_fetch_assoc($GetElemsConn)) {
    $ids[] = $ElemsConn['elem_id'];
}

array_unique($ids);
$sql_select = array();

if (isset($_GET['options'])) {
    $options = trim(strip_tags($_GET['options']));
    $options = strtolower(filter_var($options, FILTER_SANITIZE_SPECIAL_CHARS));
    $options = str_replace("%", "", $options);
    $options = str_replace("`", "", $options);
    $options = str_replace("concat", "", $options);
    $options = explode(',', $options);

    $ArrSortOptions = array(0);

    if (!empty($options)) {
        foreach ($options as $key => $value) {
            $ArrOptions = explode("~", $value);

            if (substr_count($ArrOptions[1], 'undefined') > 0) {
                continue;
            }

            if (is_array($ArrOptions) && is_numeric($ArrOptions[1]) && $ArrOptions[1] > 0) {
                $ArrSortOptions[$ArrOptions[0]][] = (int)$ArrOptions[1];
            }
        }
    }

    foreach ($ArrSortOptions as $key => $ArrParamId) {
        $IdSelect = array(0);

        if (empty($ArrParamId)) {
            continue;
        }

        $GetElemIdSql = "SELECT el.id FROM ws_catalog_elements el
							   INNER JOIN ws_characteristics_values vl ON el.id = vl.elem_id
							   WHERE vl.param_id IN ( " . implode(',', $ArrParamId) . " )
							   AND el.id IN ( " . implode(',', $ids) . " )
							   GROUP BY el.id";


        $GetElemId = mysqli_query($db, $GetElemIdSql);
        while ($ElemId = mysqli_fetch_assoc($GetElemId)) {
            $IdSelect[] = $ElemId['id'];
        }

        $sql_select[] = ' id IN(' . implode(',', $IdSelect) . ')';
    }

    $sql_select = implode(" AND ", $sql_select);
}

if (empty($sql_select)) {
    $sql_select = " AND id IN ( " . implode(',', $ids) . " )";
} else {
    $sql_select = " AND " . $sql_select;
}

/* ////////////////////////// ПАРАМЕТРЫ КОНЕЦ /////////////////////////////////// */

/*----------------------------------- разберем сортировку -----------------------*/

$ORDER_STR = ' sort DESC ';
$order_by = isset($_GET['order']) ? $_GET['order'] : '';
switch ($order_by) {
    case 'rating_desc':
        $ORDER_STR = ' rating DESC ';
        break;

    case 'rating_asc':
        $ORDER_STR = ' rating ASC ';
        break;

    case 'price_desc':
        $ORDER_STR = ' price DESC ';
        break;

    case 'price_asc':
        $ORDER_STR = ' price ASC ';
        break;

    default:
        $ORDER_STR = ' sort DESC ';
        break;

}

/*----------------------------------- разберем сортировку КОНЕЦ -----------------------*/

/*---- categories ----*/

//$SECT = 'AND section_id = '.$pageData['elem_id'];


$query = " SELECT id FROM ws_catalog_elements WHERE active = 1 " . $sql_select;
$Paginator = pagination($query, 10);

//show( $query );

$GetElems = mysqli_query($db, " SELECT image , title_" . $CCpu->lang . " AS title , price , id, duration_id
	FROM ws_catalog_elements 
	WHERE active = 1 " . $sql_select . "
	ORDER BY " . $ORDER_STR . " LIMIT " . $Paginator['from'] . ", " . $Paginator['per_page']);
?>


<div class="filter_btn" onclick="filter_btn()">
    <div class="btn_fil"> <?=$GLOBALS['ar_define_langterms']['MSG_ALL_SEARCH_FILTER']?> </div>
</div>

<div class="wrap_fil_form">
    <div class="filter_form">
        <div class="name_btn_fil">
            <div class="close_fil" onclick="clos_btn_fil()"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_CANCEL']?></div>
            <div class="btn_fil_open"> <?=$GLOBALS['ar_define_langterms']['MSG_ALL_SEARCH_FILTER']?> </div>
        </div>
        <div class="clear"></div>

        <div class="wrap_filter">
            <div class="fil_sort">
                <div class="sort_name"> <?=$GLOBALS['ar_define_langterms']['MSG_ALL_SORTARE_DUPA_PRET']?> </div>
                <div class="sort_options">
                    <select class="sel_opt">
                    	<option value="n"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_SORTARE_DEFAULT']?></option>
                        <option <?if($order_by=='price_asc'){?> selected <?}?>  value="price_asc">
                        	<?=$GLOBALS['ar_define_langterms']['MSG_ALL_DE_LA_MIC_LA_MARE']?>
                        </option>
                        <option <?if($order_by=='price_desc'){?> selected <?}?>  value="price_desc">
                        	<?=$GLOBALS['ar_define_langterms']['MSG_ALL_DE_LA_MARE_LA_MIC']?>
                        </option>
                    </select>
                </div>
            </div>
            <div class="fil_sort">
                <div class="sort_name"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_SORTARE_DUPA_RATING']?></div>
                <div class="sort_options">
                    <select class="sel_opt">
                    	<option value="n"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_SORTARE_DEFAULT']?></option>
                        <option <?if($order_by=='rating_asc'){?> selected <?}?> value="rating_asc">
                        	<?=$GLOBALS['ar_define_langterms']['MSG_ALL_DE_LA_MIC_LA_MARE']?>
                        </option>
                        <option <?if($order_by=='rating_desc'){?> selected <?}?> value="rating_desc">
                        	<?=$GLOBALS['ar_define_langterms']['MSG_ALL_DE_LA_MARE_LA_MIC']?>
                        </option>
                    </select>
                </div>
            </div>

			 <?

				/* собираем ИД основхы категории характеристик которые привязаны к нашей категории */

				$ArrIdParams = array();
				$ArrColors = array();

				/* получаем доступные опции и характеристики */

				$GetConnectorId = mysqli_query($db, " SELECT c.param_id , cc.title_".$CCpu->lang." AS title , cc.section_id
			        FROM ws_characteristics_values c
			        INNER JOIN ws_catalog_elements el ON el.id = c.elem_id
			        INNER JOIN ws_characteristics cc ON cc.id = c.param_id
			        WHERE el.active = 1 && cc.id <> 13
			        GROUP BY c.param_id");

				$CharsId = $SectionIds = array(0);

				/* сохраняем опции к категории */

				while( $ConnectorId = mysqli_fetch_assoc($GetConnectorId) ){
					$CharsId[$ConnectorId['section_id']][] = $ConnectorId['param_id'];
					$SectionIds[] = $ConnectorId['section_id'];
				}

				$SectionIds = array_unique($SectionIds);
				// id <> 13 -> Hors Power no needed filter
				$GetSectionChars = mysqli_query($db, " SELECT title_".$CCpu->lang." AS title ,  id FROM ws_characteristics 
					WHERE id IN ( ". implode( ',' , $SectionIds ) ." ) AND active = 1 AND id <> 13 ORDER BY sort DESC ");

				$i = 0;	

				while( $SectionChars = mysqli_fetch_assoc($GetSectionChars) ){
					$i++;
					$ArrIdParams[$i]['id'] = $SectionChars['id'];
					$ArrIdParams[$i]['title'] = $SectionChars['title'];
				}

             	foreach ( $ArrIdParams as $key => $DataSection ) {
             		//show( $DataSection );
             ?>            

	                <div class="fil_sort">
	                    <div class="sort_name">
	                    	<?=$DataSection['title']?>
	                    </div>
	                    <div class="flt-itcon">

	                    	<?
	                    		$GetElemParams = mysqli_query($db, " SELECT title_".$CCpu->lang." AS title , id 
											 FROM ws_characteristics 
											 WHERE active = 1 AND id IN ( ". implode( ',' , $CharsId[$DataSection['id']] )." )
											 ORDER BY sort DESC, 
		                                     CASE 
		                                         WHEN title_".$CCpu->lang." REGEXP '^[0-9]+$' 
		                                         THEN CAST(title_".$CCpu->lang." AS UNSIGNED) 
		                                         ELSE 999999 
		                                     END, 
		                                     title_".$CCpu->lang." DESC " );

		                    while( $ElemParams = mysqli_fetch_assoc($GetElemParams) ){
									$checked = '';
									if(!empty($ArrSortOptions[$DataSection['id']])){
                                        if( in_array( $ElemParams['id'], $ArrSortOptions[$DataSection['id']] ) ){
                                            $checked = 'active';
                                        }
                                    }
	                    	?>

		                        <div class="fil-param <?=$checked?>" data-parent="<?=$DataSection['id']?>" data-id="<?=$ElemParams['id']?>" >
		                            <?=$ElemParams['title']?>
		                        </div>
		                    <?}?>

	                    </div>
	                </div>
	           <? } ?>
	        </div>

        <div onclick="go_filter()" class="fil_btn_search">
            <div class="intp_form"><?=$GLOBALS['ar_define_langterms']['MSG_ALL_SELECTEAZA_MODEL']?></div>
        </div>
    </div>
</div>

<input type="hidden" name="sort_type" value=" " id="sort_type"/>

<script type="text/javascript">
		<?/* -- фильтр -- */?>
			function go_filter( elem ){
				setTimeout(function(){
					<?/* проверка если мы выбираем основную категорию */?>
					var addr = '<?=$pageData['cpu']?>?';

					<?/* элементы сортировки */?>

					var orderType = $('#sort_type').val();

					<?/* элементы сортировки */?>

					var options = [];

					$('.fil-param.active').each(function(){
						var id = $(this).attr('data-id');
						var parent = $(this).attr('data-parent');

						options.push( parent+"~"+id );
					});

					if( options.length > 0 ){
						addr = addr + "&options="+options;
					}

					if( orderType != undefined ){
						addr = addr + "&order="+orderType;
					}

					location.href = addr.replace('?&', '?');
				},200);
			}

		<?/* -- фильтр КОНЕЦ -- */?>
</script>

