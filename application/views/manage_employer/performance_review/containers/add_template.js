const initialState = {
    new: 1,
    title: '',
    description: '',
    status: 1,
    startDate: '',
    endInDays: 0,
    currentStep: 0,
    reviewEmployees: [],
    questions: [],
    questionCount: 0,
    empCount: 0,
    revType: 1,
    dep: 0,
    selfRev: '',
    templatesList: []
};
class ReviewTemplateAddPage extends React.Component{

    constructor(props){
        super(props);
        this.validate = this.validate.bind(this);
        this.startWith = this.startWith.bind(this);
        this.changeReviewEmp = this.changeReviewEmp.bind(this);
        this.changeStep = this.changeStep.bind(this);
        this.changeEmpCount = this.changeEmpCount.bind(this);
        this.revType = this.revType.bind(this);
        this.changeDepartment = this.changeDepartment.bind(this);
        this.changeQuestions = this.changeQuestions.bind(this);
        this.changeQueCount = this.changeQueCount.bind(this);
        this.changeSelfReview = this.changeSelfReview.bind(this);
        this.saveReviewEmp = this.saveReviewEmp.bind(this);
        this.saveStartDate = this.saveStartDate.bind(this);
        this.saveEndInDays = this.saveEndInDays.bind(this);
        this.changeTemplate = this.changeTemplate.bind(this);
    }

    state = initialState;

    componentDidMount(){
        console.log('asd');
        
        if(!CKEDITOR.instances.hasOwnProperty('js-ckeditor') && this.state.currentStep == 0){
            // CKEDITOR.replace('js-ckeditor');
            // $('.js-status').select2();
        }
        // this.setState({templatesList: this.props.templates});
    }

    componentDidUpdate(){
        //
        // if(!CKEDITOR.instances.hasOwnProperty('js-ckeditor')){
        //     CKEDITOR.replace('js-ckeditor');
        //     $('.js-status').select2();
        // }
    }

    changeTemplate(e){

        var template = this.state.templatesList[e.target.value];
        this.setState({title: template.title, 
            description: template.description, 
            questions: template.questions,
            questionCount: template.questions.length
        });
        // this.setState(prevState => {
        //     let prevIndexVal = Object.assign({}, prevState.questions);  // creating copy of state variable
        //     prevIndexVal = false;   
        //     if(prevIndexVal[key].reviewer !== undefined){
        //         delete prevIndexVal[key].reviewer[key];      
        //     }                             
        //     return { prevIndexVal };
        // });
    }

    saveStartDate(e){
        var date = e.target.value;
        this.setState({startDate: date});
    }

    saveEndInDays(e){
        var days = e.target.value;
        this.setState({endInDays: days});
    }

    changeReviewEmp(revEmp){
        this.setState({reviewEmployees: revEmp});
    }

