<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 시스템 관리
 */
class ConfigSystem extends MY_Controller
{

    # 레이아웃 파일 설정 기본 : views/_layout/layout_default.php
    public $layout = 'layout_default';

    /**
     * 생성자
     */
    function __construct()
    {
        parent::__construct();

        $this->load->model('site/siteconfig_model');
        $this->load->model('site/menu_model');
        $this->load->model('site/access_model');
        $this->load->model('site/group_model');
        $this->load->model('site/code_model');
    }

    /**
     * 사이트설정: 페이지
     */
    public function site()
    {
        parent::__start();

        # Library 설정
        $data = $this->data;

        # Model 설정
        $siteconfig_model = $this->siteconfig_model;

        # 관리자 사이트 설정 상세정보 검색
        $adminSiteConfigDetail = $siteconfig_model->getAdminSiteConfigDetail();

        if (count($adminSiteConfigDetail) > 0) {
            $adminSiteConfigDetail = $adminSiteConfigDetail[0];
        }
        $data['adminSiteConfigDetail'] = $adminSiteConfigDetail;

        # 웹 사이트 설정 상세정보 검색
        $webSiteConfigDetail = $siteconfig_model->getWebSiteConfigDetail();

        if (count($webSiteConfigDetail) > 0) {
            $webSiteConfigDetail = $webSiteConfigDetail[0];
        }
        $data['webSiteConfigDetail'] = $webSiteConfigDetail;

        # 모바일 사이트 설정 상세정보 검색
        $mobileSiteConfigDetail = $siteconfig_model->getMobileSiteConfigDetail();

        if (count($mobileSiteConfigDetail) > 0) {
            $mobileSiteConfigDetail = $mobileSiteConfigDetail[0];
        }
        $data['mobileSiteConfigDetail'] = $mobileSiteConfigDetail;

        $this->load->view('configsystem/site', $data);
    }

    /**
     * 사이트설정: 관리자 사이트 상세정보 수정
     */
    public function adminConfigDetailDo($seq)
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $siteconfig_model = $this->siteconfig_model;

        # 파라미터
        $params = array(
            'title'               => $util->isNullToVal($util->postParam('title'), ''),
        );

        # 사이트 설정 상세정보 검색
        $site_vo = array(
            'seq'   => $seq,
            'data'  => array(
                'title'              => $params['title'],
                'editAdminId'        => $data['session']->adminId,
                'editDate'           => date('Y-m-d H:i:s'),
            ),
        );

        $isResult = $siteconfig_model->updateAdminSiteInfo($site_vo);

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
     * 사이트설정: 웹 사이트 상세정보 수정
     */
    public function webConfigDetailDo($seq)
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $siteconfig_model = $this->siteconfig_model;

        # 파라미터
        $params = array(
            'title'               => $util->isNullToVal($util->postParam('title'), ''),
            'googleAnalytics'     => $util->isNullToVal($util->postParam('googleAnalytics'), ''),
        );

        # 사이트 설정 상세정보 검색
        $site_vo = array(
            'seq'   => $seq,
            'data'  => array(
                'title'              => $params['title'],
                'googleAnalytics'    => $params['googleAnalytics'],
                'editAdminId'        => $data['session']->adminId,
                'editDate'           => date('Y-m-d H:i:s'),
            ),
        );

        $isResult = $siteconfig_model->updateWebSiteInfo($site_vo);

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
     * 사이트설정: 모바일 사이트 상세정보 수정
     */
    public function mobileConfigDetailDo($seq)
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $siteconfig_model = $this->siteconfig_model;

        # 파라미터
        $params = array(
            'title'               => $util->isNullToVal($util->postParam('title'), ''),
            'googleAnalytics'     => $util->isNullToVal($util->postParam('googleAnalytics'), ''),
        );

        # 사이트 설정 상세정보 검색
        $site_vo = array(
            'seq'   => $seq,
            'data'  => array(
                'title'              => $params['title'],
                'googleAnalytics'    => $params['googleAnalytics'],
                'editAdminId'        => $data['session']->adminId,
                'editDate'           => date('Y-m-d H:i:s'),
            ),
        );

        $isResult = $siteconfig_model->updateMobileSiteInfo($site_vo);

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
     * 메뉴설정: 페이지
     */
    public function menu()
    {
        parent::__start();

        # Library 설정
        $data = $this->data;

        # Model 설정
        $menu_model = $this->menu_model;

        # 메뉴 설정을 위한 전체 메뉴 목록
        $data['configMenuList'] = $menu_model->getConfigMenuList();

        $this->load->view('configsystem/menu', $data);
    }

