import React from 'react'
import classNames from 'classnames'

import { Navbar, Nav, NavItem, Icon, Grid, Row, Col } from '@sketchpixy/rubix'

import { SidebarBtn, SettingsMenu } from 'fivemetrics/utils'
import { MainIcon, DateHour } from 'fivemetrics/fragments'
import TagsConfiguration from "fivemetrics/Tags/"

class Brand extends React.Component {
  render() {
    return (
      <Navbar.Header {...this.props}>
        <Navbar.Brand tabIndex='-1'>
          <i></i>
        </Navbar.Brand>
      </Navbar.Header>
    );
  }
}

class HeaderNavigation extends React.Component {
  render() {
    var props = {
      ...this.props,
      className: classNames('pull-right', this.props.className)
    };

    return (
      <Nav {...props}>
        <NavItem className='logout' href='#'>
          <Icon bundle='fontello' glyph='off-1' />
        </NavItem>
      </Nav>
    );
  }
}


class HeaderIcon extends React.Component {
  render() {
    var props = { ...this.props };
    return (
      <div className="header-icon"></div>
    );
  }
}


export default class Header extends React.Component {
  constructor(props) {
    super(props)
  }
  render() {
    let props = { ...this.props };
    return (

            <Navbar fluid id='dashboard-header' className='dashboard-header' style={{padding: '10px 15px 0px 15px'}}>
              <Row>
                {/*<Col xs={3} id='sidebar-col-btn'>
                  <SidebarBtn visible={true}/>
                </Col>*/}
                <Col xs={12} sm={8} collapseRight className='text-right'>
                  {/*<MainIcon {...this.props} {...{ class: 'header-icon' }}/>*/}
                  <hgroup style={{'float': 'left'}}>
                    <h1>{props.title}</h1>
                    <h2>{props.description}</h2>
                  </hgroup>
                  </Col>
                  <Col xs={12} sm={2}>

                  </Col>
                  <Col xs={12} sm={2}>
                    <DateHour date={props.lastUpdate}/>
                    <div>
                      <span><SettingsMenu/></span>
                      <span style={{float: 'right',marginTop: 5}}>{this.props.enableTags && (<TagsConfiguration parentId={this.props.id} metrics={this.props.metrics} />)}</span>
                    </div>
                  </Col>
              </Row>
            </Navbar>

    );
  }
}
