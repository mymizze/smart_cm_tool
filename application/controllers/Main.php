<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 메인 화면
 */
class Main extends MY_Controller
{

    # 기본 레이아웃 파일 설정
    public $layout = 'layout_default';

    /**
     * 생성자
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 메인화면: 페이지
     */
    public function index()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('main/index', $data);
    }

    /**
     * 빈 화면: 페이지
     */
    public function blank()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('main/blank', $data);
    }
}
