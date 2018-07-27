import React from 'react'
import ReactDOM from 'react-dom'
import { Moment } from 'fivemetrics/utils'

export default class DateHour extends React.Component {
  constructor(props) {
    super(props)
  }

  render() {
    return (
    	<div className="date-hour">
        <h4>
          <span className="date-month"><Moment format="MMM" locale="en">{this.props.date}</Moment></span>
          <span className="date-day"><Moment format="DD" locale="en">{this.props.date}</Moment></span>
          <span className="date-year"><Moment format="YYYY" locale="en">{this.props.date}</Moment></span>
          <span className="hour"><Moment format="hh:mma" locale="en">{this.props.date}</Moment></span>
        </h4>
    	</div>
    );
  }
}
