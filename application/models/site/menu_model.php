<?php
/**
 * 사이트: 메뉴 관련 모델
 */
class Menu_model extends CI_Model
{
    /**
     * 검색: 메뉴 목록
     */
    public function getMenuList($vo) {
        $query = "
            SELECT
                mn.depth1, mn.depth2, mn.dep1name, mn.dep2name, mn.summary, mn.linkUrl, mn.linkParams, mn.icon,
                acc.grSeq, acc.adSeq, acc.mnSeq, acc.accView, acc.accModify
            FROM AdminGroup AS gr
                LEFT OUTER JOIN AdminAccount AS ad ON ad.grSeq = gr.seq AND gr.isDel = 'N' AND ad.isDel = 'N'
                INNER JOIN AdminMenuAccess AS acc ON acc.grSeq = gr.seq
                INNER JOIN AdminMenu AS mn ON mn.seq = acc.mnSeq AND (mn.isDel = 'N' AND mn.isUse = 'Y')
            WHERE 1=1
                AND gr.isUse = 'Y' ".$this->getWhereMenuList($vo)."
            GROUP BY mn.depth1, mn.depth2, mn.dep1name, mn.dep2name, mn.summary, mn.linkUrl, mn.linkParams, mn.icon,
                    acc.grSeq, acc.adSeq, acc.mnSeq, acc.accView, acc.accModify
            ORDER BY depth1 ASC, depth2 ASC
        ";

        return $this->db->query($query)->result_array();
    }

    /**
     * 검색: 메뉴 상세정보
     */
    public function getMenuDetail($vo) {
        $query = "
            SELECT
                seq, depth1, depth2, dep1name, dep2name, icon,
                summary, linkUrl, linkParams, isUse, isDel, regDate, editDate
            FROM AdminMenu
            WHERE 1=1 ".$this->getWhereMenuDetail($vo)."
            LIMIT 1
        ";

        return $this->db->query($query)->result_array();
    }

