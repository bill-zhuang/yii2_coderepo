<?php
use app\assets\AppAssetIndex;
AppAssetIndex::register($this);
?>

<div class="panel panel-warning">
    <!-- panel heading -->
    <div class="panel-heading">
        <h2>Variety</h2>
    </div>
    <!-- panel body -->
    <div class="panel-body">
        <div class="row">
            <form class="form-inline">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    Baidu Music download link:&nbsp;&nbsp;
                    <input type="text" id="baidu_music_url" class="form-control" style="width: 500px;"
                           placeholder="like:http://music.baidu.com/song/1262598/download?title=&pst=naga&fr="/>&nbsp;&nbsp;
                    <button class="btn btn-success" type="button" id="btn_generate_download_link">
                        Generate real download link
                    </button><br><br>
                    Real download link:(right click and save as.../other will failed!)&nbsp;&nbsp;
                    <a href="#" id="generated_baidu_music_url"></a>
                    <hr>
                    Generate url with underline:&nbsp;&nbsp;
                    <input type="text" id="url" class="form-control" style="width: 800px;"/>&nbsp;&nbsp;
                    <button class="btn btn-success" type="button" id="btn_generate_url">Generate</button><br/>
                    <a href="#" id="generated_url" target="_blank"></a>
                    <hr>
                    <a href='#exampleModal' data-toggle="modal">Bootstrap Modal Example</a>
                </div>
            </form>
        </div><hr>
    </div>
</div>

<!-- Bootstrap Modal -->
<div id="exampleModal" class="modal fade" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>

            <div class="modal-body">
                <form class="form-inline">
                    <span>Example: </span>
                    <input type="text" class="form-control"/>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>