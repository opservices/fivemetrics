import React from 'react'
import classNames from 'classnames'


export default class MicroColumnChart extends React.Component {
  constructor(props) {
    super(props);

    this.baseProperties = {
      path: "/js/vendor/amcharts",
      type: "serial",
      addClassNames: true,
      categoryField: "date",
      dataProvider: props.dp || [],
      autoMargins: false,
      marginLeft: 5,
      marginRight: 5,
      height: props.height || '100%',
      width: '100%',
      marginTop: 0,
      marginBottom: 0,
      valueAxes: [{
        unit: props.unit,
        gridAlpha: 0,
        axisAlpha: 0,
        labelsEnabled: false
      }],
      graphs: [{
        id: props.id + "-graph",
        title: "columnchart",
        type: "column",
        fillAlphas: 1,
        strokeAlphas: 0,
        lineColor: "#3EFCC9",
        showBalloon: false,
        fillColors: "#3EFCC9",
        labelText: '',
        columnWidth: 0.4
      }],
      categoryAxis: {
        "parseDates": false,
        gridAlpha: 0,
        axisAlpha: 0
      }
    }
    this.chart = null;
  }


  updateData(data) {
    if (this.chart) {
      this.chart.dataProvider = data.points || (Array.isArray(data) ? data : [])
      this.chart.validateData()
    }
  }

  componentWillUpdate(nextProps,nextState) {
    this.updateData(nextProps.dp)
  }

  resize() {
    if (this.chart && this.refs.chart.parentElement) {
        const h = this.refs.chart.parentElement.getBoundingClientRect().height
        const w = this.refs.chart.parentElement.getBoundingClientRect().width
        this.refs.chart.style.height = h - 70
        this.refs.chart.style.width = w - 20
        this.chart.invalidateSize()
    }
  }

  componentDidMount() {
    this.chart = this.getChart()
  }

  getChart() {
    var chartProperties = {...this.baseProperties,...this.props.theme.getChartProperties(this.props.type)}

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

  render() {
    return (
      <div id={`microcolumnchart_${this.props.id}`}
          ref="chart"
          style={{flex:1, height: this.props.height, width: '100%'}}
          className={classNames('amchart-chart-container',this.props.className)}>
      </div>
    );
  }
}