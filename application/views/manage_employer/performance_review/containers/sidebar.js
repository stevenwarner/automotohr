
		class Sidebar extends React.Component{
			constructor(props){
				super(props);
			}
			render(){
				return (
					<div className="dashboard-menu">
						<ul>
							<li><a href={`${base_url}/dashboard`}><figure><i className="fa fa-th"></i></figure>Dashboard</a></li>
							<li><NavLink to={`${base_url}/performance/review/add`} activeClassName="active"><figure><i className="fa fa-plus"></i></figure>Create Review Template</NavLink> </li>
							<li><NavLink to={`${base_url}/performance/review/view`} activeClassName="active"><figure><i className="fa fa-sticky-note"></i></figure>View Review Templates</NavLink> </li>
							<li><NavLink to={`${base_url}/performance/review/allReviews`} activeClassName="active"><figure><i className="fa fa-sticky-note"></i></figure>Created Reviews</NavLink> </li>
							<li><NavLink to={`${base_url}/performance/review/assignedReviews`} activeClassName="active"><figure><i className="fa fa-sticky-note"></i></figure>Assigned Reviews</NavLink> </li>
						</ul>
					</div>
				);
			}
		}