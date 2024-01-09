 <section class="whyus-fourth">
     <div class="row">
         <div class="col-xs-12 column-flex-center">
             <div class="w-80 ">
                 <div class="row ">
                     <div class="col-xs-12 col-xl-6 <?= $hasTopPadding ? "padding-top-80" : ""; ?>">
                         <div class="center-horizontally">
                             <?=
                                getSourceByType(
                                    $data["sourceType"],
                                    $data["sourceFile"],
                                    '',
                                    false
                                ); ?>
                         </div>
                     </div>
                     <div class="col-xs-12 col-xl-6 yellow-border-right position-relative  <?= $hasTopPadding ? "padding-top-80" : ""; ?>">
                         <img src="<?= image_url("Polygon 7.png") ?>" alt="right arrow pointer" class="yellow-small-triangle-aboutus" />
                         <div class="aboutus-grey-background">
                             <div class="d-flex align-items-center">
                                 <h3>
                                     <?= convertToStrip($data["mainHeading"]); ?>
                                 </h3>
                             </div>

                             <div class="autmotoPara aboutus-boxes-para opacity-90 margin-bottom-0">
                                 <?= convertToStrip($data["details"]); ?>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </section>