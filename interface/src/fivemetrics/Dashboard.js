import React from 'react'
import { assoc, dissoc, prop, uniq } from "ramda"
import * as Maybe from "./utils/Maybe"
import {
  Dispatcher,
  Row,
  Col
} from '@sketchpixy/rubix'

import { Dashlet } from 'fivemetrics'
import { Factory, LocalStorage } from 'fivemetrics/utils'
import { DashboardHeader } from 'fivemetrics/fragments'

import {Responsive, WidthProvider,ReactGridLayout} from 'react-grid-layout'
import { ProfileManager } from 'fivemetrics/utils'

const ResponsiveReactGridLayout = WidthProvider(Responsive)

import { Alert } from 'antd'


export default class Dashboard extends React.Component {
  constructor(props) {
    super(props)
    this.profileManager = ProfileManager.getInstance()
    this.state = {
      paymentType: '',
      lastUpdate: undefined,
      editLayout: props.editMode || false,
      metrics: uniq((this.props.dashlets || []).reduce(
          (acc, dashlet) => (
            acc.concat(dashlet.element.ds.map(prop("metric")))
          )
        , []
        ))
    }
    this.mounted = false
    this.timer = null
    this.grid = null
    this.onLayoutChange = this.onLayoutChange.bind(this)
    this.onResizeStop = this.onResizeStop.bind(this)
    this.saveAndApply = this.saveAndApply.bind(this)
    this.getLayout = this.getLayout.bind(this)
    this.resetLayout = this.resetLayout.bind(this)
    this.getChildren = this.getChildren.bind(this)
  }

  onLayoutChange(layout, layouts) {
    if (this.props.editMode) {
      this.saveAndApply(layouts)
    }
  }

  onResizeStop(ItemCallback) {
    console.log(ItemCallback);
    this.forceUpdate()
  }

  resetLayout() {
    this.saveAndApply({})
    this.forceUpdate()
  }

  saveAndApply(layouts) {
    let local = LocalStorage.read('custom-layout') || {}
    LocalStorage.write('custom-layout',Object.assign(local,{[this.props.id]: layouts}))
  }

  updateDashboard(data) {
    if (data.lastUpdate && this.mounted) {
      clearTimeout(this.timer)
      this.timer = setTimeout(() => {
        if (this.mounted) {
          this.setState({lastUpdate: data.lastUpdate})
        }
      },1000)
    }
  }

  componentDidMount() {
    this.profileManager.getData()
      .then(({ uid, paymentType }) => this.setState({ uid, paymentType }))
   this.mounted = true
    this.systemtagstoken = Dispatcher.subscribe('systemTags', (mtags, flag) => {
      const before = LocalStorage.read('tags-filter')
      const next = mtags
        .chain(Maybe.fromEmpty)
        .cata({
          Nothing: () => dissoc(this.props.id, before),
          Just: tags => assoc(this.props.id, assoc("enabled", flag, tags), before)
        })
      LocalStorage.write('tags-filter', next)
    })
  }

  componentWillUnmount() {
    Dispatcher.unsubscribe(this.systemtagstoken)
  }

  componentWillReceiveProps(nextProps) {
    this.setState({editLayout: nextProps.editMode})
  }

  getChildren() {
    let props = {...this.props}
    let children = []
    this.newchildren = []
    if (props.hasOwnProperty('dashlets') && Array.isArray(props.dashlets)) {
      children = props.dashlets.map(properties => {
        let {x,y,width,height, ...element} = properties
        element.updateDashboard = this.updateDashboard.bind(this)
        element.parentId = props.id
        let comp = Factory.builder(element)
        let {minHeight,minWidth,...cprops} = comp.props
        let datagrid = {w: width, h: height, x: x, y: y}
        const enabled = !element.enabled ? " not-ready" : " ready"
        let e = React.createElement('div',{className: 'grid-stack-item'+enabled, id: element.id, key: element.id,'data-grid': datagrid},comp)
        return (e)
      })
    } else {
      children = props.children
    }
    return children
  }

  getLayout() {
    let local = LocalStorage.read('custom-layout') || {}
    let customLayout = local.hasOwnProperty(this.props.id) ? local[this.props.id] : {}
    return JSON.parse(JSON.stringify(customLayout))
  }

  render() {
      return (
        <div ref="dashboard" className='dashboard-container'>
        {(this.state.paymentType == 'pending') && <Alert
      message="Please, verify your subscription plan or check any payment issue."
      type="error" />}

          <DashboardHeader {...this.props} metrics={this.state.metrics} lastUpdate={this.state.lastUpdate} />
          <Row>
            <Col sm={12}>
            <GridLayout onLayoutChange={this.onLayoutChange} layouts={this.getLayout()} onResizeStop={this.onResizeStop}
            isDraggable={this.state.editLayout} isResizable={this.state.editLayout}
            breakpoints={{lg: 1200, md: 996, sm: 768, xs: 480, xxs: 0}}
            rowHeight={50} margin={[30,30]} cols={{lg: 12, md: 12, sm: 2, xs: 2, xxs: 2}} className="grid-stack" getChildren={this.getChildren}/>
            </Col>
          </Row>
        </div>
      )
  }
}

class GridLayout extends React.Component {
  constructor(props) {
    super(props)
    this.grid = null
  }

  shouldComponentUpdate(nextProps, nextState) {
    if (this.props.isDraggable !== nextProps.isDraggable ||
      this.props.isResizable !== nextProps.isResizable ||
      JSON.stringify(this.props.layouts) !== JSON.stringify(nextProps.layouts)) {
      console.log('dashboard willupdate', JSON.stringify(this.props.layouts),JSON.stringify(nextProps.layouts) )
      return true
    }
    return false
  }
  render() {
     return (<ResponsiveReactGridLayout ref={(grid) => { this.grid = grid }} onResizeStop={this.props.onResizeStop} onLayoutChange={this.props.onLayoutChange} layouts={this.props.layouts}
      isDraggable={this.props.isDraggable} isResizable={this.props.isResizable}
      rowHeight={this.props.rowHeight} margin={this.props.margin} cols={this.props.cols} className={this.props.className}>
      {this.props.getChildren()}
      </ResponsiveReactGridLayout>)
  }
}
