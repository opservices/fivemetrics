
import React from "react"
import { Icon, Tooltip, Tag, Modal, Button, List } from "antd"
import * as Maybe from "fivemetrics/utils/Maybe"
import GroupCombo from "fivemetrics/GroupCombo"
import { TagResult } from "./models/"
import { Value, emptyValue } from "fivemetrics/GroupCombo/models/"
import { execMapRender } from "fivemetrics/GroupCombo/help"
import { MAP_RENDER } from "./constants"
import { compose as B, assoc, assocPath, identity } from "ramda"
import { flatTagsToArray } from "./help"

export class ModalForm extends React.Component {
  constructor(props) {
    super(props)
    this.state = {
      tagSource: TagResult.of(props.metrics, this.setTags)
    , formValue: emptyValue()
    }
  }

  setTags = (tagSource) => {
    this.setState({tagSource})
  }

  handleSelect = ({type, label, values}) => {
    const nextValue = values.cata({
      Just: Maybe.Just
    , Nothing: () => Maybe.of(type => name => [type, name])
      .ap(type)
      .ap(label)
      .chain(([type, name]) => Maybe.pluck([type, name], this.props.tags))
    })
    this.setState({ formValue: Value({type, label, values: nextValue}) })
  }

  handleCreate = () => {
    const {type, label, values} = this.state.formValue
    Maybe.of(type => label => values =>
      this.setState({formValue: emptyValue()}, () => {
        const next = assocPath([type, label], values, this.props.tags)
        this.props.onCreate(next)
      })
    )
    .ap(type)
    .ap(label)
    .ap(values)
  }

  render() {
    return (
      <div>
        { this.state.tagSource.cata({
            Loading: () => <div>Loading...</div>
          , Rejected: (err) => <div>Error in response from webservice: ${err}</div>
          , Resolved: (source) => (
              <GroupCombo
                data={source}
                value={this.state.formValue}
                mapRender={MAP_RENDER}
                onSelect={this.handleSelect}
                onCreate={this.handleCreate}
              />
          )
          })
        }
      </div>
    )
  }
}

export const TagGroup = ({
  tags={}
, onRemove=identity
, onRemoveValue=identity
}) => (
  <List
    itemLayout="horizontal"
    dataSource={flatTagsToArray(tags)}
    renderItem={({tag, type, values}) => (
      <List.Item style={{position: "relative"}} actions={[<Icon onClick={() => onRemove({type, name: tag})} type="close" />]}>
        <List.Item.Meta

          title={
            <span style={{fontStyle: (type=='system') ? 'italic' : 'normal'}}>
              {MAP_RENDER.label(Maybe.of(tag))}
            </span>
          }
          description={values.map((v,i) => (
            <span key={v+'-span'}>
              { (i!=0)
                  ? <span
                      key={v+'-span-sep'}
                      style={{color:(type=='system') ? '#8F52F8' : '#ac8ce2'}}
                      className="tag-separator"
                    >
                        |
                    </span>
                  : ""
              }
              <Tag
                color={(type=='system') ? '#8F52F8' : '#ac8ce2'}
                key={v}
                onClose={() => onRemoveValue({type, name: tag, value: v})}
                closable
              >
                { B(MAP_RENDER.values, Maybe.Just) (v) }
              </Tag>
            </span>
          ))}
        />

      </List.Item>
    )}
  />
)
