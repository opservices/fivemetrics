
const silence = () =>
  (!process || process.env.NODE_ENV != 'development')

const Logger = {

  trace: (...args) => {
    if(silence()) return args
    console.trace("TRACE", ...args)
    return args
  },

  error: (...args) => {
    if(silence()) return args
    console.error("ERROR", ...args)
    return args
  },

  warn: (...args) => {
    if(silence()) return args
    console.warn("WARN", ...args)
    return args
  }

}


export default Logger