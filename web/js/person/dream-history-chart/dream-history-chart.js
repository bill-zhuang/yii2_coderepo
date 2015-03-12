
$(document).ready(function(){
    console.log(js_data);
    var chart_data = js_data['chart_data'];
    //console.log(chart_data);
    /*var data_period = chart_data['period'];
    var data_number = chart_data['number'];
    if (data_period.length != 0) {
        initLineChart(data_period, data_number);
        initBarChart(data_period, data_number);
        initPieChart(data_period, data_number);
        initDoughnut(data_period, data_number);
        }
    //all dream history data by day
    initLineChartAll();*/
});

function initLineChartAll()
{
    var chart_data = js_data['all_chart_data'];
    //console.log(chart_data);
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
        var chart_line_canvas = document.getElementById("dream_history_line_chart_all").getContext("2d");
        var chart_line = new Chart(chart_line_canvas).Line(line_data, line_option);
    }
}

function initLineChart(data_period, data_number)
{
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
        responsive: true,
        scaleOverride: true,
        scaleSteps: 8, //y axis length = steps * step_width
        scaleStepWidth: 1, // y axis
        scaleStartValue: 0 // y axis start value
    };
    //$('#dream_history_line_chart').get(0).getContent('2d');
    var chart_line_canvas = document.getElementById("dream_history_line_chart").getContext("2d");
    var chart_line = new Chart(chart_line_canvas).Line(line_data, line_option);
}

function initBarChart(data_period, data_number)
{
    var bar_data = {
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
    var bar_option = {
        responsive: true
    };
    //$('#dream_history_bar_chart').get(0).getContent('2d');
    var chart_bar_canvas = document.getElementById("dream_history_bar_chart").getContext("2d");
    var chart_bar = new Chart(chart_bar_canvas).Bar(bar_data, bar_option);
}

function initPieChart(data_period, data_number)
{
    var pie_data = [];
    var i = 0;
    var len = data_period.length;
    var hex_color = '';
    for(i = 0; i < len; i++) {
        hex_color = getRandomColorHex();
        pie_data.push(
            {
                value: parseInt(data_number[i]), //required, number, string failed
                color: hex_color, //required
                highlight: hex_color, //optional
                label: data_period[i] //optional
            }
        );
    }
    var pie_option = {
        responsive: true
    };
    //$('#dream_history_pie_chart').get(0).getContent('2d');
    var chart_pie_canvas = document.getElementById("dream_history_pie_chart").getContext("2d");
    var chart_pie = new Chart(chart_pie_canvas).Pie(pie_data, pie_option);
}

function initDoughnut(data_period, data_number)
{
    var doughnut_data = [];
    var i = 0;
    var len = data_period.length;
    var hex_color = '';
    for(i = 0; i < len; i++) {
        hex_color = getRandomColorHex();
        doughnut_data.push(
            {
                value: parseInt(data_number[i]), //required, number, string failed
                color: hex_color, //required
                highlight: hex_color, //optional
                label: data_period[i] //optional
            }
        );
    }
    var doughnut_option = {
        responsive: true
    };
    //$('#dream_history_doughnut_chart').get(0).getContent('2d');
    var chart_doughnut_canvas = document.getElementById("dream_history_doughnut_chart").getContext("2d");
    var chart_doughnut = new Chart(chart_doughnut_canvas).Doughnut(doughnut_data, doughnut_option);
}
