import React from 'react';
import ReactDOM from 'react-dom';

export default class SecondaryNumber extends React.Component {
  constructor(props) {
    super(props)
  }
  render() {
    let classnames = (isNaN(this.props.value)) ? "secondary-number" : "secondary-number"
    return (
    	<div className="secondary-number-wrapper">
  		  <div>
          <span className="secondary-unit">{this.props.unit}</span>
  		    <span className={classnames}>{this.props.value}</span>
        </div>
    	</div>
    );
  }
}