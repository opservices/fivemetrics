export default {
            "type": "Dashboard",
            "title": "AWS EBS Overview",
            "description": "AWS Manager",
            "id": "379DC79C-615B-4891-99CE-28BCCA5CFF3F",
            "icon": "aws/aws",
            "refresh": "30s",
            "style": {},
            "enableTags": true,
            "dashlets": [
                {
                    "type": "Dashlet",
                    "title": "By Availability Zones",
                    "id": "0ec6e856-65df-4f4f-b402-558cdce5a9a3",
                    "enabled": true,
                    "x": 8,
                    "y": 0,
                    "width": 2,
                    "height": 8,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "PieChart",
                            "id": "4a131d97-28f7-471c-a557-6f367ad8a6ab",
                            "label": "Total Volumes",
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ec2.ebs",
                                "uri": "/metrics/aws.ec2.ebs/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "query": {
                                            "aggregation": "sum",
                                            "groupBy": {
                                                "tags": ["::fm::availabilityZone"],
                                                "time": "second"
                                            }
                                        },
                                        "groupBy": {
                                            "tags": ["::fm::availabilityZone"]
                                        },
                                        "orderBy": "newest"
                                    }
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": false,
                                    "map": {"::fm::availabilityZone": "name"}
                                }
                            },
                            {
                                "model": {"reduce_points": true, "reduce_series": true},
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "groupBy": {"time": "second"}
                                        }
                                    }
                                },
                                "metric": "aws.ec2.ebs",
                                "uri": "/metrics/aws.ec2.ebs/history"
                            }],
                            "interval": 30,
                            "style": {
                                "unit_name": "Volumes"
                            }
                        }
                    ,
                    "tags": {
                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Available Volumes",
                    "id": "1c4e6546-d034-4946-8456-b8f139fb61t8",
                    "enabled": true,
                    "x": 0,
                    "y": 0,
                    "width": 2,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "StatusIconWithNumericLabel",
                        "id": "92B5A21F-BC47-42B5-B177-B79B3EC7F856",
                        "dp": {
                            "value": null,
                            "status": null,
                            "max": 0
                        },
                        "labelmax": "Total",
                        "icon": "aws/ebs",
                        "style": {
                        },
                        "ds": [
                        {
                            "model": {"reduce_points": true, "reduce_series": true},
                            "query": {
                                "periods": ["lastminute", "last5minutes", "last10minutes"],
                                "query": {
                                    "aggregation": "max",
                                    "filter": {"::fm::state": ["available"]},
                                    "groupBy": {"tags": ["::fm::state"]}
                                }
                            },
                            "metric": "aws.ec2.ebs",
                            "uri": "/metrics/aws.ec2.ebs/history"
                        },
                            {
                                "model": {"reduce_points": true, "reduce_series": true, "map": {"value": "max"}},
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "groupBy": {"time": "second"}
                                        }
                                    }
                                },
                                "metric": "aws.ec2.ebs",
                                "uri": "/metrics/aws.ec2.ebs/history"
                            },
                        ],
                        "interval": 30
                    },
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Unused Size",
                    "id": "1c4e6546-d034-4946-8456-b8f139fb61t9",
                    "enabled": true,
                    "x": 2,
                    "y": 0,
                    "width": 2,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "StatusIconWithNumericLabel",
                        "id": "92B5A21F-BC47-42B5-B177-B79B3EC7F857",
                        "dp": {
                            "value": null,
                            "status": null,
                            "max": 0
                        },
                        "label": "GB",
                        "labelmax": "Total",
                        "icon": "aws/ebs",
                        "style": {
                            "empty_as_zero": true
                        },
                        "ds": [
                        {
                            "model": {"reduce_points": true, "reduce_series": true},
                            "query": {
                                "periods": ["lastminute", "last5minutes", "last10minutes"],
                                "query": {
                                    "aggregation": "max",
                                    "filter": {"::fm::state": ["available"]},
                                    "groupBy": {"tags": ["::fm::state"]}
                                }
                            },
                            "metric": "aws.ec2.ebs.size",
                            "uri": "/metrics/aws.ec2.ebs.size/history"
                        },
                            {
                                "model": {"reduce_points": true, "reduce_series": true, "map": {"value": "max"}},
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "sum"
                                    }
                                },
                                "metric": "aws.ec2.ebs.size",
                                "uri": "/metrics/aws.ec2.ebs.size/history"
                            },
                        ],
                        "interval": 30
                    },
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Volumes by Instance",
                    "id": "DF190EBA-CB4A-476C-8101-B418A8507460",
                    "enabled": true,
                    "x": 4,
                    "y": 0,
                    "width": 4,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "AdvancedDataGrid",
                            "id": "3BC8993E-1BE8-4614-B668-C889D0D474C4",
                            "metrics": [
                                {
                                    "name": "tags.::fm::instanceName",
                                    "title": "name"
                                },
                                {
                                    "name": "maximum",
                                    "title": "volumes",
                                    "type": "Badge"
                                }
                            ],
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ec2.ebs",
                                "uri": "/metrics/aws.ec2.ebs/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "max",
                                        "groupBy": {
                                            "tags": ["::fm::instanceName"],
                                            "query": {
                                                "aggregation": "max",
                                                "groupBy": {
                                                    "tags": ["::fm::instanceName"],
                                                    "time": "second"
                                                }
                                            }
                                        }
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
                },
                {
                    "type": "Dashlet",
                    "title": "Volumes Size",
                    "id": "DF190EBA-CB4A-476C-8101-B418A8007470",
                    "enabled": true,
                    "x": 4,
                    "y": 5,
                    "width": 4,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "AdvancedDataGrid",
                            "id": "3BC8993E-1BE8-4614-B668-C789D1D474C2",
                            "metrics": [
                                {
                                    "name": "tags.Name",
                                    "title": "Name"
                                },
                                {
                                    "name": "maximum",
                                    "title": "size",
                                    "type": "Badge"
                                },
                                {
                                    "name": "unit",
                                    "title": "unit",
                                    "type": "Badge"
                                }
                            ],
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ec2.ebs.size",
                                "uri": "/metrics/aws.ec2.ebs.size/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "sum",
                                        "groupBy": {"tags": ["Name", "unit"]}
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
                },
                {
                    "type": "Dashlet",
                    "title": "Volumes by State",
                    "id": "0ec6e856-65df-4f4f-b402-558cdce5a9a4",
                    "enabled": true,
                    "x": 10,
                    "y": 0,
                    "width": 2,
                    "height": 8,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "PieChart",
                            "id": "4a131d97-28f7-471c-a557-6f367ad8a6aa",
                            "label": "State",
                            "dp": [],
                            "ds":
                                [{
                                    "metric": "aws.ec2.ebs",
                                    "uri": "/metrics/aws.ec2.ebs/history",
                                    "query": {
                                        "periods": ["lastminute", "last5minutes", "last10minutes"],
                                        "query": {
                                            "query": {
                                                "aggregation": "sum",
                                                "groupBy": {"tags": ["::fm::state"], "time": "second"}
                                            },
                                            "groupBy": {
                                                "tags": ["::fm::state"],
                                            }
                                        }

                                    },
                                    "model": {
                                        "reduce_points": true,
                                        "reduce_series": false,
                                        "map": {
                                            "::fm::state":
                                                "name"
                                        }
                                    }
                                }],
                            "interval": 30,
                            "style": {
                                "unit_name": "Volumes"
                            }
                        },
                    "tags": {
                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Volumes by Type",
                    "id": "DF190EBA-CB4A-476C-8101-B418A8007900",
                    "enabled": true,
                    "x": 0,
                    "y": 5,
                    "width": 4,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "AdvancedDataGrid",
                            "id": "3BC8993E-1BE8-4614-B668-C789D0D474C5",
                            "metrics": [{"name": "tags.::fm::type", "title": "type"}, {
                                "name": "maximum",
                                "title": "total",
                                "type": "Badge"
                            }],
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ec2.ebs",
                                "uri": "/metrics/aws.ec2.ebs/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "query": {
                                            "aggregation": "sum",
                                            "groupBy": {"tags": ["::fm::type"], "time": "second"}
                                        },
                                        "groupBy": {
                                            "tags": ["::fm::type"],
                                        }
                                    }

                                },
                                "model": {
                                    "reduce_points": false,
                                    "reduce_series": false,
                                    "map": {
                                        "time": "date"
                                    }
                                }
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