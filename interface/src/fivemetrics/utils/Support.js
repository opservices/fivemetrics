
import { curry } from 'ramda'
import * as Maybe from './Maybe'

// not :: (a -> Bool) -> a -> Bool
export const not =
  curry((f, x) => !f(x))

// trace :: String -> a -> a
export const trace =
  curry((msg, x) => (console.log('## APP TRACE ##', msg, x), x))

// getSelectedValues :: Dom Select -> [String]
export const getSelectedValues = multi =>
  Array.prototype.slice.call(multi.options)
    .reduce((acc, o) => (o.selected ? acc.concat(o.value) : acc), [])


// cutWord :: Number -> String -> String -> String
export const cutWord =
  curry((end, appendStr, str) => (
    (str.length <= end)
      ? str
      : str.slice(0, end).trim().concat(appendStr)
  ))

// captilize :: String -> Maybe String
export const captilize = str =>
  Maybe.of(x => xs => x.toUpperCase().concat(xs))
    .ap(Maybe.head(str))
    .ap(Maybe.of(str.slice(1)))

// assign : (...{}) -> {}
export const assign = (...os) =>
  Object.assign({}, ...os)
