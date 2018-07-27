import React from 'react'
import { Config } from 'config'
import { Theme } from 'fivemetrics/utils'

export default class CircleLabel extends React.Component {
  constructor(props) {
    super(props)
    this.theme = Theme.load(Config.Theme)
  }

  render() {

    let boxSize = this.props.boxSize || 100
    let style = Object.assign({},this.props.style,{})

    return (
    <div className="circle-label" style={style}>
      <div style={{textAlign:'center', verticalAlign:'middle',display: 'grid',margin:'0 auto -'+(boxSize/2)+'px',lineHeight: (this.props.disableLineHeight ? 'inherit' : boxSize +'px')}}>
      {this.props.children}
      </div>
      <svg viewBox="0 0 100 100" style={{top: 0,left: 0, width: '100%'}}>
        <defs>
          <linearGradient id="gradient" gradientUnits="objectBoundingBox" gradientTransform="rotate(90)">
            <stop stopColor={this.theme.gradientColorDefault[0]} offset="0%"></stop>
            <stop stopColor={this.theme.gradientColorDefault[1]} offset="100%"></stop>
          </linearGradient>
        </defs>
        <circle r="46" cx="50" cy="50" fill="transparent" stroke="url(#gradient)" strokeWidth={this.props.stroke || 2}/>
      </svg>
    </div>
    );
  }
}





