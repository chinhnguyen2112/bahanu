<?php

use function PHPSTORM_META\type;

defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
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

        $this->load->model(['Post', 'Game', 'Job', 'Account']);
        $this->load->database();
        $this->load->library(['session', 'facebook']);
        $this->load->helper(['url',  'func_helper']);
        $this->load->library(['Globals', 'pagination311']);
        if (isset($_SESSION['user']) && $_SESSION['user']['id'] > 0) {
            $where_g = [
                'id' => $_SESSION['user']['id'],
            ];
            $check_user_g = $this->Account->get_detail_user($where_g);
            $_SESSION['user'] = $check_user_g;
            // if ($check_user_g['active'] != 1 && $_SERVER['REQUEST_URI'] != '/xac-thuc-tai-khoan/') {
            //     redirect('/xac-thuc-tai-khoan/');
            // }
        }
    }

    public function home()
    {
        if (check_login()) {
            $id = $_SESSION['user']['id'];
            $sql = "SELECT accounts.id,username,avatar,name,friend.type as type_fr FROM `accounts` JOIN friend ON friend.id_user = accounts.id OR friend.id_friend = accounts.id WHERE accounts.id != $id AND ( id_user = $id OR id_friend = $id ) GROUP by id  LIMIT 20";
            $sql2 = "SELECT accounts.id,username,avatar,name FROM `accounts` WHERE id NOT IN   ( SELECT id_user FROM friend WHERE id_user = $id OR id_friend = $id ) AND  id NOT IN   ( SELECT id_friend FROM friend WHERE id_user = $id OR id_friend = $id )    ORDER BY id DESC LIMIT 20";
            $data['my_friend'] = $this->Account->query_sql($sql);
        } else {
            $sql2 = "SELECT accounts.id,username,avatar,name FROM `accounts` ORDER BY id DESC LIMIT 20";
            $data['my_friend'] = [];
        }
        $data['list_user'] = $this->Account->query_sql($sql2);

        $sql3 = "SELECT avatar,name,price,cate, accounts.id,text_intro FROM accounts INNER JOIN kol ON kol.id_user = accounts.id ";
        $data['list_kol'] = $this->Account->query_sql($sql3);
        $data['list_js'] = [
            'kol/community.js'
        ];
        $data['list_css'] = [
            'kol/community.css'
        ];
        $data['content'] = '/main/main';
        $this->load->view('index', $data);
    }
    public function login()
    {
        if (check_login()) {
            redirect('/');
        } else {
            $data['list_js'] = [
                'main/login_sell.js'
            ];
            $userData = array();


            $data['authURL'] =  $this->facebook->login_url();

            $data['content'] = '/main/login';
            $this->load->view('index', $data);
        }
    }
    public function register()
    {
        if (check_login()) {
            redirect('/');
        } else {
            $data['content'] = '/main/register';
            $data['authURL'] =  $this->facebook->login_url();
            $data['list_js'] = [
                'main/register_user.js'
            ];
            $this->load->view('index', $data);
        }
    }
    public function active_user()
    {
        redirect('/');
        if (check_login() &&  $_SESSION['user']['active'] > 0) {
            redirect('/');
        } else {
            $data['list_js'] = [
                'main/active.js'
            ];
            $data['content'] = '/main/active_user';
            $this->load->view('index', $data);
        }
    }
    public function list_kol()
    {
        $data['list_js'] = [
            'select2.min.js',
            'kol/list_kol.js'
        ];
        $data['list_css'] = [
            'select2.min.css',
            'kol/list_kol.css'
        ];
        $data['content'] = 'kol/list_kol';
        $this->load->view('index', $data);
    }
    public function search_kol()
    {
        $this->load->helper(['pagination']);
        $page = $this->input->post('page');
        $name = $this->input->post('name');
        $list_cate = $this->input->post('list_cate');
        $price_max = (int)$this->input->post('price_max');
        $price_max = $price_max + 1;
        $price_min = (int)$this->input->post('price_min');
        $sex = $this->input->post('sex');
        $where = " price >= $price_min ";
        if ($price_max > 1) {
            $where .= " AND price < $price_max ";
        }
        if ($name != '') {
            $where .= " AND name LIKE '%$name%' ";
        }
        if ($list_cate != null) {
            $where .= " AND ( ";
            foreach ($list_cate as $key => $val) {
                $cnt = ' OR ';
                if ($key == 0) {
                    $cnt = '';
                }
                $where .= $cnt . " FIND_IN_SET($val, cate) ";
            }
            $where .= " ) ";
        }
        if ($sex > 0) {
            $where .= " AND sex = $sex ";
        }
        $sql = "SELECT avatar,name,price,cate, accounts.id,text_intro FROM accounts INNER JOIN kol ON kol.id_user = accounts.id WHERE $where ";
        $total_record = $this->db->query($sql)->num_rows(); // đếm hàng
        // config phân trang
        $so_lg = $total_record . ' kết quả';
        $config_page = array(
            "current_page" => $page,
            "total_record" => $total_record,
            "limit" => "15",
            "range" => "5",
            "link_first" => "",
            "link_full" => "?page={page}"
        );
        $paging = new Pagination;
        $paging->init($config_page);
        $sql .= " LIMIT {$paging->getConfig()["start"]}, {$paging->getConfig()["limit"]} ";
        if ($total_record > 0) {
            $list_acc =  $this->db->query($sql)->result_array();
            $html = '';
            foreach ($list_acc as $key => $data_post) {
                $i = "";
                $list_img_cate = '';
                if ($data_post['cate'] != '') {
                    $arr = explode(',', $data_post['cate']);
                    $i = "";
                    foreach ($arr as $key2 =>  $val) {
                        if ($key2 < 4) {
                            $cate = $this->Account->query_sql_row("SELECT * FROM category WHERE id = $val ");
                            $list_img_cate .= '<img src="/' . $cate['image'] . '" alt="' . $cate['name'] . '">';
                        } else {
                            $i = '<p class="hide_cate">+' . ($key2 - 3) . '</p>';
                        }
                    }
                }
                $html .= '
                    <div class="box__list_danhmuc"><a href="/idol-' . $data_post['id'] . '/">
                            <div class="box_detail_danhmuc">
                                <img src="/' . $data_post['avatar'] . '" alt="chi tiết idol">
                                <p class="num_count">' . number_format($data_post["price"], 0, '.', '.') . ' Zen/giờ</p>
                            </div>
                            <p class="name">' . $data_post["name"] . '</p>
                            <p class="intro">' . $data_post["text_intro"] . '</p>
                            <div class="box_data_detail">
                                <div class="list_cate">
                                    ' . $list_img_cate . $i . '
                                </div>
                                <div class="list_star">
                                    <img src="/images/list/star.png" atl="đánh giá sao">
                                    <p class="p_avg_star">0</p>
                                    <p class="p_count_amount">(0)</p>
                                </div>
                            </div>
                        </a>
                    </div>';
            }
        } else {
            $html = '<div></div><p style="color:#FFF;width: 100%;float:none;text-align:center;font-size:20px;margin:0;">KHÔNG TÌM THẤY IDOL<p><div></div>';
        }
        echo $html;
    }
    public function detail_kol($id)
    {
        $data['list_js'] = [
            'select2.min.js',
            'kol/detail_kol.js'
        ];
        $data['list_css'] = [
            'select2.min.css',
            'kol/detail_kol.css'
        ];
        $sql = "SELECT accounts.id,name,avatar,price,cate,text_intro,des,list_img,facebook,youtube,tiktok,instagram,kol.created_at FROM accounts INNER JOIN kol ON kol.id_user = accounts.id WHERE accounts.id = $id AND user_type = 2 ";
        $data['kol'] = $this->Account->query_sql_row($sql);
        if ($data['kol'] == null) {
            redirect('/');
        }
        $data['content'] = 'kol/detail_kol';
        $this->load->view('index', $data);
    }
    public function his_playdoul()
    {
        if (check_login()) {
            $id = $_SESSION['user']['id'];
            if (is_playdoul()) {
                $where = [
                    'id_user' => $id,
                    'id_kol' => $id
                ];
                $count = $this->Account->get_kol_or($where, 0, 0);
                $page = $this->uri->segment(2);
                if ($page < 1 || $page == '') {
                    $page = 1;
                }
                $limit = 20;
                $start = $limit * ($page - 1);
                pagination('/lich-su-choi', count($count), $limit);
                $data['list'] = $this->Account->get_kol_or($where, $start, $limit);
            } else {
                $where = [
                    'id_user' => $id,
                ];
                $count = $this->Account->get_playdoul($where, 0, 0);
                $page = $this->uri->segment(2);
                if ($page < 1 || $page == '') {
                    $page = 1;
                }
                $limit = 20;
                $start = $limit * ($page - 1);
                pagination('/lich-su-choi', count($count), $limit);
                $data['list'] = $this->Account->get_playdoul($where, $start, $limit);
            }
            $data['list_js'] = [
                'kol/his_playdoul.js'
            ];
            $data['list_css'] = [
                'bootstrap.min.css',
                'sanacc/cash.css'
            ];
            $data['content'] = '/kol/ls_playdoul';
            $this->load->view('index', $data);
        } else {
            redirect('/dang-nhap/');
        }
    }
    public function show_more_friend()
    {
        $this->load->helper(['pagination']);
        $page = $this->input->post('page');
        $page = 20 * ($page - 1);
        $type = $this->input->post('type');
        if (check_login()) {
            $id = $_SESSION['user']['id'];
            if ($type == 2) {
                $sql = "SELECT accounts.id,username,avatar,name FROM `accounts` JOIN friend ON friend.id_user = accounts.id OR friend.id_friend = accounts.id WHERE accounts.id != $id AND ( id_user = $id OR id_friend = $id ) GROUP by id  LIMIT $page, 20";
                $p = '<p class="btn_user">Xóa bạn bè</p>';
            } else {
                $sql = "SELECT accounts.id,username,avatar,name FROM `accounts` WHERE id NOT IN   ( SELECT id_user FROM friend WHERE id_user = $id OR id_friend = $id ) AND  id NOT IN   ( SELECT id_friend FROM friend WHERE id_user = $id OR id_friend = $id )    ORDER BY id DESC LIMIT $page, 20";
                $p = '<p class="btn_user">Thêm bạn bè</p>';
            }
        } else {
            if ($type == 2) {
                $sql = "SELECT accounts.id,username,avatar,name FROM `accounts` JOIN friend ON friend.id_user = accounts.id OR friend.id_friend = accounts.id  GROUP by id  LIMIT $page, 20";
                $p = '<p class="btn_user">Xóa bạn bè</p>';
            } else {
                $sql = "SELECT accounts.id,username,avatar,name FROM `accounts` ORDER BY id DESC LIMIT $page, 20";
                $p = '<p class="btn_user">Thêm bạn bè</p>';
            }
        }
        $list = $this->Account->query_sql($sql);
        $html = '';
        if ($list != null) {
            foreach ($list as $val) {
                $html .= '
                            <div class="this_user_list">
                                <img src="/' . $val['avatar'] . '" alt="' . $val['name'] . '">
                                <div class="detail_user">
                                    <p class="name_user">' . $val['name'] . '</p>
                                    <div class="list_box_user" data-id="' . $val['id'] . '">
                                        ' . $p . '
                                        <p class="btn_user chat_user">Nhắn tin</p>
                                    </div>
                                </div>
                            </div>';
            }
            $next = 0;
            if (count($list) == 20) {
                $next = 1;
            }
            $response = [
                'status' => 1,
                'html' => $html,
                'next' => $next
            ];
        } else {
            $response = [
                'status' => 0,
            ];
        }
        echo json_encode($response);
    }
    public function xoa_anh()
    {
        foreach (glob("assets/files/post/*.jpg") as $filename) {
            // echo $filename . '<br>';
            $name = str_replace('assets/files/post/', '', $filename);
            $name = str_replace('.jpg', '', $name);
            $arr = (explode('-', $name));
            $sql = "SELECT id_post FROM posts  WHERE id_post = '$name' AND status = 1";
            $acc = $this->Account->query_sql_row($sql);
            if ($acc != null) {
                // $post = glob("assets/files/post/" . $name . "-*");
                // $post = glob("assets/files/post_tmdt/" . $val . "-*");
                @unlink($filename);
            }
        }
    }
}
