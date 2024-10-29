$(function() {
    loadOverallGraph(
        graph1.Pending,
        graph1.Completed
    );
    loadSchedulesGraph(
        graph2.Started,
        graph2.Pending,
        graph2.Archived,
        graph2.Draft
    );
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
                        completed,
                        notcompleted,
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
                    `Pending Review(s) : ${notcompleted}`,
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
    function loadSchedulesGraph(
        scheduled,
        pending,
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
                        pending,
                        archived,
                        draft,
                    ],
                    backgroundColor: [
                        '#1032c3',
                        '#FFA500',
                        '#cc1100',
                        '#444'
                    ],
                    borderWidth: 1
                }],
                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    `Started Review(s)`,
                    `Pending Review(s)`,
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
                                return `Pending Review(s): ${pending}`
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