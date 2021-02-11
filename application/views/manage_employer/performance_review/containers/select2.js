class Select2 extends React.Component{
    constructor(props){
        super(props);
        this.handleChange = this.handleChange.bind(this);
        this.state = {
            options: this.props.options,
            default: [],
            multipleCheck: false,
            index: this.props.index,
            value: this.props.value
        }
    }

    componentDidMount(){
        if(this.props.defaultVal != undefined) this.setState({default:this.props.defaultVal});
        // console.log('select',this.state.default);
        if(this.props.multipleCheck != undefined && this.props.multipleCheck) this.setState({multipleCheck: true});
        this.$el = $(this.el);
        this.$el.select2();
        this.$el.on('change', this.handleChange);
    }

    componentDidUpdate(prevProps) {
      if (prevProps.options !== this.props.options) {
        this.$el.trigger("select2:updated");
      }
    }

    handleChange(e) {
        this.props.changeHandler(this.vals(e.target.selectedOptions));
    }

    vals(arr){
        let t = [];
        if(arr.length == 0) return t;
        let i = 0,
        l = arr.length;
        for(i; i < l; i++){
            t.push({
                value: arr[i].value,
                text: arr[i].text
            });
        }
        return t;
    }

    render(){
        var _this = this;
        return (
            <div className="conatiner">
                <div className="row">
                    <div className="col-sm-12">
                        <select className="js-selecty" data-attr={(this.props.attribute != undefined) ? this.props.attribute : ''} multiple={(this.props.multipleCheck != undefined && this.props.multipleCheck) ? true : false} ref={el => this.el = el} default = {this.state.default}>
                            {
                                (this.props.pleaseSelect != undefined && this.props.pleaseSelect ? (<option>Select Option</option>) : '')

                            }
                            {
                                Object.keys(this.state.options).map(function (k) {
                                    return <option key={_this.state.options[k][_this.state.index]} value={_this.state.options[k][_this.state.index]}>{_this.state.options[k][_this.state.value]}</option>
                                })
                            }
                        </select>
                    </div>
                </div>
            </div>
        );
    }
}