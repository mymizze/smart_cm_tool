<?php
/**
 * 사이트: 접근 권한 관련 모델
 */
class Access_model extends CI_Model
{
    /**
     * 처리: 신규 접근 권한 그룹별 등록
     */
    public function addAccessByGroup($vo) {
        return $this->db->insert_batch('AdminMenuAccess', $vo);
    }

    /**
     * 처리: 메뉴별 그룹 권한 수정
     */
    public function updateAccessByMenu($vo) {
        $this->db->where('mnSeq', $vo['mnSeq']);
        return $this->db->update_batch('AdminMenuAccess', $vo['accessData'], 'grSeq');
    }

    /**
     * 처리: 그룹별 메뉴 권한 수정
     */
    public function updateAccessByGroup($vo) {
        if ($this->db->update_batch('AdminMenuAccess', $vo['accessData'], 'seq')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 검색: 메뉴 설정에서 해당 메뉴의 그룹 권한 목록
     */
    public function getAccessByGroup($vo) {
        $query = "
            SELECT
                gr.seq, gr.grName, gr.grSummary, acc.accView, acc.accModify
            FROM AdminGroup AS gr
                INNER JOIN AdminMenuAccess AS acc ON acc.grSeq = gr.seq
                INNER JOIN AdminMenu AS mn ON mn.seq = acc.mnSeq
            WHERE gr.isDel = 'N'
                AND acc.mnSeq = '".$vo['mnSeq']."'
                AND gr.grType <> 'system'
            ORDER BY mn.depth1 ASC, mn.depth2 ASC
        ";

        return $this->db->query($query)->result_array();
    }

    /**
     * 검색: 그룹 및 권한관리에서 메뉴별 접속 권한 목록
     */
    public function getAccessByMenu($vo) {
        $query = "
            SELECT
                mn.depth1, mn.depth2, mn.dep1name, mn.dep2name, mn.summary,
                acc.seq AS accSeq, acc.grSeq, acc.mnSeq, acc.accView, acc.accModify
            FROM AdminMenu AS mn
                INNER JOIN AdminMenuAccess AS acc ON acc.mnSeq = mn.seq
                INNER JOIN AdminMenuAccess AS myacc ON myacc.mnSeq = mn.seq AND myacc.grSeq = '".$vo['myGrSeq']."'
            WHERE mn.isDel = 'N'
                AND mn.isUse = 'Y'
                AND acc.grSeq = '".$vo['grSeq']."'
                AND myacc.accView = 'Y'
            ORDER BY mn.depth1 ASC, mn.depth2 ASC
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 검색: 로그인 한 계정의 페이지별 접근권한 체크
     */
    public function getAccessToMenu($vo) {
        $query = "
            SELECT
                acc.accView, acc.accModify
            FROM AdminMenuAccess AS acc
                INNER JOIN AdminMenu AS menu ON acc.mnSeq = menu.seq
                INNER JOIN AdminAccount AS adm ON acc.grSeq = adm.grSeq
                LEFT OUTER JOIN AdminMenuRelative AS mnrl ON menu.seq = mnrl.mnSeq AND mnrl.isUse = 'Y' AND mnrl.isDel = 'N'
            WHERE adm.adminId = '".$vo['adminId']."'
                AND (menu.linkUrl REGEXP '".$vo['linkUrl']."' OR mnrl.linkUrl REGEXP '".$vo['linkUrl']."')
        ";

        return $this->db->query($query)->result();
    }
}
