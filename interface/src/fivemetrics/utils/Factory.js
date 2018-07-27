import React from 'react'
import * as components from 'fivemetrics'
import { Theme, Connection, Store } from 'fivemetrics/utils'
import { Config } from 'config'

export default class Factory {
      static builder(baseProps,children = null) {
            let props = {...baseProps}
            let componentName = props.type
            let lower = String(componentName).toLowerCase()
            if (props.hasOwnProperty('id')) {
                  props.key = 'key_' + props.id
                  props.mkey = props.key
            }
            if (props.className) {
                  props.className += ` ${componentName}`
            } else {
                  props.className = componentName
            }

            props.theme = Theme.load(Config.Theme)
            props.store = Store
            props.dataProviderService = Connection.dataProviderService

            var comp = React.createElement(components[componentName], {...props})
            return comp
      }
}
