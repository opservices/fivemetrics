import React from 'react'
import classNames from 'classnames'
import {browserHistory} from 'react-router'
import { Row,
  Grid,
  Panel,
  Table } from 'react-bootstrap'
import { Config } from 'config'
import { Theme, Color } from 'fivemetrics/utils'
import { MainIcon } from 'fivemetrics/fragments'
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

import { StatusIcon, CircleLabel } from 'fivemetrics'

export default class Step3 extends React.Component {
  constructor(props) {
    super(props)
    this.createContent = this.createContent.bind(this)
    this.state = {complete: false}
    this.theme = Theme.load(Config.Theme)
    this.selectedCollection = this.props.getStore().collection
  }

  componentWillMount() {
    this.props.validateNextStep(false)
    this.props.hideNavigation(true)
    this.props.hideHeader(true)
  }

  componentDidMount() {
    const content = this.selectedCollection.content
    let ds = []
    let store = {...this.props.getStore()}
    let base_parameters = [{name: 'aws.key', value: store.config['aws.key']}, {name: 'aws.secret', value: store.config['aws.secret']}]
    let regions = store.config['aws.region'] || []
    regions = regions.map((e) => {return {name: 'aws.region', value: e}})

    content.forEach((e) => {

      let parameters = e.parameters || []
      parameters = parameters.concat(base_parameters)

      if (e.multiregion) {
        regions.forEach((r) => {
            ds.push({dataSource: {name: e.datasource}, parameters:  parameters.concat([r])})
        })
      } else {
        ds.push({dataSource: {name: e.datasource}, parameters:  parameters})
      }

      let n = {}
      n[e.name] = {value: 100, id: 0, complete: false}
      this.setState(Object.assign(this.state,n))
    })

    this.props.serviceStep3((d)=>{
       let complete = false
       this.interval = setInterval(() =>{
        this.props.serviceStep3((e) => {
          //console.log(e)
          if (e.status == 'unknown' || e.status == 'finished') {
            complete = true
          }
        },d.id,this.errorCallback, false)
        if (complete) {
            clearInterval(this.interval)
            this.setState(Object.assign(this.state,{complete:true}))
            this.timeout = setTimeout((e) =>{
              clearTimeout(this.timeout)
              //console.log('redirect',this.selectedCollection.dashboard)
              browserHistory.replace(this.selectedCollection.dashboard)
            },14000)
          }
      },3000)
    },ds, this.errorCallback, true)

  }

  errorCallback(error) {
    //console.log(error)
  }

  componentWillUnmount() {
    clearInterval(this.interval)
    clearTimeout(this.timeout)
  }

  createContent(contentList) {
    return contentList.map(e => {
        const theme = this.theme
        const value = (this.state[e.name]) ? this.state[e.name].value : 0
        return (
          <Row key={e.name}>
            <Col>
                <div className='text-left' style={{width:64, float: 'left', margin: '-45px 20px 10px 0px'}}>
                  <StatusIcon style={{width:64, height:64}} className='opaque-fill' icon={e.icon} />
                </div>
                <div>
                  <div className='text-content'>
                    <span className="text-left" style={{width: '100%', position:'absolute'}}>{e.name}</span><span className="text-right" style={{display:'block',with:'100%'}}>{value} inst.</span>
                  </div>
                  <ColoRangedProgress value={value} range={theme.gradientColorDefault} />

                </div>
            </Col>
          </Row>
        )
    })
  }
  render() {
    const value = 60
  if (this.state.complete) {
      return (
        <Row>
        <h1 style={{textAlign: 'center'}} className="normalcase">Take your seat, we are redirecting you</h1>
          <Col xs={12} sm={6} smOffset={3}>
            <Panel className='flat' style={{marginTop:30,marginBottom:-25,backgroundColor: 'transparent'}}>
              <Row style={{width: 280, margin: '0 auto'}}>
                <Col>
                  <CircleLabel boxSize={440} stroke={1} disableLineHeight={true} style={{height:200}}>
                   <div className="form-header" style={{zIndex: 99}}>
                     <div className='text-center' style={{position:'absolute', width: 272, marginTop: 62}}>
                       <MainIcon icon='star' className="assistent-star animation-satelite" style={{width: 35, height: 40, margin: '0px auto'}}/>
                    </div>
                    <div className='text-center'>
                      {(this.selectedCollection.icon != 'aws/aws') ? (<StatusIcon height={90} className='opaque-fill' icon={this.selectedCollection.icon} />) :
                          (<span className='icon-fontello-gauge icon-onboarding' style={{fontSize: '120px',lineHeight: '120px', textAlign: 'center'}}/>)}
                    </div>
                    <div className='text-center'>
                      <span className='title-top' style={{fontSize:24}}>{this.selectedCollection.titleTop}</span>
                      <span className='title-sub'> {this.selectedCollection.titleSub}</span>
                    </div>
                  </div>
                 </CircleLabel>
                </Col>
              </Row>
             <Row style={{width: 280, margin: '0 auto'}}>
              <Col>
                <div style={{display: 'flex',margin:'20% auto 20% auto'}}>
                </div>
              </Col>
             </Row>
             <Row style={{marginTop:-40}}>
              <Col>
              <h2 className="normalcase" style={{textAlign: 'center'}}>Please take note: Your data may take some time to be displayed on dashboard</h2>
              </Col>
             </Row>
            </Panel>
          </Col>
        </Row>
    )} else {
        return (
          <Row>
            <Col xs={12} sm={6} smOffset={3}>
              <Panel className='active' style={{marginTop:30,height:450}}>
                <Row style={{height:160}}>
                  <Col>
                    <div className="form-header" style={{zIndex: 99, width: '100%'}}>
                        <div className='text-center'>
                          {(this.selectedCollection.icon != 'aws/aws') ? (<StatusIcon height={90} className='opaque-fill' icon={this.selectedCollection.icon} />) :
                          (<span className='icon-fontello-gauge icon-onboarding' style={{fontSize: '120px',lineHeight: '120px', textAlign: 'center'}}/>)}
                        </div>
                        <div className='text-center'>
                          <span className='title-top' style={{fontSize:24}}>{this.selectedCollection.titleTop}</span>
                          <span className='title-sub'> {this.selectedCollection.titleSub}</span>
                        </div>
                    </div>
                  </Col>
                </Row>
                <MainIcon icon='loader' className="" style={{width: 200, height: 200, margin: '0px auto'}}/>
                <div className='text-center'>Discovering services...</div>
              </Panel>
            </Col>
          </Row>
        )
    }
  }
}

class ColoRangedProgress extends React.Component {
  componentDidMount() {
    if (this.progress) {
      let color = new Color()
      let rgbcolor1 = color.hexToRgb(this.props.range[0])
      let rgbcolor2 = color.hexToRgb(this.props.range[1])
      this.progress.getElementsByClassName('progress')[0].style.borderColor = '#'+color.Range(this.props.value,rgbcolor1,rgbcolor2).toHex()
      this.progress.getElementsByClassName('progress-bar')[0].style.backgroundColor = '#'+color.Range(this.props.value,rgbcolor1,rgbcolor2).toHex()
    }
  }

  render() {
      return (<div ref={(r) => { this.progress = r}}><Progress value={this.props.value} srOnly /></div>)
  }
}