
import React from "react"
import {Just, Nothing, fromEmpty, pluck, of as MaybeOf} from "fivemetrics/utils/Maybe"
import { curry, always, identity } from "ramda"
import { Select, Button, Input, Col, Row } from "antd"
import { Value, emptyValue, ComboValue } from "./models/"
import { execMapRender, findGroup } from "./help"

const { Option, OptGroup } = Select

export const GroupCombo = (
	{ mapRender={}
	, value=emptyValue()
	, onSelect=identity
	, onCreate=identity
	, data={}
	}
) => (
  <div>
		{ ComboValue.of(value).cata({
				Empty: () => (
					<Input.Group>
					<Col xs={24} sm={11}>
						<ComboSelect
							value={value}
							data={data}
							mapRender={mapRender}
							onSelect={(mt, ml) => onSelect(Value({ type: fromEmpty(mt), label: fromEmpty(ml), values: Nothing() }))}
							style={{width: "100%"}}
						/>
					</Col>
					<Col xs={20} sm={11}>
						<ComboMultiSelect
							value={emptyValue()}
							disabled={true}
							data={data}
							mapRender={mapRender}
							style={{width: "100%"}}
						/>

					</Col>
					<Col xs={4} sm={2}>
						<Button style={{marginLeft:10}} type="default"  disabled={true} shape="circle" icon="plus"/>
					</Col>
					</Input.Group>
				)
			, PreConfigured: (value) => (
					<Input.Group>
					<Col xs={24} sm={11}>
						<ComboSelect
							value={value}
							data={data}
							mapRender={mapRender}
							onSelect={(mt, ml) => onSelect(Value({ type: fromEmpty(mt), label: fromEmpty(ml), values: Nothing() }))}
							style={{width: "100%"}}
						/>
					</Col>
					<Col xs={20} sm={11}>
						<ComboMultiSelect
							value={value}
							disabled={false}
							data={data}
							mapRender={mapRender}
							onSelect={v => onSelect(Value({ type: value.type, label: value.label, values: fromEmpty(v) }))}
							style={{width: "100%"}}
						/>

						</Col>
						<Col xs={4} sm={2}>
						<Button style={{marginLeft:10}} type="default" disabled={true} shape="circle" icon="plus"/>
						</Col>
					</Input.Group>
				)
			, Configured: (value) => (
					<Input.Group>
					<Col xs={24} sm={11}>
						<ComboSelect
							value={value}
							data={data}
							mapRender={mapRender}
							onSelect={(mt, ml) => onSelect(Value({ type: fromEmpty(mt), label: fromEmpty(ml), values: Nothing() }))}
							style={{width: "100%"}}
						/>
						</Col>
					<Col xs={20} sm={11}>
						<ComboMultiSelect
							value={value}
							disabled={false}
							data={data}
							mapRender={mapRender}
							onSelect={v => onSelect(Value({ type: value.type, label: value.label, values: fromEmpty(v) }))}
							style={{width: "100%"}}
						/>

						</Col>
						<Col xs={4} sm={2}>
						<Button style={{marginLeft:10}} type="default" onClick={onCreate} disabled={false} shape="circle" icon="plus"/>
						</Col>
					</Input.Group>
				)
			})
		}
	</div>
)

const ComboSelect = (
	{ data={}
	, mapRender={}
	, value
	, disabled=false
	, onSelect
	, style={}
	}
) => (
	<Select
		style={style}
		value={value.label.cata({ Nothing: always(undefined), Just: identity })}
		placeholder={execMapRender("label", mapRender, null)}
		disabled={disabled}
		mode="combobox"
		optionLabelProp="renderValue"
		onSelect={(val, node) => onSelect(pluck(["props", "group"], node).option(""), val)}
		onSearch={val => onSelect("", val)}
		onBlur={val => ComboValue.of(value).cata({
			Empty: () => onSelect.apply(onSelect, findGroup(data, val).option(["", val]))
		, PreConfigured: () => undefined
		, Configured: () => undefined
		})}
	>
		{ Object.keys(data).map(group => (
				<OptGroup key={group} value={group} label={execMapRender("group", mapRender, group)}>
					{ Object.keys(data[group]).map(label => (
							<Option
								renderValue={execMapRender("label", mapRender, label)}
								key={label}
								group={group}
								value={label}
							>
								{execMapRender("label", mapRender, label)}
							</Option>
						))
					}
				</OptGroup>
			))
		}
	</Select>
)

const ComboMultiSelect = (
	{ data={}
	, mapRender={}
	, value
	, disabled=false
	, onSelect
	, style={}
	}
) => (
	<Select
		style={style}
		value={value.values.cata({ Nothing: always(undefined), Just: identity })}
		placeholder={execMapRender("values", mapRender, null)}
		disabled={disabled}
		mode="multiple"
		onSelect={x => onSelect(value.values.option([]).concat(x))}
		onDeselect={x => onSelect(value.values.option([]).filter(y => x !== y))}
		optionLabelProp="renderValue"
	>
		{ MaybeOf(type => label => data[type][label])
				.ap(value.type)
				.ap(value.label)
				.option([])
				.map(v => (
					<Option
						key={v}
						value={v}
						renderValue={execMapRender("values", mapRender, v)}
					>
							{execMapRender("values", mapRender, v)}
					</Option>
				))
		}
	</Select>
)

export default GroupCombo
