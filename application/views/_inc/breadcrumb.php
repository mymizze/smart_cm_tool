
<div id="ribbon">

    <span class="ribbon-button-alignment">
        <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh"  rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true">
            <i class="fa fa-refresh"></i>
        </span>
    </span>

    <!-- breadcrumb -->
    <ol class="breadcrumb">
        <li><a href="<?=GD_HOME_PATH?>">홈</a></li>

        <?if ($page['dep1name'] != "") {?>
        <li><?=$page['dep1name']?></li>
        <?}?>

        <?if ($page['dep2name'] != "") {?>
        <li><?=$page['dep2name']?></li>
        <?}?>
    </ol>
    <!--// breadcrumb -->
</div>
