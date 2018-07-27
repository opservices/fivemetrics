import React from 'react'
import classNames from 'classnames'
import {MicroColumnChart} from 'fivemetrics'
import Griddle, {
    plugins,
    RowDefinition,
    ColumnDefinition,
    Table,
    Pagination,
    Filter,
    SettingsWrapper
} from 'griddle-react'
import {Label} from '@sketchpixy/rubix'

export default class AdvancedDataGrid extends React.Component {
    constructor(props) {
        super(props)

        this.customSort = this.customSort.bind(this)

        this.newLayout = ({Table, Pagination, Filter, SettingsWrapper}) => (
            <div className="AdvancedDataGrid">
                <div className="griddle-table-wrapper">
                    <Table/>
                </div>
            </div>
        )
        this.griddata = []
        this.customComponentBadge = ({value}) => {
            return <Label className="bg-darkgreen45 fg-white">{value}</Label>
        }
        this.customComponentMicroColumnChart = ({value}) => {
            let d = value.toArray().map(d => {
                let o = {}
                for (var [key, value] of d.entries()) {
                    o[key] = value
                }
                return o
            })
            return <MicroColumnChart dp={d} id={this.props.id} height={27} width={150} theme={this.props.theme}
                                     type="MicroColumnChart"/>
        }
        this.customComponentLabel = ({title}) => {
            return <span style={{color: '#AA0000'}}>{title}</span>
        }

    }

    customComponentLabel({title}) {
        return <span style={{color: '#AA0000'}}>{title}</span>
    }

    /** ###########################################**/
    unitBytes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];

    round(number, precision) {
        let factor = Math.pow(10, precision);
        let tempNumber = number * factor;
        let roundedTempNumber = Math.round(tempNumber);
        return roundedTempNumber / factor;
    }

    fixUnit = function (data) {

        //fixes unit
        if (data.points["0"].hasOwnProperty('unit')) {
            data.unit = data.points["0"].unit
        }

        if (this.unitBytes.some(element => element == data.unit)) {
            return {'value': data.maximum, 'key_unit': data.unit};
        }

        let bytes = data.maximum
        if (bytes == 0) return {'value': 0, 'key_unit': 'B'};

        let i = Math.floor(Math.log(bytes) / Math.log(1024));
        let value = this.round(bytes / Math.pow(1024, i), 1);
        let unit = (this.unitBytes[i]) ? this.unitBytes[i] : 'B';

        return {'value': value, 'key_unit': unit};
    };

    updateData(nextProps) {
        if (Array.isArray(nextProps.dp.series)) {
            this.griddata = nextProps.dp.series

            this.griddata.map(dt => {
                if (dt.tags["::fm::instanceName"] == '\"\"' || ! dt.tags["::fm::instanceName"]) {

                    if (dt.tags["::fm::instanceId"] == '\"\"') {
                        dt.tags["::fm::instanceName"] = 'Unattached'
                    } else {
                        dt.tags["::fm::instanceName"] = dt.tags["::fm::instanceName"]
                    }
                }
                if (nextProps.metrics.some(element => element.name == "unit")) {
                    let dataTmp = this.fixUnit(dt)
                    dt.maximum = dataTmp.value
                    dt.unit = dataTmp.key_unit
                }
            })

            /** EBS **/
            nextProps.metrics.forEach(metric => {
                if (metric.hasOwnProperty("secondOption")) {
                    let firstChoice = metric.name.slice(5)
                    let secondChoice = metric.secondOption.slice(5)
                    this.griddata.map(dt => {
                        if (dt.tags[firstChoice] == '\"\"' || ! dt.tags[firstChoice]) {

                            dt.tags[firstChoice] = dt.tags[secondChoice]
                        }
                    })
                }
            })
        } else {
            this.griddata = []
        }
    }

    componentWillUpdate(nextProps, nextState) {
        this.updateData(nextProps)
    }

    resize() {
    }

    componentDidMount() {
        PubSub.subscribe("windowResize", () => {
            this.forceUpdate()
        })
    }

    componentWillUnmount() {
        PubSub.unsubscribe("windowResize")
    }

    setWidgetHeight(el) {
    }

    createColumns() {
        let self = this

        if (Array.isArray(this.props.metrics)) {
            return this.props.metrics.map(({ name, title, type, sortable = true }) => {
                let columnProps = {key: 'key_' + name, id: name, title: title}

                if (this['customComponent' + type]) {
                    columnProps.customComponent = this['customComponent' + type]
                }

                return <ColumnDefinition
                    {...columnProps}
                    sortable={sortable}
                    sortMethod={this.customSort}
                />
            })
        }
    }

    customSort(data, column, sortAscending = true) {
      return data.sort(
        (original, newRecord) => {
          const columnKey = column.split('.');
          const originalValue = (original.hasIn(columnKey) && original.getIn(columnKey)) || '';
          const newRecordValue = (newRecord.hasIn(columnKey) && newRecord.getIn(columnKey)) || '';

          if(originalValue === newRecordValue) {
            return 0;
          } else if (originalValue > newRecordValue) {
            return sortAscending ? 1 : -1;
          }
          else {
            return sortAscending ? -1 : 1;
          }
        });
    }

    render() {

        return (
            <Griddle
                pageProperties={{
                    pageSize: 100,
                    recordCount: 100,
                }}
                id={`advanceddatagrid_${this.props.id}`}
                ref="datagrid"
                data={this.griddata}
                plugins={[plugins.LocalPlugin]}
                components={{Layout: this.newLayout}}
            >
                <RowDefinition>
                    {this.createColumns()}
                </RowDefinition>
            </Griddle>
        )
    }
}
AdvancedDataGrid.defaultProps = {"minHeight": 4, "minWidth": 3}