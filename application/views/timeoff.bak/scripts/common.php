    var timeoffDateFormat = 'MMM DD YYYY, ddd';
    // Time Converters
    // Convert to DHM from minutes
    function formatMinutes(pto_format, default_slot, Minutes, report, returnArray){
        report = report === undefined ? false : report;
        var finalResult = '';
        if(Minutes == 0 && !report){
           finalResult = 'Infinite';
        }else{
           var D = 0;
           var H = 0;
           var M = 0;
           var rt = { D: 0, H: 0, M: 0 };
           if(pto_format == 'D:H:M'){
               D = parseInt((Minutes)/(default_slot * 60));
               H = parseInt(((Minutes)%(default_slot * 60))/60);
               M = parseInt(((Minutes)%(default_slot * 60))%60);
               rt = { D: D, H: H, M: M };
               finalResult = D + ' Day(s) ' + H + ' Hour(s) ' + M + ' Minute(s)';
           }else if(pto_format == 'H:M'){
               H = parseInt((Minutes)/60);
               M = parseInt((Minutes)%60);
               rt = { H: H, M: M };
               finalResult = H + ' Hour(s) ' + M + ' Minute(s)';
           }else if(pto_format == 'D'){
               D = ((Minutes)/(default_slot * 60)).toFixed(2);
               rt = { D: D };
               finalResult = D + ' Day(s) ';
           }else if(pto_format == 'M'){
               M = Minutes;
               rt = { M: M };
               finalResult =  M + ' Minute(s)';
           }else if(pto_format == 'H'){
               H = ((Minutes)/60).toFixed(2);
               rt = { H: H };
               finalResult = H + ' Hour(s) ';
           }
       }

       if(returnArray !== undefined) return rt;
       return finalResult;
    }
    // Convert DHM to minutes
    function loadPageTime(record, day, hour, minute){
        var D = 0, H = 0, M = 0;
        if(record.format == 'D:H:M'){
            D = parseInt((record.allowed_timeoff)/(record.default_timeslot * 60));
            H = parseInt(((record.allowed_timeoff)%(record.default_timeslot * 60))/60);
            M = parseInt(((record.allowed_timeoff)%(record.default_timeslot * 60))%60);
            $(day).val(D);
            $(hour).val(H);
            $(minute).val(M);
        }else if(record.format == 'H:M'){
            H = parseInt((record.allowed_timeoff)/60);
            M = parseInt((record.allowed_timeoff)%60);
            $(hour).val(H);
            $(minute).val(M);
        }else if(record.format == 'D'){
            D = ((record.allowed_timeoff)/(record.default_timeslot * 60));
            $(day).val(D.toFixed(2));
        }else if(record.format == 'M'){
            M = record.allowed_timeoff;
            $(minute).val(M);
        }else if(record.format == 'H'){
            H = ((record.allowed_timeoff)/60);
            $(hour).val(H.toFixed(2));
        }
    }
    // Loader
    function loader(show_it){
        show_it = show_it === undefined || show_it == true || show_it == 'show' ? 'show' : 'hide';
        if(show_it === 'show') $('.js-ad-loader').show();
        else $('.js-ad-loader').hide();
    }
    //
    function ucwords(str) {
        return (str + '').replace(/^([a-z])|\s+([a-z])/g, function ($1) {
            return $1.toUpperCase();
        });
    }
    // Pagination
    // Get previous page
    $(document).on('click', '.js-pagination-prev', pagination_event);
    // Get first page
    $(document).on('click', '.js-pagination-first', pagination_event);
    // Get last page
    $(document).on('click', '.js-pagination-last', pagination_event);
    // Get next page
    $(document).on('click', '.js-pagination-next', pagination_event);
    // Get page
    $(document).on('click', '.js-pagination-shift', pagination_event);
    // TODO convert it into a plugin
    function load_pagination(limit, list_size, target_ref, page_type){
        //
        var obj = pOBJ[page_type];
        // parsing to int
        limit = parseInt(limit);
        obj['page'] = parseInt(obj['page']);
        // get paginate array
        var page_array = paginate(obj['totalRecords'], obj['page'], limit, list_size);
        // append the target ul
        // to top and bottom of table
        var rows = '';
        rows += '<div class="col-lg-12">';
        rows += '   <div class="row pto-pagination">';
        rows += '       <div class="col-xs-4 col-lg-3">';
        rows += '           <div class="pagination-left-content js-showing-target">';
        rows += '               <div class="js-show-record"></div>';
        rows += '           </div>';
        rows += '       </div>';
        rows += '       <div class="col-xs-8 col-lg-9">';
        rows += '           <nav aria-label="Pagination">';
        rows += '               <ul class="pagination cs-pagination js-pagination"></ul>';
        rows += '           </nav>';
        rows += '       </div>';
        rows += '   </div>';
        rows += '</div>';

        target_ref.html(rows);
        // set rows append table
        var target = target_ref.find('.js-pagination');
        var targetShowing = target_ref.find('.js-showing-target');
        // get total items number
        var total_records = page_array.total_pages;
        // load pagination only there
        // are more than one page
        if(obj['totalRecords'] >= limit) {
            // generate li for
            // pagination
            var rows = '';
            // move to one step back
            rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == 1 ? '' : 'js-pagination-first')+'">First</a></li>';
            rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == 1 ? '' : 'js-pagination-prev')+'">&laquo;</a></li>';
            // generate 5 li
            $.each(page_array.pages, function(index, val) {
                rows += '<li class="'+(val == obj['page'] ?  'active page-item' : '')+'"><a href="javascript:void(0)" data-page-type="'+(page_type)+'" data-page="'+(val)+'" class="'+(obj['page'] != val ? 'js-pagination-shift' : '')+'">'+(val)+'</a></li>';
            });
            // move to one step forward
            rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == page_array.total_pages ? '' : 'js-pagination-next')+'">&raquo;</a></li>';
            rows += '<li class="page-item"><a href="javascript:void(0)" data-page-type="'+(page_type)+'" class="'+(obj['page'] == page_array.total_pages ? '' : 'js-pagination-last')+'">Last</a></li>';
            // append to ul
            target.html(rows);
        }
        // append showing of records
        targetShowing.html('<p>Showing '+(page_array.start_index + 1)+' - '+(page_array.end_index != -1 ? (page_array.end_index + 1) : 1)+' of '+(obj['totalRecords'])+'</p>');
    }
    // Paginate logic
    function paginate(total_items, current_page, page_size, max_pages) {
        // calculate total pages
        var total_pages = Math.ceil(total_items / page_size);

        // ensure current page isn't out of range
        if (current_page < 1) current_page = 1;
        else if (current_page > total_pages) current_page = total_pages;

        var start_page, end_page;
        if (total_pages <= max_pages) {
            // total pages less than max so show all pages
            start_page = 1;
            end_page = total_pages;
        } else {
            // total pages more than max so calculate start and end pages
            var max_pagesBeforecurrent_page = Math.floor(max_pages / 2);
            var max_pagesAftercurrent_page = Math.ceil(max_pages / 2) - 1;
            if (current_page <= max_pagesBeforecurrent_page) {
                // current page near the start
                start_page = 1;
                end_page = max_pages;
            } else if (current_page + max_pagesAftercurrent_page >= total_pages) {
                // current page near the end
                start_page = total_pages - max_pages + 1;
                end_page = total_pages;
            } else {
                // current page somewhere in the middle
                start_page = current_page - max_pagesBeforecurrent_page;
                end_page = current_page + max_pagesAftercurrent_page;
            }
        }

        // calculate start and end item indexes
        var start_index = (current_page - 1) * page_size;
        var end_index = Math.min(start_index + page_size - 1, total_items - 1);

        // create an array of pages to ng-repeat in the pager control
        var pages = Array.from(Array((end_page + 1) - start_page).keys()).map(i => start_page + i);

        // return object with all pager properties required by the view
        return {
            total_items: total_items,
            // current_page: current_page,
            // page_size: page_size,
            total_pages: total_pages,
            start_page: start_page,
            end_page: end_page,
            start_index: start_index,
            end_index: end_index,
            pages: pages
        };
    }
    //
    function pagination_event(){
        //
        var i = $(this).data('page-type');
        // When next is press
        if($(this).hasClass('js-pagination-next') === true){
            pOBJ[i]['page'] = pOBJ[i]['page'] + 1;
            pOBJ[i]['cb']($(this));
        } else if($(this).hasClass('js-pagination-prev') === true){
            pOBJ[i]['page'] = pOBJ[i]['page'] - 1;
            pOBJ[i]['cb']($(this));
        } else if($(this).hasClass('js-pagination-first') === true){
            pOBJ[i]['page'] = 1;
            pOBJ[i]['cb']($(this));
        } else if($(this).hasClass('js-pagination-last') === true){
            pOBJ[i]['page'] = pOBJ[i]['totalPages'];
            pOBJ[i]['cb']($(this));
        } else if($(this).hasClass('js-pagination-shift') === true){
            pOBJ[i]['page'] = parseInt($(this).data('page'));
            pOBJ[i]['cb']($(this));
        }
    }
    //
    function findRecord(records, recordId, index){
        var record = [],
        index = index === undefined ? 'plan_id' : index;
        records.forEach(function(v, i) {
            if(v[index] == recordId) {
                record = v;
                return false;
            }
        });
        return record;
    }
    //
    $("#btn_apply_filter").click(function(){
        $(this).toggleClass('btn-theme-bg');
        $(".filter-content").toggleClass('show-filter');
    });

    //
    function loadEmployeeOffBox(){
        var employeeOffLists = [
            { image: 'applican-img-JdHpeD.jpg', employee_id: '58', full_name: 'Mubashir Ahmed', last_timeoff_date: '11-10-2019' },
            { image: 'applican-img-JdHpeD.jpg', employee_id: '58', full_name: 'Ali', last_timeoff_date: '11-10-2019' },
            { image: 'applican-img-JdHpeD.jpg', employee_id: '58', full_name: 'Hassan Bokhary', last_timeoff_date: '11-10-2019' }
        ],
        mobileRow = '<div class="row hidden-sm hidden-md hidden-lg">',
        desktopRow = '<div class="row hidden-xs">',
        template = '';
        template += '<div class="col-md-12">';
        template += '   <div class="employees-figures people-out-of-office">';
        template += '       <p class="heading-style-custom">Out Of Office</p>';
        //
        template += '<div class="row padding-custom">';
        if(employeeOffLists.length > 0){
            $.each(employeeOffLists, function(i, v){
                template += '<div class="col-xs-4 col-sm-4 col-lg-3">';
                template += '    <div class="people-out-of-office-info">';
                template += '        <a href="<?=base_url();?>/employee_profile/'+( v.emplyee_id )+'" data-toggle="tooltip" title="'+( v.last_timeoff_date )+'" class="custom-tooltip">';
                template += '            <figure>';
                template += '                <img src="<?=AWS_S3_BUCKET_URL;?>'+( v.image )+'" class="img-circle emp-image" />';
                template += '            </figure>';
                template += '            <div class="text-center">';
                template += '                <h4>'+( v.full_name )+'</h4>';
                template += '            </div>';
                template += '        </a>';
                template += '    </div>';
                template += '</div>';
            });
        } else{
            template += '<div class="col-xs-4 col-sm-4 col-lg-3">';
            template += '   <div class="people-out-of-office-info">';
            template += '       <p>No one is taking time-off!</p>';
            template += '   </div>';
            template += '</div>';
        }
        template += '</div>';
        template += '   </div>';
        template += '</div>';
        //
        //$('#js-employee-off-box-desktop').html(desktopRow+template);
        //$('#js-employee-off-box-mobile').html(mobileRow+template);
    }

    loadEmployeeOffBox();

    String.prototype.ucwords = function() {
        return this.toLowerCase().replace(/(^([a-zA-Z\p{M}]))|([ -][a-zA-Z\p{M}])/g,
            function(s){
                return s.toUpperCase();
            }
        );
    };

    $('.btn-apply-filter').click(function(){
        $(this).toggleClass('btn-apply-filter-active');
    });


    function getImageURL(img){
        if(img == '' || img == null){
            return "<?=base_url('assets/images/img-applicant.jpg');?>";
        } else return "<?=AWS_S3_BUCKET_URL;?>"+( img )+"";
    }
    //
    function getEmployeeId(i, n){
        return n == '' || n == null ? i : n;
    }
    //
    function getReasonHTML(reason){
        if(reason != '')
            return `<a href="javascript:void(0)" title="Reason" data-trigger="hover" data-toggle="popover" data-placement="left" data-content="${reason}" class="action-activate custom-tooltip"><i class="fa fa-list-alt fa-fw text-success"></i></a>`;
        else
            return '<a href="javascript:void(0)"><i class="fa fa-list-alt fa-fw"></i></a>';
    }
    //
    function getStatusHTML(status){
        return '<p><span class="text-'+( status.toLowerCase() == 'approved' ? 'success' : status.toLowerCase() == 'rejected' ? 'danger' : 'warning' )+' cs-status-text">'+( status.toUpperCase() )+'</span></p>';
    }

    $('[data-toggle="popovers"]').popover({
        trigger: 'hover'
    })

	let dch = $(window).height() - $('.footer').height();
    $('.dashboard-wrp').css('min-height', dch+'px');