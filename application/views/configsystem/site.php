
<script type="text/javascript">
    /**
     * 관리자 사이트
     */
    var admin = {
        /**
         * 상세정보 저장
         */
        save: function () {
            var $frm = $(document.frmAdminSite);
            var $frmParsley = $frm.parsley();

            // 유효성 검사
            $frmParsley.validate();

            if (confirm("저장하시겠습니까?")) {
                $.ajax({
                    url: "/configSystem/adminConfigDetailDo/<?=$adminSiteConfigDetail->seq?>",
                    type: "POST",
                    dataType: "json",
                    data: $frm.serialize(),
                    beforeSend: function () {
                        // write code here before submit
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("code : " + xhr.status);
                    },
                    success: function(data, textStatus, xhr) {
                        alert(data.errMsg);

                        if (data.status == true) {
                            window.location.reload();
                        } else {
                            return false;
                        }
                    }
                });
            }
        }
    }

    /**
     * 웹 사이트
     */
    var web = {
        /**
         * 상세정보 저장
         */
        save: function () {
            var $frm = $(document.frmWebSite);
            var $frmParsley = $frm.parsley();

            // 유효성 검사
            $frmParsley.validate();

            if (confirm("저장하시겠습니까?")) {
                $.ajax({
                    url: "/configSystem/webConfigDetailDo/<?=$webSiteConfigDetail->seq?>",
                    type: "POST",
                    dataType: "json",
                    data: $frm.serialize(),
                    beforeSend: function () {
                        // write code here before submit
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("code : " + xhr.status);
                    },
                    success: function(data, textStatus, xhr) {
                        alert(data.errMsg);

                        if (data.status == true) {
                            window.location.reload();
                        } else {
                            return false;
                        }
                    }
                });
            }
        }
    }

    /**
     * 모바일 사이트
     */
    var mobile = {
        /**
         * 상세정보 저장
         */
        save: function () {
            var $frm = $(document.frmMobileSite);
            var $frmParsley = $frm.parsley();

            // 유효성 검사
            $frmParsley.validate();

            if (confirm("저장하시겠습니까?")) {
                $.ajax({
                    url: "/configSystem/mobileConfigDetailDo/<?=$mobileSiteConfigDetail->seq?>",
                    type: "POST",
                    dataType: "json",
                    data: $frm.serialize(),
                    beforeSend: function () {
                        // write code here before submit
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        console.log("code : " + xhr.status);
                    },
                    success: function(data, textStatus, xhr) {
                        alert(data.errMsg);

                        if (data.status == true) {
                            window.location.reload();
                        } else {
                            return false;
                        }
                    }
                });
            }
        }
    }
</script>

<div id="content">
    <?php include_once(APPPATH."views/_inc/title.php");?>

    <!-- widget grid -->
    <section id="widget-grid" class="">
        <!-- row -->
        <div class="row">
            <!-- 사이트 설정 -->
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <!-- 관리자 사이트 설정 -->
                <div class="jarviswidget <?=GD_JARVISWIDGET_COLOR?>" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon glyphicon glyphicon-align-justify"> <i class="fa fa-comments"></i> </span>
                        <h2>관리자 사이트 설정</h2>
                    </header>

                    <!-- widget div-->
                    <div class="p-0">
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                            <input class="form-control" type="text">
                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body">

                            <form name="frmAdminSite" class="form-horizontal form-bordered" data-parsley-validate="true" onsubmit="admin.save(); return false;">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2" for="adminTitle">사이트 타이틀</label>
                                    <div class="col-md-4 col-sm-4">
                                        <input class="form-control" type="text" id="adminTitle" name="title" value="<?=$adminSiteConfigDetail->title?>" placeholder="title 태그내에 들어갈 문구" data-parsley-required="true" />
                                    </div>
                                </div>
                                <div class="form-group text-center p-10">
                                    <button type="button" onclick="admin.save()" data-ismodify="true" class="btn btn-primary">저장</button>
                                </div>
                            </form>

                        </div>
                        <!-- end widget content -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!--// 관리자 사이트 설정 -->

                <!-- 웹 사이트 설정 -->
                <div class="jarviswidget <?=GD_JARVISWIDGET_COLOR?>" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon glyphicon glyphicon-align-justify"> <i class="fa fa-comments"></i> </span>
                        <h2>웹 사이트 설정</h2>
                    </header>

                    <!-- widget div-->
                    <div class="p-0">
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                            <input class="form-control" type="text">
                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body">

                            <form name="frmWebSite" class="form-horizontal form-bordered" data-parsley-validate="true" onsubmit="web.save(); return false;">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2" for="webTitle">사이트 타이틀</label>
                                    <div class="col-md-4 col-sm-4">
                                        <input class="form-control" type="text" id="webTitle" name="title" value="<?=$webSiteConfigDetail->title?>" placeholder="title 태그내에 들어갈 문구" data-parsley-required="true" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2" for="webGoogleAnalytics">구글 애널리틱스 코드</label>
                                    <div class="col-md-10 col-sm-10">
                                        <textarea id="webGoogleAnalytics" name="googleAnalytics" rows="8" class="form-control" placeholder="구글 애널리틱스 스크립트 코드"><?=$webSiteConfigDetail->googleAnalytics?></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-center p-10">
                                    <button type="button" onclick="web.save()" data-ismodify="true" class="btn btn-primary">저장</button>
                                </div>
                            </form>

                        </div>
                        <!-- end widget content -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!--// 웹 사이트 설정 -->

                <!-- 모바일 사이트 설정 -->
                <div class="jarviswidget <?=GD_JARVISWIDGET_COLOR?>" id="wid-id-0" data-widget-colorbutton="false" data-widget-editbutton="false">
                    <header>
                        <span class="widget-icon glyphicon glyphicon-align-justify"> <i class="fa fa-comments"></i> </span>
                        <h2>모바일 사이트 설정</h2>
                    </header>

                    <!-- widget div-->
                    <div class="p-0">
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                            <input class="form-control" type="text">
                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body">

                            <form name="frmMobileSite" class="form-horizontal form-bordered" data-parsley-validate="true" onsubmit="mobile.save(); return false;">
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2" for="mobTitle">사이트 타이틀</label>
                                    <div class="col-md-4 col-sm-4">
                                        <input class="form-control" type="text" id="mobTitle" name="title" value="<?=$mobileSiteConfigDetail->title?>" placeholder="title 태그내에 들어갈 문구" data-parsley-required="true" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-md-2 col-sm-2" for="mobGoogleAnalytics">구글 애널리틱스 코드</label>
                                    <div class="col-md-10 col-sm-10">
                                        <textarea id="mobGoogleAnalytics" name="googleAnalytics" rows="8" class="form-control" placeholder="구글 애널리틱스 스크립트 코드"><?=$mobileSiteConfigDetail->googleAnalytics?></textarea>
                                    </div>
                                </div>
                                <div class="form-group text-center p-10">
                                    <button type="button" onclick="mobile.save()" data-ismodify="true" class="btn btn-primary">저장</button>
                                </div>
                            </form>

                        </div>
                        <!-- end widget content -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!--// 모바일 사이트 설정 -->
            </aticle>
            <!--// 사이트 설정 -->
        </div>
        <!-- end row -->

        <!-- row -->
        <div class="row">
            <!-- a blank row to get started -->
            <div class="col-sm-12">
                <!-- your contents here -->
            </div>
        </div>
        <!-- end row -->
    </section>
    <!-- end widget grid -->
</div>
