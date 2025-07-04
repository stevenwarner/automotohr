<!-- Incident Item Section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="2">
                <strong>Incident Issue(s)</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($incidentIssues)) { ?>
            <tr>
                <th class="text-center">Severity Level</th>
                <th class="text-center">Description</th>
            </tr>
            <?php foreach ($incidentIssues as $issue) { ?>
                <tr>
                    <?php
                        //
                        $level = $severityStatus[$issue["severity_level_sid"]];
                        //
                        $decodedJSON = json_decode(
                            $issue["answers_json"],
                            true
                        );
                    ?>
                    <td>
                        <div class="csLabelPill jsSelectedLabelPill text-center"
                            style="background-color: <?= $level["bg_color"]; ?>; 
                        color: <?= $level["txt_color"]; ?>;">Severity Level <?= $level["level"]; ?></div>
                    </td>
                    <td>
                        <div class="col-sm-10 jsCSPItemDescription">
                            <?= convertCSPTags($issue["issue_description"], $decodedJSON ?? []); ?>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td>
                    <div class="center-col">
                        <h2>No Incident Issue Found</h2>
                    </div>
                </td>
            </tr>
        <?php } ?>    
    </tbody>
</table>

<?php if (!empty($incidentIssues)) { ?>
    <?php foreach ($incidentIssues as $issue) { ?>
        <article class="sheet-header">
            <div class="center-col">
                <h2><?php echo $issue['issue_title']; ?></h2>
            </div>
        </article>

        <?php 
            if ($issue['question_answer_json']) { 
                $this->load->view("compliance_safety_reporting/partials/download/question_new", ['questions' => $issue['question_answer_json']]); 
            }
        ?> 

        <?php 
            if ($issue['documents']) { 
                $this->load->view("compliance_safety_reporting/partials/download/documents", ['documents' => $issue['documents']]); 
            }
        ?> 

        <?php 
            if ($issue['audios']) { 
                $this->load->view("compliance_safety_reporting/partials/download/media", ['audios' => $issue['audios']]); 
            }
        ?> 

        <?php 
            if ($issue['visibilityManagersList']) { 
                $this->load->view("compliance_safety_reporting/partials/download/department_team_managers", ['managerList' => $issue['visibilityManagersList']]); 
            }
        ?>

        <?php 
            if ($issue['internal_employees']) { 
                $this->load->view("compliance_safety_reporting/partials/download/internal", ['internalEmployees' => $issue['internal_employees']]); 
            }
        ?>


        <?php 
            if ($issue['external_employees']) { 
                $this->load->view("compliance_safety_reporting/partials/download/external", ['externalEmployees' => $issue['external_employees']]); 
            }
        ?>

        <?php 
            if ($issue['emails']) { 
                $this->load->view("compliance_safety_reporting/partials/download/emails", ['emails' => $issue['emails']]);
            }
        ?>

        <?php 
            if ($report['notes']) { 
                $this->load->view("compliance_safety_reporting/partials/download/comments", ['notes' => $issue['notes']]);
            }
        ?>
    <?php } ?>    
<?php } ?>  