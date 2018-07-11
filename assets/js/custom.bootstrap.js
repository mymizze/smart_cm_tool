/**==============================================
 * Page Summary: Bootstrap 커스터마이징 용 스크립트
 *==============================================*/

 /**
  * 페이지 로딩시 실행 이벤트
  */
$(document).ready(function () {

});


/**==============================================
 * custom 객체 생성
 *==============================================*/
var custom = {
    /**
     * Switchery
     */
    switchery: {
        green:  "#00acac",
        red:    "#ff5b57",
        blue:   "#348fe2",
        purple: "#727cb6",
        orange: "#f59c1a",
        black:  "#2d353c",

        /**
         * 상태 변경
         */
        setStatus: function (obj) {
            if (typeof Event === 'function' || !document.fireEvent) {
                var event = document.createEvent('HTMLEvents');
                event.initEvent('change', true, true);
                obj.dispatchEvent(event);
            } else {
                obj.fireEvent('onchange');
            }
        },

        /**
         * 지정 객체 초기화
         */
        renderSwitcher: function (selector, eq) {
            var elems = Array.prototype.slice.call(document.querySelectorAll(selector));

            if ((eq != undefined && typeof parseInt(eq) == "number")) {
                tmpElems = elems;
                elems = [];
                elems.push(tmpElems[eq]);
            }

            $.each(elems, function(index, item) {
                var t = "";

                if ($(item).attr("data-theme")) {
                    switch ($(item).attr("data-theme")) {
                        case "red":
                            t = custom.switchery.red;
                            break;
                        case "blue":
                            t = custom.switchery.blue;
                            break;
                        case "purple":
                            t = custom.switchery.purple;
                            break;
                        case "orange":
                            t = custom.switchery.orange;
                            break;
                        case "black":
                            t = custom.switchery.black
                    }
                }

                var options = {};
                options.color = t, options.secondaryColor = $(item).attr("data-secondary-color") ? $(item).attr("data-secondary-color") : "#dfdfdf", options.className = $(item).attr("data-classname") ? $(item).attr("data-classname") : "switchery", options.disabled = $(item).attr("data-disabled") ? !0 : !1, options.disabledOpacity = $(item).attr("data-disabled-opacity") ? parseFloat($(item).attr("data-disabled-opacity")) : .5, options.speed = $(item).attr("data-speed") ? $(item).attr("data-speed") : "0.5s";
                new Switchery(item, options);
            });
        }
    }
}
