<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 공통으로 사용 하는 코드형 함수 라이브러리
 */
Class CommonCode
{
    private $admin_model;
    private $group_model;
    private $menu_model;
    private $code_model;
    private $siteconfig_model;

    private $util;

    /**
     * 생성자
     */
    function __construct()
    {
        # CI 객체 생성
        $CI =& get_instance();

        # Model 로드
        $CI->load->model('admin/admin_model');
        $CI->load->model('site/group_model');
        $CI->load->model('site/menu_model');
        $CI->load->model('site/code_model');
        $CI->load->model('site/siteconfig_model');

        # Model 설정
        $this->admin_model = $CI->admin_model;
        $this->group_model = $CI->group_model;
        $this->menu_model = $CI->menu_model;
        $this->code_model = $CI->code_model;
        $this->siteconfig_model = $CI->siteconfig_model;

        # Library 설정
        $this->util = $CI->util;
    }

    /**
     * 메인 메뉴 목록
     */
    public function getMainMenuCodeList() {
        # Model 설정
        $menu_model = $this->menu_model;

        # 메인메뉴 목록 검색
        $mainMenuList = $menu_model->getMainMenuList();

        return $mainMenuList;
    }

    /**
     * 서브 메뉴 목록
     */
    public function getSubMenuCodeList() {
        # Model 설정
        $menu_model = $this->menu_model;

        # 메인메뉴 목록 검색
        $subMenuList = $menu_model->getSubMenuList();

        return $subMenuList;
    }

    /**
     * 관리자 페이지 설정 정보
     */
    public function getAdminSiteInfo() {
        # Model 설정
        $siteconfig_model = $this->siteconfig_model;

        # 메인메뉴 목록 검색
        $adminSiteInfo = $siteconfig_model->getAdminSiteConfigDetail();

        return $adminSiteInfo;
    }

    /**
     * 공통코드 그룹
     */
    public function getCodeGroupList() {
        # Model 설정
        $code_model = $this->code_model;

        # 메인메뉴 목록 검색
        $codeGroupList = $code_model->getGroupList();

        return $codeGroupList;
    }

    /**
     * 공통코드 목록 상세
     */
    public function getCodeList($vo) {
        # Library 설정
        $util = $this->util;

        # Model 설정
        $code_model = $this->code_model;

        # 메뉴 설정을 위한 전체 메뉴 목록
        $code_vo = array(
            'groupCode' => $util->isNullToVal($vo['groupCode'], 0),
        );
        $codeList = $code_model->getCodeList($code_vo);

        return $codeList;
    }

    /**
     * 공통코드 목록 상세
     */
    public function getCodeInfo($vo) {
        # Library 설정
        $util = $this->util;

        # Model 설정
        $code_model = $this->code_model;

        # 메뉴 설정을 위한 전체 메뉴 목록
        $code_vo = array(
            'groupCode' => $util->isNullToVal($vo['groupCode'], 0),
            'itemCode'  => $util->isNullToVal($vo['itemCode'], 0),
        );
        $codeInfo = $code_model->getCodeInfo($code_vo);

        return $codeInfo;
    }

    /**
     * 그룹 및 권한 관리에서 그룹구성원 목록
     */
    public function getAdminInGroupList($vo) {
        # Library 설정
        $util = $this->util;

        # Model 설정
        $admin_model = $this->admin_model;

        # 메뉴 설정을 위한 전체 메뉴 목록
        $code_vo = array(
            'grSeq'          => $util->isNullToVal($vo['grSeq'], 0),
            'ignoreAdminId'  => '\'system\'',
            'ignoreStatus'   => '3',
        );
        $adminInGroupList = $admin_model->getAdminInGroupList($code_vo);

        return $adminInGroupList;
    }

    /**
     * 그룹 상세 정보
     */
    public function getGroupInfo($vo) {
        # Library 설정
        $util = $this->util;

        # Model 설정
        $group_model = $this->group_model;

        # 그룹 상세 정보 호출
        $code_vo = array(
            'seq' => $util->isNullToVal($vo['grSeq'], 0),
        );
        $groupInfo = $group_model->getGroupDetail($code_vo);

        return $groupInfo;
    }

    /**
     * 그룹 목록
     */
    public function getGroupList() {
        # Library 설정
        $util = $this->util;

        # Model 설정
        $group_model = $this->group_model;

        # 그룹 목록 검색
        $group_vo = array(
            'ignoreGrType' => '\'system\'',
        );
        $groupList = $group_model->getGroupList($group_vo);

        return $groupList;
    }
}
