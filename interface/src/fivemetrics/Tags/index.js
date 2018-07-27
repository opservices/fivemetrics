
import React from "react"
import { Dispatcher } from '@sketchpixy/rubix'
import { Switch, Modal, Divider, Icon, Button, Tooltip } from "antd"
import { assocPath, dissocPath, always, isEmpty } from "ramda"
import * as Maybe from "fivemetrics/utils/Maybe"
import { Value } from "fivemetrics/GroupCombo/models/"
import { assign } from "fivemetrics/utils/Support"
import { ModalForm, TagGroup } from "./views"
import { flatTagsToArray } from "./help"
import LocalStorage from "fivemetrics/utils/LocalStorage"

export default class TagConfiguration extends React.Component {
  constructor(props) {
   super(props)

   let tags = Maybe.of(LocalStorage.read('tags-filter'))
        .chain(Maybe.pluck([props.parentId]))
        .option({})

   this.state = {
      tags: tags
    , showModal: false
    , enableFilter: !!tags.enabled
    }
  }

  publishEmptyTags = () => {
    Dispatcher.publish("systemTags", Maybe.Nothing(), this.state.enableFilter)
  }

  toggleModal = () => {
    this.setState({showModal: !this.state.showModal})
  }

  tagCreated = (tags) => {
    this.setState({tags},this.applyFilter)
  }

  removeTag = ({type, name}) => {
    this.setState({tags: dissocPath([type, name], this.state.tags)},this.applyFilter)
  }

  removeTagValue = ({type, name, value}) =>
    Maybe.pluck([type, name], this.state.tags)
      .map(xs => xs.filter(x => x !== value))
      .chain(Maybe.fromEmpty)
      .cata({
        Nothing: () => this.removeTag({type, name})
      , Just: (xs) => this.setState({tags: assocPath([type, name], xs, this.state.tags)},this.applyFilter)
      })

  applyFilter = () => {
    if (flatTagsToArray(this.state.tags).length === 0) {
      this.setState({enableFilter: false}, this.publishEmptyTags)
    } else {
      const result = Maybe.fromEmpty(this.state.tags)
      Dispatcher.publish("systemTags", result, this.state.enableFilter)
    }
  }

  handleSwitchChange = (checked) => {
    this.setState({enableFilter: checked}, this.applyFilter)
  }

  render() {
    return (
      <div style={this.props.style} className="fivemetrics-antd-theme">
        <Button id="btnFilterHome" type={(this.state.enableFilter) ? 'primary': 'default'} size='small' icon="tags" onClick={this.toggleModal}>Filter</Button>
        <Modal
          visible={this.state.showModal}
          title="Filter by Tag"
          width="768px"
          onCancel={this.toggleModal}
          maskClosable={false}
          footer={[
            <span key="footer-tag">
              <span style={{marginRight:10}}>Activate Filter</span>
              <Switch checked={this.state.enableFilter} onChange={this.handleSwitchChange} />
            </span>,
          ]}
          closable
          className="fivemetrics-antd-theme"
          wrapClassName="filter-modal"
          okText="Apply"
        >
            <ModalForm
              tags={this.state.tags}
              metrics={this.props.metrics}
              onCreate={this.tagCreated}
            />
            <Divider />
            <TagGroup
              tags={this.state.tags}
              onRemove={this.removeTag}
              onRemoveValue={this.removeTagValue}
            />
        </Modal>
      </div>
    )
  }
}
