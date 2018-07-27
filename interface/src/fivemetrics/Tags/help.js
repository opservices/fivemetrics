import { curry, identity } from 'ramda'
import { assign } from '../utils/Support'
import { EMPTY_TAG } from './constants'

// generateTagReqProps : [String] -> String -> {}
export const generateTagReqProps =
  curry((metrics = [], type = 'system') => (
    {
      uri: '/tags/metrics/',
      query: { q: `{"metrics": [${metrics.map(m => `"${m}"`)}], "type": "${type}"}` },
      method: 'get',
    }
  ))

// flatMetricsToTags : [{}] -> {}
export const flatMetricsToTags = xs =>
  xs.reduce(
    ({ system, custom }, x) => (
      {
        system: assocTag('system', x.tags, system),
        custom: assocTag('custom', x.tags, custom),
      }),
    { system: {}, custom: {} },
  )

// assocTag : {} -> {}
const assocTag =
  curry((type, tags, acc) =>
    tags[type].reduce(
      (accIn, tag) =>
        assign(accIn, { [tag.name]: tag.values.concat(acc[tag.name] || []) }),
      acc,
    ))

// sortTagValues : {} -> {}
export const sortTagValues = meta =>
  assign(
    meta,
    {
      custom: sortValues(meta.custom),
      system: sortValues(meta.system),
    },
  )

// sortValues : {} -> {}
const sortValues = tags =>
  Object.keys(tags).reduce(
    (acc, name) =>
      assign(
        acc,
        {
          [name]: tags[name].reduce(
            (accInner, value) => (
              {
                toArr: accInner.toArr,
                data: assign(accInner.data, { [value]: (accInner.data[value] || 0) + 1 }),
              }
            ),
            {
              data: {},
              toArr: function toArr() {
                return Object.keys(this.data)
                  .sort((x, y) => {
                    if (isEmptyTag(x)) return -1
                    else if (isEmptyTag(y)) return 1
                    return this.data[y] - this.data[x]
                  })
                  .map(identity)
              },
            }
          ).toArr(),
        }
      ),
    {}
  )

// flatTagsToArray : {} -> [{}]
export const flatTagsToArray = tags =>
  Object.keys(tags).reduce(
    (components, tagType) => (
      components.concat(Object.keys(tags[tagType]).map(tag => (
        { type: tagType, tag, values: tags[tagType][tag] }
      )))
    ),
    [],
  )

// isEmptyTag : String -> Bool
export const isEmptyTag = x => (x === EMPTY_TAG)
