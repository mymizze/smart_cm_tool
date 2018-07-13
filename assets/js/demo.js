$("#main").append('<div class="demo"><span id="demo-setting"><i class="fa fa-cogs fa-spin txt-color-blueDark"></i></span> <form> <legend class="no-padding margin-bottom-10">Layout Options</legend> <section> <label> <input name="subscription" id="smart-fixed-header" type="checkbox" class="checkbox style-0"><span>Fixed Header</span></label> <label> <input type="checkbox" name="terms" id="smart-fixed-navigation" class="checkbox style-0"><span>Fixed Navigation</span></label> <label> <input type="checkbox" name="terms" id="smart-fixed-ribbon" class="checkbox style-0"><span>Fixed Ribbon</span></label> <label> <input type="checkbox" name="terms" id="smart-fixed-footer" class="checkbox style-0"><span>Fixed Footer</span></label> <label> <input type="checkbox" name="terms" id="smart-fixed-container" class="checkbox style-0"><span>Inside <b>.container</b></span></label> <label style="display:block;"> <input type="checkbox" id="smart-topmenu" class="checkbox style-0"><span>Menu on <b>top</b></span></label> <section> </form> </div>');
var smartbgimage = "<h6 class='margin-top-10 semi-bold'>Background</h6><img src='/assets/img/pattern/graphy-xs.png' data-htmlbg-url='/assets/img/pattern/graphy.png' width='22' height='22' class='margin-right-5 bordered cursor-pointer'><img src='/assets/img/pattern/tileable_wood_texture-xs.png' width='22' height='22' data-htmlbg-url='/assets/img/pattern/tileable_wood_texture.png' class='margin-right-5 bordered cursor-pointer'><img src='/assets/img/pattern/sneaker_mesh_fabric-xs.png' width='22' height='22' data-htmlbg-url='/assets/img/pattern/sneaker_mesh_fabric.png' class='margin-right-5 bordered cursor-pointer'><img src='/assets/img/pattern/nistri-xs.png' data-htmlbg-url='/assets/img/pattern/nistri.png' width='22' height='22' class='margin-right-5 bordered cursor-pointer'><img src='/assets/img/pattern/paper-xs.png' data-htmlbg-url='/assets/img/pattern/paper.png' width='22' height='22' class='bordered cursor-pointer'>";

$("#smart-bgimages").fadeOut(),

$("#demo-setting").click(function () {
    $(".demo").toggleClass("activate")
}),

/*Fixed Header*/
$('input[type="checkbox"]#smart-fixed-header').click(function () {
    $(this).is(":checked") ?
        $.root_.addClass("fixed-header") :
        (
            $('input[type="checkbox"]#smart-fixed-ribbon').prop("checked", !1),
            $('input[type="checkbox"]#smart-fixed-navigation').prop("checked", !1),
            $.root_.removeClass("fixed-header"),
            $.root_.removeClass("fixed-navigation"),
            $.root_.removeClass("fixed-ribbon"),
            localStorage.setItem("sm-fixed-header", ""),
            localStorage.setItem("sm-fixed-navigation", ""),
            localStorage.setItem("sm-fixed-ribbon", "")
        )
}),
$('input[type="checkbox"]#smart-fixed-header').on("change", function (a) {
    $(this).prop("checked") ?
        (
            localStorage.setItem("sm-fixed-header", "fixed")
        ) :
        (
            localStorage.setItem("sm-fixed-header", ""),
            localStorage.setItem("sm-fixed-navigation", ""),
            localStorage.setItem("sm-fixed-ribbon", "")
        )
}),
"fixed" == localStorage.getItem("sm-fixed-header") ?
    (
        $('input[type="checkbox"]#smart-fixed-header').prop("checked", !0),
        $.root_.addClass("fixed-header")
    ) :
    $('input[type="checkbox"]#smart-fixed-header').prop("checked", !1),

/*Fixed Navigation*/
$('input[type="checkbox"]#smart-fixed-navigation').click(function () {
    $(this).is(":checked") ?
    (
        $('input[type="checkbox"]#smart-fixed-header').prop("checked", !0),
        $.root_.addClass("fixed-header"),
        $.root_.addClass("fixed-navigation"),
        localStorage.setItem("sm-fixed-header", "fixed"),
        localStorage.setItem("sm-fixed-navigation", "fixed"),
        (
            $('input[type="checkbox"]#smart-fixed-container').prop("checked", !1),
            $.root_.removeClass("container"),
            localStorage.setItem("sm-fixed-container", "")
        )
    ) :
    (
        $('input[type="checkbox"]#smart-fixed-ribbon').prop("checked", !1),
        $.root_.removeClass("fixed-navigation"),
        $.root_.removeClass("fixed-ribbon"),
        localStorage.setItem("sm-fixed-navigation", ""),
        localStorage.setItem("sm-fixed-ribbon", "")
    )
}),
$('input[type="checkbox"]#smart-fixed-navigation').on("change", function (a) {
    $(this).prop("checked") ?
    (
        localStorage.setItem("sm-fixed-navigation", "fixed")
    ) :
    (
        localStorage.setItem("sm-fixed-navigation", "")
    )
}),
"fixed" == localStorage.getItem("sm-fixed-navigation") ?
    (
        $('input[type="checkbox"]#smart-fixed-navigation').prop("checked", !0),
        $.root_.addClass('fixed-navigation')
    ) :
    $('input[type="checkbox"]#smart-fixed-navigation').prop("checked", !1),

