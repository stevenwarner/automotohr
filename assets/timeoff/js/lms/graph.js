$(function() {
    //
    let graphData = {};
    //
    getEmployeeGraphBalance();
    //
    $('.jsTeamShiftTab').change(function() {
        //
        let isMine = $('.jsTeamShiftTab:checked').val() == 'on' ? 1 : 0;
        //
        if (isMine == 1) {
            //
            if (graphData.Balance === undefined) getEmployeeGraphBalance();
            $('.jsGraphBox').show();
        } else {
            $('.jsGraphBox').hide();
        }
    });
    //
    function getEmployeeGraphBalance() {
        $.post(
            handlerURL, {
                action: 'get_employee_balances',
                companyId: companyId,
                employerId: employerId,
                employeeId: employeeId,
                public: 0
            },
            (resp) => {
                // Generate timeoff graph
                loadTimeLineGraph(
                    resp.Data.Timeoffs
                );
                loadHourGraph(
                    resp.Data.Balance.Remaining.H.hours,
                    resp.Data.Balance.Consumed.H.hours,
                    resp.Data.Balance.Remaining.text,
                    resp.Data.Balance.Consumed.text
                );

                //
                ml(false, 'graph');
            }
        );
    }

    //
    function loadTimeLineGraph(ob) {
        new Chart(document.getElementById('jsTimeoffLineGraph'), {
            type: 'bar',
            data: {
                datasets: [{
                    label: `Time-offs for ${moment().format('YYYY')}`,
                    barPercentage: 1,
                    barThickness: 6,
                    minBarThickness: 80,
                    minBarLength: 0,
                    data: [
                        ob[1][0],
                        ob[2][0],
                        ob[3][0],
                        ob[4][0],
                        ob[5][0],
                        ob[6][0],
                        ob[7][0],
                        ob[8][0],
                        ob[9][0],
                        ob[10][0],
                        ob[11][0],
                        ob[12][0]
                    ],
                    backgroundColor: [
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                    ],
                    borderColor: [
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                        '#fd7a2a',
                    ],
                    borderWidth: 1
                }],
                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December',
                ],
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            //
                            let _hours = ob[tooltipItem.index + 1][1] == 0 ? 0 : ob[tooltipItem.index + 1][1].H.hours;
                            let _timeoffs = ob[tooltipItem.index + 1][0];
                            let returnText = '';
                            //
                            returnText += `${_timeoffs} time-off${_timeoffs > 1 || _timeoffs == 0 ? 's' : ''} taken for ${moment().format('YYYY')} of ${_hours} hour${_hours > 1 || _hours == 0 ? 's' : ''}`;
                            //
                            return returnText;
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    xAxes: [{
                        barPercentage: 1,
                        stacked: true,
                    }],
                    yAxes: [{
                        ticks: {
                            display: false,
                        },
                        barPercentage: 3,
                        stacked: true,
                    }]
                }
            },
        });

    }

    //
    function loadHourGraph(p, c, pt, ct) {
        new Chart(document.getElementById('jsTimeoffPieGraph'), {
            type: 'doughnut',
            data: {
                datasets: [{
                    label: '# of hours',
                    data: [
                        p,
                        c
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
                    `Remaining Hours ${pt}`,
                    `Scheduled Hours ${ct}`
                ],
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            if (tooltipItem.index == 0) return `Remaining Time: ${pt}`;
                            return `Scheduled Time: ${ct}`;
                        }
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
                showAllTooltips: true
            },
        });
    }
});