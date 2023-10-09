<main>
    <div class="row">
        <div class="col-xs-12 column-flex-center  top-background-div-resources" style="background-image: url(/assets/images/resourcesBanner.png);">
            <div class="background-image-div-contact-us width-50 " >
                <h1 class="automotoH1 darkGreyColor sora-family  margin-bottom-30 font-size-40 ">Resources</h1>
                <p class="autmotoPara text-center opacity-80">Delve into expert-crafted insights, tips, tools, and articles covering various professional domains.</p>            
            </div>
        </div>
    </div>
    <section class="light-grey-background resources">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 image-section-padding-product resources-page">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2 class="desktop-left sora-family darkGreyColor margin-bottom-30">Latest blog posts</h2>
                        </div>
                    </div>
                    <div class="row" id="jsBlogSection">
                        <?php if (!empty($blogs)) { ?>
                          
                                <?php foreach ($blogs as $blog) { ?>
                                    <div class="col-xs-12 col-md-6 col-lg-4">
                                        <div class="card card-margin-bottom" >
                                            <img class="resources-card-images-adjustment" src="<?= !empty($blog['feature_image']) ? AWS_S3_BUCKET_URL . $blog['feature_image'] : base_url('assets/images/no-img.jpg'); ?>" alt="smiling girl">
                                            <div class="card-body">
                                                <p class="card-text">
                                                    <?= substr(strip_tags($blog['description']),0,200) . '...'; ?>
                                                </p>
                                                <a  href="<?php echo base_url("resources/".$blog['slug']); ?>"  class="card-text">Read more</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?> 
                           
                        
                        <?php } else { ?>    
                            <div class="col-xs-12">
                                <p class="desktop-left sora-family darkGreyColor margin-bottom-30">No blog publish yet.</p>
                            </div>
                        <?php } ?> 
                    </div>
                    <?php if (!empty($resources)) { ?>
                        <div class="row">
                            <div class="col-xs-12 margin-top-30 column-flex-center">
                                <button class="d-flex justify-content-center align-items-center load-more login-screen-btns admin_btn btn-animate margin-top-30 jsLoadMoreBlog">
                                    <p class="text">Load More</p>
                                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                </button>    
                            </div> 
                        </div>
                    <?php } ?>    
                </div>
            </div>
        </div>
    </section>
    <section class="resources">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 image-section-padding-product resources-page">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="d-flex search-div-border margin-bottom-20">
                                <div class="background-white">
                                <img  src="<?= base_url('assets/v1/app/images/icon_search.png'); ?>" alt="icon search" />
                                </div>
                                <input id="jsSearchResources" class="no-margin opacity-50 search-input-font" type="email" placeholder="search topics and keywords" />
                            </div>
                        </div>
                        <div class="reource-row-wrapper">
                            <div class="col-xs-12 resources-checkbox-row  d-flex  justify-between padding-left-right-50">
                            <div class="d-flex  ">
                                <p>Resource Type :</p>
                            </div>
                            <div class=" resources-select ">
                                <select class="form-select form-select-lg mb-3 padding-top-0px" aria-label=".form-select-lg example">
                                <option selected>Select</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                                </select>
                            </div>
                            <div class="d-flex show-on-desktop ">
                                <div class="form-check">
                                <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="Video" type="checkbox">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Videos
                                </label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="Webinars" type="checkbox">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Webinars
                                </label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="Case Studies" type="checkbox">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Case Studies 
                                </label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="eBooks" type="checkbox">
                                <label class="form-check-label" for="flexCheckDefault">
                                    eBooks 
                                </label>
                                </div>
                                <div class="form-check">
                                <input class="form-check-input resources-checkbox no-padding" name="resourceType" value="Others" type="checkbox">
                                <label class="form-check-label" for="flexCheckDefault">
                                    others 
                                </label>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="row card-second-section-resources" id="jsResourceSection">
                        <?php if (!empty($resources)) { ?>
                            <?php foreach ($resources as $resource) { ?>
                                <div class="col-xs-12 col-md-6 col-lg-4">
                                    <div class="card card-margin-bottom">
                                        <?php  $dcoument_extension = pathinfo($resource['resources'], PATHINFO_EXTENSION); ?>

                                        <?php if (in_array($dcoument_extension, ['mp4','m4a','m4v','f4v','f4a','m4b','m4r','f4b','mov'])) { ?>
                                            <div class="resources-video-div"> 
                                                <video poster="./assets/images/smillingGirl.png" src="<?php echo base_url().'assets/uploaded_videos/resourses/' .$resource['resources']; ?>" controls="true" class="resources-video" alt="smiling girl" > </video>
                                            </div>
                                        <?php } else if (in_array($dcoument_extension, ['jpe', 'jpg', 'jpeg', 'png', 'gif'])) { ?>
                                            <img src="<?= !empty($resource['resources']) ? AWS_S3_BUCKET_URL . $resource['resources'] : base_url('assets/images/no-img.jpg'); ?>" class="resources-card-images-adjustment" alt="tablet with tea">
                                        <?php } ?>
                                        
                                        <div class="card-body">
                                            <div class="card-type-div">
                                                <?php 
                                                    $types = explode(',',$resource['resource_type']);
                                                    //
                                                    foreach ($types as $type) {
                                                        echo '<span class="resources-card-type card-pills-resource-type">'.$type.'</span>';
                                                    }
                                                ?>
                                            </div>
                                            <p class="card-text">
                                                <?= substr($resource['description'],0,200) . '...'; ?>
                                            </p>
                                            <a href="<?php echo base_url("resources/".$resource['slug']); ?>" class="card-anchor-text opacity-90">Watch Now</a>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>  
                        <?php } else { ?>    
                            <div class="col-xs-12">
                                <p class="desktop-left sora-family darkGreyColor margin-bottom-30">No Resources Found.</p>
                            </div>
                        <?php } ?> 
                    </div>
                    <?php if (!empty($resources)) { ?>
                        <div class="row">
                            <div class="col-xs-12 margin-top-30 column-flex-center">
                                <button class="d-flex load-more justify-content-center align-items-center login-screen-btns admin_btn btn-animate margin-top-30 jsLoadMoreResources">
                                    <p class="text">Load More</p>
                                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                </button>    
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <section class="resources-subscribe-section ">
        <div class="row">
            <div class="col-xs-12 column-flex-center">
                <div class="w-80 image-section-padding-product resources-page padding-top-0px">
                    <div class="row resources-subscribe-row ">
                        <div class="col-xs-12 col-xl-7 column-center">
                            <p class="autmotoPara subscribe-para">
                                Join our community of subscribers and experience growth through expertly curated insights for your business.
                            </p>
                            <div class="file-btn-div subscribe-input-div">
                                <input
                                    id="jsSubscriberEmail"
                                    class="upload-file-input subscribe-input"
                                    placeholder="you@yourcompany.com"
                                />
                                <button
                                    for="file-input"
                                    id="jsSubscribeCommunity"
                                    class="custom-file-upload "
                                    data-bs-toggle="modal" data-bs-target="#exampleModal"
                                >
                                    Subscribe
                                    <i class="fa-solid fa-arrow-right top-button-icon ps-3"></i>
                                </button>
                            </div> 
                        </div>
                        <div class="col-xs-12 col-xl-5">
                            <div class="resources-subscribe-image-div">
                                <img src="<?= base_url('assets/v1/app/images/multipleletters.png'); ?>" alt="multiple letters"/>
                            </div>
                        </div>
                    </div> 
                </div> 
            </div> 
        </div> 
    </section>
    <div class="modal fade resources-subscribe-model" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog position-relative">
            <div class="modal-content">
                <button type="button resources-subscribe-model-btn" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="modal-body">
                    <div class="text-center" >
                        <img src="<?= base_url('assets/v1/app/images/letterWithTick.png'); ?>" alt="letter with tick"  />
                    </div>
                    <p class="text-center darkGreyColor fw-500">Thank you for opting to stay updated through our blog!</p>
                    <p class="text-center dark-grey-color">Get ready to receive exciting content.</p>
                </div>
            </div>
        </div>
    </div>
</main>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery-1.11.3.min.js"></script>
<script>
    //
    // set the xhr
	let XHR = null;
    let blogCount = 1;
    let resourceCount = 1;
    let timeoutID = null;
    let BASEURL = '<?php echo base_url(); ?>';
    let AWSPath = '<?php echo AWS_S3_BUCKET_URL; ?>';

    //
    $(document).on("click", ".jsLoadMoreBlog", function (event) {
		// call the function
		XHR = $.ajax({
			url: BASEURL +"load_more/blog/"+ blogCount,
			method: "GET"
		})
        .success(function (response) {
            // empty the call
            XHR = null;
            //
            let blogHTML = "";
            //
            if (response.length) {
                response.map(function (blog) {
                    blogHTML += `<div class="col-xs-12 col-md-6 col-lg-4">`;
                    blogHTML += `   <div class="card card-margin-bottom">`;
                    blogHTML += `       <img src="${AWSPath+blog.feature_image}" class="resources-card-images-adjustment" alt="smiling girl">`;
                    blogHTML += `       <div class="card-body">`;
                    blogHTML += `           <p class="card-text">`;
                    blogHTML += `               ${blog.description}`;
                    blogHTML += `           </p>`;
                    blogHTML += `           <a href="${BASEURL}read_more/${blog.slug}"  class="card-text">Read more</a>`;
                    blogHTML += `       </div>`;
                    blogHTML += `   </div>`;
                    blogHTML += `</div>`;
                });
            }  
            //
            $("#jsBlogSection").append(blogHTML);
            //      
            blogCount++;
            //
            if (response.length < 3) {
                $(".jsLoadMoreBlog").prop('disabled', true)
            }
        })
        .fail()
        .done(function () {
            // empty the call
            XHR = null;
        });
	});

    $(document).on("click", ".jsLoadMoreResources", function (event) {
		getMoreResources();
	});

    $(document).on("click", "#jsSubscribeCommunity", function (event) {
        let email = $("#jsSubscriberEmail").val();
        //
        if (!isEmail(email)) {
            return alertify.alert('Error', 'Please Enter valid email', function() {});
        }
        //
        if (XHR !== null) {
            return;
        }
        //
        var obj = {
            scriber_email: email
        };
        //
        XHR = $.ajax({
                method: "POST",
                url: BASEURL +"subscribeCommunity",
                data: obj
            })
            .success(function(response) {
                XHR = null;
                //
                $('#exampleModal').modal('toggle');
                $("#jsSubscriberEmail").val("");
            })
            .fail(function() {
                XHR = null;
                //
                return alertify.alert(
                    'Error!',
                    'Oops! Something went wrong. Please try again in a few moments.',
                    function() {}
                );
            });
        // 
    });

    $(document).on("click", ".resources-checkbox", function (event) {
        resourceCount = 0;
        $("#jsResourceSection").html("");
        getMoreResources();
    });

    $('#jsSearchResources').keyup(function(e) {
        clearTimeout(timeoutID);
        let value = e.target.value
        timeoutID = setTimeout(function () {
            resourceCount = 0;
            $("#jsResourceSection").html("");
            getMoreResources();
        }, 1000);
    });
     
    function isEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }

    function getMoreResources () {
        //
        let filterObj = getFilterCheckbox();
        // call the function
		XHR = $.ajax({
			url: BASEURL +"load_more/resource/"+ resourceCount,
			method: "GET",
            data: filterObj
		})
        .success(function (response) {
            // empty the call
            XHR = null;
            //
            let resourceHTML = "";
            //
            if (response.length) {
                response.map(function (resource) {
                    resourceHTML += `<div class="col-xs-12 col-md-6 col-lg-4">`;
                    resourceHTML += `   <div class="card card-margin-bottom">`;
                    //
                    var ext = resource.resources.split('.').pop();
                    //
                    if (ext == "jpe" || ext == "jpg" || ext == "jpeg" || ext == "png" || ext == "gif") {
                        resourceHTML += `<img src="${AWSPath+resource.resources}" class="resources-card-images-adjustment" alt="tablet with tea">`;
                    } else if (ext == "mp4" || ext == "m4a" || ext == "m4v" || ext == "f4v" || ext == "f4a" | ext == "m4b" || ext == "m4r" || ext == "f4b" || ext == "mov") {
                        resourceHTML += `<div class="resources-video-div">`; 
                        resourceHTML += `   <video poster="./assets/images/smillingGirl.png" src="${BASEURL}assets/uploaded_videos/resourses/${resource.resources}" controls="true" class="resources-video" alt="smiling girl" > </video>`;
                        resourceHTML += `</div>`;
                    }
                    //
                    resourceHTML += `       <div class="card-body">`;
                    resourceHTML += `           <div class="card-type-div">`;
                    //
                    let resourceTypes = resource.resource_type.split(',');
                    //
                    resourceTypes.map(function (type) {
                        resourceHTML += `           <span class="resources-card-type">${type}</span>`;
                    });
                    //
                    resourceHTML += `           </div>`;
                    resourceHTML += `           <p class="card-text">`;
                    resourceHTML +=                 resource.description;
                    resourceHTML += `           </p>`;
                    resourceHTML += `           <a href="${BASEURL}watch_resource/${resource.slug}" class="card-anchor-text opacity-90">Watch Now</a>`;
                    resourceHTML += `       </div>`;
                    resourceHTML += `   </div>`;
                    resourceHTML += `</div>`;
                });
            }  
            //
            $("#jsResourceSection").append(resourceHTML);
            //      
            resourceCount++;
            //
            if (response.length < 3) {
                $(".jsLoadMoreResources").prop('disabled', true)
            }
        })
        .fail()
        .done(function () {
            // empty the call
            XHR = null;
        });
    }

    function getFilterCheckbox () {
        let categoryTypes = $('input[name=resourceType]:checked').map(function(){
            return $(this).val();
        }).get(); 
        //
        let filterString = $('#jsSearchResources').val();
        //
        var obj = {
            category: categoryTypes,
            keywords: filterString
        };
        //
        return obj;
    }
</script>
