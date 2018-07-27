import React from 'react'

import {
  Dispatcher,
  DropdownButton,
  MenuItem,
  Icon
} from '@sketchpixy/rubix';

export default class SettingsMenu extends React.Component {

  constructor(props) {
    super(props)
    this.state = {settingsLayout: {edit_layout: false}}
    this.onSelectSettings = this.onSelectSettings.bind(this)
  }

  onSelectSettings(eventKey) {
    //console.log(eventKey)
    Dispatcher.publish('dashboard:settings',eventKey)
    let n = {}
    n[eventKey] = !this.state.settingsLayout[eventKey]
    this.setState({settingsLayout: Object.assign(this.state.settingsLayout,n)})
  }

  render() {

    return (
    <div id='container-settings' >
      <DropdownButton pullRight lg noCaret title={<Icon bundle='fontello' glyph='equalizer' />} bsStyle='link' id='dropdown-settings'>
        <MenuItem header>Layout</MenuItem>
        <MenuItem eventKey="reset_layout" onSelect={this.onSelectSettings}>Reset layout</MenuItem>
        <MenuItem active={this.state.settingsLayout.edit_layout} eventKey="edit_layout" onSelect={this.onSelectSettings}>Edit mode</MenuItem>
      </DropdownButton>
    </div>
    );
  }
}
