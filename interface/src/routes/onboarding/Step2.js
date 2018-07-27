import React from 'react'
import {Radio, Select} from 'antd'
import {Grid, Panel, Row} from 'react-bootstrap'
import { Connection } from "fivemetrics/utils"
import { Spin } from 'antd'

import {
    Alert,
    Col,
    ControlLabel,
    Form,
    FormControl,
    FormGroup,
    HelpBlock,
    Icon,
    InputGroup,
    OverlayTrigger,
    Tooltip,
    Modal
} from '@sketchpixy/rubix'

import {StatusIcon} from 'fivemetrics'

const Option = Select.Option


export default class Step2 extends React.Component {
  constructor(props) {
    super(props)

    this.isValidated = this.isValidated.bind(this)
    this.handleChange = this.handleChange.bind(this)
    this.handleBlur = this.handleBlur.bind(this)

    this.handleValidationFunction = this.handleValidationFunction.bind(this)
    this.checkCredentials = this.checkCredentials.bind(this)
    this.validationCheck = this.validationCheck.bind(this)
    this.showValidationMessage = this.showValidationMessage.bind(this)
    this.showValidationClass = this.showValidationClass.bind(this)
    this.isDataValid = this.isDataValid.bind(this)

    this.timeout = null
    this.changed = {}

    this.formData = {
      'aws.region': {source: 'value', validation: this.validNotNull, success: "Valid region", error: 'Invalid region'},
      'aws.key': {source: 'value', validation: this.validNotNull, success: "Valid key", error: 'Invalid key'},
      'aws.secret': {source: 'value', validation: this.validNotNull, success: "Valid secret", error: 'Invalid secret'}
    }
    this.tmpData = {'aws.region': {value: ['us-east-1'], validated: true}, 'aws.key': {validated: false}, 'aws.secret': {validated: false}}
    this.docurl = 'https://fivemetrics.io/files/FiveMetrics-AWS-access-key-creation.pdf'
    this.state = {isKeySecretError: false, error: {}, loading: false}

    this.selectedCollection = this.props.getStore().collection

    this._isMounted = false

  }

  validateEmail(value) {
    return /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/.test(value)
  }

  validNotNull(value) {
     return (typeof value == 'undefined' || value == "" || String(value).length <= 0) ? false : true
  }

  isValidated() {
    return new Promise((resolve, reject) => {
      if (this.isDataValid()) {
          this.checkCredentials(()=>{
              this.props.serviceStep2((data) => {
              if (data.error) {
                data.fields.forEach((field) => {
                  let n = {}
                  let {id,msg} = {...field}
                  n[id] = Object.assign(this.state[id],{validated: false, msg: msg})
                  this.setState(Object.assign(this.state, n))
                })
                reject()
              } else {
                resolve()
              }
            },(error)=>{
              clearTimeout(this.timeout)
            })
            },reject)
      } else {
        reject()
      }
    })
  }

  handleValidationFunction(id,value) {
    if (this.formData.hasOwnProperty(id) && this.formData[id].validation){
      return this.formData[id].validation(value)
    } else {
      return true
    }
  }

  showValidationMessage(id) {
    let tooltip = (<Tooltip id="tooltip-hide" style={{display:'none'}}/>)
    if (this.state.hasOwnProperty(id) && this.formData[id].validation && typeof this.state[id] != 'undefined' && this.changed.hasOwnProperty(id)){
      let statusClass = (this.state[id].validated) ? 'success' : 'danger'
      let statusMsg = (this.state[id].validated) ? this.formData[id].success : (this.state[id].hasOwnProperty('error') ? this.state[id].error : this.formData[id].error)

      if (this.state.isKeySecretError) {
        let errordata = this.state.error
        let erromsg
        statusMsg = Object.keys(errordata).map((error) => {
          erromsg = errordata[error]
          return String(error).toUpperCase()
        }).join(", ") + " - " + erromsg
      }

      statusMsg = (!this.state[id].validated && this.state[id].hasOwnProperty('msg')) ? this.state[id].msg : statusMsg
      if (!this.state[id].validated) {
        tooltip = (<Tooltip id="tooltip-danger" placement="top">{statusMsg}</Tooltip>)
      }
    }
    return (tooltip)
  }

