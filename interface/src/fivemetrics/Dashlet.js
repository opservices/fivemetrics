import React from 'react'
import PropTypes from 'prop-types'
import { assocPath, dissocPath,assoc,dissoc, path } from "ramda"
import { Factory, Connection } from 'fivemetrics/utils'
import classNames from 'classnames'
import * as Maybe from "fivemetrics/utils/Maybe"
import LocalStorage from "./utils/LocalStorage"
import { assign } from "./utils/Support"
import { TagPeriod } from 'fivemetrics/fragments'
import { Period } from 'fivemetrics/utils'

import {
  Grid,
  Row,
  Col,
  Panel,
  PanelBody,
  PanelContainer,
  Dispatcher,
  Tag
} from '@sketchpixy/rubix'

export default class Dashlet extends React.Component {

	constructor(props) {
		super(props)
		this.handleClick = this.handleClick.bind(this)
		this.getElement = this.getElement.bind(this)
		this.handlerError = this.handlerError.bind(this)
		this.updateData = this.updateData.bind(this)
		this.renamePeriod = this.renamePeriod.bind(this)
		this.updatePool = []
		this.timer = null
		this.data = null
		this.mounted = false
		this.main = null
	    const tags = Maybe
	      .fromEmpty(LocalStorage.read('tags-filter'))
	      .chain(Maybe.pluck([props.parentId]))
	      .chain(x => x.enabled ? Maybe.Just(x) : Maybe.Nothing())
		this.state = { currentPeriod:null,inError:false, tags, period: null, doResize: false, shouldUpdate: props.shouldUpdate || false}
		this.enableUpdate = false

	}

	shouldComponentUpdate() {
		return false
	}

	static get defaultProps() {
	    return {
	     handleResize: (newSize, oldSize) => {
	     	this.forceUpdate()
	     },
	     onShouldUpdate: () => {return false}
	    }
	}

	componentWillUnmount() {
	    clearTimeout(this.timer)
		Dispatcher.unsubscribe(this.token);
		Dispatcher.unsubscribe(this.systemTagsToken)
	}

	componentWillMount() {
		this.token = Dispatcher.subscribe('doResize', this.subscriber.bind(this));
	}

	subscriber(msg, data) {
		//this.forceUpdate()
	}

	getSize() {
		if (this.dashlet) {
			return this.dashlet.parentElement.getBoundingClientRect()
		}
		return null
	}
	componentWillReceiveProps(nextProps) {
		console.log(nextProps)
		this.forceUpdate()
	}

	getElement(tmpInterval) {
		const props = {...this.props};
		let newHeight = '100%'
		let newWidth = '100%';

		if (this.getSize()) {
			const h = this.getSize().height
			newHeight = h - 70
			//newWidth = this.getSize().width
		}

		this.data = this.data || {}

		if (props.element.style && props.element.style.empty_as_zero) {
			if (Object.keys(this.data).length === 0) {
				this.data = {value: 0, max: 0, maximum: 0, minimum: 0}
			} else if (!this.data.hasOwnProperty('value')) {
				this.data.value = 0
			}
		}

	    const element = {...props.element,height: newHeight,width: newWidth, dp: this.data || props.element.dp, period: this.state.period}
	    let child = Factory.builder(element)
	    this.defaultProps = {}

		if (child.props.interval && props.enabled) {
			let interval = 1
			if (this.timer) {
				clearTimeout(this.timer)
				interval = child.props.interval
			}
			this.timer = setTimeout(this.updateData.bind(this,child.props), (tmpInterval || interval) * 1000)
		}

		return child
	}

	renamePeriod(p) {
		let n = Period.find((v)=>{
			return (v.value == p) ? true : false
		})
		return (n) ? n.name : p
	}

	updateData(props) {
	    let ds = this.state.tags
	      .map(o => assign(o.system, o.custom))
	      .cata({
	        Nothing: () => props.ds,
	        Just: xs => {
	        	props.ds.forEach((nds) => {
	        		let _p = {...path(['query', 'query', 'query', 'filter'],nds)}
	        		let _xs = {...xs}
	        		Object.keys(_p).forEach((v) => {
	        			delete _xs[v]
	        		})
	        		xs = Object.assign({},_xs,_p)
	        	})
				return props.ds.map(assocPath(['query', 'query', 'query', 'filter'], xs))
	        },
	      })

	    if (this.state.period) {
		    ds = ds.map((nds) => {
        		let _p = [...path(['query', 'periods'],nds)]
        		_p.unshift(this.state.period)

        		let groupBy = Period.find((v)=>{
					return (v.value == this.state.period) ? true : false
				}).groupBy || 'hour'
        		nds = assocPath(['query', 'query', 'groupBy'], {time: groupBy}, nds)
        		return assocPath(['query', 'periods'], _p, nds)

        	})
	    }

		props.dataProviderService((result)=>{
			this.data = result.data
			this.props.updateDashboard({lastUpdate: new Date()})
			if (Object.keys(this.data).length === 0) { //return on empty data
				this.handlerError('Empty Object')
				return
			}

			if (this.mounted) {
				this.setState({currentPeriod: (result.period) ? result.period : '', inError: false})
				this.forceUpdate()
			}
		}, ds, this.handlerError)
	}

	handlerError(e) {
		if (this.mounted) {
			this.setState({inError: (this.props.element.style && this.props.element.style.empty_as_zero) ? false : true})
			this.forceUpdate()
		}
	}

	handleClick(e) {
		 this.setState({ period: e }, this.getElement.bind(this, 1))
	}


  componentDidMount() {
    this.mounted = true

    this.systemTagsToken = Dispatcher.subscribe('systemTags', (tags, apply) => {
      if (apply) {
        this.setState({ tags }, this.getElement.bind(this, 1))
      } else {
        this.setState({ tags: Maybe.Nothing() }, this.getElement.bind(this, 1))
      }
    })
  }

	render() {
		const props = {...this.props}
		let tags = props.tags || {}
		const tagsComponents = []
		if (!tags.hasOwnProperty('period')) {
			tags.currentPeriod = this.renamePeriod(this.state.currentPeriod)
		}

		for (let tag in tags) {
			let tagType = (tag == 'currentPeriod' || tag == 'period') ? classNames('tags','period-tag') : classNames('tags','filter-tag')
			tagsComponents.push((this.props.selectableperiod && tag == 'currentPeriod') ?
				<TagPeriod className={classNames('tags','period-tag')} key={`period-filter-${tags[tag]}`} label={tags[tag]} onClick={this.handleClick}/> :
				<Tag className={tagType} key={`${tag}_${tags[tag]}`} >{tags[tag]}</Tag>)
		}

		return (
			<div className={classNames('grid-stack-item-content',props.element.type,(this.state.inError) ? 'problem' : '')} ref={(el) => {this.dashlet = el}} style={{flex:1}}>
				<div className="filter-group">{tagsComponents}</div>
	            <h4 className="dashlet-title">{this.props.title}</h4>
	            <div ref={(m) => { this.main = m }} className="dashlet-content">{this.getElement()}</div>
            </div>
		)
	}
	shouldComponentUpdate() {
		return false
	}
}


Dashlet.propTypes = {
  autoPosition: PropTypes.bool,
  children: PropTypes.node,
  height: PropTypes.number,
  id: PropTypes.string.isRequired,
  maxHeight: PropTypes.number,
  maxWidth: PropTypes.number,
  minHeight: PropTypes.number,
  minWidth: PropTypes.number,
  width: PropTypes.number,
  x: PropTypes.number,
  y: PropTypes.number,
  realHeight: PropTypes.number,
  realWidth: PropTypes.number
}
