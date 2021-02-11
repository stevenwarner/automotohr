class RevieweesViewPage extends React.Component {
    constructor(props) {
        super(props);
        this.getReviewees = this.getReviewees.bind(this);
        this.empChange = this.empChange.bind(this);
        this.depChange = this.depChange.bind(this);
        this.onNextHandler = this.onNextHandler.bind(this);
        this.state = {
            revEmployees: {},
            defaultRev: [],
            subordinateEmployees: [],
            empCount: 0,
            dep: 0,
            allEmployees: [],
            depEmployees: [],
            allDepartments: [],
            revType: 1,
        }
    }

    componentDidUpdate() {
        // $('.js-status').select2();
    }

    async componentWillMount() {
        this.setState({revType : this.props.revType})
        //Fetch All Employees
        await axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'all_employees'
        }), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then((resp) => {
            if (resp.data.Data != null) {
                this.setState({ allEmployees: resp.data.Data });
                if(this.props.empCount > 0){
                    this.setState({empCount : this.props.empCount, revEmployees : this.props.finalReviewees})
                }else{
                    this.setState({revEmployees: resp.data.Data, empCount: Object.keys(resp.data.Data).length});
                }
            }
        })
        .catch((err) => {
            console.log(err)
        });

        //Fetch My Subordinates
        axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'fetch_reviewees_by_department',
            dep: 'own'
        }), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then((resp) => {
            if (resp.data.Data != null) {
                this.setState({ subordinateEmployees: resp.data.Data });
            }
        })
        .catch((err) => {
            console.log(err)
        });

        //Fetch Departments
        await axios.post(`${base_url}/performance/handler`, JSON.stringify({
            action: 'fetch_all_departments'
        }), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then((resp) => {
            if (resp.data.Data != null) {
                this.setState({ allDepartments: resp.data.Data });
            }
        })
        .catch((err) => {
            console.log(err)
        });

        //Set values from main component
        if(this.props.finalReviewees.length > 0){
            this.setState({revEmployees : this.props.finalReviewees})
        }
        if(this.props.empCount > 0){
            this.setState({empCount : this.props.empCount, dep : this.props.dep})
        }
    }

    componentDidMount(){
        //Set values from main component
        if(this.props.empCount > 0){
            this.setState({empCount : this.props.empCount, revEmployees : this.props.finalReviewees})
        }
    }

    getReviewees(e) {
        let revType = e.target.value;
        if (revType == 1) {
            this.setState({
                revEmployees: this.state.allEmployees,
                empCount: Object.keys(this.state.allEmployees).length,
                revType: 1
            });
        } else if (revType == 2) {
            this.setState({
                revEmployees: this.state.subordinateEmployees,
                empCount: Object.keys(this.state.subordinateEmployees).length,
                revType: 2
            });
        } else if (revType == 3) {
            this.setState({
                revEmployees: {},
                empCount: 0,
                revType: 3
            });
        } else if (revType == 4) {
            this.setState({
                revEmployees: {},
                empCount: 0,
                revType: 4
            });
        }
        // this.loaderToggle();
    }

    empChange(selected) {
        
        let review = this.state.revEmployees ? this.state.revEmployees : {};
        let so = selected.target.selectedOptions;
        for (var i = 0; i < so.length; i++) {
            review[so[i].value] = {sid: so[i].value, full_name: so[i].text}
        }
        this.setState({empCount: Object.keys(review).length, revEmployees: review});
    }

    depChange(selected) {
        var depSid = selected.target.value;
        //Fetch Employees By Departments
        if(depSid !== ''){
            axios.post(`${base_url}/performance/handler`, JSON.stringify({
                action: 'fetch_reviewees_by_department',
                dep: depSid
            }), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            }).then((resp) => {
                if (resp.data.Data != null) {
                    this.setState({ depEmployees: resp.data.Data, dep: depSid });
                    console.log(resp.data.Data);
                    
                }
            }).catch((err) => {
                console.log(err)
            });
        }
    }

    onNextHandler(e) {
        if(this.state.empCount){
            this.props.changeReviewEmp(this.state.revEmployees);
            this.props.changeEmpCount(this.state.empCount);
            this.props.changeRevType(this.state.revType);
            this.props.changeDepartment(this.state.dep);
            console.log(this.state.empCount);
            
            this.props.changeStep(2)
        } 
        else{
            alertify.alert('Select Employee(s)');
            console.log('Select Employees');
        }
    }


    render() {
        let empSelect = '';
        let depSelect = '';
        var _this = this;
        var defaultRadioActive = this.state.revType;
        if (this.state.revType == 3 || this.state.revType == 4) {
            empSelect = <div>
                <hr></hr>
                <div className="form-group">
                    <label onClick={this.empChange}>Employees</label>
                    <select className="form-control" multiple onChange={this.empChange} placeholder="Select Employees">
                        <option value="">Please Select Employees</option>
                        {
                            Object.keys(this.state.allEmployees).map(function (k) {
                                return <option key={_this.state.allEmployees[k].sid} value={_this.state.allEmployees[k].sid}>{_this.state.allEmployees[k].full_name}</option>
                            })
                        }
                    </select>  
                    {/* <Select2 options={this.state.allEmployees} default={Object.keys(this.state.revEmployees)} multipleCheck={1} changeHandler={this.empChange} index = "sid" value = "full_name"></Select2> */}
                </div>
            </div>
            if (this.state.revType == 4)
                depSelect = <div>
                        <div className="col-sm-6">
                            <div className="form-group">
                                <label>Departments</label>
                                <select className="form-control" onChange={this.depChange}>
                                <option value="">Please Select Department</option>
                                    {
                                        this.state.allDepartments.map(dep => {
                                            return <option key={dep.sid} value={dep.sid}>{dep.name}</option>
                                        })
                                    }
                                </select>
                                {/* <Select2 options={this.state.allDepartments} pleaseSelect={1} multiple={0} changeHandler={this.depChange} index = "sid" value = "name"></Select2> */}
                            </div>
                        </div>
                        <div className="col-sm-6">
                            <div className="form-group">
                                <label>Employees</label>
                                <select className="form-control" multiple onChange={this.empChange} placeholder="Select Employees">
                                    <option value="">Please Select Employees</option>
                                    {
                                        Object.keys(this.state.depEmployees).map(function (k) {
                                            return <option key={_this.state.depEmployees[k].sid} value={_this.state.depEmployees[k].sid}>{_this.state.depEmployees[k].full_name}</option>
                                        })
                                    }
                                </select>
                                {/* <Select2 options={this.state.depEmployees} multiple={1} changeHandler={this.empChange} index = "sid" value = "full_name"></Select2> */}
                            </div>
                        </div>
                    </div>
                
        }

        return (
            <div>
                <div className="row">
                    <div className="col-sm-12">
                        <label>Choose Reviewees:</label>
                        <ul className="list-group">
                            <li className="list-group-item"><input type="radio" name="reviewees" value="1" defaultChecked = {(defaultRadioActive == 1) ? true : false} onChange={this.getReviewees}></input> Entire Company</li>
                            <li className="list-group-item"><input type="radio" name="reviewees" value="2" defaultChecked = {(defaultRadioActive == 2) ? true : false} onChange={this.getReviewees}></input> My Subordinates</li>
                            <li className="list-group-item"><input type="radio" name="reviewees" value="3" defaultChecked = {(defaultRadioActive == 3) ? true : false} onChange={this.getReviewees}></input> Specific People</li>
                            <li className="list-group-item"><input type="radio" name="reviewees" value="4" defaultChecked = {(defaultRadioActive == 4) ? true : false} onChange={this.getReviewees}></input> Custom</li>
                        </ul>
                    </div>
                </div>


                <div className="row">
                    <div className="col-sm-12">
                        {empSelect}
                    </div>
                    <div className="col-sm-12">
                        {depSelect}
                    </div>
                </div>
                
                
                <hr></hr>
                <div className="row">
                    <div className="col-sm-12">
                        <div className="form-group">
                            <label>Total Reviewees: </label> {this.state.empCount}
                            <span className="pull-right"></span>
                        </div>
                        <div className="form-group">
                            <a onClick={ this.onNextHandler} className="btn btn-success" > Next </a> &nbsp;
                            <a onClick={() => this.props.changeStep(0)} className="btn btn-default"> Back </a>
                        </div>
                        <hr />
                    </div>
                </div>
            </div>
        );
    }
}