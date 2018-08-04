<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 게시물 관리
 */
class Board extends MY_Controller
{

    # 레이아웃 파일 설정 기본 : views/_layout/layout_default.php
    public $layout = 'layout_default';

    /**
     * 생성자
     */
    function __construct()
    {
        parent::__construct();

        // $this->load->model('site/siteconfig_model');
        // $this->load->model('site/menu_model');
        // $this->load->model('site/access_model');
        // $this->load->model('site/group_model');
        // $this->load->model('site/code_model');
    }

    /**
     * 자유게시판: 페이지
     */
    public function free()
    {
        parent::__start();

        # Library 설정
        $data = $this->data;

        # Model 설정
        // $siteconfig_model = $this->siteconfig_model;

        $this->load->view('board/free', $data);
    }
}
