
import { pluck, isNothing } from "fivemetrics/utils/Maybe"
import { curry, cond, identity, T, always } from "ramda"


/**
 * Type ComboValue a
 * | Empty Value
 * | PreConfigured Value
 * | Configured Value
 */


export const Empty = (value) => (
  {  value
  , _type: "Empty / ComboValue"
  , cata: ({ Empty }) => Empty(value)
  }
)

export const PreConfigured = (value) => (
  {  value
  , _type: "PreConfigured / ComboValue"
  , cata: ({ PreConfigured }) => PreConfigured(value)
  }
)

export const Configured = (value) => (
  { value
  , _type: "Configured / ComboValue"
  , cata: ({ Configured }) => Configured(value)
  }
)

// of :: Value a => a -> ComboValue a
export const of = x => (
  cond([
    [allPropEquals(not(isNothing)), always(Configured(x))]
  , [verifyTypeAndLabel, always(PreConfigured(x))]
  , [T, always(Empty(x))]
  ]) (x)
)

export const ComboValue = { Empty, PreConfigured, Configured }
ComboValue.of = of

// not :: (a -> Bool) -> a -> Bool
const not = curry(
  (f, x) => !f(x)
)

// and :: [(a -> Bool)] -> a -> Bool
const and = curry(
  (fs, x) => fs.reduce((y, f) => y && f(x), true)
)

// propEquals :: String -> (a -> Bool) -> a -> Bool
const propEquals = curry(
  (key, f, o) => pluck(key, o).map(f).cata({
    Nothing: always(false)
  , Just: identity
  })
)

// allPropEquals :: (a -> Bool) -> a -> Bool
const allPropEquals = curry(
  (f, x) => Object.keys(x).reduce((y, key) => y && f(x[key]), true)
)

// verifyTypeAndLabel :: Value a => a -> Bool
const verifyTypeAndLabel = and(
  [ propEquals(["type"], not(isNothing))
  , propEquals(["label"], not(isNothing))
  ]
)

