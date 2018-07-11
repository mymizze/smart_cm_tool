<?php
/**
 * 사이트: 관리자 그룹 관련 모델
 */
class Siteconfig_model extends CI_Model
{
    /**
     * 검색: 관리자 사이트 설정 상세정보
     */
    public function getAdminSiteConfigDetail() {
        $query = "
            SELECT
                seq, title, editAdminId, isUse, regDate, editDate
            FROM AdminSiteConfig
            WHERE isUse = 'Y'
            ORDER BY regDate DESC
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 관리자 사이트 설정 수정
     */
    public function updateAdminSiteInfo($vo) {
        $this->db->where('seq', $vo['seq']);

        return $this->db->update('AdminSiteConfig', $vo['data']);
    }

    /**
     * 검색: 웹 사이트 설정 상세정보
     */
    public function getWebSiteConfigDetail() {
        $query = "
            SELECT
                seq, title, googleAnalytics, editAdminId, isUse, regDate, editDate
            FROM WebSiteConfig
            WHERE isUse = 'Y'
            ORDER BY regDate DESC
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 웹 사이트 설정 수정
     */
    public function updateWebSiteInfo($vo) {
        $this->db->where('seq', $vo['seq']);

        return $this->db->update('WebSiteConfig', $vo['data']);
    }

    /**
     * 검색: 모바일 사이트 설정 상세정보
     */
    public function getMobileSiteConfigDetail() {
        $query = "
            SELECT
                seq, title, googleAnalytics, editAdminId, isUse, regDate, editDate
            FROM MobileSiteConfig
            WHERE isUse = 'Y'
            ORDER BY regDate DESC
            LIMIT 1
        ";

        return $this->db->query($query)->result();
    }

    /**
     * 처리: 모바일 사이트 설정 수정
     */
    public function updateMobileSiteInfo($vo) {
        $this->db->where('seq', $vo['seq']);

        return $this->db->update('MobileSiteConfig', $vo['data']);
    }
}
