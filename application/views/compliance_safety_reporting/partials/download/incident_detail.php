<article class="sheet-header">
    <div class="center-col">
        <h2><?php echo 'Incident - '.$incidentDetail['compliance_incident_type_name']; ?></h2>
    </div>
</article>

<!-- Incident Creator Information section Start -->
<table class="incident-table">
    <thead>
        <tr class="bg-gray">
            <th colspan="4">
                <strong>Incident Information</strong>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <th class="text-center">Created By</th>
            <th class="text-center">Created Date</th>
            <th class="text-center">Completion Date</th>
            <th class="text-center">Incident Status</th>
        </tr>
        <tr>
            <td class="text-center"><?php echo $incidentDetail['first_name'].' '.$incidentDetail['last_name']; ?></td>
            <td class="text-center"><?php echo !empty($incidentDetail['created_at']) ? formatDateToDB($incidentDetail['created_at'], DB_DATE_WITH_TIME, DATE) : 'N/A'; ?></td>
            <td class="text-center"><?php echo !empty($incidentDetail['completed_at']) ? formatDateToDB($incidentDetail['completed_at'], DB_DATE_WITH_TIME, DATE) : 'N/A'; ?></td>
            <td class="text-center"><?php echo !empty($incidentDetail['status']) ? strtoupper($incidentDetail['status']) : 'N/A'; ?></td>
        </tr>
        <tr>
            <td colspan="4">
                <label>
                    <strong>Description :</strong>
                </label>
                <span class="value-box bg-gray">
                    <?php echo $incidentDetail['description']; ?>
                </span>
            </td>
        </tr>
    </tbody>
</table>
<!-- Incident Creator Information section End -->

<?php $this->load->view("compliance_safety_reporting/partials/download/items", 
    [
        'incidentItemsSelected' => $incidentDetail['incidentItemsSelected'],
        'severityStatus' => $incidentDetail['severity_status']
    ]
); ?>

<?php 
    if ($incidentDetail['documents']) { 
        $this->load->view("compliance_safety_reporting/partials/download/documents", ['documents' => $incidentDetail['documents']]); 
    }
?> 

<?php 
    if ($incidentDetail['audios']) { 
        $this->load->view("compliance_safety_reporting/partials/download/media", ['audios' => $incidentDetail['audios']]); 
    }
?> 

<?php 
    if ($incidentDetail['internal_employees']) { 
        $this->load->view("compliance_safety_reporting/partials/download/internal", ['internalEmployees' => $incidentDetail['internal_employees']]); 
    }
?>

<?php 
    if ($incidentDetail['external_employees']) { 
        $this->load->view("compliance_safety_reporting/partials/download/external", ['externalEmployees' => $incidentDetail['external_employees']]); 
    }
?>

<?php 
    if ($incidentDetail['emails']) { 
        $this->load->view("compliance_safety_reporting/partials/download/emails", ['emails' => $incidentDetail['emails']]);
    }
?>

<?php 
    if ($incidentDetail['notes']) { 
        $this->load->view("compliance_safety_reporting/partials/download/comments", ['notes' => $incidentDetail['notes']]);
    }
?>  