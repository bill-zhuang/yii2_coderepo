
$(document).ready(function(){
    //payment history data by month
    initMonthChart();
    //all payment history data by day
    initLineChartAll();
    //payment by category last year
    initYearCategoryChart();
    //payment by category last 30 days
    initMonthCategoryChart();
});

function initMonthChart()
{
    var chart_data = js_data['chart_data'];
    var data_period = chart_data['period'];
    var data_payment = chart_data['payment'];
    var line_canvas_id = 'payment_history_line_chart';
    var bar_canvas_id = 'payment_history_bar_chart';
    if (data_period.length != 0) {
        initLineChart(data_period, data_payment, line_canvas_id);
        initBarChart(data_period, data_payment, bar_canvas_id);
    }
}

function initYearCategoryChart()
{
    var category_chart_data = js_data['year_category_chart_data'];
    var data_category = category_chart_data['category'];
    var data_payment = category_chart_data['payment'];
    $('#year_spent').text('(' + js_data['year_spent'] + ')');
    var line_canvas_id = 'category_payment_history_line_chart';
    var bar_canvas_id = 'category_payment_history_bar_chart';
    if (data_category.length != 0) {
        initLineChart(data_category, data_payment, line_canvas_id);
        initBarChart(data_category, data_payment, bar_canvas_id);
    }
}

function initMonthCategoryChart()
{
    var category_chart_data = js_data['month_category_chart_data'];
    var data_category = category_chart_data['category'];
    var data_payment = category_chart_data['payment'];
    $('#month_spent').text('(' + js_data['month_spent'] + ')');
    var line_canvas_id = 'month_category_payment_history_line_chart';
    var bar_canvas_id = 'month_category_payment_history_bar_chart';
    if (data_category.length != 0) {
        initLineChart(data_category, data_payment, line_canvas_id);
        initBarChart(data_category, data_payment, bar_canvas_id);
    }
}

function initLineChartAll()
{
    var chart_data = js_data['all_chart_data'];
    var data_period = chart_data['period'];
    var data_payment = chart_data['payment'];
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
                    data: data_payment
                }
            ]
        };
        var line_option = {
            responsive: true
        };
        var chart_line_canvas = document.getElementById("payment_history_line_chart_all").getContext("2d");
        var chart_line = new Chart(chart_line_canvas).Line(line_data, line_option);
    }
}

function initLineChart(data_x_labels, data_y_axis, line_canvas_id)
{
    var line_data = {
        labels : data_x_labels,
        datasets: [
            {
                fillColor: "rgba(151,187,205,0.2)",
                strokeColor: "rgba(151,187,205,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: data_y_axis
            }
        ]
    };
    var line_option = {
        responsive: true
    };
    var chart_line_canvas = document.getElementById(line_canvas_id).getContext("2d");
    var chart_line = new Chart(chart_line_canvas).Line(line_data, line_option);
}

function initBarChart(data_x_labels, data_y_axis, bar_canvas_id)
{
    var bar_data = {
        labels : data_x_labels,
        datasets: [
            {
                fillColor: "rgba(151,187,205,0.2)",
                strokeColor: "rgba(151,187,205,1)",
                pointColor: "rgba(151,187,205,1)",
                pointStrokeColor: "#fff",
                pointHighlightFill: "#fff",
                pointHighlightStroke: "rgba(220,220,220,1)",
                data: data_y_axis
            }
        ]
    };
    var bar_option = {
        responsive: true
    };
    var chart_bar_canvas = document.getElementById(bar_canvas_id).getContext("2d");
    var chart_bar = new Chart(chart_bar_canvas).Bar(bar_data, bar_option);
}