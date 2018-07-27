
import Connection from "./Connection"
import Logger from "./Logger"


export default class ProfileManager {

  static instance = null

  static getInstance() {
    if(!this.instance) {
      this.instance = new ProfileManager()
    }
    return this.instance
  }

  async getData() {
    if (this.data) {
      return this.data
    }
    return await Connection
      .genericService(null, this.props(), this.initializeAnalytics.bind(this))
      .then(data => {
        this.data = Object.assign({username: ''},data)
        this.initializeAnalytics()
        return this.data
      })
  }

  props() {
    return { uri: "/account", query: "", method: "get" }
  }

  initializeAnalytics() {
    if (ga && this.data && this.data.username && this.data.email) {
      const { username, email } = this.data
      if (email.indexOf('fivemetrics.io') == -1) {
        ga('create', 'UA-762755-5', { 'userId': `${username}-${email}` })
        ga('send', 'pageview')
      }
    }
  }
}