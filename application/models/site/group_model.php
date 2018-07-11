<?php
/**
 * 사이트: 관리자 그룹 관련 모델
 */
class Group_model extends CI_Model
{
    /**
     * 검색: 그룹 목록
     */
    public function getGroupList($vo = array()) {
        $query = "
            SELECT
                seq, grType, grName, grSummary, mainMenuSeq, sort, isUse, isDel, regDate, editDate
            FROM AdminGroup
            WHERE isDel = 'N' ".$this->getWhereGroupList($vo)."
            ORDER BY sort ASC
        ";

        return $this->db->query($query)->result_array();
    }

    /**
     * 검색: 그룹 상세 정보
     */
    public function getGroupDetail($vo) {
        $query = "
            SELECT
                seq, grType, grName, grSummary, mainMenuSeq, isUse, isDel, regDate, editDate
            FROM AdminGroup
            WHERE isDel = 'N'
                AND seq = '".$vo['seq']."'
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 그룹 순서 재정렬
     */
    public function updateSort($vo) {
        if ($this->db->update_batch('AdminGroup', $vo['sortData'], 'seq')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 검색: 그룹 최대 정렬값 보다 1 큰 값 구하기
     */
    public function getNewMaxGroup() {
        $query = "
            SELECT MAX(ifnull(sort, 0)) + 1 AS sort
            FROM AdminGroup
            WHERE isDel = 'N'
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 신규 그룹 등록
     */
    public function addNewGroup($vo) {
        $query = "
            INSERT INTO AdminGroup (
                grName, grSummary, mainMenuSeq, sort, isUse, regDate, editDate
            ) VALUES (
                '".$vo['grName']."',
                '".$vo['grSummary']."',
                '".$vo['mainMenuSeq']."',
                '".$vo['sort']."',
                '".$vo['isUse']."',
                '".$vo['regDate']."',
                '".$vo['editDate']."'
            );
        ";

        $this->db->query($query);

        return $this->db->insert_id();
    }

    /**
     * 처리: 그룹 삭제
     */
    public function removeGroup($vo) {
        $query = "
            UPDATE AdminGroup SET
                isDel = 'Y',
                editDate = '".$vo['editDate']."'
            WHERE seq = '".$vo['seq']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 처리: 그룹 상세정보 수정
     */
    public function updateGroupInfo($vo) {
        $query = "
            UPDATE AdminGroup SET
                grName = '".$vo['grName']."',
                grSummary = '".$vo['grSummary']."',
                mainMenuSeq = '".$vo['mainMenuSeq']."',
                isUse = '".$vo['isUse']."',
                editDate = '".$vo['editDate']."'
            WHERE seq = '".$vo['seq']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 조건: 그룹 목록
     */
    public function getWhereGroupList($vo) {
        $query = "";

        # 그룹 타입 검색 제외 대상
        if (isset($vo['grType']) && $vo['grType'] != "") {
            $query .= " AND grType IN (".$vo['grType'].") ";
        }

        # 그룹 타입 검색 제외 대상
        if (isset($vo['ignoreGrType']) && $vo['ignoreGrType'] != "") {
            $query .= " AND grType NOT IN (".$vo['ignoreGrType'].") ";
        }

        return $query;
    }
}