    async saveReviewEmp(revEmp){
        //Save review
        await axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'save_review_template',
            data_to_save: this.state
        }), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then((resp) => {
            if (resp.data.Data != null) {
                this.setState(initialState);
                <Redirect to="performance/review/view"/>
            }
        })
        .catch((err) => {
            console.log(err)
        });
        this.setState({reviewEmployees: revEmp, currentStep: 0});
    }

    changeQuestions(questions){
        this.setState({questions: questions});
    }

    changeQueCount(count){
        this.setState({questionCount: count});
    }

    changeStep(step){
        this.setState({ currentStep: step })
    }

    changeEmpCount(count){
        this.setState({empCount: count});
    }

    revType(type){
        this.setState({revType: type});
    }

    changeDepartment(d){
        this.setState({dep: d});
    }

    async validate(e){
        e.preventDefault();
        //
        if(this.state.title == ''){
            alertify.alert('ERROR!', 'Template title is required.');
            return;
        }
        await axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'check_title',
            title: this.state.title
        }), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then((resp) => {
            if (resp.data.Data) {
                alertify.alert('ERROR!', 'Title Already Exists');
                return;
            }
        })
        .catch((err) => {
            console.log(err)
        });
        
        if(this.state.startDate == ''){
            alertify.alert('ERROR!', 'Review Start Date is required.');
            return;
        }
        //
        if(this.state.endInDays == '' || this.state.endInDays == 0){
            alertify.alert('ERROR!', 'Review Due is required.');
            return;
        }
        //
        await this.setState({
            // description: CKEDITOR.instances['js-ckeditor'].getData(),
            description: $('.js-ckeditor').val(),
            status: $('.js-status').val()
        })
        

        this.setState({ currentStep: 1 });

    }

    changeSelfReview(self){
        this.setState({selfRev: self});
    }

    startWith(e){
        this.setState({new: e.target.value});
    }

    render(){
        //        
        let tempDiv = [];
        let temp = [];
        if(this.state.new == 0){
            let list = this.state.templatesList;
            if(Object.keys(list).length > 0){
                temp = Object.keys(list).map(template_id => {
                    return <div key = {template_id}>
                                <br></br>
                                <input type="radio" name="existTemp" value={list[template_id].id} onChange={this.changeTemplate}></input>{list[template_id].title}
                            </div>
                });
            tempDiv = <div><h2>Existing Templates</h2>{temp}</div>
            }else{
                tempDiv = 'No Template found';
            }
        }
        // let headingRows = 
        //     <span className="pull-right">
        //         <Link to={`${base_url}/performance/review/view`} className="dashboard-link-btn2">
        //             <i className="fa fa-arrow-left"></i>&nbsp; View Review Templates
        //         </Link>
        //     </span>;
        // let mainRows = 
        //     <div>
        //         <h1>Build A Review</h1>
        //         <hr></hr>
        //         <label>Craft a new review from the ground up or pick a template with insightful questions.</label>
        //         <hr></hr>
        //         <div className="row">
        //             <div className="col-lg-4">
        //                 <label>Options</label>
        //             </div>
        //             <div className="col-lg-8">
        //                 <input type="radio" name="startOption" value={1} onChange={this.startWith} defaultChecked></input>New Review<br></br>
        //                 <input type="radio" name="startOption" value={0} onChange={this.startWith}></input>Use Template
        //             </div>
        //         </div>
        //         <hr></hr>
        //         {tempDiv}
        //         <hr></hr>
        //         <div className="row">
        //             <div className="col-lg-12">
        //                 <div className="pull-right">
        //                     <a onClick={ (e) => this.setState({ currentStep: 0 })} className="btn btn-success"> Next </a> &nbsp;
        //                     <NavLink to={`${base_url}/performance/review/view`} className="btn btn-default">Cancel</NavLink>
        //                 </div>
        //             </div>

        //         </div>
        //         <hr></hr>
        //     </div>

        // if(this.state.currentStep === 0 ){  
            let headingRows = 
            <span className="pull-right">
                <Link to={`${base_url}/performance/review/view`} className="dashboard-link-btn2">
                    <i className="fa fa-arrow-left"></i>&nbsp; View Review Templates
                </Link>
            </span>;
            let mainRows = 
            <form onSubmit={this.validate}>
                <div className="form-group">
                    <label>Title <span className="cs-required">*</span></label>
                    <input type="text" className="form-control" value={this.state.title} 
                    onChange={(e) => this.setState({ title: e.target.value}) }/>
                </div>

                <div className="form-group">
                    <label>Description</label>
                    <textarea className="form-control js-ckeditor" name="js-ckeditor" 
                    defaultValue={this.state.description}/>
                </div>

                <div className="form-group">
                    <label>Start Date <span className="cs-required">*</span></label>
                    <input onChange={this.saveStartDate} type="date" placeholder="Start Date" className="form-control review-start" value={this.state.startDate}></input>
                </div>

                <div className="form-group">
                    <label>When are reviews due? <span className="cs-required">*</span></label>
                    <input onChange={this.saveEndInDays} type="number" placeholder="7" className="form-control" value={this.state.endInDays}></input>
                </div>


                <div className="form-group">
                    <label>Status</label>
                    <select className="form-control js-status" defaultValue={this.state.status}>
                        <option value={1}>Active</option>
                        <option value={0}>Archive</option>
                    </select>
                </div>

                <div className="form-group">
                    <input type="submit" className="btn btn-success" value="Next" /> &nbsp;
                    <Link to={"view"} className="btn btn-default"> Cancel </Link>
                    {/* <a onClick={ (e) => this.setState({ currentStep: 0 })} className="btn btn-default"> Cancel </a> */}
                </div>
            </form>;
        // }
        //
        if(this.state.currentStep === 1 ){
            headingRows = 
            <span className="pull-right">
                <a onClick={() => {
                    // delete CKEDITOR.instances['js-ckeditor'];
                    this.setState({ currentStep: 0 })

                } } className="dashboard-link-btn2">
                    <i className="fa fa-arrow-left"></i>&nbsp; Back To Template
                </a>
            </span>;

            mainRows = 
            <RevieweesViewPage changeReviewEmp = {this.changeReviewEmp} changeRevType = {this.revType} revType = {this.state.revType} changeDepartment = {this.changeDepartment} dep = {this.state.dep} finalReviewees = {this.state.reviewEmployees} changeStep = {this.changeStep} changeEmpCount = {this.changeEmpCount} empCount = {this.state.empCount}/>;
        }
        //
        if(this.state.currentStep === 2 ){
            headingRows = 
            <span className="pull-right">
                <a onClick={() => {
                    this.setState({ currentStep: 1 })
                } } className="dashboard-link-btn2">
                    <i className="fa fa-arrow-left"></i>&nbsp; Back To Reviewees
                </a>
            </span>;

            mainRows = 
            <AddQuestionSection changeQuestions = {this.changeQuestions} finalQuestions = {this.state.questions} changeStep = {this.changeStep} changeQueCount = {this.changeQueCount} queCount = {this.state.questionCount}/>;
        }
        //
        if(this.state.currentStep === 3 ){
            headingRows = 
            <span className="pull-right">
                <a onClick={() => {
                    this.setState({ currentStep: 2 })
                }} className="dashboard-link-btn2">
                    <i className="fa fa-arrow-left"></i>&nbsp; Back To Questions
                </a>
            </span>

            mainRows = 
            <ReviewersViewPage saveTemplate = {this.saveReviewEmp} selfStatus = {this.state.selfRev} changeSelfReview = {this.changeSelfReview} allReviewees = {this.state.reviewEmployees} changeStep = {this.changeStep} empCount = {this.state.empCount}/>;
        }
        return (
            <div>
                <div className="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <div className="hr-box">
                        <div className="hr-box-header bg-header-green" style={{paddingTop: 11, paddingBottom: 11}}>
                            <span className="pull-left">
                                <h1 className="hr-registered">Performance Review Templates</h1>
                            </span>
                            {headingRows}
                        </div>
                        <div className="hr-innerpadding">
                            <div className="row">
                                <div className="col-xs-12">
                                    {mainRows}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}