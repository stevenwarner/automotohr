	<div id="js-app"></div>

	 <!-- Load React. -->
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/react/16.12.0/umd/react.development.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/react-dom/16.11.0/umd/react-dom.development.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/react-router-dom/5.0.1/react-router-dom.min.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/react-bootstrap/0.33.1/react-bootstrap.min.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/6.26.0/babel.min.js"></script>
  	<!-- <script src="https://unpkg.com/react@16/umd/react.development.js" crossorigin></script>
  	<script src="https://unpkg.com/react-dom@16/umd/react-dom.development.js" crossorigin></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/react-router-dom/5.0.1/react-router-dom.min.js"></script>
  	<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.19.0/axios.min.js"></script>
  	<script src="https://unpkg.com/react-bootstrap@next/dist/react-bootstrap.min.js" crossorigin></script>
  	<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script> -->
	
	<script>
  		var 
  		prefix = '/ahr/',
  		Router = ReactRouterDOM.BrowserRouter,
  		Route = ReactRouterDOM.Route,
  		Bootstrap = ReactBootstrap,
  		Switch = ReactRouterDOM.Switch,
  		NavLink = ReactRouterDOM.NavLink,
  		Link = ReactRouterDOM.Link,
  		base_url = window.location.origin == "http://localhost" ? '/ahr' : '';
	</script>

	<script type="text/babel">
		

		// Sidebar Start
		<?php $this->load->view('manage_employer/performance_review/containers/sidebar.js')?>
		// Sidebar End

		// ContentArea Start
		class ContentArea extends React.Component{
			constructor(props){
				super(props);
				this.state = {
					templates: [],
					reviews: [],
					loaderText: 'Please wait while we generate a preview...',
					showLoader: false
				};
				// Binders
				this.loaderToggle = this.loaderToggle.bind(this);
				this.hideLoader = this.hideLoader.bind(this);
				this.showLoader = this.showLoader.bind(this);
				this.statusHandler = this.statusHandler.bind(this);
				this.setLoaderText = this.setLoaderText.bind(this);
				this.setTemplates = this.setTemplates.bind(this);
				this.strCnclReview = this.strCnclReview.bind(this);
			}

			async componentDidMount(){
				//Templates
				await axios.post(`${base_url}/performance/handler`, JSON.stringify({
					action: 'fetch_templates'
				}), {
					headers: {'X-Requested-With':'XMLHttpRequest'}
				})
				.then((resp) => {
					if(resp.data.Data != null && resp.data.Data != undefined)
					this.setTemplates(resp.data.Data);
					this.loaderToggle();
				})
				.catch((err) => {
					console.log(err)
				});
				
				//Reviews
				await axios.post(`${base_url}/performance/handler`, JSON.stringify({
					action: 'fetch_reviews'
				}), {
					headers: {'X-Requested-With':'XMLHttpRequest'}
				})
				.then((resp) => {
					if(resp.data.Data != null && resp.data.Data != undefined)
					this.setState({reviews: resp.data.Data});
				})
				.catch((err) => {
					console.log(err)
				});
				
				
				this.loaderToggle();
			}

			// Toggle loader
			loaderToggle(){
				this.setState((oldState) =>  ({ showLoader: !oldState.showLoader }));
			}

			// Show loader
			showLoader(){
				this.setState({ showLoader: true });
			}

			// Hide loader
			hideLoader(){
				this.setState({ showLoader: false });
			}

			setLoaderText(text){
				this.setState({
					loaderText: text
				});
			}

			//
			setTemplates(templates){
				this.setState((oldState) => { 
					if(oldState.templates.length == templates.length) return;
					return {templates: templates};
				});
			}

			// Save Review
			saveReviewAndAddQuestions(e){
				e.preventDefault();
			}
			//
			saveReview(e){
				console.log(e);
			}

			//UCwords
			ucwords(str){
				str = str.toLowerCase().replace(/\b[a-z]/g, function(letter) {
					return letter.toUpperCase();
				});
				return str;
			}

			blund(e){
				e.preventDefault();
			}

			// Template Status Changer
			async statusHandler(templateId, status, confirmIt){
				if(confirmIt === undefined){
					let message = status.toLowerCase() == 'active' ? 'de-activate' : 'activate';
					alertify.confirm(`Do you really want to ${message} this template?`, this.statusHandler.bind(this, templateId, status, false)).set('labels', {'ok': 'Yes', 'cancel': 'No'});
					return;
				}
				this.setLoaderText('Please wait, while we are changing status.');
				this.loaderToggle();

				
				// Create post object
				let megaOBJ = { action: 'change_template_status', templateId: templateId, status: status.toLowerCase() == 'active' ? 'inactive' : 'active' };
				// Make AJAX request6
				let ajax = await axios.post(
					`${base_url}/performance/handler`,  
					JSON.stringify(megaOBJ), {
						headers: {'X-Requested-With':'XMLHttpRequest'}
					});
				this.setLoaderText('');
				this.loaderToggle();
			}

			strCnclReview(index, status){
				this.setState(prevState => {
					let prev = Object.assign({}, prevState.reviews);  // creating copy of state variable
					prev[index].status = status;  
					return { prev };
				});
			}

			render(){
				let loader = '';
				if(this.state.showLoader == true){
					loader = 
					<div className="text-center my_loader">
	                    <div id="file_loader" className="file_loader" style={{display:"block", height: "1353px"}}></div>
	                    <div className="loader-icon-box">
	                        <i className="fa fa-refresh fa-spin my_spinner" style={{visibility : "visible"}}></i>
	                        <div className="loader-text" style={{display : "block", marginTop: "35px"}}>{this.state.loaderText}</div>
	                    </div>
	                </div>;
				}

				return (
					<div>
						{loader}
						<Route path={`${prefix}performance/review/add`} 
								component={
									() => { return ( <ReviewTemplateAddPage 
											templates={this.state.templates}
										/> )}
						} />
						<Route path={`${prefix}performance/review/view`} component={
							() => { return ( <ReviewTemplateViewPage 
										templates={this.state.templates}
										statusHandler={this.statusHandler}
										blund={this.blund}
										ucwords={this.ucwords}
									/> )}
						} />
						<Route path={`${prefix}performance/review/allReviews`} component={
							() => { return ( <ReviewViewPage 
										reviews={this.state.reviews}
										strCnclReview={this.strCnclReview}
										blund={this.blund}
										ucwords={this.ucwords}
									/> )}
						} />
						<Route path={`${prefix}performance/review/assignedReviews`} component={
							() => { return ( <AssignedReviewPage ucwords={this.ucwords}/> )}
						} />
					</div>
				);
			}
		}
		// ContentArea End

		// Review Template Add Start
		<?php $this->load->view('manage_employer/performance_review/containers/add_template.js')?>
			
			// Review Add Question Section Start
			<?php $this->load->view('manage_employer/performance_review/containers/add_question.js')?>
			// Review Add Question Section End
			
			// Reviewees Add Section Start
			<?php $this->load->view('manage_employer/performance_review/containers/reviewees.js')?>
			// Reviewees Add Section End

		// Review Template Add End

		// Review Template View Start
		<?php $this->load->view('manage_employer/performance_review/containers/view_template.js')?>

		// Review View Start
		<?php $this->load->view('manage_employer/performance_review/containers/view_reviews.js')?>

		// Assigned Review View Start
		<?php $this->load->view('manage_employer/performance_review/containers/assigned_reviews.js')?>

		// Reviewers View Start
		<?php $this->load->view('manage_employer/performance_review/containers/reviewers.js')?>

		// Select2 Component
		<?php $this->load->view('manage_employer/performance_review/containers/select2.js')?>

		// Review Template Page Header Start
		const ReviewTemplateViewPageHeader = () => (
			<div className="page-header-area">
				<span className="page-heading down-arrow">
					<a className="dashboard-link-btn" href={`${base_url}/dashboard`}>
						<i className="fa fa-chevron-left"></i>Dashboard
					</a>Performance Review Template
				</span>
			</div>
		)
		// Review Template Page Header End

		const ReviewTemplateViewPageSearchFilter = () => (
			<div className="hr-search-main" style={{display: 'block'}}>
				<form method="GET" action="#" id="js-search-filter">
					<div className="row">
						
						<div className="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<div className="field-row">
								<label className="">Ttile</label>
								<input className="invoice-fields" id="js-employee-select" />
							</div>
						</div>
						<div className="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<div className="field-row">
								<label className="">Status</label>
								<select className="invoice-fields" id="js-status" defaultValue="-1">
									<option value="-1">All</option>
									<option value="1">Active</option>
									<option value="0">In-Active</option>
								</select>
							</div>
						</div>
						<div className="col-xs-12 col-sm-2 col-md-2 col-lg-2">
							<div className="field-row">
								<label className="">&nbsp;</label>
								<a className="btn btn-success btn-block js-apply-filter-btn" href="#" >Apply Filters</a>
							</div>
						</div>
						<div className="col-xs-12 col-sm-2 col-md-2 col-lg-2">
							<div className="field-row">
								<label className="">&nbsp;</label>
								<a className="btn btn-success btn-block js-reset-filter" href="#">Reset Filters</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		)
		// Review Template View End

		// 404 Add Start
		<?php $this->load->view('manage_employer/performance_review/containers/404.js')?>
		// 404 Add End
		
		class App extends React.Component{
			constructor(props){
				super(props);
				this.state = {
					QuantionnaireTypes: []

				};
			}
			componentDidMount(){
				console.log('should mount last');
			}

			render(){
				return (
					<Router>
						<div className="main-content">
							<div className="dashboard-wrp">
								<div className="container-fluid">
									<div className="row">
										<div className="col-lg-3 col-md-3 col-xs-12 col-sm-4">
											<Sidebar />
										</div>
										<div className="col-lg-9 col-md-9 col-xs-12 col-sm-8">
											<div className= "row">
												<ContentArea />
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</Router>
				);
			}
		}

		ReactDOM.render(<App />, document.getElementById('js-app'));
	</script>


	<style>
		.hr-search-main {
			float: left;
			width: 100%;
			display: none;
			margin: 0 0 30px 0;
			background-color: #fafafa;
			border: 1px solid #ccc;
			padding: 8px;
			border-radius: 5px;
		}
		.select2-container--default .select2-selection--single{
			background-color: #eeeeee !important;
			border: 1px solid #aaaaaa !important;
		}
		.select2-container .select2-selection--single .select2-selection__rendered{
			padding-left: 8px !important;
			padding-right: 20px !important;
		}
		.dashboard-link-btn2 {
			background-color: #518401;
			border-radius: 5px;
			color: #fff !important;
			font-size: 14px;
			padding: 7px 15px;
			text-transform: uppercase;
		}
		.cs-required,
		.cs-error{
			font-weight: bolder; color: #cc0000;
		}
		.cs-checkbox .checkbox:before{
			height: auto;
			width: 0 !important;
		}
		.cs-checkbox input{
			width: 30px;
			height: 30px;
		}
		.cs-checkbox span{
			font-size: 16px;
			line-height: 35px;
			margin-left: 25px;
		}
		.cs-loader{ position: fixed; top: 0; bottom: 0; left: 0; right: 0; width: 100%; z-index: 1; background: rgba(0,0,0,.5);}
		.cs-loader-box{ position: absolute; top: 50%; bottom: 0; left: 0; right: 0; width: 300px; margin: auto; margin-top: -190px;}
		.cs-loader-box i{ font-size: 14em; color: #81b431; }
		.cs-loader-box div.cs-loader-text{ display: block; padding: 10px; color: #000; background-color: #fff; border-radius: 5px; text-align: center; font-weight: 600; margin-top: 35px; }
		.cs-calendar{ margin-top: 10px; }
		/**/
		.ajs-button{ background-color: #81b431 !important; color: #ffffff !important; padding-left: 5px !important; padding-right: 5px !important; border-radius: 4px; -webkit-border-radius: 4px; -moz-border-radius: 4px; -o-border-radius: 4px; border-color: #4cae4c !important; }
		.ajs-header{ background-color: #81b431 !important; color: #ffffff !important; }
		/*Pagination*/
		.cs-pagination{ float: right; }
		.cs-pagination li a{ background-color: #81b431; color: #ffffff; }
	</style>