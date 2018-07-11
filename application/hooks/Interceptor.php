<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Interceptor
{
    /**
     * 컨트롤러가 인스턴스화 된 직후 실행되는 영역
     * 즉, 사용준비가 완료된 상태
     * 하지만, 인스턴스화 된 후 메소드들이 호출되기 직전
     */
    function postHandleConstructor()
    {
        $CI =& get_instance();

        # Database 로드
        $CI->load->database();

        # Library 로드
        $CI->load->library('session');

        # Model 로드
        $CI->load->model('site/access_model');

        # Library 설정
        $util = $CI->util;
        $commonCode = $CI->commoncode;
        $codeToHtml = $CI->codetohtml;
        $codeToHtml = $CI->codetohtml;

        # Model 설정
        $access_model = $CI->access_model;

        # Util 글로벌 변수 설정
        $CI->global_data['util'] = $util;
        $CI->global_data['commonCode'] = $commonCode;
        $CI->global_data['codeToHtml'] = $codeToHtml;

        # 관리자 사이트 설정 정보
        $CI->global_data['adminSiteInfo'] = $commonCode->getAdminSiteInfo()[0];

        # 디바이스 체크
        $CI->global_data['device']['isAndroid'] = (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false);
        $CI->global_data['device']['isIphone']  = (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false);
        $CI->global_data['device']['isIpad']    = (strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false);

        # 현재 선택한 메뉴 페이지
        $CI->global_data['page'] = array(
            'depth'     => "depth1=".$util->requestParam('depth1')."&depth2=".$util->requestParam('depth2'),
            'depth1'    => $util->requestParam('depth1'),
            'depth2'    => $util->requestParam('depth2'),
            'dep1name'  => '',
            'dep2name'  => '',
            'summary'   => '',
            'pageName'  => '',
        );

        # URL 정보
        $CI->global_data['className']       = $CI->uri->segment(1);
        $CI->global_data['methodName']      = $CI->uri->segment(2);
        $CI->global_data['URI']['segment']  = join("/", $CI->uri->segment_array());
        $CI->global_data['URI']['base']     = join("/", $CI->uri->segment_array())."?".http_build_query($CI->global_data['page']);

        # 현재 페이지 접근권한 값 설정
        if ($CI->session->adminId != "" && $CI->global_data['URI']['segment'] != "") {
            // 접속자의 요청 페이지 접근 권한 검색
            $access_vo = array(
                'adminId' => $CI->session->adminId,
                'linkUrl' => $CI->global_data['URI']['segment'],
            );
            $accessToMenu = $access_model->getAccessToMenu($access_vo);

            if (count($accessToMenu) > 0) {
                $accessToMenu = $accessToMenu[0];

                // 글로벌 data 에 현재 페이지 권한 담기 (값: Y/N)
                $CI->global_data['access']['view'] = $accessToMenu->accView;
                $CI->global_data['access']['modify'] = $accessToMenu->accModify;
            } else {
                $CI->global_data['access']['view'] = 'N';
                $CI->global_data['access']['modify'] = 'N';
            }
        }
    }

    /**
     * 컨트롤러가 인스턴스화되고 메소드실행 후
     */
    function postHandle()
    {}
}