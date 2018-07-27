export default {
            "type": "Dashboard",
            "title": "AWS Auto Scaling Overview",
            "description": "AWS Manager",
            "id": "DF7BC7C3-7185-4833-90DE-B0A29C92EBB3",
            "icon": "aws/aws",
            "refresh": "30s",
            "style": {},
            "enableTags": true,
            "dashlets": [
                {
                    "type": "Dashlet",
                    "title": "Auto Scalings",
                    "id": "1c4e6546-d034-4946-8456-b8f139fb61e6",
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
                        "id": "92B5A21F-BC47-42B5-B177-B79B3EC7F756",
                        "dp": {"value": null, "status": null, "max": 0},
                        "label": "Groups",
                        "labelmax": "Groups",
                        "icon": "aws/autoscaling",
                        "style": {
                        },
                        "ds": [{
                            "metric": "aws.ec2.autoscaling.instances",
                            "uri": "/metrics/aws.ec2.autoscaling.instances/history",
                            "query": {
                                "periods": ["lastminute", "last5minutes", "last10minutes", "last15minutes", "last30minutes"],
                                "query": {
                                    "aggregation": "max",
                                    "query": {"aggregation": "sum", "groupBy": {"time": "second"}}
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
                    "title": "Active Auto Scalings by Region",
                    "id": "0ec6e856-65df-4f4f-b402-558cdce5a9a3",
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
                        "id": "4a131d97-28f7-471c-a557-6f367ad8a6aa",
                        "label": "Regions",
                        "dp": [],
                        "ds": [{
                            "metric": "aws.ec2.autoscaling.instances",
                            "uri": "/metrics/aws.ec2.autoscaling.instances/history",
                            "query": {
                                "periods": ["last24hours"],
                                "query": {
                                    "aggregation": "count",
                                    "groupBy": {"tags": ["::fm::region"]},
                                    "query": {
                                        "aggregation": "count",
                                        "groupBy": {"tags": ["::fm::region", "::fm::groupName"]},
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {
                                                "time": "second",
                                                "tags": ["::fm::region", "::fm::groupName"]
                                            }
                                        }
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
                            "color": "#428bca"
                        }
                    },
                    "tags": {

                    }
                },
                /*{
                    "type": "Dashlet",
                    "title": "Active EC2 Instances",
                    "id": "9DAC7349-28F5-424B-9D0E-E22FAC880553",
                    "enabled": false,
                    "x": 5,
                    "y": 0,
                    "width": 3,
                    "height": 8,
                    "layout": "horizontal",
                    "style": {},
                    "element": {
                        "type": "PieChart",
                        "id": "8729B868-6959-4E39-8CCB-BD4E10738E3F",
                        "label": "Active EC2",
                        "dp": [],
                        "ds": [
                            {
                                "uri": "/metrics/aws.ec2.autoscaling.instances/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "groupBy": {"tags": ["::fm::groupName", "::fm::region"]},
                                        "aggregation": "sum",
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"time": "second", "filter": {"::fm::state": ["InService"]}}
                                        }
                                    }
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": true,
                                    "return_array": true,
                                    "map": {"maximum": "value"}
                                }
                            }
                        ],
                        "interval": 30,
                        "style": {
                            "unit": "%",
                            "unit_name": "instances",
                            "color": "#428bca"
                        }
                    },
                    "tags": {
                        "period": "realtime"
                    }
                },*/
                {
                    "type": "Dashlet",
                    "title": "Active Auto Scaling Instances",
                    "id": "DF190EBA-CB4A-476C-8101-B418A8007460",
                    "enabled": true,
                    "x": 6,
                    "y": 0,
                    "width": 5,
                    "height": 8,
                    "layout": "horizontal",
                    "style": {},
                    "element": {
                        "type": "AdvancedDataGrid",
                        "id": "3BC8993E-1BE8-4614-B668-C789D0D474C1",
                        "metrics": [{"name": "tags.::fm::groupName", "title": "name"}, {
                            "name": "tags.::fm::region",
                            "title": "region"
                        }, {"name": "maximum", "title": "instances", "type": "Badge"}],
                        "dp": [],
                        "ds": [{
                            "metric": "aws.ec2.autoscaling.instances",
                            "uri": "/metrics/aws.ec2.autoscaling.instances/history",
                            "query":
                                {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "groupBy": {"tags": ["::fm::groupName", "::fm::region"]},
                                        "aggregation": "sum",
                                        "query": {
                                            "aggregation": "sum",
                                            "groupBy": {
                                                "time": "second",
                                                "tags": ["::fm::groupName", "::fm::region"]
                                            }
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