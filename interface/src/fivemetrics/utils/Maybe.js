
import { curry, always, identity, isEmpty } from "ramda"


export const Just = (x) => ({
  x
, map: f => isNull(x) ? Nothing() : Just(f(x))
, chain: f => isNull(x) ? Nothing() : f(x)
, ap: other => other.map(x)
, cata: ({ Nothing=identity, Just=identity }) => isNull(x) ? Nothing() : Just(x)
, isNothing: false
, option: y => option(y, of(x))
, _type: "Just / Maybe"
})

export const Nothing = () => ({
  x: "Nothing"
, map: Nothing
, chain: Nothing
, ap: Nothing
, cata: ({ Nothing=identity }) => Nothing()
, isNothing: true
, option: y => option(y, Nothing())
, _type: "Nothing / Maybe"
})

const Maybe = { Just, Nothing }

export default Maybe

// option :: a -> Maybe a -> a
export const option = curry(
  (x, mx) => mx.cata({ Nothing: always(x), Just: identity })
)

// isNothing :: Maybe a => a -> Bool
export const isNothing = ma => ma.isNothing

// of :: a -> Maybe a
export const of = x => isNull(x) ? Nothing() : Just(x)
Maybe.of = of

// isNull :: a -> Bool
const isNull = x => (x === null) || (x === undefined)

// pluck :: [String] -> a -> b
export const pluck = curry(
  (keys, ox) => keys.reduce(
    (ma, key) => ma.chain(oy => of(oy[key]))
  , of(ox)
  )
)

// head :: [a] -> a
export const head = xs => Maybe.of(xs[0])

// fromEmpty :: a -> Maybe a
export const fromEmpty = x =>
  of(x).chain(y => isEmpty(y) ? Maybe.Nothing() : Maybe.Just(y))