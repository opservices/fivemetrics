import React from 'react';
import classNames from 'classnames';
import { Link, withRouter } from 'react-router';

import {
  Row,
  Col,
  Icon,
  Grid,
  Form,
  Badge,
  Panel,
  Button,
  PanelBody,
  FormGroup,
  LoremIpsum,
  InputGroup,
  FormControl,
  ButtonGroup,
  ButtonToolbar,
  PanelContainer,
} from '@sketchpixy/rubix';

@withRouter
export default class Login extends React.Component {
  back(e) {
    e.preventDefault();
    e.stopPropagation();
    this.props.router.goBack();
  }

  componentDidMount() {
    //$('html').addClass('authentication');
    document.getElementsByTagName('html')[0].classList.add('authentication')
  }

  componentWillUnmount() {
    //$('html').removeClass('authentication');
    document.getElementsByTagName('html')[0].classList.remove('authentication')
  }

  getPath(path) {
    var dir = this.props.location.pathname.search('rtl') !== -1 ? 'rtl' : 'ltr';
    path = `/${dir}/${path}`;
    return path;
  }

  render() {
    return (
      <div id='auth-container' className='login' style={{position: 'absolute', top: '50%', transform: 'translateY(-50%)', width:'100%'}}>
        <div id='auth-row'>
          <div id='auth-cell'>
            <Grid>
              <Row>
                <Col sm={4} smOffset={4} xs={10} xsOffset={1} collapseLeft collapseRight>
                    <Panel>
                      <PanelBody style={{padding: 0}}>
                        <img src="/imgs/common/logo.png" style={{marginLeft:20}}/>
                        <div>
                          {/*<div className='text-center' style={{padding: 12.5}}>
                            Login
                          </div>*/}
                          <div style={{padding: 25, paddingTop: 0, paddingBottom: 0, margin: 'auto', marginBottom: 25, marginTop: 25}}>
                            <Form onSubmit={::this.back}>
                              <FormGroup controlId='emailaddress'>
                                <InputGroup bsSize='large'>
                                  <InputGroup.Addon style={{paddingRight:14}}>
                                    <Icon glyph='icon-fontello-mail' />
                                  </InputGroup.Addon>
                                  <FormControl autoFocus type='email' name='login_form[_account]' placeholder='user@youremail.com' />
                                </InputGroup>
                              </FormGroup>
                              <FormGroup controlId='password'>
                                <InputGroup bsSize='large'>
                                  <InputGroup.Addon>
                                    <Icon glyph='icon-fontello-key' />
                                  </InputGroup.Addon>
                                  <FormControl type='password' name='login_form[_password]' placeholder='password' />
                                </InputGroup>
                              </FormGroup>
                              <FormGroup>
                                <Grid>
                                  <Row>
                                    <Col xs={6} collapseLeft collapseRight style={{paddingTop: 10}}>
                                    <a href='#'>Become a Beta!</a>
                                    </Col>
                                    <Col xs={6} collapseLeft collapseRight className='text-right' style={{marginTop:30}}>
                                      <Button outlined lg type='submit' bsStyle='default' onClick={::this.back}>Login</Button>
                                    </Col>
                                  </Row>
                                </Grid>
                              </FormGroup>
                            </Form>
                          </div>
                        </div>
                      </PanelBody>
                    </Panel>

                </Col>
              </Row>
            </Grid>
          </div>
        </div>
      </div>
    );
  }
}
