<div class="container-fluid">
    
    <!--  -->
    <div class="row">
        <!-- Content Area -->
        <div class="col-sm-12 col-xs-12">
            <!-- Content Area -->
            <div class="csPageBox csRadius5">
                <!-- Body  -->
                <div class="csPageBoxBody p10">
                    <!-- Data -->
                    <div class="csPageBodyData">
                        <!-- Loader -->
                        <div>
                           <canvas id="jsTimeoffPieGraph" height="500"></canvas>
                        </div>
                    </div>
                    
                    <!--  -->
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

$(document).ready(function(){

    loadHourGraph();
    function loadHourGraph() {
        new Chart(document.getElementById('jsTimeoffPieGraph'), {
            type: 'bar',
            data: {
                datasets: [{
                    data: [
                        '5',
                        '10',
                        '2',
                        7,
                        50
                    ],
                    backgroundColor: [
                        '#81b431',
                        '#81b435',
                        '#000',
                        '#cc1100',
                        '#cc1111'
                    ],
                    borderColor: [
                        '#81b431',
                        '#81b435',
                        '#000',
                        '#cc1100',
                        '#cc1111'
                    ],
                    borderWidth: 1
                }],
                // These labels appear in the legend and in the tooltips when hovering different arcs
                labels: [
                    `Strongly Agree`,
                    `Agree`,
                    `Neutral`,
                    `Disagree`,
                    `Strongly Disagree`
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                showAllTooltips: true
            },
        });
    }

});
</script>