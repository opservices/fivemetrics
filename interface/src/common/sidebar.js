import React from 'react'

import {
  Grid, Row, Col, Icon
} from '@sketchpixy/rubix'

import {SidebarBtn, Sidebar, SidebarNav, SidebarNavItem } from 'fivemetrics/utils'


class ApplicationSidebar extends React.Component {

  render() {
    return (
      <div>
        <Grid>
          <Row>
            <Col xs={12}>
              <div className='sidebar-nav-container'>
                <div className='sidebar-header'>Dashboards</div>
                <SidebarNav style={{marginBottom: 0}} ref={(c) => this._nav = c}>
                  <SidebarNavItem glyph='icon-fontello-gauge' name='AWS Manager' opened={true}>
                    <SidebarNav>
                      <SidebarNavItem glyph='icon-feather-paper' name='EC2' href='/app/aws/manager/ec2' active={true}/>
                      <SidebarNavItem glyph='icon-feather-paper' name='Autoscaling' href='/app/aws/manager/autoscaling'/>
                      <SidebarNavItem glyph='icon-feather-paper' name='ELB' href='/app/aws/manager/elb'/>
                      <SidebarNavItem glyph='icon-feather-paper' name='EBS' href='/app/aws/manager/ebs'/>
                      <SidebarNavItem glyph='icon-feather-paper' name='Billing' href='/app/aws/manager/billing'/>
                      <SidebarNavItem glyph='icon-feather-paper' name='S3' href='/app/aws/archivist/s3'/>
                      <SidebarNavItem glyph='icon-feather-paper' name='Glacier' href='/app/aws/archivist/glacier'/>
                      <SidebarNavItem glyph='icon-feather-paper' name='Reserved Instances' href='/app/aws/manager/reserves'/>
                    </SidebarNav>
                  </SidebarNavItem>
                </SidebarNav>
              </div>
            </Col>
          </Row>
        </Grid>
      </div>
    );
  }
}

export default class SidebarContainer extends React.Component {

  render() {
    return (
      <div id='sidebar'>
        <div id='sidebar-btn-container'>
          <div id='sidebar-small-logo-container'>
            <img className='img-logo' src={`/imgs/logo.svg`} />
          </div>
          <SidebarBtn visible={true} iconOpen={<Icon bundle='fontello' glyph='th-list-5' />} iconClose={<Icon bundle='fontello' glyph='cancel-7' />}/>
        </div>
        <div id='sidebar-container'>
          <div id='sidebar-logo-container'>
            <img className='img-logo-full' src={`/imgs/logo-full.svg`} />
          </div>
          <Sidebar sidebar={0}>
            <ApplicationSidebar />
          </Sidebar>
        </div>
      </div>
    );
  }
}
