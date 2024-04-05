<style>
    .d-flex{
        display: flex;
    }
    .align-center{
        align-items: center;
    }
    .justify-between{
        justify-content: space-between;
    }
    .title{
        font-size: 20px;
        font-weight: 700;
    }
    .total-hours span {
        color: #3f4648;
        font-size: 14px;
    }
    .lable-light-grey{
        background-color: #f5f5f5;
        border-radius: 10px;
        padding: 6px 10px;
    }
    .lable-light-blue{
        background-color:#628eb7;
        border-radius: 50px;
        padding: 10px 5px;
        font-size: 13px;
        font-weight: 500;
        margin: 5px 0;
    }
    .lable-light-green{
        background-color:#7c9ba2;
        border-radius: 50px;
        padding: 6px 10px;
        font-size: 13px;
        font-weight: 500;
    }
    .lable-light-white{
        background-color:#fff;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 500;
        color: #3f4548;
    }
    .justify-center{
        justify-content: center;
    
    }
    .clock{
        font-weight: 500;
        font-size: 20px;
    }
    .clock button{
        text-align: center;
        position: relative;
        width: 158px;
        height: 158px;
        padding: 43px 36px 42px;
        box-shadow: 0 10px 21px rgba(53,194,209,.25);
        background-image: linear-gradient(135deg,#2ba5f4 15%,#34d0d1 85%);
        border-radius: 50%;
        transition: transform .2s;
        cursor: pointer;
        user-select: none;
        border-width: 0;
        color: #fff;
    }
    .clock:hover{
        transform: scale(1.1);
        transition: transform .2s;
    }
    .clock i {
        font-size: 40px;
    }
    .manual-breaks{
        margin-top: 25px;
    }
    .manual-breaks button{
        line-height: normal;
        padding: 13px 27px;
        border-radius: 46px;
        border: 1px solid #d9d9d9;
        background: #fff;
        color: #663dff;
        font-weight: 400;
    }
    .manual-breaks button i {
        margin-right: 10px;
    }
    .manual-breaks button:hover{
        border-color: #663dff;
    }
    .time-sheet-button button{   
        line-height: normal;
        padding: 13px 27px;
        border-radius: 46px;
        border: 1px solid #d9d9d9;
        background: #fff;
        color: #2998ff;
        font-weight: 400;
    }
    .time-sheet-button button:hover{   
        background: #f6f7f7;
    }
    .border-down-bottom{
        padding-bottom: 15px;
        border-bottom: 1px solid #d9d9d9;
    }
    .request-buttons{
        padding-top: 10px;
        padding-bottom: 15px;
    }
    .request-btn{
        width: 100%;
        border-radius: 25px;
        box-shadow: 0 4px 14px rgba(0,0,0,.08);
        background-color: #fff;
        border: 0;
        margin-block: 5px;
        transition: transform .1s linear;
        min-height: 50px;
        text-align: left;
        font-weight: 400;
    }
    .request-btn:hover{
        cursor: pointer;
        transform: scale(1.05);
        transition: transform .1s linear;
    }
    .request-btn i{
        font-size: 20px;
        margin: 8px;
    }
    .user-requests-footer{
        padding: 15px;
        border-top: 1px solid #d9d9d9;
    }
    .user-requests-footer button{
        color: #2998ff;
        font-size: 15px;
        font-weight: 700;
    }
    .blue-light{
        background-color: #eaf5ff;
    }
    .custom-select {
        margin-left: 10px;
        min-width: 200px;
        border-radius: 20px;
        position: relative;
    }

    .custom-select select {
        appearance: none;
        -webkit-appearance: none;
        width: 100%;
        font-size: 1.15rem;
        padding: 0.675em 6em 0.675em 1em;
        background-color: #fff;
        border: 1px solid #dbdee0;
        border-radius: 10px;
        color: #000;
        cursor: pointer;
    }

    .custom-select::before,
    .custom-select::after {
        --size: 5px;
        content: "";
        position: absolute;
        right: 1rem;
        pointer-events: none;
    }

    .custom-select::before {
        border-left: var(--size) solid transparent;
        border-right: var(--size) solid transparent;
        /* border-bottom: var(--size) solid black; */
        top: 40%;
    }

    .custom-select::after {
        border-left: var(--size) solid transparent;
        border-right: var(--size) solid transparent;
        border-top: var(--size) solid black;
        top: 45%;
    }
    .custom-select select option{
        padding: 10px 0;
    } 
    .timesheet-section{
        padding: 0 20px;
    }
    .timesheet-section .heading-title{
       margin: 0 0 0 10px;
    }
    .punch-clock{
        padding: 0 10px;
    }
    .punch-clock p{
        font-weight: 400;
        font-size: 12px;
        line-height: 16px;
        color: #8b939c;
    }
    .punch-clock b{
        color: #8b939c;
        font-size: 12px;
    }
    .punch-clock .equal,
    .punch-clock .plus{
        color: #8b939c;
        font-size: 16px;
        font-weight: 800;
    }
    .pt-2{
        padding-top: 20px;
    }
    .user-timesheet-table thead tr th{
        background: #f8f8f8;
        font-size: 14px;
        font-weight: 400;
        padding: 15px;
    }
    .user-timesheet-table tbody tr td{
        font-size: 14px;
        font-weight: 400;
        vertical-align: middle;
    }
    .bg-grey{
        background-color: #f0f1f2;
        padding: 10px !important; 
    }
    .w-100{
        width: 100%;
    }
    .flex-colum{
        flex-direction: column;
    }
    .inner-padding span{
        padding: 10px 0 ;
    }
    .mt-2{
        margin-top: 20px;
    }
    .ml-2{
        margin-left: 20px;
    }
    .pl-2{
        padding-left: 20px;
    }
    .user-timesheet-table tbody tr td.text-12{
        font-size: 12px;
        font-weight: 800;
        color: #000;
    }
    .current-punch-clock{
        background-image: linear-gradient(25deg,#2998ff,#41d6ff);
        color: #fff;
        border-radius: 15px;

    }
    .countdown span{
        font-size: 60px;
        font-weight: 300;
    }
    .current-punch-clock p{
        margin-bottom: 0;
    }
    .text-center{
        text-align: center;
    }
    .online-dot{
        position: relative;
    }
    .online-dot::before{
        content: "";
        position: absolute;
        top: 50%;
        left: 10px;
        width: 8px;
        height: 8px;
        background-color: #6d94b9;
        border-radius: 50px;
        transform: translate(-50%, -50%);
    }
    .punch-address{
        border-top: 1px solid ;
        opacity: .6;
        padding-top: 10px;
    }
    .punch-address p{
        white-space: nowrap; 
        width: 100%; 
        overflow: hidden;
        text-overflow: ellipsis; 
    }
    .punch-time-sec{
        background-color: #2ea3ffbf;
    }
    .punch-hours-time{
        padding-inline: 15px 20px;
        padding-block: 10px;
        border-radius: 10px;
    }
    .punch-hours-time p,
    .punch-hours-time span{
        color: #fff;
        font-size: 14px;
        font-weight: 400;
        margin-bottom: 0;
    }
    .punch-time-sec.panel{
        border-radius: 15px;
    }
    .border-right{
        border-right: 1px solid #d9d9d9;
    }
    .stop-time-button{
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        width: 100%;
        height: 50px;
        font-size: 16px;
        border-radius: 100px;
        border: 0;
        box-shadow: 0 5px 7px rgba(0,0,0,.05);
        background-image: linear-gradient(90deg,#ff6361,#f88d71);
        transition: transform .2s linear,opacity .2s linear;
        font-size: 18px;
        font-weight: 400;
    }
    .stop-time-button p{
        margin-bottom: 0;
        margin-right: 10px;
    }
    .logs-section .nav-tabs>li.active>a, 
    .logs-section .nav-tabs>li.active>a:hover, 
    .logs-section  .nav-tabs>li.active>a:focus{
        background-color: transparent !important;
        color: #2998ff !important;
        border-bottom: 4px solid #2998ff;
        border-top: none;
        border-left: none;
        border-right: none;
    }
    .logs-section .nav-tabs li a{
        color: #3f4548;
        font-weight: 400;
    
    }
    .notes-field{
        margin-top: 30px;
    }
    .notes-field p{
        margin-bottom: 5px;
        font-size: 14px;
        font-weight: 400;
    }
    .notes-field p span i{
        color: #2998ff;
        font-size: 16px;
    }
    .logs-times{
        padding-top: 40px;
    }
    .logs-times .time-container{
        color: #3f4548;
        font-size: 15px;
        font-weight: 400;
    }
    .logs-times .tag{
        display: inline-block;
        width: 120px;
        margin-right: 90px;
    }
    .dot-borders{
        width: 8px;
        height: 8px;
        background-color: #673dff;
        border-radius: 50px;
        margin-right: 10px;
        position: relative;
    }
    .before-circle:not(:last-child) .dot-borders::before{
        content: "";
        position: absolute;
        border-right: 2px dashed #d8d8d8;
        top: 13px;
        height: 19px;
        left: 50%;
        transform: translateX(-50%);
    }
    .relative{
        position: relative;
    }
    .sidebar{
        position: absolute;
        top: 0;
        bottom: 0;
        background: #fff;
        right: 0;
        width: 300px;
        display: flex;
        flex-direction: column;
        border-top-left-radius: 10px;
        border-bottom-left-radius: 10px;
        animation: sidebar-open-animation .3s linear;
        border: 1px solid #000;
        transform: translateX(300px);
        transition: all 1s ease-in-out;
        z-index: 10;
    }
    .sidebar p{
        margin-bottom: 0;
    }
    .sidebar-header{
        height: 60px;
        gap: 10px;
        padding: 0 20px;
    }
    .sidebar-header button{
        background: none;
        font-weight: 300;
        border: none;
        color: #3f4648;
        font-size: 20px;
    }
    .sidebar-header p{
        font-size: 15px;
        color: #3f4548;
        font-weight: 400;
    }
    .search-button{
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 20px;
        padding-inline: 12px;
        border: 1px solid #d9d9d9;
        cursor: pointer;
        margin-right: 20px;
    }
    .sidebar-body p{
        padding: 10px 20px;
        margin-bottom: 0;
        font-size: 15px;
        font-weight: 400;
        border-top: 1px solid #d8d8d8;
        border-bottom: 1px solid #d8d8d8;
        background-color: #f6f8f9;
    }
    .sidebar-body ul{
        list-style: none;
    }
    .sidebar-body ul li{
        font-size: 15px;
        font-weight: 400;
        border-bottom: 1px solid #d8d8d8;
        padding: 10px 20px;
    }
    .big-dot{
        min-width: 27px;
        width: 27px;
        height: 27px;
        opacity: .5;
        border-radius: 50%;
        display: inline-flex;
        margin-right: 10px;
    }
    .big-dot.blue{
        background-color: #81a8cc;
    }
    .big-dot.grey{
        background-color: #71adbb;
    }
    .time{
        overflow-x: hidden;
    }
    .overlay{
        position: absolute;
        top: 0px;
        left: 0px;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,.5);
        z-index: 8;
        border-radius: 3px;
        display: none;
    }
</style>

<section class="clock-section">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-8 col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="panel panel-default mt-2">
                    <div class="panel-body relative time">
                        <div class="overlay" id="overlay" onclick="popupClose()"></div>
                        <div class="d-flex justify-between align-center">
                            <div>
                                <h3 class="title">Today's clock</h3>
                            </div>
                            <div class="total-hours">
                                <span class="label lable-light-grey">Total work hours today <b>00:02</b></span>
                            </div>
                        </div>
                        <div class="clock text-center">
                            <button onclick="popupOpen()">
                                <p>
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                </p>    
                                Clock in
                            </button>
                        </div>
                        <div class="text-center manual-breaks">
                            <button>
                                <span>
                                    <i class="fa fa-coffee" aria-hidden="true"></i>
                                </span>    
                                Start Break
                            </button>
                        </div>
                        <div class="sidebar" id="sidebar">
                            <div class="d-flex justify-between align-center">
                                <div class="d-flex align-center sidebar-header">
                                    <button onclick="popupClose()"><i class="fa fa-times" aria-hidden="true"></i></button>
                                    <p>Select job</p>
                                </div>
                                <div>
                                <span class="search-button"><i class="fa fa-search" aria-hidden="true"></i></span> 
                                </div>
                            </div>
                            <div class="sidebar-body">
                                <p>Other Jobs</p>
                                <ul>
                                    <li class="d-flex align-center"><span class="big-dot blue"></span>Customer 1</li>
                                    <li class="d-flex align-center"><span class="big-dot grey"></span>Work Site A</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- <div class="panel panel-default mt-2">
                    <div class="panel-body">
                      <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12 border-right">
                            <h3 class="title">Today's clock</h3>
                            <div class="panel mt-2 punch-time-sec">
                                <div class="panel-body current-punch-clock">
                                    <div class="d-flex align-center justify-center">
                                        <div>
                                            <p>Work time on</p>
                                        </div>
                                        <div class="ml-2">
                                            <span class="label lable-light-white pl-2 online-dot"> Customer 1</span>
                                        </div>
                                    </div> 
                                    <div class="d-flex align-center justify-center countdown">
                                        <span> 00:10:01</span>
                                    </div>
                                    <div class="d-flex align-center justify-center punch-address">
                                        <p><span><i class="fa fa-map-marker" aria-hidden="true"></i></span> 642 Khayaban-e-Jinnah Road, near Ucp, Airline Society, Lahore, Punjab, Pakistan</p>
                                    </div>
                                </div>
                                    <div class="punch-hours-time">
                                        <div class="d-flex align-center justify-between">
                                            <div>
                                                <p>Total work hours for Fri, Apr 5</p>
                                            </div>
                                            <div>
                                                <span> 00:10:01</span>
                                            </div>
                                        </div> 
                                    </div>
                            </div>
                            <button class="d-flex stop-time-button">
                                <p>
                                    <i class="fa fa-clock-o" aria-hidden="true"></i>
                                </p>    
                                End
                            </button>
                        </div>
                        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12">
                            <div class="logs-section">    
                                <ul class="nav nav-tabs">
                                    <li role="presentation" class="active">
                                        <a href="#one" aria-controls="one" role="tab" data-toggle="tab">Attachments</a>
                                    </li>
                                    <li role="presentation">
                                        <a href="#two" aria-controls="two" role="tab" data-toggle="tab">My day log</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div role="tabpanel" class="tab-pane fade in active notes-field" id="one">
                                        <p> <span><i class="fa fa-pencil-square-o" aria-hidden="true"></i></span> Note</p>
                                        <input type="text" class="form-control" placeholder="Blank" aria-describedby="basic-addon1">
                                    </div>
                                    <div role="tabpanel" class="tab-pane fade" id="two">
                                        <div class="d-flex flex-colum inner-padding logs-times">
                                            <div class="d-flex align-center before-circle">
                                                <div class="dot-borders"></div>
                                                <div class="d-flex align-center">
                                                    <div>
                                                        <span class="label lable-light-blue tag">Customer 1</span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="label time-container">15:10 <span>- active</span> </p>
                                                </div>
                                            </div>   
                                            <div class="d-flex align-center before-circle">
                                                <div class="d-flex align-center">
                                                    <div class="dot-borders"></div>
                                                    <div class="d-flex align-center">
                                                        <div>
                                                            <span class="label lable-light-blue tag">Customer 1</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="label time-container">15:10 <span>- active</span> </p>
                                                    </div>
                                                </div> 
                                                <div>
                                                    <p class="label time-container">15:10 <span>- total 13:00</span> </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                </div> -->
            </div>
            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
                <div class="panel panel-default mt-2">
                    <div class="panel-body user-requests">
                        <h3 class="title border-down-bottom">Requests</h3>
                        <div class="request-buttons">
                            <button class="request-btn d-flex align-center">
                                <span>
                                    <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                </span>    
                                Add a shift request
                            </button>
                            <button class="request-btn d-flex align-center">
                                <span>
                                    <i class="fa fa-coffee" aria-hidden="true"></i>
                                </span>    
                                Add a break request
                            </button>
                            <button class="request-btn d-flex align-center">
                                <span>
                                    <i class="fa fa-cog" aria-hidden="true"></i>
                                </span>    
                                Add an absence request
                            </button>
                        </div>
                        <div class="user-requests-footer">
                            <button class="request-btn text-center blue-light"> 
                                View your requests
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default timesheet-section">
                    <div class="d-flex justify-between align-center border-down-bottom pt-2">
                        <div class="d-flex align-center">
                            <h3 class="heading-title">Timesheet</h3>
                            <div class="custom-select">
                                <select>
                                    <option value="">14/03/24 - 27/03/24</option>
                                    <option value="">14/03/24 - 27/03/24</option>
                                    <option value="">14/03/24 - 27/03/24</option>
                                    <option value="">14/03/24 - 27/03/24</option>
                                    <option value="">14/03/24 - 27/03/24</option>
                                    <option value="">14/03/24 - 27/03/24</option>
                                    <option value="">14/03/24 - 27/03/24</option>
                                </select>
                            </div>
                        </div>
                        <div class="text-center time-sheet-button">
                            <button>
                                Select empty days
                            </button>
                            <button>
                                Export
                            </button>
                        </div>
                    </div>
                    <div class="d-flex justify-between align-center pt-2">
                        <div class="d-flex align-center punch-clock-table">
                            <div class="punch-clock">
                                <b>00:03</b>
                                <p>Regular</p>
                            </div> 
                            <div class="punch-clock">
                                <span class="plus">+</span>
                            </div>       
                            <div class="punch-clock">
                                <b>00:03</b>
                                <p>Overtime</p>
                            </div>       
                            <div class="punch-clock">
                                <span class="plus">+</span>
                            </div>   
                            <div class="punch-clock">
                                <b>00:03</b>
                                <p>Paid time off</p>
                            </div>     
                            <div class="punch-clock">
                                <span class="equal">=</span>
                            </div>   
                            <div class="punch-clock">
                                <b>00:03</b>
                                <p>Total Paid Hours</p>
                            </div>  
                        </div>
                        <div class="d-flex align-center punch-clock-table">
                            <div class="punch-clock">
                                <b>00:03</b>
                                <p>Regular</p>
                            </div>    
                            <div class="punch-clock">
                                <b>00:03</b>
                                <p>Overtime</p>
                            </div>      
                            <div class="punch-clock">
                                <b>00:03</b>
                                <p>Overtime</p>
                            </div> 
                        </div>
                    </div>
                    <div class="panel-body table-responsive">
                        <table class="table table-hover user-timesheet-table">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" aria-label="..."></th>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Sub job</th>
                                    <th>Start</th>
                                    <th>End</th>
                                    <th>Total hours</th>
                                    <th>Daily total</th>
                                    <th>Overtime</th>
                                    <th>Weekly total</th>
                                    <th>Total regular</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr> 
                                    <td class="bg-grey text-center text-12" colspan="11">Apr 08 - Apr 10</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="..."></td>
                                    <td>Wed 10/4</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="..."></td>
                                    <td>Wed 10/4</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="..."></td>
                                    <td>Wed 10/4</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                <tr> 
                                    <td class="bg-grey text-center text-12" colspan="11">Apr 08 - Apr 10</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="..."></td>
                                    <td>Wed 10/4</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="..."></td>
                                    <td>Wed 10/4</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="..."></td>
                                    <td>Wed 10/4</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                    <td>--</td>
                                </tr>
                                <tr>
                                    <td><input type="checkbox" aria-label="..."></td>
                                    <td>thu 4/4</td>
                                    <td>
                                        <div class="d-flex flex-colum inner-padding">
                                            <span class="label lable-light-blue">Customer 1</span>
                                            <span class="label lable-light-green">Work site A</span>
                                            <span class="label lable-light-blue">Customer 1</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-colum inner-padding">
                                            <span>--</span>
                                            <span>--</span>
                                            <span>--</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-colum inner-padding">
                                            <span >15:23</span>
                                            <span>15:24</span>
                                            <span> 15:47</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-colum inner-padding">
                                            <span>15:23</span>
                                            <span>15:24</span>
                                            <span> 15:47</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-colum inner-padding">
                                            <span>15:23</span>
                                            <span>15:24</span>
                                            <span> 15:47</span>
                                        </div>
                                    </td>
                                    <td>
                                       <b>00:03</b>
                                    </td>
                                    <td>--</td>
                                    <td>
                                       <b>00:03</b>
                                    </td>
                                    <td>
                                       <b>00:03</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    const overlay = document.getElementById("overlay").style
    const popup = document.getElementById('sidebar').style
    function popupOpen() {
        popup.transform = "translateX(0px)"
        overlay.display = "flex"
    }
    function popupClose() {
        popup.transform = "translateX(350px)"
        overlay.display = "none"
    }
</script>