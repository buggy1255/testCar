<?

	if(!isset($_POST) || empty($_POST) || !isset($_POST['task'])){ exit; }
	//todo: check if is authorization is obligatory, if not authorized, not allow to do

	$ar_clean = filter_input_array( INPUT_POST , FILTER_SANITIZE_SPECIAL_CHARS );
	include($_SERVER['DOCUMENT_ROOT']."/lib/libmail/libmail.php");

/* подргрузка даты - для вторго календаря при выборе дат для резервации */

	if($_POST['task']=='selDate'){
		$s1date = explode( '/' , $ar_clean['s1date'] );
		$datePickerMin = $s1date['2'] .'-'.($s1date['1']-1).'-'.($s1date['0']);

		if( $s1date['0'] == 31 ){
			$datePickerMin = strtotime( $datePickerMin );
		}else{
			$datePickerMin = strtotime( $datePickerMin . " +1day ");
		}

		$s1date = $s1date['2'] .'-'.($s1date['1']).'-'.($s1date['0']);
		//show( $s1date );

		$s1date = strtotime($s1date);
		$classInpt = $ar_clean['classInpt'];
		if( $s1date ){

?>
			<input type="text" class=" <?=$classInpt?> datetimepicker1 dateClass" placeholder="-"/>

			<script type="text/javascript">
				$( document ).ready(function() {
					setTimeout( function(){
						$('.datetimepicker1').datetimepicker({
				             format: 'd/m/y',
				             minDate: new Date( <?=date( 'Y,m,d' , $datePickerMin )?> ),
				             weekStart: 1,
				             language: '<?=$CCpu->lang?>',
				             timepicker:false,
				         });
				     }, 400 );
				});
			</script>
<?
		}
	}
/* подргрузка даты КОНЕЦ */


/* смена валюты */

if ($_POST['task'] == 'selectСourse') {
    if (empty($ar_clean['code']) || !is_numeric($ar_clean['code']) || ($ar_clean['code'] < 1 || $ar_clean['code'] > 3)) {
        exit('Error!');
    }

    $code = (int)$ar_clean['code'];
    $_SESSION['var_change'] = $code;
}

/* БРОНИРОВАНИЕ */

if ($_POST['task'] == 'reservedata1') {
    $_SESSION['reserve']['1'] = '';
    unset($_SESSION['reserve']['1']);

    $obligatoryFields = array(
            'overPoint',
            'dateFrom',
            'dateFrom',
            'hourFrom',
            'returnPoint',
            'returnDateFrom',
            'returnHourFrom',
            'age'
    );

    foreach ($obligatoryFields AS $value) {
        if (empty($ar_clean[$value])) {
            echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
            exit;
        }
    }

    if (!is_numeric($ar_clean['overPoint']) || !is_numeric($ar_clean['returnPoint']) || !is_numeric($ar_clean['age'])) {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
        exit;
    }

    if (!validationDate($ar_clean['dateFrom']) || !validationDate($ar_clean['returnDateFrom'])) {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
        exit;
    }

    if (!validationHour($ar_clean['hourFrom']) || !validationHour($ar_clean['returnHourFrom'])) {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
        exit;
    }

    /* проверка если время не меньше чем сейчас */
    $dFrom = $ar_clean['dateFrom'];

    if ($dFrom == date('d/m/y')) {
        $hour = $GLOBALS['ar_define_settings']['MIN_HOURS'];
        if ($ar_clean['hourFrom'] < date('H:i', strtotime('+' . $hour . ' hour'))) {
            exit($GLOBALS['ar_define_langterms']['MSG_ALL_VREMYA_BRONIROVANIE_NE_MOZHET_BYTI_RANISHE_MENISHE'] . ' ' . $hour . ' ' . $GLOBALS['ar_define_langterms']['MSG_ALL_HOURS']);
        }
    }

    $dateFrom = explode('/', $ar_clean['dateFrom']);
    $dateFrom = $dateFrom['2'] . '-' . $dateFrom['1'] . '-' . $dateFrom['0'];
    $timeFrom = strtotime($dateFrom);

    $dateReturn = explode('/', $ar_clean['returnDateFrom']);
    $dateReturn = $dateReturn['2'] . '-' . $dateReturn['1'] . '-' . $dateReturn['0'];
    $timeReturn = strtotime($dateReturn);

    // Checking if time return is not less that time from.
    if ($timeReturn < $timeFrom) {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
        exit;
    }

	// Add minimum 3-day rental check
	$minRentalDays = 3;
	$daysDifference = ($timeReturn - $timeFrom) / (60 * 60 * 24); // Convert seconds to days

	if ($daysDifference < $minRentalDays) {
		echo $GLOBALS['ar_define_langterms']['MSG_ALL_MINIMUM_RENTAL_PERIOD'];
		exit;
	}

	$_SESSION['reserve']['1']['overPoint'] = $ar_clean['overPoint'];
    $_SESSION['reserve']['1']['dateFrom'] = $ar_clean['dateFrom'];
    $_SESSION['reserve']['1']['hourFrom'] = $ar_clean['hourFrom'];
    $_SESSION['reserve']['1']['returnPoint'] = $ar_clean['returnPoint'];
    $_SESSION['reserve']['1']['returnDateFrom'] = $ar_clean['returnDateFrom'];
    $_SESSION['reserve']['1']['returnHourFrom'] = $ar_clean['returnHourFrom'];
    $_SESSION['reserve']['1']['age'] = $ar_clean['age'];

    echo 'ok';
    exit;
}

if ($_POST['task'] == 'reservedata2') {
    if (empty($ar_clean['elem_id']) OR !is_numeric($ar_clean['elem_id'])) {
        echo 'ERROR';
        exit;
    }

	/** @var mysqli $db */
	$GetElem = mysqli_query($db, " SELECT sort FROM ws_catalog_elements WHERE id = '" . (int)$ar_clean['elem_id'] . "' AND active = 1 LIMIT 1 ");

    if (mysqli_num_rows($GetElem) === 0) {
        exit($GLOBALS['ar_define_langterms']['MSG_ALL_NET_TAKOGO_ELEMENTA']);
    }

    $_SESSION['reserve']['2'] = '';
    unset($_SESSION['reserve']['2']);
    $_SESSION['reserve']['2']['elem_id'] = (int)$ar_clean['elem_id'];

    echo 'ok';
    exit;
}

if ($_POST['task'] == 'reservedata3') {
    $_SESSION['reserve']['3'] = '';
    unset($_SESSION['reserve']['3']);

    $_SESSION['reserve']['3']['options'] = (!empty($ar_clean['options'])) ? $ar_clean['options'] : '';

    echo 'ok';
    exit;
}

// шаг 4

