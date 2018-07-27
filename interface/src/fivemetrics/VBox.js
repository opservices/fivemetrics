import React from 'react';
import {
  Row,
  Col,
  Grid
} from '@sketchpixy/rubix';

export default class VBox extends React.Component {
	render() {
		var props = {...this.props};
		var children = React.Children.map(props.children, (child) => {
			return <Col><Row>{child}</Row></Col>;
		});
		return (<Grid>{children}</Grid>);
	}
}