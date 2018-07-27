import React from 'react'
import classNames from 'classnames'
import { MainNumber, SecondaryNumber } from 'fivemetrics/fragments'
import NumberFormat from 'fivemetrics/utils/NumberFormat'
import ChartUtils from "./utils/Chart"
import { NumericLabel } from 'fivemetrics'


export default class PieChart extends React.Component {
  constructor(props) {
    super(props);
    this.chartUtils = new ChartUtils()
    this.baseProperties = {
      path: "/js/vendor/amcharts",
      type: "pie",
      pieY: "50%",
      allLabels: [{
        "text": "",
        "align": "center",
        "y": 0
      }],
      addClassNames: true,
      titleField: "name",
      valueField: "value",
      descriptionField: "description",
      dataProvider: props.dp || [],
      legend: {
        divId: `piechart_legend_${this.props.id}`,
        valueText: '[[percents]]% - [[description]] ',
      },
      balloonText: "[[percents]]% - [[description]] "
    };
    this.chart = null;
    this.formatValue = this.formatValue.bind(this)
  }


  updateData(data = {}) {
    if (this.chart) {
      //data = JSON.parse('[{"name":"aws.ec2.autoscaling.instanceState","tags":[],"minimum":3,"time":"2017-07-17T16:36:04.000000001Z","value":3},{"name":"aws.ec2.instanceState","tags":[],"minimum":67,"time":"2017-07-17T16:36:04.000000001Z","value":67}]')
      this.chart.dataProvider = (data.points || (Array.isArray(data) ? data : [])).map((e) => {
        e.description = this.formatValue(e.value)
        e.value = parseFloat(e.value)
        return e
      }).sort((a, b) => {
        if (a.value > b.value) {
          return -1
        }
        if (a.value < b.value) {
          return 1
        }
        return 0
      })
      this.chart.validateData()
    }
  }

  componentWillUpdate(nextProps,nextState) {
    this.updateData(nextProps.dp.series || nextProps.dp.points)
  }

  resize() {
  }

  componentDidMount() {
    this.chart = this.getChart()
  }

  getChart() {
    var chartProperties = Object.assign({},this.baseProperties,this.props.theme.getChartProperties(this.props.type))
    return AmCharts.makeChart(this.refs.chart,chartProperties,500)
  }

  componentWillUnmount() {
    if (this.chart) {
      this.chart.clear()
    }
  }

  componentWillReceiveProps(nextProps) {
  }

  setWidgetHeight(el) {
  }

  formatValue(value) {
    let unit = this.props.dp.unit || ''
    let {id, icon, theme, label, labelmax, style, ...props} = this.props
    let formatted = NumberFormat.format(value, unit, style)
    console.log(formatted)
    value = formatted.toString()
    return value;
  }

  render() {
    const height = this.props.height == "100%" ? this.props.height : this.props.height-100
    const msg = ChartUtils.tryGetErrorMsg(this.chart)
    let value = msg === "" ? (this.props.dp.hasOwnProperty('value') ? this.props.dp.value : this.props.dp.series.length) : "-"

    if (this.props.style.createMax && Array.isArray(this.props.dp.points)) {
      value = this.props.dp.points.reduce((acc, cur)=>{
        return NumberFormat.isNumeric(cur.value) ? parseFloat(cur.value) + acc : acc;
      },0)
    }
    const style = this.props.style
    const styleLabel = Object.assign({},{precision: 2},style)

    return (
      <div className={classNames(this.props.className)}>

        <div className="number-wrapper">
          <NumericLabel value={value} style={styleLabel}/>
        </div>
        <div id={`piechart_${this.props.id}`} ref="chart" className="amchart-chart-container amchart-chart-piechart-container"></div>
        <div id={`piechart_legend_${this.props.id}`} className="amchart-chart-container amchart-chart-legend-container"></div>
        <div style={{textAlign: "center"}}>{msg}</div>
      </div>
    );
  }
}

PieChart.defaultProps = {"minHeight": 8,"minWidth": 2}
