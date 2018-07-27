import { SvgSupport } from 'fivemetrics/utils';

export default class Majortom {

    constructor() {

    }

    get name() {
        return 'majortom'
    }

    //get svg_support() {
    //    const svg_support = new SvgSupport
    //    return svg_support
    //}
    get gradientColorDefault() {
        return ['#3EFCC9','#8F52F8']
    }

    get gradientColorCritical() {
        return ['#F552DC','#E72E2E']
    }

    get gradientColorWarning() {
        return ['#FAF131','#FF8D6C']
    }

    get gradientColorUnknown() {
        return ['#898989','#353535']
    }

    getChartProperties(chartType) {

        var properties = {"theme": this.name}

        const lineChartProperties = {
            "theme": this.name,
            "defs": {
                "linearGradient": {
                    "id": "themeLineStroke",
                    "x1": "100%",
                    "x2": "0%",
                    "y1": "76.5719541%",
                    "y2": "76.5719533%",
                    "stop": [{
                        "offset": "0%",
                        "stop-color": this.gradientColorDefault[0]
                    },{
                        "offset": "100%",
                        "stop-color": this.gradientColorDefault[1]
                    }]
                }
            },
            "listeners": [{
              "event": "init",
              "method": function(e) {
                  e.chart.removeListener(e.chart.chartScrollbar, "zoomed", e.chart.handleScrollbarZoom);
              }
            }],
            "chartCursor": {
                "enabled": true,
                "zoomable": false,
                "zooming": false,
                "bulletSize": 11,
                "animationDuration" : 0,
                "showNextAvailable": true,
                "selectWithoutZooming": true
            },
            "balloon": {
                "adjustBorderColor": false,
                "horizontalPadding": 8,
                "verticalPadding": 5,
                "color": "#3EFCC9"
            },
            "addClassNames": true,
            "categoryField": "date"
        }

        const columnChartProperties = {
            "theme": this.name,
            "addClassNames": true,
            "titleField": "date",
            "marginBottom": 30,
            "valueField": "value",
            "categoryField": "date",
            "columnWidth": 0.5,
            "categoryAxis": {
                "parseDates": true,
                "minPeriod": "hh",
                //"labelFunction": function(v,d){return new Date(d).getHours()+'h'},
                "inside": false,
                "labelFrequency": 1,

                "labelsEnabled": true,
                "fontSize": 11,
                "centerLabels": true,
                "autoGridCount": true,
                //"gridCount": 24,
                "gridThickness": 0
            }
        }

        const barChartProperties = {
            "theme": this.name,
            "defs": {
                "linearGradient": {
                    "id": "themeBarGradient",
                    "x1": "100%",
                    "x2": "0%",
                    "y1": "76.5719541%",
                    "y2": "76.5719533%",
                    "stop": [{
                        "offset": "0%",
                        "stop-color": this.gradientColorDefault[0]
                    },{
                        "offset": "100%",
                        "stop-color": this.gradientColorDefault[1]
                    }]
                }
            }
        }

        const pieChartProperties = {
            "theme": this.name,
            "addClassNames": true,
            "labelsEnabled": false,
            "autoMargins": false,
            "marginTop": 4,
            "marginBottom": 4,
            "marginLeft": 4,
            "marginRight": 4,
            "pullOutRadius": 0,
            "height": 260,
            "autoResize": true
        }

        const microColumnChartProperties = {
            "theme": this.name,
            "defs": {},
            "categoryAxis": {
                parseDates: false,
                gridThickness: 0,
                labelsEnabled: false
            }
        }

        switch(chartType) {
            case 'LineChart':
                properties = lineChartProperties
            break;
            case 'ColumnChart':
                properties = columnChartProperties
            break;
            case 'BarChart':
                properties = barChartProperties
            break;
            case 'PieChart':
                properties = pieChartProperties
            break;
            case 'MicroColumnChart':
                properties = microColumnChartProperties
            break;
        }

        return properties
    }

}

