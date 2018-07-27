import React from 'react'
import classNames from 'classnames'
import { Row,
  Grid,
  Panel,
  Table } from 'react-bootstrap'

import {
  Col
} from '@sketchpixy/rubix'

import { StatusIcon } from 'fivemetrics'


export default class Step1 extends React.Component {
  constructor(props) {
    super(props)
    this.handleSelection = this.handleSelection.bind(this)
    this.removeSelection = this.removeSelection.bind(this)
    this.state = {collectionList: this.props.collectionList, selectedIndex: -1}
  }

  handleSelection(collectionId) {
    let newCollectionList = [...this.props.collectionList].map((e, index) => {
      e.selected = (e.id == collectionId && this.state.selectedIndex !== index) ? true : false
      return e
    })

    let selectedIndex = newCollectionList.findIndex((e) => e.selected)

    this.props.validateNextStep((selectedIndex != -1) ? true : false)
    this.setState({collectionList: newCollectionList, selectedIndex: selectedIndex})
    this.props.updateStore({collection: newCollectionList[selectedIndex]})
  }

  isValidated() {
    let isDataValid = false

    if (this.state.selectedIndex != -1) {
      isDataValid = true
    }

    return isDataValid
  }

  renderCollection(collapseRight) {
    return (data, key) => (
      <Col sm={3} className={collapseRight ? "col-sm-collapse-right" : ""} key={key}>
        <CollectionSelection {...data} selectionCallback={this.handleSelection}/>
      </Col>
    )
  }

  removeSelection() {
    if (this.state.selectedIndex == -1) {
      let newCollectionList = [...this.props.collectionList].map((e, index) => {
        e.selected = false
        return e
      })
      this.setState({collectionList: newCollectionList})
    }
  }

  componentWillMount() {
    this.removeSelection()
  }
  render() {

    return (
      <Row>
        <Col sm={3} collapseRight>
          <div className="onboarding">
            <div className='text-left title-top' style={{marginTop: 0}}>Choose a collection to Start</div>
            <div className='text-left text-content' style={{marginTop: 20}}>Choose <i>wisely</i> and be <b>bold</b></div>
          </div>
        </Col>
        {this.state.collectionList.slice(0,-1).map(this.renderCollection(true))}
        {this.state.collectionList.slice(-1).map(this.renderCollection(false))}
      </Row>

    );
  }
}

class CollectionSelection extends React.Component {
  constructor(props) {
    super(props)
    this.handleCollectionSelect = this.handleCollectionSelect.bind(this)
  }

  handleCollectionSelect(e) {
    this.props.selectionCallback(this.props.id)
  }

  createContent(contentList) {
    return contentList.map(e => e.name).join(", ")
  }

  render() {

    return (
      <Panel key={this.props.id} onClick={this.props.enabled && this.handleCollectionSelect || null} style={{height:440}} className={(this.props.enabled) ? classNames((this.props.selected) ? 'active' : '','selectable') : 'disabled'}>
        <div className="onboarding">
          {(this.props.icon != 'aws/aws') ? (<StatusIcon style="float:'left'" height={60} icon={this.props.icon}/>) : (<span className='icon-fontello-gauge icon-onboarding' />)}
          <div className='text-center title-top' style={{margin: '0 0 -20px 0', fontSize:24}}>{this.props.titleTop}</div>
          <div className='text-center title-sub'>{this.props.titleSub}</div>
          <div className='text-center text-description'>{this.props.description}</div>
          <div className='text-center text-content' style={{marginTop:40}}>{this.createContent(this.props.content)}</div>
        </div>
      </Panel>
    );
  }
}