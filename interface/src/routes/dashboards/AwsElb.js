export default {
            "type": "Dashboard",
            "title": "AWS ELB Overview",
            "description": "AWS Manager",
            "id": "72879588-11C5-4649-8113-184C6B4A4CAE",
            "icon": "aws/aws",
            "refresh": "30s",
            "style": {},
            "enableTags": true,
            "dashlets": [
                {
                    "type": "Dashlet",
                    "title": "Active ELBs",
                    "id": "DDF56927-DD57-430B-950E-9C0C6BC48D80",
                    "enabled": true,
                    "x": 0,
                    "y": 0,
                    "width": 3,
                    "height": 5,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "StatusIconWithNumericLabel",
                        "id": "5520DE26-977F-48B8-BC63-398F9AE96FC0",
                        "dp": {"value": null, "status": null, "max": 0},
                        "label": "Total",
                        "labelmax": "Load Balances",
                        "icon": "aws/elb",
                        "style": {

                        },
                        "ds": [{
                            "metric": "aws.ec2.elb.instances",
                            "uri": "/metrics/aws.ec2.elb.instances/history",
                            "query": {
                                "periods": ["lastminute", "last5minutes", "last10minutes", "last15minutes", "last30minutes"],
                                "query": {
                                    "aggregation": "count",
                                    "query": {
                                        "aggregation": "min",
                                        "groupBy": {"time": "second", "tags": ["::fm::elbName"]}
                                    }
                                }
                            },
                            "model": {"reduce_points": true, "reduce_series": true}
                        }],
                        "interval": 30
                    },
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "ELB by Region",
                    "id": "0EEAAE30-9790-46D6-8A04-A0A64750D8A2",
                    "enabled": true,
                    "x": 3,
                    "y": 0,
                    "width": 3,
                    "height": 8,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "PieChart",
                        "id": "E56161B8-420F-45E5-9C5F-C91B10CFAED1",
                        "label": "Regions",
                        "name": "DiskWriteOps",
                        "dp": [],
                        "ds": [{
                            "metric": "aws.ec2.elb.instances",
                            "uri": "/metrics/aws.ec2.elb.instances/history",
                            "query": {
                                "periods": ["lastminute", "last5minutes", "last10minutes"],
                                "query": {
                                    "aggregation": "count",
                                    "groupBy": {"tags": ["::fm::region"]},
                                    "query": {
                                        "aggregation": "max",
                                        "groupBy": {"tags": ["::fm::region"]},
                                        "time": "second"
                                    }
                                }
                            },
                            "model": {
                                "reduce_points": true,
                                "reduce_series": false,
                                "map": {"::fm::region": "name"}
                            }
                        }],
                        "interval": 30,
                        "style": {
                            "unit_name": "Groups",
                        }
                    },
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "ELB List",
                    "id": "2B8CD4BA-C88D-4424-ABEC-83DEA17B5C1E",
                    "enabled": true,
                    "x": 6,
                    "y": 0,
                    "width": 5,
                    "height": 8,
                    "layout": "horizontal",
                    "style": {},
                    "element": {
                        "type": "AdvancedDataGrid",
                        "id": "4FACC9CA-BFB6-4E51-828D-07D04EB01A5B",
                        "metrics": [{"name": "tags.::fm::elbName", "title": "name"}, {
                            "name": "tags.::fm::region",
                            "title": "region"
                        }, {"name": "maximum", "title": "instances", "type": "Badge"}],
                        "period": "All",
                        "ec2": "All",
                        "region": "All",
                        "name": "DiskWriteOps",
                        "dp": [],
                        "ds": [{
                            "metric": "aws.ec2.elb.instances",
                            "uri": "/metrics/aws.ec2.elb.instances/history",
                            "query":
                                {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "groupBy": {"tags": ["::fm::elbName", "::fm::region"]},
                                        "aggregation": "sum",
                                        "query": {
                                            "aggregation": "sum",
                                            "groupBy": {"time": "second", "tags": ["::fm::elbName", "::fm::region"]}
                                        }
                                    }
                                },
                            "model": {"reduce_points": false, "reduce_series": false, "map": {"time": "date"}}
                        }],
                        "interval": 30,
                        "style": {
                        }
                    },
                    "tags": {

                    }
                }
            ]
        }