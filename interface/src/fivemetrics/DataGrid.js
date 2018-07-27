import React from 'react'
import classNames from 'classnames'
import {NumberFormat} from 'fivemetrics/utils'
import { Table, Input, Button, Icon } from 'antd'
import Moment from 'moment'

export default class DataGrid extends React.Component {
    constructor(props) {
        super(props)
        this.state = {dimensions: {width: 0,height: 0}, filterDropdownVisible: {}, searchObject: {}}
        this.getData = this.getData.bind(this)
        this.onSearch = this.onSearch.bind(this)
        this.emitEmpty = this.emitEmpty.bind(this)
        this.getColumns = this.getColumns.bind(this)
        this.searchInput = {}
        this.data = []
    }

    getColumns(expandedRow = false) {
        if (!this.props.columns) return []
        return this.props.columns.filter((e) => {
            return ((expandedRow && e.expandedRow) || (!expandedRow && !e.expandedRow)) ? true : false
        }).map((o) => {
            const field = o.dataIndex
            if (!expandedRow) {
                o.sorter = (a, b) => {
                    let _a = a[field]
                    let _b = b[field]
                    if (o.hasOwnProperty('type') && o.type == 'datetime') {
                        _a = Moment(a[field]).valueOf()
                        _b = Moment(b[field]).valueOf()
                    }
                    return +/\d+/.exec(_a)[0] - +/\d+/.exec(_b)[0]
                }
            }
            if (o.searchable) {
                const val = this.searchInput[field] ? this.searchInput[field].input.value : ''//this.state.searchObject[field] ? this.state.searchObject[field] : ''
                const suffix = val ? <Icon type="close" key={field} name={field} onClick={() => this.emitEmpty(field)} /> : <Icon type="search" key={field} name={field} />
                o.filterDropdown = (
                    <div className="custom-filter-dropdown">
                      <Input size="small" key={field}
                        placeholder="Search"
                        suffix={suffix}  ref={node => this.searchInput[field] = node}
                        name={field}
                        onPressEnter={this.onSearch}
                      />
                    </div>
                )
            }
            return o
        })
    }

    emitEmpty(field) {
        if (this.searchInput[field]) {
            this.searchInput[field].focus()
            this.searchInput[field].input.value = ""
            let searchObject = this.state.searchObject
            delete searchObject[field]
            this.setState({ searchObject })
        }
    }

    onSearch() {
        let searchObject = {}
        Object.keys(this.searchInput).forEach((e) => {
            searchObject[e] = this.searchInput[e].input.value
        })
        this.setState({searchObject})
    }

    getFiltedData(store,filter) {
        return store.filter((record) => {
            return Object.keys(filter).every((field) => {
                const reg = new RegExp(filter[field], 'gi')
                const match = String(record[field]).match(reg)
                return (!!match)
            })
        })
    }

    getData() {
        return this.data
    }

    updateData(data) {

        let filter_formater = {
            datetime: (d) => {
                return Moment(d).format('lll')
            },
            RecurringCharges: (d) => {
               return Array.isArray(d) ? d.map((e) => e.Frequency+': '+e.Amount).join(", ") : ""
            }
        }

        let filter = {}

        this.props.columns.forEach((e) => {
            if (filter_formater.hasOwnProperty(e.type)) {
                filter[e.dataIndex] = filter_formater[e.type]
            }

        })

        this.data = Array.isArray(data.points) ? data.points.map((e, index) =>{
            e.key = index
            Object.keys(filter).forEach((ee) => {
                e[ee] = filter[ee](e[ee])
            })
            return e
        }) : []
    }

    componentWillUpdate(nextProps, nextState) {
        if (nextProps.dp) {
            this.updateData(nextProps.dp)
        }
    }

    expandedRowRender(record) {
        return (
          <Table
            columns={this.getColumns(true)}
            dataSource={[record]}
            pagination={false}
          />
        )
    }


    render() {
        let p = {
            expandedRowRender: this.expandedRowRender.bind(this)
        }

        return (
            <div id={`datagrid_${this.props.id}`} style={{overflow: 'auto', height:(this.props.height || 50)-25 || this.props.height}}>
                <Table columns={this.getColumns()}  dataSource={this.getFiltedData(this.getData(),this.state.searchObject)} pagination={false} {...p} />
            </div>

        )
    }
}
