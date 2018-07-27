import React from 'react'
import * as Maybe from '../utils/Maybe'


// MAP_RENDER :: {(Maybe a -> a)}
export const MAP_RENDER = {
  label: ma => Maybe.option('Select a tag', ma.map(x => x.replace('::fm::', ''))),
  values: ma => (
    ma.map(x => (x === EMPTY_TAG) ? <span style={{fontStyle: 'italic'}}>{'<empty>'}</span> : x)
      .option('Select a tag value')
  ),
}

// EMPTY_TAG : String
export const EMPTY_TAG = '""'

export default MAP_RENDER