if (typeof AmCharts !== 'undefined') {
    AmCharts.themes.majortom = {

        themeName:"majortom",

        AmChart: {
            color: "#000000",
            backgroundColor: "#FFFFFF"
        },

        AmBalloon: {
            borderThickness: 0,
            shadowAlpha: 0,
            fillColor: "#1D1B1D",
            showBullet: true,
            // offsetY: "-10px",
            color: "#ffffff",
            fillAlpha: 1,
            drop: false,
            pointerWidth: 1,
            fixedPosition: false
        },

        AmSlicedChart: {
            colors: ["#AC8BE4", "#53D0D5", "#8F52F8", "#3EFCC9", "#A16CFB", "#108B6A", "#AC8BE4", "#53D0D5", "#8F52F8", "#3EFCC9", "#A16CFB", "#108B6A", "#AC8BE4", "#53D0D5", "#8F52F8", "#3EFCC9", "#A16CFB", "#108B6A", "#AC8BE4", "#53D0D5", "#8F52F8", "#3EFCC9", "#A16CFB", "#108B6A", "#AC8BE4", "#53D0D5", "#8F52F8", "#3EFCC9", "#A16CFB", "#108B6A", "#AC8BE4", "#53D0D5", "#8F52F8", "#3EFCC9", "#A16CFB", "#108B6A"],
            outlineAlpha: 1,
            outlineThickness: 0,
            labelTickColor: "#000000",
            labelTickAlpha: 0.3,
        },

        AmRectangularChart: {
            zoomOutButtonColor: '#000000',
            zoomOutButtonRollOverAlpha: 0.15,
            zoomOutButtonImage: "lens",
            marginRight: 0,
            marginLeft: 0,
            marginBottom: 0,
            marginTop: 0,
            autoMarginOffset: 0,
            usePrefixes: true
        },

        AxisBase: {
            axisColor: "#ffffff",
            gridAlpha: 0,
            gridColor: "#ffffff",
            gridThickness: 1,
            position: "left",
            ignoreAxisWidth: true,
            dashLength: 1,
            stackType: "regular",
            minorGridEnabled: true,
            minorGridAlpha: 0.24,
            labelsEnabled: false,
            autoGridCount: false
        },

        ValueAxis: {
            axisAlpha: 0.2,
            dashLength: 9876,
            gridAlpha: 0.5,
            color: "#FFF",
            gridColor: "#ffffff",
            gridThickness: 1,
            inside: true,
            minorGridEnabled: true,
            minorGridAlpha: 0.24,
            labelsEnabled: true,
            autoGridCount: false,
            gridCount: 5
        },

        CategoryAxis: {
            axisAlpha: 0,
            parseDates: true,
            dashLength: 0,
            gridAlpha: 0.5,
            gridColor: "#ffffff",
            gridThickness: 1,
            minPeriod: "mm",
            inside: true,
            minorGridEnabled: true,
            minorGridAlpha: 0.5,
            labelsEnabled: true,
            boldLabels: false,
            boldPeriodBeginning: false,
            centerLabels: false,
            labelFrequency: 1,
            labelOffset: 1,
            markPeriodChange: false,
            dateFormats: [{"period":"fff","format":"JJ:NN:SS"},{"period":"ss","format":"JJ:NN:SS"},{"period":"mm","format":"JJ:NN"},{"period":"hh","format":"JJ:NN"},{"period":"DD","format":"MMM DD"},{"period":"WW","format":"MMM DD"},{"period":"MM","format":"MMM"},{"period":"YYYY","format":"YYYY"}]
        },

        ChartScrollbar: {
            backgroundColor: "#000000",
            backgroundAlpha: 0.12,
            graphFillAlpha: 0.5,
            graphLineAlpha: 0,
            selectedBackgroundColor: "#FFFFFF",
            selectedBackgroundAlpha: 0.4,
            gridAlpha: 0.15
        },

        ChartCursor: {
            cursorColor: "#ffffff",
            color: "#1D1B1D",
            cursorAlpha: 0.5,
            pan: false,
            valueLineEnabled: false,
            valueLineBalloonEnabled: false,
            cursorAlpha: 1,
            valueLineAlpha: 1,
            valueZoomable: false,
            bulletsEnabled: true,
            bulletSize: 10,
            categoryBalloonEnabled: false
        },

        AmLegend: {
            top: 315,
            position: 'absolute',
            maxColumns: 1,
            autoMargins: false,
            color: '#ffffff',
            markerSize: 30,
            markerType: 'bar',
            labelText: '[[title]]',
            valueWidth: 150
        },
        AmPieChart: {
            innerRadius: "85%",
            labelsEnabled: false,
            tapToActivate: false,
            pieX: "50%",
            pieY: "50%",
            marginBottom: 0,
            marginLeft: 0,
            marginRight: 0,
            marginTop: 0
        },
        AmGraph: {
            lineAlpha: 1,
            fillAlphas: 1,
            pointPosition: "middle",
            fillColors: ["#3EFCC9","#8F52F8"],
            bullet: "none",
            plotAreaGradientAngle: 0,
            bulletBorderAlpha: 0,
            bulletColor: "#FFFFFF",
            bulletSize: 10,
            hideBulletsCount: 50,
            lineThickness: 1,
            useLineColorForBulletBorder: false,
            valueField: "value",
            balloonText: "[[value]]",
            autoMarginOffset: 0,
            autoMargins: false,
            gridAboveGraphs: true,
            labelPosition: "top",
            labelText: "[[value]]"
        },
        AmSerialChart: {
            gradientOrientation: "vertical"
        },
        GaugeArrow: {
            color: "#000000",
            alpha: 0.8,
            nailAlpha: 0,
            innerRadius: "40%",
            nailRadius: 15,
            startWidth: 15,
            borderAlpha: 0.8,
            nailBorderAlpha: 0
        },

        GaugeAxis: {
            tickColor: "#000000",
            tickAlpha: 1,
            tickLength: 15,
            minorTickLength: 8,
            axisThickness: 3,
            axisColor: '#000000',
            axisAlpha: 1,
            bandAlpha: 0.8
        },

        TrendLine: {
            lineColor: "#c03246",
            lineAlpha: 0.8
        },

        // ammap
        AreasSettings: {
            alpha: 0.8,
            color: "#67b7dc",
            colorSolid: "#003767",
            unlistedAreasAlpha: 0.4,
            unlistedAreasColor: "#000000",
            outlineColor: "#FFFFFF",
            outlineAlpha: 0.5,
            outlineThickness: 0.5,
            rollOverColor: "#3c5bdc",
            rollOverOutlineColor: "#FFFFFF",
            selectedOutlineColor: "#FFFFFF",
            selectedColor: "#f15135",
            unlistedAreasOutlineColor: "#FFFFFF",
            unlistedAreasOutlineAlpha: 0.5
        },

        LinesSettings: {
            color: "#000000",
            alpha: 0.8
        },

        ImagesSettings: {
            alpha: 0.8,
            labelColor: "#000000",
            color: "#000000",
            labelRollOverColor: "#3c5bdc"
        },

        ZoomControl: {
            buttonFillAlpha:0.7,
            buttonIconColor:"#a7a7a7",
            mouseWheelZoomEnabled: false,
            maxZoomFactor: 1,
            panEventsEnabled: false
        },

        SmallMap: {
            mapColor: "#000000",
            rectangleColor: "#f15135",
            backgroundColor: "#FFFFFF",
            backgroundAlpha: 0.7,
            borderThickness: 1,
            borderAlpha: 0.8
        },

        // the defaults below are set using CSS syntax, you can use any existing css property
        // if you don't use Stock chart, you can delete lines below
        PeriodSelector: {
            color: "#000000"
        },

        PeriodButton: {
            color: "#000000",
            background: "transparent",
            opacity: 0.7,
            border: "1px solid rgba(0, 0, 0, .3)",
            MozBorderRadius: "5px",
            borderRadius: "5px",
            margin: "1px",
            outline: "none",
            boxSizing: "border-box"
        },

        PeriodButtonSelected: {
            color: "#000000",
            backgroundColor: "#b9cdf5",
            border: "1px solid rgba(0, 0, 0, .3)",
            MozBorderRadius: "5px",
            borderRadius: "5px",
            margin: "1px",
            outline: "none",
            opacity: 1,
            boxSizing: "border-box"
        },

        PeriodInputField: {
            color: "#000000",
            background: "transparent",
            border: "1px solid rgba(0, 0, 0, .3)",
            outline: "none"
        },

        DataSetSelector: {

            color: "#000000",
            selectedBackgroundColor: "#b9cdf5",
            rollOverBackgroundColor: "#a8b0e4"
        },

        DataSetCompareList: {
            color: "#000000",
            lineHeight: "100%",
            boxSizing: "initial",
            webkitBoxSizing: "initial",
            border: "1px solid rgba(0, 0, 0, .3)"
        },

        DataSetSelect: {
            border: "1px solid rgba(0, 0, 0, .3)",
            outline: "none"
        }

    }
}
