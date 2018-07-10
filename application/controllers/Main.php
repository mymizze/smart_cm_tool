<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 메인 화면
 */
class Main extends MY_Controller
{

    # 레이아웃 파일 설정 기본 : views/_layout/layout_default.php
    public $layout = 'layout_default';

    /**
     * 생성자
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 화면: 메인 기본 홈
     */
    public function index()
    {
        // parent::__start();

        $data = $this->data;

        $this->load->view('main/index', $data);
    }

    /**
     * 화면: 기본 빈 화면
     */
    public function blank()
    {
        // parent::__start();

        $data = $this->data;

        $this->load->view('main/blank', $data);
    }
}
