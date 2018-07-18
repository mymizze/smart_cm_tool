<?php
/**
 * 관리자: 인증 관련 쿼리
 */
class Admin_model extends CI_Model
{
    /**
     * 검색: 관리자 목록
     */
    public function getAdminList($vo) {
        $query = "
            SELECT
                seq, grSeq, adminId, adminPw, name, phone, email,
                picture, status, memo, isDel, regDate, editDate
            FROM AdminAccount
            WHERE 1=1 ".$this->getWhereAdminList($vo)."
            ORDER BY name ASC
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 로그인시 관리자 정보
     */
    public function getAdminInfo($vo) {
        $query = "
            SELECT
                seq, grSeq, adminId, adminPw, name,
                phone, email, picture, status, memo,
                isDel, regDate, editDate
            FROM AdminAccount
            WHERE isDel = 'N'
                AND adminId = '".$vo['adminId']."'
                AND adminPw = md5('".$vo['adminPw']."')
                ".$this->getWhereAdmin($vo)."
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 관리자 상세정보
     */
    public function getAdminDetail($vo) {
        $query = "
            SELECT
                seq, grSeq, adminId, adminPw, name,
                phone, email, picture, status, memo,
                isDel, regDate, editDate
            FROM AdminAccount
            WHERE isDel = 'N'
                AND seq = '".$vo['seq']."'
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 그룹 및 권한 관리에서 그룹구성원 목록
     */
    public function getAdminInGroupList($vo)
    {
        $query = "
            SELECT
                acc.department, dep.itemName, acc.seq AS adSeq, acc.adminId, acc.name,
                (CASE WHEN gr.seq = '".$vo['grSeq']."' THEN 'Y' ELSE 'N' END) AS isMember
            FROM AdminAccount AS acc
                LEFT OUTER JOIN AdminGroup AS gr ON acc.grSeq = gr.seq AND gr.seq = '".$vo['grSeq']."'
                LEFT OUTER JOIN (
                    SELECT
                        groupCode, itemCode, itemName
                    FROM CommonCode
                    WHERE groupCode='department'
                ) AS dep ON acc.department = dep.itemCode
            WHERE 1=1 ".$this->getWhereAdminInGroupList($vo)."
            ORDER BY dep.itemName ASC, acc.name ASC;
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 신규 계정 등록
     */
    public function addNewAccount($vo) {
        $query = "
            INSERT INTO AdminAccount (
                grSeq, adminId, adminPw, name, phone,
                email, picture, status, memo,
                regDate, editDate
            ) VALUES (
                '".$vo['grSeq']."',
                '".$vo['adminId']."',
                md5('".$vo['adminPw']."'),
                '".$vo['name']."',
                '".$vo['phone']."',
                '".$vo['email']."',
                '".$vo['picture']."',
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
        if (isset($vo['adminPw']) && $vo['adminPw'] != "") {
            $query .= ", adminPw = md5('".$vo['adminPw']."') ";
        }

        $query .= "
            WHERE adminId = '".$vo['adminId']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 처리: 계정 정보수정
     */
    public function removeAccount($vo) {
        $query = "
            UPDATE AdminAccount SET
                isDel = 'Y',
                editDate = '".$vo['editDate']."'
            WHERE adminId = '".$vo['adminId']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 처리: 계정 파일 정보수정
     */
    public function updateFileAccount($vo) {
        $query = "
            UPDATE AdminAccount SET
                picture     = '".$vo['picture']."',
                editDate    = '".$vo['editDate']."'
            WHERE adminId = '".$vo['adminId']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 처리: 지정 그룹내의 모든 회원들의 그룹값 리셋 설정
     */
    public function updateResetAdminInGroup($vo) {
        $query = "
            UPDATE AdminAccount SET
                grSeq = NULL
            WHERE grSeq = '".$vo['grSeq']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 처리: 메뉴별 그룹 권한 수정
     */
    public function updateAdminInGroup($vo) {
        if ($this->db->update_batch('AdminAccount', $vo['adminData'], 'seq')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 조건: 관리자 목록
     */
    public function getWhereAdminList($vo) {
        $query = " AND isDel = 'N' ";

        if (isset($vo['withDel']) && $vo['withDel'] == true) {
            $query = "";
        }

        # 검색제외: 관리자 아이디
        if (isset($vo['ignoreAdminId']) && $vo['ignoreAdminId'] != "") {
            $query .= " AND adminId NOT IN (".$vo['ignoreAdminId'].") ";
        }

        # 검색제외: 계정상태
        if (isset($vo['ignoreStatus']) && $vo['ignoreStatus'] != "") {
            $query .= " AND status NOT IN (".$vo['ignoreStatus'].") ";
        }

        # 검색: 관리자 아이디
        if (isset($vo['adminId']) && $vo['adminId'] != "") {
            $query .= " AND adminId = '".$vo['adminId']."' ";
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

    /**
     * 조건: 그룹 및 권한 관리에서 그룹구성원 목록
     */
    public function getWhereAdminInGroupList($vo) {
        $query = "";

        # 검색제외: 관리자 아이디
        if (isset($vo['ignoreAdminId']) && $vo['ignoreAdminId'] != "") {
            $query .= " AND acc.adminId NOT IN (".$vo['ignoreAdminId'].") ";
        }

        # 검색제외: 계정상태
        if (isset($vo['ignoreStatus']) && $vo['ignoreStatus'] != "") {
            $query .= " AND acc.status NOT IN (".$vo['ignoreStatus'].") ";
        }

        return $query;
    }
}
