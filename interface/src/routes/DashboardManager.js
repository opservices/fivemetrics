import React from 'react'
import {Dashboard} from 'fivemetrics'
import * as dashboards from 'routes/dashboards'
import {Dispatcher} from '@sketchpixy/rubix'
import PubSub from 'pubsub-js'

export default class DashboardManager extends React.Component {
    constructor(props) {
        super(props)
        this.defaultClassname = 'AwsEc2'
        this.editMode = false
        this.dashboard = {}
        this.getDashboardProvider = this.getDashboardProvider.bind(this)
        this.state = {dashboard: this.getDashboardProvider(props), editMode: false, resetLayout: false}
        this.settings = this.settings.bind(this)
    }

    componentDidMount() {
        Dispatcher.subscribe('dashboard:settings', this.settings)
    }

    getDashboardProvider(props) {
        let classname = [props.params.namespace,props.params.datasource].map((n) => {
            return String(n).charAt(0).toUpperCase() + String(n).slice(1)
        }).join('')
        let d = (dashboards.hasOwnProperty(classname)) ? dashboards[classname] : dashboards[this.defaultClassname]
        return d
    }

    componentWillUnmount() {
        Dispatcher.unsubscribe('dashboard:settings')
    }

    settings(settings) {
        switch(settings) {
            case 'edit_layout':
                this.setState({editMode: !this.state.editMode})
            break
            case 'reset_layout':
              if (this.dashboard) {
                this.dashboard.resetLayout()
              }
            break
        }
    }

    componentWillReceiveProps(nextProps) {
        this.setState({dashboard: this.getDashboardProvider(nextProps)})
    }

    render() {
        return (<Dashboard ref={(dashboard) => { this.dashboard = dashboard }} {...this.state.dashboard} key={this.state.dashboard.id} onLayoutChange={() => {}} editMode={this.state.editMode} resetLayout={this.state.resetLayout}/>)
    }
}
