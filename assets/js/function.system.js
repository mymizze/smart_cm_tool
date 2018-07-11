/**====================================================
 * Page Summary: 시스템 프로세스 관련
 *
 * Description : 시스템 실행과 관련된 중요 코드
 *              (관계자 외에는 절대 건들지 말것)
 *====================================================*/
 /**
 * 페이지 로딩 후 실행
 */
$(function () {
    /**
     * 수정 권한 체크 (중요: 절대 지우지 마세요)
     */
    var segments = util.uri.segment_array();

    if (segments.length >= 2) {
        $.ajax({
            url: "/auth/getAccessPage",
            type: "POST",
            dataType: "json",
            data: {segments: segments},
            beforeSend: function () {
                // write code here before submit
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log("code : " + xhr.status);
            },
            success: function(data, textStatus, xhr) {
                if (data.accModify != "Y") {
                    $("[data-ismodify=true]").attr('onclick', '');
                    $("[data-ismodify=true]").attr('href', '');

                    $("[data-ismodify=true]").click(function(event) {
                        alert("권한이 없습니다");
                        return false;
                    });
                }
            }
        });
    }
});