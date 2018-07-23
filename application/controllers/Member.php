<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 회원 관리
 */
class Member extends MY_Controller
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
     * 화면: 회원 목록
     */
    public function lists()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/list', $data);
    }

    /**
     * 화면: 회원정보 수정 내역
     */
    public function modifyInfoHistory()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/modifyInfoHistory', $data);
    }

    /**
     * 화면: 로그인 내역
     */
    public function loginHistory()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/loginHistory', $data);
    }

    /**
     * 화면: 차단 내역
     */
    public function block()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/block', $data);
    }

    /**
     * 화면: 해킹 내역
     */
    public function hacking()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/hacking', $data);
    }

    /**
     * 화면: 회원가입 코드 발급
     */
    public function joinCode()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/joinCode', $data);
    }
}
