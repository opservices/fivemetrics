export default {
            "type": "Dashboard",
            "title": "AWS Glacier Storage Overview",
            "description": "AWS Manager",
            "id": "84C3B025-06C4-4DBD-81F6-DCD2BB0B41E7",
            "icon": "aws/aws",
            "refresh": "30s",
            "style": {},
            "enableTags": true,
            "dashlets": [
                {
                    "type": "Dashlet",
                    "title": "Vaults By Region",
                    "id": "0ec6e856-65df-4f4f-b402-558cdce5a9a3",
                    "enabled": true,
                    "x": 0,
                    "y": 0,
                    "width": 2,
                    "height": 7,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "PieChart",
                            "id": "4a131d97-28f7-471c-a557-6f367ad8a6aa",
                            "label": "Active Regions",
                            "dp": [],
                            "ds": [{
                                "metric": "aws.glacier.vault.archive",
                                "uri": "/metrics/aws.glacier.vault.archive/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "count",
                                        "groupBy": {"tags": ["::fm::region"]},
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"tags": ["::fm::region", "::fm::vaultName"]},
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
                                "unit_name": "Vaults"
                            }
                        }
                    ,
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Vaults",
                    "id": "1c4e6546-d034-4946-8456-b8f139fb61e6",
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
                            "id": "92B5A21F-BC47-42B5-B177-B79B3EC7F756",
                            "dp": {"value": null, "status": null, "max": 0},
                            "label": "Total",
                            "labelmax": "Vaults",
                            "icon": "aws/glacier",
                            "style": {
                            },
                            "ds": [{
                                "model": {"reduce_points": true, "reduce_series": true},
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "count",
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"tags": ["::fm::vaultName"], "time": "second"}
                                        }
                                    }
                                },
                                "metric": "aws.glacier.vault.archive",
                                "uri": "/metrics/aws.glacier.vault.archive/history"
                            }],
                            "interval": 30
                        },
                    "tags": {

                    }
                },
               /* {
                    "type": "Dashlet",
                    "title": "Vaults by tag",
                    "id": "BF390FBA-CC&A-481D-9911-B498A8008860",
                    "enabled": false,
                    "x": 4,
                    "y": 0,
                    "width": 2,
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
                                "uri": "/metrics/aws.glacier.vault.size/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "count",
                                        "groupBy": {"tags": ["env"]},
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"tags": ["env", "::fm::vaultName"]},
                                            "time": "second"
                                        }
                                    }
                                },
                                "model": {"reduce_points": true, "reduce_series": false, "map": {"env": "name"}}
                            }],
                            "interval": 60,
                            "style": {
                                "chart": {"valueAxes": [{"stackType": "regular"}]},
                                "unit": "%",
                                "color": "#428bca"
                            }
                        }
                    ,
                    "tags": {
                        "period": "realtime",
                        "filter": "by tag"
                    }
                },*/
                {
                    "type": "Dashlet",
                    "title": "Size Per Vault",
                    "id": "DF190EBA-CB4A-476C-8101-B418A8007460",
                    "enabled": true,
                    "x": 6,
                    "y": 0,
                    "width": 3,
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
                                    "name": "tags.::fm::vaultName",
                                    "title": "vault"
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
                                "metric": "aws.glacier.vault.size",
                                "uri": "/metrics/aws.glacier.vault.size/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "groupBy": {"tags": ["::fm::vaultName"]},
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"time": "second", "tags": ["::fm::vaultName"]}
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
                    "title": "Archives in Vault",
                    "id": "9B242834-1622-4A82-9924-64F5C78385B9",
                    "enabled": true,
                    "x": 9,
                    "y": 0,
                    "width": 3,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "AdvancedDataGrid",
                            "id": "F756DB83-09CD-4E61-B453-410160921F00",
                            "metrics": [{"name": "tags.::fm::vaultName", "title": "vault"}, {
                                "name": "maximum",
                                "title": "archives",
                                "type": "Badge"
                            }],
                            "dp": [],
                            "ds": [{
                                "metric": "aws.glacier.vault.archive",
                                "uri": "/metrics/aws.glacier.vault.archive/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "groupBy": {"tags": ["::fm::vaultName"]},
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"tags": ["::fm::vaultName"], "time": "second"}
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
                    "title": "Used Space Size",
                    "id": "E64A4F9D-1C90-47DB-B1F4-04E568EEE1FE",
                    "enabled": true,
                    "x": 4,
                    "y": 0,
                    "width": 2,
                    "height": 5,


                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {

                    },
                    "element":
                        {
                            "type": "NumericLabel",
                            "id": "EED84247-8087-41A0-8730-8242D6BF195C",
                            "dp": {"value": null, "status": null, "max": 0},
                            "label": "Total",
                            "labelmax": "GB",
                            "icon": "aws/glacier",
                            "style": {
                            },
                            "ds": [{
                                "model": {"reduce_points": true, "reduce_series": true},
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "sum",
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"time": "second", "tags": ["::fm::vaultName"]}
                                        }
                                    }
                                },
                                "metric": "aws.glacier.vault.size",
                                "uri": "/metrics/aws.glacier.vault.size/history"
                            }],
                            "interval": 30,
                            "style": {
                                "number_format": 'bytes'
                            }
                        }
                    ,
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Jobs In Progress",
                    "id": "6CD5ECFC-D6CA-4167-BA3B-E523605EE452",
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
                            "id": "89F95202-839A-4739-A5F2-C8859FE58AC5",
                            "dp": {"value": null, "status": null, "max": 0},
                            "labelmax": "Total",
                            "label": "Jobs",
                            "icon": "aws/glacier",
                            "style": {
                            },
                            "ds": [{
                                "metric": "aws.glacier.vault.jobs",
                                "uri": "/metrics/aws.glacier.vault.jobs/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {"::fm::jobStatus": ["InProgress"]},
                                            "groupBy": {"time": "second"}
                                        }
                                    }
                                },
                                "model": {"reduce_points": true, "reduce_series": true}
                            }],
                            "interval": 30
                        }
                    ,
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Jobs Succeeded",
                    "id": "C26C2F61-E8DF-4232-813F-677499EB6A17",
                    "enabled": true,
                    "x": 4,
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
                            "labelmax": "Total",
                            "label": "Jobs",
                            "icon": "aws/glacier",
                            "style": {

                            },
                            "ds": [{
                                "metric": "aws.glacier.vault.jobs",
                                "uri": "/metrics/aws.glacier.vault.jobs/history",
                                "query": {
                                    "periods": ["lastminute", "last5minutes", "last10minutes"],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {"::fm::jobStatus": ["Succeeded"]},
                                            "groupBy": {"time": "second"}
                                        }
                                    }
                                },
                                "model": {"reduce_points": true, "reduce_series": true}
                            }],
                            "interval": 30
                        }
                    ,
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Succeeded Jobs By Hour",
                    "id": "0ec6e856-65df-4f45-b402-558cdce5a9a3",
                    "enabled": true,
                    "x": 6,
                    "y": 5,
                    "width": 6,
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
                                "metric": "aws.glacier.vault.jobs",
                                "uri": "/metrics/aws.glacier.vault.jobs/history",
                                "query": {
                                    "periods": [
                                        "last24hours"
                                    ],
                                    "query": {
                                        "aggregation": "max",
                                        "query": {
                                            "aggregation": "sum",
                                            "filter": {
                                                "::fm::jobStatus": [
                                                    "Succeeded"
                                                ]
                                            },
                                            "groupBy": {
                                                "time": "second"
                                            }
                                        },
                                        "groupBy": {
                                            "time": "hour"
                                        },
                                        "fill": "0"
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
                }

            ]
        }