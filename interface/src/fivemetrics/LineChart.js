import React from 'react';
import classNames from 'classnames';
import ChartUtils from "./utils/Chart"
import { SerialChart } from 'fivemetrics'
import { mergeDeepRight } from 'ramda'
import { Period } from 'fivemetrics/utils'

export default class LineChart extends React.Component {
  constructor(props) {
    super(props)

    this.chartUtils = new ChartUtils()

    this.chart = null

    this.style = this.props.style || {}

    this.baseProperties = {
      path: "/js/vendor/amcharts",
      type: "serial",
      valueAxes: [{
          unit: this.props.unit,
          inside: false,
          labelFunction: this.chartUtils.labelFunctionOnlySides(this.style, 'LineChart')
      }],
      graphs: [{
          id: this.props.id + "-graph",
          title: "linechart",
          showBalloon: true,
          balloonText: "[[value]]",
          bulletSizeField: "bulletSize",
          customBulletField: "bullet",
          bulletOffset: -5,
          labelText: "",
          labelFunction: this.chartUtils.labelFunctionGraph(this.style, 'LineChart')
      }],
      "categoryAxis": {
          "parseDates": true,
          "autoGridCount": true,
          "showLastLabel":  true,
          "labelOffset": 0
          //"minPeriod": "ss",
          //"labelFunction": function(v,d){return new Date(d).getHours()+'h'},
          //"gridCount": 24
      },
      marginLeft: 45,
      marginTop: 35,
      marginBottom: 5,
      dataProvider: props.dp || []
    };
  }


  updateData(data, period = null) {
    if (this.chart) {
      let p = Period.filter((e)=>(e.value == period)).shift() || {minPeriod: 'hh'}
      this.chart.categoryAxis.minPeriod = p.minPeriod
      this.chart.dataProvider = this.getDataProviderFromSource_(data);
      this.chart.ignoreZoomed = true;
      this.chart.validateNow(true)
    }
  }

  getDataProviderFromSource_(d) {
    const dataProvided = d.points || (Array.isArray(d) ? d : [])
    const [ max ] = this.chartUtils.updateSidesBasedOnSource(dataProvided)
    return dataProvided.map(curr =>
      (curr.value !== max)
        ? curr
        : Object.assign({}, curr, { bullet: "/imgs/star.svg", bulletSize: 55 })
    )
  }

  componentWillUpdate(nextProps,nextState) {
    let period = nextProps.period || nextProps.dp.period
    this.updateData(nextProps.dp, period)
  }

  componentDidMount() {
    this.chart = this.getChart()
    this.chart.ignoreZoomed = false
    this.chart.addListener("dataUpdated", (event) => {
      event.chart.zoomOut()
    });
  }

  getChart() {
    var chartProperties = mergeDeepRight({...this.props.theme.getChartProperties(this.props.type)},{...this.baseProperties})
    return AmCharts.makeChart(this.refs.chart,chartProperties,500);
  }

  componentWillUnmount() {
    if (this.chart) {
      this.chart.clear()
    }
  }

  render() {
    return (
      <SerialChart chart={this.chart}>
        <div id={`linechart_${this.props.id}`} ref="chart" style={{flex:1, height: this.props.height}} className={classNames('amchart-chart-container',this.props.className)}/>
      </SerialChart>
    );
  }
}
LineChart.defaultProps = {"minHeight": 5,"minWidth": 2}