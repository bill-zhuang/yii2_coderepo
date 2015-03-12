
$(document).ready(function(){
    //all bad history data by day
    initLineChartAll();
});
function initLineChartAll()
{
    var chart_data = js_data;
    console.log(chart_data);
    var data_period = chart_data['period'];
    var data_number = chart_data['interval'];
    if (data_period.length != 0) {
        var line_data = {
            labels : data_period,
            datasets: [
                {
                    fillColor: "rgba(151,187,205,0.2)",
                    strokeColor: "rgba(151,187,205,1)",
                    pointColor: "rgba(151,187,205,1)",
                    pointStrokeColor: "#fff",
                    pointHighlightFill: "#fff",
                    pointHighlightStroke: "rgba(220,220,220,1)",
                    data: data_number
                }
            ]
        };
        var line_option = {
            responsive: true
            };
        var chart_line_canvas = document.getElementById("bad_history_line_chart_all").getContext("2d");
        var chart_line = new Chart(chart_line_canvas).Line(line_data, line_option);
    }
}
