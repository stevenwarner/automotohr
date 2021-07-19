$(function() {

    $('.jsViewFeedback').click(function(event) {
        //
        event.preventDefault();
        //
        var data = $(this).closest('tr').data();
        //
        var record;
        //
        reviews.map(function(review) {
            //
            if (
                review.review_sid == data.id &&
                review.reviewee_sid == data.reviwee &&
                review.reviewer_sid == data.reviwer
            ) {
                record = review;
            }
        });
        //
        var html = '';
        html += '<!-- Rating -->';
        html += '<div class="container">';
        if (record.feedback.rating != null) {
            html += '<div class="row">';
            html += '    <br />';
            html += '    <ul class="csRatingBar pl10 pr10">';
            html += '        <li data-id="1" class="jsReviewRating ' + (record.feedback.rating == 1 ? "active" : '') + '">';
            html += '            <p class="csF20 csB9">1</p>';
            html += '            <p class="csF14 csB6">Strongly Agree</p>';
            html += '        </li>';
            html += '        <li data-id="2" class="jsReviewRating ' + (record.feedback.rating == 2 ? "active" : '') + '">';
            html += '            <p class="csF20 csB9">2</p>';
            html += '            <p class="csF14 csB6">Agree</p>';
            html += '        </li>';
            html += '        <li data-id="3" class="jsReviewRating ' + (record.feedback.rating == 3 ? "active" : '') + '">';
            html += '            <p class="csF20 csB9">3</p>';
            html += '            <p class="csF14 csB6">Neutral</p>';
            html += '        </li>';
            html += '        <li data-id="4" class="jsReviewRating ' + (record.feedback.rating == 4 ? "active" : '') + '">';
            html += '            <p class="csF20 csB9">4</p>';
            html += '            <p class="csF14 csB6">Disagree</p>';
            html += '        </li>';
            html += '        <li data-id="5" class="jsReviewRating ' + (record.feedback.rating == 5 ? "active" : '') + '">';
            html += '            <p class="csF20 csB9">5</p>';
            html += '            <p class="csF14 csB6">Strongly Disagree</p>';
            html += '        </li>';
            html += '    </ul>';
            html += '</div>';
        }
        html += '<!-- Text -->';
        html += '<div class="row">';
        html += '    <br />';
        html += '    <div class="col-xs-12">';
        html += '        <p class="csF14 csB7">Feedback (Elaborate)</p>';
        html += '        <textarea rows="5" class="form-control jsReviewText">' + (record.feedback.text_answer) + '</textarea>';
        html += '    </div>';
        html += '</div>';
        html += '</div>';
        //
        Modal({
            Id: 'jsViewFeedbackModal',
            Title: 'Feedback from ' + (record.first_name) + ' ' + (record.last_name) + ' for ' + record.review_title,
            Body: html,
            Loader: 'jsViewFeedbackModalLoader'
        });
        //
        ml(false, 'jsViewFeedbackModalLoader');
    });

});