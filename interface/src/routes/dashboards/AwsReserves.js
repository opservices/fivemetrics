export default {
            "type": "Dashboard",
            "title": "AWS Reserved Instances Overview",
            "description": "AWS Manager",
            "id": "663600A2-C0E8-4A5F-B241-CE1F7E27840E",
            "icon": "aws/aws",
            "refresh": "30s",
            "style": {},
            "enableTags": false,
            "dashlets": [

                {
                    "type": "Dashlet",
                    "title": "Not in use",
                    "id": "0A72EE3E-7F10-4857-A879-0626C03B0E0D",
                    "enabled": true,
                    "x": 0,
                    "y": 0,
                    "width": 2,
                    "height": 4,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "NumericLabel",
                        "id": "6B2BCA84-3620-4C04-BC5A-4163CB32611E",
                        "dp": {
                            "value": null,
                            "status": null,
                            "max": 0
                        },
                        "label": "",
                        "labelmax": "Total",
                        "style": {
                        },
                        "ds": [
                            {
                                "metric": "aws.ec2.reserves",
                                "uri": "/metrics/aws.ec2.reserves/realtime",
                                "query": {
                                    "filter":{"instanceusing": [0], "state":["active"]}
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": true
                                }
                            }
                        ],
                        "interval": 30
                    },
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Actives",
                    "id": "75E3184D-2A78-461C-974D-B88E0C184DCA",
                    "enabled": true,
                    "x": 2,
                    "y": 0,
                    "width": 2,
                    "height": 4,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "NumericLabel",
                        "id": "4C1538BF-9367-4E7C-846F-17BD7883E488",
                        "dp": {
                            "value": null,
                            "status": null,
                            "max": 0
                        },
                        "label": "",
                        "labelmax": "Total",
                        "style": {
                        },
                        "ds": [
                            {
                                "metric": "aws.ec2.reserves",
                                "uri": "/metrics/aws.ec2.reserves/realtime",
                                "query": {
                                    "filter":{"state": ["active"]}
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": true
                                }
                            }
                        ],
                        "interval": 30
                    },
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Payment Pending",
                    "id": "28580D2D-FD5C-42F0-8445-E80FBA13772C",
                    "enabled": true,
                    "x": 4,
                    "y": 0,
                    "width": 2,
                    "height": 4,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "NumericLabel",
                        "id": "28580D2D-FD5C-42F0-8445-E80FBA13772D",
                        "dp": {
                            "value": null,
                            "status": null,
                            "max": 0
                        },
                        "label": "",
                        "labelmax": "Total",
                        "style": {
                        },
                        "ds": [
                            {
                                "metric": "aws.ec2.reserves",
                                "uri": "/metrics/aws.ec2.reserves/realtime",
                                "query": {
                                    "filter":{"state": ["payment-pending"]}
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": true
                                }
                            }
                        ],
                        "interval": 30
                    },
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Payment Fail",
                    "id": "D11A460B-22B0-4B44-837A-31599F594F8B",
                    "enabled": true,
                    "x": 6,
                    "y": 0,
                    "width": 2,
                    "height": 4,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "NumericLabel",
                        "id": "28580D2D-FD5C-42F0-8445-E80FBA13772F",
                        "dp": {
                            "value": null,
                            "status": null,
                            "max": 0
                        },
                        "label": "",
                        "labelmax": "Total",
                        "style": {
                        },
                        "ds": [
                            {
                                "metric": "aws.ec2.reserves",
                                "uri": "/metrics/aws.ec2.reserves/realtime",
                                "query": {
                                    "filter":{"state": ["payment-failed"]}
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": true
                                }
                            }
                        ],
                        "interval": 30
                    },
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Retired",
                    "id": "28580D2D-FD5C-42F0-8445-E80FBA13772A",
                    "enabled": true,
                    "x": 8,
                    "y": 0,
                    "width": 2,
                    "height": 4,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "NumericLabel",
                        "id": "28580D2D-FD5C-42F0-8445-E80FBA13772B",
                        "dp": {
                            "value": null,
                            "status": null,
                            "max": 0
                        },
                        "label": "",
                        "labelmax": "Total",
                        "style": {
                        },
                        "ds": [
                            {
                                "metric": "aws.ec2.reserves",
                                "uri": "/metrics/aws.ec2.reserves/realtime",
                                "query": {
                                    "filter":{"state": ["retired"]}
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": true
                                }
                            }
                        ],
                        "interval": 30
                    },
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Reserved Instances",
                    "id": "DF190EBA-CB4A-476C-8101-B418A8007460",
                    "enabled": true,
                    "x": 0,
                    "y": 4,
                    "width": 12,
                    "height": 6,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "DataGrid",
                            "id": "28580D2D-FD5C-42F0-8445-E80FBA13772E",
                            "columns": [{
                                  title: 'Instance Type',
                                  dataIndex: 'InstanceType',
                                  searchable: true
                                }, {
                                  title: 'Scope',
                                  dataIndex: 'Scope',
                                  searchable: true
                                }, {
                                  title: 'Availability Zone',
                                  dataIndex: 'AvailabilityZone',
                                  searchable: true
                                },
                                {
                                  title: 'Product Description',
                                  dataIndex: 'ProductDescription',
                                  searchable: true
                                },
                                {
                                  title: 'Recurring Charges',
                                  dataIndex: 'RecurringCharges',
                                  type: 'RecurringCharges',
                                  searchable: true
                                },
                                {
                                  title: 'State',
                                  dataIndex: 'State',
                                  searchable: true
                                },
                                {
                                  title: 'Start',
                                  dataIndex: 'Start',
                                  type: 'datetime',
                                  expandedRow: true,
                                  searchable: true
                                },
                                {
                                  title: 'End',
                                  dataIndex: 'End',
                                  type: 'datetime',
                                  expandedRow: true,
                                  searchable: true
                                },
                                {
                                  title: 'Offering Class',
                                  dataIndex: 'OfferingClass',
                                  expandedRow: true,
                                  searchable: true
                                },
                                {
                                  title: 'Instance Count',
                                  dataIndex: 'InstanceCount',
                                  expandedRow: true,
                                  searchable: true
                                },
                                {
                                  title: 'Instance Using',
                                  dataIndex: 'InstanceUsing',
                                  expandedRow: true,
                                  searchable: true
                                },
                                {
                                  title: 'Instance Tenancy',
                                  dataIndex: 'InstanceTenancy',
                                  expandedRow: true,
                                  searchable: true
                                },
                                {
                                  title: 'Instance Available',
                                  dataIndex: 'InstanceAvailable',
                                  expandedRow: true,
                                  searchable: true
                                }
                            ],
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ec2.reserves.grid",
                                "uri": "/metrics/aws.ec2.reserves/realtime",
                                "query": {},
                                "model": {"reduce_points": false, "reduce_series": true}
                            }],
                            "interval": 30,
                            "style": {
                            }
                        }
                    ,
                    "tags": {

                    }
                }
            ]

        }