/**====================================================
 * Page Summary: UI 공통 스크립트
 *
 * Description : UI에 직/간접적으로 관여하는 공통 스크립트 모음
 *====================================================*/

/**
 * 페이지 로딩 후 실행
 */
$(function () {
    /**
     * 셀렉트 박스에서 직접입력 기능 (사용자 입력란 노출 토글)
     *
     * @tutorial:
     *      <select data-toggle="customInput" data-custominput-base="custom" data-custominput-targetid="depth1nameNew">
     *          <option value="custom">직접입력</option>
     *      </select>
     *      <input id="depth1nameNew" maxlength="20" class="form-control hide" />
     *
     * - data-toggle = "customInput" (고정 값)
     * - data-custominput-base = "custom" (selectbox option 값중 직접입력 부분의 value 값)
     * - data-custominput-targetid = "depth1nameNew" (직접입력 선택시 보여질 textbox 의 ID 값)
     */
    $("select[data-toggle=customInput]").change(function(event) {
        var $target = $("#"+$(this).data('custominput-targetid'));

        $target.val('');

        if ($(this).val() == $(this).data('custominput-base')) {
            if ($target.hasClass('hide')) {
                $target.removeClass('hide');
            }
            $target.show();
            $target.focus();
        } else {
            $target.hide();
            $target.val($(this).find(':selected').text());
        }
    });
});

/**
 * ui 객체 생성
 */
var ui = {
    /**
     * 쿠키 초기화
     */
    initCookie: function () {
        util.cookies.del([
            "codeCurrTabIndex"
        ]);
    },

    /**
     * 페이징
     */
    page: function (page) {
        var $frm = $("input[name=currPage]").closest('form');

        $("input[name=currPage]", $frm).val(page);
        $frm.submit();
    }
}
