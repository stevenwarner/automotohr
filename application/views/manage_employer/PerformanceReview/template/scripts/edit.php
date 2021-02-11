var baseURI = "<?= base_url('performance/handler') ?>",
XHR = null,
template = {
main: <?= json_encode($template['main']); ?>,
questions: <?= json_encode($template['questions']); ?>,
};
const ratingText = <?= json_encode($RatingText); ?>;
const pages = {
step1: loadStep1,
step2: loadStep2,
step4: loadStep4,
step3: () => {},
};
let eQuestion = {},
eQuestionPlace = 0;

//
CKEDITOR.replace("js-template-description");
//
$("#js-template-status").select2();

//
$("#js-template-add-form").submit((e) => {
e.preventDefault();
let localOBJ = {};
localOBJ.title = $("#js-template-title").val().trim();
localOBJ.status = $("#js-template-status").val();
localOBJ.description = CKEDITOR.instances[
"js-template-description"
].getData();
//
if (localOBJ.title == "") {
alertify.alert("WARNING!", "Template name is required.");
} else {
template.main = localOBJ;
//
loadStep(2);
}
});

// Add Question Process Starts
//
$(".js-add-question").click((e) => {
e.preventDefault();
//
$('#js-add-question-number').text(template.questions.length + 1);
//
if (CKEDITOR.instances['js-add-question-description'] === undefined) CKEDITOR.replace('js-add-question-description');
//
$(".js-add-use-label-box").hide(0);
$("#js-add-use-label").prop("checked", false);
$("#js-add-include-na").prop("checked", false);
//
$("#js-add-question-text").val("");
CKEDITOR.instances['js-add-question-description'].setData("");
$("#js-add-question-type").select2();
$("#js-add-question-type").select2("val", "text");
//
$("#js-add-rating-scale").select2();
$("#js-add-rating-scale").select2("val", "0");
//
$(".js-add-rating-box").hide(0);
$(".js-add-rating-list-box").hide(0).html("");
//
loadStep(3);
});

//
$("#js-add-question-type").change((e) => {
$("#js-add-use-label").prop("checked", false);
$(".js-add-rating-list-box").show(0).html("");
$(".js-add-rating-box").hide(0);
if (e.target.value === "text") {
$(".js-add-use-label-box").hide(0);
$(".js-add-rating-list-box").hide(0).html("");
} else {
$(".js-add-use-label-box").show(0);
$(".js-add-rating-box").show(0);
}
});

//
$("#js-add-use-label").click(function (e) {
if ($(this).prop("checked") === true) {
$(".js-add-rating-list-box").show(0);
$(".js-add-rating-list-box").html(getRatingRows($('#js-add-rating-scale').val()));
} else {
$(".js-add-rating-list-box").hide(0).html("");
}
});

//
$('#js-add-rating-scale').change(function () {
if ($("#js-add-use-label").prop('checked') === true) {
$(".js-add-rating-list-box").show(0);
$(".js-add-rating-list-box").html(getRatingRows($(this).val()));
}
});

//
$("#js-add-question-submit").submit((e) => {
e.preventDefault();
//
let q = {};
//
q.question = $('#js-add-question-text').val().trim();
q.description = CKEDITOR.instances['js-add-question-description'].getData();
q.type = $('#js-add-question-type').val();
q.ratingScale = $('#js-add-rating-scale').val();
q.useLabels = $('#js-add-use-label').prop('checked');
q.includeNA = $('#js-add-include-na').prop('checked');
q.sortOrder = 1;
q.ratingLabels = [];
if (q.type !== 'text' && q.useLabels === true) {
q.ratingLabels = getRatingLabels();
}
//
if (q.question == '') {
alertify.alert('WARNING!', 'Question is required.', () => {});
} else if (q.type === '0') {
alertify.alert('WARNING!', 'Question type is required.', () => {});
} else {
template.questions.push(q);
alertify.alert('SUCCESS!', 'Question added successfully.', () => {
loadStep(2);
})
}
});

