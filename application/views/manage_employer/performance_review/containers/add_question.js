class AddQuestionSection extends React.Component{
	constructor(props){
		super(props);
		this.addQuestionClicked = this.addQuestionClicked.bind(this);
		this.cancelHandler = this.cancelHandler.bind(this);
		this.saveHandler = this.saveHandler.bind(this);
		this.onEditClick = this.onEditClick.bind(this);
		this.onShiftUp = this.onShiftUp.bind(this);
		this.onShiftDown = this.onShiftDown.bind(this);
		this.editCallBack = this.editCallBack.bind(this);
		this.deleteHandler = this.deleteHandler.bind(this);
		this.onNextHandler = this.onNextHandler.bind(this);
		this.state = {
			questionsArray: [],
			questionCount: 0,
			viewState: 1,
			editFlag: false,
			editIndex: -1
		};
		
	}

	tempStruct =  {
		qno: this.props.qNo,
		question: '',
		description: '',
		responseType: 'text',
		scale: 5,
		options: {
			labels: 0,
			n_a: 0,
		},
		rating: {
		}
	};

	
	componentDidMount(){
		// console.log(this.props.finalQuestions);
		this.setState({questionsArray : this.props.finalQuestions, questionCount : this.props.queCount})
	}

	ratingScale(){
		return (
			<div>
				<div className="col-lg-3">
					<label>Response Scale</label>
				</div>
				<div className="col-lg-9">
					<div className="form-group">
						<select className="form-control">
							<option value={1}>1</option>
							<option value={2}>2</option>
							<option value={3}>3</option>
							<option value={4}>4</option>
							<option value={5} defaultChecked>5</option>
						</select>
					</div>
				</div>
			</div>
			)
	}

	addQuestionClicked(){
		this.setState({questionCount: (this.state.questionCount+1), viewState: 2});
	}
	
	cancelHandler(){
		if(!this.state.editFlag){
			this.setState({viewState:1, editFlag:false, editIndex:-1, questionCount: this.state.questionCount-1});
		}else{
			this.setState({viewState:1, editFlag:false, editIndex:-1});
		}
	}

	saveHandler(newQuestion){
		let allQuestionsTemp = this.state.questionsArray;
		allQuestionsTemp[this.state.questionCount-1] = newQuestion;
		this.setState({questionsArray: allQuestionsTemp,viewState: 1});
	}

	onEditClick(e){
		let indexVal = e.currentTarget.dataset.attr;
		this.tempStruct = this.state.questionsArray[indexVal];
		this.setState({viewState:2, editFlag:true, editIndex:indexVal});
	}

	onShiftUp(e){
		let indexVal = e.currentTarget.dataset.attr;
		let firstFlag = e.currentTarget.dataset.count;
		if(firstFlag == 1){
			console.log('First Element');
		}else{
			let allQuestionsTemp = this.state.questionsArray;
			let tempObj = this.state.questionsArray[indexVal];
			allQuestionsTemp[indexVal] = allQuestionsTemp[indexVal-1];
			allQuestionsTemp[indexVal-1] = tempObj;
			
			this.setState({questionsArray: allQuestionsTemp});
		}
	}

	onShiftDown(e){
		let indexVal = e.currentTarget.dataset.attr;
		let lastFlag = e.currentTarget.dataset.lastflag;
		if(lastFlag == 'false'){
			let allQuestionsTemp = this.state.questionsArray;
			let tempObj = this.state.questionsArray[indexVal];
			allQuestionsTemp[indexVal] = allQuestionsTemp[(++indexVal)];
			allQuestionsTemp[(indexVal)] = tempObj;
			this.setState({questionsArray: allQuestionsTemp});
		}
	}

	editCallBack(updatedValue){
		let allQuestionsTemp = this.state.questionsArray;
		allQuestionsTemp[this.state.editIndex] = updatedValue;
		this.setState({questionsArray: allQuestionsTemp, viewState:1, editFlag:false, editIndex:-1});
	}

	deleteHandler(e){
		let indexVal = e.currentTarget.dataset.attr;
		let allQuestionsTemp = this.state.questionsArray;
		allQuestionsTemp.splice(indexVal,1);
		let _this = this;
		alertify.confirm('Delete Question','Are you sure you want to delete this question ?',function(){
			_this.setState({questionsArray: allQuestionsTemp, questionCount: (_this.state.questionCount-1)});
		},function() {
			
		});
	}

	generateListView(){
		let questionList = [];
		let radioBoxes;
		let first_flag = 1;
		this.state.questionsArray.map((question, index) => {
			radioBoxes = [];
			questionList.push(
				<div key={index} className="row">
					<div className="col-lg-12">
						<label>QUESTION {index+1}</label>
						<div className="pull-right">
							<div>
								<a onClick={this.onShiftUp} data-count={first_flag} data-attr={index}><i className="fa fa-arrow-up"></i></a>
								<a onClick={this.onShiftDown} data-lastflag={first_flag++ == this.state.questionsArray.length} data-attr={index}><i className="fa fa-arrow-down"></i></a>
								<a onClick={this.onEditClick} data-attr={index}><i className="fa fa-pencil"></i></a>
								<a onClick={this.deleteHandler} data-attr={index}><i className="fa fa-trash"></i></a>
							</div>
						</div>
					</div>
					
					<div className="col-lg-12">
						<label>{question.question}</label>
					</div>
					{
						question.options.n_a ? (<div className="col-lg-12"><label>Not Applicable</label></div>) : ''
					}
					
					{
						question.responseType.indexOf('rating') != -1 ? (<div className="col-lg-12">
								<div className="btn-radio-group">
									{
										(Object.keys(question.rating).map(option => {
											radioBoxes.push(<label key={option} className="control control--radio">                                       
												<input type="radio" className="video_source" />
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
						question.responseType.indexOf('text') != -1 ? (<div className="col-lg-12"><textarea className="form-control" placeholder="Reviewer's Comment"></textarea></div>) : ''
					}
					
					<hr></hr>

				</div>
			)
		});
		return questionList;
	}

	onNextHandler(e) {
        if(this.state.questionCount){
            this.props.changeQuestions(this.state.questionsArray);
            this.props.changeQueCount(this.state.questionCount);
            this.props.changeStep(3);
        }
    }

	//
	render(){

		var view = '';
		var nextBtn = '';

		if(this.state.viewState == 1){
			let questionnaireList = [];
			if(this.state.questionCount > 0){
				questionnaireList = this.generateListView();
				nextBtn =   <div className="form-group">
								<a onClick={ this.onNextHandler} className="btn btn-success" > Next </a> &nbsp;
								<a onClick={() => this.props.changeStep(1)} className="btn btn-default"> Back </a>
							</div>
			}
			view = (<div>
						<div className="row">
							<div className="col-sm-12">
								<button onClick={this.addQuestionClicked} className="btn btn-success pull-right">
									<i className="fa fa-plus"></i>&nbsp; Add Question
								</button>
							</div>
						</div>
						<div className="row">
							<div className="col-sm-12">
								<div className="form-group">
									<label>Add Custom Questions</label>
									<span className="pull-right"></span>
								</div>
								<hr />
								{
									questionnaireList
								}
							</div>
						</div>
						<div className="row">
							<div className="col-sm-12">
								{nextBtn}
							</div>
						</div>
					</div>
				)
		}else if (this.state.viewState == 2){
			view = <QuestionHtml qNo={this.state.questionCount} onCancelHandler={this.cancelHandler} onSaveHandler={this.saveHandler} editCallBack={this.editCallBack} editFlag={this.state.editFlag} editObject={this.tempStruct}/> 
		}

		return(
			<div>
				{view}
			</div>
		);
	}
}

class QuestionHtml extends React.Component{
	constructor(props){
		super(props);
		this.responseChangeHandler = this.responseChangeHandler.bind(this);
		this.ratingChangeHandler = this.ratingChangeHandler.bind(this);
		this.labelChangeHandler = this.labelChangeHandler.bind(this);
		this.ratingIncludeHandler = this.ratingIncludeHandler.bind(this);
		this.saveRatingOptionsValue = this.saveRatingOptionsValue.bind(this);
		this.questionChange = this.questionChange.bind(this);
		this.descriptionChange = this.descriptionChange.bind(this);
		this.onSaveHandler = this.onSaveHandler.bind(this);
		this.onEditHandler = this.onEditHandler.bind(this);
		
	}
	state = {
		questionStruct: {
			qno: this.props.qNo,
			question: '',
			description: '',
			responseType: 'text',
			scale: 5,
			options: {
				labels: 0,
				n_a: 0,
			},
			rating: {
			}
		},
		ratingBox: false,
		useLabel: false
	}

	ratingBoxesValues = ['Unacceptable','Need Improvement','Meets Expectations','Exceeds Expectations','Outstanding'];
	
	componentDidMount(){
		if(this.props.editFlag){
			if(this.props.editObject.responseType != 'text'){
				this.setState({ratingBox : true})
			}
			this.setState({questionStruct : this.props.editObject, useLabel : this.props.editObject.options.labels})
		}
	}

	responseChangeHandler(e){
		let resVal = e.target.value;
		if(resVal == 'text'){
			this.setState({ratingBox: false});
			this.setState({useLabel: false});
			this.setState(prevState => {
				let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
				questionStruct.responseType = resVal;                                      
				questionStruct.options['labels'] = false;     
				return { questionStruct };
			});
		}else{
			this.setState({ratingBox: true});
			this.setState(prevState => {
				let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
				questionStruct.rating = {};   
			    return { questionStruct };
			});
			this.setState(prevState => {
				let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
				questionStruct.responseType = resVal;  
				for (let index = 1; index <= questionStruct.scale; index++) {
					questionStruct.rating[index] = this.state.useLabel ? this.ratingBoxesValues[(index-1)] : '';   
				}
				return { questionStruct };
			});
		}
	}

	ratingChangeHandler(e){
		let rating = e.target.value;
		// this.setState(prevState => {
		// 	let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
		// 	questionStruct.rating = {};   
		// 	return { questionStruct };
		// });
		this.setState(prevState => {
			let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
			if(!this.state.useLabel){
				questionStruct.rating = {};
			}
			for (let index = 1; index <= rating; index++) {
				if(this.state.useLabel){
					if(questionStruct.rating[index] == '' || questionStruct.rating[index] == undefined || index > questionStruct.scale)
						questionStruct.rating[index] = this.ratingBoxesValues[(index-1)];   
				}else{
					questionStruct.rating[index] = '';   
				}
			} 
			questionStruct.scale = rating;  
			return { questionStruct };
		});
	}

	ratingIncludeHandler(e){
		let includeValue = e.target.checked;
		this.setState(prevState => {
			let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
			questionStruct.options['n_a'] = includeValue;                                      
			return { questionStruct };
		});
	}

	labelChangeHandler(e){
		let labelValue = e.target.checked;
		this.setState({useLabel: labelValue});

		this.setState(prevState => {
			let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
			questionStruct.rating = {};   
			return { questionStruct };
		});
		this.setState(prevState => {
			let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
			questionStruct.options['labels'] = labelValue;  
			for (let index = 1; index <= this.state.questionStruct.scale; index++) {
				questionStruct.rating[index] = labelValue ? this.ratingBoxesValues[(index-1)] : '';   
			} 

			return { questionStruct };
		});
		
		
	}

	saveRatingOptionsValue(e){
		let index = e.target.getAttribute('data-attr');
		let value = e.target.value;
		this.setState(prevState => {
			let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
			questionStruct.rating[index] = value;                                      
			return { questionStruct };
		});
		
	}

	questionChange(e){
		let que = e.target.value;
		this.setState(prevState => {
			let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
			questionStruct.question = que;                                      
			return { questionStruct };
		});
	}

	descriptionChange(e){
		let desc = e.target.value;
		this.setState(prevState => {
			let questionStruct = Object.assign({}, prevState.questionStruct);  // creating copy of state variable
			questionStruct.description = desc;                                      
			return { questionStruct };
		});
	}

	generateRatingLabels(){
		let ratingBoxes = [];
		for (let index = 1; index <= this.state.questionStruct.scale; index++) {
			ratingBoxes.push(
				<div className="form-group" key={index}>
					<label>Rating {index}</label>
					<input key={index} data-attr={index} className="form-control" value={this.state.questionStruct.rating[index]} onChange={this.saveRatingOptionsValue} />
				</div>				
			)
		}		
		return ratingBoxes;
	}

	onSaveHandler(){
		let questionText = this.state.questionStruct.question;
		if(questionText == ''){
			alertify.alert('Question Title is required');
			return false;
		}else{
			this.props.onSaveHandler(this.state.questionStruct);
		}
	}

	onEditHandler(){
		this.props.editCallBack(this.state.questionStruct)
	}

	render(){
		var rSelect = '';
		var rCheckBox = '';
		var labelsDiv = [];
		var ratings = new Array(1,2,3,4,5);
		if(this.state.ratingBox){
			rSelect = 
			<div>
				<div className="col-lg-3">
					<label>Rating Scale</label>
				</div>
				<div className="col-lg-9">
					<div className="form-group">
						<select className="form-control" onChange={this.ratingChangeHandler} defaultValue={5}>
							{
								ratings.map(index => {
									return <option key={index} value={index}>{index}</option>
								})
							}
						</select>
					</div>
				</div>
			</div>
			rCheckBox = <li className="list-group-item"><input type="checkbox" defaultChecked={(this.state.questionStruct.options.labels) ? true : false} onChange={this.labelChangeHandler}></input> Use Labels</li>
		}

		if(this.state.useLabel){
			labelsDiv = 
			<div>
				<div className="col-lg-3">
					<label>Ratings</label>
				</div>
				<div className="col-lg-9">
					{
						this.generateRatingLabels()
					}
				</div>
			</div>

		}

		return(
			<div>
				<div className="col-sm-12">
					<div className="form-group">
						<label>Question {this.props.qNo}</label>
						<span className="pull-right"></span>
					</div>
					
				</div>
				<div className="col-lg-3">
					<label>Question <span className="cs-required">*</span></label>
				</div>
				<div className="col-lg-9">
					<div className="form-group">
						<input className="form-control" value={this.state.questionStruct.question} placeholder="Write Question" onChange={this.questionChange}></input>
					</div>
				</div>
				<div className="col-lg-3">
					<label>Description</label>
				</div>
				<div className="col-lg-9">
					<div className="form-group">
						<textarea className="form-control" value={this.state.questionStruct.description} placeholder="Write Description" onChange={this.descriptionChange}></textarea>
					</div>
				</div>
				<hr />
	
				<div className="col-lg-3">
					<label>Response Type</label>
				</div>
				<div className="col-lg-9">
					<div className="form-group">
						<select className="form-control" value={this.state.questionStruct.responseType} onChange={this.responseChangeHandler}>
							<option value="text">Text Box</option>
							<option value="rating">Rating Scale</option>
							<option value="text-rating">Rating Scale and Text Box</option>
						</select>
					</div>
				</div>
				{rSelect}
				<div className="col-lg-3">
					<label>Options</label>
				</div>
				<div className="col-lg-9">
					<div className="form-group">
						<ul className="list-group">
							{rCheckBox}
							<li className="list-group-item"><input type="checkbox" defaultChecked={(this.state.questionStruct.options.n_a) ? true : false} onChange={this.ratingIncludeHandler}></input> Include N/A</li>
						</ul>
					</div>
				</div>
				{labelsDiv}
				<hr />

				<div className="form-group">
					<a onClick={ !this.props.editFlag ? this.onSaveHandler : this.onEditHandler} className="btn btn-success"> { !this.props.editFlag ? 'Save' : 'Update'} </a> &nbsp;
					<a onClick={() => this.props.onCancelHandler()} className="btn btn-default"> Cancel </a>
				</div>
			</div>
	
			)
	}
}