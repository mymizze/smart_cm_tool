<?php
/**
 * 사이트: 공통코드 관련 모델
 */
class Code_model extends CI_Model
{
    /**
     * 검색: 공통코드 탭 기본 값
     */
    public function getDefaultGroupCode() {
        $query = "
            SELECT DISTINCT
                groupCode, groupName
            FROM CommonCode
            WHERE isDel = 'N'
                AND isUse = 'Y'
            ORDER BY groupSort ASC
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 공통코드 탭
     */
    public function getGroupList() {
        $query = "
            SELECT DISTINCT
                groupCode, groupName
            FROM CommonCode
            WHERE isDel = 'N'
                AND isUse = 'Y'
            ORDER BY groupSort ASC
        ";

        return $this->db->query($query)->result();
    }


    /**
     * 검색: 공통코드 전체 목록
     */
    public function getCodeListAll($vo) {
        $query = "
            SELECT
                seq, groupCode, groupName, itemName, itemSort,
                isUse, isDel, regDate, editDate
            FROM CommonCode
            WHERE 1=1 ".$this->getWhereCodeListAll($vo)."
            ORDER BY groupSort ASC, itemSort ASC
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 공통코드 목록
     */
    public function getCodeList($vo) {
        $query = "
            SELECT
                seq, groupCode, groupName, itemCode, itemName, itemSort,
                isUse, isDel, regDate, editDate
            FROM CommonCode
            WHERE isDel = 'N' ".$this->getWhereCodeList($vo)."
            ORDER BY groupSort ASC, itemSort ASC
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 공통코드 상세정보
     */
    public function getCodeInfo($vo) {
        $query = "
            SELECT
                seq, groupCode, groupName, itemName, itemSort,
                isUse, isDel, regDate, editDate
            FROM CommonCode
            WHERE isDel = 'N'
                AND groupCode = '".$vo['groupCode']."'
                AND itemCode = '".$vo['itemCode']."'
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 공통코드 신규등록을 위한 추가정보
     */
    public function getCodeDetail($vo) {
        $query = "
            SELECT
                groupCode, groupName, groupSort, itemName,
                MAX(itemCode) AS itemCode, MAX(itemSort) AS itemSort
            FROM CommonCode
            WHERE isDel = 'N'
                AND groupCode = '".$vo['groupCode']."'
            ORDER BY itemSort DESC
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 공통코드 신규 등록
     */
    public function addCode($vo) {
        return $this->db->insert_batch('CommonCode', $vo);
    }

    /**
     * 처리: 공통코드 각 항목 데이터 수정
     */
    public function updateCode($vo) {
        return $this->db->update_batch('CommonCode', $vo, 'seq');
    }

    /**
     * 처리: 공통코드 각 항목 삭제
     */
    public function removeCode($vo) {
        $query = "
            UPDATE CommonCode SET
                isDel = 'Y',
                editDate = '".$vo['editDate']."'
            WHERE seq IN (".$vo['seqs'].")
        ";

        return $this->db->query($query);
    }

    /**
     * 검색: 공통코드 그룹 정렬 최대값
     */
    public function getCodeMaxGroupSort() {
        $query = "
            SELECT MAX(groupSort) AS groupSort
            FROM CommonCode
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 공통코드 그룹 신규 등록
     */
    public function addCodeGroup($vo) {
        if ($this->db->insert('CommonCode', $vo)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 처리: 공통코드 그룹 수정
     */
    public function updateCodeGroup($vo) {
        $query = "
            UPDATE CommonCode SET
                groupCode = '".$vo['groupCode']."',
                groupName = '".$vo['groupName']."',
                editDate = '".$vo['editDate']."'
            WHERE groupCode = '".$vo['groupCode']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 처리: 공통코드 그룹 삭제
     */
    public function removeCodeGroup($vo) {
        $query = "
            UPDATE CommonCode SET
                isDel = 'Y',
                editDate = '".$vo['editDate']."'
            WHERE groupCode = '".$vo['groupCode']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 조건: 공통코드 전체 목록
     */
    public function getWhereCodeListAll($vo) {
        $query = "";

        # 그룹지정
        if (isset($vo['groupCode']) && $vo['groupCode'] != "") {
            $query .= " AND groupCode = '".$vo['groupCode']."' ";
        }

        return $query;
    }

    /**
     * 조건: 공통코드 목록
     */
    public function getWhereCodeList($vo) {
        $query = "";

        # 그룹지정
        if (isset($vo['groupCode']) && $vo['groupCode'] != "") {
            $query .= " AND groupCode = '".$vo['groupCode']."' ";
        }

        # 시퀀스 지정
        if (isset($vo['seq']) && $vo['seq'] != "" && $vo['seq'] != 0) {
            $query .= " AND seq = '".$vo['seq']."' ";
        }

        return $query;
    }
}
