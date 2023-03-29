<?php

use function PHPSTORM_META\type;

defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/userguide3/general/urls.html
     */

    public function __construct()
    {
        parent::__construct();

        $this->load->model('Post');
        $this->load->model('Game');
        $this->load->model('Job');
        $this->load->model('Account');
        $this->load->database();
        $this->load->library('session');
        $this->load->library('facebook');
        $this->load->helper('url');
        $this->load->helper('func_helper');
        $this->load->helper('pagination');
        $this->load->library('Globals');
        $this->load->library('pagination311');
        $this->db = $this->load->database('default', TRUE);
        if (isset($_SESSION['user']) && $_SESSION['user']['id'] > 0) {
            $where_g = [
                'id' => $_SESSION['user']['id'],
            ];
            $check_user_g = $this->Account->get_detail_user($where_g);
            $_SESSION['user'] = $check_user_g;
            // if ($check_user_g['active'] != 1 && $_SERVER['REQUEST_URI'] != '/active') {
            //     redirect('/xac-thuc-tai-khoan/');
            // }
        }
    }
    public function logout()
    {
        unset($_SESSION['user']);
        redirect('/');
    }
    public function register_user()
    {
        $email = $this->input->post('email');
        $name = $this->input->post('name');
        $pass = md5($this->input->post('pass'));
        $where_rgt = [
            'username' => $name,
            'email' => $email
        ];
        $check_register = $this->Account->check_register($where_rgt);
        if ($check_register > 0) {
            $response = [
                'status' => 0,
                'mess' => 'Tên tài khoản hoặc email  đã được sử dụng, vui lòng kiểm tra lại.'
            ];
        } else {
            $code = rand(100000, 999999);
            $date = date('Y-m-d H-i-s', time());
            $data_insert = [
                'username' => $name,
                'password' => $pass,
                'email' => $email,
                'active' => 1, // không gửi được mail
                'code' => $code,
                'time' => $date
            ];
            $insert = $this->Account->insert($data_insert, 'accounts');
            if ($insert > 0) {
                $where = [
                    'id' => $insert
                ];
                $get_user = $this->Account->get_detail_user($where);
                $this->session->set_userdata('user', $get_user);
                // $body_email = file_get_contents('https://bahanu.com/email_tmp/dangky.html');
                // $body_email = str_replace('%name%', $name, $body_email);
                // $body_email = str_replace('%email%', $email, $body_email);
                // $body_email = str_replace('%code%', $code, $body_email);
                // $send_mail = sendEmail($email, $name, 'Email thông báo đăng ký thành công tài khoản', $body_email);
                // if ($send_mail) {
                $response = [
                    'status' => 1,
                    'mess' => 'Đăng ký thành công.'
                ];
                // }
            }
        }
        echo json_encode($response);
    }
    public function login()
    {
        $name = $this->input->post('name');
        $pass = md5($this->input->post('pass'));
        $where = [
            'username' => $name,
            'password' => $pass,
        ];
        $check_user = $this->Account->get_detail_user($where);
        if (isset($check_user['id']) &&  $check_user['id'] > 0) {
            if ($check_user['active'] == 1) {
                $_SESSION['user'] = $check_user;
                $response = [
                    'status' => 1,
                    'mess' => 'Đăng nhập thành công.'
                ];
            } else {
                $_SESSION['user'] = $check_user; //lưu session id fb
                $body_email = file_get_contents('https://bahanu.com/email_tmp/dangky.html');
                $body_email = str_replace('%name%', $check_user['username'], $body_email);
                $body_email = str_replace('%email%', $check_user['email'], $body_email);
                $body_email = str_replace('%code%', $check_user['code'], $body_email);
                sendEmail($check_user['email'], $check_user['username'], 'Email thông báo đăng ký thành công tài khoản', $body_email);
                $response = [
                    'status' => 2,
                    'mess' => 'Tài khoản chưa xác thực. Chúng tôi đã gửi mã xác thực qua email đăng ký của bạn. Vui lòng kiểm tra hộp thử để có mã xác thực.'
                ];
            }
        } else {
            $response = [
                'status' => 0,
                'mess' => 'Sai tài khoản hoặc mật khẩu.'
            ];
        }
        echo json_encode($response);
    }
    public function login_fb()
    {
        $id_user = 0;
        $date_check = date('H:i:s', time());
        // Authenticate user with facebook 
        if ($this->facebook->is_authenticated()) {
            // Get user info from facebook 
            $fbUser = $this->facebook->request('get', '/me?fields=id,name,email');
            // Preparing data for database insertion 
            $userData['oauth_provider'] = 'facebook';
            $userData['oauth_uid']    = !empty($fbUser['id']) ? $fbUser['id'] : '';;
            $userData['name']    = !empty($fbUser['name']) ? $fbUser['name'] : '';
            $userData['email']        = !empty($fbUser['email']) ? $fbUser['email'] : '';
            // Insert or update user data to the database 
            $userID = $this->Account->check_data(['username' => $userData['oauth_uid']], 'accounts');
            // Check user data insert or update status 
            if ($userID > 0) {
                $data_user = $this->Account->get_by_id(['username' => $userData['oauth_uid']], 'accounts');
                $id_user = $data_user['id'];
            } else {
                $data_insert = [
                    'username' => $userData['oauth_uid'],
                    'name' =>  $userData['name'],
                    'email' => $userData['email'],
                    'active' => 1,
                    'time' => date('Y-m-d H:i:s')
                ];
                $insert = $this->Account->insert($data_insert, 'accounts');
                if ($insert > 0) {
                    $id_user = $insert;
                    $data_user = $this->Account->get_by_id(['id' => $insert], 'accounts');
                }
            }
            if ($id_user > 0) {
                $id = $id_user;
                $data_user = $this->Account->get_by_id(['id' => $id_user], 'accounts');
            }
            $this->session->set_userdata('user', $data_user);
            redirect('/');
        } else {
            redirect('/');
        }
    }
    public function active()
    {
        redirect('/');
        $code = $this->input->post('code');
        if (strlen($code) == 6) {
            $id = $_SESSION['user']['id'];
            $where = [
                'id' => $id,
                'code' => $code
            ];
            $check = $this->Account->check_data($where, 'accounts');
            if ($check > 0) {

                $data_update = [
                    'active' => 1
                ];
                $where_update = [
                    'id' => $id
                ];
                $update = $this->Account->update($where_update, $data_update, 'accounts');
                if ($update) {
                    $response = [
                        'status' => 1,
                        'mess' => 'Xác thực thành công!'
                    ];
                }
            } else {
                $response = [
                    'status' => 0,
                    'mess' => 'Mã xác thực không đúng vui lòng kiểm tra lại email !'
                ];
            }
        } else {
            $response = [
                'status' => 0,
                'mess' => 'Mã code không hợp lệ'
            ];
        }
        echo json_encode($response);
    }
    public function re_active()
    {
        $code = rand(100000, 999999);
        $id = $_SESSION['user']['id'];
        $data_update = [
            'code' => $code
        ];
        $where_update = [
            'id' => $id
        ];
        $update = $this->Account->update($where_update, $data_update, 'accounts');
        if ($update) {
            $body_email = file_get_contents('https://bahanu.com/email_tmp/dangky.html');
            $body_email = str_replace('%name%', $_SESSION['user']['username'], $body_email);
            $body_email = str_replace('%email%', $_SESSION['user']['email'], $body_email);
            $body_email = str_replace('%code%', $code, $body_email);
            sendEmail($_SESSION['user']['email'], $_SESSION['user']['username'], 'Email thông báo đăng ký thành công tài khoản', $body_email);
            $response = [
                'status' => 1,
                'mess' => 'Xác thực thành công!'
            ];
        }

        echo json_encode($response);
    }
    public function card()
    {
        $iduser = $_SESSION['user']['username'];
        $name = addslashes($_SESSION['user']['name']);
        function curl($url, $post = false, $ref = '', $cookie = false, $follow = false, $cookies = false, $header = true, $headers = false)
        {
            $ch = curl_init($url);
            if ($ref != '') {
                curl_setopt($ch, CURLOPT_REFERER, $ref);
            }
            if ($cookie) {
                curl_setopt($ch, CURLOPT_COOKIE, $cookie);
            }
            if ($cookies) {
                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
            }
            if ($post) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_POST, 1);
            }
            if ($follow) curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            if ($header)     curl_setopt($ch, CURLOPT_HEADER, 1);
            if ($headers)        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_ENCODING, '');
            //curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);

            //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            $result[0] = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $result[1] = substr($result[0], $header_size);
            curl_close($ch);
            return $result;
        }
        function curl1($url, $post = false, $ref = '', $cookie = false, $cookies = false, $header = true, $headers = false, $follow = false)
        {
            $ch = curl_init($url);
            if ($ref != '') {
                curl_setopt($ch, CURLOPT_REFERER, $ref);
            }
            if ($cookie) {
                curl_setopt($ch, CURLOPT_COOKIE, $cookie);
            }
            if ($cookies) {
                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookies);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $cookies);
            }
            if ($post) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_POST, 1);
            }
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($ch, CURLOPT_ENCODING, '');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);

            //curl_setopt($ch, CURLINFO_HEADER_OUT, true);
            $result[0] = curl_exec($ch);
            $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
            $result[1] = substr($result[0], $header_size);
            curl_close($ch);
            return $result;
        }


        function getcookie($content)
        {
            preg_match_all('/Set-Cookie: (.*);/U', $content, $temp);
            $cookie = $temp[1];
            $cookies = "";
            $a = array();
            foreach ($cookie as $c) {
                $pos = strpos($c, "=");
                $key = substr($c, 0, $pos);
                $val = substr($c, $pos + 1);
                $a[$key] = $val;
            }
            foreach ($a as $b => $c) {
                $cookies .= "{$b}={$c};";
            }
            return $cookies;
        }
        function number_in_string($string)
        {
            try {
                if (!is_string($string))
                    throw new Exception("Error : Param Type");
                preg_match_all("/\d+/", $string, $matches);
                // Return the all coincidences
                return $matches[0];
            } catch (Exception $e) {
                echo $e->getMessage();
            }
        }
        function card_type($stt)
        {
            switch ($stt) {
                case "Vinaphone":
                    $text = "VNP";
                    break;
                case "Mobifone":
                    $text = "VMS";
                    break;
                case "Viettel":
                    $text = "VTT";
                    break;
                case "Gate":
                    $text = "Gate";
                    break;
                default:
                    // code...
                    break;
            }
            return $text;
        }





        if (isset($_SESSION['user']) && $_SESSION['user']['id'] > 1) {


            if (!$_POST['type']) {
                echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Chưa chọn loại nhà mạng"));
            } elseif (!$_POST['code']) {
                echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Chưa nhập mã thẻ"));
            } elseif ($this->input->post('menhgia') == "chuachon") {
                echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Vui lòng chọn mệnh giá thẻ"));
            } elseif (!$_POST['serial']) {
                echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Chưa nhập mã seri"));
            } else if ($this->input->post('type') == "Viettel" && (strlen($this->input->post('serial')) != 11 && strlen($this->input->post('serial')) != 14)) {
                echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Độ dài không hợp lệ, Seri thẻ Viettel phải có độ dài 11 hoặc 14"));
            } else if ($this->input->post('type') == "Viettel" && (strlen($this->input->post('code')) != 13 && strlen($this->input->post('code')) != 15)) {
                echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Độ dài không hợp lệ , Mã thẻ Viettel phải có độ dài 13 hoặc 15"));
            } else if ($this->input->post('type') == "Mobifone" && (strlen($this->input->post('code')) != 12)) {
                echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Độ dài không hợp lệ, Mã thẻ Mobifone phải có độ dài là 12"));
            } else if ($this->input->post('type') == "Vinaphone" && (strlen($this->input->post('code')) != 14)) {
                echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Độ dài không hợp lệ , Mã thẻ Vinaphone phải có độ dài là 14"));
            } else {


                function remove_n($num)
                {
                    return preg_replace('/[^0-9]/', '', $num);
                }

                function curl_post($url, $data)
                {
                    $dataPost = '';

                    if (is_array($data))
                        $dataPost = http_build_query($data);
                    else
                        $dataPost = $data;

                    $ch = curl_init();

                    curl_setopt($ch, CURLOPT_URL, $url);
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $dataPost);
                    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    curl_setopt($ch, CURLOPT_REFERER, $actual_link);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch);
                    curl_close($ch);

                    return $result;
                }


                function xss_clean($data)
                {
                    // Fix &entity\n;
                    $data = str_replace(array('&amp;', '&lt;', '&gt;'), array('&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
                    $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
                    $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
                    $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');

                    // Remove any attribute starting with "on" or xmlns
                    $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);

                    // Remove javascript: and vbscript: protocols
                    $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
                    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
                    $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);

                    // Only works in IE: <span style="width: expression(alert('Ping!'));"></span>
                    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
                    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
                    $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);

                    // Remove namespaced elements (we do not need them)
                    $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);

                    do {
                        // Remove really unwanted tags
                        $old_data = $data;
                        $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
                    } while ($old_data !== $data);

                    // we are done...
                    return $data;
                }


                $TxtSeri = xss_clean($this->input->post('serial'));

                $TxtCard  = xss_clean($this->input->post('type'));
                $menhgia  = xss_clean($this->input->post('menhgia'));
                $TxtMaThe = xss_clean($this->input->post('code'));

                $count = $this->Account->query_sql_num("SELECT * FROM history_card WHERE seri = '{$TxtSeri}' AND code = '{$TxtMaThe}' LIMIT 1");
                if ($count == 0) {
                    $partner_id = '3437753661'; //tes
                    $partner_key = 'b4a4eef850f4cb9c85e9a3a3a54ec154';
                    $info_card = 0;
                    if ($info_card == 10000) {
                        $vnd = 10000;
                    } elseif ($info_card == 20000) {
                        $vnd = 20000;
                    } elseif ($info_card == 30000) {
                        $vnd = 30000;
                    } elseif ($info_card == 50000) {
                        $vnd = 50000;
                    } elseif ($info_card == 100000) {
                        $vnd = 100000;
                    } elseif ($info_card == 200000) {
                        $vnd = 200000;
                    } elseif ($info_card == 300000) {
                        $vnd = 300000;
                    } elseif ($info_card == 500000) {
                        $vnd = 500000;
                    } elseif ($info_card == 1000000) {
                        $vnd = 1000000;
                    } else {
                        $vnd = 0;
                    }


                    $telco = $TxtCard;
                    $amount = $menhgia;
                    $serial = $TxtSeri;
                    $code = $TxtMaThe;
                    $request_id = rand(100000000, 999999999);  /// Cái này có thể mà mã order của bạn, nếu không có thì để nguyên ko cần động vào.
                    // $sign = md5($partner_id.$partner_key.$telco.$code.$serial.$amount.$request_id);
                    $dataPost = array();
                    $dataPost['request_id'] = $request_id;
                    $dataPost['code'] = $code;
                    $dataPost['partner_id'] = $partner_id;
                    $dataPost['serial'] = $serial;
                    $dataPost['telco'] = $telco;
                    $dataPost['command'] = 'charging';

                    // $dataPost = array(
                    // 	'request_id' => $request_id,
                    // 	'partner_id' => $partner_id,
                    // 	'code' => $code,
                    // 	'serial' => $serial,
                    // 	'telco' => $telco,
                    //   	'command' => 'charging'
                    // );
                    ksort($dataPost);
                    $sign = $partner_key;
                    foreach ($dataPost as $item) {
                        $sign .= $item;
                    }
                    $mysign = md5($sign);
                    $dataPost['amount'] = $amount;
                    $dataPost['sign'] = $mysign;

                    $data = http_build_query($dataPost);
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "https://naptudong.com/chargingws/v2");
                    curl_setopt($ch, CURLOPT_POST, 1);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                    $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                    curl_setopt($ch, CURLOPT_REFERER, $actual_link);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $result = curl_exec($ch);
                    curl_close($ch);

                    $obj = json_decode($result);
                    //$post = curl_post('http://api.naptudong.com/chargingws/v2', $dataPost);
                    //$obj = json_decode($post);

                    if ($obj->status == 99) {
                        $now = getdate();
                        $data_insert = [
                            'username' => $iduser,
                            'seri' => $TxtSeri,
                            'code' => $TxtMaThe,
                            'name' => $name,
                            'menhgia' => $menhgia,
                            'type_card' => $TxtCard,
                            'count_card' => $vnd,
                            'time' => date("Y-m-d H:i:s")
                        ];
                        $insert = $this->Account->insert($data_insert, 'history_card'); // lịch sử
                        echo json_encode(array('status' => "success", 'title' => "Thành công", 'msg' => " Thẻ đang được xử lý ( thời gian xử lý 2 đến 5 phút ), Quý khách sẽ được cộng tiền ngay khi thẻ xử lý xong. Trân trọng !."));
                    } else if ($obj->status == 3) {
                        echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => ' Sai seri hoặc mã thẻ, vui lòng nhập lại'));
                    } else {
                        echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => $obj->message));
                    }
                } else {
                    $err = isset($msg) ? $msg : 'Thẻ bị trùng, vui lòng nhập thẻ khác';
                    echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => 'Thẻ bị trùng, vui lòng nhập thẻ khác'));
                }
            }
        } else {
            echo json_encode(array('status' => "error", 'title' => "Lỗi", 'msg' => "Bạn chưa đăng nhập"));
        }
    }
    public function callback()
    {
        $partner_id = '3437753661'; //API key, lấy từ website thesieure.vn thay vào trong cặp dấu '
        $partner_key = 'b4a4eef850f4cb9c85e9a3a3a54ec154'; //API secret lấy từ website thesieure.vn thay vào trong cặp dấu '
        if (isset($_POST)) {

            if ($this->input->post('callback_sign') != '') {

                /// Đoạn này lưu log lại để test, chạy thực bỏ đoạn này đi nhé

                $myfile = fopen("log.txt", "w") or die("Unable to open file!");
                $txt = $this->input->post('callback_sign') . "|" . $this->input->post('status') . "|" . $this->input->post('message') . "\n";
                fwrite($myfile, $txt);
                fclose($myfile);

                $row = $this->Account->query_sql_row("SELECT * FROM `history_card` WHERE `status` = '1' AND `seri` = '" . $this->input->post('serial') . "' AND `code` = '" . $this->input->post('code') . "'");

                $callback_sign = md5($partner_key . $this->input->post('code') . $this->input->post('serial'));
                if ($this->input->post('callback_sign') == $callback_sign) { //Xác thực API, tránh kẻ lạ gửi dữ liệu ảo.
                    if ($this->input->post('status') != '') {

                        if ($this->input->post('status') == 1) {

                            $zen =  $this->input->post('value') / 100;
                            $where_u = [
                                'username' => $row['username']
                            ];
                            $update_1 = $this->Account->update_set($where_u, 'zen', $zen, '+', 'accounts'); // cộng tiền
                            $where_update = [
                                'id' => $row['id']
                            ];
                            $data_update = [
                                'status' => 5
                            ];
                            $update = $this->Account->update($where_update, $data_update, 'history_card'); // ĐÃ CỘNG TIỀN
                            //1 ==> thẻ đúng



                        } else {
                            $where_update = [
                                'id' => $row['id']
                            ];
                            $data_update = [
                                'status' => 2
                            ];
                            $update = $this->Account->update($where_update, $data_update, 'history_card'); // thẻ sai
                        }
                    }
                }
            }
        }
    }
    public function history_card()
    {
        if (check_login()) {
            $iduser = $_SESSION['user']['username'];
            $page = $this->input->post("page2");
            $total_record = $this->Account->query_sql_num("SELECT * FROM history_card WHERE username = '{$iduser}' LIMIT 1");
            // config phân trang
            $config = array(
                "current_page" => $page,
                "total_record" => $total_record,
                "limit" => "10",
                "range" => "5",
                "link_first" => "",
                "link_full" => "?page2={page}"
            );

            $paging = new Pagination;
            $paging->init($config);
            $sql_get_list_buy = "SELECT * FROM `history_card` WHERE username = '{$iduser}' ORDER BY `time` DESC LIMIT {$paging->getConfig()["start"]}, {$paging->getConfig()["limit"]}";
            // Nếu có 
            if ($total_record > 0) {
                $html = '<table class="table_cash">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Loại thẻ</th>
                            <th>Mã thẻ</th>
                            <th>Tình Trạng Thẻ</th>
                            <th>Mệnh giá</th>
                        </tr>
                    </thead>
                    <tbody>';
                $i = 1;

                foreach ($this->Account->query_sql($sql_get_list_buy) as $key => $data_card) {
                    if ($data_card['status'] == 5) {
                        $status_text = 'Đã cộng tiền';
                    } elseif ($data_card['status'] == 2) {
                        $status_text = 'Sai mã thẻ hoặc seri, vui lòng kiểm tra lại';
                    } else {
                        $status_text = 'Vui lòng đợi 2 đến 5 phú để xử lý thẻ';
                    }
                    $html .= '<tr>
                                <td class="text-center">' . $i . '</td>
                                <td>' . $data_card['type_card'] . '</td>
                                <td>' . $data_card['code'] . '</td>
                                <td>' . $status_text . '</td>
                                <td>' . number_format($data_card['menhgia']) . 'đ</td>
                                </tr>';



                    $i++;
                }
                $html .= '</tbody>

                </table>';
                $html .= $paging->html_card(); // page
            } else {
                $html = '<p class="content-mini content-mini-full bg-info text-white">Bạn chưa có giao dịch nào</p>';
            }
        }
        echo $html;
    }
    public function doi_mk()
    {
        if (check_login()) {
            $oldpass = md5($this->input->post('oldpass'));
            $newpass = md5($this->input->post('newpass'));
            $id = $_SESSION['user']['id'];
            if ($oldpass == $_SESSION['user']['password']) {
                $where_update = [
                    'id' => $id
                ];
                $data_update = [
                    'password' => $newpass
                ];
                $update = $this->Account->update($where_update, $data_update, 'accounts'); // đổi mật khẩu
                $response = [
                    'status' => 1,
                    'mess' => 'Cập nhật thành công!',
                ];
            } else {
                $response = [
                    'status' => 0,
                    'mess' => 'Sai mật khẩu',
                ];
            }
            echo json_encode($response);
        } else {
            redirect('/');
        }
    }
    public function doi_thong_tin()
    {
        if (check_login()) {
            $id = $_SESSION['user']['id'];
            $fullname = $this->input->post('fullname');
            $email = $this->input->post('email');
            $phonenumber = $this->input->post('phonenumber');
            $birthday = strtotime($this->input->post('birthday'));
            $address = $this->input->post('address');
            $sex = $this->input->post('sex');

            // $url_img = $_SESSION['user']['avatar'];

            // if (isset($_FILES['file'])   && $_FILES['file']['name'] !== "") {
            //     $path = '../user/'; // patch lưu file
            //     $tmp_name = $_FILES["file"]["tmp_name"];
            //     $name = $names = str_replace('php', '', $_FILES["file"]["name"]);
            //     $temp            = explode('.', $name);
            //     if (count($temp) > 2) {
            //         $response = [
            //             'status' => 0,
            //             'mess' => 'Vui lòng đổi lại tên ảnh',
            //             'url' => $url_img
            //         ];
            //     } else {
            //         $name = 'user_' . $id . '.jpg';
            //         resize_image_upload($_FILES["file"]["tmp_name"], $names, $name, 200, 200, 100, $type = "index", $path);
            //         $url_img = 'assets/user/' . $name;
            //     }
            // }
            $where_update = [
                'id' => $id
            ];
            $data_update = [
                'name' => $fullname,
                'email' => $email,
                'phone' => $phonenumber,
                'birthday' => $birthday,
                'address' => $address,
                'sex' => $sex
            ];
            $update = $this->Account->update($where_update, $data_update, 'accounts'); // đổi mật khẩu
            $response = [
                'status' => 1,
                'mess' => 'Cập nhật thành công!'
                // 'url' => $url_img
            ];
            echo json_encode($response);
        } else {
            redirect('/');
        }
    }
    public function sitemap()
    {
        if ($_GET['type'] == 'home') {
            $sql = "SELECT * FROM sitemap";
            $sitemap = $this->Account->query_sql($sql);
            $doc = new DOMDocument("1.0", "utf-8");
            $doc->formatOutput = true;
            $doc->appendChild($doc->createProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="https://bahanu.com/assets/css/sanacc/css_sitemap.xsl"'));
            $r = $doc->createElement("sitemapindex");
            $r->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
            $doc->appendChild($r);

            foreach ($sitemap as $key => $val) {
                $url = $doc->createElement("sitemap");
                $name = $doc->createElement("loc");
                $name->appendChild(
                    $doc->createTextNode('https://bahanu.com/' . $val['name'])
                );
                $url->appendChild($name);
                $lastmod = $doc->createElement("lastmod");
                $lastmod->appendChild(
                    $doc->createTextNode($val['time'] . 'T17:28:31+07:00')
                );
                $url->appendChild($lastmod);
                $r->appendChild($url);
            }
            $doc->save("sitemap.xml");
            if (isset($_COOKIE['url_sitemap']) && $_COOKIE['url_sitemap'] != "") {
                redirect($_COOKIE['url_sitemap']); // Trở về trang index
            }
        } else if ($_GET['type'] == 'blog') {

            $sql = "SELECT id,alias,updated_at FROM baiviet ORDER BY id ASC";
            $blog = $this->Account->query_sql($sql);
            $count = count($blog);
            $page = ceil($count / 200);
            for ($i = 1; $i <= $page; $i++) {
                $check_page = ($i - 1) * 200;
                $sql_limit = "SELECT id,alias,updated_at FROM baiviet ORDER BY id ASC LIMIT {$check_page}, 200";
                $blog_limit = $this->Account->query_sql($sql_limit);
                $doc = new DOMDocument("1.0", "utf-8");
                $doc->formatOutput = true;
                $r = $doc->createElement("urlset");
                $r->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
                $doc->appendChild($r);
                foreach ($blog_limit as $val) {
                    $url = $doc->createElement("url");
                    $name = $doc->createElement("loc");
                    $name->appendChild(
                        $doc->createTextNode('https://bahanu.com/' . $val['alias'] . '/')
                    );
                    $url->appendChild($name);
                    $changefreq = $doc->createElement("changefreq");
                    $changefreq->appendChild(
                        $doc->createTextNode('daily')
                    );
                    $url->appendChild($changefreq);
                    $lastmod = $doc->createElement("lastmod");
                    $lastmod->appendChild(
                        $doc->createTextNode(date('Y-m-d', $val['updated_at']) . 'T07:24:06+00:00')
                    );
                    $url->appendChild($lastmod);
                    $priority = $doc->createElement("priority");
                    $priority->appendChild(
                        $doc->createTextNode('0.8')
                    );
                    $url->appendChild($priority);
                    $r->appendChild($url);
                }
                $name = ($i == 1) ? '' : $i - 1;
                $name_file = 'blog' . $name . ".xml";
                $date = date('Y-m-d', time());
                if ($i >= 2) {
                    $sql_check = "SELECT * FROM sitemap  WHERE name = '$name_file' ";
                    $row = $this->Account->query_sql_num($sql_check);
                    if ($row > 0) {

                        $where_update = [
                            'name' => $name_file
                        ];
                        $data_update = [
                            'time' => time()
                        ];
                        $update_free = $this->Account->update($where_update, $data_update, 'sitemap');
                    } else {
                        $data_insert = [
                            'name' => $name_file,
                            'time' => time()
                        ];
                        $insert = $this->Account->insert($data_insert, 'sitemap');
                    }
                }
                $doc->save('blog' . $name . ".xml");
            }
            redirect('/sitemap/?type=home'); // Trở về trang index
        } else if ($_GET['type'] == 'account') {
            $sql = "SELECT id_post,date_posted FROM posts ORDER BY id_post ASC ";
            $blog = $this->Account->query_sql($sql);
            $count = count($blog);
            $page = ceil($count / 200);
            for ($i = 1; $i <= $page; $i++) {
                $check_page = ($i - 1) * 200;
                $sql_limit = "SELECT id_post,date_posted FROM posts ORDER BY id_post ASC LIMIT {$check_page}, 200";
                $blog_limit = $this->Account->query_sql($sql_limit);
                $doc = new DOMDocument("1.0", "utf-8");
                $doc->formatOutput = true;
                $r = $doc->createElement("urlset");
                $r->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.9");
                $doc->appendChild($r);
                foreach ($blog_limit as $val) {
                    $url = $doc->createElement("url");
                    $name = $doc->createElement("loc");
                    $name->appendChild(
                        $doc->createTextNode('https://bahanu.com/tai-khoan-' . $val['id_post'] . '/')
                    );
                    $url->appendChild($name);
                    $changefreq = $doc->createElement("changefreq");
                    $changefreq->appendChild(
                        $doc->createTextNode('daily')
                    );
                    $url->appendChild($changefreq);
                    $lastmod = $doc->createElement("lastmod");
                    $lastmod->appendChild(
                        $doc->createTextNode(substr($val['date_posted'], 0, 10) . 'T07:24:06+00:00')
                    );
                    $url->appendChild($lastmod);
                    $priority = $doc->createElement("priority");
                    $priority->appendChild(
                        $doc->createTextNode('0.5')
                    );
                    $url->appendChild($priority);
                    $r->appendChild($url);
                }
                $name = ($i == 1) ? '' : $i - 1;
                $name_file = 'account' . $name . ".xml";
                $date = date('Y-m-d', time());
                if ($i >= 2) {
                    $sql_check = "SELECT * FROM sitemap  WHERE name = '$name_file' ";
                    $row = $this->Account->query_sql_num($sql_check);
                    if ($row > 0) {

                        $where_update = [
                            'name' => $name_file
                        ];
                        $data_update = [
                            'time' => time()
                        ];
                        $update_free = $this->Account->update($where_update, $data_update, 'sitemap');
                    } else {
                        $data_insert = [
                            'name' => $name_file,
                            'time' => time()
                        ];
                        $insert = $this->Account->insert($data_insert, 'sitemap');
                    }
                }
                $doc->save('account' . $name . ".xml");
            }
            redirect('/sitemap/?type=home'); // Trở về trang index
        } else if ($_GET['type'] == 'chuyenmuc') {
            $sql = "SELECT id,updated_at,alias FROM chuyenmuc ORDER BY updated_at ASC ";
            $chuyenmuc = $this->Account->query_sql($sql);
            $count = count($chuyenmuc);
            $page = ceil($count / 200);
            for ($i = 1; $i <= $page; $i++) {
                $check_page = ($i - 1) * 200;
                $sql_limit = "SELECT id,updated_at,alias FROM chuyenmuc ORDER BY updated_at ASC LIMIT {$check_page}, 200";
                $blog_limit = $this->Account->query_sql($sql_limit);
                $doc = new DOMDocument("1.0", "utf-8");
                $doc->formatOutput = true;
                $r = $doc->createElement("urlset");
                $r->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.8");
                $doc->appendChild($r);
                foreach ($blog_limit as $val) {
                    $url = $doc->createElement("url");
                    $name = $doc->createElement("loc");
                    $name->appendChild(
                        $doc->createTextNode('https://bahanu.com/' . $val['alias'])
                    );
                    $url->appendChild($name);
                    $changefreq = $doc->createElement("changefreq");
                    $changefreq->appendChild(
                        $doc->createTextNode('daily')
                    );
                    $url->appendChild($changefreq);
                    $lastmod = $doc->createElement("lastmod");
                    $lastmod->appendChild(
                        $doc->createTextNode(date('Y-m-d', $val['updated_at']) . 'T07:24:06+00:00')
                    );
                    $url->appendChild($lastmod);
                    $priority = $doc->createElement("priority");
                    $priority->appendChild(
                        $doc->createTextNode('0.8')
                    );
                    $url->appendChild($priority);
                    $r->appendChild($url);
                }
                $name = ($i == 1) ? '' : $i - 1;
                $name_file = 'chuyenmuc' . $name . ".xml";
                $date = date('Y-m-d', time());
                if ($i >= 2) {
                    $sql_check = "SELECT * FROM sitemap  WHERE name = '$name_file' ";
                    $row = $this->Account->query_sql_num($sql_check);
                    if ($row > 0) {

                        $where_update = [
                            'name' => $name_file
                        ];
                        $data_update = [
                            'time' => time()
                        ];
                        $update_free = $this->Account->update($where_update, $data_update, 'sitemap');
                    } else {
                        $data_insert = [
                            'name' => $name_file,
                            'time' => time()
                        ];
                        $insert = $this->Account->insert($data_insert, 'sitemap');
                    }
                }
                $doc->save('chuyenmuc' . $name . ".xml");
            }
            redirect('/sitemap/?type=home'); // Trở về trang index
        } else if ($_GET['type'] == 'game') {
            $sql = "SELECT id,updated_at,alias FROM game_bq ORDER BY updated_at ASC ";
            $chuyenmuc = $this->Account->query_sql($sql);
            $count = count($chuyenmuc);
            $page = ceil($count / 200);
            for ($i = 1; $i <= $page; $i++) {
                $check_page = ($i - 1) * 200;
                $sql_limit = "SELECT id,updated_at,alias FROM game_bq ORDER BY updated_at ASC LIMIT {$check_page}, 200";
                $blog_limit = $this->Account->query_sql($sql_limit);
                $doc = new DOMDocument("1.0", "utf-8");
                $doc->formatOutput = true;
                $r = $doc->createElement("urlset");
                $r->setAttribute("xmlns", "http://www.sitemaps.org/schemas/sitemap/0.8");
                $doc->appendChild($r);
                foreach ($blog_limit as $val) {
                    $url = $doc->createElement("url");
                    $name = $doc->createElement("loc");
                    $name->appendChild(
                        $doc->createTextNode('https://bahanu.com/' . $val['alias'] . '/')
                    );
                    $url->appendChild($name);
                    $changefreq = $doc->createElement("changefreq");
                    $changefreq->appendChild(
                        $doc->createTextNode('daily')
                    );
                    $url->appendChild($changefreq);
                    $lastmod = $doc->createElement("lastmod");
                    $lastmod->appendChild(
                        $doc->createTextNode(date('Y-m-d', $val['updated_at']) . 'T07:24:06+00:00')
                    );
                    $url->appendChild($lastmod);
                    $priority = $doc->createElement("priority");
                    $priority->appendChild(
                        $doc->createTextNode('0.5')
                    );
                    $url->appendChild($priority);
                    $r->appendChild($url);
                }
                $name = ($i == 1) ? '' : $i - 1;
                $name_file = 'game' . $name . ".xml";
                $date = date('Y-m-d', time());
                if ($i >= 2) {
                    $sql_check = "SELECT * FROM sitemap  WHERE name = '$name_file' ";
                    $row = $this->Account->query_sql_num($sql_check);
                    if ($row > 0) {

                        $where_update = [
                            'name' => $name_file
                        ];
                        $data_update = [
                            'time' => time()
                        ];
                        $update_free = $this->Account->update($where_update, $data_update, 'sitemap');
                    } else {
                        $data_insert = [
                            'name' => $name_file,
                            'time' => time()
                        ];
                        $insert = $this->Account->insert($data_insert, 'sitemap');
                    }
                }
                $doc->save('game' . $name . ".xml");
            }
            redirect('/sitemap/?type=home'); // Trở về trang index
        }
    }
}