if ($_POST['task'] == 'reservedata4') {
	get_smtp_setting();
	$_SESSION['reserve']['4'] = '';
	unset($_SESSION['reserve']['4']);

    if (empty($ar_clean['name']) OR empty($ar_clean['email']) OR empty($ar_clean['phone'])){
        exit('Error');
    }

    if (empty($_SESSION['reserve']['2']['elem_id']) || !is_numeric($_SESSION['reserve']['2']['elem_id'])) {
        exit('Error session');
    }

	/** @var mysqli $db */
	$GetCarData = mysqli_query($db, "SELECT * 
				FROM ws_catalog_elements 
				WHERE id = '" . $_SESSION['reserve']['2']['elem_id'] . "' LIMIT 1");

    if (!mysqli_num_rows($GetCarData)){
        exit('Car not exist');
    }

    $CarData = mysqli_fetch_assoc($GetCarData);

	$_SESSION['reserve']['4']['name'] = ucwords(strtolower(trim($ar_clean['name'])));
	$_SESSION['reserve']['4']['email'] = strtolower(trim($ar_clean['email']));
	$_SESSION['reserve']['4']['phone'] = trim($ar_clean['phone']);


	$name = $_SESSION['reserve']['4']['name'];
	$phone = $_SESSION['reserve']['4']['phone'];
	$email = $_SESSION['reserve']['4']['email'];

	if (!empty($ar_clean['commment']) && mb_strlen($ar_clean['commment']) > 512){
        exit($GLOBALS['ar_define_langterms']['MSG_ALL_COMMENT_NOT_MORE_THAN_512']);
    }

	$comment = (!empty($ar_clean['comment'])) ? trim($ar_clean['comment']) : '';

	$auth = 0;
    $user = '';

	if ($Auth->isAuthorized()) {
		$auth = 1;
		$user = $_SESSION['user_id'];
	}
	else {
	    if (!empty($_SESSION['auth']['hash'])){
            $user = $_SESSION['auth']['hash'];
        }
	}

	$dataTitle = $dataValue = array();
	$dataTitle[] = "  date ";
	$dataValue[] = "  NOW() ";
	$dataTitle[] = "  user ";
	$dataValue[] = "  '" . $user . "' ";
	$dataTitle[] = "  auth ";
	$dataValue[] = "  '" . $auth . "' ";
	$dataTitle[] = "  total_sum ";
	$dataValue[] = "  '" . $_SESSION['reserve']['total_sum'] . "' ";
	$dataTitle[] = "  name ";
	$dataValue[] = "  '" . $name . "' ";
	$dataTitle[] = "  phone ";
	$dataValue[] = "  '" . $phone . "' ";
	$dataTitle[] = "  email ";
	$dataValue[] = "  '" . $email . "' ";
	$dataTitle[] = "  elem_id ";
	$dataValue[] = "  '" . $_SESSION['reserve']['2']['elem_id'] . "' ";


	$dataTitle[] = "  pointFrom ";
	$dataValue[] = "  '" . $_SESSION['reserve']['1']['overPoint'] . "' ";
	$dateFrom = explode('/', $_SESSION['reserve']['1']['dateFrom']);
	$dateFrom = $dateFrom['2'] . '-' . $dateFrom['1'] . '-' . $dateFrom['0'];
	$timeFrom = strtotime($dateFrom);
	$dataTitle[] = "  dateFrom ";
	$dataValue[] = "  '" . date('Y-m-d', $timeFrom) . "' ";
	$dataTitle[] = "  hourFrom ";
	$dataValue[] = "  '" . $_SESSION['reserve']['1']['hourFrom'] . "' ";
	$dataTitle[] = "  pointReturn ";
	$dataValue[] = "  '" . $_SESSION['reserve']['1']['returnPoint'] . "' ";
	$dateReturn = explode('/', $_SESSION['reserve']['1']['returnDateFrom']);

	$dateReturn = $dateReturn['2'] . '-' . $dateReturn['1'] . '-' . $dateReturn['0'];
	$timeReturn = strtotime($dateReturn);
	$dataTitle[] = "  dateReturn ";
	$dataValue[] = "  '" . date('Y-m-d', $timeReturn) . "' ";
	$dataTitle[] = "  hourReturn ";
	$dataValue[] = "  '" . $_SESSION['reserve']['1']['returnHourFrom'] . "' ";

	$dataTitle[] = "  lang ";
	$dataValue[] = "  '".$CCpu->lang."' ";

	$dataTitle[] = "  status ";
	$dataValue[] = "  '0' ";
	$dataTitle[] = "  age ";
	$dataValue[] = "  '" . $_SESSION['reserve']['1']['age'] . "' ";

	$dataTitle[] = "  currency ";
	$dataValue[] = "  '" . get_ue_id() . "' ";
	$dataTitle[] = "  deposit ";
	$dataValue[] = "  '" . get_price($CarData['deposit']) . "' ";
	$dataTitle[] = "  comment ";
	if (!empty($comment)){
        $dataValue[] = "  '" . mysqli_real_escape_string($db, $comment) . "' ";
    }
	else {
        $dataValue[] = "  NULL ";
    }

	$qInsert = mysqli_query($db, "INSERT INTO ws_orders ( " . implode(',', $dataTitle) . " ) VALUES ( " . implode(',', $dataValue) . " ) ");
	$order_id = mysqli_insert_id($db);
	$ArrAd = make_explode($_SESSION['reserve']['3']['options']);

	foreach ($ArrAd as $key => $id) {
		$qInsert = mysqli_query($db,"INSERT INTO ws_orders_options 

				( section_id , elem_id ) VALUES ( '" . $order_id . "' , '" . $id . "' ) ");
	}

	// отправка письма
	//$mailText = $Main->GetPageIncData('MAIL_1');
	//$mailText = str_replace("#order_id#", $order_id, $mailText);
	//$mailText = str_replace("#user#", $name, $mailText);
	//$mailText = str_replace("#order_id#", $order_id, $mailText);


	$https = '';

	if ($_SERVER['HTTPS'] == 'on') {
		$https = 'https://';
	} else {
		$https = 'http://';
	}

	$domain = $https . $_SERVER['HTTP_HOST'];
	$body = file_get_contents($_SERVER['DOCUMENT_ROOT'] . '/mail_template/order_mail/base.php');

	//что заменить
	$Subj = array(
		'#domain#',
		'#phone#',
		'#address#',
		'#email#',
		'#name_client#',
		'#hello_world#',
		'#mail_top_text#',
		'#code_word#',
		'#order_id#',
		'#detail_client_word#',
		'#name_word#',
		'#email_word#',
		'#phone_word#',
		'#email_client#',
		'#phone_client#'
	);

	//на что
	$ReplaceSubj = array(
		$domain,
		$GLOBALS['ar_define_settings']['EMAIL_PHONE'],
		$GLOBALS['ar_define_settings']['EMAIL_ADDRESS'],
		$GLOBALS['ar_define_settings']['EMAIL_EMAIL'],
		$name,
		$GLOBALS['ar_define_langterms']['MSG_ALL_HELLO_WORD'],
		strip_tags($Main->GetPageIncData('MAIL_TOP_TEXT', $CCpu->lang), '<a><strong><b><p><span><i><u><br>'),
		$GLOBALS['ar_define_langterms']['MSG_ALL_CODE_RESERVE'],
		$order_id,
		$GLOBALS['ar_define_langterms']['MSG_ALL_DETAIL_CLIENT'],
		$GLOBALS['ar_define_langterms']['MSG_ALL_NUMELE_PRENUMELE_PATRONIMIC'],
		$GLOBALS['ar_define_langterms']['MSG_ALL_EMAIL'],
		$GLOBALS['ar_define_langterms']['MSG_ALL_PHONE'],
		$email,
		$phone
	);

	$body = str_replace($Subj, $ReplaceSubj, $body);

	//лучше по старинке думаю
	$body = str_replace('#detail_reserve_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_DETAIL_RESERVE_WORD'], $body);
	$body = str_replace('#punct_de_preluare_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_PUNCTUL_DE_PRELUARE'], $body);
	$body = str_replace('#punct_de_returnare_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_THE_RETURN_POINT'], $body);
	$body = str_replace('#perioada_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_PERIOADA'], $body);

	//от когда и до когда
	$GetP = mysqli_query($db, " SELECT id,title_" . $CCpu->lang . " AS title 
		                		FROM ws_take_over_point
		                		WHERE id = '" . $_SESSION['reserve']['1']['overPoint'] . "' LIMIT 1 ");

	$P = mysqli_fetch_assoc($GetP);

	$body = str_replace('#punct_de_preluare#', $_SESSION['reserve']['1']['dateFrom'] . ', ' . $_SESSION['reserve']['1']['hourFrom'] . '<br>' . $P['title'], $body);
	$GetOP = mysqli_query($db, " SELECT title_" . $CCpu->lang . " AS title 
		                		FROM ws_return_point
		                		WHERE id = '" . $_SESSION['reserve']['1']['returnPoint'] . "' LIMIT 1 ");

	$OP = mysqli_fetch_assoc($GetOP);
	$body = str_replace('#punct_de_returnare#', $_SESSION['reserve']['1']['returnDateFrom'] . ', ' . $_SESSION['reserve']['1']['returnHourFrom'] . '<br>' . $OP['title'], $body);

	//от когда и до когда КОНЕЦ
	$body = str_replace('#day#', $_SESSION['reserve']['day_reserve'], $body);
	$body = str_replace('#day_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_DAYS'], $body);
	$body = str_replace('#selected_auto_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_SELECTED_AUTO_WORD'], $body);
	$body = str_replace('#marca_auto_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_MARCA_AUTO_WORD'], $body);
	$body = str_replace('#price_day_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_RATA_PENTRU_VEHICUL'], $body);
	$body = str_replace('#total_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_TOTAL'], $body);
	$body = str_replace('#deposit_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_DEPOSIT'], $body);

	// данные по машине
	$body = str_replace('#path_img_car#', $domain . '/upload/cars/' . $CarData['image'], $body);
	$body = str_replace('#title_car#', $CarData['title_' . $CCpu->lang], $body);
	$B = day_reserve($_SESSION['reserve']['2']['elem_id'], $_SESSION['reserve']['day_reserve']);
	$body = str_replace('#price_day#', $B['price_one_day'], $body);
	$body = str_replace('#price_car#', $B['price'], $body);
	$body = str_replace('#currency#', get_ue(), $body);
	// данные по машине КОНЕЦ


	//дополнительные опции к авто
	$ArrOptionsId = make_explode($_SESSION['reserve']['3']['options']);
	$ADDITIONAL_LIST_HTML = '';
	$AddPrice = 0;

	if (!empty($ArrOptionsId)) {
		$ADDITIONAL_LIST_HTML .= '<tr>

				<td colspan="6" bgcolor="#ececec" height="20"></td>

			</tr>';


		foreach ($ArrOptionsId as $k => $AddId) {
			$GetAddData = mysqli_query($db, " SELECT * FROM ws_additional_services WHERE id = '" . $AddId . "' LIMIT 1 ");
			$AddData = mysqli_fetch_assoc($GetAddData);

			$AddPrice += get_price($AddData['price'] * $_SESSION['reserve']['day_reserve']);
			$ADDITIONAL_LIST_HTML .= '<tr bgcolor="#ececec">
						<td colspan="2" width="40%" height="20px" valign="top" style="padding-left: 15px;">
							<span style="font-size: 14px; color: #171f31; text-decoration: none;font-family: Arial, Helvetica, sans-serif; ">' . $AddData['title_' . $CCpu->lang] . '</span>
						</td>
						<td colspan="2" width="40%"></td>
						<td colspan="2" width="20%" height="20px" valign="top">
							<span style="font-size: 14px; color: #171f31; text-decoration: none;font-family: Arial, Helvetica, sans-serif; ">' . get_price($AddData['price'] * $_SESSION['reserve']['day_reserve']) . ' ' . get_ue() .'</span>
						</td>
					</tr>';
		}

		$ADDITIONAL_LIST_HTML .= '<tr>
					<td colspan="6" bgcolor="#ececec" height="10"></td>
				</tr>';
	}

	$body = str_replace('#additional_list#', $ADDITIONAL_LIST_HTML, $body);

	//дополнительные опции к авто КОНЕЦ

	$body = str_replace('#total_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_TOTAL'], $body);
	$body = str_replace('#rata_car_word#', $GLOBALS['ar_define_langterms']['MSG_TOTAL_PRICE_CAR'], $body);
	$body = str_replace('#rata_car#', $B['price'], $body);
	$body = str_replace('#add_option_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_OPTIUNI_ADITIONALE'], $body);
	$body = str_replace('#add_option#', $AddPrice, $body);
	$body = str_replace('#deposit#', get_price($CarData['deposit']), $body);
	$body = str_replace('#discount_word#', $GLOBALS['ar_define_langterms']['MSG_ALL_RATA_DE_REDUCERE'], $body);
	$body = str_replace('#discount#', '- ' . (int)$B['discount'], $body);
	$body = str_replace('#total_to_pay#', $GLOBALS['ar_define_langterms']['MSG_ALL_TOTAL_SPRE_PLATA'], $body);

	$total = ($B['price'] + $AddPrice + get_price($CarData['deposit'])) - (int)$B['discount'];
	$body = str_replace('#total#', $total, $body);
	//$body = str_replace( '#add_option_word#' , $GLOBALS['ar_define_langterms']['MSG_ALL_OPTIUNI_ADITIONALE'] , $body );

	$m = new Mail;
	$m->From($GLOBALS['ar_define_settings']['SMTP_MAIL']); // от кого отправляется почта
	$m->To($email); // кому адресованно
	$m->Subject($GLOBALS['ar_define_langterms']['MSG_ALL_ZAVERSHENIE_ZAKAZA']);
	$m->Body($body, "html");
	$m->Priority(3); // приоритет письма
	$m->smtp_on( $smtp_server, $smtp_mail, $smtp_pass, $smtp_port );

    $m->Send();

    // Send

    $copy = new Mail;
    $copy->From($GLOBALS['ar_define_settings']['SMTP_MAIL']);
	$copy->To($GLOBALS['ar_define_settings']['EMAIL_EMAIL']);
	$copy->Subject("Новое бронирование от: {$name}");

	if ($CCpu->lang == 'ru'){
	    $lang = 'Русский';
	}
	elseif($CCpu->lang == 'ro'){
		$lang = 'Румынский';
	}
	else{
		$lang = 'Английский';
	}

	$text = "Клиент: {$name}\n";
	$text .= "Email: {$email}\n";
	$text .= "Телефон: {$phone}\n";
	$text .= "Язык: {$lang}\n";
	$text .= "Авто: {$CarData['title_' . $CCpu->lang]}\n";
	$text .= "С: {$_SESSION['reserve']['1']['dateFrom']}\n";
	$text .= "По: {$_SESSION['reserve']['1']['returnDateFrom']}\n";
	$text .= "Период: {$_SESSION['reserve']['day_reserve']} Дней\n";
	$ue = get_ue();

	$text .= "Всего к оплате: {$total} {$ue}\n";
	$link = "https://www.car4rent.md/ws/orders/orders/edit_elem.php?id={$order_id}";
	$text .= "Линк: {$link}\n";

	if ($comment){
	    $text .= "Комментарий к заказу: \n{$comment}\n";
    }

    $text .= "\n\nПожалуйста не отвечайте на данное сообщение. Ее отсылает ваш сайт.\n";

	$copy->Body($text);
	$copy->Priority(3);
	$copy->smtp_on( $smtp_server, $smtp_mail, $smtp_pass, $smtp_port );
	$copy->Send();

	echo 'ok';
    exit;
}

