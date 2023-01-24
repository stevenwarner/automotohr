<div class="main-content">
    <div class="dashboard-wrp">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-xs-12 col-sm-4">
                    <?php $this->load->view('manage_employer/profile_left_menu_personal'); ?>
                </div>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-8">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                            <div class="page-header-area">
                                <span class="page-heading down-arrow"><?php $this->load->view('manage_employer/company_logo_name'); ?><?php echo $title; ?></span>
                            </div>
                            <div class="dashboard-conetnt-wrp">
                                <div class="table-responsive table-outer">
                                    <div class="i9form-wrp">
                                        <STYLE type="text/css">

                                            body {margin-top: 0px;margin-left: 0px;}

                                            #page_1 {position:relative; overflow: hidden;margin: 45px 0px 31px 48px;padding: 0px;border: none;width: 768px;}
                                            #page_1 #id_1 {border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 724px;overflow: hidden;}
                                            #page_1 #id_1 #id_1_1 {float:left;border:none;margin: 1px 0px 0px 0px;padding: 0px;border:none;width: 250px;overflow: hidden;}
                                            #page_1 #id_1 #id_1_2 {float:left;border:none;margin: 3px 0px 0px 0px;padding: 0px;border:none;width: 222px;overflow: hidden;}
                                            #page_1 #id_1 #id_1_3 {float:left;border:none;margin: 0px 0px 0px 27px;padding: 0px;border:none;width: 225px;overflow: hidden;}
                                            #page_1 #id_2 {border:none;margin: 4px 0px 0px 0px;padding: 0px;border:none;width: 768px;overflow: hidden;}

                                            #page_1 #dimg1 {position:absolute;top:2px;left:0px;z-index:-1;width:720px;height:881px;}
                                            #page_1 #dimg1 #img1 {width:720px;height:881px;}




                                            #page_2 {position:relative; overflow: hidden;margin: 46px 0px 35px 47px;padding: 0px;border: none;width: 769px;}
                                            #page_2 #id_1 {border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 769px;overflow: hidden;}
                                            #page_2 #id_2 {border:none;margin: 3px 0px 0px 1px;padding: 0px;border:none;width: 716px;overflow: hidden;}
                                            #page_2 #id_2 #id_2_1 {float:left;border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 346px;overflow: hidden;}
                                            #page_2 #id_2 #id_2_2 {float:left;border:none;margin: 1px 0px 0px 28px;padding: 0px;border:none;width: 342px;overflow: hidden;}

                                            #page_2 #dimg1 {position:absolute;top:17px;left:0px;z-index:-1;width:722px;height:834px;}
                                            #page_2 #dimg1 #img1 {width:722px;height:834px;}




                                            .ft0{font: 24px 'Arial';line-height: 27px;}
                                            .ft1{font: 8px 'Arial';line-height: 10px;}
                                            .ft2{font: 9px 'Arial';line-height: 10px;}
                                            .ft3{font: italic 9px 'Arial';line-height: 11px;}
                                            .ft4{font: 9px 'Arial';line-height: 11px;}
                                            .ft5{font: 9px 'Arial';line-height: 12px;}
                                            .ft6{font: 9px 'Arial';margin-left: 2px;line-height: 12px;}
                                            .ft7{font: 9px 'Arial';line-height: 9px;}
                                            .ft8{font: italic 8px 'Arial';line-height: 9px;}
                                            .ft9{font: 8px 'Arial';line-height: 9px;}
                                            .ft10{font: 13px 'Arial';line-height: 16px;}
                                            .ft11{font: 11px 'Arial';line-height: 13px;}
                                            .ft12{font: 1px 'Arial';line-height: 1px;}
                                            .ft13{font: 45px 'Times New Roman';line-height: 37px;}
                                            .ft14{font: 11px 'Arial';line-height: 14px;}
                                            .ft15{font: 10px 'Arial';line-height: 13px;}
                                            .ft16{font: 11px 'Arial';margin-left: 20px;line-height: 14px;}
                                            .ft17{font: 1px 'Arial';line-height: 4px;}
                                            .ft18{font: 1px 'Arial';line-height: 3px;}
                                            .ft19{font: 11px 'Arial';line-height: 9px;}
                                            .ft20{font: bold 8px 'MS PGothic';line-height: 8px;}
                                            .ft21{font: 1px 'Arial';line-height: 8px;}
                                            .ft22{font: 28px 'Times New Roman';line-height: 33px;}
                                            .ft23{font: 1px 'Arial';line-height: 7px;}
                                            .ft24{font: 1px 'Arial';line-height: 13px;}
                                            .ft25{font: 1px 'Arial';line-height: 11px;}
                                            .ft26{font: 11px 'Arial';line-height: 11px;}
                                            .ft27{font: 1px 'Arial';line-height: 12px;}
                                            .ft28{font: 11px 'Arial';line-height: 12px;}
                                            .ft29{font: bold 32px 'Arial';line-height: 37px;}
                                            .ft30{font: 19px 'Arial';line-height: 22px;}
                                            .ft31{font: bold 28px 'Arial';line-height: 33px;}
                                            .ft32{font: 28px 'Arial';line-height: 32px;}
                                            .ft33{font: bold 8px 'MS PGothic';line-height: 8px;position: relative; bottom: 2px;}
                                            .ft34{font: 9px 'Arial';line-height: 8px;}
                                            .ft35{font: 8px 'Arial';line-height: 6px;}
                                            .ft36{font: 1px 'Arial';line-height: 10px;}
                                            .ft37{font: 1px 'Arial';line-height: 2px;}
                                            .ft38{font: 12px 'Arial';line-height: 15px;}
                                            .ft39{font: 11px 'Arial';margin-left: 19px;line-height: 14px;}
                                            .ft40{font: 11px 'Arial';margin-left: 3px;line-height: 14px;}
                                            .ft41{font: bold 7px 'MS PGothic';line-height: 7px;position: relative; bottom: 1px;}
                                            .ft42{font: italic 11px 'Arial';line-height: 14px;}
                                            .ft43{font: 8px 'Arial';margin-left: 19px;line-height: 11px;}
                                            .ft44{font: 8px 'Arial';line-height: 11px;}
                                            .ft45{font: 11px 'Arial';line-height: 10px;}
                                            .ft46{font: 1px 'Arial';line-height: 5px;}
                                            .ft47{font: 45px 'Times New Roman';line-height: 49px;}
                                            .ft48{font: italic 13px 'Arial';line-height: 16px;}
                                            .ft49{font: 13px 'Arial';line-height: 15px;}

                                            .p0{text-align: left;margin-top: 0px;margin-bottom: 0px;}
                                            .p1{text-align: justify;padding-right: 27px;margin-top: 9px;margin-bottom: 0px;}
                                            .p2{text-align: left;padding-right: 32px;margin-top: 0px;margin-bottom: 0px;}
                                            .p3{text-align: left;padding-right: 31px;margin-top: 0px;margin-bottom: 0px;}
                                            .p4{text-align: left;padding-right: 35px;margin-top: 0px;margin-bottom: 0px;text-indent: 9px;}
                                            .p5{text-align: justify;margin-top: 2px;margin-bottom: 0px;}
                                            .p6{text-align: justify;margin-top: 4px;margin-bottom: 0px;}
                                            .p7{text-align: justify;padding-right: 49px;margin-top: 3px;margin-bottom: 0px;}
                                            .p8{text-align: left;padding-right: 4px;margin-top: 0px;margin-bottom: 0px;}
                                            .p9{text-align: left;padding-right: 4px;margin-top: 0px;margin-bottom: 0px;text-indent: 9px;}
                                            .p10{text-align: left;padding-right: 8px;margin-top: 0px;margin-bottom: 0px;}
                                            .p11{text-align: left;margin-top: 9px;margin-bottom: 0px;}
                                            .p12{text-align: left;padding-right: 9px;margin-top: 6px;margin-bottom: 0px;}
                                            .p13{text-align: left;padding-right: 19px;margin-top: 7px;margin-bottom: 0px;}
                                            .p14{text-align: left;padding-right: 4px;margin-top: 1px;margin-bottom: 0px;}
                                            .p15{text-align: left;padding-right: 1px;margin-top: 4px;margin-bottom: 0px;}
                                            .p16{text-align: left;padding-left: 185px;margin-top: 0px;margin-bottom: 0px;}
                                            .p17{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p18{text-align: right;padding-right: 19px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p19{text-align: justify;padding-left: 29px;padding-right: 136px;margin-top: 1px;margin-bottom: 0px;}
                                            .p20{text-align: right;padding-right: 49px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p21{text-align: right;padding-right: 5px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p22{text-align: left;padding-left: 5px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p23{text-align: left;padding-left: 14px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p24{text-align: left;padding-left: 125px;margin-top: 0px;margin-bottom: 0px;}
                                            .p25{text-align: left;padding-left: 139px;margin-top: 9px;margin-bottom: 0px;}
                                            .p26{text-align: left;padding-left: 61px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p27{text-align: left;padding-left: 8px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p28{text-align: right;padding-right: 18px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p29{text-align: center;padding-right: 1px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p30{text-align: right;padding-right: 11px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p31{text-align: left;padding-left: 9px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p32{text-align: left;padding-left: 6px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p33{text-align: left;padding-left: 26px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p34{text-align: left;padding-left: 7px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p35{text-align: right;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p36{text-align: right;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p37{text-align: justify;padding-left: 13px;margin-top: 1px;margin-bottom: 0px;}
                                            .p38{text-align: justify;padding-left: 38px;margin-top: 2px;margin-bottom: 0px;}
                                            .p39{text-align: left;padding-left: 38px;margin-top: 1px;margin-bottom: 0px;}
                                            .p40{text-align: left;margin-top: 1px;margin-bottom: 0px;}
                                            .p41{text-align: right;padding-right: 10px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p42{text-align: left;padding-left: 10px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p43{text-align: left;padding-left: 103px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p44{text-align: left;padding-left: 233px;margin-top: 3px;margin-bottom: 0px;}
                                            .p45{text-align: left;padding-left: 8px;margin-top: 1px;margin-bottom: 0px;}
                                            .p46{text-align: justify;padding-left: 39px;padding-right: 187px;margin-top: 0px;margin-bottom: 0px;text-indent: -25px;}
                                            .p47{text-align: right;padding-right: 80px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p48{text-align: right;padding-right: 21px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p49{text-align: left;padding-left: 102px;margin-top: 1px;margin-bottom: 0px;}
                                            .p50{text-align: right;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p51{text-align: left;padding-left: 19px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p52{text-align: left;padding-left: 3px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p53{text-align: right;padding-right: 13px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p54{text-align: left;padding-left: 20px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p55{text-align: right;padding-right: 27px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p56{text-align: right;padding-right: 24px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p57{text-align: right;padding-right: 26px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p58{text-align: right;padding-right: 33px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p59{text-align: right;padding-right: 38px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p60{text-align: right;padding-right: 1px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p61{text-align: right;padding-right: 12px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p62{text-align: right;padding-right: 30px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p63{text-align: right;padding-right: 20px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p64{text-align: right;padding-right: 22px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
                                            .p65{text-align: left;margin-top: 0px;margin-bottom: 0px;text-indent: 9px;}
                                            .p66{text-align: justify;padding-right: 3px;margin-top: 7px;margin-bottom: 0px;text-indent: 9px;}
                                            .p67{text-align: justify;padding-right: 3px;margin-top: 0px;margin-bottom: 0px;text-indent: 9px;}

                                            .td0{padding: 0px;margin: 0px;width: 29px;vertical-align: bottom;}
                                            .td1{padding: 0px;margin: 0px;width: 633px;vertical-align: bottom;}
                                            .td2{padding: 0px;margin: 0px;width: 9px;vertical-align: bottom;}
                                            .td3{padding: 0px;margin: 0px;width: 86px;vertical-align: bottom;}
                                            .td4{padding: 0px;margin: 0px;width: 470px;vertical-align: bottom;}
                                            .td5{padding: 0px;margin: 0px;width: 77px;vertical-align: bottom;}
                                            .td6{padding: 0px;margin: 0px;width: 620px;vertical-align: bottom;}
                                            .td7{padding: 0px;margin: 0px;width: 71px;vertical-align: bottom;}
                                            .td8{padding: 0px;margin: 0px;width: 32px;vertical-align: bottom;}
                                            .td9{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 39px;vertical-align: bottom;}
                                            .td10{padding: 0px;margin: 0px;width: 73px;vertical-align: bottom;}
                                            .td11{padding: 0px;margin: 0px;width: 18px;vertical-align: bottom;}
                                            .td12{padding: 0px;margin: 0px;width: 561px;vertical-align: bottom;}
                                            .td13{padding: 0px;margin: 0px;width: 600px;vertical-align: bottom;}
                                            .td14{padding: 0px;margin: 0px;width: 529px;vertical-align: bottom;}
                                            .td15{padding: 0px;margin: 0px;width: 39px;vertical-align: bottom;}
                                            .td16{padding: 0px;margin: 0px;width: 0px;vertical-align: bottom;}
                                            .td17{padding: 0px;margin: 0px;width: 30px;vertical-align: bottom;}
                                            .td18{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 75px;vertical-align: bottom;}
                                            .td19{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 509px;vertical-align: bottom;}
                                            .td20{padding: 0px;margin: 0px;width: 104px;vertical-align: bottom;}
                                            .td21{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 104px;vertical-align: bottom;}
                                            .td22{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 105px;vertical-align: bottom;}
                                            .td23{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 509px;vertical-align: bottom;}
                                            .td24{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 105px;vertical-align: bottom;}
                                            .td25{padding: 0px;margin: 0px;width: 76px;vertical-align: bottom;}
                                            .td26{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 134px;vertical-align: bottom;}
                                            .td27{padding: 0px;margin: 0px;width: 136px;vertical-align: bottom;}
                                            .td28{padding: 0px;margin: 0px;width: 84px;vertical-align: bottom;}
                                            .td29{padding: 0px;margin: 0px;width: 100px;vertical-align: bottom;}
                                            .td30{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 20px;vertical-align: bottom;}
                                            .td31{padding: 0px;margin: 0px;width: 181px;vertical-align: bottom;}
                                            .td32{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 30px;vertical-align: bottom;}
                                            .td33{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 210px;vertical-align: bottom;}
                                            .td34{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 125px;vertical-align: bottom;}
                                            .td35{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 11px;vertical-align: bottom;}
                                            .td36{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 84px;vertical-align: bottom;}
                                            .td37{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 57px;vertical-align: bottom;}
                                            .td38{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 20px;vertical-align: bottom;}
                                            .td39{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 77px;vertical-align: bottom;}
                                            .td40{padding: 0px;margin: 0px;width: 211px;vertical-align: bottom;}
                                            .td41{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 124px;vertical-align: bottom;}
                                            .td42{padding: 0px;margin: 0px;width: 11px;vertical-align: bottom;}
                                            .td43{padding: 0px;margin: 0px;width: 298px;vertical-align: bottom;}
                                            .td44{padding: 0px;margin: 0px;width: 135px;vertical-align: bottom;}
                                            .td45{padding: 0px;margin: 0px;width: 354px;vertical-align: bottom;}
                                            .td46{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 211px;vertical-align: bottom;}
                                            .td47{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 124px;vertical-align: bottom;}
                                            .td48{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 21px;vertical-align: bottom;}
                                            .td49{padding: 0px;margin: 0px;width: 343px;vertical-align: bottom;}
                                            .td50{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 76px;vertical-align: bottom;}
                                            .td51{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 135px;vertical-align: bottom;}
                                            .td52{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 343px;vertical-align: bottom;}
                                            .td53{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 585px;vertical-align: bottom;}
                                            .td54{padding: 0px;margin: 0px;width: 347px;vertical-align: bottom;}
                                            .td55{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 238px;vertical-align: bottom;}
                                            .td56{padding: 0px;margin: 0px;width: 443px;vertical-align: bottom;}
                                            .td57{padding: 0px;margin: 0px;width: 96px;vertical-align: bottom;}
                                            .td58{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 181px;vertical-align: bottom;}
                                            .td59{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 443px;vertical-align: bottom;}
                                            .td60{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 96px;vertical-align: bottom;}
                                            .td61{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 413px;vertical-align: bottom;}
                                            .td62{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 95px;vertical-align: bottom;}
                                            .td63{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 442px;vertical-align: bottom;}
                                            .td64{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 95px;vertical-align: bottom;}
                                            .td65{padding: 0px;margin: 0px;width: 689px;vertical-align: bottom;}
                                            .td66{padding: 0px;margin: 0px;width: 31px;vertical-align: bottom;}
                                            .td67{padding: 0px;margin: 0px;width: 22px;vertical-align: bottom;}
                                            .td68{padding: 0px;margin: 0px;width: 555px;vertical-align: bottom;}
                                            .td69{padding: 0px;margin: 0px;width: 40px;vertical-align: bottom;}
                                            .td70{padding: 0px;margin: 0px;width: 87px;vertical-align: bottom;}
                                            .td71{padding: 0px;margin: 0px;width: 67px;vertical-align: bottom;}
                                            .td72{padding: 0px;margin: 0px;width: 273px;vertical-align: bottom;}
                                            .td73{padding: 0px;margin: 0px;width: 215px;vertical-align: bottom;}
                                            .td74{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 87px;vertical-align: bottom;}
                                            .td75{padding: 0px;margin: 0px;width: 463px;vertical-align: bottom;}
                                            .td76{padding: 0px;margin: 0px;width: 27px;vertical-align: bottom;}
                                            .td77{padding: 0px;margin: 0px;width: 6px;vertical-align: bottom;}
                                            .td78{padding: 0px;margin: 0px;width: 41px;vertical-align: bottom;}
                                            .td79{padding: 0px;margin: 0px;width: 33px;vertical-align: bottom;}
                                            .td80{padding: 0px;margin: 0px;width: 20px;vertical-align: bottom;}
                                            .td81{padding: 0px;margin: 0px;width: 61px;vertical-align: bottom;}
                                            .td82{padding: 0px;margin: 0px;width: 10px;vertical-align: bottom;}
                                            .td83{padding: 0px;margin: 0px;width: 563px;vertical-align: bottom;}
                                            .td84{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 20px;vertical-align: bottom;}
                                            .td85{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 6px;vertical-align: bottom;}
                                            .td86{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 61px;vertical-align: bottom;}
                                            .td87{padding: 0px;margin: 0px;width: 266px;vertical-align: bottom;}
                                            .td88{padding: 0px;margin: 0px;width: 250px;vertical-align: bottom;}
                                            .td89{padding: 0px;margin: 0px;width: 516px;vertical-align: bottom;}
                                            .td90{padding: 0px;margin: 0px;width: 583px;vertical-align: bottom;}
                                            .td91{padding: 0px;margin: 0px;width: 177px;vertical-align: bottom;}
                                            .td92{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 40px;vertical-align: bottom;}
                                            .td93{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 41px;vertical-align: bottom;}
                                            .td94{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 563px;vertical-align: bottom;}
                                            .td95{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 9px;vertical-align: bottom;}
                                            .td96{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 33px;vertical-align: bottom;}
                                            .td97{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 10px;vertical-align: bottom;}
                                            .td98{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 28px;vertical-align: bottom;}
                                            .td99{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 51px;vertical-align: bottom;}
                                            .td100{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 113px;vertical-align: bottom;}
                                            .td101{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;}
                                            .td102{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 61px;vertical-align: bottom;}
                                            .td103{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 52px;vertical-align: bottom;}
                                            .td104{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 54px;vertical-align: bottom;}
                                            .td105{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 27px;vertical-align: bottom;}
                                            .td106{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 93px;vertical-align: bottom;}
                                            .td107{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 154px;vertical-align: bottom;}
                                            .td108{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 46px;vertical-align: bottom;}
                                            .td109{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 181px;vertical-align: bottom;}
                                            .td110{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 59px;vertical-align: bottom;}
                                            .td111{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 107px;vertical-align: bottom;}
                                            .td112{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 66px;vertical-align: bottom;}
                                            .td113{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 104px;vertical-align: bottom;}
                                            .td114{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 61px;vertical-align: bottom;}
                                            .td115{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 114px;vertical-align: bottom;}
                                            .td116{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 108px;vertical-align: bottom;}
                                            .td117{padding: 0px;margin: 0px;width: 89px;vertical-align: bottom;}
                                            .td118{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 19px;vertical-align: bottom;}
                                            .td119{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 37px;vertical-align: bottom;}
                                            .td120{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 50px;vertical-align: bottom;}
                                            .td121{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 66px;vertical-align: bottom;}
                                            .td122{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 53px;vertical-align: bottom;}
                                            .td123{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 56px;vertical-align: bottom;}
                                            .td124{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 52px;vertical-align: bottom;}
                                            .td125{padding: 0px;margin: 0px;width: 37px;vertical-align: bottom;}
                                            .td126{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 50px;vertical-align: bottom;}
                                            .td127{padding: 0px;margin: 0px;width: 54px;vertical-align: bottom;}
                                            .td128{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 53px;vertical-align: bottom;}
                                            .td129{padding: 0px;margin: 0px;width: 56px;vertical-align: bottom;}
                                            .td130{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 52px;vertical-align: bottom;}
                                            .td131{padding: 0px;margin: 0px;width: 28px;vertical-align: bottom;}
                                            .td132{padding: 0px;margin: 0px;width: 46px;vertical-align: bottom;}
                                            .td133{padding: 0px;margin: 0px;width: 8px;vertical-align: bottom;}
                                            .td134{padding: 0px;margin: 0px;width: 52px;vertical-align: bottom;}
                                            .td135{padding: 0px;margin: 0px;width: 47px;vertical-align: bottom;}
                                            .td136{padding: 0px;margin: 0px;width: 48px;vertical-align: bottom;}
                                            .td137{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 39px;vertical-align: bottom;}
                                            .td138{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 59px;vertical-align: bottom;}
                                            .td139{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 107px;vertical-align: bottom;}
                                            .td140{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 39px;vertical-align: bottom;}
                                            .td141{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 19px;vertical-align: bottom;}

                                            .tr0{height: 13px;}
                                            .tr1{height: 19px;}
                                            .tr2{height: 37px;}
                                            .tr3{height: 18px;}
                                            .tr4{height: 14px;}
                                            .tr5{height: 16px;}
                                            .tr6{height: 17px;}
                                            .tr7{height: 4px;}
                                            .tr8{height: 3px;}
                                            .tr9{height: 9px;}
                                            .tr10{height: 8px;}
                                            .tr11{height: 71px;}
                                            .tr12{height: 7px;}
                                            .tr13{height: 24px;}
                                            .tr14{height: 11px;}
                                            .tr15{height: 12px;}
                                            .tr16{height: 15px;}
                                            .tr17{height: 29px;}
                                            .tr18{height: 20px;}
                                            .tr19{height: 6px;}
                                            .tr20{height: 10px;}
                                            .tr21{height: 2px;}
                                            .tr22{height: 22px;}
                                            .tr23{height: 5px;}
                                            .tr24{height: 49px;}

                                            .t0{width: 671px;font: 11px 'Arial';}
                                            .t1{width: 720px;font: 11px 'Arial';}
                                            .t2{width: 720px;margin-top: 4px;font: 9px 'Arial';}
                                            .t3{width: 720px;margin-top: 5px;font: 9px 'Arial';}
                                            .t4{width: 720px;margin-left: 1px;font: 9px 'Arial';}
                                            .t5{width: 704px;margin-left: 8px;font: 11px 'Arial';}
                                            .t6{width: 722px;font: 9px 'Arial';}

                                        </STYLE>
                                        <DIV id="page_1" class="page_1">
                                            <DIV id="id_1">
                                                <DIV id="id_1_1">
                                                    <P class="p0 ft0">Form <NOBR>W-4</NOBR> (2016)</P>
                                                    <P class="p1 ft1">Purpose. Complete Form <NOBR>W-4</NOBR> so that your employer can withhold the correct federal income tax from your pay. Consider completing a new Form <NOBR>W-4</NOBR> each year and when your personal or financial situation changes.</P>
                                                    <P class="p2 ft2">Exemption from withholding. If you are exempt, complete only lines 1, 2, 3, 4, and 7 and sign the form to validate it. Your exemption for 2016 expires February 15, 2017. See Pub. 505, Tax Withholding and Estimated Tax.</P>
                                                    <P class="p3 ft2">Note: If another person can claim you as a dependent on his or her tax return, you cannot claim exemption from withholding if your income exceeds $1,050 and includes more than $350 of unearned income (for example, interest and dividends).</P>
                                                    <P class="p4 ft4"><SPAN class="ft3">Exceptions</SPAN>. An employee may be able to claim exemption from withholding even if the employee is a dependent, if the employee:</P>
                                                    <P class="p5 ft5"><SPAN class="ft5">•</SPAN><SPAN class="ft6">Is age 65 or older,</SPAN></P>
                                                    <P class="p6 ft5"><SPAN class="ft5">•</SPAN><SPAN class="ft6">Is blind, or</SPAN></P>
                                                    <P class="p7 ft5"><SPAN class="ft5">•</SPAN><SPAN class="ft6">Will claim adjustments to income; tax credits; or itemized deductions, on his or her tax return.</SPAN></P>
                                                </DIV>
                                                <DIV id="id_1_2">
                                                    <P class="p8 ft4">The exceptions do not apply to supplemental wages greater than $1,000,000.</P>
                                                    <P class="p8 ft2">Basic instructions. If you are not exempt, complete the Personal Allowances Worksheet below. The worksheets on page 2 further adjust your withholding allowances based on itemized deductions, certain credits, adjustments to income, or <NOBR>two-earners/multiple</NOBR> jobs situations.</P>
                                                    <P class="p9 ft2">Complete all worksheets that apply. However, you may claim fewer (or zero) allowances. For regular wages, withholding must be based on allowances you claimed and may not be a flat amount or percentage of wages.</P>
                                                    <P class="p10 ft7">Head of household. Generally, you can claim head of household filing status on your tax return only if you are unmarried and pay more than 50% of the costs of keeping up a home for yourself and your dependent(s) or other qualifying individuals. See Pub. 501, Exemptions, Standard Deduction, and Filing Information, for information.</P>
                                                    <P class="p11 ft1">Tax credits. You can take projected tax credits into account in figuring your allowable number of withholding allowances. Credits for child or dependent care expenses and the child tax credit may be claimed using the Personal Allowances Worksheet below. See Pub. 505 for information on converting your other credits into withholding allowances.</P>
                                                </DIV>
                                                <DIV id="id_1_3">
                                                    <P class="p0 ft7">Nonwage income. If you have a large amount of nonwage income, such as interest or dividends, consider making estimated tax payments using Form <NOBR>1040-ES,</NOBR> Estimated Tax for Individuals. Otherwise, you may owe additional tax. If you have pension or annuity income, see Pub. 505 to find out if you should adjust your withholding on Form <NOBR>W-4</NOBR> or <NOBR>W-4P.</NOBR></P>
                                                    <P class="p12 ft7">Two earners or multiple jobs. If you have a working spouse or more than one job, figure the total number of allowances you are entitled to claim on all jobs using worksheets from only one Form <NOBR>W-4.</NOBR> Your withholding usually will be most accurate when all allowances are claimed on the Form <NOBR>W-4</NOBR> for the highest paying job and zero allowances are claimed on the others. See Pub. 505 for details.</P>
                                                    <P class="p13 ft2">Nonresident alien. If you are a nonresident alien, see Notice 1392, Supplemental Form <NOBR>W-4</NOBR> Instructions for Nonresident Aliens, before completing this form.</P>
                                                    <P class="p14 ft2">Check your withholding. After your Form <NOBR>W-4</NOBR> takes effect, use Pub. 505 to see how the amount you are having withheld compares to your projected total tax for 2016. See Pub. 505, especially if your earnings exceed $130,000 (Single) or $180,000 (Married).</P>
                                                    <P class="p15 ft9">Future developments. Information about any future developments affecting Form <NOBR>W-4</NOBR> (such as legislation enacted after we release it) will be posted at <SPAN class="ft8">www.irs.gov/w4</SPAN>.</P>
                                                </DIV>
                                            </DIV>
                                            <DIV id="id_2">
                                                <P class="p16 ft10">Personal Allowances Worksheet (Keep for your records.)</P>
                                                <TABLE cellpadding=0 cellspacing=0 class="t0">
                                                    <TR>
                                                        <TD class="tr0 td0"><P class="p17 ft11">A</P></TD>
                                                        <TD colspan=3 class="tr0 td1"><P class="p17 ft11">Enter “1” for yourself if no one else can claim you as a dependent . . . . . . . . . . . . . . . . . .</P></TD>
                                                        <TD class="tr0 td2"><P class="p17 ft11">A</P></TD>
                                                        <TD class="tr0 td2">
                                                            <input type="text" class="w4dinput">
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr1 td0"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr2 td3"><P class="p17 ft14">Enter “1” if: <SPAN class="ft13">{</SPAN></P></TD>
                                                        <TD class="tr1 td4"><P class="p17 ft14">• You are single and have only one job; or</P></TD>
                                                        <TD rowspan=2 class="tr2 td5"><P class="p18 ft14"><SPAN class="ft13">} </SPAN>. . .</P></TD>
                                                        <TD class="tr1 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr3 td0"><P class="p17 ft14">B</P></TD>
                                                        <TD class="tr3 td4"><P class="p17 ft14">• You are married, have only one job, and your spouse does not work; or</P></TD>
                                                        <TD class="tr3 td2"><P class="p17 ft14">B</P></TD>
                                                        <TD class="tr0 td2">
                                                            <input type="text" class="w4dinput">
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr4 td0"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td3"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td4"><P class="p17 ft15">• Your wages from a second job or your spouse’s wages (or the total of both) are $1,500 or less.</P></TD>
                                                        <TD class="tr4 td5"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td0"><P class="p17 ft14">C</P></TD>
                                                        <TD colspan=3 class="tr5 td1"><P class="p17 ft14">Enter “1” for your spouse. But, you may choose to enter <NOBR>“-0-”</NOBR> if you are married and have either a working spouse or more</P></TD>
                                                    <TD class="tr5 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td0"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr5 td1"><P class="p17 ft14">than one job. (Entering <NOBR>“-0-”</NOBR> may help you avoid having too little tax withheld.) . . . . . . . . . . . . . .</P></TD>
                                                    <TD class="tr5 td2"><P class="p17 ft14">C</P></TD>
                                                    <TD class="tr0 td2">
                                                            <input type="text" class="w4dinput">
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td0"><P class="p17 ft14">D</P></TD>
                                                        <TD colspan=3 class="tr5 td1"><P class="p17 ft14">Enter number of dependents (other than your spouse or yourself) you will claim on your tax return . . . . . . . .</P></TD>
                                                        <TD class="tr5 td2"><P class="p17 ft14">D</P></TD>
                                                        <TD class="tr0 td2">
                                                            <input type="text" class="w4dinput">
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td0"><P class="p17 ft14">E</P></TD>
                                                        <TD colspan=3 class="tr5 td1"><P class="p17 ft14">Enter “1” if you will file as head of household on your tax return (see conditions under Head of household above) . .</P></TD>
                                                        <TD class="tr5 td2"><P class="p17 ft14">E</P></TD>
                                                        <TD class="tr0 td2">
                                                            <input type="text" class="w4dinput">
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td0"><P class="p17 ft14">F</P></TD>
                                                        <TD colspan=3 class="tr5 td1"><P class="p17 ft14">Enter “1” if you have at least $2,000 of child or dependent care expenses for which you plan to claim a credit . . .</P></TD>
                                                        <TD class="tr5 td2"><P class="p17 ft14">F</P></TD>
                                                        <TD class="tr0 td2">
                                                            <input type="text" class="w4dinput">
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr6 td0"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr6 td1"><P class="p17 ft14">(Note: Do not include child support payments. See Pub. 503, Child and Dependent Care Expenses, for details.)</P></TD>
                                                        <TD class="tr6 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                </TABLE>
                                                <P class="p5 ft14"><SPAN class="ft14">G</SPAN><SPAN class="ft16">Child Tax Credit (including additional child tax credit). See Pub. 972, Child Tax Credit, for more information.</SPAN></P>
                                                <P class="p19 ft14">• If your total income will be less than $70,000 ($100,000 if married), enter “2” for each eligible child; then less “1” if you have two to four eligible children or less “2” if you have five or more eligible children.</P>
                                                <TABLE cellpadding=0 cellspacing=0 class="t1">
                                                    <TR>
                                                        <TD class="tr5 td0"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr5 td6"><P class="p17 ft14">• If your total income will be between $70,000 and $84,000 ($100,000 and $119,000 if married), enter “1” for each eligible child . .</P></TD>
                                                        <TD colspan=2 class="tr5 td7"><P class="p20 ft14">G</P></TD>
                                                        <TD class="tr0 td2">
                                                            <input type="text" class="w4dinput">
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr9 td0"><P class="p17 ft19">H</P></TD>
                                                        <TD colspan=3 class="tr9 td6"><P class="p17 ft7">Add lines A through G and enter total here. (Note: This may be different from the number of exemptions you claim on your tax return.)</P></TD>
                                                        <TD colspan=2 class="tr9 td7"><P class="p20 ft20">▶ <SPAN class="ft19">H</SPAN></P></TD>
                                                        <TD class="tr0 td2">
                                                            <input type="text" class="w4dinput">
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr0 td0"><P class="p17 ft24">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr13 td10"><P class="p17 ft14">For accuracy,</P></TD>
                                                        <TD colspan=3 class="tr0 td13"><P class="p22 ft11">• If you plan to itemize or claim adjustments to income and want to reduce your withholding, see the Deductions</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td0"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td14"><P class="p23 ft26">and Adjustments Worksheet on page 2.</P></TD>
                                                        <TD class="tr14 td8"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td15"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr15 td0"><P class="p17 ft27">&nbsp;</P></TD>
                                                        <TD class="tr15 td10"><P class="p17 ft28">complete all</P></TD>
                                                        <TD colspan=3 rowspan=2 class="tr16 td13"><P class="p22 ft14">• If you are single and have more than one job or are married and you and your spouse both work and the combined</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr8 td0"><P class="p17 ft18">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr16 td10"><P class="p17 ft14">worksheets</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr15 td0"><P class="p17 ft27">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr15 td13"><P class="p23 ft28">earnings from all jobs exceed $50,000 ($20,000 if married), see the <NOBR>Two-Earners/Multiple</NOBR> Jobs Worksheet on page 2</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr15 td0"><P class="p17 ft27">&nbsp;</P></TD>
                                                        <TD class="tr15 td10"><P class="p17 ft28">that apply.</P></TD>
                                                        <TD class="tr15 td14"><P class="p23 ft28">to avoid having too little tax withheld.</P></TD>
                                                        <TD class="tr15 td8"><P class="p17 ft27">&nbsp;</P></TD>
                                                        <TD class="tr15 td15"><P class="p17 ft27">&nbsp;</P></TD>
                                                    </TR>
                                                </TABLE>
                                                <P class="p24 ft14">• If neither of the above situations applies, stop here and enter the number from line H on line 5 of Form <NOBR>W-4</NOBR> below.</P>
                                                <P class="p25 ft14">Separate here and give Form <NOBR>W-4</NOBR> to your employer. Keep the top part for your records.</P>
                                                <TABLE cellpadding=0 cellspacing=0 class="t2">
                                                    <TR>
                                                        <TD class="tr6 td16"></TD>
                                                        <TD class="tr6 td17"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD rowspan=3 class="tr2 td18"><P class="p17 ft29"><NOBR>W-4</NOBR></P></TD>
                                                    <TD colspan=7 rowspan=2 class="tr17 td19"><P class="p26 ft30">Employee's Withholding Allowance Certificate</P></TD>
                                                    <TD class="tr6 td20"><P class="p27 ft5">OMB No. <NOBR>1545-0074</NOBR></P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr15 td16"></TD>
                                                        <TD rowspan=2 class="tr18 td17"><P class="p17 ft5">Form</P></TD>
                                                        <TD rowspan=4 class="tr2 td21"><P class="p28 ft32">20<SPAN class="ft31">16</SPAN></P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr10 td16"></TD>
                                                        <TD colspan=7 class="tr10 td19"><P class="p29 ft34"><SPAN class="ft33">▶ </SPAN>Whether you are entitled to claim a certain number of allowances or exemption from withholding is</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr19 td16"></TD>
                                                        <TD colspan=2 class="tr19 td22"><P class="p17 ft35">Department of the Treasury</P></TD>
                                                        <TD colspan=7 rowspan=2 class="tr6 td23"><P class="p29 ft5">subject to review by the IRS. Your employer may be required to send a copy of this form to the IRS.</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td16"></TD>
                                                        <TD colspan=2 class="tr14 td24"><P class="p17 ft1">Internal Revenue Service</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td16"></TD>
                                                        <TD class="tr14 td17"><P class="p30 ft4">1</P></TD>
                                                        <TD class="tr14 td25"><P class="p31 ft4">Your first name</P></TD>
                                                        <TD class="tr14 td26"><P class="p17 ft4">and middle initial</P></TD>
                                                        <TD colspan=2 class="tr14 td27"><P class="p27 ft4">Last name</P></TD>
                                                        <TD class="tr14 td28"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td29"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td30"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr14 td31"><P class="p22 ft4">2 Your social security number</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr18 td16"></TD>
                                                        <TD class="tr18 td32"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr18 td33"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td34"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td35"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td36"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td37"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td38"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td39"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td21"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr20 td16"></TD>
                                                        <TD class="tr20 td17"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr20 td40"><P class="p31 ft2">Home address (number and street or rural route)</P></TD>
                                                        <TD class="tr20 td41"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr4 td42"><P class="p32 ft5">3</P></TD>
                                                        <TD rowspan=2 class="tr4 td28">
                                                            <div class="form-col-100 status-box">
                                                                <input type="checkbox">
                                                                <label>Single</label>
                                                            </div>
                                                        </TD>
                                                        <TD rowspan=2 class="tr4 td29">
                                                            <div class="form-col-100 status-box">
                                                                <input type="checkbox">
                                                                <label>Married</label>
                                                            </div>
                                                        </TD>
                                                        <TD colspan=3 rowspan=2 class="tr4 td43"><div class="form-col-100 status-box">
                                                                <input type="checkbox">
                                                                <label>Married, but withhold at higher Single rate.</label>
                                                            </div></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr7 td16"></TD>
                                                        <TD class="tr7 td17"><P class="p17 ft17">&nbsp;</P></TD>
                                                        <TD class="tr7 td25"><P class="p17 ft17">&nbsp;</P></TD>
                                                        <TD class="tr7 td44"><P class="p17 ft17">&nbsp;</P></TD>
                                                        <TD class="tr7 td41"><P class="p17 ft17">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr16 td16"></TD>
                                                        <TD class="tr16 td17"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td25"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td44"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td41"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=6 class="tr16 td45"><P class="p22 ft1">Note: If married, but legally separated, or spouse is a nonresident alien, check the “Single” box.</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr21 td16"></TD>
                                                        <TD class="tr21 td32"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr21 td46"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td47"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td35"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td36"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td37"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td48"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td39"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td21"><P class="p17 ft37">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr20 td16"></TD>
                                                        <TD class="tr20 td17"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr20 td40"><P class="p31 ft2">City or town, state, and ZIP code</P></TD>
                                                        <TD class="tr20 td41"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr4 td42"><P class="p32 ft5">4</P></TD>
                                                        <TD colspan=5 rowspan=2 class="tr4 td49"><P class="p34 ft5">If your last name differs from that shown on your social security card,</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr7 td16"></TD>
                                                        <TD class="tr7 td17"><P class="p17 ft17">&nbsp;</P></TD>
                                                        <TD class="tr7 td25"><P class="p17 ft17">&nbsp;</P></TD>
                                                        <TD class="tr7 td44"><P class="p17 ft17">&nbsp;</P></TD>
                                                        <TD class="tr7 td41"><P class="p17 ft17">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr6 td16"></TD>
                                                        <TD class="tr6 td32"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr6 td50"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr6 td51"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr6 td47"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr6 td35"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=5 class="tr6 td52"><P class="p34 ft5">check here. You must call <NOBR>1-800-772-1213</NOBR> for a replacement card. <SPAN class="ft33">▶</SPAN>
                                                            <div class="form-col-100 status-box">
                                                                <input type="checkbox">
                                                            </div>
                                                        </P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr16 td16"></TD>
                                                        <TD class="tr5 td17"><P class="p30 ft14">5</P></TD>
                                                        <TD colspan=8 class="tr5 td53"><P class="p27 ft14">Total number of allowances you are claiming (from line H above or from the applicable worksheet on page 2)</P></TD>
                                                        <TD class="tr16 td21"><P class="p35 ft14">5<input type="text" class="w4dinput"></P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr16 td16"></TD>
                                                        <TD class="tr5 td17"><P class="p30 ft14">6</P></TD>
                                                        <TD colspan=4 class="tr5 td54"><P class="p27 ft14">Additional amount, if any, you want withheld from each paycheck</P></TD>
                                                        <TD colspan=4 class="tr5 td55"><P class="p34 ft14">. . . . . . . . . . . . . .</P></TD>
                                                        <TD class="tr16 td21"><P class="p36 ft14">6 <SPAN class="ft38">$</SPAN><input type="text" class="w4dinput"></P></TD>
                                                    </TR>
                                                </TABLE>
                                                <P class="p37 ft14"><SPAN class="ft14">7</SPAN><SPAN class="ft39">I claim exemption from withholding for 2016, and I certify that I meet both of the following conditions for exemption.</SPAN></P>
                                                <P class="p38 ft14"><SPAN class="ft14">•</SPAN><SPAN class="ft40">Last year I had a right to a refund of all federal income tax withheld because I had no tax liability, and</SPAN></P>
                                                <P class="p38 ft14"><SPAN class="ft14">•</SPAN><SPAN class="ft40">This year I expect a refund of all federal income tax withheld because I expect to have no tax liability.</SPAN></P>
                                                <P class="p39 ft14">If you meet both conditions, write “Exempt” here . . . . . . . . . . . . . . . <SPAN class="ft33">▶ </SPAN>7</P>
                                                <P class="p40 ft14">Under penalties of perjury, I declare that I have examined this certificate and, to the best of my knowledge and belief, it is true, correct, and complete.</P>
                                                <TABLE cellpadding=0 cellspacing=0 class="t3">
                                                    <TR>
                                                        <TD colspan=2 class="tr16 td56"><P class="p17 ft14">Employee’s signature</P></TD>
                                                        <TD class="tr16 td57"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr17 td58"><P class="p22 ft14">Date <SPAN class="ft41">▶</SPAN></P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr4 td59"><P class="p17 ft14">(This form is not valid unless you sign it.) <SPAN class="ft41">▶</SPAN></P></TD>
                                                        <TD class="tr4 td60"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr15 td0"><P class="p41 ft5">8</P></TD>
                                                        <TD class="tr15 td61"><P class="p42 ft5">Employer’s name and address (Employer: Complete lines 8 and 10 only if sending to the IRS.)</P></TD>
                                                        <TD class="tr15 td62"><P class="p22 ft1">9 Office code (optional)</P></TD>
                                                        <TD class="tr15 td31"><P class="p22 ft5">10 Employer identification number (EIN)</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr1 td63">
                                                            <input type="text" class="w4dinput no-border">
                                                        </TD>
                                                        <TD class="tr1 td64"><input type="text" class="w4dinput no-border"></TD>
                                                        <TD class="tr1 td58"><input type="text" class="w4dinput no-border"></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr5 td56"><P class="p17 ft14">For Privacy Act and Paperwork Reduction Act Notice, see page 2.</P></TD>
                                                        <TD class="tr5 td57"><P class="p34 ft5">Cat. No. 10220Q</P></TD>
                                                        <TD class="tr5 td31"><P class="p43 ft1">Form <NOBR><SPAN class="ft38">W-4</SPAN></NOBR><SPAN class="ft38"> </SPAN>(2016)</P></TD>
                                                    </TR>
                                                </TABLE>
                                            </DIV>
                                        </DIV>
                                        <DIV id="page_2">
                                            <DIV id="dimg1">
                                                <IMG src="<?php echo site_url('assets/images/fw4form_images/fw4form2x1.jpg'); ?>" id="img1">
                                            </DIV>


                                            <DIV id="id_1">
                                                <TABLE cellpadding=0 cellspacing=0 class="t4">
                                                    <TR>
                                                        <TD class="tr5 td65"><P class="p17 ft5">Form <NOBR>W-4</NOBR> (2016)</P></TD>
                                                    <TD class="tr5 td66"><P class="p17 ft5">Page <SPAN class="ft10">2</SPAN></P></TD>
                                                    </TR>
                                                </TABLE>
                                                <P class="p44 ft10">Deductions and Adjustments Worksheet</P>
                                                <P class="p45 ft14">Note: Use this worksheet <SPAN class="ft42">only </SPAN>if you plan to itemize deductions or claim certain credits or adjustments to income.</P>
                                                <P class="p46 ft44"><SPAN class="ft5">1</SPAN><SPAN class="ft43">Enter an estimate of your 2016 itemized deductions. These include qualifying home mortgage interest, charitable contributions, state and local taxes, medical expenses in excess of 10% (7.5% if either you or your spouse was born before January 2, 1952) of your</SPAN></P>
                                                <TABLE cellpadding=0 cellspacing=0 class="t5">
                                                    <TR>
                                                        <TD class="tr4 td67"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr4 td68"><P class="p31 ft1">income, and miscellaneous deductions. For 2016, you may have to reduce your itemized deductions if your income is over $311,300</P></TD>
                                                        <TD class="tr4 td69"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td70"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr15 td67"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr15 td68"><P class="p31 ft1">and you are married filing jointly or are a qualifying widow(er); $285,350 if you are head of household; $259,400 if you are single and</P></TD>
                                                        <TD class="tr15 td69"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr22 td70">
                                                            <div class="price-box">
                                                                <div class="cruncy-simble">$</div>
                                                                <div class="amount-box">
                                                                    <input type="text" class="w4dinput no-border">
                                                                </div>
                                                            </div>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr20 td67"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr20 td68"><P class="p31 ft2">not head of household or a qualifying widow(er); or $155,650 if you are married filing separately. See Pub. 505 for details . . .</P></TD>
                                                        <TD class="tr20 td69"><P class="p18 ft45">1</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr23 td67"><P class="p17 ft46">&nbsp;</P></TD>
                                                        <TD rowspan=4 class="tr24 td71"><P class="p31 ft14">Enter: <SPAN class="ft47">{</SPAN></P></TD>
                                                        <TD rowspan=2 class="tr1 td72"><P class="p17 ft14">$12,600 if married filing jointly or qualifying widow(er)</P></TD>
                                                        <TD rowspan=4 class="tr24 td73"><P class="p48 ft14"><SPAN class="ft47">} </SPAN>. . . . . . . . . . .</P></TD>
                                                        <TD class="tr23 td69"><P class="p17 ft46">&nbsp;</P></TD>
                                                        <TD class="tr7 td74"><P class="p17 ft17">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr4 td67"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td69"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td70"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr3 td67"><P class="p41 ft14">2</P></TD>
                                                        <TD class="tr3 td72"><P class="p17 ft14">$9,300 if head of household</P></TD>
                                                        <TD class="tr3 td69"><P class="p18 ft14">2</P></TD>
                                                        <TD class="tr6 td74">
                                                            <div class="price-box">
                                                                <div class="cruncy-simble">$</div>
                                                                <div class="amount-box">
                                                                    <input type="text" class="w4dinput no-border">
                                                                </div>
                                                            </div>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr15 td67"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD rowspan=2 class="tr4 td72"><P class="p17 ft14">$6,300 if single or married filing separately</P></TD>
                                                        <TD class="tr15 td69"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr15 td70"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr21 td67"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td71"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td73"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td69"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td70"><P class="p17 ft37">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr3 td67"><P class="p41 ft14">3</P></TD>
                                                        <TD colspan=3 class="tr3 td68"><P class="p31 ft14">Subtract line 2 from line 1. If zero or less, enter <NOBR>“-0-” . . . . . . . . . . . . . . . .</NOBR></P></TD>
                                                    <TD class="tr3 td69"><P class="p18 ft14">3</P></TD>
                                                    <TD class="tr6 td74">
                                                        <div class="price-box">
                                                                <div class="cruncy-simble">$</div>
                                                                <div class="amount-box">
                                                                    <input type="text" class="w4dinput no-border">
                                                                </div>
                                                            </div>
                                                    </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td67"><P class="p41 ft14">4</P></TD>
                                                        <TD colspan=3 class="tr5 td68"><P class="p31 ft14">Enter an estimate of your 2016 adjustments to income and any additional standard deduction (see Pub. 505)</P></TD>
                                                        <TD class="tr5 td69"><P class="p18 ft14">4</P></TD>
                                                        <TD class="tr16 td74">
                                                            <div class="price-box">
                                                                <div class="cruncy-simble">$</div>
                                                                <div class="amount-box">
                                                                    <input type="text" class="w4dinput no-border">
                                                                </div>
                                                            </div>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr4 td67"><P class="p41 ft14">5</P></TD>
                                                        <TD colspan=3 class="tr4 td68"><P class="p31 ft14">Add lines 3 and 4 and enter the total. (Include any amount for credits from the <SPAN class="ft42">Converting Credits to</SPAN></P></TD>
                                                        <TD class="tr4 td69"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td70"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr3 td67"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr3 td68"><P class="p31 ft14"><SPAN class="ft42">Withholding Allowances for 2016 Form </SPAN><NOBR><SPAN class="ft42">W-4</SPAN></NOBR><SPAN class="ft42"> </SPAN>worksheet in Pub. 505.) . . . . . . . . . . . .</P></TD>
                                                    <TD class="tr3 td69"><P class="p18 ft14">5</P></TD>
                                                    <TD class="tr6 td74">
                                                        <div class="price-box">
                                                            <div class="cruncy-simble">$</div>
                                                            <div class="amount-box">
                                                                <input type="text" class="w4dinput no-border">
                                                            </div>
                                                        </div>
                                                    </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td67"><P class="p41 ft14">6</P></TD>
                                                        <TD colspan=3 class="tr5 td68"><P class="p31 ft14">Enter an estimate of your 2016 nonwage income (such as dividends or interest) . . . . . . . .</P></TD>
                                                        <TD class="tr5 td69"><P class="p18 ft14">6</P></TD>
                                                        <TD class="tr16 td74">
                                                            <div class="price-box">
                                                                <div class="cruncy-simble">$</div>
                                                                <div class="amount-box">
                                                                    <input type="text" class="w4dinput no-border">
                                                                </div>
                                                            </div>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td67"><P class="p41 ft14">7</P></TD>
                                                        <TD colspan=3 class="tr5 td68"><P class="p31 ft14">Subtract line 6 from line 5. If zero or less, enter <NOBR>“-0-” . . . . . . . . . . . . . . . .</NOBR></P></TD>
                                                    <TD class="tr5 td69"><P class="p18 ft14">7</P></TD>
                                                    <TD class="tr16 td74">
                                                        <div class="price-box">
                                                            <div class="cruncy-simble">$</div>
                                                            <div class="amount-box">
                                                                <input type="text" class="w4dinput no-border">
                                                            </div>
                                                        </div>
                                                    </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td67"><P class="p41 ft14">8</P></TD>
                                                        <TD colspan=3 class="tr5 td68"><P class="p31 ft14">Divide the amount on line 7 by $4,050 and enter the result here. Drop any fraction . . . . . . .</P></TD>
                                                        <TD class="tr5 td69"><P class="p18 ft14">8</P></TD>
                                                        <TD class="tr16 td74">
                                                            <div class="price-box">
                                                                <input type="text" class="w4dinput no-border">
                                                            </div>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td67"><P class="p41 ft14">9</P></TD>
                                                        <TD colspan=3 class="tr5 td68"><P class="p31 ft14">Enter the number from the Personal Allowances Worksheet, line H, page 1 . . . . . . . . .</P></TD>
                                                        <TD class="tr5 td69"><P class="p18 ft14">9</P></TD>
                                                        <TD class="tr16 td74">
                                                            <div class="price-box">
                                                                <input type="text" class="w4dinput no-border">
                                                            </div>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr4 td67"><P class="p41 ft14">10</P></TD>
                                                        <TD colspan=3 class="tr4 td68"><P class="p31 ft14">Add lines 8 and 9 and enter the total here. If you plan to use the <NOBR>Two-Earners/Multiple</NOBR> Jobs Worksheet,</P></TD>
                                                    <td class="tr5 td69"><p class="p18 ft14">10</p></td>
                                                    <TD class="tr4 td70">
                                                        <div class="price-box">
                                                            <input type="text" class="w4dinput no-border">
                                                        </div>
                                                    </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td67"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr5 td68"><P class="p31 ft14">also enter this total on line 1 below. Otherwise, stop here and enter this total on Form <NOBR>W-4,</NOBR> line 5, page 1</P></TD>
                                                    <TD class="tr5 td69"><P class="p18 ft14">&nbsp;</P></TD>
                                                    <TD class="tr5 td70"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                </TABLE>
                                                <P class="headingborders"><NOBR>Two-Earners/Multiple</NOBR> Jobs Worksheet (See <SPAN class="ft48">Two earners or multiple jobs </SPAN>on page 1.)</P>
                                                <TABLE cellpadding=0 cellspacing=0 class="t6">
                                                    <TR>
                                                        <TD colspan=13 class="tr16 td75"><P class="p27 ft14">Note: Use this worksheet <SPAN class="ft42">only </SPAN>if the instructions under line H on page 1 direct you here.</P></TD>
                                                        <TD class="tr16 td76"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td69"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td78"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td79"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td81"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr3 td80"><P class="p50 ft14">1</P></TD>
                                                        <TD colspan=17 class="tr3 td83"><P class="p51 ft15">Enter the number from line H, page 1 (or from line 10 above if you used the Deductions and Adjustments Worksheet)</P></TD>
                                                        <TD class="tr3 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr3 td79"><P class="p34 ft14">1</P></TD>
                                                        <TD class="tr6 td84">
                                                            <div class="price-box">
                                                                <input type="text" class="w4dinput no-border">
                                                            </div>
                                                        </TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr16 td80"><P class="p50 ft14">2</P></TD>
                                                        <TD colspan=17 class="tr16 td83"><P class="p51 ft14">Find the number in Table 1 below that applies to the LOWEST paying job and enter it here. However, if</P></TD>
                                                        <TD class="tr16 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td79"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td81"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr4 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=17 class="tr4 td83"><P class="p51 ft14">you are married filing jointly and wages from the highest paying job are $65,000 or less, do not enter more</P></TD>
                                                        <TD class="tr4 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td79"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td81"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr1 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=8 class="tr1 td87"><P class="p51 ft14">than “3” . . . . . . . . . . . .</P></TD>
                                                        <TD colspan=7 class="tr1 td88"><P class="p52 ft14">. . . . . . . . . . . . . . . .</P></TD>
                                                        <TD class="tr1 td78"><P class="p53 ft14">. .</P></TD>
                                                        <TD class="tr1 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr1 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr1 td79"><P class="p27 ft14">2</P></TD>
                                                        <TD class="tr3 td84"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr3 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr3 td86"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr1 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr4 td80"><P class="p50 ft14">3</P></TD>
                                                        <TD colspan=17 class="tr4 td83"><P class="p51 ft14">If line 1 is more than or equal to line 2, subtract line 2 from line 1. Enter the result here (if zero, enter</P></TD>
                                                        <TD class="tr4 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td79"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td81"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr3 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=15 class="tr3 td89"><P class="p51 ft14"><NOBR>“-0-”)</NOBR> and on Form <NOBR>W-4,</NOBR> line 5, page 1. Do not use the rest of this worksheet . . . . . . .</P></TD>
                                                    <TD class="tr3 td78"><P class="p53 ft14">. .</P></TD>
                                                    <TD class="tr3 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr3 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr3 td79"><P class="p34 ft14">3</P></TD>
                                                    <TD class="tr6 td84"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr6 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr6 td86"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr3 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=18 class="tr0 td90"><P class="p27 ft11">Note: If line 1 is less than line 2, enter <NOBR>“-0-”</NOBR> on Form <NOBR>W-4,</NOBR> line 5, page 1. Complete lines 4 through 9 below to</P></TD>
                                                    <TD class="tr0 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr0 td79"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr0 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr0 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr0 td81"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr0 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr16 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=12 class="tr16 td56"><P class="p54 ft14">figure the additional withholding amount necessary to avoid a <NOBR>year-end</NOBR> tax bill.</P></TD>
                                                    <TD class="tr16 td76"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr16 td69"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr16 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr16 td78"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr16 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr16 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr16 td79"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr16 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr16 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr16 td81"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr16 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr18 td80"><P class="p50 ft14">4</P></TD>
                                                        <TD colspan=8 class="tr18 td87"><P class="p51 ft14">Enter the number from line 2 of this worksheet</P></TD>
                                                        <TD colspan=4 class="tr18 td91"><P class="p52 ft14">. . . . . . . . . .</P></TD>
                                                        <TD class="tr18 td76"><P class="p18 ft14">4</P></TD>
                                                        <TD class="tr1 td92"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr1 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr1 td93"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td79"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td81"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr18 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td80"><P class="p50 ft14">5</P></TD>
                                                        <TD colspan=8 class="tr5 td87"><P class="p51 ft14">Enter the number from line 1 of this worksheet</P></TD>
                                                        <TD colspan=4 class="tr5 td91"><P class="p55 ft14">. . . . . . . . . .</P></TD>
                                                        <TD class="tr5 td76"><P class="p18 ft14">5</P></TD>
                                                        <TD class="tr16 td92"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td93"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td79"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td81"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td80"><P class="p50 ft14">6</P></TD>
                                                        <TD colspan=8 class="tr5 td87"><P class="p51 ft14">Subtract line 5 from line 4 . . . . . . .</P></TD>
                                                        <TD colspan=7 class="tr5 td88"><P class="p52 ft14">. . . . . . . . . . . . . . . .</P></TD>
                                                        <TD class="tr5 td78"><P class="p53 ft14">. .</P></TD>
                                                        <TD class="tr5 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td79"><P class="p18 ft14">6</P></TD>
                                                        <TD class="tr16 td84"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td86"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td80"><P class="p50 ft14">7</P></TD>
                                                        <TD colspan=15 class="tr5 td89"><P class="p51 ft14">Find the amount in Table 2 below that applies to the HIGHEST paying job and enter it here . .</P></TD>
                                                        <TD class="tr5 td78"><P class="p53 ft14">. .</P></TD>
                                                        <TD class="tr5 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td79"><P class="p27 ft14">7</P></TD>
                                                        <TD class="tr16 td84"><P class="p53 ft38">$</P></TD>
                                                        <TD class="tr16 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td86"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr5 td80"><P class="p50 ft14">8</P></TD>
                                                        <TD colspan=15 class="tr5 td89"><P class="p51 ft14">Multiply line 7 by line 6 and enter the result here. This is the additional annual withholding needed</P></TD>
                                                        <TD class="tr5 td78"><P class="p53 ft14">. .</P></TD>
                                                        <TD class="tr5 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td79"><P class="p27 ft14">8</P></TD>
                                                        <TD class="tr16 td84"><P class="p53 ft38">$</P></TD>
                                                        <TD class="tr16 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td86"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr5 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr16 td80"><P class="p50 ft14">9</P></TD>
                                                        <TD colspan=17 class="tr16 td83"><P class="p51 ft14">Divide line 8 by the number of pay periods remaining in 2016. For example, divide by 25 if you are paid every two</P></TD>
                                                        <TD class="tr16 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td79"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td81"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr4 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=17 class="tr4 td83"><P class="p51 ft14">weeks and you complete this form on a date in January when there are 25 pay periods remaining in 2016. Enter</P></TD>
                                                        <TD class="tr4 td2"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td79"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td81"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr3 td84"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=17 class="tr3 td94"><P class="p51 ft14">the result here and on Form <NOBR>W-4,</NOBR> line 6, page 1. This is the additional amount to be withheld from each paycheck</P></TD>
                                                    <TD class="tr3 td95"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr3 td96"><P class="p34 ft14">9</P></TD>
                                                    <TD class="tr3 td84"><P class="p53 ft38">$</P></TD>
                                                    <TD class="tr3 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr3 td86"><P class="p17 ft12">&nbsp;</P></TD>
                                                    <TD class="tr3 td97"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr16 td84"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td98"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td95"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td99"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr16 td100"><P class="p56 ft49">Table 1</P></TD>
                                                        <TD class="tr16 td101"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td99"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td102"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td103"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td95"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td104"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td105"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=4 class="tr16 td106"><P class="p57 ft49">Table 2</P></TD>
                                                        <TD class="tr16 td95"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td96"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td84"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td86"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr16 td97"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr4 td84"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=4 class="tr4 td107"><P class="p58 ft14">Married Filing Jointly</P></TD>
                                                        <TD class="tr4 td108"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td101"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr4 td37"><P class="p32 ft14">All Others</P></TD>
                                                        <TD class="tr4 td102"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=5 class="tr4 td109"><P class="p59 ft14">Married Filing Jointly</P></TD>
                                                        <TD class="tr4 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td93"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td85"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td95"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr4 td110"><P class="p27 ft14">All Others</P></TD>
                                                        <TD class="tr4 td86"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td97"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=4 class="tr3 td111"><P class="p27 ft1">If wages from LOWEST</P></TD>
                                                        <TD class="tr3 td112"><P class="p22 ft5">Enter on</P></TD>
                                                        <TD colspan=3 class="tr3 td113"><P class="p22 ft1">If wages from LOWEST</P></TD>
                                                        <TD class="tr3 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr3 td114"><P class="p17 ft5">Enter on</P></TD>
                                                        <TD colspan=3 class="tr3 td115"><P class="p22 ft5">If wages from HIGHEST</P></TD>
                                                        <TD colspan=2 class="tr3 td112"><P class="p22 ft5">Enter on</P></TD>
                                                        <TD class="tr3 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=5 class="tr3 td116"><P class="p17 ft5">If wages from HIGHEST</P></TD>
                                                        <TD class="tr3 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr3 td7"><P class="p17 ft5">Enter on</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=4 class="tr15 td111"><P class="p27 ft5">paying job are—</P></TD>
                                                        <TD class="tr15 td112"><P class="p22 ft5">line 2 above</P></TD>
                                                        <TD colspan=3 class="tr15 td113"><P class="p22 ft5">paying job are—</P></TD>
                                                        <TD class="tr15 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr15 td114"><P class="p17 ft5">line 2 above</P></TD>
                                                        <TD colspan=3 class="tr15 td115"><P class="p22 ft5">paying job are—</P></TD>
                                                        <TD colspan=2 class="tr15 td112"><P class="p22 ft5">line 7 above</P></TD>
                                                        <TD class="tr15 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=4 class="tr15 td117"><P class="p17 ft5">paying job are—</P></TD>
                                                        <TD class="tr15 td118"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr15 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr15 td7"><P class="p17 ft5">line 7 above</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr21 td84"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr21 td119"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td120"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td121"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr21 td104"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td120"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td85"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td102"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr21 td86"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td122"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr21 td121"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td85"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr21 td123"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr21 td124"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td85"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td86"><P class="p17 ft37">&nbsp;</P></TD>
                                                        <TD class="tr21 td97"><P class="p17 ft37">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr4 td80"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr4 td125"><P class="p60 ft5">$0 -</P></TD>
                                                        <TD class="tr4 td126"><P class="p61 ft5">$6,000</P></TD>
                                                        <TD class="tr4 td112"><P class="p62 ft5">0</P></TD>
                                                        <TD colspan=2 class="tr4 td127"><P class="p50 ft5">$0 -</P></TD>
                                                        <TD class="tr4 td126"><P class="p61 ft5">$9,000</P></TD>
                                                        <TD class="tr4 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td114"><P class="p62 ft5">0</P></TD>
                                                        <TD colspan=2 class="tr4 td81"><P class="p50 ft5">$0 -</P></TD>
                                                        <TD class="tr4 td128"><P class="p61 ft5">$75,000</P></TD>
                                                        <TD colspan=2 class="tr4 td112"><P class="p56 ft5">$610</P></TD>
                                                        <TD class="tr4 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD colspan=3 class="tr4 td129"><P class="p50 ft5">$0 -</P></TD>
                                                        <TD colspan=2 class="tr4 td130"><P class="p34 ft5">$38,000</P></TD>
                                                        <TD class="tr4 td77"><P class="p17 ft12">&nbsp;</P></TD>
                                                        <TD class="tr4 td81"><P class="p63 ft5">$610</P></TD>
                                                        <TD class="tr4 td82"><P class="p17 ft12">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td80"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td131"><P class="p50 ft4">6,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">14,000</P></TD>
                                                        <TD class="tr14 td112"><P class="p62 ft4">1</P></TD>
                                                        <TD class="tr14 td132"><P class="p50 ft4">9,001</P></TD>
                                                        <TD class="tr14 td133"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">17,000</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td114"><P class="p62 ft4">1</P></TD>
                                                        <TD class="tr14 td134"><P class="p50 ft4">75,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td128"><P class="p61 ft4">135,000</P></TD>
                                                        <TD colspan=2 class="tr14 td112"><P class="p56 ft4">1,010</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr14 td135"><P class="p50 ft4">38,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD colspan=2 class="tr14 td130"><P class="p61 ft4">85,000</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td81"><P class="p63 ft4">1,010</P></TD>
                                                        <TD class="tr14 td82"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr14 td136"><P class="p50 ft4">14,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">25,000</P></TD>
                                                        <TD class="tr14 td112"><P class="p62 ft4">2</P></TD>
                                                        <TD class="tr14 td132"><P class="p50 ft4">17,001</P></TD>
                                                        <TD class="tr14 td133"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">26,000</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td114"><P class="p62 ft4">2</P></TD>
                                                        <TD class="tr14 td134"><P class="p50 ft4">135,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td128"><P class="p61 ft4">205,000</P></TD>
                                                        <TD colspan=2 class="tr14 td112"><P class="p56 ft4">1,130</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr14 td135"><P class="p50 ft4">85,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD colspan=2 class="tr14 td130"><P class="p34 ft4">185,000</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td81"><P class="p63 ft4">1,130</P></TD>
                                                        <TD class="tr14 td82"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr20 td136"><P class="p50 ft2">25,001</P></TD>
                                                        <TD class="tr20 td2"><P class="p50 ft2">-</P></TD>
                                                        <TD class="tr20 td126"><P class="p61 ft2">27,000</P></TD>
                                                        <TD class="tr20 td112"><P class="p62 ft2">3</P></TD>
                                                        <TD class="tr20 td132"><P class="p50 ft2">26,001</P></TD>
                                                        <TD class="tr20 td133"><P class="p50 ft2">-</P></TD>
                                                        <TD class="tr20 td126"><P class="p61 ft2">34,000</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td114"><P class="p62 ft2">3</P></TD>
                                                        <TD class="tr20 td134"><P class="p50 ft2">205,001</P></TD>
                                                        <TD class="tr20 td2"><P class="p50 ft2">-</P></TD>
                                                        <TD class="tr20 td128"><P class="p61 ft2">360,000</P></TD>
                                                        <TD colspan=2 class="tr20 td112"><P class="p56 ft2">1,340</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD colspan=2 class="tr20 td135"><P class="p50 ft2">185,001</P></TD>
                                                        <TD class="tr20 td2"><P class="p50 ft2">-</P></TD>
                                                        <TD colspan=2 class="tr20 td130"><P class="p34 ft2">400,000</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td81"><P class="p63 ft2">1,340</P></TD>
                                                        <TD class="tr20 td82"><P class="p17 ft36">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr14 td136"><P class="p50 ft4">27,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">35,000</P></TD>
                                                        <TD class="tr14 td112"><P class="p62 ft4">4</P></TD>
                                                        <TD class="tr14 td132"><P class="p50 ft4">34,001</P></TD>
                                                        <TD class="tr14 td133"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">44,000</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td114"><P class="p62 ft4">4</P></TD>
                                                        <TD class="tr14 td134"><P class="p50 ft4">360,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td128"><P class="p61 ft4">405,000</P></TD>
                                                        <TD colspan=2 class="tr14 td112"><P class="p56 ft4">1,420</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD colspan=4 class="tr14 td117"><P class="p23 ft4">400,001 and over</P></TD>
                                                        <TD class="tr14 td118"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td81"><P class="p63 ft4">1,600</P></TD>
                                                        <TD class="tr14 td82"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr14 td136"><P class="p50 ft4">35,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">44,000</P></TD>
                                                        <TD class="tr14 td112"><P class="p62 ft4">5</P></TD>
                                                        <TD class="tr14 td132"><P class="p50 ft4">44,001</P></TD>
                                                        <TD class="tr14 td133"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">75,000</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td114"><P class="p62 ft4">5</P></TD>
                                                        <TD colspan=3 class="tr14 td115"><P class="p64 ft4">405,001 and over</P></TD>
                                                        <TD colspan=2 class="tr14 td112"><P class="p56 ft4">1,600</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td78"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td79"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td118"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td81"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td82"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr20 td136"><P class="p50 ft2">44,001</P></TD>
                                                        <TD class="tr20 td2"><P class="p50 ft2">-</P></TD>
                                                        <TD class="tr20 td126"><P class="p61 ft2">55,000</P></TD>
                                                        <TD class="tr20 td112"><P class="p62 ft2">6</P></TD>
                                                        <TD class="tr20 td132"><P class="p50 ft2">75,001</P></TD>
                                                        <TD class="tr20 td133"><P class="p50 ft2">-</P></TD>
                                                        <TD class="tr20 td126"><P class="p61 ft2">85,000</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td114"><P class="p62 ft2">6</P></TD>
                                                        <TD class="tr20 td134"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td2"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td128"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td76"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td137"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td78"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td2"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td79"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td118"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td81"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td82"><P class="p17 ft36">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr14 td136"><P class="p50 ft4">55,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">65,000</P></TD>
                                                        <TD class="tr14 td112"><P class="p62 ft4">7</P></TD>
                                                        <TD class="tr14 td132"><P class="p50 ft4">85,001</P></TD>
                                                        <TD class="tr14 td133"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">110,000</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td114"><P class="p62 ft4">7</P></TD>
                                                        <TD class="tr14 td134"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td128"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td76"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td137"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td78"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td79"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td118"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td81"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td82"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr14 td136"><P class="p50 ft4">65,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">75,000</P></TD>
                                                        <TD class="tr14 td112"><P class="p62 ft4">8</P></TD>
                                                        <TD class="tr14 td132"><P class="p50 ft4">110,001</P></TD>
                                                        <TD class="tr14 td133"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">125,000</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td114"><P class="p62 ft4">8</P></TD>
                                                        <TD class="tr14 td134"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td128"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td76"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td137"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td78"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td79"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td118"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td81"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td82"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr20 td136"><P class="p50 ft2">75,001</P></TD>
                                                        <TD class="tr20 td2"><P class="p50 ft2">-</P></TD>
                                                        <TD class="tr20 td126"><P class="p61 ft2">80,000</P></TD>
                                                        <TD class="tr20 td112"><P class="p62 ft2">9</P></TD>
                                                        <TD class="tr20 td132"><P class="p50 ft2">125,001</P></TD>
                                                        <TD class="tr20 td133"><P class="p50 ft2">-</P></TD>
                                                        <TD class="tr20 td126"><P class="p61 ft2">140,000</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td114"><P class="p62 ft2">9</P></TD>
                                                        <TD class="tr20 td134"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td2"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td128"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td76"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td137"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td78"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td2"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td79"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td118"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td81"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td82"><P class="p17 ft36">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD class="tr14 td80"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td131"><P class="p50 ft4">80,001</P></TD>
                                                        <TD colspan=2 class="tr14 td138"><P class="p61 ft4">- 100,000</P></TD>
                                                        <TD class="tr14 td112"><P class="p62 ft4">10</P></TD>
                                                        <TD colspan=3 class="tr14 td113"><P class="p18 ft4">140,001 and over</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td114"><P class="p62 ft4">10</P></TD>
                                                        <TD class="tr14 td134"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td128"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td76"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td137"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td78"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td79"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td118"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td81"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td82"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr14 td136"><P class="p50 ft4">100,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">115,000</P></TD>
                                                        <TD class="tr14 td112"><P class="p62 ft4">11</P></TD>
                                                        <TD class="tr14 td132"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td133"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td126"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td114"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td134"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td128"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td76"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td137"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td78"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td79"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td118"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td81"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td82"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr20 td136"><P class="p50 ft2">115,001</P></TD>
                                                        <TD class="tr20 td2"><P class="p50 ft2">-</P></TD>
                                                        <TD class="tr20 td126"><P class="p61 ft2">130,000</P></TD>
                                                        <TD class="tr20 td112"><P class="p62 ft2">12</P></TD>
                                                        <TD class="tr20 td132"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td133"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td126"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td114"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td134"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td2"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td128"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td76"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td137"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td78"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td2"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td79"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td118"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td77"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td81"><P class="p17 ft36">&nbsp;</P></TD>
                                                        <TD class="tr20 td82"><P class="p17 ft36">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr14 td136"><P class="p50 ft4">130,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">140,000</P></TD>
                                                        <TD class="tr14 td112"><P class="p62 ft4">13</P></TD>
                                                        <TD class="tr14 td132"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td133"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td126"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td114"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td134"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td128"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td76"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td137"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td78"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td79"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td118"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td81"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td82"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=2 class="tr14 td136"><P class="p50 ft4">140,001</P></TD>
                                                        <TD class="tr14 td2"><P class="p50 ft4">-</P></TD>
                                                        <TD class="tr14 td126"><P class="p61 ft4">150,000</P></TD>
                                                        <TD class="tr14 td112"><P class="p62 ft4">14</P></TD>
                                                        <TD class="tr14 td132"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td133"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td126"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td114"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td134"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td128"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td76"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td137"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td78"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td2"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td79"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td118"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td77"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td81"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td82"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                    <TR>
                                                        <TD colspan=4 class="tr14 td139"><P class="p63 ft4">150,001 and over</P></TD>
                                                        <TD class="tr14 td121"><P class="p62 ft4">15</P></TD>
                                                        <TD class="tr14 td108"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td101"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td120"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td85"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td102"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td103"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td95"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td122"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td105"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td140"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td85"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td93"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td85"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td95"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td96"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td141"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td85"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td86"><P class="p17 ft25">&nbsp;</P></TD>
                                                        <TD class="tr14 td97"><P class="p17 ft25">&nbsp;</P></TD>
                                                    </TR>
                                                </TABLE>
                                            </DIV>
                                            <DIV id="id_2">
                                                <DIV id="id_2_1">
                                                    <P class="p0 ft44">Privacy Act and Paperwork Reduction Act Notice. We ask for the information on this form to carry out the Internal Revenue laws of the United States. Internal Revenue Code sections 3402(f)(2) and 6109 and their regulations require you to provide this information; your employer uses it to determine your federal income tax withholding. Failure to provide a properly completed form will result in your being treated as a single person who claims no withholding allowances; providing fraudulent information may subject you to penalties. Routine uses of this information include giving it to the Department of Justice for civil and criminal litigation; to cities, states, the District of Columbia, and U.S. commonwealths and possessions for use in administering their tax laws; and to the Department of Health and Human Services for use in the National Directory of New Hires. We may also disclose this information to other countries under a tax treaty, to federal and state agencies to enforce federal nontax criminal laws, or to federal law enforcement and intelligence agencies to combat terrorism.</P>
                                                </DIV>
                                                <DIV id="id_2_2">
                                                    <P class="p65 ft1">You are not required to provide the information requested on a form that is subject to the Paperwork Reduction Act unless the form displays a valid OMB control number. Books or records relating to a form or its instructions must be retained as long as their contents may become material in the administration of any Internal Revenue law. Generally, tax returns and return information are confidential, as required by Code section 6103.</P>
                                                    <P class="p66 ft5">The average time and expenses required to complete and file this form will vary depending on individual circumstances. For estimated averages, see the instructions for your income tax return.</P>
                                                    <P class="p67 ft5">If you have suggestions for making this form simpler, we would be happy to hear from you. See the instructions for your income tax return.</P>
                                                </DIV>
                                            </DIV>
                                        </DIV>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>