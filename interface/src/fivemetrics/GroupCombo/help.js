
import * as Maybe from "fivemetrics/utils/Maybe"
import { identity, always, curry } from "ramda"

// getMapRender :: String -> Object -> a -> a
export const execMapRender = curry(
	(key, meta, x) => getMapRender(meta)[key](Maybe.of(x))
)

// getMapRender :: Object -> Object
const getMapRender = meta => Object.assign({}, defaultMapRender, meta)

// defaultMapRender :: Object
const defaultMapRender =
	{ group: (ma) => ma.cata({ Nothing: always("Group anonymous"), Just: identity })
	, label: (ma) => ma.cata({ Nothing: always("Select a label"), Just: identity })
	, values: (ma) => ma.cata({ Nothing: always("Select a value"), Just: identity })
	}

// findGroup :: Object -> String -> Maybe [String]
export const findGroup = curry(
	(data, val) => Maybe.fromEmpty(val)
		.chain(v => (
			Object.keys(data).reduce(
				(ma, group) =>
					Maybe.isNothing(ma) && data[group].hasOwnProperty(v)
						? Maybe.of([group, v])
						: ma
			, Maybe.Nothing()
			)
		))
)