<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 공통으로 사용 하는 코드형 함수 라이브러리
 */
Class CodeToHtml
{
    private $commoncode;

    /**
     * 생성자
     */
    function __construct()
    {
        # CI 객체 생성
        $CI =& get_instance();

        # Library 설정
        $this->commoncode = $CI->commoncode;
    }

    /**
     * 검색: 메인 메뉴 목록 셀렉트 박스 옵션
     */
    public function getMainMenuCodeListSelectBox() {
        # Model 설정
        $commoncode = $this->commoncode;

        # 메인메뉴 목록 검색
        $mainMenuCodeListSelectBox = $commoncode->getMainMenuCodeList();

        # html 변환
        $html = "";
        foreach ($mainMenuCodeListSelectBox as $key => $item) {
            $html .= "<option value='".$item->depth1."'>".$item->dep1name."</option>";
        }

        return $html;
    }

    /**
     * 서브 메뉴 목록 셀렉트 박스 옵션
     */
    public function getSubMenuCodeListSelectBox() {
        # Model 설정
        $commoncode = $this->commoncode;

        # 메인메뉴 목록 검색
        $subMenuCodeListSelectBox = $commoncode->getSubMenuCodeList();

        # html 변환
        $html = "";
        foreach ($subMenuCodeListSelectBox as $key => $item) {
            $html .= "<option value='".$item->seq."'>[".$item->dep1name."] ".$item->dep2name."</option>";
        }

        return $html;
    }

    /**
     * 소속 목록 셀렉트 박스 옵션
     */
    public function getCodeListSelectBox($groupCode, $itemCode = '') {
        # Model 설정
        $commoncode = $this->commoncode;

        # 메인메뉴 목록 검색
        $code_vo = array(
            'groupCode' => $groupCode,
        );
        $codeList = $commoncode->getCodeList($code_vo);

        # html 변환
        $html = "";
        $selected = "";

        foreach ($codeList as $key => $item) {
            $selected = ($itemCode != "" && $item->itemCode == $itemCode) ? " selected" : "";

            $html .= "<option value='".$item->itemCode."'".$selected.">".$item->itemName."</option>";
        }

        return $html;
    }

    /**
     * 공통코드 텍스트 변환
     */
    public function getCodeToText($groupCode, $itemCode) {
        # Model 설정
        $commoncode = $this->commoncode;

        # 메인메뉴 목록 검색
        $code_vo = array(
            'groupCode' => $groupCode,
            'itemCode'  => $itemCode,
        );
        $codeToText = $commoncode->getCodeInfo($code_vo);

        if (count($codeToText) > 0) {
            $result = $codeToText[0]->itemName;
        } else {
            $result = "";
        }

        return $result;
    }

    /**
     * 그룹 및 권한 관리에서 그룹구성원 목록 셀렉트 박스 옵션
     */
    public function getAdminInGroupListSelectBox($grSeq) {
        # Model 설정
        $commoncode = $this->commoncode;

        # 그룹내에 포함여부를 포함한 관리자 목록 검색
        $admin_vo = array(
            'grSeq' => $grSeq,
        );
        $adminInGroupList = $commoncode->getAdminInGroupList($admin_vo);

        # html 변환
        $html = "";
        $prevDepart = "";

        foreach ($adminInGroupList as $key => $item) {
            $classSelected = ($item->isMember == "Y") ? "selected" : "";

            if ($prevDepart != $item->department) {
                if ($key > 0) {
                    $html .= "</optgroup>";
                }
                $html .= "<optgroup label='".$item->itemName."'>";
            }
            $html .= "<option value='".$item->adSeq."' ".$classSelected.">".$item->name."</option>";

            $prevDepart = $item->department;
        }

        return $html;
    }


    /**
     * 그룹코드 텍스트 변환
     */
    public function getGroupToText($grSeq) {
        # Model 설정
        $commoncode = $this->commoncode;

        # 메인메뉴 목록 검색
        $group_vo = array(
            'grSeq' => $grSeq,
        );
        $groupToText = $commoncode->getGroupInfo($group_vo);

        if (count($groupToText) > 0) {
            $result = $groupToText[0]->grName;
        } else {
            $result = "";
        }

        return $result;
    }

    /**
     * 그룹 목록 셀렉트 박스 옵션
     */
    public function getGroupListSelectBox($grSeq = '') {
        # Model 설정
        $commoncode = $this->commoncode;

        # 그룹 목록 검색
        $groupList = $commoncode->getGroupList();

        # html 변환
        $html = "";
        $selected = "";

        foreach ($groupList as $key => $item) {
            $selected = ($grSeq != "" && $item['seq'] == $grSeq) ? " selected" : "";

            $html .= "<option value='".$item['seq']."'".$selected.">".$item['grName']."</option>";
        }

        return $html;
    }
}
