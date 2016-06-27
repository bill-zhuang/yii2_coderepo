<?php
use app\assets\AppAssetColorHex;
AppAssetColorHex::register($this);
?>

<title>Color Hex - Bill Coderepo</title>
<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Color Hex</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="bill-chart-canvas">
                    <span>Farbtastic color:</span>
                    <input type="text" id="farbtastic_color" class="form-control"/>

                    <div id="farbtastic_colorpicker"></div>
                </div>
                <div class="bill-chart-canvas bill-margin-left">
                    <span>mini color:</span><br/>
                    <input type="text" id="mini_color"/>
                </div>
            </div>
        </div>
    </div>
    <!-- panel footer -->
    <!--<div class="panel-footer">
    </div>-->
</div>