/*Fixed Ribbon*/
$('input[type="checkbox"]#smart-fixed-ribbon').click(function () {
    $(this).is(":checked") ?
    (
        $('input[type="checkbox"]#smart-fixed-header').prop("checked", !0),
        $('input[type="checkbox"]#smart-fixed-navigation').prop("checked", !0),
        $('input[type="checkbox"]#smart-fixed-ribbon').prop("checked", !0),
        $.root_.addClass("fixed-header"),
        $.root_.addClass("fixed-navigation"),
        $.root_.addClass("fixed-ribbon"),
        localStorage.setItem("sm-fixed-header", "fixed"),
        localStorage.setItem("sm-fixed-navigation", "fixed"),
        localStorage.setItem("sm-fixed-ribbon", "fixed"),
        (
            $('input[type="checkbox"]#smart-fixed-container').prop("checked", !1),
            $.root_.removeClass("container"),
            localStorage.setItem("sm-fixed-container", "")
        )

    )
    : $.root_.removeClass("fixed-ribbon")
}),
$('input[type="checkbox"]#smart-fixed-ribbon').on("change", function (a) {
    $(this).prop("checked") ?
    (
        localStorage.setItem("sm-fixed-ribbon", "fixed")
    ) :
    (
        localStorage.setItem("sm-fixed-ribbon", "")
    )
}),
"fixed" == localStorage.getItem("sm-fixed-ribbon") ?
    (
        $('input[type="checkbox"]#smart-fixed-ribbon').prop("checked", !0),
        $.root_.addClass('fixed-ribbon')
    ) :
    $('input[type="checkbox"]#smart-fixed-ribbon').prop("checked", !1),


/*Fixed Footer*/
$('input[type="checkbox"]#smart-fixed-footer').click(function () {
    $(this).is(":checked") ?
        $.root_.addClass("fixed-page-footer") :
        $.root_.removeClass("fixed-page-footer")
}),
$('input[type="checkbox"]#smart-fixed-footer').on("change", function (a) {
    $(this).prop("checked") ?
    (
        localStorage.setItem("sm-fixed-footer", "fixed")
    ) :
    (
        localStorage.setItem("sm-fixed-footer", "")
    )
}),
"fixed" == localStorage.getItem("sm-fixed-footer") ?
    (
        $('input[type="checkbox"]#smart-fixed-footer').prop("checked", !0),
        $.root_.addClass('fixed-page-footer')
    ) :
    $('input[type="checkbox"]#smart-fixed-footer').prop("checked", !1),


/*Fixed Topmenu*/
$("#smart-topmenu").on("change", function (a) {
    $(this).prop("checked") ?
        (
            localStorage.setItem("sm-setmenu", "top"),
            location.reload()
        ) :
        (
            localStorage.setItem("sm-setmenu", "left"), location.reload()
        )
}),
"top" == localStorage.getItem("sm-setmenu") ?
    $("#smart-topmenu").prop("checked", !0) :
    $("#smart-topmenu").prop("checked", !1),


/*Inside Container*/
$('input[type="checkbox"]#smart-fixed-container').click(function () {
    $(this).is(":checked") ?
        (
            $('input[type="checkbox"]#smart-fixed-ribbon').prop("checked", !1),
            $('input[type="checkbox"]#smart-fixed-navigation').prop("checked", !1),
            $.root_.addClass("container"),
            $.root_.removeClass("fixed-navigation"),
            $.root_.removeClass("fixed-ribbon"),
            localStorage.setItem("sm-fixed-container", "fixed"),
            localStorage.setItem("sm-fixed-navigation", ""),
            localStorage.setItem("sm-fixed-ribbon", ""),
            smartbgimage ?
                (
                    $("#smart-bgimages").append(smartbgimage).fadeIn(1e3),
                    $("#smart-bgimages img").bind("click", function () {
                        var a = $(this),
                        b = $("html");
                        bgurl = a.data("htmlbg-url"),
                        b.css("background-image", "url(" + bgurl + ")")
                    }),
                    smartbgimage = null
                ) :
                $("#smart-bgimages").fadeIn(1e3)
        ) :
        (
            $.root_.removeClass("container"),
            $("#smart-bgimages").fadeOut()
        )
}),
$('input[type="checkbox"]#smart-fixed-container').on("change", function (a) {
    $(this).prop("checked") ?
        (
            localStorage.setItem("sm-fixed-container", "fixed"),
            localStorage.setItem("sm-fixed-navigation", ""),
            localStorage.setItem("sm-fixed-ribbon", "")
        ) :
        (
            localStorage.setItem("sm-fixed-container", "")
        )
}),
"fixed" == localStorage.getItem("sm-fixed-container") ?
    (
        $('input[type="checkbox"]#smart-fixed-container').prop("checked", !0),
        $("body").addClass('container')
    ) :
    $('input[type="checkbox"]#smart-fixed-container').prop("checked", !1),


/*checking this function*/
$("#reset-smart-widget").bind("click", function () {
    return $("#refresh").click(), !1
}),

$("#smart-styles > a").on("click", function () {
    var a = $(this),
        b = $("#logo img");
    $.root_.removeClassPrefix("smart-style").addClass(a.attr("id")), $("html").removeClassPrefix("smart-style").addClass(a.attr("id")), b.attr("src", a.data("skinlogo")), $("#smart-styles > a #skin-checked").remove(), a.prepend("<i class='fa fa-check fa-fw' id='skin-checked'></i>")
});