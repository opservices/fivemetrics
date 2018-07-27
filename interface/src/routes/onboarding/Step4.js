import React from 'react'
import classNames from 'classnames'
import { Row,
  Grid,
  Panel,
  Table } from 'react-bootstrap'

import {
  Col,
  FormControl,
  PanelContainer,
  Nav,
  PanelBody,
  PanelHeader,
  Icon,
  Form,
  HelpBlock,
  FormGroup,
  InputGroup,
  ControlLabel,
  Progress,
  Alert
} from '@sketchpixy/rubix'

import { StatusIcon } from 'fivemetrics'

export default class Step4 extends React.Component {
  constructor(props) {
    super(props)
    this.createContent = this.createContent.bind(this)
    this.state = {value: 0}
  }
  componentDidMount() {

  }

  createContent(contentList) {
    return contentList.map(name => {
        let value = Math.random() * 100
        let icon = 'EC2'
        return (
          <Row key={value}>
            <Col>
                <div className='text-center' style={{margin: '-45px 10px 30px 10px'}}>
                  <StatusIcon width={64} height={64} className='opaque-fill' icon={icon} />
                </div>
            </Col>
          </Row>
        )
    })

  }
  render() {
    const value = 60
    return (
        <Row>
          <Col xs={12} sm={6} smOffset={3}>
            <Panel className='active' style={{marginTop:30}}>
              <Row style={{height:160}}>
                <Col>
                  <div className="form-header" style={{zIndex: 99, width: '100%'}}>
                      <div className='text-center' style={{marginTop: -90}}>
                        {/*<StatusIcon height={128} className='opaque-fill' icon={this.props.icon} />*/}
                        {(this.props.icon != 'aws/aws') ? (<StatusIcon height={128} className='opaque-fill' icon={this.props.icon} />) : (<span className='icon-fontello-gauge icon-onboarding' style={{fontSize: '200px', lineHeight:'200px'}}/>)}
                      </div>
                      <div className='text-center'>
                        <div className='title-top' style={{fontSize:24,marginBottom:-20}}>{this.props.titleTop}</div>
                        <div className='title-sub'> {this.props.titleSub}</div>

                      </div>
                  </div>
                </Col>
              </Row>
              {this.createContent(this.props.content)}
            </Panel>
            <div>You'll receive a email with a summary of your AWS computing infrastructure</div>
          </Col>
        </Row>
    )
  }
}