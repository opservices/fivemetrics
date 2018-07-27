import React from 'react'
import classNames from 'classnames'

import {  Navbar, Nav, NavItem, Icon, Grid, Row, Col, Dispatcher } from '@sketchpixy/rubix'

import { SidebarBtn, ProfileMenu } from 'fivemetrics/utils'

import { Payment } from 'fivemetrics'

export default class Header extends React.Component {
  constructor(props) {
    super(props)
    this.state = {header: null}
  }

  render() {
    let {showSidebarBtn, ...props} = this.props
    return (
      <Grid id='navbar' {...props}>
        <Row>
          <Col xs={12}>
            <Navbar fixedTop fluid id='main-header'>
              <Row>
                <Col xs={3} sm={2}>
                  {/*sidebarBtn*/}
                </Col>
                <Col xs={6} sm={4}>
                </Col>
                <Col xs={3} sm={6} collapseRight className='text-right settings' style={{paddingTop: 5, paddingRight:25}}>
                  <Payment className="btn-group"/>
                  <ProfileMenu />
                </Col>
              </Row>
            </Navbar>
          </Col>
        </Row>
      </Grid>
    );
  }
}
