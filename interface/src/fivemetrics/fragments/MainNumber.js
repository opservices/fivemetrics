import React from 'react'
import classNames from 'classnames'

export default class MainNumber extends React.Component {
  constructor(props) {
    super(props)
  }
  render() {
    return (
    	<div className="main-number-wrapper" ref="base">
    		<h5 className="main-label">{this.props.label}</h5>
        <h4 className={classNames('main-number', this.props.value === "-" ? 'component-no-data' : '')}>{this.props.value}</h4>
    	</div>
    );
  }
}
