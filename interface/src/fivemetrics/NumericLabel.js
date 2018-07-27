import React from 'react'
import {MainNumber, SecondaryNumber} from 'fivemetrics/fragments'
import NumberFormat from 'fivemetrics/utils/NumberFormat'
import Measure from 'react-measure'
import { Textfit } from 'react-textfit'
import classNames from 'classnames'

export default class NumericLabel extends React.Component {
    constructor(props) {
        super(props)
        this.state = {dimensions: {width: 0,height: 0}}
    }

    render() {
        const { width, height } = this.state.dimensions
        const data = this.props.dp || this.props

        let value = data.value
        let max = data.max
        let unit = data.unit || ''
        let status = data.status || undefined

        let {id, icon, theme, label, labelmax, style, ...props} = this.props
        let formatted = NumberFormat.format(value, unit, style)

        if (NumberFormat.isNumeric(formatted.value)) {

            if (formatted.unit_position == 'left') {
                value = formatted.unit+formatted.value
            } else {
                label = formatted.unit || label
                value = (<span className='main-number-value'>{formatted.value}</span>)
            }

            value = (<Textfit autoResize={false} mode="single" max={70} onReady={() => {}}>{value}</Textfit>)
        } else {
            value = (<span className='main-number-none'>-</span>)
        }

        let secondaryLabel = (!NumberFormat.isNumeric(max)) ? null : <SecondaryNumber {...{value: max, unit: labelmax}} />

        return (
            <Measure bounds onResize={(contentRect) => {this.setState({ dimensions: contentRect.bounds })}}>
                {({ measureRef }) =>
                    <div style={{flex: 1, verticalAlign: 'middle', width:'100%'}} className='numberlabel text-center' ref={measureRef}>
                        <MainNumber {...{value: value, label, width, height}}  />
                        {secondaryLabel}
                    </div>
                }
            </Measure>
        )
    }
}
