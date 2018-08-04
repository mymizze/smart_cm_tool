
<script src="<?=GD_ASSETS_PATH?>/js/plugin/datatables/jquery.dataTables.min.js"></script>
<script src="<?=GD_ASSETS_PATH?>/js/plugin/datatables/dataTables.colVis.min.js"></script>
<script src="<?=GD_ASSETS_PATH?>/js/plugin/datatables/dataTables.tableTools.min.js"></script>
<script src="<?=GD_ASSETS_PATH?>/js/plugin/datatables/dataTables.bootstrap.min.js"></script>
<script src="<?=GD_ASSETS_PATH?>/js/plugin/datatable-responsive/datatables.responsive.min.js"></script>

<script type="text/javascript">
    /**
     * 페이지 로딩시 실행 이벤트
     */
    $(function () {
        /* Data table 초기화 */
        var responsiveHelper_dt_basic = undefined;
        var responsiveHelper_datatable_fixed_column = undefined;
        var responsiveHelper_datatable_col_reorder = undefined;
        var responsiveHelper_datatable_tabletools = undefined;

        var breakpointDefinition = {
            tablet : 1024,
            phone : 480
        };

        $('#dt_board').dataTable({
            "sDom": "<'dt-toolbar'<'col-xs-12 col-sm-6'f><'col-sm-6 col-xs-12 hidden-xs'l>r>"+
                "t"+
                "<'dt-toolbar-footer'<'col-sm-6 col-xs-12 hidden-xs'i><'col-xs-12 col-sm-6'p>>",
            "autoWidth" : true,
            "oLanguage": {
                "sSearch": '<span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>'
            },
            "pageLength": 25,
            "preDrawCallback" : function() {
                // Initialize the responsive datatables helper once.
                if (!responsiveHelper_dt_basic) {
                    responsiveHelper_dt_basic = new ResponsiveDatatablesHelper($('#dt_board'), breakpointDefinition);
                }
            },
            "rowCallback" : function(nRow) {
                responsiveHelper_dt_basic.createExpandIcon(nRow);
            },
            "drawCallback" : function(oSettings) {
                responsiveHelper_dt_basic.respond();
            }
        });

        // Data table에 버튼영역 추가
        $("#dt_board_wrapper").prepend(
            '<div class="p-10 p-b-0 text-right">'+
            '    <a href="#modal-addaccount" class="btn btn-primary" data-ismodify="true" data-toggle="modal">글쓰기</a>'+
            '    <input type="button" class="btn btn-default" value="목록보기" onclick="util.move(\'<?=$page['currentUrl']?>\')">'+
            '</div>'+
            '<hr class="simple">'
        );
        $("#dt_board_wrapper").addClass('bg-silver-lighter-more');
    })
</script>
<div id="content">
    <?php include_once(APPPATH."views/_inc/title.php");?>

    <!-- widget grid -->
    <section id="widget-grid" class="">
        <!-- row -->
        <div class="row">
            <!-- NEW WIDGET START -->
            <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

                <!-- Widget ID (each widget will need unique ID)-->
                <div class="jarviswidget jarviswidget-color-white" id="wid-id-0" data-widget-editbutton="false" data-widget-colorbutton="false">
                    <header>
                        <span class="widget-icon glyphicon glyphicon-align-justify"> <i class="fa fa-table"></i> </span>
                        <h2>게시판 목록</h2>
                    </header>

                    <!-- widget div-->
                    <div>
                        <!-- widget edit box -->
                        <div class="jarviswidget-editbox">
                            <!-- This area used as dropdown edit box -->
                        </div>
                        <!-- end widget edit box -->

                        <!-- widget content -->
                        <div class="widget-body no-padding">

                            <table id="dt_board" class="table table-striped table-bordered table-hover" width="100%">
                                <colgroup>
                                    <col width="50">
                                    <col width="80">
                                    <col width="*">
                                    <col width="80">
                                    <col width="100">
                                    <col width="50">
                                </colgroup>
                                <thead>
                                    <tr>
                                        <th data-hide="phone,tablet">
                                            <label class="checkbox">
                                                <input type="checkbox" name="checkbox">
                                                <i></i>
                                            </label>
                                        </th>
                                        <th>번호</th>
                                        <th data-class="expand">제목</th>
                                        <th data-hide="phone,tablet">이름</th>
                                        <th data-hide="phone,tablet">날짜</th>
                                        <th data-hide="phone,tablet">조회</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <label class="checkbox">
                                                <input type="checkbox" name="checkbox">
                                                <i></i>
                                            </label>
                                        </td>
                                        <td>1</td>
                                        <td>제목 테스트</td>
                                        <td>이름</td>
                                        <td>2018-07-21 17:40:42</td>
                                        <td>6</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <label class="checkbox">
                                                <input type="checkbox" name="checkbox">
                                                <i></i>
                                            </label>
                                        </td>
                                        <td>1</td>
                                        <td>제목 테스트</td>
                                        <td>이름</td>
                                        <td>2018-07-21 17:40:42</td>
                                        <td>6</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                        <!-- end widget content -->
                    </div>
                    <!-- end widget div -->
                </div>
                <!-- end widget -->
            </article>
            <!-- WIDGET END -->
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