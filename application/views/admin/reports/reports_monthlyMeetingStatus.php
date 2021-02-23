<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Home</a></li>
        <li class="breadcrumb-item"><?php echo lang('reports'); ?></li>
        <li class="breadcrumb-item active"><?php echo $page_title; ?></li>
    </ol>
    <div class="container-fluid">

        <div class="row">

            <div class="col-12 animated fadeIn">
                <div class="card">
                    <div class="card-header">
                        Status-wise Monthly Meetings
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <div class="chart-wrapper">
                                    <canvas id="monthwise" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="">
                    <div class="card">
                        <div class="card-header">Meetings Weekly Status
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3 col-sm-12">
                                    <label>Select a Month</label>
                                    <div class="form-group">
                                        <select class="form-control form-control-sm" id="curmonth" onchange="weekdate(this.value)">
                                        <option value="">Select</option>
                                        <?php for($m=1; $m < 13; $m++){ ?>
                                                <option value="<?php echo $m; ?>"><?php echo date('F',strtotime(date('Y-'.$m.'-01'))); ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-12">
                                    <label>Select A Week</label>
                                    <select class="form-control form-control-sm"  id="weeks">
                                        
                                    </select>
                                </div>
                                <div class="col-md-3 mt-4 d-inline">
                                    <button type="button" class="btn btn-sm btn-primary" id="wklyreprtsrch">Search</button>
                                    <button type="button" class="btn btn-sm btn-secondary d-none" id="exportweeklyreport">Export</button>
                                    <button type="button" class="btn btn-sm btn-secondary d-none" onclick="printCanvas('weeklyMeetingsReport')" id="printcanvas">Print</button>
                                </div>
                            </div>
                            <div class="chart-wrapper" id="weeklychart" >
                                <canvas id="weeklyMeetingsReport" style="max-height: 400px;"></canvas>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>


        

    </div>
    </div>
</main>




<script src="<?php echo template_assets(); ?>vendors/chartjs/js/Chart.min.js"></script>
<script src="<?php echo template_assets(); ?>vendors/_coreui/coreui-plugin-chartjs-custom-tooltips/js/custom-tooltips.min.js"></script>
<script>
     Date.prototype.getWeek = function (dowOffset) {
        /*getWeek() was developed by Nick Baicoianu at MeanFreePath: http://www.meanfreepath.com */

            dowOffset = typeof(dowOffset) == 'int' ? dowOffset : 0; //default dowOffset to zero
            var newYear = new Date(this.getFullYear(),0,1);
            var day = newYear.getDay() - dowOffset; //the day of week the year begins on
            day = (day >= 0 ? day : day + 7);
            var daynum = Math.floor((this.getTime() - newYear.getTime() - 
            (this.getTimezoneOffset()-newYear.getTimezoneOffset())*60000)/86400000) + 1;
            var weeknum;
            //if the year starts before the middle of a week
            if(day < 4) {
                weeknum = Math.floor((daynum+day-1)/7) + 1;
                if(weeknum > 52) {
                    nYear = new Date(this.getFullYear() + 1,0,1);
                    nday = nYear.getDay() - dowOffset;
                    nday = nday >= 0 ? nday : nday + 7;
                    /*if the next year starts before the middle of
                    the week, it is week #1 of that year*/
                    weeknum = nday < 4 ? 1 : 53;
                }
            }
            else {
                weeknum = Math.floor((daynum+day-1)/7);
            }
            return weeknum;
        };
    function weekdate(month)
    {
        opttxt = '';
        var d = new Date();
        var n = d.getFullYear();
        var mydate = new Date(n+','+month+',1');
        // alert(mydate.getWeek())
        sw=mydate.getWeek();
        if(month < 12){
            ttlsw = new Date(n+','+(parseInt(month)+1)+',1').getWeek();
        }
        else{
            ttlsw = 53;
        }
        // alert(ttlsw);
        for(weektext=1,sw;sw < ttlsw || weektext <= 5;sw++,weektext++)
        {
            opttxt += '<option value="'+sw+'"> Week '+weektext+'</option>';
        }
        $('#weeks').html(opttxt);
       
    }

    $('#wklyreprtsrch').on('click',function(){
    /* Start the ajax function here to render the ajax data */
        get_weekly_chart();

    });
    $(document).ready(function() {

        var lineChart = new Chart($('#monthwise'), {
            type: 'line',
            data: {
                labels: <?php echo json_encode($monthly_meetings['labels']); ?>,
                datasets: [{
                    label: 'All Meetings',
                    backgroundColor: 'rgb(128, 236, 236,0.2)',
                    borderColor: 'rgb(128, 236, 236,1)',
                    pointBackgroundColor: 'rgb(128, 236, 236,1)',
                    pointBorderColor: '#fff',
                    data: <?php echo json_encode($monthly_meetings['all_meetings']); ?>
                }, {
                    label: 'Completed',
                    backgroundColor: 'rgba(35, 193, 0, 0.2)',
                    borderColor: 'rgba(35, 193, 0, 1)',
                    pointBackgroundColor: 'rgba(35, 193, 0, 1)',
                    pointBorderColor: '#fff',
                    data: <?php echo json_encode($monthly_meetings['completed']); ?>
                }, {
                    label: 'Cancelled',
                    backgroundColor: 'rgba(221, 48, 48, 0.2)',
                    borderColor: 'rgba(221, 48, 48, 1)',
                    pointBackgroundColor: 'rgba(221, 48, 48, 1)',
                    pointBorderColor: '#fff',
                    data: <?php echo json_encode($monthly_meetings['cancelled']); ?>
                }, {
                    label: 'Missed',
                    backgroundColor: 'rgba(255, 255, 100, 0.2)',
                    borderColor: 'rgba(255, 255, 100, 1)',
                    pointBackgroundColor: 'rgba(255, 255, 100, 1)',
                    pointBorderColor: '#fff',
                    data: <?php echo json_encode($monthly_meetings['missed']); ?>
                }, {
                    label: 'Upcoming',
                    backgroundColor: 'rgba(132, 132, 132, 0.2)',
                    borderColor: 'rgba(132, 132, 132, 1)',
                    pointBackgroundColor: 'rgba(132, 132, 132, 1)',
                    pointBorderColor: '#fff',
                    data: <?php echo json_encode($monthly_meetings['upcoming']); ?>
                }]
            },
            options: {
                responsive: true,
                legend:
                {
                    position: 'bottom',
                },title:
                {
                    display: true,
                    text: 'Status-wise Monthly Report'
                },
                scales:
                {
                    xAxes: [
                    {
                        display: true,
                        scaleLabel:
                        {
                            display: true,
                            labelString: 'Months'
                        },
                        gridLines:
                        {
                            display: true,
                            color: "#f2f2f2"
                        },
                        ticks:
                        {
                            beginAtZero: true,
                            fontSize: 12
                        }
                    }],
                    yAxes: [
                    {
                        display: true,
                        scaleLabel:
                        {
                            display: true,
                            labelString: 'Meetings Count'
                        },
                        gridLines:
                        {
                            display: true,
                            color: "#f2f2f2"
                        },
                        ticks:
                        {
                            beginAtZero: true,
                            fontSize: 12
                        }
                    }]
                }
                
            }
        });

        
        
    });

    function get_weekly_chart()
        {
            var month = $('#curmonth').val();
            var week = $('#weeks').val();
            $.post('<?php echo admin_url('reports/get_weely_date'); ?>',{weekno:week},function(response){
                data = JSON.parse(response)
                strallmeet = data.all_meeting;
                // alert();
                console.log(data.all_meeting)
                var repdata = [];
                repdata.push(data.all_meeting)
                repdata.push(data.completed)
                repdata.push(data.missed)
                repdata.push(data.cancelled);
                var labels = data.label;
                    
                renderWeeklychart(labels,repdata);
                // alert(response);
            });
        }

        function renderWeeklychart(label,data)
        {
            console.log(label);
            $('#exportweeklyreport,#printcanvas').removeClass('d-none');
            $('#weeklyMeetingsReport').remove();
            $('#weeklychart').append('<canvas id="weeklyMeetingsReport" height="400"></canvas>');
            var lineChart = new Chart($('#weeklyMeetingsReport'), {
            type: 'line',
            data: {
                labels: label,
                datasets: [{
                    label: 'All Meetings',
                    backgroundColor: 'rgb(0, 224, 234,0.2)',
                    borderColor: 'rgb(0, 224, 234,1)',
                    pointBackgroundColor: 'rgb(0, 224, 234,1)',
                    pointBorderColor: '#fff',
                    data: data[0]
                }, {
                    label: 'Completed',
                    backgroundColor: 'rgba(35, 193, 0, 0.2)',
                    borderColor: 'rgba(35, 193, 0, 1)',
                    pointBackgroundColor: 'rgba(35, 193, 0, 1)',
                    pointBorderColor: '#fff',
                    data: data[1]
                }, {
                    label: 'Cancelled',
                    backgroundColor: 'rgba(221, 48, 48, 0.2)',
                    borderColor: 'rgba(221, 48, 48, 1)',
                    pointBackgroundColor: 'rgba(221, 48, 48, 1)',
                    pointBorderColor: '#fff',
                    data: data[2]
                }, {
                    label: 'Missed',
                    backgroundColor: 'rgba(242, 242, 0, 0.2)',
                    borderColor: 'rgba(242, 242, 0, 1)',
                    pointBackgroundColor: 'rgba(242, 242, 0, 1)',
                    pointBorderColor: '#fff',
                    data: data[3]
                }]
            },
                options: {
                    responsive: true,
                    legend:
                    {
                        position: 'bottom',
                    },
                    scales:
                    {
                        xAxes: [
                        {
                            display: true,
                            scaleLabel:
                            {
                                display: true,
                                labelString: 'Weekly Dates'
                            },
                            gridLines:
                            {
                                display: true,
                                color: "#f2f2f2"
                            },
                            ticks:
                            {
                                beginAtZero: true,
                                fontSize: 11
                            }
                        }],
                        yAxes: [
                        {
                            display: true,
                            scaleLabel:
                            {
                                display: true,
                                labelString: 'Meetings Count'
                            },
                            gridLines:
                            {
                                display: true,
                                color: "#f2f2f2"
                            },
                            ticks:
                            {
                                beginAtZero: true,
                                fontSize: 11
                            }
                        }]
                    }
                }
            });
        }
    function selectedDate(e)
    {
        id = e.delegateTarget.attributes[1].value
        if(id == 'startdate')
        {
            $('#enddate').data("DateTimePicker").minDate(e.date);
        }
        if(id == 'enddate')
        {
            $('#startdate').data("DateTimePicker").maxDate(e.date);
        }
       
    }
    function printCanvas(canvasid)  
    {  
       
        const dataUrl = document.getElementById(canvasid).toDataURL(); 

        let windowContent = '<!DOCTYPE html>';
        windowContent += '<html>';
        windowContent += '<head><title>Print canvas</title></head>';
        windowContent += '<body>';
        windowContent += '<img src="' + dataUrl + '">';
        windowContent += '</body>';
        windowContent += '</html>';

        const printWin = window.open('', '', 'width=' + screen.availWidth + ',height=' + screen.availHeight);
        printWin.document.open();
        printWin.document.write(windowContent); 

        printWin.document.addEventListener('load', function() {
            printWin.focus();
            printWin.print();
            printWin.document.close();
            printWin.close();            
        }, true);
    }
</script>

<script src="<?php echo template_assets(); ?>js/filesaver.min.js"></script>
<script>
    $('#exportweeklyreport').on('click',function(){
        $('#weeklyMeetingsReport').get(0).toBlob(function(blob) {
                
                saveAs(blob,"Weeklymeetings.png");
            });
    })
</script>
