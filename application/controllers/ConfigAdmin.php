<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 관리자 페이지 환경 설정
 */
class ConfigAdmin extends MY_Controller
{

    # 레이아웃 파일 설정 기본 : views/_layout/layout_default.php
    public $layout = 'layout_default';

    /**
     * 생성자
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('site/menu_model');
        $this->load->model('site/group_model');
        $this->load->model('site/access_model');
        $this->load->model('account/admin_model');
    }

    /**
     * 그룹 및 권한 관리: 페이지
     */
    public function group()
    {
        parent::__start();

        # Library 설정
        $data = $this->data;

        # Model 설정
        $group_model = $this->group_model;

        # 그룹 목록 검색
        $group_vo = array(
            'ignoreGrType' => '\'system\'',
        );
        $data['groupList'] = $group_model->getGroupList($group_vo);

        $this->load->view('configadmin/group', $data);
    }


    /**
     * 그룹 및 권한 관리: 그룹 상세정보 호출
     */
    public function getGroupDetail($seq)
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $group_model = $this->group_model;

        # 그룹 상세정보 검색
        $group_vo = array(
            'seq' => $seq,
        );
        $groupDetail = $group_model->getGroupDetail($group_vo);

        if (count($groupDetail) > 0) {
            $groupDetail = $groupDetail[0];
        }