  showValidationClass(id) {
    if (this.state.hasOwnProperty(id) && this.formData[id].validation && typeof this.state[id] != 'undefined' && this.changed.hasOwnProperty(id)){
       return (this.state[id].validated) ? 'success' : 'error'
    } else {
      return null
    }
  }

  handleChange(e) {
    let id, value
    if (Array.isArray(e)) {
      id= 'aws.region'
      value = e
    } else {
      this.props.updateStore({isKeySecretError: false})
      id = e.currentTarget.id
      value = e.currentTarget.value

    }
    this.changed[id] = true
    clearTimeout(this.timeout)
    this.tmpData[id] = {value: value, validated: this.handleValidationFunction(id,value)}
    this.timeout = setTimeout(()=>{
      clearTimeout(this.timeout)
      this.validationCheck()
    },1000)

  }

  isDataValid() {
    let validated = false
    Object.keys(this.formData).every((id) => {
        if (this.state.hasOwnProperty(id) && this.state[id].validated) {
          validated = true
          return true
        } else {
          validated = false
          return false
        }
    })
    return validated
  }
  validationCheck() {
    let validated = false
    if (this._isMounted) {
      this.setState(Object.assign(this.state, this.tmpData), () => {

        validated = this.isDataValid()

        if (validated) {
          let config = {}

          Object.keys(this.state).forEach((k) => {
            config[k] = this.state[k].value
          })
          this.props.updateStore({config: config})
        }
        this.props.validateNextStep(validated)
      })
    }

    return validated
  }

  handleBlur(e) {
      this.validationCheck()
  }

  checkCredentials(onSuccess, onError) {
    let key = this.tmpData['aws.key']
    let secret = this.tmpData['aws.secret']

    this.setState({loading: true})

    if (! key['value'] || ! secret['value']) {
        this.setState({loading: false})
        key['validated'] = false
        secret['validated'] = false
        this.validationCheck()

        onError()
        return
    }

    const config = {
        uri: '/onboarding/check-credentials/',
        method: 'post',
        query: {
          "aws.key": key['value'],
          "aws.secret":  secret['value']
        }
    }

    Connection.genericService(
        (data, rawResponse) => {
            this.setState({loading: false})
            onSuccess()
        },
        config,
        (error) => {
            this.setState({loading: false, isKeySecretError: true, error: error.response.data.errors})
            this.changed['aws.key'] = true
            this.tmpData['aws.key'] = {value: key, validated: this.handleValidationFunction('aws.key',"")}
            this.changed['aws.secret'] = true
            this.tmpData['aws.secret'] = {value: secret, validated: this.handleValidationFunction('aws.secret',"")}
            this.timeout = setTimeout(()=>{
              clearTimeout(this.timeout)
              this.validationCheck()
            },1000)
            onError()
        }
    )
  }

  componentDidMount() {
    this._isMounted = true
  }
  componentWillMount() {
    this._isMounted = false
    this.props.validateNextStep(false)
  }

  componentWillUnmount() {
    clearTimeout(this.timeout)
  }

