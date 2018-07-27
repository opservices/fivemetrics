import React from 'react'
import classNames from 'classnames'
import { Menu, Dropdown, Button, Icon, message } from 'antd'
import { Period } from 'fivemetrics/utils'

export default class TagPeriod extends React.Component {
  constructor(props) {
    super(props)
    this.handleMenuClick = this.handleMenuClick.bind(this)
  }

  handleMenuClick(e) {
    this.props.onClick(e.key)
  }

  render() {

    const menu = (
      <Menu onClick={this.handleMenuClick}>{Period.filter((p)=>p.enabled).map((p)=>{
                return (<Menu.Item key={p.value}>{p.name}</Menu.Item>)
      })}</Menu>
    )
    return (
      <Dropdown overlay={menu} trigger={['click']} disabled={(this.props.label === null) ? true : false}>
        <Button style={{ marginLeft: 8 }} className={classNames(this.props.className, 'left-tag', 'period-filter')} >
        {this.props.label}<Icon type="down" />{/*<div className={classNames("main-icon-wrapper",this.props.className)} style={this.props.style}>
        <div className="header-icon" style={style}></div>
      </div>*/}
        </Button>
    </Dropdown>
    )
  }
}
