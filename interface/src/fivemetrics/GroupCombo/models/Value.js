
import Maybe from "fivemetrics/utils/Maybe"

export const Value = (props={}) => (
  { label: props.label || Maybe.Nothing()
  , values: props.values || Maybe.Nothing()
  , type: props.type || Maybe.Nothing()
  }
)

export const emptyValue = () => Value()
