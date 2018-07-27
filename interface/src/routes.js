import React from 'react'
import { IndexRoute, Route } from 'react-router'
import { Grid, Row, Col, Dispatcher } from '@sketchpixy/rubix'
import { MainContainer } from 'fivemetrics/utils'
import Footer from './common/footer'
import Header from './common/header'
import SidebarContainer from './common/sidebar'
import DashboardManager from './routes/DashboardManager'
import Login from './routes/Login'
import Onboarding from './routes/onboarding/Start'
import PasswordReset from './routes/PasswordReset'

class App extends React.Component {

  constructor(props) {
    super(props)
    this.sidebar = ""
    this.sidebarIsOpened = false;
  }

  isOpenedCallback(value) {
    this.sidebarIsOpened = value
  }

  componentDidMount() {
    if (this.main) {
      //this.main.closeSidebar(true)
    }
  }
  componentWillMount() {

  }

  handleCloseSidebar(e) {
    if (this.sidebar && this.sidebarIsOpened) {
      Dispatcher.publish('sidebar:completeClose')
    }
    e.preventDefault();
    e.stopPropagation();
  }

  render() {

    let showHeader = true
    let login = false
    const paths = ['/app/login','/app/logout','/app/onboarding', '/app/reset-password']

    if (paths.some((path) => this.props.location.pathname.match(path))) {
      this.sidebar = ''
      showHeader = false
      if (this.props.location.pathname == '/app/login') {
        login = true
      }
    } else {
      this.sidebar = <SidebarContainer />
    }
    return (
      <MainContainer {...this.props} ref={(m) => { this.main = m; }} isOpenedCallback={::this.isOpenedCallback}>
        {this.sidebar}
        {(showHeader) ? <Header id='body-header'/> : null}
        <div id='body' onClick={::this.handleCloseSidebar} onTouchStart={::this.handleCloseSidebar}>
        {((!login) ?
          <Grid>
            <Row>
              <Col xs={12}>
                {this.props.children}
              </Col>
            </Row>
          </Grid>
          : this.props.children
        )}
        </div>
        <Footer />
      </MainContainer>
    );

  }
}

export default (
  <Route component={App}>
    <Route path='/app' component={DashboardManager} />
    <Route path='/app/:namespace/:collection/:datasource' component={DashboardManager} />
    <Route path='/app/onboarding' component={Onboarding} />
    <Route path='/app/reset-password/:token' component={PasswordReset} />
  </Route>
);

