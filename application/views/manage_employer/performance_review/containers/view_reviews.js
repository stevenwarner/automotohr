class ReviewViewPage extends React.Component{
    constructor(props){
        super(props);
        this.startReviewHandler = this.startReviewHandler.bind(this);
        this.viewReviewHandler = this.viewReviewHandler.bind(this);
        this.cancelReviewHandler = this.cancelReviewHandler.bind(this);

        this.startSingleReviewHandler = this.startSingleReviewHandler.bind(this);
        this.viewSingleReviewHandler = this.viewSingleReviewHandler.bind(this);
        this.cancelSingleReviewHandler = this.cancelSingleReviewHandler.bind(this);

        this.backClick = this.backClick.bind(this);
        this.strCnclSingleReview = this.strCnclSingleReview.bind(this);
        this.cancelReview = this.cancelReview.bind(this);

        this.state = {
            viewFlag: 0,
            reviewId:0,
            revieersList: {},
            questionsArray:[]
        }
    }

    componentDidMount(){
        console.log(this.props.reviews);
    }

    startReviewHandler(e){
        let id = e.target.dataset.attr;
        let _this = this;
        alertify.confirm('Confirm','Do you really want to start this review?',
        function(){
            axios.post(`${base_url}/performance/handler`, JSON.stringify({
                action: 'start_review',
                sid: id
            }), {
                headers: {'X-Requested-With':'XMLHttpRequest'}
            })
            .then((resp) => {
                _this.props.strCnclReview(id, 'started');
            })
            .catch((err) => {
                console.log(err)
            });
        },
        function() {
            
        });
    }

    cancelReviewHandler(e){
        let id = e.target.dataset.attr;
        let _this = this;
        alertify.confirm('Confirm','Do you really want to cancel this review?',
        function(){
            axios.post(`${base_url}/performance/handler`, JSON.stringify({
                action: 'cancel_review',
                rid: id
            }), {
                headers: {'X-Requested-With':'XMLHttpRequest'}
            })
            .then((resp) => {
                _this.props.strCnclReview(id, 'cancelled');
            })
            .catch((err) => {
                console.log(err)
            });
        },
        function() {
            
        });
    }

    startSingleReviewHandler(e){
        let cid = e.target.dataset.attr;
        let rid = e.target.dataset.rid;
        let _this = this;
        alertify.confirm('Confirm','Do you really want to start this review?',
        function(){
            axios.post(`${base_url}/performance/handler`, JSON.stringify({
                action: 'start_single_review',
                sid: cid,
                rid: rid,
                status: 1
            }), {
                headers: {'X-Requested-With':'XMLHttpRequest'}
            })
            .then((resp) => {
                _this.strCnclSingleReview(cid, 1);
                _this.props.strCnclReview(id, 'started');
            })
            .catch((err) => {
                console.log(err)
            });
        },
        function() {
            
        });
    }

    cancelSingleReviewHandler(e){
        let id = e.target.dataset.attr;
        let _this = this;
        alertify.confirm('Confirm','Do you really want to cancel this review?',
        function(){
            axios.post(`${base_url}/performance/handler`, JSON.stringify({
                action: 'start_single_review',
                sid: id,
                status: 0
            }), {
                headers: {'X-Requested-With':'XMLHttpRequest'}
            })
            .then((resp) => {
                _this.strCnclSingleReview(id, 0);
            })
            .catch((err) => {
                console.log(err)
            });
        },
        function() {
            
        });
    }

    async viewReviewHandler(e){
        let id = e.target.dataset.attr;
        let _this = this;
        await axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'fetch_reviewers_list',
            rid: id
        }), {
            headers: {'X-Requested-With':'XMLHttpRequest'}
        })
        .then((resp) => {
            _this.setState({reviewId: id, viewFlag: 1, revieersList: resp.data.Data});
        })
        .catch((err) => {
            console.log(err)
        });
    }

    async viewSingleReviewHandler(e){
        let id = e.target.dataset.attr;
        let selectedReview = this.state.revieersList[id];
        let review_id = this.state.revieersList[id].review_id;
        //fetch questions
        axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'fetch_questions_answers',
            reviewId: review_id,
            conductor_sid: selectedReview.conduct_table_id,
        }), {
            headers: {'X-Requested-With':'XMLHttpRequest'}
        })
        .then((resp) => {
            this.setState({viewFlag: 2, questionsArray: resp.data.Data});            
        })
        .catch((err) => {
            console.log(err)
        });
        // this.setState({viewFlag: 2, questionsArray: this.state.revieersList[id].questions});
        
    }

    strCnclSingleReview(index, status){
        this.setState(prevState => {
            let prev = Object.assign({}, prevState.revieersList);  // creating copy of state variable
            prev[index].conductor_status = status;  
            return { prev };
        });
    }

    backClick(){
        this.setState({reviewId: 0, viewFlag: 0, revieersList: {}});
    }

    generateListView(){
		let questionList = [];
		this.state.questionsArray.map((question, index) => {
            let questionType = question.responseType;
            let answerDiv = '';
            if(questionType == 'text'){
                answerDiv = <div className="col-lg-12">
                                <div className="col-lg-5">
                                    <h5>Shared Feedback</h5>
                                    <p><b>{question.reviewer_name} > {question.reviewee_name}</b></p>
                                </div>
                                <div className="col-lg-7">
                                    <h5>&nbsp;</h5>
                                    <p>{question.text_answer}</p>
                                </div>
                                {/* <hr></hr> */}
                            </div>
            }else if(questionType == 'rating'){
                answerDiv = <div className="col-lg-12">
                                <div className="col-lg-5">
                                    <h5>Shared Feedback</h5>
                                    <p><b>{question.reviewer_name} > {question.reviewee_name}</b></p>
                                </div>
                                <div className="col-lg-7">
                                    {question.is_completed ? <h5><b>{question.rating_answer}</b> (out of ) {question.scale}</h5> : <h5>Not Given Yet</h5>}
                                    {/* <p>{question.text_answer}</p> */}
                                </div>
                                {/* <hr></hr> */}
                            </div>
            }else {
                answerDiv = <div className="col-lg-12">
                                <div className="col-lg-5">
                                    <h5>Shared Feedback</h5>
                                    <p><b>{question.reviewer_name} > {question.reviewee_name}</b></p>
                                </div>
                                <div className="col-lg-7">
                                    {question.is_completed ? <h5><b>{question.rating_answer}</b> (out of ) {question.scale}</h5> : <h5>Not Given Yet</h5>}
                                    <p>{question.text_answer}</p>
                                </div>
                                {/* <hr></hr> */}
                            </div>
            }
			questionList.push(
                <div key={index}>
                    <div className="row">
                        {!index && !question.is_completed ? <div className="col-lg-12"><p className="text-center"><b>Review have not been conducted yet!</b></p></div> : ''}

                        <div className="col-lg-12">
                            <label>QUESTION {index+1}</label>
                        </div>
                        
                        <div className="col-lg-12">
                            <label>{this.props.ucwords(question.question)}</label>
                        </div>
                        {
                            // question.options.n_a && question.options.n_a != '0' ? (<div className="col-lg-12"><label>Not Applicable</label></div>) : ''
                        }
                        {
                            answerDiv
                        }
                    </div>
                    <hr></hr>
                </div>
            )
        });
        
		return questionList;
    }
    
    cancelReview(){
        this.setState({viewFlag: 1,questionsArray: []})
    }

    render(){
        //
        let table = '';
        let backBtn = '';
        let actionBtn = '';
        let templates = this.props.reviews;
        let revieersList = this.state.revieersList;

        if(this.state.viewFlag == 1){
            backBtn = <button onClick={this.backClick} className="dashboard-link-btn2 js-add-btn"><i className="fa fa-arrow-left"></i>&nbsp; Back</button>
            let rows = Object.keys(revieersList).map((index) => {

                if((revieersList[index].conductor_status === '0' || !revieersList[index].conductor_status) /* && revieersList[index].status != 'ended'*/){
                    actionBtn = <button onClick={this.startSingleReviewHandler} data-attr={index} data-rid={revieersList[index].review_id} className="btn btn-info" title="Start Review">Start</button>
                }else{
                    actionBtn = <button onClick={this.cancelSingleReviewHandler} data-attr={index} className="btn btn-default" title="Start Review">Cancel</button>
                }
                return(
                    <tr key={index}>
                        <td>{revieersList[index].reviewee_name}</td>
                        <td>{revieersList[index].reviewer_name}</td>
                        <td>{revieersList[index].start_date}</td>
                        <td>{revieersList[index].end_date}</td>
                        <td>
                            <button onClick={this.viewSingleReviewHandler} data-attr={index} className="btn btn-success" title="Start Review">View</button>
                            {actionBtn}
                        </td>
                    </tr>
                )}
            )
            table = <div className="table-responsive">
                        <table className="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Reviewee</th>
                                    <th>Reviewer</th>
                                    <th>Start On</th>
                                    <th>End On</th>
                                    <th className="col-sm-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>{rows}</tbody>
                        </table>
                    </div>
        } else if(this.state.viewFlag == 0){
            let rows = '';
            if(this.props.reviews.length === 0){
                rows = 
                <tr>
                    <td colSpan="6">
                        <p className="alert alert-info text-center">No Review found</p>
                    </td>
                </tr>;
            }else{
                rows = Object.keys(templates).map((index) => {

                    if(templates[index].status != 'started' /* && revieersList[index].status != 'ended'*/){
                        actionBtn = <button onClick={this.startReviewHandler} data-attr={index} className="btn btn-info" title="Start Review">Start</button>
                    }else{
                        actionBtn = <button onClick={this.cancelReviewHandler} data-attr={index} className="btn btn-default" title="Start Review">Cancel</button>
                    }
                    return(
                        <tr key={index}>
                            <td>{templates[index].title}</td>
                            <td>{this.props.ucwords(templates[index].status)}</td>
                            <td>{templates[index].start_date}</td>
                            <td>{templates[index].end_date}</td>
                            <td>
                                <button onClick={this.viewReviewHandler} data-attr={index} className="btn btn-success" title="Start Review">View</button>
                                {actionBtn}
                                
                            </td>
                        </tr>
                    )}
                )
            }          
            table =  <div className="table-responsive">
                        <table className="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>Start On</th>
                                    <th>End On</th>
                                    <th className="col-sm-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>{rows}</tbody>
                        </table>
                    </div>
        }else{
            backBtn = <button onClick={this.cancelReview} className="dashboard-link-btn2 js-add-btn"><i className="fa fa-arrow-left"></i>&nbsp; Back</button>
            table = 
            <div className="row">
                <div className="col-xs-12">
                    {this.generateListView()}
                    <div className="row">
                        <div className="col-lg-12 pull-right">
                            <button className="btn btn-default pull-right" onClick={this.cancelReview}>Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        }
        
        return (
            <div>
                <div className="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <ReviewTemplateViewPageHeader />
                    {this.state.viewFlag !=2 ? <ReviewTemplateViewPageSearchFilter /> : ''}
                    <br />

                    <div className="hr-box">
                        <div className="hr-box-header bg-header-green" style={{paddingTop: 11, paddingBottom: 11}}>
                            <span className="pull-left">
                                <h1 className="hr-registered">Performance Reviews</h1>
                            </span>
                            <span className="pull-right">
                                {backBtn}
                            </span>
                        </div>
                        <div className="hr-innerpadding">
                            <div className="row">
                                <div className="col-sm-12 js-ip-pagination"></div>
                            </div>
                            <div className="row">
                                <div className="col-xs-12">
                                    {table}
                                </div>
                            </div>
                            <div className="row">
                                <div className="col-sm-12 js-ip-pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}