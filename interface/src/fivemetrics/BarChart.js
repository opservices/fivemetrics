import React from 'react';
import classNames from 'classnames';


export default class BarChart extends React.Component {
  constructor(props) {
    super(props);

    this.baseProperties = {
      path: "/js/vendor/amcharts",
      type: "serial",
      rotate: true,
      categoryField: "name",
      addClassNames: true,
      color: "#ffffff",
      marginTop: 10,
      marginBottom: 50,
      height: "100%",
      autoDisplay: true,
      autoResize: true,
      graphs: [
        {
          id: this.props.id + "-graph",
          type: "column",
          showBalloon: false,
          columnWidth: 0.4,
          labelText: "[[value]]",
          labelPosition: "right",
          labelOffset: 10,
          valueField: "value"
        }
      ],
      categoryAxis: {
        gridPosition: "start",
        position: "left",
        parseDates: false,
        tickPosition: "start",
        inside: true,
        labelsEnabled: true,
        fontSize: 16,
        gridThickness: 0,
      },
      valueAxes: [
        {
          gridAlpha: 0,
          unit: this.props.unit,
          gridColor: "#ffffff",
          color: "#ffffff",
          gridThickness: 0,
          inside: true,
          minorGridEnabled: true,
          labelsEnabled: false,
          autoGridCount: false,
          gridCount: 5,
          boldLabels: false

        }
      ],
      dataProvider: props.dp.slice(0,5) || []
    };
    this.chart = null;
  }


  updateData(data) {
    if (this.chart) {
      this.chart.dataProvider = data.points || (Array.isArray(data) ? data : [])
      this.chart.validateData()
    }
  }

  componentWillUpdate(nextProps,nextState) {
    if (Array.isArray(nextProps.dp)) {
      this.updateData(nextProps.dp.slice(0,5))
    }
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

  render() {
    return (
      <div id={`barchart_${this.props.id}`} ref="chart" style={{flex:1, height: this.props.height}} className={classNames('amchart-chart-container',this.props.className)}></div>
    );
  }
}
BarChart.defaultProps = {"minHeight": 2,"minWidth": 2}