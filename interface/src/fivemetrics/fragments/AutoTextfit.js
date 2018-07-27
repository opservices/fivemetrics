import React from 'react'
import Measure from 'react-measure'
import { Textfit } from 'react-textfit'

export default class AutoTextfit extends React.Component {
    constructor(props) {
        super(props)
        this.state = {dimensions: {width: 0,height: 0}, max: props.max || 70, min: props.min || 5}

    }

    render() {
        const { width, height } = this.state.dimensions

        return (
            <Measure bounds onResize={(contentRect) => {this.setState({ dimensions: contentRect.bounds })}}>
                {({ measureRef }) =>
                <Textfit autoResize={false} mode="single" max={this.state.max} min={this.state.min}  onReady={() => {}}>
                    <div style={{width: "100%"}} ref={measureRef}>{this.props.children}</div>
                </Textfit>}
            </Measure>
        )
    }
}
