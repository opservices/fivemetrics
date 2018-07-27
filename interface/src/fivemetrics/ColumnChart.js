import React from 'react';
import classNames from 'classnames';
import ChartUtils from "./utils/Chart"
import { SerialChart } from 'fivemetrics'
import { mergeDeepRight } from 'ramda'
import { Config } from 'config'
import { Period, Theme } from 'fivemetrics/utils'

export default class ColumnChart extends React.Component {
  constructor(props) {
    super(props);

    this.chartUtils = new ChartUtils()
    this.theme = Theme.load(Config.Theme)

    this.chart = null;

    this.style = this.props.style || {}

    this.baseProperties = {
      path: "/js/vendor/amcharts",
      type: "serial",
      valueAxes: [
        {
          unit: this.props.unit,
          inside: false,
          labelFunction: this.chartUtils.labelFunctionOnlySides(this.style, 'ColumnChart')
        }
      ],
      graphs: [
        {
          id: this.props.id + "-graph",
          title: "columnchart",
          type: "column",
          showBalloon: true,
          hideBulletsCount: 0,
          labelText: (this.style.show_labels===false) ? "" : "[[value]]",
          balloonFunction: this.chartUtils.labelFunctionGraph(this.style, 'ColumnChart'),
          fillColors: [this.theme.gradientColorDefault[1]],
          labelFunction: this.chartUtils.labelFunctionGraph(this.style, 'ColumnChart')
        }
      ],
      "categoryAxis": {
      },
      marginTop: 35,
      marginLeft: 45,
      dataProvider: props.dp || []
    };


    if (this.style && this.style.type == "stacked") {
      this.baseProperties.valueAxes[0].stackType = "regular"
      this.baseProperties.graphs.push({
          id: this.props.id + "-graph-stack",
          title: "columnchart",
          type: "column",
          showBalloon: true,
          hideBulletsCount: 0,
          fillAlphas: 0.5,
          color: this.theme.gradientColorDefault[1],
          fillColors: [this.theme.gradientColorDefault[1]],
          labelText: (this.style.show_labels===false) ? "" : "[[value]]",
          balloonFunction: this.chartUtils.labelFunctionGraph(this.style, 'ColumnChart'),
          labelFunction: this.chartUtils.labelFunctionGraph(this.style, 'ColumnChart'),
          valueField: "value2"
      })
      //this.baseProperties.graphs[0].fillAlphas = 0.5

      //this.baseProperties.graphs.reverse()

    }


  }


  updateData(data, period=null) {
    if (this.chart) {
      const dataProvided = data.points || (Array.isArray(data) ? data : [])
      if (this.style && this.style.type == "stacked") {
        dataProvided.map((e, index, a)=>{
          e.value2 = (index) ? e.value : 0
          e.value = (index) ? parseFloat(a[index-1].value2) + parseFloat(a[index-1].value) : e.value
        })
      }
      //if (data.maximum && data.minimum) {
      //  this.chartUtils.updateSides([ data.maximum, data.minimum ])
      //}
      //else {
        this.chartUtils.updateSidesBasedOnSource(dataProvided)
      //}
      let p = Period.filter((e)=>(e.value == period)).shift() || {minPeriod: 'hh'}
      this.chart.categoryAxis.minPeriod = p.minPeriod
      this.chart.dataProvider = dataProvided
      this.chart.validateNow(true)
    }
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
    return AmCharts.makeChart(this.refs.chart,chartProperties,500)
  }

  componentWillUnmount() {
    if (this.chart) {
      this.chart.clear()
    }
  }

  render() {
    return (
      <SerialChart chart={this.chart} {...this.props}>
        <div id={`columnchart_${this.props.id}`} ref="chart" style={{flex:1, height: this.props.height}} className={classNames('amchart-chart-container',this.props.className)}/>
      </SerialChart>
    );
  }
}
ColumnChart.defaultProps = {"minHeight": 2,"minWidth": 2}