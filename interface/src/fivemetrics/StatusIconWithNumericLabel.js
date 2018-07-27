import React from 'react'
import { Icon } from '@sketchpixy/rubix'
import { StatusIcon, NumericLabel } from 'fivemetrics'

export default class StatusIconWithNumericLabel extends React.Component {
  constructor(props) {
    super(props)
  }
  render() {
    const data = this.props.dp
    const value = data.value
    const max = data.max
    const status = data.status || undefined

    const {id, icon, label, labelmax, theme, style, ...props} = this.props

    let animation = false

    if (status == 'critical') {
    }

    return (
      <div id={`statusiconwithnumericlabel_${id}`} style={{flex:1, verticalAlign: 'middle'}} className='statusiconwithnumberlabel text-center'>
        <StatusIcon {...{ icon, status, animation, theme}}/>
        <NumericLabel {...{ value, max, label, labelmax, style}} />
      </div>
    );
  }
}
StatusIconWithNumericLabel.defaultProps = {"minHeight": 4,"minWidth": 2}