    /**
     * 메뉴설정: 메뉴 정렬용 메뉴 목록 검색
     *
     * @return: json
     */
    public function getConfigMenuList()
    {
        parent::__start();

        # Library 설정
        $data = $this->data;

        # Model 설정
        $menu_model = $this->menu_model;

        # 메뉴 설정을 위한 전체 메뉴 목록
        $configMenuList = $menu_model->getConfigMenuList();

        echo json_encode($configMenuList);
        exit;
    }

    /**
     * 메뉴설정: 각 메뉴별 그룹 목록 검색
     *
     * @return: json
     */
    public function getGroupListForConfigMenu($mnSeq)
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;

        # Model 설정
        $access_model = $this->access_model;

        # 메뉴 설정을 위한 전체 메뉴 목록
        $search_vo = array(
            'mnSeq' => $mnSeq,
        );
        $groupListForConfigMenu = $access_model->getAccessByGroup($search_vo);

        echo json_encode($groupListForConfigMenu);
        exit;
    }

    /**
     * 메뉴설정: 메뉴 정렬 재설정 처리
     *
     * @return: json
     */
    public function menuSortDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        # 보기권한 체크 안함
        $this->isCheckAccessView = false;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $menu_model = $this->menu_model;

        # 파라미터
        $sortData = $util->postParam('sort');

        # "메뉴설정" 메뉴가 업데이트 되었을 경우 페이지 정보가 잘못 나오는 경우를 위해 메뉴설정 정보 선호출
        $search_vo = array(
            'depth1' => $data['page']['depth1'],
            'depth2' => $data['page']['depth2'],
        );
        $menuInfo = $menu_model->getMenuDetail($search_vo);
        $menuSeq = $menuInfo[0]['seq'];

        # 데이터 배열화
        $sortData = json_decode($sortData, true);

        # "메뉴설정" 메뉴의 depth 가 수정 되었을 경우 리턴 URL 재설정
        if (in_array($menuSeq, array_column($sortData, 'seq'))) {
            // "메뉴설정" 의 변경된 depth 값 찾아서 리턴 URL 재설정
            foreach ($sortData as $key => $item) {
                if ($item['seq'] == $menuSeq) {
                    $params = array(
                        'depth1' => $item['depth1'],
                        'depth2' => $item['depth2'],
                    );
                    break;
                }
            }
            $retUrl = GD_HOME_PATH.$menuInfo[0]['linkUrl']."?".http_build_query($params);
        } else {
            // "메뉴설정" 메뉴의 정렬 값 변경이 없을 경우 기존 depth 설정
            $params = array(
                'depth1' => $menuInfo[0]['depth1'],
                'depth2' => $menuInfo[0]['depth2'],
            );
            $retUrl = GD_HOME_PATH.$menuInfo[0]['linkUrl']."?".http_build_query($params);
        }

        # 데이터 업데이트
        if (count($sortData) > 0) {
            $sort_vo = array(
                'sortData' => $sortData
            );
            $isResult = $menu_model->updateSort($sort_vo);
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
                'retUrl'  => $retUrl,
            )
        );
    }

    /**
     * 메뉴설정: 신규 메뉴 추가 처리
     *
     * @return: json
     */
    public function menuAddDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        # 보기권한 체크 안함
        $this->isCheckAccessView = false;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $menu_model = $this->menu_model;
        $access_model = $this->access_model;
        $group_model = $this->group_model;

        # 파라미터
        $params = array(
            'depth1'    => $util->postParam('depth1'),
            'dep1name'  => trim($util->postParam('dep1name')),
            'dep2name'  => trim($util->postParam('dep2name')),
            'summary'   => trim($util->postParam('summary')),
            'linkUrl'   => trim($util->postParam('linkUrl')),
            'linkParams'=> trim($util->postParam('linkParams')),
            'isUse'     => $util->compare($util->postParam('isUse'), "on", "Y", "N"),
        );

        # 메뉴 등록 정보 설정
        $menu_vo = array(
            'depth1'    => $params['depth1'],
            'depth2'    => '',
            'dep1name'  => $params['dep1name'],
            'dep2name'  => $params['dep2name'],
            'summary'   => $params['summary'],
            'linkUrl'   => $params['linkUrl'],
            'linkParams'=> $params['linkParams'],
            'icon'      => '',
            'isUse'     => $params['isUse'],
            'regDate'   => date('Y-m-d H:i:s'),
            'editDate'  => date('Y-m-d H:i:s'),
        );
        # 메뉴 Depth 정보 설정 및 신규메뉴 등록
        if ($menu_vo['depth1'] == "custom") {
            // 신규 메인 메뉴 인 경우
            $newMaxDepth1 = $menu_model->getNewMaxDepth1();
            $menu_vo['depth1'] = $newMaxDepth1[0]->depth1;
            $menu_vo['depth2'] = 1;
        } else {
            // 기존 메인 메뉴 인 경우
            $newMaxDepth2 = $menu_model->getNewMaxDepth2($menu_vo);
            $menu_vo['depth2'] = $newMaxDepth2[0]->depth2;
        }

        $newMenuSeq = $menu_model->addNewMenu($menu_vo);

        # 그룹별 접근권한 값 설정
        $access_vo = array();

        // 그룹 목록 검색
        $groupList = $group_model->getGroupList();

        // 접근 권한 값 설정 및 입력
        foreach ($groupList as $key => $item) {
            if ($item['grType'] == 'system') {
                array_push($access_vo,
                    array(
                        'grSeq'     => $item['seq'],
                        'mnSeq'     => $newMenuSeq,
                        'accView'   => 'Y',
                        'accModify' => 'Y',
                        'regDate'   => date('Y-m-d H:i:s'),
                        'editDate'  => date('Y-m-d H:i:s'),
                    )
                );
            } else {
                array_push($access_vo,
                    array(
                        'grSeq'     => $item['seq'],
                        'mnSeq'     => $newMenuSeq,
                        'accView'   => 'N',
                        'accModify' => 'N',
                        'regDate'   => date('Y-m-d H:i:s'),
                        'editDate'  => date('Y-m-d H:i:s'),
                    )
                );
            }
        }

        $isResult = $access_model->addAccessByGroup($access_vo);

        # 출력: json
        if ($isResult > 0) {
            $isResult = true;
            $errMsg = "신규메뉴 추가가 완료되었습니다.\n접속권한을 설정 해주세요";
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
     * 메뉴설정: 메뉴 삭제 처리
     *
     * @return: json
     */
    public function menuRemoveDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $menu_model = $this->menu_model;

        # 파라미터
        $params = array(
            'seq'    => $util->postParam('mnSeq')
        );

        # 메뉴 등록 정보 설정
        $menu_vo = array(
            'seq'       => $params['seq'],
            'editDate'  => date('Y-m-d H:i:s'),
        );
        $isResult = $menu_model->removeMenu($menu_vo);


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
     * 메뉴설정: 메뉴 기본정보 수정 처리
     *
     * @return: json
     */
    public function baseInfoUpdateDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $menu_model = $this->menu_model;

        # 파라미터
        parse_str($util->postParam('saveData'), $output);
        $params = array(
            'seq'       => $output['mnSeq'],
            'depth1'    => $output['depth1'],
            'depth2'    => $output['depth2'],
            'dep1name'  => trim($output['dep1name']),
            'dep2name'  => trim($output['dep2name']),
            'summary'   => trim($output['summary']),
            'linkUrl'   => trim($output['linkUrl']),
            'linkParams'=> trim($output['linkParams']),
            'isUse'     => $util->compare($output['isUse'], "on", "Y", "N"),
            'saveDataRelAdded' => json_decode($this->input->post('saveDataRelAdded')),
            'saveDataRelNew'   => json_decode($this->input->post('saveDataRelNew')),
        );

        # 메뉴 등록 정보 업데이트
        $baseInfo_vo = array(
            'seq'       => $params['seq'],
            'dep1name'  => $params['dep1name'],
            'dep2name'  => $params['dep2name'],
            'summary'   => $params['summary'],
            'linkUrl'   => $params['linkUrl'],
            'linkParams'=> $params['linkParams'],
            'icon'      => '',
            'isUse'     => $params['isUse'],
            'editDate'  => date('Y-m-d H:i:s'),
        );
        $isResult = $menu_model->updateMenuBaseInfo($baseInfo_vo);

        # 연관 페이지 링크 삭제 대상 처리
        $ignoreSeqs = array();

        foreach ($params['saveDataRelAdded'] as $key => $item) {
            array_push($ignoreSeqs, $item->seq);
        }

        $remove_vo = array(
            'mnSeq'      => $params['seq'],
            'ignoreSeqs' => "'".join("','", $ignoreSeqs)."'",
        );
        $isResult = $menu_model->removeRelative($remove_vo);

        # 연관 페이지 링크 경로 신규 등록
        if (count($params['saveDataRelNew']) > 0) {
            $relative_vo = array(
                'saveDataRelNew' => $params['saveDataRelNew'],
            );
            $isResult = $menu_model->addRelative($relative_vo);
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
     * 메뉴설정: 연관 페이지 목록 검색
     *
     * @return: json
     */
    public function getRelativeList()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $menu_model = $this->menu_model;

        # 파라미터
        $params = array(
            'mnSeq'    => $util->postParam('mnSeq')
        );

        # 메뉴 등록 정보 설정
        $menu_vo = array(
            'mnSeq'    => $params['mnSeq'],
        );
        $relativeList = $menu_model->getRelativeList($menu_vo);

        echo json_encode($relativeList);
        exit;
    }

    /**
     * 메뉴설정: 메뉴 아이콘 설정
     *
     * @return: json
     */
    public function setMenuIcon()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $menu_model = $this->menu_model;

        # 파라미터
        $params = array(
            'depth1'   => $util->postParam('pk'),
            'icon'     => $util->postParam('value'),
        );

        # 메뉴 등록 정보 설정
        $menu_vo = array(
            'depth1'   => $params['depth1'],
            'icon'     => $params['icon'],
            'editDate' => date("Y-m-d H:i:s"),
        );
        $isResult = $menu_model->setMenuIcon($menu_vo);

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

        exit;
    }

    /**
     * 메뉴설정: 메뉴별 전체그룹 접속권한 수정 처리
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
        $mnSeq = $util->postParam('mnSeq');
        $accessData = $util->postParam('access');

        # 데이터 배열화
        $accessData = json_decode($accessData, true);

        # 데이터 업데이트
        if (count($accessData) > 0) {
            $access_vo = array(
                'mnSeq'         => $mnSeq,
                'accessData'    => $accessData,
            );
            $access_model->updateAccessByMenu($access_vo);

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
     * 공통코드관리: 페이지
     */
    public function code()
    {
        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $code_model = $this->code_model;

        # 공통코드 목록
        $groupList = $code_model->getGroupList();
        $data['groupList'] = json_encode($groupList);

        # 그룹코드 기본값
        $defaultGroupInfo = $code_model->getDefaultGroupCode()[0];
        $data['defaultGroupCode'] = $defaultGroupInfo->groupCode;
        $data['defaultGroupName'] = $defaultGroupInfo->groupName;

        $this->load->view('configsystem/code', $data);
    }

    /**
     * 공통코드관리: 공통 코드 탭별 목록
     *
     * @return: json
     */
    public function getCodeList($groupCode='')
    {
        parent::__start();

        # Library 설정
        $data = $this->data;

        # Model 설정
        $code_model = $this->code_model;

        # 메뉴 설정을 위한 전체 메뉴 목록
        $code_vo = array(
            'groupCode' => $groupCode,
        );
        $groupCodeList = $code_model->getCodeList($code_vo);

        echo json_encode($groupCodeList);
        exit;
    }

    /**
     * 공통코드관리: 코드 항목 신규등록 및 수정 처리
     *
     * @return: json
     */
    public function codeUpdateDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $code_model = $this->code_model;

        # 파라미터
        $saveData = $util->postParam('saveData');

        # 데이터 배열화
        $saveData = json_decode($saveData, true);

        # 지역변수 선언
        $errMsg = "";
        $isResult = false;

        # 신규 데이터 입력
        if (count($saveData['new']) > 0) {
            // 코드 메뉴 최종 입력 정보 호출
            $code_vo = array(
                'groupCode' => $saveData['new'][0]['groupCode'],
            );
            $codeDetailInfo = $code_model->getCodeDetail($code_vo);

            if (count($codeDetailInfo) > 0) {
                $codeDetailInfo = $codeDetailInfo[0];
            }

            $groupName  = $codeDetailInfo->groupName;
            $groupSort  = $codeDetailInfo->groupSort;
            $itemCode   = $codeDetailInfo->itemCode;
            $itemSort   = $codeDetailInfo->itemSort;

            $newData = array();
            foreach ($saveData['new'] as $key => $item) {
                $item['groupName'] = $groupName;
                $item['groupSort'] = $groupSort;
                $item['itemCode']  = ++$itemCode;
                $item['itemSort']  = ++$itemSort;
                $item['regDate']   = date("Y-m-d H:i:s");
                $item['editDate']  = date("Y-m-d H:i:s");

                array_push($newData, $item);
            }

            $affectedRows = $code_model->addCode($newData);
            $isResult = true;
            $errMsg .= "신규추가 항목: ".$affectedRows."건";
        }

        # 데이터 업데이트
        if (count($saveData['edit']) > 0) {
            // 수정일 추가 및 데이터 수정 실행
            foreach ($saveData['edit'] as $key => $item) {
                $saveData['edit'][$key]['editDate'] = date("Y-m-d H:i:s");
            }
            $affectedRows = $code_model->updateCode($saveData['edit']);
            $isResult = true;

            if ($errMsg != "") {
                $errMsg .= "\n";
            }
            $errMsg .= "수정 항목: ".$affectedRows."건";
        }

        # 처리 결과
        if ($isResult == true) {
            $errMsg .= "\n처리완료 되었습니다";
        } else {
            $errMsg = "처리 할 항목이 없거나 처리도중 오류가 발생하였습니다";
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
     * 공통코드관리: 코드 항목 삭제 처리
     *
     * @return: json
     */
    public function codeRemoveDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $code_model = $this->code_model;

        # 파라미터
        $params = array(
            'seqs' => $this->input->post('seqs')
        );
        $seqs = "'".join("','", $params['seqs'])."'";

        # 지정 항목 삭제
        $code_vo = array(
            'seqs'      => $seqs,
            'editDate'  => date('Y-m-d H:i:s'),
        );
        $isResult = $code_model->removeCode($code_vo);


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
     * 공통코드관리: 그룹 신규 등록시 고유 ID 중복체크
     */
    public function isUniqueIdForCodeGroup()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $code_model = $this->code_model;

        # 파라미터
        $params = array(
            'groupCode' => $this->input->post('groupCode')
        );

        # ID 존재 유무
        $code_vo = array(
            'groupCode' => $params['groupCode']
        );
        $codeList = $code_model->getCodeListAll($code_vo);

        if (count($codeList) > 0) {
            $isResult = false;
            $errMsg = "이미 사용중인 ID 입니다";
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
     * 공통코드관리: 코드 그룹 신규등록 처리
     *
     * @return: json
     */
    public function codeGroupAddDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $code_model = $this->code_model;

        # 파라미터
        $params = array(
            'groupCode' => $this->input->post('groupCode'),
            'groupName' => $this->input->post('groupName'),
            'itemName'  => $this->input->post('itemName'),
        );

        # 지정 항목 삭제
        $code_vo = array(
            'groupCode'    => $params['groupCode'],
            'groupName'    => $params['groupName'],
            'groupSort'    => $code_model->getCodeMaxGroupSort()[0]->groupSort + 1,
            'itemCode'     => 1,
            'itemName'     => $params['itemName'],
            'itemSort'     => 1,
            'regDate'      => date('Y-m-d H:i:s'),
            'editDate'     => date('Y-m-d H:i:s'),
        );
        $isResult = $code_model->addCodeGroup($code_vo);


        # 출력: json
        if ($isResult) {
            $errMsg = "등록 되었습니다";
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
     * 공통코드관리: 코드 그룹 수정 처리
     *
     * @return: json
     */
    public function codeGroupUpdateDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $code_model = $this->code_model;

        # 파라미터
        $params = array(
            'groupCode' => $this->input->post('groupCode'),
            'groupName' => $this->input->post('groupName'),
        );

        # 지정 그룹 수정
        $code_vo = array(
            'groupCode' => $params['groupCode'],
            'groupName' => $params['groupName'],
            'editDate'  => date('Y-m-d H:i:s'),
        );
        $isResult = $code_model->updateCodeGroup($code_vo);

        # 처리 결과
        if ($isResult == true) {
            $errMsg = "수정 되었습니다";
        } else {
            $errMsg = "처리중 오류가 발생했습니다";
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
     * 공통코드관리: 코드 그룹 삭제 처리
     *
     * @return: json
     */
    public function codeGroupRemoveDo()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $code_model = $this->code_model;

        # 파라미터
        $params = array(
            'groupCode' => $this->input->post('groupCode')
        );

        # 지정 그룹 삭제
        $code_vo = array(
            'groupCode' => $params['groupCode'],
            'editDate'  => date('Y-m-d H:i:s'),
        );
        $isResult = $code_model->removeCodeGroup($code_vo);


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
}
