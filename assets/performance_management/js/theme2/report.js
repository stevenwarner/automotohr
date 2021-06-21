$(function() {
    loadOverallGraph(10, 20);
    loadExpiringGraph(2, 50);
    loadSchedulesGraph(20, 5, 10, 2);
    //
    function loadOverallGraph(
        notcompleted,
        completed
    ) {
        new Chart(document.getElementById('jsTimeoffPieGraph'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    label: '# of hours',
                    data: [
                        notcompleted,
                        completed
                    ],
                    backgroundColor: [
                        '#1032c3',
                        '#fd7a2a',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderColor: [
                        '#1032c3',
                        '#fd7a2a',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }],
                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    `Completed Review(s): ${completed}`,
                    `Not Completed Review(s) : ${notcompleted}`
                ],
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            if (tooltipItem.index == 0) return `Completed Review(s): ${completed}`;
                            return `Not Completed Review(s): ${notcompleted}`;
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                showAllTooltips: true,
                onClick: function(e) {}
            },
        });
    }

    //
    function loadExpiringGraph(
        past,
        within
    ) {
        new Chart(document.getElementById('jsTimeoffPieGraph1'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    data: [
                        past,
                        within,
                    ],
                    backgroundColor: [
                        '#fd7a2a',
                        '#1032c3',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderColor: [
                        '#fd7a2a',
                        '#1032c3',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }],
                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    `Past Due`,
                    `Due within a week`,
                ],
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            if (tooltipItem.index == 0) return `Past Due: ${past}`;
                            return `Due within a week: ${within}`;
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                showAllTooltips: true
            },
        });
    }

    //
    function loadSchedulesGraph(
        scheduled,
        active,
        archived,
        draft
    ) {
        new Chart(document.getElementById('jsTimeoffPieGraph2'), {
            type: 'bar',
            data: {
                datasets: [{
                    label: '',
                    data: [
                        scheduled,
                        active,
                        archived,
                        draft,
                    ],
                    backgroundColor: [
                        '#1032c3',
                        '#fd7a2a',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }],
                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    `Scheduled Review(s)`,
                    `Active Review(s)`,
                    `Arhived Review(s)`,
                    `Review(s) In Draft`
                ],
            },
            options: {
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true
                    }
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipindex) {
                            if (tooltipindex.index == 0) {
                                return `Scheduled Review(s): ${scheduled}`
                            }
                            if (tooltipindex.index == 1) {
                                return `Active Review(s): ${active}`
                            }
                            if (tooltipindex.index == 2) {
                                return `Archived Review(s): ${archived}`
                            }
                            return `Draft Review(s): ${draft}`;
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                showAllTooltips: true
            },
        });
    }

    //
    $('#jsRangeDate').daterangepicker({
        opens: 'left',
        showDropdowns: true,
        minYear: 2021,
        maxYear: parseInt(moment().format('YYYY'))
    }, function() {
        coneole.log('AJAX CALL');
    });


    makeContainer(
        'jsGraph3',
        '', [{
                data: [{
                    name: 'Disagree',
                    y: 20,
                    color: '#fd7a2a'
                }]
            },
            {
                data: [{
                    name: 'Neutral',
                    y: 20,
                    color: '#0000ff'
                }]
            },
            {
                data: [{
                    name: 'Agree',
                    y: 60,
                    color: '#81b431'
                }]
            },
        ], {
            xAxis: {
                labels: {
                    enabled: false
                }
            }
        }
    );


    makeContainer(
        'jsGraph4',
        '', [{
                data: [{
                    name: 'Disagree',
                    y: 0,
                    color: '#fd7a2a'
                }]
            },
            {
                data: [{
                    name: 'Neutral',
                    y: 0,
                    color: '#0000ff'
                }]
            },
            {
                data: [{
                    name: 'Agree',
                    y: 0,
                    color: '#81b431'
                }]
            },
        ], {
            xAxis: {
                labels: {
                    enabled: false
                }
            }
        }
    );

    makeContainer(
        'jsGraph5',
        '', [{
                data: [{
                    name: 'Disagree',
                    y: 80,
                    color: '#fd7a2a'
                }]
            },
            {
                data: [{
                    name: 'Neutral',
                    y: 10,
                    color: '#0000ff'
                }]
            },
            {
                data: [{
                    name: 'Agree',
                    y: 10,
                    color: '#81b431'
                }]
            },
        ], {
            xAxis: {
                labels: {
                    enabled: false
                }
            }
        }
    );

    //
    function makeContainer(
        target,
        categories,
        data,
        additionalOptions
    ) {
        //
        var options = {
            chart: {
                height: 200,
                backgroundColor: '#f1f1f1',
                type: 'bar'
            },
            title: {
                text: ''
            },
            tooltip: {
                formatter: function() {
                    return this.key + ' ' + this.y + '%';
                }
            },
            xAxis: {
                categories: categories
            },
            yAxis: {
                min: 0,
                tickInterval: 25,
                max: 100
            },
            legend: false,
            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },
            series: data
        };
        //
        if (additionalOptions !== undefined) {
            options = Object.assign(options, additionalOptions);
        }
        Highcharts.chart(target, options);
    }
});