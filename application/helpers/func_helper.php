<?php
function pagination($base_url, $total_row, $per_page, $segment = '')
{
	$CI = &get_instance();
	$CI->load->library("pagination");
	$config['base_url'] = $base_url;
	$config['total_rows'] = $total_row;
	$config['per_page'] = $per_page;
	$config["use_page_numbers"] = true;
	$config["reuse_query_string"] = true;
	$config["prefix"] = '/';
	if ($segment > 0) {
		$config['uri_segment'] = 3;
	}
	// full tag pagination
	$config["full_tag_open"] = " <nav class='list_pagination' >
        <ul class='pagination'>";
	$config["full_tag_close"] = "</ul>
        </nav>";
	// first link
	$config["first_link"] = "&lt&lt";
	$config["first_tag_open"] = "<li class='page-item update-page-item'>";
	$config["first_tag_close"] = "</li>";

	// last link
	$config["last_link"] = "&gt&gt";
	$config["last_tag_open"] = "<li class='page-item update-page-item'>";
	$config["last_tag_close"] = "</li>";

	// next link
	$config["next_link"] = "&gt";
	$config["next_tag_open"] = "<li class='page-item update-page-item'>";
	$config["next_tag_close"] = "</li>";

	// pre link
	$config["prev_link"] = "&lt";
	$config["prev_tag_open"] = "<li class='page-item update-page-item'>";
	$config["prev_tag_close"] = "</li>";

	// cur link 
	$config["cur_tag_open"] = "<li class='page-item update-page-item active_pagin'><a class='page-link'>";
	$config["cur_tag_close"] = "</a></li>";
	$config['num_links'] = 1;

	$config["num_tag_open"] = "<li class='page-item update-page-item'>";
	$config["num_tag_close"] = "</li>";
	$config['attributes'] = array('class' => 'page-link');
	$CI->pagination->initialize($config);
}
function url_loginfb()
{
	// Facebook config
	$app_id = '610442623844480';
	$app_secret = '1958b41c0d15a998b737ddca989b0d87';
	$default_access_token = '610442623844480|1958b41c0d15a998b737ddca989b0d87'; // appid|secret
	$fb = new Facebook\Facebook([
		'app_id' => $app_id,
		'app_secret' => $app_secret,
		'default_graph_version' => 'v2.4',
		'default_access_token' => isset($_SESSION['facebook_access_token']) ? $_SESSION['facebook_access_token'] : $default_access_token
	]);
	$helper = $fb->getRedirectLoginHelper();
	$permissions = ['email'];  // set the permissions. 
	$loginUrl = $helper->getLoginUrl('' . base_url() . '/login.php', $permissions);
	return $loginUrl;
}
function vn_str_filter($str)
{
	$str = trim(strtolower($str));
	$unicode = array(
		'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
		'd' => 'đ|Đ',
		'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
		'i' => 'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
		'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
		'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
		'y' => 'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ',
		'-' => ' |%|,|=|;|!',
	);
	foreach ($unicode as $nonUnicode => $uni) {
		$str = preg_replace("/($uni)/i", $nonUnicode, $str);
	}
	$str = str_replace('?', '', $str);
	$str = str_replace('"', '', $str);
	$str = str_replace('“', '', $str);
	$str = str_replace('”', '', $str);
	$str = str_replace("'", '', $str);
	$str = str_replace("---", '-', $str);
	$str = str_replace("--", '-', $str);
	return $str;
}
function sendEmail($to, $name, $subject, $content)
{
	require_once(APPPATH . '/libraries/phpmailer/class.phpmailer.php');
	require_once(APPPATH . '/libraries/phpmailer/class.smtp.php');
	$usernameSmtp = 'mations88@gmail.com'; //  AKIA4H45CLBRDNNBQ4NW
	$passwordSmtp = 'ytsptbaatkcovtxc';  // amkbkhqvdvjfoojb BBhUIbTmBLQkalYzuYFoRFjnWZRXhzkiyod+qfGtxvME
	$host = 'smtp.gmail.com';
	$port = 587;
	$sender = 'no-reply@timviec365.com.vn';
	$senderName = 'Zendo.vn';

	$mail             = new PHPMailer(true);
	$mail->IsSMTP();
	$mail->SetFrom($sender, $senderName);
	$mail->Username   = $usernameSmtp;  // khai bao dia chi email
	$mail->Password   = $passwordSmtp;              // khai bao mat khau   
	$mail->Host       = $host;    // sever gui mail.
	$mail->Port       = $port;         // cong gui mail de nguyen 
	$mail->SMTPAuth   = true;    // enable SMTP authentication
	$mail->SMTPSecure = "tls";   // sets the prefix to the servier        
	$mail->CharSet  = "utf-8";
	$mail->SMTPDebug  = 0;   // enables SMTP debug information (for testing)
	// xong phan cau hinh bat dau phan gui mail
	$mail->isHTML(true);
	$mail->Subject    = $subject; // tieu de email 
	$mail->Body       = $content;
	$mail->addAddress($to, $name);
	if (!$mail->Send()) {
		echo $mail->ErrorInfo;
	} else {
		return true;
	}
}
function resize_image($path, $filename, $new_name, $maxwidth, $maxheight, $quality, $type = "", $new_path = "")
{
	$sExtension = substr($filename, (strrpos($filename, ".") + 1));
	$sExtension = strtolower($sExtension);
	// Get new dimensions
	list($width, $height) = getimagesize($path);
	if ($width != 0 && $height != 0) {
		if ($maxwidth / $width > $maxheight / $height) $percent = $maxheight / $height;
		else $percent = $maxwidth / $width;
	}

	$new_width    = $width * $percent;
	$new_height    = $height * $percent;
	// Resample
	$image_p = imagecreatetruecolor($new_width, $new_height);
	//check extension file for create
	switch ($sExtension) {
		case "gif":
			$image = imagecreatefromgif($path);
			break;
		case $sExtension == "jpg" || $sExtension == "jpe" || $sExtension == "jpeg":
			$image = imagecreatefromjpeg($path);
			break;
		case "png":
			$image = imagecreatefrompng($path);
			imagealphablending($image_p, false);
			imagesavealpha($image_p, true);
			$transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
			imagefilledrectangle($image_p, 0, 0, $new_width, $new_height, $transparent);
			break;
	}
	//Copy and resize part of an image with resampling
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	// Output

	// check new_path, nếu new_path tồn tại sẽ save ra đó, thay path = new_path
	if ($new_path != "") $path = $new_path;
	// echo $path;die;
	switch ($sExtension) {
		case "gif":
			imagegif($image_p, $path . $type . $filename);
			break;
		case $sExtension == "jpg" || $sExtension == "jpe" || $sExtension == "jpeg":
			imagejpeg($image_p, $path . $type . $new_name, $quality);
			break;
		case "png":
			imagejpeg($image_p, $path . $type . $new_name, $quality);
			break;
	}
	imagedestroy($image_p);
}
function resize_image_upload($path, $filename, $new_name, $maxwidth, $maxheight, $quality, $type = "", $new_path = "") // resize khi đăng ảnh
{
	$sExtension = substr($filename, (strrpos($filename, ".") + 1));
	$sExtension = strtolower($sExtension);
	// Get new dimensions
	list($width, $height) = getimagesize($path);
	if ($width != 0 && $height != 0) {
		if ($maxwidth / $width > $maxheight / $height) $percent = $maxheight / $height;
		else $percent = $maxwidth / $width;
	}

	$new_width    = $width * $percent;
	$new_height    = $height * $percent;
	if ($type == 'index') {
		$new_width    = $maxwidth;
		$new_height    = $maxheight;
		$type = "";
	}
	// Resample
	$image_p = imagecreatetruecolor($new_width, $new_height);
	//check extension file for create
	switch ($sExtension) {
		case "gif":
			$image = imagecreatefromgif($path);
			break;
		case $sExtension == "jpg" || $sExtension == "jpe" || $sExtension == "jpeg":
			$image = imagecreatefromjpeg($path);
			break;
		case "png":
			$image = imagecreatefrompng($path);
			imagealphablending($image_p, false);
			imagesavealpha($image_p, true);
			$transparent = imagecolorallocatealpha($image_p, 255, 255, 255, 127);
			imagefilledrectangle($image_p, 0, 0, $new_width, $new_height, $transparent);
			break;
	}
	//Copy and resize part of an image with resampling
	imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	// Output

	// check new_path, nếu new_path tồn tại sẽ save ra đó, thay path = new_path
	if ($new_path != "") $path = $new_path;
	// echo $path;die;
	switch ($sExtension) {
		case "gif":
			imagegif($image_p, $path . $type . $filename);
			break;
		case $sExtension == "jpg" || $sExtension == "jpe" || $sExtension == "jpeg":
			imagejpeg($image_p, $path . $type . $new_name, $quality);
			break;
		case "png":
			imagejpeg($image_p, $path . $type . $new_name, $quality);
			break;
	}
	imagedestroy($image_p);
}
function check_login()
{
	if (isset($_SESSION['user']) && $_SESSION['user']['id'] > 0) {
		return true;
	} else {
		return false;
	}
}
//kiểm tra xem có phải admin không.
if (!function_exists('is_admin')) {
	function is_admin()
	{
		global $user, $_SESSION, $db;
		$user_id = $_SESSION['user']['id'];
		if ($user_id > 0 && $_SESSION['user']['admin'] == 1) {
			return true;
		}
		return false;
	}
}
function validate_image($image)
{
	if ($image["error"] == 0) {
		if ($image['size'] < 100000) {
			$tmp_name = $image["tmp_name"];
			$name = $names = str_replace('php', '', $image["name"]);
			$temp            = explode('.', $name);
			if (count($temp) > 2) {
				$response = [
					'status' => 0,
					'mess' => 'Vui lòng đổi lại tên'
				];
			}
		} else {
			$response = [
				'status' => 0,
				'mess'   => "Vui lòng chọn hình ảnh hơn 100kb"
			];
		}
	} else {
		$response = [
			'status' => 0,
			'mess'   => "Đã có lỗi khi tải ảnh"
		];
	}
	return  $response;
}
//kiểm tra xem có phải là playdoul.
if (!function_exists('is_playdoul')) {
	function is_playdoul()
	{
		global $user, $_SESSION, $db;
		$user_id = $_SESSION['user']['id'];
		if ($user_id > 0 && $_SESSION['user']['user_type'] == 2) {
			return true;
		}
		return false;
	}
}