        echo json_encode($groupDetail);
        exit;
    }

    /**
     * 그룹 및 권한 관리: 메뉴별 접속 권한 호출
     */
    public function getAccessByMenu($grSeq)
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $access_model = $this->access_model;

        # 메뉴별 접속 권한 검색
        $access_vo = array(
            'grSeq' => $grSeq,
            'myGrSeq' => $this->session->grSeq,
        );
        $accessMenuList = $access_model->getAccessByMenu($access_vo);

        echo json_encode($accessMenuList);
        exit;
    }

    /**
     * 그룹 및 권한 관리: 그룹 정렬 재설정 처리
     *
     * @return: json
     */
    public function groupSortDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $group_model = $this->group_model;

        # 파라미터
        $sortData = $util->postParam('sort');

        # AR 처리를 위한 데이터 배열화
        $sortData = json_decode($sortData, true);

        # 데이터 업데이트
        if (count($sortData) > 0) {
            $sort_vo = array(
                'sortData' => $sortData
            );
            $isResult = $group_model->updateSort($sort_vo);
            $errMsg = "수정 되었습니다";
        } else {
            $isResult = false;
            $errMsg = "수정할 데이터가 없거나 처리중 오류가 발생했습니다";
        }

        # 출력: json
        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
            )
        );
    }

    /**
     * 그룹 및 권한 관리: 신규 그룹 추가 처리
     *
     * @return: json
     */
    public function groupAddDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $group_model = $this->group_model;
        $menu_model = $this->menu_model;
        $access_model = $this->access_model;

        # 파라미터
        $params = array(
            'grName'        => $util->postParam('grName'),
            'grSummary'     => $util->postParam('grSummary'),
            'mainMenuSeq'   => $util->postParam('mainMenuSeq'),
            'isUse'         => $util->compare($util->postParam('isUse'), "on", "Y", "N"),
        );

        # 그룹 등록 정보 설정
        $group_vo = array(
            'grName'        => $params['grName'],
            'grSummary'     => $params['grSummary'],
            'mainMenuSeq'   => $params['mainMenuSeq'],
            'sort'          => 0,
            'isUse'         => $params['isUse'],
            'regDate'       => date('Y-m-d H:i:s'),
            'editDate'      => date('Y-m-d H:i:s'),
        );

        # 최대 정렬값 검색 및 설정
        $newMaxGroup = $group_model->getNewMaxGroup();
        $group_vo['sort'] = $newMaxGroup[0]->sort;

        # 데이터 입력
        $insertId = $group_model->addNewGroup($group_vo);

        # 메뉴별 권한 처리
        if ($insertId > 0) {
            // 그룹별 접근권한 값 설정
            $access_vo = array();

            // 메뉴 목록 호출
            $menuList = $menu_model->getConfigMenuList();

            // 메뉴 수 만큼 신규 그룹 접속 권한 데이터 배열에 설정후 입력
            foreach ($menuList as $key => $item) {
                array_push($access_vo,
                    array(
                        'grSeq'     => $insertId,
                        'mnSeq'     => $item->seq,
                        'accView'   => 'N',
                        'accModify' => 'N',
                        'regDate'   => date('Y-m-d H:i:s'),
                        'editDate'  => date('Y-m-d H:i:s'),
                    )
                );
            }
            $isResult = $access_model->addAccessByGroup($access_vo);

            // 처리결과
            if ($isResult > 0) {
                $isResult = true;
                $errMsg = "신규그룹 추가가 완료되었습니다.";
            } else {
                $isResult = false;
                $errMsg = "처리중 오류가 발생했습니다";
            }
        } else {
            // 처리결과
            $isResult = false;
            $errMsg = "처리중 오류가 발생했습니다";
        }

        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
            )
        );
    }

    /**
     * 그룹 및 권한 관리: 그룹 삭제 처리
     *
     * @return: json
     */
    public function groupRemoveDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $group_model = $this->group_model;
        $admin_model = $this->admin_model;

        # 파라미터
        $params = array(
            'seq'    => $util->postParam('seq')
        );

        # 그룹내에 포함된 모든 구성원들의 그룹값 초기화
        $admin_model->updateResetAdminInGroup(array('grSeq' => $params['seq']));

        # 메뉴 등록 정보 설정
        $group_vo = array(
            'seq'       => $params['seq'],
            'editDate'  => date('Y-m-d H:i:s'),
        );
        $isResult = $group_model->removeGroup($group_vo);

        # 출력: json
        if ($isResult == true) {
            $errMsg = "삭제 되었습니다";
        } else {
            $errMsg = "처리중 오류가 발생했습니다";
        }

        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
            )
        );
    }

    /**
     * 그룹 및 권한 관리: 그룹 상세정보 수정 처리
     *
     * @return: json
     */
    public function groupInfoUpdateDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $group_model = $this->group_model;
        $admin_model = $this->admin_model;

        # 파라미터
        $params = array(
            'seq'           => $util->postParam('seq'),
            'grName'        => $util->postParam('grName'),
            'grSummary'     => $util->postParam('grSummary'),
            'mainMenuSeq'   => $util->postParam('mainMenuSeq'),
            'adSeqs'        => $this->input->post('adSeqs'),
            'isUse'         => $util->compare($util->postParam('isUse'), "on", "Y", "N"),
        );

        # 그룹 상세정보 설정
        $group_vo = array(
            'seq'           => $params['seq'],
            'grName'        => $params['grName'],
            'grSummary'     => $params['grSummary'],
            'mainMenuSeq'   => $params['mainMenuSeq'],
            'isUse'         => $params['isUse'],
            'editDate'      => date('Y-m-d H:i:s'),
        );
        $isResult = $group_model->updateGroupInfo($group_vo);

        # 그룹 구성원 설정
        $adminData = array();

        if (isset($params['adSeqs'])) {
            foreach ($params['adSeqs'] as $key => $adSeq) {
                array_push($adminData, array(
                        'seq' => $adSeq,
                        'grSeq' => $params['seq'],
                    )
                );
            }
        }

        # 지정된 구성원들의 그룹값 설정. 단, 미사용 설정시 그룹내에 포함된 모든 구성원들의 그룹값 초기화
        // 지정 그룹내의 모든 관리자들의 그룹값 리셋
        $admin_model->updateResetAdminInGroup(array('grSeq' => $params['seq']));

        // 그룹 사용중일 경우만 구성원들의 그룹값 설정
        if ($params['isUse'] == "Y") {
            if (count($adminData) > 0) {
                // 설정 요청 한 관리자들의 그룹 값 설정
                $admin_vo = array(
                    'adminData' => $adminData,
                );
                $isResult = $admin_model->updateAdminInGroup($admin_vo);
            }
        }

        # 출력: json
        if ($isResult == true) {
            $errMsg = "수정 되었습니다";
        } else {
            $errMsg = "처리중 오류가 발생했습니다";
        }

        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
            )
        );
    }

    /**
     * 그룹 및 권한 관리: 그룹별 메뉴 접속권한 수정 처리
     *
     * @return: json
     */
    public function accessUpdateDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $access_model = $this->access_model;

        # 파라미터
        $accessData = $util->postParam('access');

        # 데이터 배열화
        $accessData = json_decode($accessData, true);

        # 데이터 업데이트
        if (count($accessData) > 0) {
            $access_vo = array(
                'accessData'    => $accessData,
            );
            $access_model->updateAccessByGroup($access_vo);

            $isResult = true;
            $errMsg = "저장 되었습니다";
        } else {
            $isResult = false;
            $errMsg = "저장할 데이터가 없거나 처리중 오류가 발생했습니다";
        }

        # 출력: json
        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
            )
        );
    }

    /**
     * 그룹 및 권한 관리: 그룹구성원 목록
     */
    public function getAdminInGroupList()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $codeToHtml = $this->codetohtml;

        # 파라미터
        $params = array(
            'grSeq' => $util->isNullToVal($util->postParam('grSeq'), ''),
        );

        # 목록 검색 및 Hhtl 변환
        $adminInGroup = $codeToHtml->getAdminInGroupListSelectBox($params['grSeq']);

        echo $adminInGroup;
    }

    /**
     * 관리자 계정: 리스트 페이지
     */
    public function account()
    {
        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $admin_model = $this->admin_model;

        # 파라미터
        $params = array(
            'name' => $util->isNullToVal($util->getParam('name'), ''),
        );

        # 사이트 설정 상세정보 검색
        $admin_vo = array(
            'ignoreAdminId' => '\'system\'',
            'name'          => $params['name'],
        );
        $data['adminList'] = $admin_model->getAdminList($admin_vo);

        $this->load->view('configadmin/account', $data);
    }

    /**
     * 관리자 계정: 계정 추가 페이지
     */
    public function accountWrite()
    {
        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        $this->load->view('configadmin/accountWrite', $data);
    }

    /**
     * 관리자 계정: 계정 상세정보 호출
     */
    public function getAdminDetail()
    {
        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $admin_model = $this->admin_model;

        # 파라미터
        $params = array(
            'seq' => $util->isNullToVal($util->postParam('seq'), 0),
        );

        # 관리자 계정 상세정보 검색
        $admin_vo = array(
            'seq'    => $params['seq'],
        );
        $adminDetail = $admin_model->getAdminDetail($admin_vo);

        if (count($adminDetail) > 0) {
            $adminDetail = $adminDetail[0];

            // 관리자 사진 파일정보를 Dropzone 영역에 입력하기 위한 형태로 변경
            $pictures = explode('|', $adminDetail->picture);

            foreach ($pictures as &$picture) {
                if ($picture != "") {
                    $picture = array(
                        'name' => $picture,
                        'size' => filesize('.'.GD_ADMIN_PHOTO_PATH.$picture),
                    );
                }
            }

            $adminDetail->picture = json_encode($pictures);
        } else {
            $util->alert("존재하지 않는 계정입니다", null, -1);
            exit;
        }

        echo json_encode($adminDetail);
        exit;
    }

    /**
     * 관리자계정: 그룹 신규 등록시 고유 ID 중복체크
     */
    public function isUniqueAdminId()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $admin_model = $this->admin_model;

        # 파라미터
        $params = array(
            'adminId' => $util->isNullToVal($util->postParam('adminId'))
        );

        # ID 존재 유무
        $admin_vo = array(
            'withDel' => true,
            'adminId' => $params['adminId']
        );
        $adminInfo = $admin_model->getAdminList($admin_vo);

        if (count($adminInfo) > 0) {
            $isResult = false;
            if ($adminInfo[0]->isDel == 'Y') {
                $errMsg = "삭제된 ID 입니다";
            } else {
                $errMsg = "이미 사용중인 ID 입니다";
            }
        } else {
            $isResult = true;
            $errMsg = "사용 가능한 ID 입니다";
        }

        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
            )
        );
    }

    /**
     * 관리자 계정: 신규 계정 추가 처리
     */
    public function accountAddDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $admin_model = $this->admin_model;

        # 파라미터
        $params = array(
            'adminId'       => $util->isNullToVal($util->postParam('adminId'), ''),
            'adminPw'       => $util->isNullToVal($util->postParam('adminPw'), ''),
            'name'          => $util->isNullToVal($util->postParam('name'), ''),
            'phone'         => $util->isNullToVal($util->postParam('phone'), ''),
            'email'         => $util->isNullToVal($util->postParam('email'), ''),
            'grSeq'         => $util->isNullToVal($util->postParam('grSeq'), 0),
            'status'        => $util->isNullToVal($util->postParam('status'), 1),
            'memo'          => $util->isNullToVal($util->postParam('memo'), ''),
            'picture'       => ($this->input->post('picture') != "") ? join("|", $this->input->post('picture')) : "",
        );

        # 신규 계정 추가
        $admin_vo = array(
            'adminId'       => $params['adminId'],
            'adminPw'       => $params['adminPw'],
            'name'          => $params['name'],
            'phone'         => $params['phone'],
            'email'         => $params['email'],
            'grSeq'         => $params['grSeq'],
            'status'        => $params['status'],
            'memo'          => $params['memo'],
            'picture'       => $params['picture'],
            'regDate'       => date('Y-m-d H:i:s'),
            'editDate'      => date('Y-m-d H:i:s'),
        );
        $isResult = $admin_model->addNewAccount($admin_vo);

        # 출력: json
        if ($isResult > 0) {
            $isResult = true;
            $errMsg = "신규 계정 추가가 완료되었습니다";
        } else {
            $isResult = false;
            $errMsg = "처리중 오류가 발생했습니다";
        }

        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
            )
        );
    }

    /**
     * 관리자 계정: 계정 정보수정 처리
     */
    public function accountUpdateDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $admin_model = $this->admin_model;

        # 파라미터
        $params = array(
            'seq'           => $util->isNullToVal($util->postParam('seq'), 0),
            'adminId'       => $util->isNullToVal($util->postParam('adminId'), ''),
            'adminPw'       => $util->isNullToVal($util->postParam('adminPw'), ''),
            'name'          => $util->isNullToVal($util->postParam('name'), ''),
            'phone'         => $util->isNullToVal($util->postParam('phone'), ''),
            'email'         => $util->isNullToVal($util->postParam('email'), ''),
            'grSeq'         => $util->isNullToVal($util->postParam('grSeq'), 0),
            'status'        => $util->isNullToVal($util->postParam('status'), 1),
            'memo'          => $util->isNullToVal($util->postParam('memo'), ''),
            'picture'       => ($this->input->post('picture') != "") ? join("|", $this->input->post('picture')) : "",
        );

        # 계정 정보수정
        $admin_vo = array(
            'adminId'       => $params['adminId'],
            'adminPw'       => $params['adminPw'],
            'name'          => $params['name'],
            'phone'         => $params['phone'],
            'email'         => $params['email'],
            'grSeq'         => $params['grSeq'],
            'status'        => $params['status'],
            'memo'          => $params['memo'],
            'picture'       => $params['picture'],
            'editDate'      => date('Y-m-d H:i:s'),
        );
        $isResult = $admin_model->updateAccount($admin_vo);

        # 출력: json
        if ($isResult) {
            $isResult = true;
            $errMsg = "수정 되었습니다";
        } else {
            $isResult = false;
            $errMsg = "처리중 오류가 발생했습니다";
        }

        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
            )
        );
    }

    /**
     * 관리자 계정: 계정 삭제 처리
     */
    public function accountRemoveDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $admin_model = $this->admin_model;

        # 파라미터
        $params = array(
            'adminId'       => $util->isNullToVal($util->postParam('adminId'), ''),
        );

        # 계정 삭제
        $admin_vo = array(
            'adminId'       => $params['adminId'],
            'editDate'      => date('Y-m-d H:i:s'),
        );
        $isResult = $admin_model->removeAccount($admin_vo);

        # 출력: json
        if ($isResult) {
            $isResult = true;
            $errMsg = "삭제 되었습니다";
        } else {
            $isResult = false;
            $errMsg = "처리중 오류가 발생했습니다";
        }

        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
            )
        );
    }

    /**
     * 관리자 계정: 계정 정보수정 페이지에서 Dropzone 파일 추가/삭제시 파일정보 업데이트 처리
     */
    public function accountFileUpdateDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $admin_model = $this->admin_model;

        # 파라미터
        $params = array(
            'adminId'    => $util->isNullToVal($util->postParam('adminId'), ''),
            'picture'    => ($this->input->post('picture') != "") ? join("|", $this->input->post('picture')) : "",
        );

        # 계정 정보수정
        $admin_vo = array(
            'adminId'       => $params['adminId'],
            'picture'       => $params['picture'],
            'editDate'      => date('Y-m-d H:i:s'),
        );
        $isResult = $admin_model->updateFileAccount($admin_vo);

        # 출력: json
        if ($isResult) {
            $isResult = true;
            $errMsg = "삭제 되었습니다";
        } else {
            $isResult = false;
            $errMsg = "처리중 오류가 발생했습니다";
        }

        echo json_encode(
            array(
                'status'  => $isResult,
                'errMsg'  => $errMsg,
            )
        );
    }
}
