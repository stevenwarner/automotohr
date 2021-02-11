class ReviewTemplateViewPage extends React.Component{
    constructor(props){
        super(props);
    }

    componentDidMount(){
        console.log(this.props.templates);
    }

    render(){
        //
        let rows = '';
        let templates = this.props.templates;
        if(this.props.templates.length === 0){
            rows = 
            <tr>
                <td colSpan="6">
                    <p className="alert alert-info text-center">No templates found</p>
                </td>
            </tr>;
        }else{
            // let statusHandler = this.props.
            rows = Object.keys(templates).map((index) => {
                templates[index].is_default = parseInt(templates[index].is_default);
                return(
                    <tr key={index}>
                        <td>{templates[index].title}</td>
                        <td>{templates[index].full_name}</td>
                        <td className={templates[index].status == 1 ? 'text-success' : 'text-danger'}>{templates[index].status == 1 ? 'Active' : 'In-Active'}</td>
                        <td>{templates[index].is_default == 1 ? 'Default' : 'Custom'}</td>
                        <td>{templates[index].created_at}</td>
                        <td>
                            <NavLink 
                                to={templates[index].is_default === 0 ? `${base_url}/performance/review/edit/${templates[index].id}` : '#'} 
                                className={["btn", "btn-warning", templates[index].is_default === 1  ? 'disabled' : '' ].join(' ')}
                                title="Edit the template">
                                <i className="fa fa-pencil"></i>
                            </NavLink>
                            &nbsp;
                            <button 
                            onClick={templates[index].is_default === 0 ? this.props.statusHandler.bind(this, templates[index].id, templates[index].status, undefined) : this.props.blund }  
                            className={"btn btn-success "+(templates[index].is_default === 1 ? 'disabled' : '' )}
                            title="Activate/deActivate template">
                                <i className="fa fa-shield"></i>
                            </button>
                            &nbsp;
                            <button onClick={templates[index].is_default === 0 ? this.props.statusHandler.bind(this, templates[index].id, templates[index].status, undefined): this.props.blund } 
                            className={"btn btn-default "+(templates[index].is_default === 1 ? 'disabled' : '' )}
                            title="Archive">
                                <i className="fa fa-file"></i>
                            </button>
                        </td>
                    </tr>
                )}
            )
        }
        
        return (
            <div>
                <div className="col-lg-12 col-md-12 col-xs-12 col-sm-12">
                    <ReviewTemplateViewPageHeader />
                    <ReviewTemplateViewPageSearchFilter />
                    <br />

                    <div className="hr-box">
                        <div className="hr-box-header bg-header-green" style={{paddingTop: 11, paddingBottom: 11}}>
                            <span className="pull-left">
                                <h1 className="hr-registered">Performance Review Templates</h1>
                            </span>
                            <span className="pull-right">
                                <Link to={`${base_url}/performance/review/add`} className="dashboard-link-btn2 js-add-btn">
                                    <i className="fa fa-plus"></i>&nbsp; Add Review Template
                                </Link>
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
                                                    <th>Created By</th>
                                                    <th>Status</th>
                                                    <th>Type</th>
                                                    <th>Created At</th>
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
                </div>
            </div>
        );
    }
}