<script src="https://rawgit.com/moment/moment/2.2.1/min/moment.min.js"></script>


<div class="csPageMain">
    <div class="container-fluid">
        <div class="csPageWrap ">
            <br>

            <table id="calandertbl">
                <thead>
                    <tr>

                    </tr>
                </thead>
                <tbody class="class=" fc-body">

                    <tr>

                    </tr>
                </tbody>

            </table>

        </div>
    </div>
</div>

<script>
    drawcalendar("week")

    function drawcalendar(
        mode = "week",
        fromdate = '',
        todate = '',
    ) {


        switch (mode) {
            case 'day':
                // code block
                break;
            case 'week':
                // code block
                drawCalander(7, 2);
                break;
            default:
                // code block
        }

    }

    function drawCalander(cols, rows) {
        //   const moment = require('moment');

        var now = moment().format('MMM DD h:mm A');
        //
        var startOfWeek = moment().startOf('isoWeek');
        var endOfWeek = moment().endOf('isoWeek');

        var days = [];
        var day = startOfWeek;

        while (day <= endOfWeek) {
            days.push(day.toDate());
            day = day.clone().add(1, 'd');
        }
 

        var tblheader = '';
        for (let i = 0; i < cols; i++) {
            tblheader += '<th class="fc-day-header fc-widget-header fc-sun">' +  moment(days[i]).format('ddd')+'<br>' + moment(days[i]).format('D')+ '</th>';
        }

        var tblrow = '';
        for (let i = 0; i < rows; i++) {

            tblrow += '<tr style="height: 179px;">';
            for (let i = 0; i < cols; i++) {
                tblrow += '<td class="fc-day fc-widget-content fc-sun fc-other-month fc-past" data-date="2023-08-06" style="width: 179px;">' + 'mon' + '</td>'
            }
            tblrow += '</tr>';
        }

        $("#calandertbl>thead>tr").append(tblheader);
        $("#calandertbl>tbody").append(tblrow);

    }
</script>