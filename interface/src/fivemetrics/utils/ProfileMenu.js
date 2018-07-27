import React from 'react';

import {
  Dispatcher,
  DropdownButton,
  MenuItem,
  Icon
} from '@sketchpixy/rubix';

import axios from 'axios'
import Connection from './Connection'
import ProfileManager from './ProfileManager'

export default class ProfileMenu extends React.Component {

  constructor(props) {
    super(props)

    this.profileManager = ProfileManager.getInstance()

    this.state = { username: null }
  }

  componentDidMount() {
    this.profileManager.getData()
      .then(({ username }) => this.setState({ username }))
  }

  render() {
    return (
      <DropdownButton
        lg
        noCaret
        title={<Icon bundle='fontello' glyph='user-male' />}
        bsStyle='link'
        id='dropdown-profile'
      >
        <MenuItem header>{this.state.username}</MenuItem>
        <MenuItem href='/app/reset-password/token'>
            <Icon bundle='fontello' glyph='key' />
            <span style={{display: 'inline-block', marginLeft: '5px', verticalAlign: 'middle'}}>Change Password</span>
        </MenuItem>
        <MenuItem href='/logout'>
            <Icon id='logout-icon' bundle='fontello' glyph='logout' />
            <span style={{display: 'inline-block', marginLeft: '5px', verticalAlign: 'middle'}}>Logout</span>
        </MenuItem>
      </DropdownButton>
    );
  }
}