    /**
     * 검색: 그룹별 메인 메뉴
     */
    public function getGroupMainMenuInfo($vo) {
        $query = "
            SELECT
                mn.depth1, mn.depth2, mn.linkUrl, mn.linkParams
            FROM AdminGroup AS gr
                LEFT OUTER JOIN AdminAccount AS ad ON ad.grSeq = gr.seq AND gr.isDel = 'N' AND ad.isDel = 'N'
                INNER JOIN AdminMenuAccess AS acc ON acc.mnSeq = gr.mainMenuSeq
                INNER JOIN AdminMenu AS mn ON mn.seq = acc.mnSeq AND (mn.isDel = 'N' AND mn.isUse = 'Y')
            WHERE 1=1
                AND gr.isUse = 'Y'
                AND gr.seq = '".$vo['grSeq']."'
                AND acc.accView = 'Y'
            ORDER BY depth1 ASC, depth2 ASC
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 메뉴 설정을 위한 메뉴 목록
     */
    public function getConfigMenuList() {
        $query = "
            SELECT
                seq, depth1, depth2, dep1name, dep2name, summary,
                linkUrl, linkParams, icon, isUse, isDel, regDate, editDate
            FROM AdminMenu
            WHERE isDel = 'N'
            ORDER BY depth1 ASC, depth2 ASC
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 메뉴 순서 재정렬
     */
    public function updateSort($vo) {
        if ($this->db->update_batch('AdminMenu', $vo['sortData'], 'seq')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 검색: 메인 메뉴 목록
     */
    public function getMainMenuList() {
        $query = "
            SELECT
                depth1, dep1name
            FROM AdminMenu
            WHERE isDel = 'N'
            GROUP BY depth1, dep1name
            ORDER BY depth1 ASC
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 서브 메뉴 목록
     */
    public function getSubMenuList() {
        $query = "
            SELECT
                seq, depth1, depth2, dep1name, dep2name, summary,
                linkUrl, linkParams, icon, isUse, isDel, regDate, editDate
            FROM AdminMenu
            WHERE isDel = 'N'
                AND isUse = 'Y'
            ORDER BY depth1 ASC, depth2 ASC
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 메인 메뉴 Depth1 최대 보다 1 큰 값 구하기
     */
    public function getNewMaxDepth1() {
        $query = "
            SELECT MAX(depth1) + 1 AS depth1
            FROM AdminMenu
            WHERE isDel = 'N'
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 서브 메뉴 Depth2 최대 보다 1 큰 값 구하기
     */
    public function getNewMaxDepth2($vo) {
        $query = "
            SELECT IFNULL(MAX(depth2), 0) + 1 AS depth2
            FROM AdminMenu
            WHERE isDel = 'N'
                AND depth1 = '".$vo['depth1']."'
        ";

        return $this->db->query($query)->result();
    }


    /**
     * 처리: 신규 메뉴 등록
     */
    public function addNewMenu($vo) {
        $query = "
            INSERT INTO AdminMenu (
                depth1, depth2, dep1name, dep2name, summary, linkUrl, linkParams, icon, isUse, regDate, editDate
            ) VALUES (
                '".$vo['depth1']."',
                '".$vo['depth2']."',
                '".$vo['dep1name']."',
                '".$vo['dep2name']."',
                '".$vo['summary']."',
                '".$vo['linkUrl']."',
                '".$vo['linkParams']."',
                '".$vo['icon']."',
                '".$vo['isUse']."',
                '".$vo['regDate']."',
                '".$vo['editDate']."'
            );
        ";
        $this->db->query($query);

        return $this->db->insert_id();
    }

    /**
     * 처리: 메뉴 삭제
     */
    public function removeMenu($vo) {
        $query = "
            UPDATE AdminMenu SET
                isDel = 'Y',
                editDate = '".$vo['editDate']."'
            WHERE seq = '".$vo['seq']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 처리: 메뉴 기본정보 수정
     */
    public function updateMenuBaseInfo($vo) {
        $query = "
            UPDATE AdminMenu SET
                dep1name = '".$vo['dep1name']."',
                dep2name = '".$vo['dep2name']."',
                summary = '".$vo['summary']."',
                linkUrl = '".$vo['linkUrl']."',
                linkParams = '".$vo['linkParams']."',
                icon = '".$vo['icon']."',
                isUse = '".$vo['isUse']."',
                editDate = '".$vo['editDate']."'
            WHERE seq = '".$vo['seq']."'
        ";

        return $this->db->query($query);
    }

    /**
     * 검색: 메뉴별 연관 페이지 링크 목록
     */
    public function getRelativeList($vo) {
        $query = "
            SELECT
                rel.seq, rel.mnSeq, rel.linkUrl
            FROM AdminMenu AS menu
                INNER JOIN AdminMenuRelative AS rel ON menu.seq = rel.mnSeq
            WHERE rel.isDel = 'N'
                AND rel.isUse = 'Y'
                AND rel.mnSeq = '".$vo['mnSeq']."'
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 메뉴별 연관 페이지 등록
     */
    public function addRelative($vo) {
        if ($this->db->insert_batch('AdminMenuRelative', $vo['saveDataRelNew'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 처리: 메뉴별 연관 페이지 삭제
     */
    public function removeRelative($vo) {
        $query = "
            DELETE
            FROM AdminMenuRelative
            WHERE mnSeq = '".$vo['mnSeq']."'
                AND seq NOT IN (".$vo['ignoreSeqs'].")
        ";

        return $this->db->query($query);
    }

    /**
     * 조건: 메뉴 목록
     */
    public function getWhereMenuList($vo) {
        $query = "";

        # 그룹지정
        if (isset($vo['grSeq']) && $vo['grSeq'] != "") {
            $query .= " AND gr.seq = '".$vo['grSeq']."' ";
        }
        if (!isset($vo['grSeq']) ||( isset($vo['grSeq']) && $vo['grSeq'] == "")) {
            $query .= " AND gr.seq = 0 ";
        }

        # 메뉴 접근권한 체크
        if (isset($vo['accView']) && $vo['accView'] != "") {
            $query .= " AND acc.accView = '".$vo['accView']."' ";
        }

        return $query;
    }

    /**
     * 조건: 메뉴 상세 정보
     */
    public function getWhereMenuDetail($vo) {
        $query = "";

        # 시퀀스 설정된 경우
        if ((isset($vo['seq']) && $vo['seq'] != "")) {
            $query .= " AND seq = '".$vo['seq']."' ";
        }

        # depth1,2 설정이 된경우
        if ((isset($vo['depth1']) && $vo['depth1'] != "")
            && (isset($vo['depth2']) && $vo['depth2'] != "")) {

            $query .= " AND depth1 = '".$vo['depth1']."' AND depth2 = '".$vo['depth2']."' ";
        }

        return $query;
    }
}
