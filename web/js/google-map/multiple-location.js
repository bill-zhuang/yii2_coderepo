
$(document).ready(function(){
    plotMarkers();
});

function plotMarkers()
{
    var lng_lat = js_data;
    markMultiPosition(lng_lat, 'map_canvas_no_cluster');
    markMultiPostionAndCluster(lng_lat, 'map_canvas_cluster');
}