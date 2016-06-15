<?php
use app\assets\AppAssetGoogleMap;
AppAssetGoogleMap::register($this);
?>

<title>Bill Coderepo - Google Map</title>
<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Google Map</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <form class="form-inline">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    Location:&nbsp;&nbsp;<input type="text" id="location" class="form-control" style="width:500px;"/>
                    <button type="button" class="btn btn-primary" id="btb_mark_location">
                        <span class="glyphicon glyphicon-search"></span>Search
                    </button>
                </div>
            </form>
        </div>
        <hr>

        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div id="map_canvas" class="bill-google-map-canvas">
                </div>
            </div>
        </div>
    </div>
    <!-- panel footer -->
    <!--<div class="panel-footer">
    </div>-->
</div>
