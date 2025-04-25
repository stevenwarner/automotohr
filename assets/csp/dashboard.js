$(function () {
    Highcharts.chart('jsProgressGraph', {
        chart: {
            type: 'pie'
        },
        title: {
            text: 'Compliance Progress',
            style: {
                fontSize: '14px' // Increased title font size
            }
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        accessibility: {
            point: {
                valueSuffix: '%'
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        fontSize: '14px' // Increased font size
                    }
                }
            }
        },
        legend: {
            itemStyle: {
                fontSize: '14px' // Increased legend font size
            }
        },
        series: [{
            name: 'Tasks',
            colorByPoint: true,
            data: JSON.parse(progressGraphData).map((item, index) => ({
                ...item,
                color: JSON.parse(progressGraphColors)[index] // Assign colors from progressGraphColors
            })),
        }]
    });


    Highcharts.chart('jsSeverityGraph', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Compliance Progress by Severity Levels',
            style: {
                fontSize: '14px' // Adjusted title font size
            }
        },
        xAxis: {
            categories: JSON.parse(severityLevelGraph).categories, // Reverse categories to show high to low
            title: {
                text: 'Severity Level',
                style: {
                    fontSize: '14px' // Adjusted x-axis title font size
                }
            },
            labels: {
                style: {
                    fontSize: '14px' // Adjusted x-axis label font size
                }
            }
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Number of Issues',
                style: {
                    fontSize: '14px' // Adjusted y-axis title font size
                }
            },
            labels: {
                style: {
                    fontSize: '14px' // Adjusted y-axis label font size
                }
            }
        },
        tooltip: {
            pointFormat: '<b>{point.y}</b> issues at Severity Level {point.category}'
        },
        plotOptions: {
            column: {
                colorByPoint: true,
                dataLabels: {
                    enabled: true,
                    format: '{point.y}',
                    style: {
                        fontSize: '14px' // Adjusted label font size
                    }
                }
            }
        },
        legend: {
            itemStyle: {
                fontSize: '14px' // Adjusted legend font size
            }
        },
        series: [{
            name: 'Issues',
            data: JSON.parse(severityLevelGraph).data, //  data to match high to low order
            colors: JSON.parse(severityLevelGraph).colors // Reverse colors to match high to low order
        }]
    });
});