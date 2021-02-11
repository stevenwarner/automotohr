var baseURI = "<?= base_url('performance/handler') ?>",
  answers = <?= json_encode($answers);?>,
  XHR = null;


//
$(".cs-rating-btn").click(function (e) {
  e.preventDefault();
  //
  answers[$(this).data("pid")]['rating'] = $(this).find('p:nth-child(1)').text();
  $('.cs-rating-btn[data-pid="' + $(this).data("pid") + '"]').removeClass(
    "active"
  );
  $(this).addClass('active');
});

//
$(".cs-text-answer").keyup(function (e) {
  e.preventDefault();
  //
  answers[$(this).data("pid")]['text'] = $(this).val();
});

//
$('.js-save-answers').click((e) => {
  e.preventDefault();
  let isError = false;
  
  Object.keys(answers).map((index, i) => {
    if (isError === true) return;
    let answer = answers[index];
    if (answer.text != undefined && answer.rating != undefined && (answer.text == '' || answer.rating == '')) {
      alertify.alert('WARNING!', `Answer is required for question ${i +1}`, () => { });
      isError = true;
      return;
    }
    if (answer.rating !== undefined && answer.rating == '') {
      alertify.alert('WARNING!', `Answer is required for question ${i +1}`, () => { });
      isError = true;
      return;
    }
    if (answer.text !== undefined && answer.text == '' && answer.text != '-1') {
      alertify.alert('WARNING!', `Answer is required for question ${i +1}`, () => { });
      isError = true;
        return;
      }
  });
  //
  if (isError === false) {
    loader('show');
    //
    $.post(baseURI, {
      Action: 'save_answers',
      Data: answers,
      Id: <?= $review['main']['sid'];?>,
      EId: <?= $review['reviewee_sid'];?>
    }, (resp) => {
      if (resp.Status === false) {
        alertify.alert('WARNING!', resp.Response, () => { });
        return;
      }
      alertify.alert('SUCCESS!', resp.Response, () => { 
        window.location.href = "<?=base_url('performance/assigned/view');?>";
      });
    })
  }
});

//
function makeEmployeeName(obj) {announcements
  let name = `${obj.first_name} ${obj.last_name}`;
  if (obj.job_title != "") name += ` (${obj.job_title})`;
  name += ` [${obj.access_level}${obj.access_level_plus == 1 ? " Plus" : ""}]`;
  //
  return name;
}

//
function loader(doShow) {
  if (doShow) {
    $(".cs-loader").show();
  } else {
    $(".cs-loader").hide();
  }
}

//
loader(false);
