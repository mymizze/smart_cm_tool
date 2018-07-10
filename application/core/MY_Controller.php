<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 컨트롤러 확장형 부모 클래스
 */
Class MY_Controller extends CI_Controller
{
    protected $data;

    /**
     * 생성자
     */
    function __construct() {
        parent::__construct();

        # Library 로드 및 설정

        # Model 로드
        // $this->load->model('site/menu_model');
        // $this->load->model('site/access_model');

        # 글로벌 변수 생성 및 설정
        $CI =& get_instance();

        /**
         * 페이지별 접속 권한 체크 여부
         *
         * 접근권한 체크를 원치 않는 페이지일 경우 다음과 같이 설정하면 된다.
         * Controller 에서 페이지 호출부에 parent::__start(); 보다 상단에
         * $this->isCheckAccessView = false; 설정을 하면 권한 여부와 상관 없이
         * 모든 사용자가 접근이 가능한 페이지가 됨(필요에 따라 사용)
         */
        $CI->isCheckAccessView = true;
    }

    /**
     * 페이지 시작시 공통으로 실행시킬 함수
     */
    protected function __start()
    {
        # Library 설정
        $data = $this->global_data;
        $util = $this->util;

        # Model 설정
        $menu_model = $this->menu_model;
        $access_model = $this->access_model;

        # 글로벌 변수에 세션 정보 추가
        $data['session'] = $this->session;

        # 로그인 체크
        if ($this->session->adminId == "") {
            if ($data['page']['depth1'] != "" || $data['page']['depth2'] != "") {
                $util->goUrl(GD_ROOT_PATH.'login?retUrl='.urlencode($data['URI']['base']));
            } else {
                $util->goUrl(GD_ROOT_PATH.'login');
            }
            exit;
        }

        # 페이지 접근권한 체크 및 제한
        if ($this->isCheckAccessView === true) {
            if ($data['page']['depth1'] != "" && $data['page']['depth2'] != "") {
                // 현재 페이지에 접속자 읽기 권한 없을시 제한
                if ($data['access']['view'] != "Y") {
                    $util->alert("접근 권한이 없습니다", null, -1);
                    exit;
                }
            }
        }

        # 접근권한 별 메뉴 목록
        $menu_vo = array(
            'grSeq'     => $this->session->grSeq,
            'accView'   => 'Y',
        );
        $data['menuList'] = $menu_model->getMenuList($menu_vo);

        # 현재 페이지 타이틀 및 Bread Crumb 설정
        if (count($data['menuList']) > 0) {
            foreach ($data['menuList'] as $key => $item) {
                if ($item['depth1'] == $data['page']['depth1']
                    && $item['depth2'] == $data['page']['depth2']) {

                    $data['page']['dep1name'] = $item['dep1name'];
                    $data['page']['dep2name'] = $item['dep2name'];
                    $data['page']['summary']  = $item['summary'];
                    break;
                } elseif ($data['URI']['segment'] == "") {
                    $data['page']['dep1name'] = "대시보드";
                    $data['page']['dep2name'] = "";
                    $data['page']['summary']  = "CMS의 중요한 정보를 한눈에 볼 수 있는 페이지 입니다.";
                } else {
                    $data['page']['dep1name'] = "";
                    $data['page']['dep2name'] = "";
                    $data['page']['summary']  = "";
                }
            }
            $dep1name = $data['page']['dep1name'];
            $dep2name = $data['page']['dep2name'];
        } else {
            $dep1name = "";
            $dep2name = "";
        }

        if ($dep1name != "" && $dep2name != "") {
            $pageName = $dep2name;
        } else {
            if ($dep1name != "" && $dep2name == "") {
                $pageName = $dep1name;
            } else {
                $pageName = "";
            }
        }
        $data['page']['pageName'] = $pageName;

        # 글로벌 변수 재설정
        $this->data = $data;
    }
}