//
function getRatingLabels(typo) {
//
let dataRows = [];
//
$('.js-rating-text-row'+( typo === undefined ? '' : typo )+'').each(function (i, el) {
dataRows.push({ id: i, text: $(this).find('input').val() })
});
//
return dataRows;
}

//
function getRatingRows(il, typo) {
let i = 0,
rows = "";
//
typo = typo === undefined ? '' : typo;
if (il === 0) return '';

for (i; i < il; i++) { rows +=`<div class="cs-m5  js-rating-text-row${typo}"><label>Rating ${
        i + 1
        }</label><input class="form-control" value="${getRatingText(i, typo)}" /></div>`;
    }

    return rows;
    }

    //
    function getRatingText(i, typo) {
    if (typo !== '') {
    return eQuestion.ratingLabels[i] === undefined ? ratingText[i] : eQuestion.ratingLabels[i].text;
    }
    return ratingText[i];
    }

    //
    function callDrager(){
    $( "#js-data-area" ).sortable({
    placeholder: "ui-state-highlight"
    });
    $( "#js-data-area" ).disableSelection();
    }

    $( "#js-data-area" ).on( "sortstop", callSort);

    function callSort(e, c) {
    //
    let new_index = $('#js-data-area').find(`tr[data-number="${$(c.item).data('number')}"]`).parent().index() - 1;
    //
    template.questions = array_move(
    template.questions,
    $(c.item).data('number') - 1,
    new_index
    );
    //
    loadStep2();
    }

    function array_move(arr, old_index, new_index) {
    arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
    return arr; // for testing
    };

    // Add Question Process Ends


    // Question remove starts
    $(document).on('click', '.js-question-remove', function (e) {
    //
    e.preventDefault();
    //
    template.questions.splice($(this).closest('tr').data('number') -1,1);
    loadStep2();
    });
    // Question remove ends

    // Question edit starts
    $(document).on('click', '.js-question-edit', function (e) {
    e.preventDefault();
    //
    eQuestion = {};
    eQuestionPlace = $(this).closest('tr').data('number');
    //
    let question = template.questions[$(this).closest('tr').data('number') - 1];
    //
    if (question === undefined) return;
    //
    eQuestion = question;
    //
    loadStep(4);
    });

    //
    function loadStep4() {
    //
    $('#js-edit-question-number').text(eQuestionPlace);
    //
    if (CKEDITOR.instances['js-edit-question-description'] === undefined) CKEDITOR.replace('js-edit-question-description');
    $(".js-edit-rating-box").hide(0);
    $(".js-edit-rating-list-box").hide(0).html("");
    //
    $("#js-edit-use-label").prop("checked", eQuestion.useLabels);
    $("#js-edit-include-na").prop("checked", eQuestion.includeNA);
    //
    $("#js-edit-question-type").select2();
    $("#js-edit-question-type").select2("val", eQuestion.type);
    //
    $("#js-edit-rating-scale").select2();
    $("#js-edit-rating-scale").select2("val", eQuestion.ratingScale);
    //
    //
    $("#js-edit-question-text").val(eQuestion.question);
    //
    CKEDITOR.instances['js-edit-question-description'].setData(eQuestion.description);
    }


    $("#js-edit-question-type").change((e) => {
    $(".js-edit-rating-box").hide(0);
    if ($(e.target).val() != 'text') {
    $(".js-edit-rating-box").show(0);
    }
    });

    $('#js-edit-rating-scale').change((e) => {
    if ($("#js-edit-use-label").prop("checked") === true) {
    $(".js-edit-rating-list-box").show(0).html(getRatingRows($(e.target).val(), '-edit' ));
    }
    });

    //
    $("#js-edit-use-label").click(function (e) {
    if ($(this).prop("checked") === true) {
    $(".js-edit-rating-list-box").show(0);
    $(".js-edit-rating-list-box").html(getRatingRows($('#js-edit-rating-scale').val(), '-edit'));
    } else {
    $(".js-edit-rating-list-box").hide(0).html("");
    }
    });

    $(".js-edit-question-cancel").click((e) => {
    e.preventDefault();
    eQuestion = {};
    eQuestionPlace = 0;
    //
    loadStep(2);
    });

    $("#js-edit-question-submit").submit((e) => {
    e.preventDefault();
    //
    let q = {};
    //
    q.question = $('#js-edit-question-text').val().trim();
    q.description = CKEDITOR.instances['js-edit-question-description'].getData();
    q.type = $('#js-edit-question-type').val();
    q.ratingScale = $('#js-edit-rating-scale').val();
    q.useLabels = $('#js-edit-use-label').prop('checked');
    q.includeNA = $('#js-edit-include-na').prop('checked');
    q.sortOrder = 1;
    q.ratingLabels = [];
    //
    if (q.type !== 'text' && q.useLabels === true) {
    q.ratingLabels = getRatingLabels('-edit');
    }
    //
    if (q.question == '') {
    alertify.alert('WARNING!', 'Question is required.', () => { });
    } else if (q.type === '0') {
    alertify.alert('WARNING!', 'Question type is required.', () => { });
    } else {
    template.questions[eQuestionPlace -1] = q;
    alertify.alert('SUCCESS!', 'Question updated successfully.', () => {
    loadStep(2);
    })
    }
    });
    // Question edit ends

    //
    $(".js-question-cancel").click((e) => {
    e.preventDefault();
    //
    loadStep(2);
    });

    $('.js-add-save-template').click((e) => {
    //
    e.preventDefault();
    //
    if (template.questions.length === 0) {
    alertify.alert("WARNING!", "Please add at-least one question.");
    return;
    }
    //
    loader(true);
    //
    $.post(`${baseURI}`, {
    Action: 'update_template',
    Data: template,
    Id: <?= $id; ?>
    }, (resp) => {
    //
    if (resp.Status === false) {
    alertify.alert("WARNING!", resp.Response, () => { });
    loader(false);
    return;
    }
    alertify.alert("SUCCESS!", resp.Response, () => {
    window.location.reload();
    });
    })
    //
    });

    //
    function loadStep2() {
    let rows = "";
    //
    if (template.questions.length > 0) {
    template.questions.map((question, i) => {
    rows += "<tr data-number="+(i+1)+">";
        rows += ` <td>${question.question}</td>`;
        rows += ` <td>${question.type.toUpperCase()}</td>`;
        rows += ` <td>
            <a href="" class="btn btn-warning js-question-edit" title="Edit Question"><i class="fa fa-pencil"></i></a>
            <a href="" class="btn btn-danger js-question-remove" title="Remove Question"><i class="fa fa-remove"></i></a>
        </td>`;
        rows += "</tr>";
    });
    $(".js-add-save-template").attr('disabled', false);
    } else {
    rows = `<tr>
        <td colspan="${
      $(" table th").length }">
            <p class="alert alert-info text-center">No questions found.</p>
        </td>
    </tr>`;
    $(".js-add-save-template").prop('disabled', true);
    }
    $("table tbody").html(rows);
    callDrager();
    }

    //
    function loadStep(step) {
    $(".js-template-step").hide(0);
    $('.js-template-step[data-step="' + step + '"]').show(0);
    pages[`step${step}`]();
    }

    //
    function loader(doShow) {
    if (doShow) {
    $(".cs-loader").show();
    } else {
    $(".cs-loader").hide();
    }
    }

    $('.js-view-template').click((e) => {
    e.preventDefault();
    loadStep(1);
    });


    function loadStep1() {
    $('#js-template-title').val(template.main.title);
    CKEDITOR.instances['js-template-description'].setData(template.main.description);
    $('#js-template-status').select2('val', template.main.status);
    }

    loader(false);
    loadStep(1);