/* БРОНИРОВАНИЕ КОНЕЦ  */

/* РЕГИСТРАЦИЯ / АВТОРИЗАЦИЯ */
//востановить пароль
if ($_POST['task'] == 'resstorepass_f') {
    $email = trim($ar_clean['email']);
	/** @var mysqli $db */
	$query = mysqli_query($db, " SELECT `id`, `email`, `password` FROM `ws_clients` WHERE `email`='" . $email . "' ");

    if (mysqli_num_rows($query) == 0) {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_TAKOJ_POLIZOVATELI_NE_NAJDEN'];
        exit;
    } else {
        $profileInfo = mysqli_fetch_assoc($query);
        $newPassSend = uniqid('', true);

        $newPass = hash('sha512', $newPassSend);
        $newPass = hash('sha512', $newPass . $_security_salt);

        get_smtp_setting();

        // текст письма

        $contents = $Main->GetPageIncData('SEND_NEW_PASSWORD', $CCpu->lang);
        $contents = str_replace('{newpass}', $newPassSend, $contents);

        $m = new Mail;
        $m->From($smtp_mail); // от кого отправляется почта
        $m->To($profileInfo['email']); // кому адресованно
        $m->Subject($GLOBALS['ar_define_langterms']['MSG_ALL_CAR_-_VOSSTANOVLENIE_PAROLYA']);
        $m->Body($contents, "html");
        $m->Priority(3);    // приоритет письма
        $m->smtp_on( $smtp_server, $smtp_mail, $smtp_pass, $smtp_port );
        if ($m->Send()) {
            $query1 = mysqli_query($db, " UPDATE ws_clients SET password='" . $newPass . "' WHERE id='" . $profileInfo['id'] . "' ");
            if ($query1) {
                echo 'ok';
                exit;
            }

            echo 'Error DB';
            exit;
        } else {
            echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
            exit;
        }
    }
}

