import React from 'react';

import {
  Row,
  Col,
  Grid,
} from '@sketchpixy/rubix';

export default class Footer extends React.Component {

  constructor(props) {
    super(props)
  }

  render() {
    var year = new Date().getFullYear()
    return (
      <div id='footer-container'>
        <Grid id='footer' className='text-center'>
          <Row>
            <Col xs={12}>
              <div>© {year} fivemetrics.io - <a className="support-link" target="_blank" href="https://fivemetrics.freshdesk.com">Support</a></div>
            </Col>
          </Row>
        </Grid>
      </div>
    );
  }
}
