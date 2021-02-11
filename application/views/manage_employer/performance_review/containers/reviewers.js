class ReviewersViewPage extends React.Component {
    constructor(props) {
        super(props);
        this.onNextHandler = this.onNextHandler.bind(this);
        this.addSelf = this.addSelf.bind(this);
        this.addReviewers  = this.addReviewers.bind(this);
        this.state = {
            allEmployees: [],
            self: this.props.selfStatus,
            reviewees: this.props.allReviewees
        }
    }

    componentWillMount() {
        //Fetch All Employees
        axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'all_employees'
        }), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then((resp) => {
            if (resp.data.Data != null) {
                this.setState({ allEmployees: resp.data.Data });
            }
        })
        .catch((err) => {
            console.log(err)
        });
        
    }

    onNextHandler(e) {
        this.props.saveTemplate(this.state.reviewees);
        this.props.changeSelfReview(this.state.self);
        
    }

    addSelf(e) {
        let _this = this;
        if(e.target.checked){
            _this.setState({self: 'Self'});
            for (const key in this.state.reviewees) {
                if (this.state.reviewees.hasOwnProperty(key)) {
                    this.setState(prevState => {
                        let prevIndexVal = Object.assign({}, prevState.reviewees);  // creating copy of state variable
                        prevIndexVal[key].self = true;  
                        if(prevIndexVal[key].reviewer !== undefined){
                            prevIndexVal[key].reviewer[key] = prevIndexVal[key].full_name;                                    
                        }else{
                            prevIndexVal[key].reviewer = {}; 
                            prevIndexVal[key].reviewer[key] = prevIndexVal[key].full_name; 
                        }
                        return { prevIndexVal };
                    });
                }
            }
        }else{
            _this.setState({self: ''});
            for (const key in this.state.reviewees) {
                if (this.state.reviewees.hasOwnProperty(key)) {
                    this.setState(prevState => {
                        let prevIndexVal = Object.assign({}, prevState.reviewees);  // creating copy of state variable
                        prevIndexVal[key].self = false;   
                        if(prevIndexVal[key].reviewer !== undefined){
                            delete prevIndexVal[key].reviewer[key];      
                        }                             
                        return { prevIndexVal };
                    });
                }
            }
        }
    }

    addReviewers(e) {
        let index = e.currentTarget.dataset.attr;
        var options = e.target.options;
        let revObj = {};
        this.setState(prevState => {
            let prevIndexVal = Object.assign({}, prevState.reviewees);  // creating copy of state variable
            if(prevIndexVal[index].reviewer !== undefined){
                revObj = prevIndexVal[index].reviewer;
            }
            for (var i = 0, l = options.length; i < l; i++) {
                if (options[i].selected && options[i].value !== '') {
                    revObj[options[i].value] = options[i].text;
                }
            }
            prevIndexVal[index].reviewer = revObj;                  
			return { prevIndexVal };
		});
    }


    render() {
        let reviewees = [];
        for (const key in this.props.allReviewees) {
            if (this.props.allReviewees.hasOwnProperty(key)) {
                reviewees.push( 
                <div key={key}>
                    <div className="col-lg-12">
                        <div className="col-lg-3">
                            <label>{this.props.allReviewees[key].full_name}</label>
                        </div>
                        <div className="col-lg-6">
                            <select className="form-control" data-attr={key} multiple onChange={this.addReviewers}>
                                <option value="">Please Select Reviewer</option>
                                    {
                                        Object.keys(this.state.allEmployees).map(emp => {
                                            return <option key={this.state.allEmployees[emp].sid} value={this.state.allEmployees[emp].sid}>{this.state.allEmployees[emp].full_name}</option>
                                        })
                                    }
                            </select>
                            {/* <Select2 options={this.state.allEmployees} attribute={key} multiple={1} changeHandler={this.addReviewers} index = "sid" value = "full_name"></Select2> */}
                        </div>
                        <div className="col-lg-3">
                            <label>{this.state.self}</label>
                        </div>
                    </div>
                </div>)
            }
        }
        return (
            <div>
                <div className="row">
                    <div className="col-sm-12">
                        All reviews are submitted to the reporting manager
                        <div className="row">
                            <div className="col-lg-3">
                                <label>Ratings</label>
                            </div>
                            <div className="col-lg-9">
                                <input type="checkbox" defaultChecked={(this.state.self == 'self') ? true : false} onChange={this.addSelf}></input> Self-Review
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr></hr>

                <div className="row">
                    <div className="col-lg-3">
                        <label>Reviewees ({this.props.empCount})</label>
                    </div>
                    <div className="col-lg-9">
                        Assigned Reviewers
                    </div>
                </div>
                
                <hr></hr>
                <div className="row">
                    {reviewees}
                    <div className="col-lg-12">
                        <a onClick={ this.onNextHandler} className="btn btn-success" > Save </a> &nbsp;
                        <a onClick={() => this.props.changeStep(2)} className="btn btn-default"> Back </a>
                    </div>                        
                </div>
            </div>
        );
    }
}