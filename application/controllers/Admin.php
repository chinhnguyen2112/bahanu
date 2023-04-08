<?php

defined('BASEPATH') or exit('No direct script access allowed');



class Admin extends CI_Controller

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
        $this->load->library('pagination311');
        $this->load->helper('url');
        $this->load->helper('func_helper');
        $this->load->library('Globals');
        $this->load->helper('images');
        $this->load->library('upload');
        $this->db = $this->load->database('default', TRUE);
    }
    public function admin()
    {

        $this->load->view('admin/index');
    }
    public function his_card()
    {
        $html_sql = '';
        $total_record = $this->Account->query_sql_num("SELECT history_card.* FROM history_card  INNER JOIN accounts ON accounts.username = history_card.username   WHERE history_card.id != '0'  $html_sql ");
        $page = $this->uri->segment(3);
        if ($page < 1 || $page == '') {
            $page = 1;
        }
        $limit = 20;
        $start = $limit * ($page - 1);
        pagination('/admin/his_card', $total_record, $limit, 3);
        $sql_get_list_buy = "SELECT history_card.*, source,accounts.id as id_u FROM `history_card` INNER JOIN accounts ON accounts.username = history_card.username WHERE history_card.id != '0' $html_sql ORDER BY `time` DESC LIMIT $start, $limit";
        $data['list'] = $this->Account->query_sql($sql_get_list_buy);
        $data['content'] = '/admin/his_card';
        $this->load->view('admin/index', $data);
    }
    public function member()
    {
        $idus = $this->input->get("idus");
        $username = $this->input->get("username");
        $html_sql = '';
        if ($username != '') {
            $html_sql .= " AND name LIKE '%$username%' ";
        }
        if ($idus != '') {
            $html_sql .= " AND( id LIKE '%$idus%' OR id LIKE '%$idus%' ) ";
        }
        $total_record = $this->Account->query_sql_num("SELECT 8 FROM accounts  WHERE id != '0'  $html_sql ");
        $page = $this->uri->segment(3);
        if ($page < 1 || $page == '') {
            $page = 1;
        }
        $limit = 20;
        $start = $limit * ($page - 1);
        pagination('/admin/member', $total_record, $limit, 3);
        $sql_get_list_buy = "SELECT * FROM `accounts` WHERE id != '0' $html_sql ORDER BY `id` DESC LIMIT $start, $limit";
        $data['list'] = $this->Account->query_sql($sql_get_list_buy);
        $data['content'] = '/admin/member';
        $this->load->view('admin/index', $data);
    }

    public function edit_user()
    {
        $id = $this->input->get('id');
        if ($id > 0) {
            $sql_get_list_buy = "SELECT * FROM  accounts  WHERE id = $id";
            $data['account'] = $this->Account->query_sql_row($sql_get_list_buy);
            $data['content'] = '/admin/edit_user';
            $data['id'] = $id;
            $this->load->view('admin/index', $data);
        } else {
            redirect('/');
        }
    }
    public function ajax_edit_user()
    {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        $pas = $this->input->post('pass');
        $zen = $this->input->post('zen');
        $admin = $this->input->post('admin');
        if ($id > 0) {
            $data = [
                'name' => $name,
                'zen' => $zen
            ];
            if ($pas != '') {
                $data['password'] = $pas;
            }
            if ($admin == 2) {
                $data['user_type'] = 1;
            } else if ($admin == 3) {
                $data['user_type'] = 2;
            } else {
                $data['user_type'] = 0;
            }
            $where_update = [
                'id' => $id
            ];
            $update = $this->Account->update($where_update, $data, 'accounts');
            if ($update) {
                if ($admin == 3) {
                    $data_kol['created_at'] = time();
                    $data_kol['updated_at'] = time();
                    $data_kol['id_user'] = $id;
                    $insert = $this->Account->insert($data_kol, 'kol');
                }
                $msg = [
                    'status' => 1,
                    'msg' => 'Cập nhật thành công'
                ];
            } else {
                $msg = [
                    'status' => 0,
                    'msg' => 'Cập nhật bại'
                ];
            }
        } else {
            $msg = [
                'status' => 0,
                'msg' => 'Không có user chỉ định'
            ];
        }
        echo json_encode($msg);
    }

    public function list_game()
    {
        
        $total_record = $this->Account->query_sql_num("SELECT * FROM category  ORDER BY id DESC  ");
        $page = $this->uri->segment(3);
        if ($page < 1 || $page == '') {
            $page = 1;
        }
        $limit = 20;
        $start = $limit * ($page - 1);
        pagination('/admin/list_game', $total_record, $limit, 3);
        $sql_get_list_buy = "SELECT * FROM `category` ORDER BY `id` DESC LIMIT $start, $limit";
        $data['list'] = $this->Account->query_sql($sql_get_list_buy);
        $data['content'] = '/admin/list_game';
        $this->load->view('admin/index', $data);
    }

    public function edit_game()
    {
            $id = $this->input->get('id');
            if ($id > 0) {
                $sql_get_list_buy = "SELECT * FROM  category  WHERE id = $id";
                $data['account'] = $this->Account->query_sql_row($sql_get_list_buy);
                $data['id'] = $id;
            } 
            $data['content'] = '/admin/edit_game';
            $this->load->view('admin/index', $data);
        
    }

    public function add_new_game() 
    {
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        
        
        // $name_img = str_replace(' ', '_' , $name );
        $data['name'] = $name;
        $data['created_at'] = time();
        
        if ($id > 0) {
            $where_id = ['id' => $id];
            $update = $this->Account->update($where_id, $data, 'category');
            $insert = 0;
            if ( $update) {
                $insert = $id;
            }
        } else {
            $insert = $this->Account->insert($data, 'category');
        }
        if ($insert >0) {
                
                $msg = [
                    'status' => 1,
                    'msg' => 'Thành công'
            ];
        } else {
            $msg = [
                'status' => 0,
                'msg' => 'Thất bại'
            ];
        }
        echo json_encode($msg);
        
        // if (isset($_FILES['img_update']) && $_FILES['img_update']['name'] !== "") {
        //     $filedata         = $_FILES['img_update']['tmp_name'];
        //     $thumb_path        = 'upload/list_game/' . $name_img . '.jpg';
        //     $imguser = $name_img . '.jpg';
        //     $config['file_name'] = $imguser;
        //     $config['upload_path'] = 'upload/list_game';
        //     $config['allowed_types'] = 'jpg|png';
        //     $this->load->library('upload', $config);
        //     $this->upload->initialize($config);
        //     if (!$this->upload->do_upload('img_update')) {
        //         $error = array('error' => $this->upload->display_errors()); 
        //         var_dump($error);
        //     } else {
        //         $imageThumb = new Image($filedata);
        //         $imageThumb->resize(100, 100);
        //         $imageThumb->save($name_img, $config['upload_path'], 'jpg');
        //         $data['image'] = $thumb_path;
        //     }
           
        // }
        
    }


   
    
}
