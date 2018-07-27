import React from 'react'
import classNames from 'classnames'

export default class MainIcon extends React.Component {
  constructor(props) {
    super(props)
  }
  render() {
    let style = Object.assign({},{backgroundImage: `url(/imgs/${this.props.icon}.svg)`},{ width: this.props.width || 'inherit',  height: this.props.height || 'inherit'})
    return (
    	<div className={classNames("main-icon-wrapper",this.props.className)} style={this.props.style}>
        <div className="header-icon" style={style}></div>
    	</div>
    );
  }
}
