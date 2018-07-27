export default  {
            "type": "Dashboard",
            "title": "AWS EC2 Overview",
            "description": "AWS Manager",
            "id": "716c67f9-c075-48b1-a7bb-4de37bccafba",
            "icon": "aws/aws",
            "refresh": "30s",
            "style": {},
            "enableTags": true,
            "dashlets": [
                {
                    "type": "Dashlet",
                    "title": "Instances",
                    "id": "1c4e6546-d034-4946-8456-b8f139fb61e6",
                    "enabled": true,
                    "x": 0,
                    "y": 0,
                    "width": 2,
                    "height": 5,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "StatusIconWithNumericLabel",
                        "id": "92B5A21F-BC47-42B5-B177-B79B3EC7F756",
                        "dp": {
                            "value": null,
                            "status": null,
                            "max": 0
                        },
                        "label": "Active",
                        "labelmax": "Total",
                        "icon": "aws/ec2",
                        "style": {
                        },
                        "ds": [
                            {
                                "metric": "aws.ec2.instances",
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": true
                                },
                                "query": {
                                    "periods": [
                                        "lastminute",
                                        "last5minutes",
                                        "last10minutes",
                                        "last15minutes",
                                        "last30minutes"
                                    ],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {
                                                "::fm::state": [
                                                    "running"
                                                ]
                                            },
                                            "groupBy": {
                                                "time": "second"
                                            }
                                        }
                                    }
                                },
                                "uri": "/metrics/aws.ec2.instances/history"
                            },
                            {
                                "metric": "aws.ec2.instances",
                                "model": {
                                    "map": {
                                        "value": "max"
                                    },
                                    "reduce_points": true,
                                    "reduce_series": true
                                },
                                "query": {
                                    "periods": [
                                        "lastminute",
                                        "last5minutes",
                                        "last10minutes",
                                        "last15minutes",
                                        "last30minutes"
                                    ],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {
                                                "::fm::state": [
                                                    "pending", "running", "shutting-down", "terminated", "stopping","stopped", "rebooting"
                                                ]
                                            },
                                            "groupBy": {
                                                "time": "second"
                                            }
                                        }
                                    }
                                },
                                "uri": "/metrics/aws.ec2.instances/history"
                            }
                        ],
                        "interval": 30
                    },
                    "tags": {

                    }
                },
               /* {
                    "type": "Dashlet",
                    "title": "Elastic Load Balances",
                    "id": "E64A4F9D-1C90-47DB-B1F4-04E568EEE1FE",
                    "enabled": true,
                    "x": 2,
                    "y": 0,
                    "width": 2,
                    "height": 5,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "StatusIconWithNumericLabel",
                            "id": "EED84247-8087-41A0-8730-8242D6BF195C",
                            "dp": {"value": null, "status": null, "max": 0},
                            "label": "ELBs",
                            "labelmax": "Instances",
                            "icon": "aws/elb",
                            "style": {
                                "unit": "%",
                                "color": "#428bca"
                            },
                            "ds": [{
                                "uri": "/metrics/aws.ec2.elb.instances/history",
                                "query": {
                                    "query": {
                                        "periods": ["lastminute", "last5minutes", "last10minutes", "last15minutes", "last30minutes"],
                                        "query": {
                                            "aggregation": "count",
                                            "query": {
                                                "aggregation": "min",
                                                "groupBy": {"time": "second", "tags": ["::fm::elbName"]}
                                            }
                                        }
                                    }
                                }, "model": {"reduce_points": true, "reduce_series": true}
                            }],
                            "interval": 30
                        }
                    ,
                    "tags": {
                        "period": "realtime"
                    }
                },*/
                {
                    "type": "Dashlet",
                    "title": "Reserves",
                    "id": "6CD5ECFC-D6CA-4167-BA3B-E523605EE452",
                    "enabled": true,
                    "x": 0,
                    "y": 5,
                    "width": 2,
                    "height": 5,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "StatusIconWithNumericLabel",
                        "id": "89F95202-839A-4739-A5F2-C8859FE58AC5",
                        "dp": {
                            "value": null,
                            "status": null,
                            "max": 0
                        },
                        "labelmax": "Total",
                        "label": "In-Use",
                        "icon": "aws/ec2",
                        "style": {
                        },
                        "ds": [
                            {
                                "metric": "aws.ec2.reserves",
                                "uri": "/metrics/aws.ec2.reserves/history",
                                "query": {
                                    "periods": [
                                        "lastminute",
                                        "last5minutes",
                                        "last10minutes",
                                        "last15minutes",
                                        "last30minutes"
                                    ],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {
                                                "::fm::state": [
                                                    "using"
                                                ]
                                            },
                                            "groupBy": {
                                                "time": "second"
                                            }
                                        }
                                    }
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": true
                                }
                            },
                            {
                                "metric": "aws.ec2.reserves",
                                "uri": "/metrics/aws.ec2.reserves/history",
                                "query": {
                                    "periods": [
                                        "lastminute",
                                        "last5minutes",
                                        "last10minutes",
                                        "last15minutes",
                                        "last30minutes"
                                    ],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {
                                                "::fm::state": [
                                                    "using","available"
                                                ]
                                            },
                                            "groupBy": {
                                                "time": "second"
                                            }
                                        }
                                    }
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": true,
                                    "map": {
                                        "value": "max"
                                    }
                                }
                            }
                        ],
                        "interval": 30
                    },
                    "tags": {

                    }
                },
                /*{
                    "type": "Dashlet",
                    "title": "Auto Scaling Groups",
                    "id": "C26C2F61-E8DF-4232-813F-677499EB6A17",
                    "enabled": true,
                    "x": 2,
                    "y": 5,
                    "width": 2,
                    "height": 5,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "StatusIconWithNumericLabel",
                            "id": "CE802B5E-5134-44B7-B5A5-956E02A9082E",
                            "dp": {"value": null, "max": 0},
                            "labelmax": "Groups",
                            "label": "Groups",
                            "icon": "aws/autoscaling",
                            "style": {
                                "unit": "%",
                                "color": "#428bca"
                            },
                            "ds": [{
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
                        }
                    ,
                    "tags": {
                        "period": "realtime"
                    }
                },*/
                {
                    "type": "Dashlet",
                    "title": "Active Instances",
                    "id": "1c4e6546-d034-4946-8456-b8f139fb61e7",
                    "enabled": true,
                    "x": 2,
                    "y": 0,
                    "width": 8,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": true,
                    "style": {},
                    "element":
                        {
                            "type": "LineChart",
                            "id": "B985D235-A4DA-44AE-B059-F8973D0D67B9",
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ec2.instances",
                                "uri": "/metrics/aws.ec2.instances/history",
                                "query": {
                                    "periods": ["last24hours"],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {"::fm::state": ["running"]},
                                            "groupBy": {"time": "second"}
                                        },
                                        "groupBy": {"time": "hour"}
                                    }
                                },
                                "model": {"reduce_points": false, "reduce_series": true, "map": {"time": "date"}}
                            }],
                            "interval": 30,
                            "style": {
                            }
                        }
                    ,
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Maximum Instances in Auto Scaling Groups",
                    "id": "0ec6e856-65df-4f45-b402-558cdce5a9a3",
                    "enabled": true,
                    "x": 2,
                    "y": 5,
                    "width": 4,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": true,
                    "style": {},
                    "element":
                        {
                            "type": "ColumnChart",
                            "id": "4a131d97-28f7-471c-a557-6f367ad8a665",
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ec2.autoscaling.instances",
                                "uri": "/metrics/aws.ec2.autoscaling.instances/history",
                                "query": {
                                    "periods": ["last24hours"],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {"::fm::state": ["InService"]},
                                            "groupBy": {"time": "second"}
                                        },
                                        "groupBy": {"time": "hour"}
                                    }
                                },
                                "model": {"reduce_points": false, "reduce_series": true, "map": {"time": "date"}}
                            }
                            ],
                            "interval": 30,
                            "style": {
                            }
                        }
                    ,
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Active Instances By Availability Zone",
                    "id": "0ec6e856-65df-4f4f-b402-558cdce5a9a3",
                    "enabled": true,
                    "x": 11,
                    "y": 5,
                    "width": 2,
                    "height": 10,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "PieChart",
                            "id": "4a131d97-28f7-471c-a557-6f367ad8a6aa",
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ec2.instances",
                                "uri": "/metrics/aws.ec2.instances/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes", "last15minutes", "last30minutes"],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {"::fm::state": ["running"]},
                                            "groupBy": {
                                                "time": "second",
                                                "tags": ["::fm::region", "::fm::availabilityZone"]
                                            }
                                        },
                                        "groupBy": {"tags": ["::fm::region", "::fm::availabilityZone"]}
                                    }
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": false,
                                    "map": {"::fm::availabilityZone": "name"}
                                }
                            }],
                            "interval": 30,
                            "style": {
                                "unit_name": "Instances"
                            }
                        }
                    ,
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Instances by Type",
                    "id": "DF190EBA-CB4A-476C-8101-B418A8007460",
                    "enabled": true,
                    "x": 6,
                    "y": 5,
                    "width": 4,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "AdvancedDataGrid",
                            "id": "3BC8993E-1BE8-4614-B668-C789D0D474C1",
                            "metrics": [
                                {
                                    "name": "tags.::fm::instanceType",
                                    "title": "type"
                                },
                                {
                                    "name": "points",
                                    "title": "distribution",
                                    "type": "MicroColumnChart",
                                    "sortable": false
                                },
                                {
                                    "name": "maximum",
                                    "title": "max",
                                    "type": "Badge"
                                }
                            ],
                            "name": "DiskWriteOps",
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ec2.instances",
                                "uri": "/metrics/aws.ec2.instances/history",
                                "query": {
                                    "periods": ["last24hours"],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {"::fm::state": ["running"]},
                                            "groupBy": {"time": "second", "tags": ["::fm::instanceType"]}
                                        },
                                        "groupBy": {"time": "hour", "tags": ["::fm::instanceType"]}
                                    }
                                },
                                "model": {"reduce_points": false, "reduce_series": false, "map": {"time": "date"}}
                            }],
                            "interval": 30,
                            "style": {
                            }
                        }
                    ,
                    "tags": {

                    }
                }/*,
                {
                    "type": "Dashlet",
                    "title": "EC2 Tag: Environment",
                    "id": "BF390FBA-CC&A-481D-9911-B498A8008860",
                    "enabled": false,
                    "x": 4,
                    "y": 10,
                    "width": 5,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "BarChart",
                            "id": "1AC2343E-1BE8-4614-B668-C711D0FF74C1",
                            "dp": [],
                            "ds": [{
                                "uri": "/metrics/aws.ec2.instanceTag/history",
                                "query": {
                                    "aggregationMethod": "sum",
                                    "groupBy": {"method": "tag", "option": "Environment"}
                                },
                                "model": {"reduce_series": true, "map": {"Environment": "name"}}
                            }],
                            "interval": 60,
                            "style": {
                                "chart": {"valueAxes": [{"stackType": "regular"}]},
                                "unit": "%",
                                "color": "#428bca"
                            }
                        }
                    ,
                    "tags": {}
                }*/
            ]

        }

