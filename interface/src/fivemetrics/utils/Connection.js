import axios from 'axios'
import qs from 'qs'

axios.defaults.headers.common['X-Requested-With'] = "XMLHttpRequest";

export default class Connection {

	static currentAccount(callback, errorCallback) {
		let account_uri = {
			uri: '/account/',
			query: null,
			method:'get'
		}
		return Connection.genericService(callback, account_uri, errorCallback)
	}

	static createAccount(data, callback, errorCallback) {
		let account_uri = {
			uri: '/account/',
			query: JSON.stringify(data),
			method:'post'
		}
		return Connection.genericService(callback, account_uri, errorCallback)
	}

	static redirectOnUnauthorized(error) {
		if (error && error.response && error.response.status == '403') { //403
			if (typeof window != 'undefined') {
				let redirect = (window.location.pathname.indexOf('/app') != -1) ? "?redirect="+window.location.pathname : ""
				window.location.replace("/login"+redirect)
			}
		}
	}

	static createConfiguration(data, callback, errorCallback) {
		let account_configuration = {
			uri: '/account/configuration/',
			query: JSON.stringify(data),
			method:'post'
		}
		Connection.genericService(callback, account_configuration, (error) => {
	    	if (error.response && error.response.status == '409'){ //conflict 409
	    		let account_configuration_update = Object.assign({},account_configuration, {method:'put'})
	    		Connection.genericService(callback,account_configuration_update,errorCallback)
	      	}
    	})
	}

	static link(path='',api='/web/v1') {
		if (typeof HOST === 'undefined' || !window) {
    		return undefined
	    } else {
	    	return (api) ? HOST+api+path : HOST+path
	    }
	}
	static genericServiceMultiple(callback, requests) {
		axios.all().then(axios.spread((acct, perms) => {
    		callback(acct, perms)
  		}))
	}
	static genericService(callback, ds, errorCallback) {
		if (typeof Connection.link() === 'undefined' || !window) {
    		return {}
	    }

	    const {uri,query,method, baseURL} = ds

	    let config = {
	    	url: uri,
	    	method: method,
	    	baseURL: baseURL || Connection.link(),
	    }

	    if (method == 'get') {
	    	config.params = query
	    }

	    if (method == 'post' || method == 'put') {
	    	config.data = query
	    }

	    return axios.request(config).then((resp) => {
	    	let data
	    	try {
	    		data = resp.data
	    	} catch(e) {
	    		data = {}
	    	}
	    	if (callback) {
		    	callback(data, resp)
	    	}
	    	return data
	    }).catch((error) => {
	    	if (errorCallback) {
	    		errorCallback(error)
	    	}
	    	//console.log('request',error)
	    })
	}
	static dataProviderService(callback, ds, errorCallback) {
		if (!Array.isArray(ds)) {
			//console.log('invalid ds')
			return {}
		}
	    var req = ds.map(({uri,query,model}) => {

	    	if (typeof Connection.link() === 'undefined' || !window) {
	    		return {}
		    }

	    	return (() => axios.get(Connection.link(uri), {
			    params: (Object.keys(query).length) ? {q: query} : {},
			    transformResponse: (jdata) => {
			    	let data = {}
			    	try {
			    		data = JSON.parse(jdata)
			    	} catch(e) {
			    		return {}
			    	}
			    	if (!data || (Array.isArray(data) && !data.length)) {
			    		return {}
			    	}
			    	if (data.hasOwnProperty('status')){
			    		return {}
			    	}

					let {reduce_points, reduce_series, map} = model

					let doreduce = (r) => {
						if (!r) return {}
						if (model) {

							r.points = r.points.map(p => Object.assign(p,r.tags))

							var {points,...dataout} = r

							if (reduce_points) {
								r = Object.assign(dataout,points.shift())
							}
							if (map) {
								const domap = (m) => {
									let nobj = {...m}
									for (let vname in map) {
										if (m.hasOwnProperty(vname)) {
											delete nobj[vname]
											nobj[map[vname]] = (map[vname] == 'date') ? new Date(m[vname]) : m[vname]
										}
									}
									return nobj
								}
								if (reduce_points) {
									r = domap(r)
								} else {
									r.points = points.map(p => domap(p))
								}
							}
						}
						return r
					}

					if (data.hasOwnProperty('series')) {
						let data_series = (reduce_series) ? doreduce(data.series.shift()) : {...data, series: data.series.map(doreduce)}
						if (data.hasOwnProperty('period')) {
							data_series.period = (map && map.period) ? map.period : data.period
						}
						return data_series
					}

					return data
		 		}
			}).catch((error) => {
				Connection.redirectOnUnauthorized(error)
				errorCallback(error)
			}))()
	    })

	    axios.all(req)
		  .then(axios.spread((...rdata) => {

			rdata.forEach((e,i) => {
				if (e) {
					e.ds = ds[i]
				}
			})
			let period = ''
			let time = null
			//consolidate multiple requests
			let data = rdata.reduce((acc, val) => {
				if (!val) {
					return acc = Object.assign(acc,{})
				}
				if (val.hasOwnProperty('data')) {
					period = val.data.period
					time = val.data.time
				}
				if (val.ds.model && val.ds.model.return_array) {
					acc = (Array.isArray(acc)) ? acc : []
					acc.push(val.data)
					return acc
				}
				if (Array.isArray(val.data)) {
					acc = Array.isArray(acc) || []
					return acc.concat(val.data)
				} else {
					return acc = Object.assign(acc,val.data)
				}
			}, {})
			let out = {data: data, period: period, time: time}
			callback(out)
		  }))
  	}
}
