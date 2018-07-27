import React from 'react'
import { Button, Menu, Dropdown, Icon } from 'antd'
import { ProfileManager } from 'fivemetrics/utils'
import { Config } from 'config'

class Payment extends React.Component {

  constructor(props) {
    super(props)
    this.profileManager = ProfileManager.getInstance()
    this.paypalCurrencyFormId = Config.PaypalCurrencyFormIds
    this.form = null
    this.state = { uid: "", paymentType: null, form: "" }

  }

  componentDidMount() {
    this.profileManager.getData()
      .then(({ uid, paymentType }) => this.setState({ uid, paymentType }))
  }

  handleMenuClick(e) {
    this.setState({form: this.paypalCurrencyFormId[e.key]})
  }

  render() {
    const menu = (
      <Menu onClick={this.handleMenuClick.bind(this)}>
        <Menu.Item key="usd">International Payment (USD)</Menu.Item>
        <Menu.Item key="brl">Brazilian Payment (BRL)</Menu.Item>
      </Menu>
    )

    let status = this.state.paymentType

    return (
      (status == 'pending') ?
    	(
      <div className="payment-container">
        <form action="https://www.sandbox.paypal.com/cgi-bin/webscr" method="post" target="_top" ref={(e) => this.form = e}>
          <input type="hidden" name="cmd" value="_s-xclick"/>
          <input type="hidden" name="hosted_button_id" value={this.state.form}/>
          <input type="hidden" name="custom" value={this.state.uid}/>
        </form>
          <span className="trial-label highlight">Trial has expired</span>
          <Dropdown overlay={menu} trigger={['click']}>
            <Button size="small" type="primary" htmlType="submit">Subscribe Now! <Icon type="down" /></Button>
          </Dropdown>
      </div>
      ) : (status == 'trial') && <span className="trial-label">Trial period</span>
    )
  }

  componentDidUpdate() {
    if (this.state.form) {
      this.form.submit()
    }
  }

}

export default Payment
