import { curry, identity, pipe } from 'ramda'
import { Connection } from '../../utils/'
import { generateTagReqProps, sortTagValues, flatMetricsToTags } from '../../Tags/help'

export const Loading = () => ({
  x: 'Loading',
  _type: 'Loading',
  cata: ({ Loading: inLoading }) => inLoading(),
})

export const Rejected = err => ({
  x: err,
  _type: 'Rejected',
  cata: ({ Rejected: inRejected }) => inRejected(err),
})

export const Resolved = data => ({
  x: data,
  _type: 'Resolved',
  cata: ({ Resolved: inResolved }) => inResolved(data),
})

// of :: TagResult a => [String] -> (a -> b) -> String -> a
export const of =
  curry((metrics = [], cb = identity, type = 'all') => {
    const fail = pipe(Rejected, cb)
    const ok = xs => (Array.isArray(xs) ? cb(Resolved(sortTagValues(flatMetricsToTags(xs)))) : fail(`Response not expected: ${xs}`))
    const props = generateTagReqProps(metrics, type)
    Connection.genericService(ok, props, fail)
    return Loading()
  })
