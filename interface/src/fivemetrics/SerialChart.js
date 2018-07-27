import React from 'react';
import classNames from 'classnames';
import ChartUtils from 'fivemetrics/utils/Chart'
import NumberFormat from 'fivemetrics/utils/NumberFormat'

export default class SerialChart extends React.Component {
  constructor(props) {
    super(props)
    this.chartUtils = new ChartUtils()
    this.style = this.props.style || {}
  }

  componentWillUpdate(nextProps,nextState) {
  }

  componentDidMount() {
  }

  componentWillUnmount() {

  }

  render() {
    let data = (this.props.chart) ? this.props.chart.dataProvider.map(a => a.value) : []
    let unit = ''
    let max_value = Math.max(...data)
    max_value = isFinite(max_value) ? max_value : '-'
    let last_value = data.filter((a) => a).pop() || '-'
    let style = this.style
    return (
      <div className="serialchart">
        <div className="fm-advanced-numeric-label-container" style={{top: -10, position: 'absolute',right: 10}}>
            <div className="fm-advanced-numeric-label-text" style={{height: 35, color: 'darkgrey', marginRight: 10,width: 50, lineHeight: '15px', marginTop: 15, textAlign: 'right', float: 'left'}}>Last result</div>
            <div className={classNames("fm-advanced-numeric-label-value-first",last_value == '-' ? 'component-no-data' : '')} style={{height: 50, fontSize: '2.5em',fontWeight: 'bold', float: 'left'}}>{NumberFormat.format(last_value,unit,style).toString()}</div>
            <div style={{textAlign: 'right', color: 'grey',lineHeight: '10px'}}>
              <span className="fm-advanced-numeric-label-text" style={{marginRight: 10}}>Maximum</span>
              <span className={classNames("fm-advanced-numeric-label-value-second",max_value == '-' ? 'component-no-data' : '')} style={{fontSize: '1em'}}>{NumberFormat.format(max_value,unit,style).toString()}</span>
            </div>
        </div>
        {this.props.children}
        <div>{ ChartUtils.tryGetErrorMsg(this.props.chart)}</div>
      </div>
    );
  }
}
