const Period = [
	{value: 'realtime', name: 'Real-time', enabled: false, minPeriod: 'mm', groupBy: 'minute'},
	{value: 'lastminute', name: 'Last Minute', enabled: false, minPeriod: 'ss', groupBy: 'minute'},
	{value: 'last5minutes', name: 'Last 5 Minutes', enabled: false, minPeriod: 'ss', groupBy: 'minute'},
	{value: 'last15minutes', name: 'Last 15 Minutes', enabled: false, minPeriod: 'mm', groupBy: 'minute'},
	{value: 'last30minutes', name: 'Last 30 Minutes', enabled: false, minPeriod: 'mm', groupBy: 'minute'},
	{value: 'lasthour', name: 'Last Hour', enabled: true, minPeriod: '300ss', groupBy: 'minute'},
	{value: 'last24hours', name: 'Last 24 Hours', enabled: true, minPeriod: 'hh', groupBy: 'hour'},
	{value: 'last15days', name: 'Last 15 Days', enabled: true, minPeriod: 'DD', groupBy: 'day'},
	{value: 'last30days', name: 'Last 30 Days', enabled: true, minPeriod: 'DD', groupBy: 'day'}
]
export default Period