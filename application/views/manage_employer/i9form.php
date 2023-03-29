<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <?php $this->load->view('templates/_parts/admin_flash_message'); ?>

                            <?php $this->load->view('manage_employer/employee_management/employee_profile_ats_view_top'); ?>

                            <div class="page-header-area margin-top">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?>
                                    <a class="dashboard-link-btn" href="<?php echo $return_title_heading_link; ?>"><i class="fa fa-chevron-left"></i><?php echo $return_title_heading; ?></a>

                                    <?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="table-responsive table-outer">
                                    <div class="i9form-wrp">
                                        <STYLE type="text/css">
                                            body {margin-top: 0px;margin-left: 0px;}
                                            #page_1 {position:relative; overflow: hidden;margin: 14px 0px 21px 48px;padding: 0px;border: none;width: 768px;}

                                            #page_1 #dimg1 {position:absolute;top:10px;left:0px;z-index:-1;width:721px;height:979px;}
                                            #page_1 #dimg1 #img1 {width:721px;height:979px;}

                                            #page_2 {position:relative; overflow: hidden;margin: 36px 0px 25px 48px;padding: 0px;border: none;width: 768px;}
                                            #page_2 #id_1 {border:none;margin: 16px 0px 0px 0px;padding: 0px;border:none;width: 768px;overflow: hidden;}
                                            #page_2 #id_2 {border:none;margin: 56px 0px 0px 0px;padding: 0px;border:none;width: 720px;overflow: hidden;}

                                            #page_2 #dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:720px;height:974px;}
                                            #page_2 #dimg1 #img1 {width:720px;height:974px;}

                                            #page_3 {position:relative; overflow: hidden;margin: 40px 0px 25px 48px;padding: 0px;border: none;width: 768px;}
                                            #page_3 #id_1 {border:none;margin: 21px 0px 0px 0px;padding: 0px;border:none;width: 768px;overflow: hidden;}
                                            #page_3 #id_2 {border:none;margin: 39px 0px 0px 0px;padding: 0px;border:none;width: 720px;overflow: hidden;}

                                            #page_3 #dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:720px;height:969px;}

                                            #page_4 {position:relative; overflow: hidden;margin: 40px 0px 25px 48px;padding: 0px;border: none;width: 768px;}
                                            #page_4 #id_1 {border:none;margin: 16px 0px 0px 0px;padding: 0px;border:none;width: 768px;overflow: hidden;}
                                            #page_4 #id_2 {border:none;margin: 36px 0px 0px 0px;padding: 0px;border:none;width: 720px;overflow: hidden;}

                                            #page_4 #dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:722px;height:970px;}

                                            #page_5 {position:relative; overflow: hidden;margin: 36px 0px 25px 48px;padding: 0px;border: none;width: 768px;}
                                            #page_5 #id_1 {border:none;margin: 16px 0px 0px 0px;padding: 0px;border:none;width: 768px;overflow: hidden;}
                                            #page_5 #id_2 {border:none;margin: 29px 0px 0px 0px;padding: 0px;border:none;width: 720px;overflow: hidden;}

                                            #page_5 #dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:720px;height:974px;}

                                            #page_6 {position:relative; overflow: hidden;margin: 40px 0px 25px 48px;padding: 0px;border: none;width: 768px;}
                                            #page_6 #id_1 {border:none;margin: 17px 0px 0px 0px;padding: 0px;border:none;width: 768px;overflow: hidden;}
                                            #page_6 #id_2 {border:none;margin: 15px 0px 0px 0px;padding: 0px;border:none;width: 720px;overflow: hidden;}

                                            #page_6 #dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:720px;height:970px;}

                                            #page_7 {position:relative; overflow: hidden;margin: 23px 0px 25px 48px;padding: 0px;border: none;width: 768px;}
                                            #page_7 #id_1 {border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 768px;overflow: hidden;}
                                            #page_7 #id_2 {border:none;margin: 5px 0px 0px 1px;padding: 0px;border:none;width: 767px;overflow: hidden;}
                                            #page_7 #id_2 #id_2_1 {float:left;border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 100%;overflow: hidden;}
                                            #page_7 #id_2 #id_2_2 {float:left;border:none;margin: 71px 0px 0px 0px;padding: 0px;border:none;width: 275px;overflow: hidden;}
                                            #page_7 #id_3 {border:none;margin: 1px 0px 0px 22px;padding: 0px;border:none;width: 746px;overflow: hidden;}
                                            #page_7 #id_4 {border:none;margin: 8px 0px 0px 22px;padding: 0px;border:none;width: 746px;overflow: hidden;}
                                            #page_7 #id_4 #id_4_1 {float:left;border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 550px;overflow: hidden;}
                                            #page_7 #id_4 #id_4_2 {float:left;border:none;margin: 11px 0px 0px 0px;padding: 0px;border:none;width: 196px;overflow: hidden;}
                                            #page_7 #id_5 {border:none;margin: 8px 0px 0px 0px;padding: 0px;border:none;width: 768px;overflow: hidden;}
                                            #page_7 #id_6 {border:none;margin: 18px 0px 0px 0px;padding: 0px;border:none;width: 720px;overflow: hidden;}

                                            #page_7 #dimg1 {position:absolute;top:9px;left:0px;z-index:-1;width:720px;height:978px;}
                                            #page_7 #dimg1 #img1 {width:720px;height:978px;}

                                            #page_7 #inl_img1 {position:relative;width:41px;height:21px;}
                                            #page_7 #inl_img2 {position:relative;width:1px;height:20px;}
                                            #page_7 #inl_img3 {position:relative;width:1px;height:20px;}
                                            #page_7 #inl_img4 {position:relative;width:1px;height:21px;}
                                            #page_7 #inl_img5 {position:relative;width:26px;height:21px;}
                                            #page_7 #inl_img6 {position:relative;width:0px;height:16px;}
                                            #page_7 #inl_img7 {position:relative;width:0px;height:21px;}
                                            #page_7 #inl_img8 {position:relative;width:56px;height:21px;}
                                            #page_7 #inl_img9 {position:relative;width:1px;height:20px;}
                                            #page_7 #inl_img10 {position:relative;width:1px;height:19px;}
                                            #page_7 #inl_img11 {position:relative;width:0px;height:20px;}
                                            #page_7 #inl_img12 {position:relative;width:0px;height:21px;}
                                            #page_7 #inl_img13 {position:relative;width:0px;height:21px;}
                                            #page_7 #inl_img14 {position:relative;width:14px;height:14px;}
                                            #page_7 #inl_img15 {position:relative;width:0px;height:14px;}

                                            #page_8 {position:relative; overflow: hidden;margin: 41px 0px 25px 47px;padding: 0px;border: none;width: 769px;}
                                            #page_8 #id_1 {border:none;margin: 19px 0px 0px 0px;padding: 0px;border:none;width: 769px;overflow: hidden;}
                                            #page_8 #id_2 {border:none;margin: 4px 0px 0px 1px;padding: 0px;border:none;width: 720px;overflow: hidden;}

                                            #page_8 #dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:722px;}

                                            #page_9 {position:relative; overflow: hidden;margin: 44px 0px 25px 48px;padding: 0px;border: none;width: 768px;}
                                            #page_9 #id_1 {border:none;margin: 16px 0px 0px 0px;padding: 0px;border:none;width: 768px;overflow: hidden;}
                                            #page_9 #id_2 {border:none;margin: 103px 0px 0px 0px;padding: 0px;border:none;width: 720px;overflow: hidden;}

                                            #page_9 #dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:720px;height:966px;}
                                            #page_9 #dimg1 #img1 {width:720px;height:966px;}

                                            .dclr {clear:both;float:none;height:1px;margin:0px;padding:0px;overflow:hidden;}

                                            .ft0{font: 1px 'Times New Roman';line-height: 1px;}
                                            .ft1{font: bold 19px 'Times New Roman';line-height: 22px;}
                                            .ft2{font: bold 15px 'Times New Roman';line-height: 17px;}
                                            .ft3{font: 1px 'Times New Roman';line-height: 16px;}
                                            .ft4{font: bold 14px 'Times New Roman';line-height: 17px;}
                                            .ft5{font: bold 14px 'Times New Roman';line-height: 16px;}
                                            .ft6{font: 1px 'Times New Roman';line-height: 14px;}
                                            .ft7{font: 11px 'Times New Roman';line-height: 14px;}
                                            .ft8{font: 15px 'Times New Roman';line-height: 17px;}
                                            .ft9{font: 12px 'Times New Roman';line-height: 15px;}
                                            .ft10{font: 1px 'Times New Roman';line-height: 2px;}
                                            .ft11{font: 1px 'Times New Roman';line-height: 8px;}
                                            .ft12{font: 1px 'Times New Roman';line-height: 3px;}
                                            .ft13{font: bold 13px 'Times New Roman';line-height: 15px;}
                                            .ft14{font: bold 15px 'Times New Roman';line-height: 18px;}
                                            .ft15{font: bold 15px 'Times New Roman';text-decoration: underline;color: #0000ff;line-height: 18px;}
                                            .ft16{font: 15px 'Times New Roman';line-height: 18px;}
                                            .ft17{font: bold 16px 'Times New Roman';line-height: 19px;}
                                            .ft18{font: bold 15px 'Times New Roman';line-height: 20px;}
                                            .ft19{font: 15px 'Times New Roman';line-height: 20px;}
                                            .ft20{font: bold 15px 'Times New Roman';line-height: 19px;}
                                            .ft21{font: 15px 'Times New Roman';line-height: 19px;}
                                            .ft22{font: bold 12px 'Times New Roman';line-height: 15px;}
                                            .ft23{font: bold 15px 'Times New Roman';margin-left: 11px;line-height: 17px;}
                                            .ft24{font: bold 15px 'Times New Roman';margin-left: 12px;line-height: 19px;}
                                            .ft25{font: bold 15px 'Times New Roman';margin-left: 12px;line-height: 18px;}
                                            .ft26{font: 15px 'Times New Roman';margin-left: 11px;line-height: 19px;}
                                            .ft27{font: 15px 'Times New Roman';margin-left: 11px;line-height: 18px;}
                                            .ft28{font: 15px 'Times New Roman';margin-left: 7px;line-height: 19px;}
                                            .ft29{font: 15px 'Times New Roman';margin-left: 7px;line-height: 18px;}
                                            .ft30{font: italic 15px 'Times New Roman';line-height: 18px;}
                                            .ft31{font: 15px 'Times New Roman';margin-left: 12px;line-height: 18px;}
                                            .ft32{font: 15px 'Times New Roman';margin-left: 12px;line-height: 19px;}
                                            .ft33{font: 15px 'Times New Roman';margin-left: 11px;line-height: 17px;}
                                            .ft34{font: 15px 'Times New Roman';text-decoration: underline;color: #0000ff;line-height: 18px;}
                                            .ft35{font: bold 15px 'Times New Roman';text-decoration: underline;color: #0000ff;line-height: 19px;}
                                            .ft36{font: italic 15px 'Times New Roman';line-height: 19px;}
                                            .ft37{font: 15px 'Times New Roman';margin-left: 11px;line-height: 24px;}
                                            .ft38{font: 15px 'Times New Roman';line-height: 24px;}
                                            .ft39{font: 15px 'Times New Roman';margin-left: 8px;line-height: 20px;}
                                            .ft40{font: 15px 'Times New Roman';margin-left: 8px;line-height: 19px;}
                                            .ft41{font: 1px 'Arial';line-height: 1px;}
                                            .ft42{font: 1px 'Arial';line-height: 16px;}
                                            .ft43{font: 1px 'Arial';line-height: 13px;}
                                            .ft44{font: 11px 'Times New Roman';line-height: 13px;}
                                            .ft45{font: 1px 'Arial';line-height: 3px;}
                                            .ft46{font: 1px 'Arial';line-height: 7px;}
                                            .ft47{font: 1px 'Arial';line-height: 4px;}
                                            .ft48{font: 13px 'Arial';line-height: 16px;}
                                            .ft49{font: bold 12px 'Arial';line-height: 15px;}
                                            .ft50{font: bold 11px 'Arial';line-height: 14px;}
                                            .ft51{font: 12px 'Arial';line-height: 15px;}
                                            .ft52{font: bold 15px 'Arial';line-height: 19px;}
                                            .ft53{font: italic bold 12px 'Arial';line-height: 16px;}
                                            .ft54{font: italic 12px 'Arial';line-height: 16px;}
                                            .ft55{font: italic 11px 'Arial';line-height: 14px;}
                                            .ft56{font: 11px 'Arial';line-height: 14px;}
                                            .ft57{font: 9px 'Arial';line-height: 12px;}
                                            .ft58{font: 1px 'Arial';line-height: 2px;}
                                            .ft59{font: 20px 'Times New Roman';line-height: 22px;}
                                            .ft60{font: italic 12px 'Arial';line-height: 15px;}
                                            .ft61{font: italic bold 12px 'Arial';line-height: 15px;}
                                            .ft62{font: bold 16px 'Arial';line-height: 19px;}
                                            .ft63{font: 1px 'Arial';line-height: 9px;}
                                            .ft64{font: bold 13px 'Arial';line-height: 16px;}
                                            .ft65{font: italic bold 15px 'Times New Roman';line-height: 18px;}
                                            .ft66{font: bold 15px 'Arial';line-height: 18px;}
                                            .ft67{font: bold 10px 'Arial';line-height: 12px;}
                                            .ft68{font: 11px 'Arial';line-height: 13px;}
                                            .ft69{font: 11px 'Arial';line-height: 9px;}
                                            .ft70{font: italic 11px 'Arial';line-height: 9px;}
                                            .ft71{font: 1px 'Arial';line-height: 8px;}
                                            .ft72{font: 1px 'Arial';line-height: 11px;}
                                            .ft73{font: 1px 'Arial';line-height: 10px;}
                                            .ft74{font: 1px 'Arial';line-height: 5px;}
                                            .ft75{font: 1px 'Arial';line-height: 6px;}
                                            .ft76{font: italic 10px 'Arial';line-height: 13px;}
                                            .ft77{font: 10px 'Arial';line-height: 13px;}
                                            .ft78{font: 11px 'Arial';margin-left: 6px;line-height: 14px;}
                                            .ft79{font: bold 11px 'Arial';line-height: 17px;}
                                            .ft80{font: 12px 'Arial';line-height: 14px;}
                                            .ft81{font: 12px 'Arial';line-height: 13px;}
                                            .ft82{font: 12px 'Arial';line-height: 12px;}
                                            .ft83{font: 1px 'Arial';line-height: 14px;}
                                            .ft84{font: 1px 'Arial';line-height: 12px;}
                                            .ft85{font: bold 12px 'Arial';line-height: 13px;}
                                            .ft86{font: bold 12px 'Arial';line-height: 14px;}
                                            .ft87{font: bold 13px 'Arial';line-height: 15px;}

                                            .p0{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p1{text-align: left;padding-left: 150px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p2{text-align: center;padding-right: 6px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p3{text-align: center;padding-left: 102px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p4{text-align: center;padding-right: 3px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p5{text-align: left;padding-left: 192px;margin-top: 4px;margin-bottom: 0px;}
                                            .p6{text-align: left;padding-right: 60px;margin-top: 6px;margin-bottom: 0px;}
                                            .p7{text-align: left;padding-left: 3px;margin-top: 16px;margin-bottom: 0px;}
                                            .p8{text-align: left;padding-right: 51px;margin-top: 10px;margin-bottom: 0px;}
                                            .p9{text-align: left;padding-left: 3px;margin-bottom: 0px;}
                                            .p10{text-align: justify;padding-right: 84px;margin-top: 10px;margin-bottom: 0px;}
                                            .p11{text-align: left;padding-right: 54px;margin-top: 5px;margin-bottom: 0px;}
                                            .p12{text-align: left;padding-left: 3px;margin-top: 18px;margin-bottom: 0px;}
                                            .p13{text-align: left;padding-right: 91px;margin-top: 9px;margin-bottom: 0px;}
                                            .p14{text-align: left;margin-top: 1px;margin-bottom: 0px;}
                                            .p15{text-align: left;padding-left: 22px;padding-right: 62px;margin-top: 6px;margin-bottom: 0px;}
                                            .p16{text-align: left;padding-left: 22px;padding-right: 76px;margin-top: 4px;margin-bottom: 0px;}
                                            .p17{text-align: justify;padding-left: 22px;padding-right: 64px;margin-top: 2px;margin-bottom: 0px;}
                                            .p18{text-align: left;padding-left: 22px;padding-right: 89px;margin-top: 6px;margin-bottom: 0px;}
                                            .p19{text-align: left;padding-left: 22px;padding-right: 58px;margin-top: 2px;margin-bottom: 0px;}
                                            .p20{text-align: left;padding-left: 22px;padding-right: 64px;margin-top: 0px;margin-bottom: 0px;}
                                            .p21{text-align: left;padding-left: 22px;padding-right: 48px;margin-top: 0px;margin-bottom: 0px;}
                                            .p22{text-align: center;padding-right: 108px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p23{text-align: center;padding-right: 106px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p24{text-align: left;padding-right: 72px;margin-top: 0px;margin-bottom: 0px;}
                                            .p25{text-align: justify;margin-top: 10px;margin-bottom: 0px;}
                                            .p26{text-align: justify;padding-left: 23px;padding-right: 56px;margin-top: 8px;margin-bottom: 0px;text-indent: -23px;}
                                            .p27{text-align: left;padding-left: 23px;padding-right: 54px;margin-top: 2px;margin-bottom: 0px;text-indent: -23px;}
                                            .p28{text-align: justify;padding-left: 23px;padding-right: 52px;margin-top: 4px;margin-bottom: 0px;text-indent: -23px;}
                                            .p29{text-align: justify;padding-left: 22px;margin-top: 1px;margin-bottom: 0px;}
                                            .p30{text-align: justify;padding-left: 45px;padding-right: 47px;margin-top: 9px;margin-bottom: 0px;text-indent: -22px;}
                                            .p31{text-align: left;padding-left: 45px;padding-right: 56px;margin-top: 3px;margin-bottom: 0px;text-indent: -23px;}
                                            .p32{text-align: left;padding-left: 68px;padding-right: 55px;margin-top: 5px;margin-bottom: 0px;text-indent: -24px;}
                                            .p33{text-align: left;padding-left: 67px;padding-right: 51px;margin-top: 4px;margin-bottom: 0px;text-indent: -24px;}
                                            .p34{text-align: left;padding-right: 48px;margin-top: 5px;margin-bottom: 0px;}
                                            .p35{text-align: left;margin-top: 9px;margin-bottom: 0px;}
                                            .p36{text-align: left;padding-right: 55px;margin-top: 6px;margin-bottom: 0px;}
                                            .p37{text-align: left;margin-top: 16px;margin-bottom: 0px;}
                                            .p38{text-align: left;padding-right: 50px;margin-top: 7px;margin-bottom: 0px;}
                                            .p39{text-align: left;padding-left: 3px;margin-top: 0px;margin-bottom: 0px;}
                                            .p40{text-align: left;padding-right: 69px;margin-top: 9px;margin-bottom: 0px;}
                                            .p41{text-align: left;padding-right: 51px;margin-top: 6px;margin-bottom: 0px;}
                                            .p42{text-align: left;padding-right: 50px;margin-top: 6px;margin-bottom: 0px;}
                                            .p43{text-align: left;padding-right: 50px;margin-top: 4px;margin-bottom: 0px;}
                                            .p44{text-align: left;margin-top: 6px;margin-bottom: 0px;}
                                            .p45{text-align: justify;padding-left: 23px;padding-right: 56px;margin-top: 7px;margin-bottom: 0px;text-indent: -23px;}
                                            .p46{text-align: justify;padding-left: 23px;padding-right: 75px;margin-top: 5px;margin-bottom: 0px;text-indent: -23px;}
                                            .p47{text-align: justify;padding-left: 22px;padding-right: 83px;margin-top: 2px;margin-bottom: 0px;}
                                            .p48{text-align: justify;padding-left: 42px;padding-right: 55px;margin-top: 4px;margin-bottom: 0px;text-indent: -19px;}
                                            .p49{text-align: left;padding-left: 23px;padding-right: 48px;margin-top: 5px;margin-bottom: 0px;text-indent: -23px;}
                                            .p50{text-align: justify;padding-left: 23px;padding-right: 124px;margin-top: 3px;margin-bottom: 0px;text-indent: -23px;}
                                            .p51{text-align: justify;margin-top: 4px;margin-bottom: 0px;}
                                            .p52{text-align: justify;margin-top: 8px;margin-bottom: 0px;}
                                            .p53{text-align: left;padding-right: 69px;margin-top: 12px;margin-bottom: 0px;}
                                            .p54{text-align: left;margin-top: 0px;margin-bottom: 0px;}
                                            .p55{text-align: left;padding-right: 66px;margin-top: 7px;margin-bottom: 0px;}
                                            .p56{text-align: left;margin-top: 12px;margin-bottom: 0px;}
                                            .p57{text-align: left;padding-right: 48px;margin-top: 7px;margin-bottom: 0px;}
                                            .p58{text-align: left;margin-top: 8px;margin-bottom: 0px;}
                                            .p59{text-align: justify;padding-left: 23px;padding-right: 106px;margin-top: 8px;margin-bottom: 0px;text-indent: -23px;}
                                            .p60{text-align: left;padding-left: 23px;padding-right: 83px;margin-top: 9px;margin-bottom: 0px;text-indent: -23px;}
                                            .p61{text-align: left;padding-left: 23px;padding-right: 53px;margin-top: 4px;margin-bottom: 0px;text-indent: -23px;}
                                            .p62{text-align: justify;padding-left: 23px;padding-right: 86px;margin-top: 9px;margin-bottom: 0px;text-indent: -23px;}
                                            .p63{text-align: left;margin-top: 10px;margin-bottom: 0px;}
                                            .p64{text-align: justify;margin-top: 9px;margin-bottom: 0px;}
                                            .p65{text-align: left;padding-right: 58px;margin-top: 15px;margin-bottom: 0px;}
                                            .p66{text-align: left;padding-right: 62px;margin-top: 14px;margin-bottom: 0px;}
                                            .p67{text-align: left;padding-right: 113px;margin-top: 7px;margin-bottom: 0px;}
                                            .p68{text-align: left;padding-right: 61px;margin-top: 0px;margin-bottom: 0px;}
                                            .p69{text-align: left;padding-right: 95px;margin-top: 6px;margin-bottom: 0px;}
                                            .p70{text-align: justify;margin-top: 1px;margin-bottom: 0px;}
                                            .p71{text-align: justify;padding-right: 166px;margin-top: 7px;margin-bottom: 0px;}
                                            .p72{text-align: left;padding-right: 72px;margin-top: 1px;margin-bottom: 0px;}
                                            .p73{text-align: justify;padding-right: 60px;margin-top: 4px;margin-bottom: 0px;}
                                            .p74{text-align: justify;margin-top: 6px;margin-bottom: 0px;}
                                            .p75{text-align: justify;padding-left: 21px;padding-right: 52px;margin-top: 7px;margin-bottom: 0px;text-indent: -21px;}
                                            .p76{text-align: justify;padding-left: 44px;padding-right: 47px;margin-top: 10px;margin-bottom: 0px;text-indent: -20px;}
                                            .p77{text-align: justify;padding-left: 44px;padding-right: 59px;margin-top: 0px;margin-bottom: 0px;text-indent: -20px;}
                                            .p78{text-align: left;padding-left: 22px;margin-top: 3px;margin-bottom: 0px;}
                                            .p79{text-align: justify;padding-left: 70px;padding-right: 63px;margin-top: 6px;margin-bottom: 0px;text-indent: -22px;}
                                            .p80{text-align: justify;padding-left: 48px;margin-top: 2px;margin-bottom: 0px;}
                                            .p81{text-align: justify;padding-left: 23px;padding-right: 100px;margin-top: 7px;margin-bottom: 0px;text-indent: -23px;}
                                            .p82{text-align: left;padding-left: 22px;padding-right: 48px;margin-top: 3px;margin-bottom: 0px;}
                                            .p83{text-align: left;padding-right: 58px;margin-top: 10px;margin-bottom: 0px;}
                                            .p84{text-align: left;padding-left: 3px;margin-bottom: 0px;}
                                            .p85{text-align: left;padding-right: 73px;margin-top: 9px;margin-bottom: 0px;}
                                            .p86{text-align: left;padding-right: 77px;margin-top: 0px;margin-bottom: 0px;}
                                            .p87{text-align: left;padding-right: 58px;margin-top: 5px;margin-bottom: 0px;}
                                            .p88{text-align: left;margin-top: 7px;margin-bottom: 0px;}
                                            .p89{text-align: left;margin-top: 3px;margin-bottom: 0px;}
                                            .p90{text-align: left;padding-left: 3px;margin-top: 24px;margin-bottom: 0px;}
                                            .p91{text-align: left;padding-right: 57px;margin-top: 10px;margin-bottom: 0px;}
                                            .p92{text-align: left;padding-right: 59px;margin-top: 7px;margin-bottom: 0px;}
                                            .p93{text-align: left;padding-left: 3px;margin-top: 22px;margin-bottom: 0px;}
                                            .p94{text-align: left;padding-right: 82px;margin-top: 11px;margin-bottom: 0px;}
                                            .p95{text-align: left;padding-right: 53px;margin-top: 4px;margin-bottom: 0px;}
                                            .p96{text-align: left;padding-right: 65px;margin-top: 6px;margin-bottom: 0px;}
                                            .p97{text-align: left;padding-right: 54px;margin-top: 8px;margin-bottom: 0px;}
                                            .p98{text-align: left;padding-right: 52px;margin-top: 11px;margin-bottom: 0px;}
                                            .p99{text-align: left;padding-left: 215px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p100{text-align: left;padding-right: 82px;margin-top: 1px;margin-bottom: 0px;}
                                            .p101{text-align: left;padding-left: 3px;padding-right: 71px;margin-top: 7px;margin-bottom: 0px;}
                                            .p102{text-align: left;padding-left: 5px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p103{text-align: left;padding-left: 2px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p104{text-align: left;padding-left: 3px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p105{text-align: center;padding-right: 31px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p106{text-align: left;padding-left: 23px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p107{text-align: left;padding-left: 143px;margin-top: 0px;margin-bottom: 0px;}
                                            .p108{text-align: justify;padding-right: 107px;margin-top: 15px;margin-bottom: 0px;}
                                            .p109{text-align: left;padding-left: 21px;margin-top: 6px;margin-bottom: 0px;}
                                            .p110{text-align: left;padding-left: 21px;margin-top: 9px;margin-bottom: 0px;}
                                            .p111{text-align: left;padding-left: 21px;margin-top: 12px;margin-bottom: 0px;}
                                            .p112{text-align: left;padding-left: 118px;margin-top: 2px;margin-bottom: 0px;}
                                            .p113{text-align: left;margin-top: 4px;margin-bottom: 0px;}
                                            .p114{text-align: left;padding-left: 13px;padding-right: 43px;margin-top: 14px;margin-bottom: 0px;}
                                            .p115{text-align: left;padding-left: 27px;margin-top: 8px;margin-bottom: 0px;}
                                            .p116{text-align: left;padding-left: 27px;margin-top: 13px;margin-bottom: 0px;}
                                            .p117{text-align: left;padding-left: 37px;margin-top: 0px;margin-bottom: 0px;}
                                            .p118{text-align: left;padding-left: 35px;margin-top: 0px;margin-bottom: 0px;}
                                            .p119{text-align: left;padding-left: 4px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p120{text-align: left;padding-left: 3px;padding-right: 87px;margin-top: 14px;margin-bottom: 0px;}
                                            .p121{text-align: left;padding-right: 60px;margin-top: 8px;margin-bottom: 0px;}
                                            .p122{text-align: left;padding-left: 54px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p123{text-align: left;padding-left: 1px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p124{text-align: left;padding-left: 272px;margin-top: 10px;margin-bottom: 0px;}
                                            .p125{text-align: left;padding-left: 5px;margin-top: 0px;margin-bottom: 0px;}
                                            .p126{text-align: left;padding-left: 6px;padding-right: 52px;margin-top: 3px;margin-bottom: 0px;}
                                            .p127{text-align: left;padding-left: 6px;margin-top: 11px;margin-bottom: 0px;}
                                            .p128{text-align: left;padding-left: 89px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p129{text-align: center;padding-right: 120px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p130{text-align: left;padding-left: 14px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p131{text-align: center;padding-right: 119px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p132{text-align: left;padding-left: 48px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p133{text-align: center;padding-right: 7px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p134{text-align: left;padding-left: 1px;margin-top: 4px;margin-bottom: 0px;}
                                            .p135{text-align: left;padding-left: 1px;padding-right: 55px;margin-top: 4px;margin-bottom: 0px;}
                                            .p136{text-align: left;padding-left: 4px;margin-top: 8px;margin-bottom: 0px;}
                                            .p137{text-align: justify;padding-left: 20px;padding-right: 88px;margin-top: 9px;margin-bottom: 0px;text-indent: -16px;}
                                            .p138{text-align: left;padding-left: 1px;padding-right: 60px;margin-top: 4px;margin-bottom: 0px;}
                                            .p139{text-align: left;padding-left: 213px;margin-top: 0px;margin-bottom: 0px;}
                                            .p140{text-align: left;padding-left: 223px;margin-top: 4px;margin-bottom: 0px;}
                                            .p141{text-align: left;padding-left: 214px;margin-top: 15px;margin-bottom: 0px;}
                                            .p142{text-align: left;padding-left: 138px;margin-top: 1px;margin-bottom: 0px;}
                                            .p143{text-align: left;padding-left: 81px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p144{text-align: left;padding-left: 71px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p145{text-align: left;padding-left: 64px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p146{text-align: center;padding-left: 7px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p147{text-align: left;padding-left: 20px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p148{text-align: center;padding-left: 8px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p149{text-align: left;padding-left: 17px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p150{text-align: left;padding-left: 203px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p151{text-align: right;padding-right: 4px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p152{text-align: right;padding-right: 8px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p153{text-align: right;padding-right: 10px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p154{text-align: left;padding-left: 8px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p155{text-align: left;padding-left: 6px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p156{text-align: left;padding-left: 21px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p157{text-align: left;padding-left: 40px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p158{text-align: center;padding-left: 6px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p159{text-align: left;padding-left: 41px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p160{text-align: center;padding-right: 2px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p161{text-align: center;padding-right: 21px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p162{text-align: left;padding-left: 9px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p163{text-align: center;padding-right: 18px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p164{text-align: left;padding-left: 19px;margin-top: 19px;margin-bottom: 0px;}
                                            .p165{text-align: left;padding-left: 131px;padding-right: 87px;margin-top: 20px;margin-bottom: 0px;text-indent: -91px;}

                                            .td0{padding: 0px;margin: 0px;width: 4px;vertical-align: bottom;}
                                            .td1{padding: 0px;margin: 0px;width: 610px;vertical-align: bottom;}
                                            .td2{padding: 0px;margin: 0px;width: 106px;vertical-align: bottom;}
                                            .td3{padding: 0px;margin: 0px;width: 102px;vertical-align: bottom;}
                                            .td4{padding: 0px;margin: 0px;width: 610px;vertical-align: bottom;background: #000000;}
                                            .td5{padding: 0px;margin: 0px;width: 102px;vertical-align: bottom;background: #000000;}
                                            .td6{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 4px;vertical-align: bottom;}
                                            .td7{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 610px;vertical-align: bottom;}
                                            .td8{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 102px;vertical-align: bottom;}
                                            .td9{padding: 0px;margin: 0px;width: 234px;vertical-align: bottom;}
                                            .td10{padding: 0px;margin: 0px;width: 431px;vertical-align: bottom;}
                                            .td11{padding: 0px;margin: 0px;width: 55px;vertical-align: bottom;}
                                            .td12{padding: 0px;margin: 0px;width: 666px;vertical-align: bottom;}
                                            .td13{padding: 0px;margin: 0px;width: 54px;vertical-align: bottom;}
                                            .td14{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 236px;vertical-align: bottom;}
                                            .td15{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 126px;vertical-align: bottom;}
                                            .td16{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 69px;vertical-align: bottom;}
                                            .td17{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 63px;vertical-align: bottom;}
                                            .td18{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 225px;vertical-align: bottom;}
                                            .td19{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 236px;vertical-align: bottom;}
                                            .td20{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 41px;vertical-align: bottom;}
                                            .td21{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 81px;vertical-align: bottom;}
                                            .td22{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 69px;vertical-align: bottom;}
                                            .td23{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 63px;vertical-align: bottom;}
                                            .td24{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 52px;vertical-align: bottom;}
                                            .td25{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 32px;vertical-align: bottom;}
                                            .td26{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;}
                                            .td27{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 13px;vertical-align: bottom;}
                                            .td28{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 111px;vertical-align: bottom;}
                                            .td29{padding: 0px;margin: 0px;width: 236px;vertical-align: bottom;}
                                            .td30{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 40px;vertical-align: bottom;}
                                            .td31{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 80px;vertical-align: bottom;}
                                            .td32{padding: 0px;margin: 0px;width: 69px;vertical-align: bottom;}
                                            .td33{padding: 0px;margin: 0px;width: 64px;vertical-align: bottom;}
                                            .td34{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 51px;vertical-align: bottom;}
                                            .td35{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 61px;vertical-align: bottom;}
                                            .td36{padding: 0px;margin: 0px;width: 111px;vertical-align: bottom;}
                                            .td37{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 276px;vertical-align: bottom;}
                                            .td38{padding: 0px;margin: 0px;width: 32px;vertical-align: bottom;}
                                            .td39{padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;}
                                            .td40{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 12px;vertical-align: bottom;}
                                            .td41{padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;background: #dcdcdc;}
                                            .td42{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 40px;vertical-align: bottom;}
                                            .td43{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 80px;vertical-align: bottom;}
                                            .td44{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 64px;vertical-align: bottom;}
                                            .td45{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 51px;vertical-align: bottom;}
                                            .td46{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 12px;vertical-align: bottom;}
                                            .td47{padding: 0px;margin: 0px;width: 362px;vertical-align: bottom;}
                                            .td48{padding: 0px;margin: 0px;width: 52px;vertical-align: bottom;}
                                            .td49{padding: 0px;margin: 0px;width: 173px;vertical-align: top;}
                                            .td50{border-left: #000000 1px solid;border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 501px;vertical-align: bottom;}
                                            .td51{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 217px;vertical-align: bottom;}
                                            .td52{border-left: #000000 1px solid;border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 501px;vertical-align: bottom;}
                                            .td53{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 217px;vertical-align: bottom;}
                                            .td54{border-left: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 362px;vertical-align: bottom;}
                                            .td55{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 193px;vertical-align: bottom;}
                                            .td56{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;}
                                            .td57{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 155px;vertical-align: bottom;}
                                            .td58{border-left: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 362px;vertical-align: bottom;}
                                            .td59{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 193px;vertical-align: bottom;}
                                            .td60{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;}
                                            .td61{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 23px;vertical-align: bottom;}
                                            .td62{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 6px;vertical-align: bottom;}
                                            .td63{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 109px;vertical-align: bottom;}
                                            .td64{border-left: #000000 1px solid;padding: 0px;margin: 0px;width: 362px;vertical-align: bottom;}
                                            .td65{padding: 0px;margin: 0px;width: 193px;vertical-align: bottom;}
                                            .td66{padding: 0px;margin: 0px;width: 9px;vertical-align: bottom;}
                                            .td67{padding: 0px;margin: 0px;width: 23px;vertical-align: bottom;}
                                            .td68{padding: 0px;margin: 0px;width: 6px;vertical-align: bottom;}
                                            .td69{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 109px;vertical-align: bottom;}
                                            .td70{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 9px;vertical-align: bottom;}
                                            .td71{border-left: #000000 1px solid;border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 361px;vertical-align: bottom;}
                                            .td72{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 192px;vertical-align: bottom;}
                                            .td73{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 54px;vertical-align: bottom;}
                                            .td74{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 5px;vertical-align: bottom;}
                                            .td75{border-left: #000000 1px solid;border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 361px;vertical-align: bottom;}
                                            .td76{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 192px;vertical-align: bottom;}
                                            .td77{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 5px;vertical-align: bottom;}
                                            .td78{padding: 0px;margin: 0px;width: 227px;vertical-align: bottom;}
                                            .td79{padding: 0px;margin: 0px;width: 81px;vertical-align: bottom;}
                                            .td80{padding: 0px;margin: 0px;width: 157px;vertical-align: bottom;}
                                            .td81{padding: 0px;margin: 0px;width: 108px;vertical-align: bottom;}
                                            .td82{padding: 0px;margin: 0px;width: 150px;vertical-align: bottom;}
                                            .td83{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 227px;vertical-align: bottom;}
                                            .td84{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 7px;vertical-align: bottom;}
                                            .td85{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 65px;vertical-align: bottom;}
                                            .td86{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 157px;vertical-align: bottom;}
                                            .td87{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 14px;vertical-align: bottom;}
                                            .td88{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 235px;vertical-align: bottom;}
                                            .td89{border-left: #000000 1px solid;padding: 0px;margin: 0px;width: 226px;vertical-align: bottom;}
                                            .td90{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 6px;vertical-align: bottom;}
                                            .td91{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;background: #c0c0c0;}
                                            .td92{padding: 0px;margin: 0px;width: 222px;vertical-align: bottom;}
                                            .td93{padding: 0px;margin: 0px;width: 14px;vertical-align: bottom;}
                                            .td94{padding: 0px;margin: 0px;width: 85px;vertical-align: bottom;}
                                            .td95{padding: 0px;margin: 0px;width: 145px;vertical-align: bottom;}
                                            .td96{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 4px;vertical-align: bottom;}
                                            .td97{border-left: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 226px;vertical-align: bottom;}
                                            .td98{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 6px;vertical-align: bottom;}
                                            .td99{border-right: #000000 1px solid;border-bottom: #c0c0c0 1px solid;padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;background: #c0c0c0;}
                                            .td100{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 222px;vertical-align: bottom;}
                                            .td101{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 80px;vertical-align: bottom;}
                                            .td102{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 5px;vertical-align: bottom;}
                                            .td103{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 145px;vertical-align: bottom;}
                                            .td104{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 4px;vertical-align: bottom;}
                                            .td105{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 150px;vertical-align: bottom;}
                                            .td106{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 234px;vertical-align: bottom;}
                                            .td107{padding: 0px;margin: 0px;width: 65px;vertical-align: bottom;}
                                            .td108{padding: 0px;margin: 0px;width: 80px;vertical-align: bottom;}
                                            .td109{padding: 0px;margin: 0px;width: 5px;vertical-align: bottom;}
                                            .td110{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 79px;vertical-align: bottom;}
                                            .td111{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 144px;vertical-align: bottom;}
                                            .td112{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 144px;vertical-align: bottom;}
                                            .td113{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;background: #c0c0c0;}
                                            .td114{padding: 0px;margin: 0px;width: 316px;vertical-align: bottom;}
                                            .td115{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 34px;vertical-align: bottom;}
                                            .td116{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 1px;vertical-align: bottom;}
                                            .td117{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 58px;vertical-align: bottom;}
                                            .td118{padding: 0px;margin: 0px;width: 292px;vertical-align: bottom;}
                                            .td119{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 239px;vertical-align: bottom;}
                                            .td120{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 77px;vertical-align: bottom;}
                                            .td121{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 108px;vertical-align: bottom;}
                                            .td122{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 31px;vertical-align: bottom;}
                                            .td123{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 18px;vertical-align: bottom;}
                                            .td124{border-left: #000000 1px solid;padding: 0px;margin: 0px;width: 315px;vertical-align: bottom;}
                                            .td125{padding: 0px;margin: 0px;width: 93px;vertical-align: bottom;}
                                            .td126{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 16px;vertical-align: bottom;}
                                            .td127{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 274px;vertical-align: bottom;}
                                            .td128{border-left: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 238px;vertical-align: bottom;}
                                            .td129{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 82px;vertical-align: bottom;}
                                            .td130{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 30px;vertical-align: bottom;}
                                            .td131{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 110px;vertical-align: bottom;}
                                            .td132{border-left: #000000 1px solid;padding: 0px;margin: 0px;width: 238px;vertical-align: bottom;}
                                            .td133{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 175px;vertical-align: bottom;}
                                            .td134{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 305px;vertical-align: bottom;}
                                            .td135{border-left: #000000 1px solid;padding: 0px;margin: 0px;width: 355px;vertical-align: bottom;}
                                            .td136{padding: 0px;margin: 0px;width: 1px;vertical-align: bottom;}
                                            .td137{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 57px;vertical-align: bottom;}
                                            .td138{padding: 0px;margin: 0px;width: 31px;vertical-align: bottom;}
                                            .td139{padding: 0px;margin: 0px;width: 18px;vertical-align: bottom;}
                                            .td140{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 110px;vertical-align: bottom;}
                                            .td141{padding: 0px;margin: 0px;width: 72px;vertical-align: bottom;}
                                            .td142{padding: 0px;margin: 0px;width: 1px;vertical-align: bottom;background: #000000;}
                                            .td143{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 107px;vertical-align: bottom;}
                                            .td144{padding: 0px;margin: 0px;width: 77px;vertical-align: bottom;}
                                            .td145{padding: 0px;margin: 0px;width: 34px;vertical-align: bottom;}
                                            .td146{padding: 0px;margin: 0px;width: 58px;vertical-align: bottom;}
                                            .td147{padding: 0px;margin: 0px;width: 18px;vertical-align: bottom;background: #dcdcdc;}
                                            .td148{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 1px;vertical-align: bottom;background: #000000;}
                                            .td149{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 107px;vertical-align: bottom;}
                                            .td150{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 433px;vertical-align: bottom;}
                                            .td151{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 223px;vertical-align: bottom;}
                                            .td152{padding: 0px;margin: 0px;width: 433px;vertical-align: bottom;}
                                            .td153{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 63px;vertical-align: bottom;}
                                            .td154{padding: 0px;margin: 0px;width: 223px;vertical-align: bottom;}
                                            .td155{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 290px;vertical-align: bottom;}
                                            .td156{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 251px;vertical-align: bottom;}
                                            .td157{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 176px;vertical-align: bottom;}
                                            .td158{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 290px;vertical-align: bottom;}
                                            .td159{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 251px;vertical-align: bottom;}
                                            .td160{padding: 0px;margin: 0px;width: 176px;vertical-align: bottom;}
                                            .td161{border-left: #000000 1px solid;border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 289px;vertical-align: bottom;}
                                            .td162{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 131px;vertical-align: bottom;}
                                            .td163{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 297px;vertical-align: bottom;}
                                            .td164{border-left: #000000 1px solid;border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 289px;vertical-align: bottom;}
                                            .td165{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 131px;vertical-align: bottom;}
                                            .td166{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 297px;vertical-align: bottom;}
                                            .td167{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 291px;vertical-align: bottom;}
                                            .td168{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 132px;vertical-align: bottom;}
                                            .td169{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 298px;vertical-align: bottom;}
                                            .td170{border-left: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 19px;vertical-align: bottom;}
                                            .td171{border-right: #c0c0c0 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 215px;vertical-align: bottom;}
                                            .td172{border-right: #c0c0c0 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;background: #c0c0c0;}
                                            .td173{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 20px;vertical-align: bottom;}
                                            .td174{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 218px;vertical-align: bottom;}
                                            .td175{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 28px;vertical-align: bottom;}
                                            .td176{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 200px;vertical-align: bottom;}
                                            .td177{border-left: #000000 1px solid;padding: 0px;margin: 0px;width: 19px;vertical-align: bottom;}
                                            .td178{border-right: #c0c0c0 1px solid;padding: 0px;margin: 0px;width: 215px;vertical-align: bottom;}
                                            .td179{border-right: #c0c0c0 1px solid;padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;background: #c0c0c0;}
                                            .td180{padding: 0px;margin: 0px;width: 20px;vertical-align: bottom;}
                                            .td181{padding: 0px;margin: 0px;width: 218px;vertical-align: bottom;}
                                            .td182{padding: 0px;margin: 0px;width: 28px;vertical-align: bottom;}
                                            .td183{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 200px;vertical-align: bottom;}
                                            .td184{border-right: #c0c0c0 1px solid;border-bottom: #c0c0c0 1px solid;padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;background: #c0c0c0;}
                                            .td185{padding: 0px;margin: 0px;width: 246px;vertical-align: bottom;}
                                            .td186{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 215px;vertical-align: bottom;}
                                            .td187{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;background: #c0c0c0;}
                                            .td188{border-left: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 19px;vertical-align: bottom;}
                                            .td189{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 215px;vertical-align: bottom;}
                                            .td190{border-right: #000000 1px solid;border-bottom: #c0c0c0 1px solid;padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;background: #c0c0c0;}
                                            .td191{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 217px;vertical-align: bottom;}
                                            .td192{border-left: #000000 1px solid;border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 234px;vertical-align: bottom;}
                                            .td193{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;background: #c0c0c0;}
                                            .td194{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 215px;vertical-align: bottom;}
                                            .td195{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 20px;vertical-align: bottom;}
                                            .td196{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 28px;vertical-align: bottom;}
                                            .td197{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 200px;vertical-align: bottom;}
                                            .td198{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 237px;vertical-align: bottom;}
                                            .td199{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 228px;vertical-align: bottom;}
                                            .td200{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;background: #c0c0c0;}

                                            .tr0{height: 28px;}
                                            .tr1{height: 16px;}
                                            .tr2{height: 30px;}
                                            .tr3{height: 14px;}
                                            .tr4{height: 19px;}
                                            .tr5{height: 2px;}
                                            .tr6{height: 8px;}
                                            .tr7{height: 3px;}
                                            .tr8{height: 34px;}
                                            .tr9{height: 18px;}
                                            .tr10{height: 29px;}
                                            .tr11{height: 13px;}
                                            .tr12{height: 20px;}
                                            .tr13{height: 7px;}
                                            .tr14{height: 4px;}
                                            .tr15{height: 21px;}
                                            .tr16{height: 22px;}
                                            .tr17{height: 9px;}
                                            .tr18{height: 17px;}
                                            .tr19{height: 23px;}
                                            .tr20{height: 15px;}
                                            .tr21{height: 11px;}
                                            .tr22{height: 10px;}
                                            .tr23{height: 5px;}
                                            .tr24{height: 6px;}
                                            .tr25{height: 27px;}
                                            .tr26{height: 12px;}
                                            .tr27{height: 26px;}

                                            .t0{width: 720px;font: bold 14px 'Times New Roman';}
                                            .t1{width: 720px;margin-top: 11px;font: bold 12px 'Times New Roman';}
                                            .t2{width: 720px;font: 12px 'Times New Roman';}
                                            .t3{width: 720px;margin-top: 1px;font: 11px 'Arial';}
                                            .t4{width: 720px;margin-top: 11px;font: 11px 'Arial';}
                                            .t5{width: 720px;margin-top: 7px;font: 11px 'Arial';}
                                            .t6{width: 723px;margin-top: 5px;font: 11px 'Arial';}
                                            .t7{width: 721px;margin-left: 1px;font: 11px 'Arial';}
                                            .t8{width: 720px;margin-left: 1px;font: 11px 'Arial';}
                                            .t9{width: 719px;margin-left: 1px;font: 10px 'Arial';}
                                            .t10{width: 720px;margin-left: 1px;margin-top: 2px;font: 11px 'Arial';}
                                            .t11{width: 720px;margin-top: 10px;font: 12px 'Arial';}
                                        </STYLE>
                                        <?php echo form_open('', array('id' => 'i9form')); ?>
                                        <DIV id="page_1">
                                            <TABLE cellpadding=0 cellspacing=0 class="t0">
                                                <TR>
                                                    <TD class="tr0 td0"><P class="p0 ft0">&nbsp;</P></TD>
                                                    <TD class="tr0 td1"><P class="p1 ft1">Instructions for Employment Eligibility Verification</P></TD>
                                                    <TD colspan=2 class="tr0 td2"><P class="p2 ft2">USCIS</P></TD>
                                                </TR>
                                                <TR>
                                                    <TD class="tr1 td0"><P class="p0 ft3">&nbsp;</P></TD>
                                                    <TD rowspan=2 class="tr2 td1"><P class="p3 ft4">Department of Homeland Security</P></TD>
                                                    <TD colspan=2 class="tr1 td2"><P class="p2 ft5">Form <NOBR>I-9</NOBR></P></TD>
                                                </TR>
                                                <TR>
                                                    <TD class="tr3 td0"><P class="p0 ft6">&nbsp;</P></TD>
                                                    <TD colspan=2 class="tr3 td2"><P class="p4 ft7">OMB No. <NOBR>1615-0047</NOBR></P></TD>
                                                </TR>
                                                <TR>
                                                    <TD class="tr4 td0"><P class="p0 ft0">&nbsp;</P></TD>
                                                    <TD class="tr4 td1"><P class="p3 ft8">U.S. Citizenship and Immigration Services</P></TD>
                                                    <TD colspan=2 class="tr4 td2"><P class="p2 ft9">Expires 03/31/2016</P></TD>
                                                </TR>
                                                <TR>
                                                    <TD class="tr5 td0"><P class="p0 ft10">&nbsp;</P></TD>
                                                    <TD class="tr5 td1"><P class="p0 ft10">&nbsp;</P></TD>
                                                    <TD class="tr5 td3"><P class="p0 ft10">&nbsp;</P></TD>
                                                    <TD class="tr5 td0"><P class="p0 ft10">&nbsp;</P></TD>
                                                </TR>
                                                <TR>
                                                    <TD class="tr6 td0"><P class="p0 ft11">&nbsp;</P></TD>
                                                    <TD class="tr6 td4"><P class="p0 ft11">&nbsp;</P></TD>
                                                    <TD class="tr6 td5"><P class="p0 ft11">&nbsp;</P></TD>
                                                    <TD class="tr6 td0"><P class="p0 ft11">&nbsp;</P></TD>
                                                </TR>
                                                <TR>
                                                    <TD class="tr7 td6"><P class="p0 ft12">&nbsp;</P></TD>
                                                    <TD class="tr7 td7"><P class="p0 ft12">&nbsp;</P></TD>
                                                    <TD class="tr7 td8"><P class="p0 ft12">&nbsp;</P></TD>
                                                    <TD class="tr7 td6"><P class="p0 ft12">&nbsp;</P></TD>
                                                </TR>
                                            </TABLE>
                                            <P class="p5 ft13">Read all instructions carefully before completing this form.</P>
                                            <P class="p6 ft16"><NOBR><SPAN class="ft14">Anti-Discrimination</SPAN></NOBR><SPAN class="ft14"> Notice. </SPAN>It is illegal to discriminate against any <NOBR>work-authorized</NOBR> individual in hiring, discharge, recruitment or referral for a fee, or in the employment eligibility verification (Form <NOBR>I-9</NOBR> and <NOBR>E-Verify)</NOBR> process based on that individual's citizenship status, immigration status or national origin. Employers <SPAN class="ft14">CANNOT </SPAN>specify which document(s) they will accept from an employee. The refusal to hire an individual because the documentation presented has a future expiration date may also constitute illegal discrimination. For more information, call the Office of Special Counsel for <NOBR>Immigration-Related</NOBR> Unfair Employment Practices (OSC) at <NOBR>1-800-255-7688</NOBR> (employees), <NOBR>1-800-255-8155</NOBR> (employers), or <NOBR>1-800-237-2515</NOBR> (TDD), or visit <SPAN class="ft15">www.justice.gov/crt/about/osc</SPAN>.</P>
                                            <P class="p7 ft17 paragraph-heading">What Is the Purpose of This Form?</P>
                                            <P class="p8 ft16">Employers must complete Form <NOBR>I-9</NOBR> to document verification of the identity and employment authorization of each new employee (both citizen and noncitizen) hired after November 6, 1986, to work in the United States. In the Commonwealth of the Northern Mariana Islands (CNMI), employers must complete Form <NOBR>I-9</NOBR> to document verification of the identity and employment authorization of each new employee (both citizen and noncitizen) hired after November 27, 2011. Employers should have used Form <NOBR>I-9</NOBR> CNMI between November 28, 2009 and November 27, 2011.</P>
                                            <P class="p9 ft17 paragraph-heading">General Instructions</P>
                                            <P class="p10 ft16">Employers are responsible for completing and retaining Form <NOBR>I-9.</NOBR> For the purpose of completing this form, the term "employer" means all employers, including those recruiters and referrers for a fee who are agricultural associations, agricultural employers, or farm labor contractors.</P>
                                            <P class="p11 ft16">Form <NOBR>I-9</NOBR> is made up of three sections. Employers may be fined if the form is not complete. Employers are responsible for retaining completed forms. Do not mail completed forms to U.S. Citizenship and Immigration Services (USCIS) or Immigration and Customs Enforcement (ICE).</P>
                                            <P class="p12 ft17 paragraph-heading">Section 1. Employee Information and Attestation</P>
                                            <P class="p13 ft19">Newly hired employees must complete and sign Section 1 of Form <NOBR>I-9</NOBR> <SPAN class="ft18">no later than the first day of employment</SPAN>. Section 1 should never be completed before the employee has accepted a job offer.</P>
                                            <P class="p14 ft8">Provide the following information to complete Section 1:</P>
                                            <P class="p15 ft16"><SPAN class="ft14">Name: </SPAN>Provide your full legal last name, first name, and middle initial. Your last name is your family name or surname. If you have two last names or a hyphenated last name, include both names in the last name field. Your first name is your given name. Your middle initial is the first letter of your second given name, or the first letter of your middle name, if any.</P>
                                            <P class="p16 ft21"><SPAN class="ft20">Other names used: </SPAN>Provide all other names used, if any (including maiden name). If you have had no other legal names, write "N/A."</P>
                                            <P class="p17 ft16"><SPAN class="ft14">Address: </SPAN>Provide the address where you currently live, including Street Number and Name, Apartment Number (if applicable), City, State, and Zip Code. Do not provide a post office box address (P.O. Box). Only border commuters from Canada or Mexico may use an international address in this field.</P>
                                            <P class="p18 ft21"><SPAN class="ft20">Date of Birth: </SPAN>Provide your date of birth in the mm/dd/yyyy format. For example, January 23, 1950, should be written as 01/23/1950.</P>
                                            <P class="p19 ft19"><SPAN class="ft18">U.S. Social Security Number: </SPAN>Provide your <NOBR>9-digit</NOBR> Social Security number. Providing your Social Security number is voluntary. However, if your employer participates in <NOBR>E-Verify,</NOBR> you must provide your Social Security number.</P>
                                            <P class="p20 ft16"><NOBR><SPAN class="ft14">E-mail</SPAN></NOBR><SPAN class="ft14"> Address and Telephone Number (Optional): </SPAN>You may provide your <NOBR>e-mail</NOBR> address and telephone number. Department of Homeland Security <SPAN class="ft14">(</SPAN>DHS) may contact you if DHS learns of a potential mismatch between</P>
                                            <P class="p21 ft21">the information provided and the information in DHS or Social Security Administration (SSA) records. You may write "N/A" if you choose not to provide this information.</P>
                                            <TABLE cellpadding=0 cellspacing=0 class="t1">
                                                <TR>
                                                    <TD rowspan=2 class="tr8 td9"><P class="p0 ft9">Form <NOBR>I-9</NOBR> Instructions 03/08/13 N</P></TD>
                                                <TD class="tr9 td10"><P class="p22 ft22">EMPLOYERS MUST RETAIN COMPLETED FORM <NOBR>I-9</NOBR></P></TD>
                                                <TD rowspan=2 class="tr8 td11"><P class="p0 ft9">Page 1 of 9</P></TD>
                                                </TR>
                                                <TR>
                                                    <TD class="tr1 td10"><P class="p23 ft22">DO NOT MAIL COMPLETED FORM <NOBR>I-9</NOBR> TO ICE OR USCIS</P></TD>
                                                </TR>
                                            </TABLE>
                                        </DIV>
                                        <DIV id="page_2">
                                            <DIV id="dimg1">
                                                <IMG src="<?php echo site_url('assets/images/i9form_images/i9form2x1.jpg'); ?>" id="img1">
                                            </DIV>
                                            <DIV class="dclr"></DIV>
                                            <DIV id="id_1">
                                                <P class="p24 ft16">All employees must attest in Section 1, under penalty of perjury, to their citizenship or immigration status by checking one of the following four boxes provided on the form:</P>
                                                <P class="p25 ft2"><SPAN class="ft4">1.</SPAN><SPAN class="ft23">A citizen of the United States</SPAN></P>
                                                <P class="p26 ft21"><SPAN class="ft4">2.</SPAN><SPAN class="ft24">A noncitizen national of the United States: </SPAN>Noncitizen nationals of the United States are persons born in American Samoa, certain former citizens of the former Trust Territory of the Pacific Islands, and certain children of noncitizen nationals born abroad.</P>
                                                <P class="p27 ft16"><SPAN class="ft4">3.</SPAN><SPAN class="ft25">A lawful permanent resident: </SPAN>A lawful permanent resident is any person who is not a U.S. citizen and who resides in the United States under legally recognized and lawfully recorded permanent residence as an immigrant. The term "lawful permanent resident" includes conditional residents. If you check this box, write either your Alien Registration Number <NOBR>(A-Number)</NOBR> or USCIS Number in the field next to your selection. At this time, the USCIS Number is the same as the <NOBR>A-Number</NOBR> without the "A" prefix.</P>
                                                <P class="p28 ft21"><SPAN class="ft4">4.</SPAN><SPAN class="ft24">An alien authorized to work: </SPAN>If you are not a citizen or national of the United States or a lawful permanent resident, but are authorized to work in the United States, check this box.</P>
                                                <P class="p29 ft8">If you check this box:</P>
                                                <P class="p30 ft21"><SPAN class="ft2">a.</SPAN><SPAN class="ft26">Record the date that your employment authorization expires, if any. Aliens whose employment authorization does not expire, such as refugees, asylees, and certain citizens of the Federated States of Micronesia, the Republic of the Marshall Islands, or Palau, may write "N/A" on this line.</SPAN></P>
                                                <P class="p31 ft16"><SPAN class="ft2">b.</SPAN><SPAN class="ft27">Next, enter your Alien Registration Number </SPAN><NOBR>(A-Number)/USCIS</NOBR> Number. At this time, the USCIS Number is the same as your <NOBR>A-Number</NOBR> without the "A" prefix. If you have not received an <NOBR>A-Number/USCIS</NOBR> Number, record your Admission Number. You can find your Admission Number on Form <NOBR>I-94,</NOBR> <NOBR>"Arrival-Departure</NOBR> Record," or as directed by USCIS or U.S. Customs and Border Protection (CBP).</P>
                                                <P class="p32 ft21"><SPAN class="ft4">(1)</SPAN><SPAN class="ft28">If you obtained your admission number from CBP in connection with your arrival in the United States, then also record information about the foreign passport you used to enter the United States (number and country of issuance).</SPAN></P>
                                                <P class="p33 ft16"><SPAN class="ft4">(2)</SPAN><SPAN class="ft29">If you obtained your admission number from USCIS </SPAN><SPAN class="ft30">within the United States</SPAN>, or you entered the United States without a foreign passport, you must write "N/A" in the Foreign Passport Number and Country of Issuance fields.</P>
                                                <P class="p34 ft16">Sign your name in the "Signature of Employee" block and record the date you completed and signed Section 1. By signing and dating this form, you attest that the citizenship or immigration status you selected is correct and that you are aware that you may be imprisoned and/or fined for making false statements or using false documentation when completing this form. To fully complete this form, you must present to your employer documentation that establishes your identity and employment authorization. Choose which documents to present from the Lists of Acceptable Documents, found on the last page of this form. You must present this documentation no later than the third day after beginning employment, although you may present the required documentation before this date.</P>
                                                <P class="p35 ft2">Preparer and/or Translator Certification</P>
                                                <P class="p36 ft16">The Preparer and/or Translator Certification must be completed if the employee requires assistance to complete Section 1 (e.g., the employee needs the instructions or responses translated, someone other than the employee fills out the information blocks, or someone with disabilities needs additional assistance). The employee must still sign Section 1.</P>
                                                <P class="p37 ft2">Minors and Certain Employees with Disabilities (Special Placement)</P>
                                                <P class="p38 ft16">Parents or legal guardians assisting minors (individuals under 18) and certain employees with disabilities should review the guidelines in the <SPAN class="ft30">Handbook for Employers: Instructions for Completing Form </SPAN><NOBR><SPAN class="ft30">I-9</SPAN></NOBR><SPAN class="ft30"> </SPAN><NOBR><SPAN class="ft30">(M-274)</SPAN></NOBR><SPAN class="ft30"> </SPAN>on <SPAN class="ft15">www.uscis.gov/ </SPAN><NOBR><SPAN class="ft15">I-9Central</SPAN></NOBR><SPAN class="ft15"> </SPAN>before completing Section 1. These individuals have special procedures for establishing identity if they cannot present an identity document for Form <NOBR>I-9.</NOBR> The special procedures include <SPAN class="ft14">(1) </SPAN>the parent or legal guardian filling out Section 1 and writing "minor under age 18" or "special placement," whichever applies, in the employee signature block; and <SPAN class="ft14">(2) </SPAN>the employer writing "minor under age 18" or "special placement" under List B in Section 2.</P>
                                            </DIV>
                                            <DIV id="id_2">
                                                <TABLE cellpadding=0 cellspacing=0 class="t2">
                                                    <TR>
                                                        <TD class="tr1 td12"><P class="p0 ft9">Form <NOBR>I-9</NOBR> Instructions 03/08/13 N</P></TD>
                                                    <TD class="tr1 td13"><P class="p0 ft9">Page 2 of 9</P></TD>
                                                    </TR>
                                                </TABLE>
                                            </DIV>
                                        </DIV>
                                        <DIV id="page_3">
                                            <DIV id="dimg1">
                                                <IMG src="<?php echo site_url('assets/images/i9form_images/i9form6x1.jpg'); ?>" id="img1">
                                            </DIV>
                                            <DIV class="dclr"></DIV>
                                            <DIV id="id_1">
                                                <P class="p39 ft17 paragraph-heading">Section 2. Employer or Authorized Representative Review and Verification</P>
                                                <P class="p40 ft21">Before completing Section 2, employers must ensure that Section 1 is completed properly and on time. Employers may not ask an individual to complete Section 1 before he or she has accepted a job offer.</P>
                                                <P class="p41 ft16">Employers or their authorized representative must complete Section 2 by examining evidence of identity and employment authorization within 3 business days of the employee's first day of employment. For example, if an employee begins employment on Monday, the employer must complete Section 2 by Thursday of that week. However, if an employer hires an individual for less than 3 business days, Section 2 must be completed no later than the first day of employment. An employer may complete Form <NOBR>I-9</NOBR> before the first day of employment if the employer has offered the individual a job and the individual has accepted.</P>
                                                <P class="p42 ft16">Employers cannot specify which document(s) employees may present from the Lists of Acceptable Documents, found on the last page of Form <NOBR>I-9,</NOBR> to establish identity and employment authorization. Employees must present one selection from List A <SPAN class="ft14">OR </SPAN>a combination of one selection from List B and one selection from List C. List A contains documents that show both identity and employment authorization. Some List A documents are combination documents. The employee must present combination documents together to be considered a List A document. For example, a foreign passport and a Form <NOBR>I-94</NOBR> containing an endorsement of the alien's nonimmigrant status must be presented together to be considered a List A document. List B contains documents that show identity only, and List C contains documents that show employment authorization only. If an employee presents a List A document, he or she should <SPAN class="ft14">not </SPAN>present a List B and List C document, and vice versa. If an employer participates in <NOBR>E-Verify,</NOBR> the List B document must include a photograph.</P>
                                                <P class="p43 ft21">In the field below the Section 2 introduction, employers must enter the last name, first name and middle initial, if any, that the employee entered in Section 1. This will help to identify the pages of the form should they get separated.</P>
                                                <P class="p44 ft8">Employers or their authorized representative must:</P>
                                                <P class="p45 ft16"><SPAN class="ft4">1.</SPAN><SPAN class="ft31">Physically examine each original document the employee presents to determine if it reasonably appears to be genuine and to relate to the person presenting it. The person who examines the documents must be the same person who signs Section 2. The examiner of the documents and the employee must both be physically present during the examination of the employee's documents.</SPAN></P>
                                                <P class="p46 ft21"><SPAN class="ft4">2.</SPAN><SPAN class="ft32">Record the document title shown on the Lists of Acceptable Documents, issuing authority, document number and expiration date (if any) from the original document(s) the employee presents. You may write "N/A" in any unused fields.</SPAN></P>
                                                <P class="p47 ft16">If the employee is a student or exchange visitor who presented a foreign passport with a Form <NOBR>I-94,</NOBR> the employer should also enter in Section 2:</P>
                                                <P class="p48 ft19"><SPAN class="ft18">a. </SPAN>The student's Form <NOBR>I-20</NOBR> or <NOBR>DS-2019</NOBR> number (Student and Exchange Visitor Information <NOBR>System-SEVIS</NOBR> Number); <SPAN class="ft18">and </SPAN>the program end date from Form <NOBR>I-20</NOBR> or <NOBR>DS-2019.</NOBR></P>
                                                <P class="p49 ft21"><SPAN class="ft4">3.</SPAN><SPAN class="ft32">Under Certification, enter the employee's first day of employment. Temporary staffing agencies may enter the first day the employee was placed in a job pool. Recruiters and recruiters for a fee do not enter the employee's first day of employment.</SPAN></P>
                                                <P class="p50 ft21"><SPAN class="ft4">4.</SPAN><SPAN class="ft32">Provide the name and title of the person completing Section 2 in the Signature of Employer or Authorized Representative field.</SPAN></P>
                                                <P class="p51 ft8"><SPAN class="ft4">5.</SPAN><SPAN class="ft33">Sign and date the attestation on the date Section 2 is completed.</SPAN></P>
                                                <P class="p25 ft8"><SPAN class="ft4">6.</SPAN><SPAN class="ft33">Record the employer's business name and address.</SPAN></P>
                                                <P class="p52 ft8"><SPAN class="ft4">7.</SPAN><SPAN class="ft33">Return the employee's documentation.</SPAN></P>
                                                <P class="p53 ft16">Employers may, but are not required to, photocopy the document(s) presented. If photocopies are made, they should be made for <SPAN class="ft14">ALL </SPAN>new hires or reverifications. Photocopies must be retained and presented with Form <NOBR>I-9</NOBR> in case of an inspection by DHS or other federal government agency. Employers must always complete Section 2 even if they photocopy an employee's document(s). Making photocopies of an employee's document(s) cannot take the place of completing Form <NOBR>I-9.</NOBR> Employers are still responsible for completing and retaining Form <NOBR>I-9.</NOBR></P>
                                            </DIV>
                                            <DIV id="id_2">
                                                <TABLE cellpadding=0 cellspacing=0 class="t2">
                                                    <TR>
                                                        <TD class="tr1 td12"><P class="p0 ft9">Form <NOBR>I-9</NOBR> Instructions 03/08/13 N</P></TD>
                                                    <TD class="tr1 td13"><P class="p0 ft9">Page 3 of 9</P></TD>
                                                    </TR>
                                                </TABLE>
                                            </DIV>
                                        </DIV>
                                        <DIV id="page_4">
                                            <DIV id="dimg1">
                                                <IMG src="<?php echo site_url('assets/images/i9form_images/i9form6x1.jpg'); ?>" id="img1">
                                            </DIV>
                                            <DIV class="dclr"></DIV>
                                            <DIV id="id_1">
                                                <P class="p54 ft2">Unexpired Documents</P>
                                                <P class="p55 ft16">Generally, only unexpired, original documentation is acceptable. The only exception is that an employee may present a certified copy of a birth certificate. Additionally, in some instances, a document that appears to be expired may be acceptable if the expiration date shown on the face of the document has been extended, such as for individuals with temporary protected status. Refer to the <SPAN class="ft30">Handbook for Employers: Instructions for Completing Form </SPAN><NOBR><SPAN class="ft30">I-9</SPAN></NOBR><SPAN class="ft30"> </SPAN><NOBR><SPAN class="ft30">(M-274)</SPAN></NOBR><SPAN class="ft30"> </SPAN>or <NOBR>I-9</NOBR> Central <NOBR>(<SPAN class="ft34">www.uscis.gov/I-9Central</SPAN>)</NOBR> for examples.</P>
                                                <P class="p56 ft2">Receipts</P>
                                                <P class="p57 ft16">If an employee is unable to present a required document (or documents), the employee can present an acceptable receipt in lieu of a document from the Lists of Acceptable Documents on the last page of this form. Receipts showing that a person has applied for an initial grant of employment authorization, or for renewal of employment authorization, are not acceptable. Employers cannot accept receipts if employment will last less than 3 days. Receipts are acceptable when completing Form <NOBR>I-9</NOBR> for a new hire or when reverification is required.</P>
                                                <P class="p41 ft16">Employees must present receipts within 3 business days of their first day of employment, or in the case of reverification, by the date that reverification is required, and must present valid replacement documents within the time frames described below.</P>
                                                <P class="p58 ft8">There are three types of acceptable receipts:</P>
                                                <P class="p59 ft21"><SPAN class="ft4">1.</SPAN><SPAN class="ft32">A receipt showing that the employee has applied to replace a document that was lost, stolen or damaged. The employee must present the actual document within 90 days from the date of hire.</SPAN></P>
                                                <P class="p60 ft21"><SPAN class="ft4">2.</SPAN><SPAN class="ft32">The arrival portion of Form </SPAN><NOBR>I-94/I-94A</NOBR> with a temporary <NOBR>I-551</NOBR> stamp and a photograph of the individual. The employee must present the actual Permanent Resident Card (Form <NOBR>I-551)</NOBR> by the expiration date of the temporary <NOBR>I-551</NOBR> stamp, or, if there is no expiration date, within 1 year from the date of issue.</P>
                                                <P class="p61 ft21"><SPAN class="ft4">3.</SPAN><SPAN class="ft32">The departure portion of Form </SPAN><NOBR>I-94/I-94A</NOBR> with a refugee admission stamp. The employee must present an unexpired Employment Authorization Document (Form <NOBR>I-766)</NOBR> or a combination of a List B document and an unrestricted Social Security card within 90 days.</P>
                                                <P class="p35 ft8">When the employee provides an acceptable receipt, the employer should:</P>
                                                <P class="p25 ft8"><SPAN class="ft4">1.</SPAN><SPAN class="ft33">Record the document title in Section 2 under the sections titled List A, List B, or List C, as applicable.</SPAN></P>
                                                <P class="p62 ft21"><SPAN class="ft4">2.</SPAN><SPAN class="ft32">Write the word "receipt" and its document number in the "Document Number" field. Record the last day that the receipt is valid in the "Expiration Date" field.</SPAN></P>
                                                <P class="p63 ft8">By the end of the receipt validity period, the employer should:</P>
                                                <P class="p52 ft8"><SPAN class="ft4">1.</SPAN><SPAN class="ft33">Cross out the word "receipt" and any accompanying document number and expiration date.</SPAN></P>
                                                <P class="p64 ft8"><SPAN class="ft4">2.</SPAN><SPAN class="ft33">Record the number and other required document information from the actual document presented.</SPAN></P>
                                                <P class="p52 ft8"><SPAN class="ft4">3.</SPAN><SPAN class="ft33">Initial and date the change.</SPAN></P>
                                                <P class="p65 ft36"><SPAN class="ft21">See the </SPAN>Handbook for Employers: Instructions for Completing Form <NOBR>I-9</NOBR> <NOBR>(M-274)</NOBR> <SPAN class="ft21">at </SPAN><NOBR><SPAN class="ft35">www.uscis.gov/I-9Central</SPAN></NOBR><SPAN class="ft35"> </SPAN><SPAN class="ft21">for more information on receipts.</SPAN></P>
                                                <P class="p12 ft17 paragraph-heading">Section 3. Reverification and Rehires</P>
                                                <P class="p66 ft16">Employers or their authorized representatives should complete Section 3 when reverifying that an employee is authorized to work. When rehiring an employee within 3 years of the date Form <NOBR>I-9</NOBR> was originally completed, employers have the option to complete a new Form <NOBR>I-9</NOBR> or complete Section 3. When completing Section 3 in either a reverification or rehire situation, if the employee's name has changed, record the name change in Block A.</P>
                                                <P class="p67 ft21">For employees who provide an employment authorization expiration date in Section 1, employers must reverify employment authorization on or before the date provided.</P>
                                            </DIV>
                                            <DIV id="id_2">
                                                <TABLE cellpadding=0 cellspacing=0 class="t2">
                                                    <TR>
                                                        <TD class="tr1 td12"><P class="p0 ft9">Form <NOBR>I-9</NOBR> Instructions 03/08/13 N</P></TD>
                                                    <TD class="tr1 td13"><P class="p0 ft9">Page 4 of 9</P></TD>
                                                    </TR>
                                                </TABLE>
                                            </DIV>
                                        </DIV>
                                        <DIV id="page_5">
                                            <DIV id="dimg1">
                                                <IMG src="<?php echo site_url('assets/images/i9form_images/i9form6x1.jpg'); ?>" id="img1">
                                            </DIV>
                                            <DIV class="dclr"></DIV>
                                            <DIV id="id_1">
                                                <P class="p68 ft16">Some employees may write "N/A" in the space provided for the expiration date in Section 1 if they are aliens whose employment authorization does not expire (e.g., asylees, refugees, certain citizens of the Federated States of Micronesia, the Republic of the Marshall Islands, or Palau). Reverification does not apply for such employees unless they chose to present evidence of employment authorization in Section 2 that contains an expiration date and requires reverification, such as Form <NOBR>I-766,</NOBR> Employment Authorization Document.</P>
                                                <P class="p69 ft21">Reverification applies if evidence of employment authorization (List A or List C document) presented in Section 2 expires. However, employers should not reverify:</P>
                                                <P class="p70 ft8"><SPAN class="ft4">1.</SPAN><SPAN class="ft33">U.S. citizens and noncitizen nationals; or</SPAN></P>
                                                <P class="p71 ft38"><SPAN class="ft4">2.</SPAN><SPAN class="ft37">Lawful permanent residents who presented a Permanent Resident Card (Form </SPAN><NOBR>I-551)</NOBR> for Section 2. Reverification does not apply to List B documents.</P>
                                                <P class="p72 ft16">If both Section 1 and Section 2 indicate expiration dates triggering the reverification requirement, the employer should reverify by the earlier date.</P>
                                                <P class="p73 ft16">For reverification, an employee must present unexpired documentation from either List A or List C showing he or she is still authorized to work. Employers CANNOT require the employee to present a particular document from List A or List C. The employee may choose which document to present.</P>
                                                <P class="p63 ft8">To complete Section 3, employers should follow these instructions:</P>
                                                <P class="p74 ft8"><SPAN class="ft4">1.</SPAN><SPAN class="ft33">Complete Block A if an employee's name has changed at the time you complete Section 3.</SPAN></P>
                                                <P class="p75 ft21"><SPAN class="ft4">2.</SPAN><SPAN class="ft26">Complete Block B with the date of rehire if you rehire an employee within 3 years of the date this form was originally completed, and the employee is still authorized to be employed on the same basis as previously indicated on this form. Also complete the "Signature of Employer or Authorized Representative" block.</SPAN></P>
                                                <P class="p51 ft8"><SPAN class="ft4">3.</SPAN><SPAN class="ft33">Complete Block C if:</SPAN></P>
                                                <P class="p76 ft19"><SPAN class="ft2">a.</SPAN><SPAN class="ft39">The employment authorization or employment authorization document of a current employee is about to expire and requires reverification; or</SPAN></P>
                                                <P class="p77 ft21"><SPAN class="ft2">b.</SPAN><SPAN class="ft40">You rehire an employee within 3 years of the date this form was originally completed and his or her employment authorization or employment authorization document has expired. (Complete Block B for this employee as well.)</SPAN></P>
                                                <P class="p78 ft8">To complete Block C:</P>
                                                <P class="p79 ft21"><SPAN class="ft2">a.</SPAN><SPAN class="ft26">Examine either a List A or List C document the employee presents that shows that the employee is currently authorized to work in the United States; and</SPAN></P>
                                                <P class="p80 ft8"><SPAN class="ft2">b.</SPAN><SPAN class="ft33">Record the document title, document number, and expiration date (if any).</SPAN></P>
                                                <P class="p81 ft21"><SPAN class="ft4">4.</SPAN><SPAN class="ft32">After completing block A, B or C, complete the "Signature of Employer or Authorized Representative" block, including the date.</SPAN></P>
                                                <P class="p82 ft16">For reverification purposes, employers may either complete Section 3 of a new Form <NOBR>I-9</NOBR> or Section 3 of the previously completed Form <NOBR>I-9.</NOBR> Any new pages of Form <NOBR>I-9</NOBR> completed during reverification must be attached to the employee's original Form <NOBR>I-9.</NOBR> If you choose to complete Section 3 of a new Form <NOBR>I-9,</NOBR> you may attach just the page containing Section 3, with the employee's name entered at the top of the page, to the employee's original Form <NOBR>I-9.</NOBR> If there is a more current version of Form <NOBR>I-9</NOBR> at the time of reverification, you must complete Section 3 of that version of the form.</P>
                                                <P class="p9 ft17 paragraph-heading">What Is the Filing Fee?</P>
                                                <P class="p83 ft16">There is no fee for completing Form <NOBR>I-9.</NOBR> This form is not filed with USCIS or any government agency. Form <NOBR>I-9</NOBR> must be retained by the employer and made available for inspection by U.S. Government officials as specified in the <SPAN class="ft14">"USCIS Privacy Act Statement" </SPAN>below.</P>
                                                <P class="p84 ft17 paragraph-heading">USCIS Forms and Information</P>
                                                <P class="p85 ft21">For more detailed information about completing Form <NOBR>I-9,</NOBR> employers and employees should refer to the <SPAN class="ft36">Handbook for Employers: Instructions for Completing Form </SPAN><NOBR><SPAN class="ft36">I-9</SPAN></NOBR><SPAN class="ft36"> </SPAN><NOBR><SPAN class="ft36">(M-274)</SPAN>.</NOBR></P>
                                            </DIV>
                                            <DIV id="id_2">
                                                <TABLE cellpadding=0 cellspacing=0 class="t2">
                                                    <TR>
                                                        <TD class="tr1 td12"><P class="p0 ft9">Form <NOBR>I-9</NOBR> Instructions 03/08/13 N</P></TD>
                                                    <TD class="tr1 td13"><P class="p0 ft9">Page 5 of 9</P></TD>
                                                    </TR>
                                                </TABLE>
                                            </DIV>
                                        </DIV>
                                        <DIV id="page_6">
                                            <DIV id="dimg1">
                                                <IMG src="<?php echo site_url('assets/images/i9form_images/i9form6x1.jpg'); ?>" id="img1">
                                            </DIV>
                                            <DIV class="dclr"></DIV>
                                            <DIV id="id_1">
                                                <P class="p86 ft16">You can also obtain information about Form <NOBR>I-9</NOBR> from the USCIS Web site at <NOBR><SPAN class="ft34">www.uscis.gov/I-9Central</SPAN>,</NOBR> by <NOBR>e-mailing</NOBR> USCIS at <NOBR><SPAN class="ft34">I-9Central@dhs.gov</SPAN>,</NOBR> or by calling <NOBR><SPAN class="ft14">1-888-464-4218</SPAN>.</NOBR> For TDD (hearing impaired), call <NOBR><SPAN class="ft14">1-877-875-6028</SPAN>.</NOBR></P>
                                                <P class="p42 ft16">To obtain USCIS forms or the <SPAN class="ft30">Handbook for Employers</SPAN>, you can download them from the USCIS Web site at <SPAN class="ft34">www.uscis. gov/forms</SPAN>. You may order USCIS forms by calling our <NOBR>toll-free</NOBR> number at <NOBR><SPAN class="ft14">1-800-870-3676</SPAN>.</NOBR> You may also obtain forms and information by contacting the USCIS National Customer Service Center at <NOBR><SPAN class="ft14">1-800-375-5283</SPAN>.</NOBR> For TDD (hearing impaired), call <NOBR><SPAN class="ft14">1-800-767-1833</SPAN>.</NOBR></P>
                                                <P class="p87 ft16">Information about <NOBR>E-Verify,</NOBR> a free and voluntary program that allows participating employers to electronically verify the employment eligibility of their newly hired employees, can be obtained from the USCIS Web site at <SPAN class="ft34">www.dhs.gov/E- Verify</SPAN>, by <NOBR>e-mailing</NOBR> USCIS at <NOBR><SPAN class="ft34">I-9Central@dhs.gov</SPAN></NOBR><SPAN class="ft34"> </SPAN>or by calling <NOBR><SPAN class="ft14">1-888-464-4218</SPAN>.</NOBR> For TDD (hearing impaired), call</P>
                                                <P class="p14 ft2"><NOBR>1-877-875-6028.</NOBR></P>
                                                <P class="p88 ft8">Employees with questions about Form <NOBR>I-9</NOBR> and/or <NOBR>E-Verify</NOBR> can reach the USCIS employee hotline by calling</P>
                                                <P class="p89 ft8"><NOBR><SPAN class="ft2">1-888-897-7781</SPAN>.</NOBR> For TDD (hearing impaired), call <NOBR><SPAN class="ft2">1-877-875-6028.</SPAN></NOBR></P>
                                                <P class="p90 ft17">Photocopying and Retaining Form <NOBR>I-9</NOBR></P>
                                                <P class="p91 ft16">A blank Form <NOBR>I-9</NOBR> may be reproduced, provided all sides are copied. The instructions and Lists of Acceptable Documents must be available to all employees completing this form. Employers must retain each employee's completed Form <NOBR>I-9</NOBR> for as long as the individual works for the employer. Employers are required to retain the pages of the form on which the employee and employer enter data. If copies of documentation presented by the employee are made, those copies must also be kept with the form. Once the individual's employment ends, the employer must retain this form for either 3 years after the date of hire or 1 year after the date employment ended, whichever is later.</P>
                                                <P class="p92 ft21">Form <NOBR>I-9</NOBR> may be signed and retained electronically, in compliance with Department of Homeland Security regulations at 8 CFR 274a.2.</P>
                                                <P class="p93 ft17">USCIS Privacy Act Statement</P>
                                                <P class="p94 ft21"><SPAN class="ft20">AUTHORITIES: </SPAN>The authority for collecting this information is the Immigration Reform and Control Act of 1986, Public Law <NOBR>99-603</NOBR> (8 USC 1324a).</P>
                                                <P class="p95 ft16"><SPAN class="ft14">PURPOSE: </SPAN>This information is collected by employers to comply with the requirements of the Immigration Reform and Control Act of 1986. This law requires that employers verify the identity and employment authorization of individuals they hire for employment to preclude the unlawful hiring, or recruiting or referring for a fee, of aliens who are not authorized to work in the United States.</P>
                                                <P class="p96 ft16"><SPAN class="ft14">DISCLOSURE: </SPAN>Submission of the information required in this form is voluntary. However, failure of the employer to ensure proper completion of this form for each employee may result in the imposition of civil or criminal penalties. In addition, employing individuals knowing that they are unauthorized to work in the United States may subject the employer to civil and/or criminal penalties.</P>
                                                <P class="p97 ft16"><SPAN class="ft14">ROUTINE USES: </SPAN>This information will be used by employers as a record of their basis for determining eligibility of an employee to work in the United States. The employer will keep this form and make it available for inspection by authorized officials of the Department of Homeland Security, Department of Labor, and Office of Special Counsel for <NOBR>Immigration-Related</NOBR> Unfair Employment Practices.</P>
                                                <P class="p84 ft17 paragraph-heading">Paperwork Reduction Act</P>
                                                <P class="p98 ft16">An agency may not conduct or sponsor an information collection and a person is not required to respond to a collection of information unless it displays a currently valid OMB control number. The public reporting burden for this collection of information is estimated at 35 minutes per response, including the time for reviewing instructions and completing and retaining the form. Send comments regarding this burden estimate or any other aspect of this collection of information, including suggestions for reducing this burden, to: U.S. Citizenship and Immigration Services, Regulatory Coordination Division, Office of Policy and Strategy, 20 Massachusetts Avenue NW, Washington, DC <NOBR>20529-2140;</NOBR> OMB No. <NOBR>1615-0047.</NOBR> <SPAN class="ft14">Do not mail your completed Form </SPAN><NOBR><SPAN class="ft14">I-9</SPAN></NOBR><SPAN class="ft14"> to this address.</SPAN></P>
                                            </DIV>
                                            <DIV id="id_2">
                                                <TABLE cellpadding=0 cellspacing=0 class="t2">
                                                    <TR>
                                                        <TD class="tr1 td12"><P class="p0 ft9">Form <NOBR>I-9</NOBR> Instructions 03/08/13 N</P></TD>
                                                    <TD class="tr1 td13"><P class="p0 ft9">Page 6 of 9</P></TD>
                                                    </TR>
                                                </TABLE>
                                            </DIV>
                                        </DIV>
                                        <DIV id="page_7">
                                            <DIV id="id_1">
                                                <TABLE cellpadding=0 cellspacing=0 class="t0">
                                                    <TR>
                                                        <TD class="tr0 td0"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr0 td1"><P class="p99 ft1">Employment Eligibility Verification</P></TD>
                                                        <TD colspan=2 class="tr0 td2"><P class="p2 ft2">USCIS</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr1 td0"><P class="p0 ft42">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr10 td1"><P class="p3 ft4">Department of Homeland Security</P></TD>
                                                        <TD colspan=2 class="tr1 td2"><P class="p2 ft5">Form <NOBR>I-9</NOBR></P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr11 td0"><P class="p0 ft43">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr11 td2"><P class="p4 ft44">OMB No. <NOBR>1615-0047</NOBR></P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr12 td0"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr12 td1"><P class="p3 ft8">U.S. Citizenship and Immigration Services</P></TD>
                                                        <TD colspan=2 class="tr12 td2"><P class="p2 ft9">Expires 03/31/2016</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr7 td0"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td1"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td3"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td0"><P class="p0 ft45">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr13 td0"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td4"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td5"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td0"><P class="p0 ft46">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td6"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td7"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td8"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td6"><P class="p0 ft47">&nbsp;</P></TD>
                                                    </TR>
                                                </TABLE>
                                                <P class="p54 ft50"><SPAN class="ft48">►</SPAN><SPAN class="ft49">START HERE. </SPAN>Read instructions carefully before completing this form. The instructions must be available during completion of this form.</P>
                                                <P class="p100 ft51"><NOBR><SPAN class="ft49">ANTI-DISCRIMINATION</SPAN></NOBR><SPAN class="ft49"> NOTICE: </SPAN>It is illegal to discriminate against <NOBR>work-authorized</NOBR> individuals. Employers <SPAN class="ft49">CANNOT </SPAN>specify which document(s) they will accept from an employee. The refusal to hire an individual because the documentation presented has a future expiration date may also constitute illegal discrimination.</P>
                                                <P class="p101 ft54"><SPAN class="ft52">Section 1. Employee Information and Attestation </SPAN>(Employees must complete and sign Section 1 of Form <NOBR>I-9</NOBR> no later than the <SPAN class="ft53">first day of employment</SPAN>, but not before accepting a job offer.)</P>
                                                <div class="i9form-table-area">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>Last Name (Family Name)</label>
                                                            <input type="text" name="last_name" value="<?php
                                                            if (isset($formpost['last_name'])) {
                                                                echo $formpost['last_name'];
                                                            } else {
                                                                echo $employer['last_name'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                                   <?php echo form_error('last_name'); ?>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>First Name (Given Name)</label>
                                                            <input type="text" name="first_name" value="<?php
                                                            if (isset($formpost['first_name'])) {
                                                                echo $formpost['first_name'];
                                                            } else {
                                                                echo $employer['first_name'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                                   <?php echo form_error('first_name'); ?>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <label>Middle Initial</label>
                                                            <input type="text" name="section1MiddleInitial" value="<?php
                                                            if (isset($formpost['section1MiddleInitial'])) {
                                                                echo $formpost['section1MiddleInitial'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                                   <?php echo form_error('section1MiddleInitial'); ?>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6">
                                                            <label>Other Names Used  (if any)</label>
                                                            <input type="text" name="section1OtherNames" value="<?php
                                                            if (isset($formpost['section1OtherNames'])) {
                                                                echo $formpost['section1OtherNames'];
                                                            } //else { echo $employer['section1_other_names']; }
                                                            ?>" class="invoice-fields">
                                                                   <?php echo form_error('section1OtherNames'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>Address (Street Number and Name)</label>
                                                            <input type="text" name="section1Address" value="<?php
                                                            if (isset($formpost['section1Address'])) {
                                                                echo $formpost['section1Address'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                                   <?php echo form_error('section1Address'); ?>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <label>Apt. Number</label>
                                                            <input type="text" name="section1AptNumber" value="<?php
                                                            if (isset($formpost['section1AptNumber'])) {
                                                                echo $formpost['section1AptNumber'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                                   <?php echo form_error('section1AptNumber'); ?>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>City or Town</label>
                                                            <input type="text" name="section1CityOrTown" value="<?php
                                                            if (isset($formpost['section1CityOrTown'])) {
                                                                echo $formpost['section1CityOrTown'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                                   <?php echo form_error('section1CityOrTown'); ?>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <label>State</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields">
                                                                    <option>Select State</option>  
                                                                    <option>Select State</option> 
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-6">
                                                            <label>Zip Code</label>
                                                            <input type="text" name="Section1Zipcode" value="<?php
                                                            if (isset($formpost['Section1Zipcode'])) {
                                                                echo $formpost['Section1Zipcode'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                                   <?php echo form_error('Section1Zipcode'); ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>Date of Birth (mm/dd/yyyy)</label>
                                                            <input class="invoice-fields startdate" readonly="" type="text" name="Section1DOB" value="<?php
                                                            if (isset($formpost['Section1DOB'])) {
                                                                echo $formpost['Section1DOB'];
                                                            }
                                                            ?>">
                                                                   <?php echo form_error('Section1DOB'); ?>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>U.S. Social Security Number</label>
                                                            <input class="invoice-fields" type="text" name="Section1SSN" value="<?php
                                                            if (isset($formpost['Section1SSN'])) {
                                                                echo $formpost['Section1SSN'];
                                                            }
                                                            ?>">
                                                                   <?php echo form_error('Section1SSN'); ?>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>E-mail Address</label>
                                                            <input class="invoice-fields" type="email" name="Section1EmailAddress" value="<?php
                                                            if (isset($employer['Section1EmailAddress'])) {
                                                                echo $employer['Section1EmailAddress'];
                                                            }
                                                            ?>">
                                                                   <?php echo form_error('Section1EmailAddress'); ?>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>Telephone Number</label>
                                                            <input class="invoice-fields" type="text" name="Section1PhoneNumber" value="<?php
                                                            if (isset($employer['Section1PhoneNumber'])) {
                                                                echo $employer['Section1PhoneNumber'];
                                                            }
                                                            ?>">
                                                                   <?php echo form_error('Section1PhoneNumber'); ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <P class="p108 ft49">I am aware that federal law provides for imprisonment and/or fines for false statements or use of false documents in connection with the completion of this form.</P>
                                                <P class="p44 ft49">I attest, under penalty of perjury, that I am (check one of the following):</P>
                                            </DIV>
                                            <DIV id="id_2">
                                                <DIV id="id_2_1">
                                                    <div class="form-col-100 checkbox-row">
                                                        <input type="checkbox" value="1" name="Section1CheckBoxCitizenUS" <?php
                                                        if (isset($formpost['Section1CheckBoxCitizenUS']) && $formpost['Section1CheckBoxCitizenUS'] == 1) {
                                                            echo " checked";
                                                        }
                                                        ?>>
                                                        <label>A citizen of the United States</label>
                                                    </div>
                                                    <div class="form-col-100 checkbox-row">
                                                        <input type="checkbox" value="1" name="Section1CheckBoxNonCitizenUS" <?php
                                                        if (isset($formpost['Section1CheckBoxNonCitizenUS']) && $formpost['Section1CheckBoxNonCitizenUS'] == 1) {
                                                            echo " checked";
                                                        }
                                                        ?>>
                                                        <label>A non citizen national of the United States <SPAN class="ft60">(See instructions)</SPAN></label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-6">
                                                            <div class="form-col-100 checkbox-row">
                                                                <input type="checkbox" value="1" name="Section1CheckBoxPermanentResident" <?php
                                                        if (isset($formpost['Section1CheckBoxPermanentResident']) && $formpost['Section1CheckBoxPermanentResident'] == 1) {
                                                            echo " checked";
                                                        }
                                                        ?>>
                                                                <label>A lawful permanent resident (Alien Registration Number/USCIS Number):</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6"><input type="text" name="Section1PermanentResident" value="<?php
                                                            if (isset($employer['Section1PermanentResident'])) {
                                                                echo $employer['Section1PermanentResident'];
                                                            }
                                                        ?>" class="invoice-fields"></div>
                                                    </div>
                                                    <div class="row checkbox-row">
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <div class="form-col-100 checkbox-row">
                                                                <input type="checkbox" value="1" name="Section1CheckBoxAlienAuthorized" <?php
                                                            if (isset($formpost['Section1CheckBoxAlienAuthorized']) && $formpost['Section1CheckBoxAlienAuthorized'] == 1) {
                                                                echo " checked";
                                                            }
                                                            ?>>
                                                                <label>An alien authorized to work until (expiration date, if applicable, mm/dd/yyyy). Some aliens may write "N/A" in this field. (See instructions)</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6"><input name="Section1AlienAuthorized" value="<?php
                                                            if (isset($employer['Section1AlienAuthorized'])) {
                                                                echo $employer['Section1AlienAuthorized'];
                                                            }
                                                            ?>" type="text" class="invoice-fields"></div>                                                      
                                                    </div>
                                                    <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12 checkbox-row">
                                                        <em>For aliens authorized to work, provide your Alien Registration Number/USCIS Number OR Form I-94 Admission Number:</em>
                                                        <div class="row">
                                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                                <label class="checkbox-row">1. Alien Registration Number/USCIS Number:</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6"><input type="text" name="Section1AlienRegistrationNumber" value="<?php
                                                            if (isset($employer['Section1AlienRegistrationNumber'])) {
                                                                echo $employer['Section1AlienRegistrationNumber'];
                                                            }
                                                            ?>" class="invoice-fields"></div>
                                                            <div class="form-col-100 aligncenter"><b>OR</b></div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                                <label class="checkbox-row">2. Form I-94 Admission Number:</label>
                                                            </div>
                                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-6"><input type="text" name="Section1I94AdmissionNumber" value="<?php
                                                                if (isset($employer['Section1I94$employerAdmissionNumber'])) {
                                                                    echo $employer['Section1I94AdmissionNumber'];
                                                                }
                                                            ?>" class="invoice-fields"></div>
                                                        </div>
                                                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                            <label>If you obtained your admission number from CBP in connection with your arrival in the United States, include the following:</label>
                                                            <div class="form-col-100">
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                    <label class="checkbox-row">Foreign Passport Number:</label>
                                                                </div>
                                                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8"><input name="Section1ForeignPassportNumber" value="<?php
                                                                if (isset($employer['Section1ForeignPassportNumber'])) {
                                                                    echo $employer['Section1ForeignPassportNumber'];
                                                                }
                                                            ?>" type="text" class="invoice-fields"></div>
                                                            </div>
                                                            <div class="form-col-100 checkbox-row">
                                                                <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                                    <label class="checkbox-row">Country of Issuance:</label>
                                                                </div>
                                                                <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8"><input type="text" name="Section1CountryOfIssuance" value="<?php
                                                                    if (isset($employer['Section1CountryOfIssuance'])) {
                                                                        echo $employer['Section1CountryOfIssuance'];
                                                                    }
                                                            ?>" class="invoice-fields"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </DIV>
                                            </DIV>
                                            <DIV id="id_5">
                                                <P class="p118 ft51">Some aliens may write "N/A" on the Foreign Passport Number and Country of Issuance fields. (<SPAN class="ft60">See instructions</SPAN>)</P>
                                                <div class="i9form-table-area">
                                                    <div class="row">
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <label>Signature of Employee:</label>
                                                            <input type="text" name="Section1SignatureOfEmployee" value="<?php
                                                            if (isset($employer['Section1SignatureOfEmployee'])) {
                                                                echo $employer['Section1SignatureOfEmployee'];
                                                            }
                                                            ?>" class="invoice-fields" name="country">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label>Date (mm/dd/yyyy):</label>
                                                            <input class="invoice-fields startdate" readonly="" type="text" name="Section1SignatureDate" value="<?php
                                                                   if (isset($formpost['Section1SignatureDate'])) {
                                                                       echo $formpost['Section1SignatureDate'];
                                                                   }
                                                            ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <P class="p120 ft60 paragraph-heading"><SPAN class="ft64">Preparer and/or Translator Certification </SPAN>(To be completed and signed if Section 1 is prepared by a person other than the employee.)</P>
                                                <P class="p121 ft49">I attest, under penalty of perjury, that I have assisted in the completion of this form and that to the best of my knowledge the information is true and correct.</P>
                                                <div class="i9form-table-area">
                                                    <div class="row">
                                                        <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                                                            <label>Signature of Preparer or Translator:</label>
                                                            <input type="text" class="invoice-fields" name="Section1SignatureOfPreparer">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label>Date (mm/dd/yyyy):</label>
                                                            <input class="invoice-fields startdate" readonly="" type="text" name="Section1SignatureOfPreparerDate" value="<?php
                                                            if (isset($formpost['Section1SignatureOfPreparerDate'])) {
                                                                echo $formpost['Section1SignatureOfPreparerDate'];
                                                            }
                                                            ?>">
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                            <label>Last Name (Family Name)</label>
                                                            <input type="text" name="Section1LastNamePreparer" value="<?php
                                                            if (isset($formpost['Section1LastNamePreparer'])) {
                                                                echo $formpost['Section1LastNamePreparer'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label>First Name (Given Name)</label>
                                                            <input type="text" name="Section1FirstNamePreparer" value="<?php
                                                                   if (isset($formpost['Section1FirstNamePreparer'])) {
                                                                       echo $formpost['Section1FirstNamePreparer'];
                                                                   }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label>Address (Street Number and Name)</label>
                                                            <input type="text" name="Section1AddressPreparer" value="<?php
                                                            if (isset($formpost['Section1AddressPreparer'])) {
                                                                echo $formpost['Section1AddressPreparer'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label>City or Town</label>
                                                            <input type="text" name="Section1CityTownPreparer" value="<?php
                                                                   if (isset($formpost['Section1CityTownPreparer'])) {
                                                                       echo $formpost['Section1CityTownPreparer'];
                                                                   }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <label>State</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields">
                                                                    <option>Select State</option>  
                                                                    <option>Select State</option> 
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label>Zip Code</label>
                                                            <input type="text" name="Section1ZipcodePreparer" value="<?php
                                                                   if (isset($formpost['Section1ZipcodePreparer'])) {
                                                                       echo $formpost['Section1ZipcodePreparer'];
                                                                   }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                    </div>
                                                </div>
                                                <P class="p124 ft65">Employer Completes Next Page</P>
                                            </DIV>
                                            <DIV id="id_6">
                                                <TABLE cellpadding=0 cellspacing=0 class="t2">
                                                    <TR>
                                                        <TD class="tr1 td12"><P class="p0 ft9">Form <NOBR>I-9</NOBR> 03/08/13 N</P></TD>
                                                    <TD class="tr1 td13"><P class="p0 ft9">Page 7 of 9</P></TD>
                                                    </TR>
                                                </TABLE>
                                            </DIV>
                                        </DIV>
                                        <DIV id="page_8">
                                            <DIV id="dimg1">
                                                <IMG src="<?php echo site_url('assets/images/i9form_images/i9form6x1.jpg'); ?>" id="img1">
                                            </DIV>
                                            <DIV class="dclr"></DIV>
                                            <DIV id="id_1">
                                                <P class="p125 ft66">Section 2. Employer or Authorized Representative Review and Verification</P>
                                                <P class="p126 ft55">(Employers or their authorized representative must complete and sign Section 2 within 3 business days of the employee's first day of employment. You must physically examine one document from List A OR examine a combination of one document from List B and one document from List C as listed on the "Lists of Acceptable Documents" on the next page of this form. For each document you review, record the following information: document title, issuing authority, document number, and expiration date, if any.)</P>
                                                <div class="i9form-table-area">
                                                    <div class="row">
                                                        <div class="col-lg-7 col-md-7 col-xs-12 col-sm-7">
                                                            <strong>Employee Last Name, First Name and Middle Initial from Section 1:</strong>
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                            <input type="text" name="Section2FirstLastMiddleName" value="<?php
                                                                   if (isset($formpost['Section2FirstLastMiddleName'])) {
                                                                       echo $formpost['Section2FirstLastMiddleName'];
                                                                   }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="i9form-table-area">
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <strong class="aligncenter">List A</strong>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <strong class="aligncenter">OR</strong>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <strong class="aligncenter">List B</strong>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <strong class="aligncenter">AND</strong>
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <strong class="aligncenter">List C</strong>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label class="aligncenter"><strong>Identity and Employment Authorization</strong></label>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label class="aligncenter"><strong>Identity</strong></label>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label class="aligncenter"><strong>Employment Authorization</strong></label>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <div class="side-pane">
                                                                <label>Document Title:</label>
                                                                <input type="text" name="Section2DocumentTitleListA1" value="<?php
                                                                if (isset($formpost['Section2DocumentTitleListA1'])) {
                                                                    echo $formpost['Section2DocumentTitleListA1'];
                                                                }
                                                                ?>" class="invoice-fields">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Issuing Authority:</label>
                                                                <input type="text" name="Section2IssuingAuthorityListA1" value="<?php
                                                                if (isset($formpost['Section2IssuingAuthorityListA1'])) {
                                                                    echo $formpost['Section2IssuingAuthorityListA1'];
                                                                }
                                                                ?>" class="invoice-fields">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Document Number:</label>
                                                                <input type="text" name="Section2DocumentNumberListA1" value="<?php
                                                                if (isset($formpost['Section2DocumentNumberListA1'])) {
                                                                    echo $formpost['Section2DocumentNumberListA1'];
                                                                }
                                                                ?>" class="invoice-fields">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Expiration Date (if any)(mm/dd/yyyy):</label>
                                                                <input class="invoice-fields startdate" readonly="" type="text" name="Section2ExpirationDateListA1" value="<?php
                                                                if (isset($formpost['Section2ExpirationDateListA1'])) {
                                                                    echo $formpost['Section2ExpirationDateListA1'];
                                                                }
                                                                ?>">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Document Title:</label>
                                                                <input type="text" name="Section2DocumentTitleListA2" value="<?php
                                                                if (isset($formpost['Section2DocumentTitleListA2'])) {
                                                                    echo $formpost['Section2DocumentTitleListA2'];
                                                                }
                                                                ?>" class="invoice-fields">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Issuing Authority:</label>
                                                                <input type="text" name="Section2IssuingAuthorityListA2" value="<?php
                                                                if (isset($formpost['Section2IssuingAuthorityListA2'])) {
                                                                    echo $formpost['Section2IssuingAuthorityListA2'];
                                                                }
                                                                ?>" class="invoice-fields">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Document Number:</label>
                                                                <input type="text" name="Section2DocumentNumberListA2" value="<?php
                                                                if (isset($formpost['Section2DocumentNumberListA2'])) {
                                                                    echo $formpost['Section2DocumentNumberListA2'];
                                                                }
                                                                ?>" class="invoice-fields">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Expiration Date (if any)(mm/dd/yyyy):</label>
                                                                <input class="invoice-fields startdate" readonly="" type="text" name="Section2ExpirationDateListA2" value="<?php
                                                                if (isset($formpost['Section2ExpirationDateListA2'])) {
                                                                    echo $formpost['Section2ExpirationDateListA2'];
                                                                }
                                                                ?>">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Document Title:</label>
                                                                <input type="text" name="Section2DocumentTitleListA3" value="<?php
                                                                if (isset($formpost['Section2DocumentTitleListA3'])) {
                                                                    echo $formpost['Section2DocumentTitleListA3'];
                                                                }
                                                                ?>" class="invoice-fields">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Issuing Authority:</label>
                                                                <input type="text" name="Section2IssuingAuthorityListA3" value="<?php
                                                                if (isset($formpost['Section2IssuingAuthorityListA3'])) {
                                                                    echo $formpost['Section2IssuingAuthorityListA3'];
                                                                }
                                                                ?>" class="invoice-fields">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Document Number:</label>
                                                                <input type="text" name="Section2DocumentNumberListA3" value="<?php
                                                                if (isset($formpost['Section2DocumentNumberListA3'])) {
                                                                    echo $formpost['Section2DocumentNumberListA3'];
                                                                }
                                                                ?>" class="invoice-fields">
                                                            </div>
                                                            <div class="side-pane">
                                                                <label>Expiration Date (if any)(mm/dd/yyyy):</label>
                                                                <input class="invoice-fields startdate" readonly="" type="text" name="Section2ExpirationDateListA3" value="<?php
                                                                       if (isset($formpost['Section2ExpirationDateListA3'])) {
                                                                           echo $formpost['Section2ExpirationDateListA3'];
                                                                       }
                                                                ?>">
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-8 col-md-8 col-xs-12 col-sm-8">
                                                            <div class="row">
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <div class="side-pane no-shadow">
                                                                        <label>Document Title:</label>
                                                                        <input type="text" name="Section2DocumentTitleListB" value="<?php
                                                                        if (isset($formpost['Section2DocumentTitleListB'])) {
                                                                            echo $formpost['Section2DocumentTitleListB'];
                                                                        }
                                                                        ?>" class="invoice-fields">
                                                                    </div>
                                                                    <div class="side-pane no-shadow">
                                                                        <label>Issuing Authority:</label>
                                                                        <input type="text" name="Section2IssuingAuthorityListB" value="<?php
                                                                        if (isset($formpost['Section2IssuingAuthorityListB'])) {
                                                                            echo $formpost['Section2IssuingAuthorityListB'];
                                                                        }
                                                                        ?>" class="invoice-fields">
                                                                    </div>
                                                                    <div class="side-pane no-shadow">
                                                                        <label>Document Number:</label>
                                                                        <input type="text" name="Section2DocumentNumberListB" value="<?php
                                                                        if (isset($formpost['Section2DocumentNumberListB'])) {
                                                                            echo $formpost['Section2DocumentNumberListB'];
                                                                        }
                                                                        ?>" class="invoice-fields">
                                                                    </div>
                                                                    <div class="side-pane no-shadow">
                                                                        <label>Expiration Date (if any)(mm/dd/yyyy):</label>
                                                                        <input class="invoice-fields startdate" readonly="" type="text" name="Section2ExpirationDateListB" value="<?php
                                                                               if (isset($formpost['Section2ExpirationDateListB'])) {
                                                                                   echo $formpost['Section2ExpirationDateListB'];
                                                                               }
                                                                        ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                                    <div class="side-pane no-shadow">
                                                                        <label>Document Title:</label>
                                                                        <input type="text" name="Section2DocumentTitleListC" value="<?php
                                                                        if (isset($formpost['Section2DocumentTitleListC'])) {
                                                                            echo $formpost['Section2DocumentTitleListC'];
                                                                        }
                                                                        ?>" class="invoice-fields">
                                                                    </div>
                                                                    <div class="side-pane no-shadow">
                                                                        <label>Issuing Authority:</label>
                                                                        <input type="text" name="Section2IssuingAuthorityListC" value="<?php
                                                                        if (isset($formpost['Section2IssuingAuthorityListC'])) {
                                                                            echo $formpost['Section2IssuingAuthorityListC'];
                                                                        }
                                                                        ?>" class="invoice-fields">
                                                                    </div>
                                                                    <div class="side-pane no-shadow">
                                                                        <label>Document Number:</label>
                                                                        <input type="text" name="Section2DocumentNumberListC" value="<?php
                                                                        if (isset($formpost['Section2DocumentNumberListC'])) {
                                                                            echo $formpost['Section2DocumentNumberListC'];
                                                                        }
                                                                        ?>" class="invoice-fields">
                                                                    </div>
                                                                    <div class="side-pane no-shadow">
                                                                        <label>Expiration Date (if any)(mm/dd/yyyy):</label>
                                                                        <input class="invoice-fields startdate" readonly="" type="text" name="Section2ExpirationDateListC" value="<?php
                                                                               if (isset($formpost['Section2ExpirationDateListC'])) {
                                                                                   echo $formpost['Section2ExpirationDateListC'];
                                                                               }
                                                                        ?>">
                                                                    </div>
                                                                </div>
                                                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                                                    <div class="barcode-wrp">
                                                                        <div class="barcode-box">
                                                                            3-D Barcode<br/>Do Not Write in This Space
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <P class="p127 ft50"></P>
                                                <P class="p134 ft66">Certification</P>
                                                <P class="p135 ft49">I attest, under penalty of perjury, that (1) I have examined the document(s) presented by the <NOBR>above-named</NOBR> employee, (2) the <NOBR>above-listed</NOBR> document(s) appear to be genuine and to relate to the employee named, and (3) to the best of my knowledge the employee is authorized to work in the United States.</P>
                                                <div class="i9form-table-area">
                                                    <div class="row">
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <strong>The employee's first day of employment (mm/dd/yyyy):</strong>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <input class="invoice-fields transparent-field startdate" readonly="" type="text" name="Section2EmployeeFirstDayOfEmployment" value="<?php
                                                                               if (isset($formpost['Section2EmployeeFirstDayOfEmployment'])) {
                                                                                   echo $formpost['Section2EmployeeFirstDayOfEmployment'];
                                                                               }
                                                                        ?>">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <strong>(See instructions for exemptions.)</strong>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                            <label>Signature of Employer or Authorized Representative</label>
                                                            <input type="text" name="Section2SignatureOfEmployerOrAuthorizedRepresentative" value="<?php
                                                            if (isset($formpost['Section2SignatureOfEmployerOrAuthorizedRepresentative'])) {
                                                                echo $formpost['Section2SignatureOfEmployerOrAuthorizedRepresentative'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <label>Date (mm/dd/yyyy)</label>
                                                            <input class="invoice-fields startdate" readonly="" type="text" name="Section2SignatureOfEmployerOrAuthorizedRepresentativeDate" value="<?php
                                                            if (isset($formpost['Section2SignatureOfEmployerOrAuthorizedRepresentativeDate'])) {
                                                                echo $formpost['Section2SignatureOfEmployerOrAuthorizedRepresentativeDate'];
                                                            }
                                                            ?>">
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                            <label>Title of Employer or Authorized Representative</label>
                                                            <input type="text" name="Section2TitleOfEmployerOrAuthorizedRepresentative" value="<?php
                                                                   if (isset($formpost['Section2TitleOfEmployerOrAuthorizedRepresentative'])) {
                                                                       echo $formpost['Section2TitleOfEmployerOrAuthorizedRepresentative'];
                                                                   }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>Last Name (Family Name)</label>
                                                            <input type="text" name="LastNameCertifaction" value="<?php
                                                            if (isset($formpost['LastNameCertifaction'])) {
                                                                echo $formpost['LastNameCertifaction'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>First Name (Given Name)</label>
                                                            <input type="text" name="FirstNameCertifaction" value="<?php
                                                            if (isset($formpost['FirstNameCertifaction'])) {
                                                                echo $formpost['FirstNameCertifaction'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-xs-12 col-sm-6">
                                                            <label>Employer's Business or Organization Name</label>
                                                            <input type="text" name="EmployersBusinessOrOrganizationName" value="<?php
                                                                   if (isset($formpost['EmployersBusinessOrOrganizationName'])) {
                                                                       echo $formpost['EmployersBusinessOrOrganizationName'];
                                                                   }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                            <label>Employer's Business or Organization Address (Street Number and Name)</label>
                                                            <input type="text" name="EmployersBusinessOrOrganizationName" value="<?php
                                                            if (isset($formpost['EmployersBusinessOrOrganizationAddress'])) {
                                                                echo $formpost['EmployersBusinessOrOrganizationAddress'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-6">
                                                            <label>City or Town</label>
                                                            <input type="text" name="EmployersBusinessOrOrganizationCityTown" value="<?php
                                                                   if (isset($formpost['EmployersBusinessOrOrganizationCityTown'])) {
                                                                       echo $formpost['EmployersBusinessOrOrganizationCityTown'];
                                                                   }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <label>State</label>
                                                            <div class="hr-select-dropdown">
                                                                <select class="invoice-fields">
                                                                    <option>Select State</option>  
                                                                    <option>Select State</option> 
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <label>Zip Code</label>
                                                            <input type="text" name="EmployersBusinessOrOrganizationZipcode" value="<?php
                                                                   if (isset($formpost['EmployersBusinessOrOrganizationZipcode'])) {
                                                                       echo $formpost['EmployersBusinessOrOrganizationZipcode'];
                                                                   }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                    </div>
                                                </div>
                                                <P class="p136 ft60"><SPAN class="ft66">Section 3. Reverification and Rehires </SPAN>(To be completed and signed by employer or authorized representative.)</P>
                                                <div class="i9form-table-area">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label>New Name (if applicable) Last Name (Family Name)</label>
                                                            <input type="text" name="Section3NewNameReverification" value="<?php
                                                            if (isset($formpost['Section3NewNameReverification'])) {
                                                                echo $formpost['Section3NewNameReverification'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label>First Name (Given Name)</label>
                                                            <input type="text" name="Section3FirstNameReverification" value="<?php
                                                            if (isset($formpost['Section3FirstNameReverification'])) {
                                                                echo $formpost['Section3FirstNameReverification'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <label>Middle Initial</label>
                                                            <input type="text" name="Section3MiddleInitialReverification" value="<?php
                                                            if (isset($formpost['Section3MiddleInitialReverification'])) {
                                                                echo $formpost['Section3MiddleInitialReverification'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-3 col-md-3 col-xs-12 col-sm-3">
                                                            <label>B. Date of Rehire (if applicable) (mm/dd/yyyy):</label>
                                                            <input class="invoice-fields startdate" readonly="" type="text" name="Section3DateOfRehire" value="<?php
                                                                   if (isset($formpost['Section3DateOfRehire'])) {
                                                                       echo $formpost['Section3DateOfRehire'];
                                                                   }
                                                            ?>">
                                                        </div>
                                                    </div>
                                                </div>                                           
                                                <P class="p137 ft56">
                                                    <SPAN class="ft67">C.</SPAN>
                                                    <SPAN class="ft78">If employee's previous grant of employment authorization has expired, provide the information for the document from List A or List C the employee presented that establishes current employment authorization in the space provided below.</SPAN>
                                                </P>
                                                <div class="i9form-table-area">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label>Document Title:</label>
                                                            <input type="text" name="Section3DocumentTitle" value="<?php
                                                            if (isset($formpost['Section3DocumentTitle'])) {
                                                                echo $formpost['Section3DocumentTitle'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label>Document Number:</label>
                                                            <input type="text" name="Section3DocumentNumber" value="<?php
                                                            if (isset($formpost['Section3DocumentNumber'])) {
                                                                echo $formpost['Section3DocumentNumber'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                            <label>Expiration Date (if any)(mm/dd/yyyy):</label>
                                                            <input class="invoice-fields startdate" readonly="" type="text" name="Section3ExpirationDate" value="<?php
                                                                   if (isset($formpost['Section3ExpirationDate'])) {
                                                                       echo $formpost['Section3ExpirationDate'];
                                                                   }
                                                            ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                                <P class="p138 ft79">I attest, under penalty of perjury, that to the best of my knowledge, this employee is authorized to work in the United States, and if the employee presented document(s), the document(s) I have examined appear to be genuine and to relate to the individual.</P>
                                                <div class="i9form-table-area">
                                                    <div class="row">
                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                            <label>Signature of Employer or Authorized Representative:</label>
                                                            <input type="text" name="Section3SignatureOfEmployerOrAuthorizedRepresentative" value="<?php
                                                            if (isset($formpost['Section3SignatureOfEmployerOrAuthorizedRepresentative'])) {
                                                                echo $formpost['Section3SignatureOfEmployerOrAuthorizedRepresentative'];
                                                            }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-xs-12 col-sm-2">
                                                            <label>Date (mm/dd/yyyy):</label>
                                                            <input class="invoice-fields startdate" readonly="" type="text" name="Section3SignatureOfEmployerOrAuthorizedRepresentativeDate" value="<?php
                                                            if (isset($formpost['Section3SignatureOfEmployerOrAuthorizedRepresentativeDate'])) {
                                                                echo $formpost['Section3SignatureOfEmployerOrAuthorizedRepresentativeDate'];
                                                            }
                                                            ?>">
                                                        </div>
                                                        <div class="col-lg-5 col-md-5 col-xs-12 col-sm-5">
                                                            <label>Print Name of Employer or Authorized Representative:</label>
                                                            <input type="text" name="Section3PrintNameOfEmployer" value="<?php
                                                                   if (isset($formpost['Section3PrintNameOfEmployer'])) {
                                                                       echo $formpost['Section3PrintNameOfEmployer'];
                                                                   }
                                                            ?>" class="invoice-fields">
                                                        </div>
                                                    </div>
                                                </div>
                                            </DIV>
                                            <DIV id="id_2">
                                                <TABLE cellpadding=0 cellspacing=0 class="t2">
                                                    <TR>
                                                        <TD class="tr1 td12"><P class="p0 ft9">Form <NOBR>I-9</NOBR> 03/08/13 N</P></TD>
                                                    <TD class="tr1 td13"><P class="p0 ft9">Page 8 of 9</P></TD>
                                                    </TR>
                                                </TABLE>
                                            </DIV>
                                        </DIV>
                                        <DIV id="page_9">
                                            <DIV id="dimg1">
                                                <IMG src="<?php echo site_url('assets/images/i9form_images/i9form9x1.jpg'); ?>" id="img1">
                                            </DIV>
                                            <DIV class="dclr"></DIV>
                                            <DIV id="id_1">
                                                <P class="p139 ft62">LISTS OF ACCEPTABLE DOCUMENTS</P>
                                                <P class="p140 ft62">All documents must be UNEXPIRED</P>
                                                <P class="p141 ft48">Employees may present one selection from List A</P>
                                                <P class="p142 ft48">or a combination of one selection from List B and one selection from List C.</P>
                                                <TABLE cellpadding=0 cellspacing=0 class="t11">
                                                    <TR>
                                                        <TD class="tr25 td170"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr25 td171"><P class="p143 ft64">LIST A</P></TD>
                                                        <TD class="tr25 td172"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr25 td173"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr25 td174"><P class="p144 ft64">LIST B</P></TD>
                                                        <TD class="tr25 td175"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr25 td176"><P class="p145 ft64">LIST C</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr12 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr12 td178"><P class="p146 ft64">Documents that Establish</P></TD>
                                                        <TD class="tr12 td179"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr12 td180"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr12 td181"><P class="p105 ft64">Documents that Establish</P></TD>
                                                        <TD class="tr12 td182"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr12 td183"><P class="p147 ft64">Documents that Establish</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr18 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr18 td178"><P class="p148 ft64">Both Identity and</P></TD>
                                                        <TD class="tr18 td179"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr18 td180"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr18 td181"><P class="p105 ft64">Identity</P></TD>
                                                        <TD class="tr18 td182"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr18 td183"><P class="p149 ft64">Employment Authorization</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr16 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr16 td178"><P class="p146 ft64">Employment Authorization</P></TD>
                                                        <TD class="tr15 td184"><P class="p0 ft49">OR</P></TD>
                                                        <TD class="tr16 td180"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr16 td185"><P class="p150 ft49">AND</P></TD>
                                                        <TD class="tr16 td183"><P class="p0 ft41">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr15 td170"><P class="p151 ft49">1.</P></TD>
                                                        <TD class="tr15 td186"><P class="p102 ft51">U.S. Passport or U.S. Passport Card</P></TD>
                                                        <TD class="tr15 td187"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr15 td173"><P class="p152 ft49">1.</P></TD>
                                                        <TD class="tr15 td51"><P class="p103 ft51">Driver's license or ID card issued by a</P></TD>
                                                        <TD class="tr15 td175"><P class="p153 ft49">1.</P></TD>
                                                        <TD class="tr15 td176"><P class="p0 ft51">A Social Security Account Number</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr23 td188"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr23 td189"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr23 td190"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr24 td180"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td191"><P class="p103 ft80">State or outlying possession of the</P></TD>
                                                        <TD class="tr24 td182"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td183"><P class="p0 ft56">card, unless the card includes one of</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 rowspan=2 class="tr20 td192"><P class="p102 ft51"><SPAN class="ft49">2. </SPAN>Permanent Resident Card or Alien</P></TD>
                                                        <TD class="tr6 td193"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td180"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td182"><P class="p0 ft71">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr13 td193"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td180"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td191"><P class="p103 ft80">United States provided it contains a</P></TD>
                                                        <TD class="tr13 td182"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td183"><P class="p0 ft80">the following restrictions:</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr13 td177"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr1 td194"><P class="p102 ft56">Registration Receipt Card (Form <NOBR>I-551)</NOBR></P></TD>
                                                    <TD class="tr13 td193"><P class="p0 ft46">&nbsp;</P></TD>
                                                    <TD class="tr13 td180"><P class="p0 ft46">&nbsp;</P></TD>
                                                    <TD class="tr13 td182"><P class="p0 ft46">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr17 td177"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr17 td193"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr17 td180"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr1 td191"><P class="p103 ft51">photograph or information such as</P></TD>
                                                        <TD class="tr17 td182"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr1 td183"><P class="p0 ft51">(1) NOT VALID FOR EMPLOYMENT</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr13 td177"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td194"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td193"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td180"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td182"><P class="p0 ft46">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr7 td188"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td189"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td190"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr14 td180"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr11 td191"><P class="p103 ft81">name, date of birth, gender, height, eye</P></TD>
                                                        <TD class="tr14 td182"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td183"><P class="p0 ft47">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 rowspan=2 class="tr16 td192"><P class="p102 ft51"><SPAN class="ft49">3. </SPAN>Foreign passport that contains a</P></TD>
                                                        <TD class="tr17 td193"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr17 td180"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr17 td182"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr16 td183"><P class="p0 ft51">(2) VALID FOR WORK ONLY WITH</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr11 td193"><P class="p0 ft43">&nbsp;</P></TD>
                                                        <TD class="tr11 td180"><P class="p0 ft43">&nbsp;</P></TD>
                                                        <TD class="tr11 td191"><P class="p103 ft81">color, and address</P></TD>
                                                        <TD class="tr11 td182"><P class="p0 ft43">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr17 td177"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr26 td194"><P class="p102 ft82">temporary <NOBR>I-551</NOBR> stamp or temporary</P></TD>
                                                    <TD class="tr6 td190"><P class="p0 ft71">&nbsp;</P></TD>
                                                    <TD class="tr6 td195"><P class="p0 ft71">&nbsp;</P></TD>
                                                    <TD class="tr6 td53"><P class="p0 ft71">&nbsp;</P></TD>
                                                    <TD class="tr17 td182"><P class="p0 ft63">&nbsp;</P></TD>
                                                    <TD rowspan=2 class="tr26 td183"><P class="p147 ft82">INS AUTHORIZATION</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr7 td177"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td193"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td180"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td191"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td182"><P class="p0 ft45">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr1 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td194"><P class="p102 ft51"><NOBR>I-551</NOBR> printed notation on a machine-</P></TD>
                                                    <TD class="tr1 td193"><P class="p0 ft41">&nbsp;</P></TD>
                                                    <TD class="tr1 td180"><P class="p103 ft49">2.</P></TD>
                                                    <TD class="tr1 td191"><P class="p103 ft51">ID card issued by federal, state or local</P></TD>
                                                    <TD class="tr1 td182"><P class="p0 ft41">&nbsp;</P></TD>
                                                    <TD rowspan=2 class="tr16 td183"><P class="p0 ft51">(3) VALID FOR WORK ONLY WITH</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr24 td177"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td194"><P class="p102 ft80">readable immigrant visa</P></TD>
                                                        <TD class="tr24 td193"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD class="tr24 td180"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td191"><P class="p103 ft80">government agencies or entities,</P></TD>
                                                        <TD class="tr24 td182"><P class="p0 ft75">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr6 td177"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td193"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td180"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td182"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr1 td183"><P class="p147 ft51">DHS AUTHORIZATION</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr13 td188"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td189"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td190"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr6 td180"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr11 td191"><P class="p103 ft81">provided it contains a photograph or</P></TD>
                                                        <TD class="tr6 td182"><P class="p0 ft71">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD rowspan=2 class="tr12 td177"><P class="p102 ft49">4.</P></TD>
                                                        <TD rowspan=2 class="tr12 td194"><P class="p102 ft51">Employment Authorization Document</P></TD>
                                                        <TD class="tr14 td190"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr23 td180"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr14 td196"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td197"><P class="p0 ft47">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr20 td193"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td180"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td191"><P class="p103 ft56">information such as name, date of birth,</P></TD>
                                                        <TD class="tr20 td182"><P class="p154 ft49">2.</P></TD>
                                                        <TD class="tr20 td183"><P class="p0 ft51">Certification of Birth Abroad issued</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr3 td177"><P class="p0 ft83">&nbsp;</P></TD>
                                                        <TD class="tr3 td194"><P class="p102 ft80">that contains a photograph (Form</P></TD>
                                                        <TD class="tr3 td193"><P class="p0 ft83">&nbsp;</P></TD>
                                                        <TD class="tr3 td180"><P class="p0 ft83">&nbsp;</P></TD>
                                                        <TD class="tr3 td191"><P class="p103 ft80">gender, height, eye color, and address</P></TD>
                                                        <TD class="tr3 td182"><P class="p0 ft83">&nbsp;</P></TD>
                                                        <TD class="tr3 td183"><P class="p0 ft80">by the Department of State (Form</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr26 td177"><P class="p0 ft84">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr1 td194"><P class="p102 ft51"><NOBR>I-766)</NOBR></P></TD>
                                                    <TD class="tr21 td190"><P class="p0 ft72">&nbsp;</P></TD>
                                                    <TD class="tr21 td195"><P class="p0 ft72">&nbsp;</P></TD>
                                                    <TD class="tr21 td53"><P class="p0 ft72">&nbsp;</P></TD>
                                                    <TD class="tr26 td182"><P class="p0 ft84">&nbsp;</P></TD>
                                                    <TD rowspan=2 class="tr1 td183"><P class="p0 ft51"><NOBR>FS-545)</NOBR></P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td177"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td193"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD colspan=2 rowspan=4 class="tr9 td198"><P class="p103 ft51"><SPAN class="ft49">3. </SPAN>School ID card with a photograph</P></TD>
                                                        <TD class="tr14 td182"><P class="p0 ft47">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td177"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td194"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr7 td190"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td196"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td197"><P class="p0 ft45">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td188"><P class="p0 ft58">&nbsp;</P></TD>
                                                        <TD class="tr5 td189"><P class="p0 ft58">&nbsp;</P></TD>
                                                        <TD class="tr5 td190"><P class="p0 ft58">&nbsp;</P></TD>
                                                        <TD colspan=2 rowspan=3 class="tr1 td199"><P class="p154 ft51"><SPAN class="ft49">3. </SPAN>Certification of Report of Birth</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 rowspan=2 class="tr11 td192"><P class="p102 ft81"><SPAN class="ft85">5. </SPAN>For a nonimmigrant alien authorized</P></TD>
                                                        <TD class="tr13 td193"><P class="p0 ft46">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr23 td190"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr23 td195"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr23 td53"><P class="p0 ft74">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr1 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td194"><P class="p102 ft51">to work for a specific employer</P></TD>
                                                        <TD class="tr1 td193"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td180"><P class="p103 ft49">4.</P></TD>
                                                        <TD class="tr1 td191"><P class="p103 ft51">Voter's registration card</P></TD>
                                                        <TD class="tr1 td182"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td183"><P class="p0 ft51">issued by the Department of State</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr17 td177"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr4 td194"><P class="p102 ft51">because of his or her status:</P></TD>
                                                        <TD class="tr6 td190"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td195"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td53"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr17 td182"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr9 td197"><P class="p0 ft51">(Form <NOBR>DS-1350)</NOBR></P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr22 td177"><P class="p0 ft73">&nbsp;</P></TD>
                                                        <TD class="tr17 td190"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD colspan=2 rowspan=2 class="tr9 td198"><P class="p103 ft51"><SPAN class="ft49">5. </SPAN>U.S. Military card or draft record</P></TD>
                                                        <TD class="tr17 td196"><P class="p0 ft63">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr6 td177"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td194"><P class="p155 ft80"><SPAN class="ft86">a. </SPAN>Foreign passport; and</P></TD>
                                                        <TD class="tr6 td193"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD colspan=2 rowspan=2 class="tr3 td199"><P class="p154 ft80"><SPAN class="ft86">4. </SPAN>Original or certified copy of birth</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr24 td177"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD class="tr23 td190"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr23 td195"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr23 td53"><P class="p0 ft74">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr18 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr19 td194"><P class="p155 ft51"><SPAN class="ft49">b. </SPAN>Form <NOBR>I-94</NOBR> or Form <NOBR>I-94A</NOBR> that has</P></TD>
                                                    <TD class="tr18 td193"><P class="p0 ft41">&nbsp;</P></TD>
                                                    <TD class="tr18 td180"><P class="p103 ft49">6.</P></TD>
                                                    <TD class="tr18 td191"><P class="p103 ft51">Military dependent's ID card</P></TD>
                                                    <TD class="tr18 td182"><P class="p0 ft41">&nbsp;</P></TD>
                                                    <TD class="tr18 td183"><P class="p0 ft51">certificate issued by a State,</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr24 td177"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD class="tr23 td190"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr23 td195"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr23 td53"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr24 td182"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr11 td183"><P class="p0 ft81">county, municipal authority, or</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr13 td177"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr1 td194"><P class="p147 ft51">the following:</P></TD>
                                                        <TD class="tr13 td193"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr1 td180"><P class="p103 ft49">7.</P></TD>
                                                        <TD rowspan=2 class="tr1 td191"><P class="p103 ft51">U.S. Coast Guard Merchant Mariner</P></TD>
                                                        <TD class="tr13 td182"><P class="p0 ft46">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr17 td177"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr17 td193"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr17 td182"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr20 td183"><P class="p0 ft51">territory of the United States</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr24 td177"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr4 td194"><P class="p156 ft56">(1) The same name as the passport;</P></TD>
                                                        <TD class="tr24 td193"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD class="tr24 td180"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr4 td191"><P class="p103 ft51">Card</P></TD>
                                                        <TD class="tr24 td182"><P class="p0 ft75">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr11 td177"><P class="p0 ft43">&nbsp;</P></TD>
                                                        <TD class="tr11 td193"><P class="p0 ft43">&nbsp;</P></TD>
                                                        <TD class="tr11 td180"><P class="p0 ft43">&nbsp;</P></TD>
                                                        <TD class="tr11 td182"><P class="p0 ft43">&nbsp;</P></TD>
                                                        <TD class="tr11 td183"><P class="p0 ft81">bearing an official seal</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td177"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD rowspan=3 class="tr1 td194"><P class="p157 ft51">and</P></TD>
                                                        <TD class="tr7 td190"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td195"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td53"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr14 td182"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td183"><P class="p0 ft47">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr23 td177"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr14 td190"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD colspan=2 rowspan=3 class="tr4 td198"><P class="p103 ft51"><SPAN class="ft49">8. </SPAN>Native American tribal document</P></TD>
                                                        <TD class="tr14 td196"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td197"><P class="p0 ft47">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr13 td177"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td193"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD colspan=2 rowspan=3 class="tr4 td199"><P class="p154 ft51"><SPAN class="ft49">5. </SPAN>Native American tribal document</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr13 td177"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD rowspan=3 class="tr20 td194"><P class="p158 ft56">(2) An endorsement of the alien's</P></TD>
                                                        <TD class="tr13 td193"><P class="p0 ft46">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr23 td177"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr14 td190"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td195"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td53"><P class="p0 ft47">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr7 td177"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr5 td190"><P class="p0 ft58">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr1 td180"><P class="p103 ft49">9.</P></TD>
                                                        <TD rowspan=2 class="tr1 td191"><P class="p103 ft51">Driver's license issued by a Canadian</P></TD>
                                                        <TD class="tr5 td196"><P class="p0 ft58">&nbsp;</P></TD>
                                                        <TD class="tr5 td197"><P class="p0 ft58">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr11 td177"><P class="p0 ft43">&nbsp;</P></TD>
                                                        <TD class="tr11 td194"><P class="p159 ft68">nonimmigrant status as long as</P></TD>
                                                        <TD class="tr11 td193"><P class="p0 ft43">&nbsp;</P></TD>
                                                        <TD colspan=2 rowspan=2 class="tr15 td199"><P class="p154 ft51"><SPAN class="ft49">6. </SPAN>U.S. Citizen ID Card (Form <NOBR>I-197)</NOBR></P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr6 td177"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD rowspan=3 class="tr18 td194"><P class="p159 ft51">that period of endorsement has</P></TD>
                                                        <TD class="tr6 td193"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td180"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD rowspan=3 class="tr18 td191"><P class="p103 ft51">government authority</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr23 td177"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr14 td190"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr23 td180"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr14 td196"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td197"><P class="p0 ft47">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td177"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td193"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td180"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td182"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td183"><P class="p0 ft47">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td177"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td194"><P class="p159 ft80">not yet expired and the</P></TD>
                                                        <TD class="tr7 td190"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td195"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD class="tr7 td53"><P class="p0 ft45">&nbsp;</P></TD>
                                                        <TD colspan=2 rowspan=2 class="tr3 td199"><P class="p154 ft80"><SPAN class="ft86">7. </SPAN>Identification Card for Use of</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr22 td177"><P class="p0 ft73">&nbsp;</P></TD>
                                                        <TD class="tr22 td193"><P class="p0 ft73">&nbsp;</P></TD>
                                                        <TD colspan=2 rowspan=2 class="tr27 td198"><P class="p160 ft64">For persons under age 18 who are</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr1 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td194"><P class="p159 ft51">proposed employment is not in</P></TD>
                                                        <TD class="tr1 td193"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td182"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td183"><P class="p0 ft51">Resident Citizen in the United</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr20 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td194"><P class="p159 ft51">conflict with any restrictions or</P></TD>
                                                        <TD class="tr20 td193"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td180"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td191"><P class="p104 ft87">unable to present a document</P></TD>
                                                        <TD class="tr20 td182"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td183"><P class="p0 ft51">States (Form <NOBR>I-179)</NOBR></P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr21 td177"><P class="p0 ft72">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr9 td189"><P class="p159 ft56">limitations identified on the form.</P></TD>
                                                        <TD class="tr22 td190"><P class="p0 ft73">&nbsp;</P></TD>
                                                        <TD class="tr21 td180"><P class="p0 ft72">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr4 td191"><P class="p161 ft64">listed above:</P></TD>
                                                        <TD class="tr22 td196"><P class="p0 ft73">&nbsp;</P></TD>
                                                        <TD class="tr22 td197"><P class="p0 ft73">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr13 td188"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr13 td190"><P class="p0 ft46">&nbsp;</P></TD>
                                                        <TD class="tr6 td180"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr9 td182"><P class="p154 ft49">8.</P></TD>
                                                        <TD rowspan=2 class="tr9 td183"><P class="p0 ft51">Employment authorization</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 rowspan=2 class="tr9 td192"><P class="p102 ft51"><SPAN class="ft49">6. </SPAN>Passport from the Federated States of</P></TD>
                                                        <TD class="tr17 td190"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr17 td195"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr17 td53"><P class="p0 ft63">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr6 td193"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr11 td180"><P class="p103 ft85">10.</P></TD>
                                                        <TD rowspan=2 class="tr11 td191"><P class="p162 ft81">School record or report card</P></TD>
                                                        <TD class="tr6 td182"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr11 td183"><P class="p0 ft81">document issued by the</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr23 td177"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr20 td194"><P class="p102 ft51">Micronesia (FSM) or the Republic of</P></TD>
                                                        <TD class="tr23 td193"><P class="p0 ft74">&nbsp;</P></TD>
                                                        <TD class="tr23 td182"><P class="p0 ft74">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr22 td177"><P class="p0 ft73">&nbsp;</P></TD>
                                                        <TD class="tr17 td190"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr17 td195"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr17 td53"><P class="p0 ft63">&nbsp;</P></TD>
                                                        <TD class="tr22 td182"><P class="p0 ft73">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr1 td183"><P class="p0 ft51">Department of Homeland Security</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr24 td177"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td194"><P class="p102 ft80">the Marshall Islands (RMI) with Form</P></TD>
                                                        <TD class="tr24 td193"><P class="p0 ft75">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td180"><P class="p103 ft86">11.</P></TD>
                                                        <TD rowspan=2 class="tr3 td191"><P class="p105 ft56">Clinic, doctor, or hospital record</P></TD>
                                                        <TD class="tr24 td182"><P class="p0 ft75">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr6 td177"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td193"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td182"><P class="p0 ft71">&nbsp;</P></TD>
                                                        <TD class="tr6 td183"><P class="p0 ft71">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr22 td177"><P class="p0 ft73">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr3 td194"><P class="p102 ft80"><NOBR>I-94</NOBR> or Form <NOBR>I-94A</NOBR> indicating</P></TD>
                                                    <TD class="tr17 td190"><P class="p0 ft63">&nbsp;</P></TD>
                                                    <TD class="tr17 td195"><P class="p0 ft63">&nbsp;</P></TD>
                                                    <TD class="tr17 td53"><P class="p0 ft63">&nbsp;</P></TD>
                                                    <TD class="tr22 td182"><P class="p0 ft73">&nbsp;</P></TD>
                                                    <TD class="tr22 td183"><P class="p0 ft73">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td177"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD class="tr14 td193"><P class="p0 ft47">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr4 td180"><P class="p103 ft49">12.</P></TD>
                                                        <TD rowspan=2 class="tr4 td191"><P class="p163 ft56"><NOBR>Day-care</NOBR> or nursery school record</P></TD>
                                                    <TD class="tr14 td182"><P class="p0 ft47">&nbsp;</P></TD>
                                                    <TD class="tr14 td183"><P class="p0 ft47">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr20 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td194"><P class="p102 ft51">non immigrant admission under the</P></TD>
                                                        <TD class="tr20 td193"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td182"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td183"><P class="p0 ft41">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr20 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td194"><P class="p102 ft51">Compact of Free Association Between</P></TD>
                                                        <TD class="tr20 td193"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td180"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td191"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td182"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr20 td183"><P class="p0 ft41">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr1 td177"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td194"><P class="p102 ft51">the United States and the FSM or RMI</P></TD>
                                                        <TD class="tr1 td193"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td180"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td191"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td182"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr1 td183"><P class="p0 ft41">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr18 td188"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr18 td189"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr18 td200"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr18 td195"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr18 td53"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr18 td196"><P class="p0 ft41">&nbsp;</P></TD>
                                                        <TD class="tr18 td197"><P class="p0 ft41">&nbsp;</P></TD>
                                                    </TR>
                                                </TABLE>
                                                <P class="p164 ft66">Illustrations of many of these documents appear in Part 8 of the Handbook for Employers <NOBR>(M-274).</NOBR></P>
                                                <P class="p165 ft52">Refer to Section 2 of the instructions, titled "Employer or Authorized Representative Review and Verification," for more information about acceptable receipts.</P>
                                            </DIV>
                                            <DIV id="id_2">
                                                <TABLE cellpadding=0 cellspacing=0 class="t2">
                                                    <TR>
                                                        <TD class="tr1 td12"><P class="p0 ft9">Form <NOBR>I-9</NOBR> 03/08/13 N</P></TD>
                                                    <TD class="tr1 td13"><P class="p0 ft9">Page 9 of 9</P></TD>
                                                    </TR>
                                                </TABLE>
                                            </DIV>
                                        </DIV>
                                        <input type="hidden" name="sid" value="<?php echo $employer['sid'] ?>">
                                        <input type="submit" class="submit-btn" value="Save">
<?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
<?php $this->load->view($left_navigation); ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $('.startdate').datepicker({dateFormat: 'mm/dd/yy', changeMonth: true, changeYear: true, yearRange: "-100:+50", }).val();
</script>