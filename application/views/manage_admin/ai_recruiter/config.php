<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>


<div class="main">
    <div class="container-fluid">
        <div class="row">
            <div class="inner-content">
                <?php $this->load->view('templates/_parts/admin_column_left_view'); ?>
                <!--  -->
                <div class="col-lg-9 col-md-9 col-xs-12 col-sm-9">
                    <div class="dashboard-content">
                        <div class="dash-inner-block">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                                    <div class="heading-title page-title">
                                        <h1 class="page-title"><i class="fa fa-cog"></i><?php echo $page_title; ?></h1>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <?php if ($this->session->flashdata('success')) { ?>
                                <div class="flash_error_message">
                                    <div class="alert alert-success alert-dismissible" role="alert">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                                aria-hidden="true">&times;</span></button>
                                        <?php echo $this->session->flashdata('success'); ?>
                                    </div>
                                </div>
                            <?php } ?>


                            <form action="" method="post">

                                <div class="form-group">
                                    <label for="voiceSelect">Choose a Deepgram Aura 2 voice:</label>
                                    <select id="voiceSelect" name="model" class="form-control">
                                        <!-- Female Voices -->
                                        <optgroup label="Female Voices (English - US)">
                                            <option <?= $result["model"] == "aura-asteria-en" ? "selected" : ""; ?>
                                                value="aura-asteria-en">Asteria</option>
                                            <option <?= $result["model"] == "aura-luna-en" ? "selected" : ""; ?>
                                                value="aura-luna-en">Luna</option>
                                            <option <?= $result["model"] == "aura-stella-en" ? "selected" : ""; ?>
                                                value="aura-stella-en">Stella</option>
                                            <option <?= $result["model"] == "aura-athena-en" ? "selected" : ""; ?>
                                                value="aura-athena-en">Athena</option>
                                            <option <?= $result["model"] == "aura-hera-en" ? "selected" : ""; ?>
                                                value="aura-hera-en">Hera</option>
                                        </optgroup>

                                        <!-- Male Voices -->
                                        <optgroup label="Male Voices (English - US)">
                                            <option <?= $result["model"] == "aura-orion-en" ? "selected" : ""; ?>
                                                value="aura-orion-en">Orion</option>
                                            <option <?= $result["model"] == "aura-arcas-en" ? "selected" : ""; ?>
                                                value="aura-arcas-en">Arcas</option>
                                            <option <?= $result["model"] == "aura-perseus-en" ? "selected" : ""; ?>
                                                value="aura-perseus-en">Perseus</option>
                                            <option <?= $result["model"] == "aura-angus-en" ? "selected" : ""; ?>
                                                value="aura-angus-en">Angus</option>
                                            <option <?= $result["model"] == "aura-orpheus-en" ? "selected" : ""; ?>
                                                value="aura-orpheus-en">Orpheus</option>
                                            <option <?= $result["model"] == "aura-helios-en" ? "selected" : ""; ?>
                                                value="aura-helios-en">Helios</option>
                                            <option <?= $result["model"] == "aura-zeus-en" ? "selected" : ""; ?>
                                                value="aura-zeus-en">Zeus</option>
                                            <option <?= $result["model"] == "aura-2-mars-en" ? "selected" : ""; ?>
                                                value="aura-2-mars-en">Mars</option>
                                        </optgroup>
                                    </select>

                                </div>
                                <div class="form-group">
                                    <label>
                                        Prompt
                                        <strong class="text-danger">*</strong>
                                    </label>
                                    <textarea name="prompt" id="" rows="20"
                                        class="form-control"><?= $result["prompt"]; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i>
                                        Save Changes
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>