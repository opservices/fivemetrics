import React from "react"


const spanStyles =
  { background: "#8F52F8"
  , position: "relative"
  , display: "inline-block"
  , width: "70px"
  , height: "15px"
  , cursor: "pointer"
  }

const imgStyles =
  { position: "absolute"
  , top: 0
  , left: 0
  , marginTop: "-15px"
  , transition: "left .2s linear"
  }

const imgStylesEnabled =
  { left: "30px"
  }


export default class SlideToggle extends React.Component {

  constructor(props) {
    super(props)
    this.state = { enabled: this.props.enabled || true }
  }

  handleChange = () => {
    this.setState(({ enabled }) => {
      let next = !enabled
      if(typeof this.props.onChange === "function") {
        this.props.onChange(next)
      }
      return { enabled: next }
    })
  }

  render() {
    return(
      <span style={spanStyles} onClick={this.handleChange}>
        <img
          src="/imgs/star.svg"
          alt="Toggle background effect"
          style={
            Object.assign(
              {}
            , imgStyles
            , (this.state.enabled ? imgStylesEnabled : {})
            )
          }
        />
      </span>
    )
  }

}