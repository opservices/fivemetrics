import NumberFormat from 'fivemetrics/utils/NumberFormat'

export default class Chart {

  // constructor :: (Number, Number) -> Chart
  constructor(max = -Infinity, min = Infinity) {
    this._max = max
    this._min = min
  }

  // tryGetErrorMsg :: Amchart -> String
  static tryGetErrorMsg = chart =>
    chart && chart.dataProvider.length ? "" : "No results found."

  // get max :: _ -> Number
  get max() {
    return this._max
  }

  // set max :: Number -> Number
  set max(x) {
    return (typeof x !== "number" || x < this.max)
      ? false
      : (this._max = x, true)
  }

  // get min :: _ -> Number
  get min() {
    return this._min
  }

  // set min :: Number -> Number
  set min(x) {
    return (typeof x !== "number" || x > this.min)
      ? false
      : (this._min = x, true)
  }

  // updateSidesBasedOnSource :: [{ value: Number }] -> [Number]
  updateSidesBasedOnSource = (data = []) => {
    const freshSides = data.reduce(
      ([ resMax, resMin ], { value }) =>
      ( NumberFormat.isNumeric(value) ) ? [ Math.max(resMax, value), Math.min(resMin, value) ] : [ resMax, resMin ]
    , [ this.max, this.min ]
    )
    return this.updateSides(freshSides)
  }

  // updateSides :: [Number] -> [Number]
  updateSides = ([ max, min ]) => {
    this.max = max
    this.min = min
    return [ this.max, this.min ]
  }

  // labelFunctionOnlySides :: Amchart -> (Number, String) -> String
  labelFunctionOnlySides = ( style = null, type = null) => {
    return (v, valueText, valueAxis) => {
      let value = (v >= this.max || v <= this.min) ? v : ""
      if (value != "" && style.number_format == 'currency') {
        value = Number(value).toFixed(2)
      }
      let vp = String(value).split('.')
      value = (vp && vp.length == 2 && vp[1] == '00') ? vp[0] : value
      return value
    }
  }

  labelFunctionGraph = ( style = null, type = null) => {
    return (item) => {
      let value
      if (item.values && item.values.value != 0 && style.number_format == 'currency') {
        //value = Number(item.values.value).toFixed(2)
        value = NumberFormat.format(item.values.value, null, style).toString()
      }
      if (type == 'ColumnChart' ) {
        value = (item.values && item.values.value == 0) ? "" : ((value) ? value : item.values.value)
      }
      if (type == 'PieChart') {
        value = item.value;
      }
      let vp = String(value).split('.')
      value = (vp && vp.length == 2 && vp[1] == '00') ? vp[0] : value
      return value
    }
  }
}