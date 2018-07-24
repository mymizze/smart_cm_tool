<?php
/**
 * 관리자: 인증 관련 쿼리
 */
class Member_model extends CI_Model
{
    /**
     * 검색: 관리자 목록
     */
    public function getMemberList($vo) {
        $query = "
            SELECT
                seq, userId, userPw, exchangePw, nickname,
                point, level, phone, email, blacklistType,
                bankName, bankDepositName, bankNumber, affiliatedId, apiGamePer,
                accountType, status, memo, lastLogin, regDate, editDate
            FROM MemberAccount
            WHERE 1=1 ".$this->getWhereMemberList($vo)."
            ORDER BY nickname ASC
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 관리자 상세정보
     */
    public function getAdminDetail($vo) {
        $query = "
            SELECT
                seq, userId, userPw, exchangePw, nickname,
                point, level, phone, email, blacklistType,
                bankName, bankDepositName, bankNumber, affiliatedId, apiGamePer,
                accountType, status, memo, lastLogin, regDate, editDate
            FROM MemberAccount
            WHERE isDel = 'N'
                AND seq = '".$vo['seq']."'
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 신규 계정 등록
     */
    public function addNewAccount($vo) {
        $query = "
            INSERT INTO MemberAccount (
                userId, userPw, exchangePw, nickname,
                point, level, phone, email, blacklistType,
                bankName, bankDepositName, bankNumber, affiliatedId, apiGamePer,
                accountType, status, memo, regDate, editDate
            ) VALUES (
                '".$vo['userId']."',
                md5('".$vo['userPw']."'),
                md5('".$vo['exchangePw']."'),
                '".$vo['nickname']."',
                '".$vo['point']."',
                '".$vo['level']."',
                '".$vo['phone']."',
                '".$vo['email']."',
                '".$vo['blacklistType']."',
                '".$vo['bankName']."',
                '".$vo['bankDepositName']."',
                '".$vo['bankNumber']."',
                '".$vo['affiliatedId']."',
                '".$vo['apiGamePer']."',
                '".$vo['accountType']."',
                '".$vo['status']."',
                '".$vo['memo']."',
                '".$vo['regDate']."',
                '".$vo['editDate']."'
            );
        ";

        return $this->db->query($query);
    }

    /**
     * 처리: 계정 정보수정
     */
    public function updateAccount($vo) {
        $query = "
            UPDATE AdminAccount SET
                name        = '".$vo['name']."',
                phone       = '".$vo['phone']."',
                email       = '".$vo['email']."',
                grSeq       = '".$vo['grSeq']."',
                status      = '".$vo['status']."',
                memo        = '".$vo['memo']."',
                picture     = '".$vo['picture']."',
                editDate    = '".$vo['editDate']."'
        ";

        // 비밀번호 입력값이 있을 경우만 수정
        if (isset($vo['userPw']) && $vo['userPw'] != "") {
            $query .= ", userPw = md5('".$vo['userPw']."') ";
        }

        // 환전 비밀번호 입력값이 있을 경우만 수정
        if (isset($vo['exchangePw']) && $vo['exchangePw'] != "") {
            $query .= ", exchangePw = md5('".$vo['exchangePw']."') ";
        }

        $query .= "
            WHERE userId = '".$vo['userId']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 처리: 계정 삭제
     */
    public function removeAccount($vo) {
        $query = "
            UPDATE MemberAccount SET
                isDel = 'Y',
                editDate = '".$vo['editDate']."'
            WHERE userId = '".$vo['userId']."'
        ";

        return $this->db->query($query);
    }


    /**
     * 조건: 관리자 목록
     */
    public function getWhereMemberList($vo) {
        $query = " AND isDel = 'N' ";

        if (isset($vo['withDel']) && $vo['withDel'] == true) {
            $query = "";
        }

        # 검색제외: 관리자 아이디
        if (isset($vo['ignoreUserId']) && $vo['ignoreUserId'] != "") {
            $query .= " AND userId NOT IN (".$vo['ignoreUserId'].") ";
        }

        # 검색제외: 계정상태
        if (isset($vo['ignoreStatus']) && $vo['ignoreStatus'] != "") {
            $query .= " AND status NOT IN (".$vo['ignoreStatus'].") ";
        }

        # 검색: 관리자 아이디
        if (isset($vo['userId']) && $vo['userId'] != "") {
            $query .= " AND userId = '".$vo['userId']."' ";
        }

        # 검색: 계정상태
        if (isset($vo['status']) && $vo['status'] != "") {
            $query .= " AND status = ".$vo['status']." ";
        }

        return $query;
    }

    /**
     * 조건: 관리자 정보
     */
    public function getWhereAdmin($vo) {
        $query = "";

        if (isset($vo['status']) && $vo['status'] != "") {
            $query .= " AND status = ".$vo['status']." ";
        }

        return $query;
    }
}
