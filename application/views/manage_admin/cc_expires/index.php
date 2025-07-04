<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
 <?php  $card_rows = '';
    if(sizeof($cards)){
        foreach ($cards as $card) {
            $card_rows .='    
            <tr>
                <td>'.ucwords($card['CompanyName']).'</td>
                <td>'.$card['expire_month'].'</td>
                <td>'.$card['expire_year'].'</td>
                <td>'.$card['type'].'</td>
                <td>'.$card['number'].'</td>
            </tr>';
        } 
    }
?>

<style>.tab-content > .tab-pane{ display: block !important; }</style>
<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9 no-padding">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <?php $this->load->view('templates/_parts/admin_flash_message'); ?>
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-users"></i><?php echo $page_title; ?></h1>
                                    </div>
                                    <div class="message-action">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-xs-12 col-sm-4">
                                                <div class="hr-items-count">
                                                    <p>Total: <strong class="messagesCounter"><?php echo $references_count; //count($references);  ?></strong> <?php echo $card_type;?> Cards</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                        <?=$links;?>
                                    </div>
                                    <div class="tabs-outer">
                                        <ul class="nav nav-tabs">
                                            <li class="cards-tabs <?=$active == 'active_cards' ? 'active' : '';?>" data-attr="active_cards" ><a data-toggle="tab" href="#js-tab-pane">Active Cards</a></li>
                                            <li class="cards-tabs <?=$active == 'inactive_cards' ? 'active' : '';?>" data-attr="inactive_cards" ><a data-toggle="tab" href="#js-tab-pane">In-Active Cards</a></li>
                                            <li class="cards-tabs <?=$active == 'expiring_in_month' ? 'active' : '';?>" data-attr="expiring_in_month"><a data-toggle="tab" href="#js-tab-pane">Expiring in Month</a></li>
                                            <li class="cards-tabs <?=$active == 'expired' ? 'active' : '';?>" data-attr="expired"><a data-toggle="tab" href="#js-tab-pane">Expired Cards</a></li>
                                        </ul>
                                        <div class="tab-content">
                                            <div class="tab-pane" id="js-tab-pane">
                                                <?php if (sizeof($cards)) { ?>
                                                    <div class="table-responsive table-outer">
                                                        <div class="table-wrp data-table">
                                                            <table class="table table-bordered table-hover table-stripped" id="reference_network_table">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="col-xs-2">Company Name</th>
                                                                        <th class="col-xs-2">Expiry Month</th>
                                                                        <th class="col-xs-3">Expiry Year</th>
                                                                        <th class="col-xs-2">Type</th>
                                                                        <th class="col-xs-2">Card Number</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                   <?=$card_rows;?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                <?php } else { ?>
                                                    <div id="show_no_jobs" class="table-wrp">
                                                        <span class="applicant-not-found">No <?=($active == 'active_cards' ? 'Active' : ( $active == 'inactive_cards' ? 'In-Active' : '' ))?> Cards found!</span>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                           
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-12">
                                        <?=$links;?>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('.cards-tabs').click(function (e) {
            e.preventDefault();
            window.location.href = '<?=base_url('manage_admin/cc_expires');?>' + '/' + $(this).data('attr');
        });
    });
</script>