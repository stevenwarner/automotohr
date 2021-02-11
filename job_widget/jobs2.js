(function(window, document, version, callback) {
    var j, d;
    var loaded = false;
    if (!(j = window.jQuery) || version > j.fn.jquery || callback(j, loaded)) {
        var script = document.createElement("script");
        script.type = "text/javascript";
        script.src = "http://tratodeauto.com/job_widget/jquery.js";
        script.onload = script.onreadystatechange = function() {
            if (!loaded && (!(d = this.readyState) || d == "loaded" || d == "complete")) {
                callback((j = window.jQuery).noConflict(1), loaded = true);
                j(script).remove();
            }
        };
        (document.getElementsByTagName("head")[0] || document.documentElement).appendChild(script);
    }
})(window, document, "1.3", function($, jquery_loaded) {
    console.log(document.getElementsByTagName('head')[0]);
    checkjquery();
});
    
    function checkjquery() {
       //console.log( "You are running jQuery version: " +$.fn.jquery );
       var id = document.getElementsByClassName('automotosocial_webwidget')[0].id;
       id = id.substr(5);
       console.log("Here: ID = "+id);
       var url = "http://tratodeauto.com/job_widget/data.php";
            jQuery.ajax({
              type: 'GET',    
              url: url,
              dataType: "jsonp",
              data: {id: id},
              jsonpCallback: 'myCallback',
              contentType: "application/json; charset=utf-8",
              crossDomain: true,          
              cache:false, 
              async: true,
              success: function(data){
                    var widget_jobs = '';
                     jQuery.each(data, function(index) {
                         if(data[index].has_records=='true'){
                            widget_jobs +='<li><div class="row_list"><div class="onecol"><span class="listIcon"><img src="http://tratodeauto.com/job_widget/images/jobSmall_icon.png" alt="image"></span>';
                            widget_jobs +='<div class="text">'+data[index].Title+'</div></div><div class="twocol"><span class="listIcon"><img src="http://tratodeauto.com/job_widget/images/place_icon.png" alt="image"></span>';
                            widget_jobs +=data[index].state_name+', '+data[index].country_name+'</div><div class="threecol"><span class="listIcon"><img src="http://tratodeauto.com/job_widget/images/time_icon.png" alt="image">';
                            widget_jobs +='</span>'+data[index].JobType+'</div><div class="col-last"><input type="button" class="show_div siteBtn shortBtn_bold f_right" value="Show Details" name="open"/>';
                            widget_jobs +='<input type="button" class="show_div_close siteBtn shortBtn_bold orangeBtn f_right" name="close" value="Hide Details" style="display:none"/>';
                            widget_jobs +='</div></div><div style="display:none;" class="jobDet" id="1"><h4 class="accord_hed">JOB DESCRIPTION:</h4><div class="accord_det">';
                            widget_jobs += data[index].JobDescription+'<a class="siteBtn" href="http://'+data[index].sub_domain+'/display_jobs.php?id='+data[index].sid+'" target="_blank">APPLY NOW</a></div></div></li>';
                         }
                    });
                    //document.getElementById("automoto-widget-container").innerHTML = widget_jobs;
                    var custom_html = '<link rel="stylesheet" type="text/css" href="http://tratodeauto.com/job_widget/css/widget.css"><div class="job-main-content"><div class="job-container"><div class="job-feature-main"><div class="portalmid"><div class="white_panel"><ul class="jobList">'+widget_jobs+'</ul></div></div></div></div></div><script type="text/javascript" src="http://tratodeauto.com/job_widget/jquery.js"></script><script>$(document).ready(function () { $(".show_div").click(function() { $(this).parents().eq(1).next().slideToggle("slow"); $(this).hide(); $(this).next().show(); }); $(".show_div_close").click(function() { $(this).parents().eq(1).next().slideToggle("slow"); $(this).hide(); $(this).prev().show(); }); });</script>';
                    var iframe = document.createElement('iframe');
                        iframe.style.width = "100%";
                        iframe.style.height = "100%";
                        if(data[0].has_records=='true'){
                            iframe.src = 'data:text/html;charset=utf-8,' + encodeURI(custom_html);
                        } else {
                            iframe.src = 'data:text/html;charset=utf-8,' + encodeURI(data[0].Title);
                        }
                        //document.body.appendChild(iframe);
                    document.getElementById("automoto-widget-container").appendChild(iframe);
                    //console.log('iframe.contentWindow =', iframe.contentWindow);
                },
              error:function(jqXHR, textStatus, errorThrown){
                console.log("errorThrown: "+errorThrown +", jqXHR: "+JSON.stringify(jqXHR, null, 4)+", textStatus: "+textStatus);
              }
            });
    }