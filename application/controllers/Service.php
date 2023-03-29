<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Service extends CI_Controller
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
    public function ban_the()
    {
        $data['list_js'] = [
            'service/ban_the.js'
        ];
        $data['list_css'] = [
            'sanacc/css_card_garena.css'
        ];

        $data['content'] = '/service/ban_the';
        $data['index'] = 1;
        $data['meta_title'] = 'Bán thẻ chiết khấu cao';
        $this->load->view('index', $data);
    }
    public function nap_the()
    {
        if (check_login()) {
            $id_user = $_SESSION['user']['id'];
            $data['list_js'] = [
                'service/napthe.js'
            ];
            $data['list_css'] = [
                'sanacc/cash.css'
            ];
            $data['content'] = '/service/nap_the';
            $this->load->view('index', $data);
        } else {
            redirect('/dang-nhap/');
        }
    }
}
