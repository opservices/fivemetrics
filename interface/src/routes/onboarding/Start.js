import React from 'react'
import classNames from 'classnames'
import { Config } from 'config'
import { MainIcon } from 'fivemetrics/fragments'
import { StatusIcon } from 'fivemetrics'
import { Theme, Color, Connection } from 'fivemetrics/utils'
import Wizard from './Wizard'

import Step1 from './Step1'
import Step2 from './Step2'
import Step3 from './Step3'

import { Row, Grid, Col } from 'react-bootstrap'

const collectionList = [
  {
    id: 'aws.manager',
    enabled: true,
    icon: 'aws/aws',
    titleTop: 'AWS',
    titleSub: 'Manager',
    description: 'A powerful set of views about your AWS virtual machines infrastructure',
    content: [
    {name:'EC2',multiregion:true,icon:'aws/ec2',datasource:'aws.ec2'},
    {name:'ELB',multiregion:true,icon:'aws/elb',datasource:'aws.elb'},
    {name:'Autoscaling',multiregion:true,icon:'aws/autoscaling',datasource:'aws.autoscaling'},
    {name:'EBS',multiregion:true,icon:'aws/elb',datasource:'aws.ebs'},
    {name:'S3',multiregion:true,icon:'aws/s3', datasource: 'aws.s3'},
    {name:'Glacier',multiregion:true,icon:'aws/glacier',datasource: 'aws.glacier'},
    {name:'CloudWatch',multiregion:false,icon:'aws/cloudwatch',datasource:'aws.cloudwatch', parameters: [
      {
        name: "aws.cloudwatch.namespace",
        value: "AWS/Billing"
      },
      {
        name: "aws.cloudwatch.metric_name",
        value: "EstimatedCharges"
      },
      {
        name: "aws.cloudwatch.unit",
        value: "None"
      },
      {
        name: "aws.cloudwatch.dimensions",
        value: [
          {
            name: "Currency",
            value: "USD"
          }
        ]
      },
      {
        name: "aws.cloudwatch.statistics",
        value: [
          "Maximum",
          "Minimum",
          "SampleCount",
          "Sum",
          "Average"
        ]
      },
      {
        name: "aws.region",
        value: "us-east-1"
      }
    ]
  }
    ],
    dashboard: '/app/aws/manager/ec2',
    selected: false
  },{
    id: 'cloud.next1',
    enabled: false,
    icon: 'aws/aws_old',
    titleTop: 'New',
    titleSub: 'Collection',
    description: 'Soon...',
    content: [],
    dashboard: '/app/aws/manager/ec2',
    selected: false
  },
  {
    id: 'cloud.next2',
    enabled: false,
    icon: 'aws/aws_old',
    titleTop: 'New',
    titleSub: 'Collection',
    description: 'Soon...',
    content: [],
    dashboard: '/app/aws/manager/ec2',
    selected: false
  }
]


class Main extends React.Component {
  constructor(props) {
    super(props)
    this.state = {validated: false, hide: false, username: 'Cap', hideHeader: false}
    this.getStore = this.getStore.bind(this)
    this.updateStore = this.updateStore.bind(this)
    this.serviceStep2 = this.serviceStep2.bind(this)
    this.validateNextStep = this.validateNextStep.bind(this)
    this.hideNavigation = this.hideNavigation.bind(this)
    this.hideHeader = this.hideHeader.bind(this)
    this.serviceStep3 = this.serviceStep3.bind(this)
    this.formStore = {config:{}}
  }
  componentDidMount() {
    document.querySelector("#bg-effect").className += " bg-with-effect"
  }
  componentWillMount() {
      Connection.currentAccount((data) => {
        this.updateStore({account: data})
        this.setState({username: data.username})
      }, (error) => {})
  }
  componentWillUnmount() {}

  getStore() {
    return this.formStore;
  }

  updateStore(update) {
    this.formStore = Object.assign({},this.getStore(),update)
  }

  validateNextStep(validated) {
    this.setState({validated: validated, hide: this.state.hide})
  }

  hideNavigation(hide) {
    this.setState({validated: this.state.validated, hide: hide})
  }

  hideHeader(hide) {
    this.setState({hideHeader: hide})
  }

  serviceStep2(callback,errorCallback) { //account
    let store = {...this.getStore()}
    let configuration = [{name: 'aws.key',value: store.config['aws.key']},{name: 'aws.secret',value: store.config['aws.secret']}]
    Connection.createConfiguration(configuration,callback, (error) => {})
  }

  serviceStep3(callback, query, errorCallback, create = false) {
    let uri = '/onboarding/discovery'
    uri = (create) ? uri : uri + '/' + query
    let config = {uri: uri,query: query, method: (create) ? 'post' : 'get'}
    Connection.genericService((data) => {
      callback(data)
    },config, errorCallback)
  }

  render() {
    const steps =
    [
      {name: 'Step1', component: <Step1 {...this.props} collectionList={collectionList} getStore={this.getStore} validateNextStep={this.validateNextStep} updateStore={this.updateStore} />},
      {name: 'Step2', component: <Step2 {...this.props} serviceStep2={this.serviceStep2}  getStore={this.getStore} hideNavigation={this.hideNavigation} validateNextStep={this.validateNextStep} updateStore={this.updateStore} />},
      {name: 'Step3', component: <Step3 {...this.props} serviceStep3={this.serviceStep3} hideHeader={this.hideHeader} getStore={this.getStore} hideNavigation={this.hideNavigation} validateNextStep={this.validateNextStep} updateStore={this.updateStore} />}
    ]

    return (
        <Grid>
        <Row>
          <Col>
            <div className={classNames('onboarding',this.state.hideHeader ? 'invisible' : '')} style={{marginLeft: 15,marginBottom: 30}}>
              <MainIcon icon='star' className="assistent-star" style={{width: 35, height: 40, float: 'left', marginLeft: -35}}/>
              <div className='text-left title-screen' style={{marginTop: 0}}>Welcome, {this.state.username}.</div>
              <div className='text-left text-content'>O captain, my captain! Our marvelous trip is almost ready to start</div>
            </div>
          </Col>
        </Row>
        <Wizard nextButtonCls={"btn btn-prev btn-lg pull-right btn-lightgreen btn-outlined btn-default " + ((this.state.validated) ? 'gradient-button' : 'disabled-button')}
            steps={steps}
            preventEnterSubmission={true}
            nextTextOnFinalActionStep={"Find Services"}
            showNavigation={!this.state.hide}
            showSteps={!this.state.hide}
           />
      </Grid>
    )
  }
}

export default class OnBoarding extends React.Component {
  constructor(props) {
    super(props)
    this.theme = Theme.load(Config.Theme)
  }

  render() {
    return <Main theme={this.theme}/>
  }
}
