<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Manager extends CI_Controller
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
        $this->load->library('Globals');
        $this->load->library('pagination311');
        if (isset($_SESSION['user']) && $_SESSION['user']['id'] > 0) {
            $where_g = [
                'id' => $_SESSION['user']['id'],
            ];
            $check_user_g = $this->Account->get_detail_user($where_g);
            $_SESSION['user'] = $check_user_g;
            // if ($check_user_g['active'] != 1) {
            //     redirect('/xac-thuc-tai-khoan/');
            // }
        }
        // $tam = $_SERVER['REQUEST_URI'];
        // if ($tam != '/dang-nhap/' && $tam != '/dang-ky/' && $tam != '/xac-thuc-tai-khoan/') {
        //     setcookie('url_301', $tam, time() + 86400, '/');
        // }
    }
    public function quan_ly()
    {
        if (check_login()) {
            if (is_playdoul()) {
                $data['list_js'] = [
                    'select2.min.js',
                    'kol/info_kol.js'
                ];
                $data['list_css'] = [
                    'select2.min.css',
                    'kol/info_kol.css'
                ];
                $user_name = $_SESSION['user']['username'];
                $id_user = $_SESSION['user']['id'];
                $sql_kol = "SELECT * FROM kol WHERE id_user = '$id_user'";
                $data['kol'] = $this->Account->query_sql_row($sql_kol);
                $data['content'] = '/kol/info_kol';
            } else {
                $data['list_js'] = [
                    'manager/manager.js'
                ];
                $data['list_css'] = [
                    'sanacc/css_quanly.css'
                ];
                $id_user = $_SESSION['user']['id'];
                $data['content'] = '/manager/manager';
            }
            $this->load->view('index', $data);
        } else {
            redirect('/dang-nhap/');
        }
    }

    public function ls_mua_hang()
    {
        if (check_login()) {
            $data['list_js'] = [
                'service/ls_mua_hang.js'
            ];
            $data['list_css'] = [
                'bootstrap.min.css',
                'sanacc/cash.css'
            ];
            $data['content'] = '/manager/ls_mua_hang';
            $this->load->view('index', $data);
        } else {
            redirect('/dang-nhap/');
        }
    }
}
