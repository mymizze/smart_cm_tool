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

        # Model 로드
        $this->load->model('account/member_model');
    }

    /**
     * 회원목록: 페이지
     */
    public function lists()
    {
        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $member_model = $this->member_model;

        # 파라미터
        $params = array(
            'name' => $util->isNullToVal($util->getParam('name'), ''),
        );

        # 사이트 설정 상세정보 검색
        $member_vo = array(
            'ignoreAdminId' => '\'system\'',
            'name'          => $params['name'],
        );
        $data['memberList'] = $member_model->getMemberList($member_vo);

        $this->load->view('member/list', $data);
    }

    /**
     * 회원목록: 회원 신규추가 페이지
     */
    public function write()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/write', $data);
    }

    /**
     * 회원목록: 신규 등록시 고유 ID 중복체크
     */
    public function isUniqueUserId()
    {
        # Layout Template 사용 안함
        $this->yield = FALSE;

        parent::__start();

        # Library 설정
        $data = $this->data;
        $util = $this->util;

        # Model 설정
        $member_model = $this->member_model;

        # 파라미터
        $params = array(
            'userId' => $util->isNullToVal($util->postParam('userId'))
        );

        # ID 존재 유무
        $member_vo = array(
            'withDel' => true,
            'userId' => $params['userId']
        );
        $memberInfo = $member_model->getMemberList($member_vo);

        if (count($memberInfo) > 0) {
            $isResult = false;
            if ($memberInfo[0]->isDel == 'Y') {
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
     * 회원목록: 신규 계정 추가 처리
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
        $member_model = $this->member_model;

        # 파라미터
        $params = array(
            'userId'           => $util->isNullToVal($util->postParam('userId'), ''),
            'userPw'           => $util->isNullToVal($util->postParam('userPw'), ''),
            'exchangePw'       => $util->isNullToVal($util->postParam('exchangePw'), ''),
            'nickname'         => $util->isNullToVal($util->postParam('nickname'), ''),
            'point'            => $util->isNullToVal($util->postParam('point'), 0),
            'email'            => $util->isNullToVal($util->postParam('email'), ''),
            'phone'            => $util->isNullToVal($util->postParam('phone'), ''),
            'level'            => $util->isNullToVal($util->postParam('level'), 0),
            'accountType'      => $util->isNullToVal($util->postParam('accountType'), 1),
            'blacklistType'    => $util->isNullToVal($util->postParam('blacklistType'), 1),
            'status'           => $util->isNullToVal($util->postParam('status'), 1),
            'memo'             => $util->isNullToVal($util->postParam('memo'), ''),
            'bankName'         => $util->isNullToVal($util->postParam('bankName'), ''),
            'bankDepositName'  => $util->isNullToVal($util->postParam('bankDepositName'), ''),
            'bankNumber'       => $util->isNullToVal($util->postParam('bankNumber'), ''),
            'affiliatedId'     => $util->isNullToVal($util->postParam('affiliatedId'), ''),
            'apiGamePer'       => $util->isNullToVal($util->postParam('apiGamePer'), 0),
        );

        # 신규 계정 추가
        $admin_vo = array(
            'userId'           => $params['userId'],
            'userPw'           => $params['userPw'],
            'exchangePw'       => $params['exchangePw'],
            'nickname'         => $params['nickname'],
            'point'            => $params['point'],
            'email'            => $params['email'],
            'phone'            => $params['phone'],
            'level'            => $params['level'],
            'accountType'      => $params['accountType'],
            'blacklistType'    => $params['blacklistType'],
            'status'           => $params['status'],
            'memo'             => $params['memo'],
            'bankName'         => $params['bankName'],
            'bankDepositName'  => $params['bankDepositName'],
            'bankNumber'       => $params['bankNumber'],
            'affiliatedId'     => $params['affiliatedId'],
            'apiGamePer'       => $params['apiGamePer'],
            'regDate'          => date('Y-m-d H:i:s'),
            'editDate'         => date('Y-m-d H:i:s'),
        );
        $isResult = $member_model->addNewAccount($admin_vo);

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
     * 회원정보 수정 내역: 페이지
     */
    public function modifyInfoHistory()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/modifyInfoHistory', $data);
    }

    /**
     * 로그인 내역: 페이지
     */
    public function loginHistory()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/loginHistory', $data);
    }

    /**
     * 차단 내역: 페이지
     */
    public function block()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/block', $data);
    }

    /**
     * 해킹 내역: 페이지
     */
    public function hacking()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/hacking', $data);
    }

    /**
     * 회원가입 코드 발급: 페이지
     */
    public function joinCode()
    {
        parent::__start();

        $data = $this->data;

        $this->load->view('member/joinCode', $data);
    }
}
