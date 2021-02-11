class AssignedReviewPage extends React.Component{
    constructor(props){
        super(props);
        this.doReview = this.doReview.bind(this);
        this.saveAnswer = this.saveAnswer.bind(this);
        this.saveReview = this.saveReview.bind(this);
        this.cancelReview = this.cancelReview.bind(this);
        this.state = {
            assignedReviews: [],
            questionsArray: [],
            answersArray: {},
            reviewDiv: false
        };
        this._isMounted = false;
    }

    ratingBoxesValues = ['Unacceptable','Need Improvement','Meets Expectations','Exceeds Expectations','Outstanding'];

    componentDidMount(){
        //Assigned Reviews
        this._isMounted = true;
        axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'fetch_assigned_reviews'
        }), {
            headers: {'X-Requested-With':'XMLHttpRequest'}
        })
        .then((resp) => {
            if(this._isMounted && resp.data.Data != null && resp.data.Data != undefined){
                this.setState({assignedReviews: resp.data.Data});
            }
            // console.log(resp.data.Data);
            
        })
        .catch((err) => {
            console.log(err)
        });
    }

    componentWillUnmount() {
        this._isMounted = false;
    }

    doReview(e){
        var review = e.target.dataset.review;
        var employee = e.target.dataset.employee;
        var conductor = e.target.dataset.conductor;
        let selected = this.state.assignedReviews[review][employee];
        let tempAns = {};
        //fetch questions
        axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'fetch_questions_answers',
            reviewId: review,
            conductor_sid: conductor,
        }), {
            headers: {'X-Requested-With':'XMLHttpRequest'}
        })
        .then((resp) => {
            resp.data.Data.map(val  => {
                tempAns[val.question_sid] = {
                    radio: val.rating_answer,
                    text: val.text_answer,
                    question_id: val.question_sid,
                    employee_id: selected.id,
                    conductor_table_id: selected.conduct_table_id,
                    employee_table_id: selected.employee_table_id,
                    review_id: selected.review_id,
                    type: val.responseType,
                    conduct_tbl_id: selected.conduct_table_id
                }
            });
            console.log(resp.data.Data);
            
            this.setState({reviewDiv: true, questionsArray: resp.data.Data, answersArray: tempAns});         
        })
        .catch((err) => {
            console.log(err)
        });
    }

    saveAnswer(e){
        let type = e.target.dataset.attr;
        let queId = e.target.dataset.key;
        var answer = e.target.value;
        
        this.setState(preState => {
            let preAns = Object.assign({}, preState.answersArray);
            
            if(type == "radio"){
                preAns[queId].radio = answer;
            }else{
                preAns[queId].text = answer;
            }
            return { preAns };
        });
    }

    saveReview(){
        axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'save_assigned_reviews',
            answers: this.state.answersArray
        }), {
            headers: {'X-Requested-With':'XMLHttpRequest'}
        })
        .then((resp) => {
            this.setState({reviewDiv: false});
            // console.log(resp.data.Data);
            
        })
        .catch((err) => {
            console.log(err)
        });
        console.log(this.state.answersArray);
    }

    cancelReview(){
        this.setState({reviewDiv: false});
    }

    generateListView(){
		let questionList = [];
		let radioBoxes;
		this.state.questionsArray.map((question, index) => {
			radioBoxes = [];
			questionList.push(
				<div key={index} className="row">
					<div className="col-lg-12">
						<label>QUESTION {index+1}</label>
					</div>
					
					<div className="col-lg-12">
						<label>{this.props.ucwords(question.question)}</label>
					</div>
					{
						question.options.n_a && question.options.n_a != '0' ? (<div className="col-lg-12"><label>Not Applicable</label></div>) : ''
					}
					
					{
						question.responseType.indexOf('rating') != -1 ? (<div className="col-lg-12">
								<div className="btn-radio-group">
									{
										(Object.keys(question.rating).map(option => {

											radioBoxes.push(<label key={option} className="control control--radio">                                       
												<input defaultChecked={question.rating_answer == option ?  true : false} type="radio" name={index} value={option} data-key={question.question_sid} data-name={index} data-attr="radio" onChange={this.saveAnswer} className="video_source"/>
												<div className="control__indicator">
													{option} <br></br>
													{question.rating[option]}
												</div>
											</label>)
										}))
										
									}
									{radioBoxes}
								</div>
							</div>) : ''
					}

					{
                        question.responseType.indexOf('text') != -1 ? (<div className="col-lg-12"><textarea data-attr="text" value={question.text_answer != null ? question.text_answer : ''} data-key={question.question_sid} onChange={this.saveAnswer} className="form-control" placeholder="Reviewer's Comment"></textarea></div>) : ''
					}
					
					<hr></hr>

				</div>
            )
        });
        
		return questionList;
	}

    render(){
        //
        let rows = [];
        let content = ''; // Main content to show (listing or start review)
        let reviews = this.state.assignedReviews;
        console.log(reviews);
        
        if(this.state.reviewDiv){
            content = 
            <div className="hr-box">
                <div className="hr-box-header bg-header-green" style={{paddingTop: 11, paddingBottom: 11}}>
                    <span className="pull-left">
                        <h1 className="hr-registered">Review</h1>
                    </span>
                </div>
                <div className="hr-innerpadding">
                    <div className="row">
                        <div className="col-sm-12 js-ip-pagination"></div>
                    </div>
                    <div className="row">
                        <div className="col-xs-12">
                            {this.generateListView()}
                            <div className="row">
                                <div className="col-lg-12 pull-right">
                                    <button className="btn btn-default pull-right" onClick={this.cancelReview}>Cancel</button>
                                    <button className="btn btn-success pull-right" onClick={this.saveReview}>Save</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        }
        else 
        {
            if(reviews.length === 0){
                rows = 
                <tr>
                    <td colSpan="6">
                        <p className="alert alert-info text-center">No Review found</p>
                    </td>
                </tr>;
            }else{
                // let statusHandler = this.props.
                Object.keys(reviews).map((review) => {
                    rows.push(
                        Object.keys(reviews[review]).map((employee) => {
                            return(
                                <tr key={review+employee}>
                                    <td>{(this.props.ucwords(reviews[review][employee].title))}</td>
                                    <td>{(reviews[review][employee].full_name)}</td>
                                    <td>{(reviews[review][employee].start_date)}</td>
                                    <td>
                                        <button className="btn btn-success" title="Start Review" onClick={this.doReview} data-review={review} data-employee={employee} data-conductor={reviews[review][employee].conduct_table_id}>Start Review</button>
                                    </td>
                                </tr>
                            )
                        })
                    )
                })
            }
            content =
            <div className="hr-box">
                <div className="hr-box-header bg-header-green" style={{paddingTop: 11, paddingBottom: 11}}>
                    <span className="pull-left">
                        <h1 className="hr-registered">Assigned Reviews</h1>
                    </span>
                </div>
                <div className="hr-innerpadding">
                    <div className="row">
                        <div className="col-sm-12 js-ip-pagination"></div>
                    </div>
                    <div className="row">
                        <div className="col-xs-12">
                            <div className="table-responsive">
                                <table className="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Reviewee</th>
                                            <th>Start At</th>
                                            <th className="col-sm-2">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>{rows}</tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div className="row">
                        <div className="col-sm-12 js-ip-pagination"></div>
                    </div>
                </div>
            </div>
        }
        
        return (
            <div>
                <div className="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <ReviewTemplateViewPageHeader />
                    <br />

                    {content}
                    
                </div>
            </div>
        );
    }
}