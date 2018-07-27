import React from 'react'
import {
  Row,
  Col,
  Grid
} from '@sketchpixy/rubix'

export default class HBox extends React.Component {
	shouldComponentUpdate() {
		//avoid unnecessary render
    	return false
  	}
	render() {
		var props = {...this.props}
		var children = React.Children.map(props.children, (child) => {
			return <Row>{child}</Row>
		});
		return (<Grid><Col>{children}</Col></Grid>)
	}
}