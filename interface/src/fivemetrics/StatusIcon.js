import React from 'react'
import ReactDOM from 'react-dom'
import ReactSVG from 'react-svg'
import classNames from 'classnames'
import { Config } from 'config'
import { Theme } from 'fivemetrics/utils'

export default class StatusIcon extends React.Component {
  constructor(props) {
    super(props)
    this.theme = Theme.load(Config.Theme)
  }
  render() {
    let style = { width: this.props.width || '100%',  height: this.props.height || 85}
    let svgProps = {}

    if (this.props.status) {
        svgProps = {style: {stroke: `url(#gradient-color-`+this.props.status+`)`,...style}}
    } else {
        svgProps = {style: {stroke: `url(#gradient-color-default)`,...style}}
    }

    const animationClass = (this.props.animation) ? 'animated-blur' : ''
    return (
    	<div className={classNames("status-icon-wrapper",this.props.className)}>
          <svg viewBox="0 0 0 0" width="0" height="0">
            <defs>
                <linearGradient x1="42.8471684%" y1="0%" x2="42.8471697%" y2="100%" id="gradient-color-default">
                    <stop stopColor={this.theme.gradientColorDefault[0]} offset="0%"></stop>
                    <stop stopColor={this.theme.gradientColorDefault[1]} offset="100%"></stop>
                </linearGradient>
                <linearGradient x1="42.8471684%" y1="-76.5966797%" x2="42.8471697%" y2="100%" id="gradient-color-critical">
                    <stop stopColor={this.theme.gradientColorCritical[0]} offset="0%"></stop>
                    <stop stopColor={this.theme.gradientColorCritical[1]} offset="100%"></stop>
                </linearGradient>
                <linearGradient x1="42.8471684%" y1="-29.9128918%" x2="42.8471697%" y2="95.8599587%" id="gradient-color-unknown">
                    <stop stopColor={this.theme.gradientColorUnknown[0]} offset="0%"></stop>
                    <stop stopColor={this.theme.gradientColorUnknown[1]} offset="100%"></stop>
                </linearGradient>
                <linearGradient x1="42.8471684%" y1="0%" x2="42.8471697%" y2="95.8599587%" id="gradient-color-warning">
                    <stop stopColor={this.theme.gradientColorWarning[0]} offset="0%"></stop>
                    <stop stopColor={this.theme.gradientColorWarning[1]} offset="100%"></stop>
                </linearGradient>
            </defs>
          </svg>
          <ReactSVG
            path={`/imgs/${this.props.icon}.svg#Icon`}
            {...svgProps}
            />
    	</div>
    );
  }
}