  render() {
    let email = (this.props.getStore().account && this.props.getStore().account.email) ? this.props.getStore().account.email : 'Cap'

    return (
        <Row>

          <Col xs={12} sm={6} smOffset={3}>
            <Panel className='active extract-border-header'>
              <Row style={{height:60}}>
                <div className="form-header" style={{marginTop: 0, zIndex: 99, width: '100%'}}>
                      <Col xs={12} style={{float: 'left',marginTop: -75, marginLeft: -15}}>
                      {(this.selectedCollection.icon != 'aws/aws') ? (<StatusIcon height={60} className='opaque-fill' icon={this.selectedCollection.icon} />) :
                      (<span className='icon-fontello-gauge icon-onboarding' style={{fontSize: '100px',lineHeight: '120px', textAlign: 'left'}}/>)}
                      </Col>
                      <Col xs={12} className='text-center' style={{height: 80}}>
                        <span className='title-top' style={{fontSize:24}}>{this.selectedCollection.titleTop}</span>
                        <span className='title-sub' > {this.selectedCollection.titleSub}</span>
                        <div className='text-content' >{this.selectedCollection.content.map(v => v.name).join(' | ')}</div>
                      </Col>
                </div>
              </Row>
              <Row>
                <Col>
                  <Form>
                  <FormGroup controlId='email'>
                      <ControlLabel>Email Address</ControlLabel>
                      <span>{email}</span>
                    </FormGroup>

                    <FormGroup controlId="aws.region" className='fivemetrics-antd-theme'>
                      <ControlLabel>AWS Region</ControlLabel>

                      <Select mode="multiple" placeholder="Please select a AWS Region" defaultValue={['us-east-1']} onChange={this.handleChange} style={{ width: '100%'}}
                      filterOption={(input, option) => option.props.children.toLowerCase().indexOf(input.toLowerCase()) >= 0}>
                       <Option key="us-east-1">US East (N. Virginia) - us-east-1</Option>
                        <Option key="us-east-2">US East (Ohio) - us-east-2</Option>
                        <Option key="us-west-1">US West (N. California) - us-west-1</Option>
                        <Option key="us-west-2">US West (Oregon) - us-west-2</Option>
                        <Option key="sa-east-1">South America (São Paulo) - sa-east-1</Option>
                        <Option key="ap-northeast-1">Asia Pacific (Tokyo) - ap-northeast-1</Option>
                        <Option key="ap-northeast-2">Asia Pacific (Seoul) - ap-northeast-2</Option>
                        <Option key="ap-south-1">Asia Pacific (Mumbai) - ap-south-1</Option>
                        <Option key="ap-southeast-1">Asia Pacific (Singapore) - ap-southeast-1</Option>
                        <Option key="ap-southeast-2">Asia Pacific (Sydney) - ap-southeast-2</Option>
                        <Option key="ca-central-1">Canada (Central) - ca-central-1</Option>
                        <Option key="eu-central-1">EU (Frankfurt) - eu-central-1</Option>
                        <Option key="eu-west-1">EU (Ireland) - eu-west-1</Option>
                        <Option key="eu-west-2">EU (London) - eu-west-2</Option>
                        <Option key="eu-west-3">EU (Paris) - eu-west-3</Option>
                      </Select>

                      <HelpBlock className='text-right'><i>AWS regions where your instances are located.</i></HelpBlock>
                    </FormGroup>

                    <Spin spinning={this.state.loading} size="large">
                    <FormGroup controlId='aws.key' validationState={this.showValidationClass('aws.key')}>
                      <ControlLabel>AWS Key <b>(Read Only Access)</b></ControlLabel>
                      <OverlayTrigger placement="top" overlay={this.showValidationMessage('aws.key')}>
                      <InputGroup>
                        <FormControl type='text' placeholder='ABCDEF-EXAMPLE-KEY' onChange={this.handleChange} onBlur={this.handleBlur} inputRef={(field) => this.keyValue = field} />
                        <InputGroup.Addon>
                          <Icon glyph='icon-fontello-key' />
                        </InputGroup.Addon>
                      </InputGroup>
                      </OverlayTrigger>
                    </FormGroup>
                    <FormGroup controlId='aws.secret' validationState={this.showValidationClass('aws.secret')}>
                      <ControlLabel>AWS Secret</ControlLabel>
                       <OverlayTrigger placement="top" overlay={this.showValidationMessage('aws.secret')}>
                      <InputGroup>
                        <FormControl type='text' placeholder='aBcDeFgHkLmNoPqQrsTUvWxYz-EXAMPLE-SECRET' onChange={this.handleChange} onBlur={this.handleBlur} inputRef={(field) => this.secretValue = field}/>
                        <InputGroup.Addon>
                          <Icon glyph='icon-fontello-key' />
                        </InputGroup.Addon>
                      </InputGroup>
                      </OverlayTrigger>
                      <HelpBlock className='text-right' style={{color: '#bbb'}}><i>We need this to autodiscover and read your data. <a href={this.docurl} target="_blank">Need help?</a></i></HelpBlock>
                    </FormGroup>
                    </Spin>
                  </Form>
                </Col>
              </Row>
            </Panel>
          </Col>

        </Row>
    )
  }
}