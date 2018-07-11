<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 권한 인증
 */
class Auth extends MY_Controller
{
    # 레이아웃 파일 설정 기본 : views/_layout/layout_default.php
    public $layout = 'layout_default';

    /**
     * 생성자
     */
    function __construct()
    {
        parent::__construct();

        # Model 로드
        $this->load->model('admin/admin_model');
        $this->load->model('site/menu_model');
        $this->load->model('site/access_model');
    }

    /**
     * 화면: 로그인
     */
    public function login()
    {
        # 레이아웃 설정
        $this->layout = 'layout_login';

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $admin_model = $this->admin_model;

        # 파라미터
        $retUrl = $util->isNullToVal($util->requestParam('retUrl'), '');

        # 로그인 체크
        if ($this->session->adminId != "") {
            $util->goUrl(GD_HOME_PATH);
            exit;
        }

        $data['retUrl'] = $retUrl;

        # 뷰 이동 및 파라미터 전달
        $this->load->view('auth/login', $data);
    }

    /**
     * 처리: 로그아웃
     */
    public function logout()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        # 로그아웃
        $session_vo = array(
            'grSeq',
            'adminId',
            'name',
            'email',
            'groupMainUrl',
        );
        $this->session->unset_userdata($session_vo);

        header("Location: ".GD_ROOT_PATH."login");
    }

    /**
     * 처리: 로그인
     *
     * @return: json
     */
    public function loginDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        # Library 로드 및 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $admin_model = $this->admin_model;
        $menu_model = $this->menu_model;

        # 파라미터
        $adminId = $util->isNullToVal($util->postParam('adminId'), '');
        $adminPw = $util->isNullToVal($util->postParam('adminPw'), '');
        $retUrl = $util->isNullToVal($util->postParam('retUrl'), '');

        # 관리자 정보 검색
        $login_vo = array(
            'adminId' => $adminId,
            'adminPw' => $adminPw,
        );
        $adminInfo = $admin_model->getAdminInfo($login_vo);

        # 로그인 인증결과 및 세션 값 설정
        $groupMainUrl = "";

        if (count($adminInfo) > 0) {
            $adminInfo = $adminInfo[0];

            switch ($adminInfo->status) {
                // 활동중 계정
                case 1:
                    // 그룹 메인 페이지 정보 호출
                    $group_vo = array(
                        'grSeq'    => $adminInfo->grSeq,
                    );
                    $GroupMainMenuInfo = $menu_model->getGroupMainMenuInfo($group_vo);

                    // 그룹 메인 페이지 설정
                    if (count($GroupMainMenuInfo) > 0) {
                        $params = array(
                            'depth1' => $GroupMainMenuInfo[0]->depth1,
                            'depth2' => $GroupMainMenuInfo[0]->depth2,
                        );
                        $groupMainUrl = GD_HOME_PATH.$GroupMainMenuInfo[0]->linkUrl."?".http_build_query($params);
                    } else {
                        $groupMainUrl = GD_HOME_PATH;
                    }

                    // 로그인 정보 세션 설정
                    $session_vo = array(
                        'grSeq'           => $adminInfo->grSeq,
                        'adminId'         => $adminInfo->adminId,
                        'name'            => $adminInfo->name,
                        'email'           => $adminInfo->email,
                        'picture'         => $adminInfo->picture,
                        'groupMainUrl'    => $groupMainUrl,
                    );
                    $this->session->set_userdata($session_vo);

                    # 리턴 URL이 있는 경우 이동 페이지 재설정
                    if ($retUrl != "") {
                        $groupMainUrl = $retUrl;
                    }

                    $isResult = true;
                    $errMsg = '로그인 성공';
                    break;

                // 사용중지 계정
                case 2:
                    $isResult = false;
                    $errMsg = '사용중지 계정 입니다';
                    break;

                // 퇴사자 계정
                case 3:
                    $isResult = false;
                    $errMsg = '퇴사자 계정 입니다';
                    break;

                default:
                    $isResult = false;
                    $errMsg = '알수 없는 계정 입니다';
                    break;
            }
        } else {
            $isResult = false;
            $errMsg = '접속 정보를 다시한번 확인해 주세요';
        }

        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
                'mainUrl' => $groupMainUrl,
            )
        );
    }

    /**
     * 페이지 로딩시 권한 체크
     */
    public function getAccessPage() {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $access_model = $this->access_model;

        # 파라미터
        $segments = $this->input->post('segments');

        # 접속자의 요청 페이지 접근 권한 검색
        $access_vo = array(
            'adminId' => $this->session->adminId,
            'linkUrl' => join('/', $segments),
        );
        $accessToMenu = $access_model->getAccessToMenu($access_vo);

        if (count($accessToMenu) > 0) {
            $accessToMenu = $accessToMenu[0];
        }

        echo json_encode($accessToMenu);
    }
}