//авторизация / регистрация по социальной сети
if ($_POST['task'] == 'socialauth') {
    if (empty($_POST['token'])) {
        echo 'Error Token';
        exit;
    }

    try {
        $Auth->socialAuth($_POST['token']);

        echo 'ok';
        exit;
    } catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

//соединение соц. сетей в Личном кабиенете пользователя
if ($_POST['task'] == 'socialConnect') {
    if (empty($_POST['token'])) {
        echo 'Error Token';
        exit;
    }

    $user = $Auth->socialAuthUlogin($_POST['token']);

    if (!$user) {
        // Probleme cu logare prin social auth.
        echo 'Error social Authorization!';
        exit;
    }

    $ulogin_FirtName = $user['first_name'];
    $ulogin_LastName = $user['last_name'];
    $ulogin_phone = $user['phone'];
    $ulogin_Email = $user['email'];
    $ulogin_Uid = $user['uid'];
    $ulogin_Network = $user['network'];

    $getClientConnect = mysqli_query($db,
        "INSERT INTO `ws_clients_connector` 
                			(`client_id`, `email`, `network` , `uid` ) 
		               VALUES 
		               		(" . $_SESSION['user_data']['id'] . ",'" . $ulogin_Email . "', '" . $ulogin_Network . "' , '" . $ulogin_Uid . "') ");

    echo 'ok';
    exit;
}

if ($_POST['task'] == 'delete_connect') {
    if (!$Auth->isAuthorized()) {
        exit();
    }

    if (empty($_POST['id']) || !is_numeric($_POST['id'])){
        exit('Error');
    }

    $id = intval($_POST['id']);


    $remove = mysqli_query($db, "DELETE FROM ws_clients_connector WHERE id = '" . $id . "' AND client_id = " . (int)$_SESSION['user_data']['id']);
    if ($remove) {
        echo "ok";
        exit;
    }
}

/*****************/

/* авторизация  */
if ($_POST['task'] == 'auth_f') {
    try {
        $Auth->login();

        echo 'ok';
        exit;
    } catch (Exception $e) {
        echo $e->getMessage();
        exit;
    }
}

	/*Регистрация  */

if ($_POST['task'] == 'register_f') {

	$name = trim($ar_clean['name']);
	$email = trim($ar_clean['email']);
	$phone = trim($ar_clean['phone']);
	$address = trim($ar_clean['address']);
	$newPassSend = trim($ar_clean['password']);
	$password = hash('sha512', trim($ar_clean['password']));
	$password = hash('sha512', $password . $_security_salt);

	$query = mysqli_query($db, " SELECT `email` FROM `ws_clients` WHERE `email` = '" . $email . "' LIMIT 1 ");

	if (mysqli_num_rows($query) > 0) {
		echo $GLOBALS['ar_define_langterms']['MSG_ALL_TAKOJ_POLIZOVATELI_UZHE_SUSCHEVSTVUET'];
		exit;
	} else {
		$hash = md5(time() . $name);
		$crypt = hash('sha512', uniqid('', true));
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		$dataTitle = $dataValue = array();
		$dataTitle[] = " email ";
		$dataValue[] = " '" . $email . "' ";
		$dataTitle[] = " phone ";
		$dataValue[] = " '" . $phone . "' ";
		$dataTitle[] = " address ";
		$dataValue[] = " '" . $address . "' ";
		$dataTitle[] = " password ";
		$dataValue[] = " '" . $password . "' ";
		$dataTitle[] = " name ";
		$dataValue[] = " '" . $name . "' ";
		$dataTitle[] = " user_agent ";
		$dataValue[] = " '" . $user_agent . "' ";
		$dataTitle[] = " logyn_crypt ";
		$dataValue[] = " '" . $crypt . "' ";
		$dataTitle[] = " hash ";
		$dataValue[] = " '" . $hash . "' ";
		$dataTitle[] = " date_activation ";
		$dataValue[] = " NOW() ";
		$dataTitle[] = " last_auth ";
		$dataValue[] = " NOW() ";
		$dataTitle[] = " auth_ip ";
		$dataValue[] = " " . ip2long($_SERVER['REMOTE_ADDR']) . " ";

		$qInsert = mysqli_query($db, " INSERT INTO ws_clients (  " . implode(',', $dataTitle) . " ) VALUES ( " . implode(',', $dataValue) . " ) ");

		$clientId = mysqli_insert_id();

        if ($qInsert) {
            try {
                $Auth->login();

                echo 'ok';
                exit;
            } catch (Exception $e) {
                echo $e->getMessage();
                exit;
            }
        } else {
            echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
            exit;
        }
	}
}

/* РЕГИСТРАЦИЯ / АВТОРИЗАЦИЯ КОНЕЦ */
	//рэйтинг товара

if ($_POST['task'] == 'rating_f') {
    $elem_id = (!empty($ar_clean['elem_id']) && is_numeric($ar_clean['elem_id'])) ? trim((int)$ar_clean['elem_id']) : 0;
    $rating = (!empty($ar_clean['rating']) && is_numeric($ar_clean['rating'])) ? trim((int)$ar_clean['rating']) : 0;
    $name = (!empty($ar_clean['name'])) ? trim($ar_clean['name']) : '';
    $email = (!empty($ar_clean['email'])) ? trim($ar_clean['email']) : '';
    $comment = (!empty($ar_clean['comment'])) ? trim($ar_clean['comment']) : '';

    if ($elem_id == 0 || $rating == 0 || empty($name) || empty($comment) || empty($name)) {
        exit($GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX']);
    }

    $C = mysqli_query($db, "SELECT id FROM ws_rating_products WHERE elem_id = '" . $elem_id . "' AND (ip = '" . ip2long($_SERVER['REMOTE_ADDR']) . "' OR email = '" . $email . "') LIMIT 1");

    if (mysqli_num_rows($C)) {
        exit($GLOBALS['ar_define_langterms']['MSG_ALL_YOU_HAVE_ALREADY_VOTED_THIS_CAR']);
    }

    $dataTitle = $dataValue = array();
    $dataTitle[] = " name ";
    $dataValue[] = " '" . $name . "' ";
    $dataTitle[] = " email ";
    $dataValue[] = " '" . $email . "' ";
    $dataTitle[] = " comment ";
    $dataValue[] = " '" . $comment . "' ";
    $dataTitle[] = " elem_id ";
    $dataValue[] = " '" . $elem_id . "' ";
    $dataTitle[] = " rating ";
    $dataValue[] = " '" . $rating . "' ";
    $dataTitle[] = " ip ";
    $dataValue[] = " '" . ip2long($_SERVER['REMOTE_ADDR']) . "' ";

    $qInsert = mysqli_query($db, "INSERT INTO ws_rating_products ( date , " . implode(',', $dataTitle) . " ) VALUES ( NOW() , " . implode(',', $dataValue) . " ) ");

    if ($qInsert) {
        $RatData = rating_product($elem_id);
        $upd = mysqli_query($db, " UPDATE `ws_catalog_elements` SET `rating` = '" . $RatData['count_nf'] . "' WHERE id = '" . $elem_id . "' ");
        echo 'ok';
        exit;
    } else {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
        exit;
    }
}


if($_POST['task'] == 'auth_popup'){
    ?>

    <div id="auth_popup" class="popup_overlay popenterBlock">
        <script type="text/javascript">
            $(document).ready(function() {
                popupCloseControl();
            });
        </script>
        <div class="standart_popup_width popup_window_wrapper">
            <div onclick="closePopupName( '#auth_popup' )" class="popup_close_button tran "></div>
            <div class="popup_window_title">
                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_AUTORIZARE']?>
            </div>
            <div class="pop_content">
                <div class="space-row-16">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="b-input-block-f">
                            <div class="b-f-lable">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_EMAIL']?>
                            </div>
                            <div class="b-fq-inpt">
                                <input type="text" name="some_name" value="" id="a_email">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="b-input-block-f">
                            <div class="b-f-lable">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_PAROLA']?>
                            </div>
                            <div class="b-fq-inpt">
                                <input type="password" name="some_name" value="" id="a_password">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="b-input-block-f-q">
                            <div onclick="popup_show( 'restorepass_popup' , '#auth_popup' )" class="respass tran">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_VOSSTANOVITI_PAROLI_']?>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div onclick="auth_f()" class="add-bklk">
                            <span  class="add-b tran popenterButton ">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_VOJTI']?>
                            </span>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="b-f-label social-blk-tl ">
                           <?=$GLOBALS['ar_define_langterms']['MSG_ALL_ILI_VOJTI_CHEREZ_']?>
                        </div>
                        <div class="social-blk">
                            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/social_auth.php")?>
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
		function register_social(e) {
			$.getJSON("//ulogin.ru/token.php?host=" + encodeURIComponent(location.toString()) + "&token=" + e + "&callback=?", function (t) {
				t = $.parseJSON(t.toString()), t.error || $.ajax({
					type: "POST",
					url: "<?=$defaultLinks['ajax']?>",
					data: "task=socialauth&token=" + e,
					success: function (e) {
						"ok" == $.trim(e) ? location.href = "<?=$CCpu->writelink(45)?>" : alert($.trim(e))
					}
				});
			});
		}

		function auth_f() {
			var a = 0;
			$('.required_field').removeClass('required_field');

			var email = $.trim($('#a_email').val());
			var password = $.trim($('#a_password').val());

			if (email.length < 3 || email.length > 170) {
				$('#a_email').addClass('required_field');
				a++;
			}

			if (password.length < 4 || password.length > 1170) {
				$('#a_password').addClass('required_field');
				a++;
			}

			if (!validateEmail(email) && a == 0) {
				$('#a_email').addClass('required_field');
				show('<?=$GLOBALS['ar_define_langterms']['MSG_ALL_INTRODUCETI_EMAIL_CORECT']?>');
				a++;
			}

			if (a > 0) {
				$('.required_field').first().focus();

				return false;
			} else {
				loader();

				var fd = new FormData;
				fd.append('task', 'auth_f');
				fd.append('email', email);
				fd.append('password', password);

				$.ajax({
					url: '<?=$defaultLinks['ajax']?>',
					data: fd,
					processData: false,
					contentType: false,
					type: 'POST',
					success: function (msg) {
						loader_destroy();
						if ($.trim(msg) == 'ok') {
							location.href = "<?=$CCpu->writelink(45)?>";
						} else {
							show(msg);
						}
					}
				});
			}
		}
    </script>
<?
}


if($_POST['task'] == 'restorepass_popup'){
    ?>
    <div id="restorepass_popup" class="popup_overlay popenterBlock">

        <script type="text/javascript">
            $(document).ready(function() {
                popupCloseControl();
            });
        </script>

        <div class="standart_popup_width popup_window_wrapper">
            <div onclick="closePopupName( '#restorepass_popup' )" class="popup_close_button tran "></div>
            <div class="popup_window_title">
                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_VOSSTANOVITI_PAROLI_']?>
            </div>
            <div class="pop_content">
                <div class="space-row-16">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="b-input-block-f">
                            <div class="b-f-lable">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_EMAIL']?>
                            </div>
                            <div class="b-fq-inpt">
                                <input type="text" name="some_name" value="" id="a_email">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="add-bklk">
                            <span onclick="resstorepass_f()" class="add-b tran popenterButton ">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_VOSSTANOVITI']?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
       function resstorepass_f() {
			var a = 0;
			$('.required_field').removeClass('required_field');

			var email = $.trim($('#a_email').val());
		    if( email.length < 3 || email.length > 170 ){
		        $('#a_email').addClass('required_field');a++;
		    }

		    if( !validateEmail( email ) && a == 0 ){
		    	$('#a_email').addClass('required_field');
		    	show( '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_INTRODUCETI_EMAIL_CORECT']?>' );a++;
		    }

		    if(a>0){
		       $('.required_field').first().focus();
		       return false;
			 }else{
			 	loader();
		        var fd = new FormData;

		        fd.append('task', 'resstorepass_f' );
		        fd.append('email', email );

 					$.ajax({
			            url: "<?=$defaultLinks['ajax']?>",
			            data: fd,
			            processData: false,
			            contentType: false,
			            type: 'POST',
			            success: function ( msg ) {
			              	loader_destroy();
			                 if( $.trim(msg) == 'ok' ){
			                 	 closePopupName( '#restorepass_popup' );
			                     show( '<?=$GLOBALS['ar_define_langterms']['MSG_ALL_NOVYJ_PAROLI_BYL_OTPRAVLEN_NA_VASHU_POCHTU']?>' );
			                  }else{
			                     show( msg );
					          }
					      }
					 });
		   	 }
		}
    </script>
<?
}


if($_POST['task'] == 'register_popup'){?>

    <div id="register_popup" data-click="false" class="popup_overlay popenterBlock" style="display: block;">
        <script type="text/javascript">
            $(document).ready(function() {
                popupCloseControl();

                $('.fil-param').click(function(){
                    $(this).toggleClass('active');
                });
            });
        </script>

        <div class="standart_popup_width popup_window_wrapper">
            <div onclick="closePopupName( '#register_popup' )" class="popup_close_button tran "></div>
            <div class="popup_window_title">
                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_REGISTRACIYA']?>
            </div>
            <div class="pop_content">
                <div class="space-row-16">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="b-input-block-f">
                            <label class="b-f-lable" for="r_name">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_NAME']?>
                            </label>
                            <div class="b-fq-inpt">
                                <input type="text" name="some_name" value="" id="r_name">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="b-input-block-f">
                            <label class="b-f-lable" for="r_email">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_EMAIL']?>
                            </label>
                            <div class="b-fq-inpt">
                                <input type="text" name="some_name" value="" id="r_email">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="b-input-block-f">
                            <label class="b-f-lable-no-oblig" for="r_phone">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_PHONE']?>
                            </label>
                            <div class="b-fq-inpt">
                                <input type="text" name="some_name" class="num-font" value="" id="r_phone">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="b-input-block-f">
                            <label class="b-f-lable-no-oblig" for="r_address">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_ADDRESS_WORD']?>
                            </label>
                            <div class="b-fq-inpt">
                                <input type="text" name="some_name" value="" id="r_address">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="b-input-block-f">
                            <label class="b-f-lable" for="r_password">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_PAROLI']?>
                            </label>
                            <div class="b-fq-inpt">
                                <input type="password" name="some_name" value="" id="r_password">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 ">
                        <div class="b-input-block-f">
                            <label class="b-f-lable" for="r_repassword">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_POVTORITE_PAROLI_']?>
                            </label>

                            <div class="b-fq-inpt">
                                <input type="password" name="some_name" value="" id="r_repassword">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="b-input-block-f">
                            <div class="fil-param AccordRegister ">
                               <?=$GLOBALS['ar_define_langterms']['MSG_ALL_SUNT_DE_ACORD_CU']?>
                               <a target="_blank"  href="<?=$CCpu->writelink( 34 )?>">
                               	<?=$GLOBALS['ar_define_langterms']['MSG_ALL_TERMENII_SI_CONDITIILE']?>
                               </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div onclick="register_f()" class="add-bklk">
                            <span class="add-b tran popenterButton ">
                                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_REGISTRACIYA']?>
                            </span>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                        <div class="b-f-label social-blk-tl ">
                           <?=$GLOBALS['ar_define_langterms']['MSG_ALL_ILI_CHEREZ_']?>
                        </div>
                        <div class="social-blk">
                            <?include($_SERVER['DOCUMENT_ROOT']."/pages/blocks/social_auth.php")?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

		$("#r_phone").intlTelInput({
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
			preferredCountries: ['md', 'ru', 'us', 'it', 'ie', 'gb'],
			separateDialCode: true,
			utilsScript: "/lib/intl-tel-input-master/js/utils.js"
		});

		function register_social(e) {
			$.getJSON("//ulogin.ru/token.php?host=" + encodeURIComponent(location.toString()) + "&token=" + e + "&callback=?", function (t) {
				t = $.parseJSON(t.toString()), t.error || $.ajax({
					type: "POST",
					url: "<?=$defaultLinks['ajax']?>",
					data: "task=socialauth&token=" + e,
					success: function (e) {
						"ok" == $.trim(e) ? location.href = "<?=$CCpu->writelink(45)?>" : alert($.trim(e))
					}
				});
			});
		}

		function register_f() {

			var a = 0;
			$('.required_field').removeClass('required_field');
			var name = $.trim($('#r_name').val());
			var email = $.trim($('#r_email').val());
			var phone = $.trim($("#r_phone").intlTelInput("getNumber"));
			var address = $.trim($('#r_address').val());
			var password = $.trim($('#r_password').val());
			var repassword = $.trim($('#r_repassword').val());

			if (name.length < 3 || name.length > 170) {
				$('#r_name').addClass('required_field');
				a++;
			}

			if (email.length < 3 || email.length > 170) {
				$('#r_email').addClass('required_field');
				a++;
			}

			if (password.length < 4 || password.length > 170) {
				$('#r_password').addClass('required_field');
				a++;
			}

			if (repassword.length < 4 || repassword.length > 170) {
				$('#r_repassword').addClass('required_field');
				a++;
			}

			if (password !== repassword) {
				$('#r_password').addClass('required_field');
				a++;
				$('#r_repassword').addClass('required_field');
				show('<?=$GLOBALS['ar_define_langterms']['MSG_ALL_PAROLI_NE_SOVPADAYUT']?>');
			}

			if (!$('.AccordRegister').hasClass('active')) {
				$('.AccordRegister').addClass('required_field');
				a++;
			}

			if (!validateEmail(email) && a == 0) {
				$('#r_email').addClass('required_field');
				show('<?=$GLOBALS['ar_define_langterms']['MSG_ALL_INTRODUCETI_EMAIL_CORECT']?>');
				a++;
			}

			if (a > 0) {
				$('.required_field').first().focus();

				return false;
			} else {
				loader();
				var fd = new FormData;

				if (phone.length < 7){
					phone = '';
				}

				fd.append('task', 'register_f');
				fd.append('name', name);
				fd.append('phone', phone);
				fd.append('email', email);
				fd.append('address', address);
				fd.append('password', password);
				fd.append('repassword', repassword);

				$.ajax({
					url: '<?=$defaultLinks['ajax']?>',
					data: fd,
					processData: false,
					contentType: false,
					type: 'POST',
					success: function (msg) {
						loader_destroy();

						if ($.trim(msg) == 'ok') {
							location.href = "<?=$CCpu->writelink(45)?>";
						} else {
							show(msg);
						}
					}
				});
			}
		}
    </script>
<?}


if($_POST['task'] == 'ratingprod_popup'){
	$arrParam = $ar_clean['arr_param'];
	$id = explode('~', $arrParam['0']);
    $id = $id[1];
?>

<div id="ratingprod_popup" class="popup_overlay popenterBlock" style="display: block;">
        <script type="text/javascript">
            $(document).ready(function() {
                popupCloseControl();
            });

            <?/* регулировка рэйтинга */?>
                var rating;
                jQuery(".rapc1").mousemove(function(e) {
                    if (!e)
                        e = window.event;
                    if (e.pageX) {
                        x = e.pageX;
                    } else if (e.clientX) {
                        x = e.clientX + (document.documentElement.scrollLeft || document.body.scrollLeft) - document.documentElement.clientLeft;
                    }

                    var posLeft = 0;
                    var obj = this;

                    while (obj.offsetParent) {
                        posLeft += obj.offsetLeft;
                        obj = obj.offsetParent;
                    }

                    var offsetX = x - posLeft, modOffsetX = 5 * offsetX % this.offsetWidth;
                    rating = parseInt(5 * offsetX / this.offsetWidth);

                    if (modOffsetX > 0)
                        rating += 1;

                    jQuery(this).find("span").eq(0).css("width", rating * 26 + "px");
                    jQuery(this).find('.rapcw1').css('width' , rating * 20 + '%' );
                });

                var saved_percent = 0;

                jQuery(".rapc1").mouseleave(function () {
                    jQuery(this).find('.rapcw1').css('width' , saved_percent + '%' );
                });

                jQuery(".rapc1").click(function() {
                    var procent = rating * 20;
                    saved_percent = procent;
                    jQuery(this).find('.rapcw1').css('width' , procent + '%' );
                    jQuery(this).find('.value_rating1').val(rating);

                    return false;
                });
          <?  /* регулировка рэйтинга КОНЕЦ */ ?>
        </script>
        <div class="standart_popup_width popup_window_wrapper">
            <div onclick="closePopupName( '#ratingprod_popup' )" class="popup_close_button tran "></div>
            <div class="popup_window_title">
                <?=$GLOBALS['ar_define_langterms']['MSG_ALL_LEAVE_REVIEW']?>
            </div>
            <div class="pop_content">
                <div class="space-row-16">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <div class="b-input-block-f">
                                <div class="b-f-lable">
                                    <?=$GLOBALS['ar_define_langterms']['MSG_ALL_NAME']?>
                                </div>
                                <div class="b-fq-inpt">
                                    <input type="text" name="name" value="<? echo (!empty($_SESSION['user_data']['name'])) ? $_SESSION['user_data']['name'] : ''?>" id="r_name">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <div class="b-input-block-f">
                                <div class="b-f-lable">
                                    <?=$GLOBALS['ar_define_langterms']['MSG_ALL_EMAIL']?>
                                </div>
                                <div class="b-fq-inpt">
                                    <input type="text" name="some_name" value="<? echo (!empty($_SESSION['user_data']['email'])) ? $_SESSION['user_data']['email'] : ''?>" id="r_email">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <div class="b-input-block-f">
                                <div class="b-f-lable">
                                    <?=$GLOBALS['ar_define_langterms']['MSG_ALL_COMENTARIU']?>
                                </div>
                                <div class="b-fq-inpt">
                                    <textarea id="r_comment" class="bfq_txt" name="textarea"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <div class="b-input-block-f">
                                <div class="b-f-lable">
                                    <?=$GLOBALS['ar_define_langterms']['MSG_ALL_OCENKA']?>
                                </div>
                                <div class="b-fq-inpt">
                                        <div class="row ratb">
                                            <div class="rapc rapc1">
                                                <div class="rapcw rapcw1"></div>
                                                <input type="hidden" name="value_rating1" value="" class="value_rating1" id="value_rating1">
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                            <div onclick="rating_f()" class="add-bklk">
                                <span class="add-b tran popenterButton "><?=$GLOBALS['ar_define_langterms']['MSG_ALL_OCENITI']?></span>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
		function rating_f() {
            var a = 0;

            $('.required_field').removeClass('required_field');

            var name = $.trim($('#r_name').val());
            var email = $.trim($('#r_email').val());
            var comment = $.trim($('#r_comment').val());
            var rating = $.trim($('#value_rating1').val());

            if (name.length < 3 || name.length > 64) {
                $('#r_name').addClass('required_field');
                a++;
            }

            if (email.length < 3 || email.length > 170) {
                $('#r_email').addClass('required_field');
                a++;
            }

            if (comment.length < 3 || comment.length > 3170) {
                $('#r_comment').addClass('required_field');
                a++;
            }

            if (rating.length == 0 && a == 0) {
                show('<?=$GLOBALS['ar_define_langterms']['MSG_ALL_POSTAVITE_OCENKU']?>');
                a++;
            }

            if (!validateEmail(email) && a == 0) {
                show('<?=$GLOBALS['ar_define_langterms']['MSG_ALL_INTRODUCETI_EMAIL_CORECT']?>');
                a++;
            }

            if (a > 0) {
                $('.required_field').first().focus();

                return false;
            } else {
                loader();

                var fd = new FormData;

                fd.append('task', 'rating_f');
                fd.append('name', name);
                fd.append('email', email);
                fd.append('comment', comment);
                fd.append('rating', rating);
                fd.append('elem_id', <?=(int)$id?>);

                $.ajax({
                    url: '<?=$defaultLinks['ajax']?>',
                    data: fd,
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    success: function (msg) {
                        loader_destroy();
                        if ($.trim(msg) == 'ok') {
                            show('<?=$GLOBALS['ar_define_langterms']['MSG_ALL_SPASIBO']?>');

                            setTimeout(function () {
                                location.reload();
                            }, 2000);
                        } else {
                            show(msg);
                        }
                    }
                });
            }
        }
	</script>
<?}


/*pagina contacte*/

if($_POST['task'] == 'form_contact'){
	date_default_timezone_set('Europe/Chisinau');

    $name = $ar_clean['name'];
    $email = $ar_clean['email'];
    $theme = $ar_clean['theme'];
    $message = $ar_clean['message'];

    $getInsert = mysqli_query($db, "INSERT INTO ws_messages (`name`,`email`,`theme`,`message`,`date`) VALUES ('".$name."','".$email."','".$theme."','".$message."',NOW()) ");

    if($getInsert){
		get_smtp_setting();

        // текст письма
        $contents = $Main->GetPageIncData( 'FORM_CONTACTS' , $CCpu->lang );
        $contents = str_replace('{date}', date( 'd/m/Y H:i' ) , $contents);
		$contents = str_replace('{name}', $name , $contents);
		$contents = str_replace('{email}', $email , $contents);
		$contents = str_replace('{theme}', $theme , $contents);
		$contents = str_replace('{message}', $message , $contents);

        $m = new Mail;

        $m->From( $smtp_mail ); // от кого отправляется почта
        $m->To( $GLOBALS['ar_define_settings']['EMAIL_SEND'] ); // кому адресованно
        $m->Subject( $GLOBALS['ar_define_langterms']['MSG_ALL_MESSAGE_CONTACTS'] );
        $m->Body( $contents,"html" );
        $m->Priority(3) ;    // приоритет письма
        $m->smtp_on( $smtp_server, $smtp_mail, $smtp_pass, $smtp_port );

        if($m->Send()){
        	echo "1";
        	exit;
		}
    }else{
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
        exit;
    }
}


/*anulare salvare date din cabinet personal*/

if ($_POST['task'] == 'cancel_personal_data_user') {
    if (!$Auth->isAuthorized()) {
        exit;
    }

    $get_client_data = mysqli_query($db, " SELECT `name`, `phone`, `address`  FROM `ws_clients` WHERE `id` = '{$_SESSION['user_id']}'");
    $client_data = mysqli_fetch_assoc($get_client_data);

    echo json_encode($client_data);
    exit;
}


/* обновить пароль */

if ($_POST['task'] == 'update_personal_password') {
    if (!$Auth->isAuthorized()) {
        exit;
    }

    $old_pass = (!empty(trim($ar_clean['old_pass']))) ? trim($ar_clean['old_pass']) : '';
    $new_pass = (!empty(trim($ar_clean['new_pass']))) ? trim($ar_clean['new_pass']) : '';
    $new_pass_confirm = (!empty(trim($ar_clean['new_pass_confirm']))) ? trim($ar_clean['new_pass_confirm']) : '';

    $getUser = mysqli_query($db, " SELECT `password` FROM `ws_clients` WHERE id = '{$_SESSION['user_id']}' LIMIT 1 ");
    $User = mysqli_fetch_assoc($getUser);

    // Old password need only if was set by user.
    if (empty($old_pass) && !empty($User['password'])) {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_ONE_FIELD_WAS_LEFT_BLANK'];
        exit;
    }

    if ($new_pass == '' || $new_pass_confirm == '') {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_ONE_FIELD_WAS_LEFT_BLANK'];
        exit;
    }

    // Checking old password if match
    if (!empty($old_pass)) {
        $UserPassword = $User['password'];
        $OldPassword = hash('sha512', $old_pass);
        $OldPassword = hash('sha512', $OldPassword . $_security_salt);

        if ($UserPassword != $OldPassword) {
            echo $GLOBALS['ar_define_langterms']['MSG_ALL_INCORRECT_PASSWORD'];
            exit;
        }
    }

    if ($new_pass != $new_pass_confirm) {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_PASSWORDS_DO_NOT_MATCH'];
        exit;
    }

    $NewPass = hash('sha512', trim($new_pass));
    $NewPass = hash('sha512', $NewPass . $_security_salt);
    $UpdateData = " password = '" . $NewPass . "' ";

    $query1 = mysqli_query($db, "UPDATE `ws_clients` SET {$UpdateData} WHERE `id` = '{$_SESSION['user_id']}'");
    if ($query1) {
        $Auth->updateUserData();
        echo 'ok';
        exit;
    } else {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
        exit;
    }
}

if ($_POST['task'] == 'update_personal_data') {
    if (!$Auth->isAuthorized()) {
        exit;
    }

    $name = trim($ar_clean['name']);
    $phone = trim($ar_clean['phone']);
    $address = trim($ar_clean['address']);
    $UpdateData = " name = '".$name."' ";
    $UpdateData .= ", phone = '".$phone."' ";
    $UpdateData .= ", address = '".$address."' ";

    $Auth->updateUserData();

    $query1 = mysqli_query($db, "UPDATE `ws_clients` SET {$UpdateData} WHERE `id` = '{$_SESSION['user_id']}'");

    if ($query1) {
        echo 'ok';
        exit;
    } else {
        echo $GLOBALS['ar_define_langterms']['MSG_ALL_ERROR_AJAX'];
        exit;
    }
}
