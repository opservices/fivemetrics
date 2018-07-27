export default {
            "type": "Dashboard",
            "title": "AWS S3 Storage Overview",
            "description": "AWS Manager",
            "id": "AEDC47DB-CA5E-48F5-9D3B-1BB40561F24E",
            "icon": "aws/aws",
            "refresh": "30s",
            "style": {},
            "enableTags": true,
            "dashlets": [
                {
                    "type": "Dashlet",
                    "title": "Buckets",
                    "id": "B697643C-74CB-432C-893B-3A8BFE92B180",
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
                            "label": "Total",
                            "labelmax": "Buckets",
                            "icon": "aws/s3",
                            "style": {
                            },
                            "ds": [{
                                "model": {"reduce_points": true, "reduce_series": true},
                                "query": {
                                    "periods": ["last15minutes", "last30minutes", "lasthour"],
                                    "query": {
                                        "aggregation": "sum",
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"tags": ["::fm::region", "::fm::bucketName"]},
                                            "time": "second"
                                        }
                                    }
                                },
                                "metric": "aws.s3.bucket.versioning",
                                "uri": "/metrics/aws.s3.bucket.versioning/history"
                            }],
                            "interval": 30
                    },
                    "tags": {

                    }
                },
                {
                    "type": "Dashlet",
                    "title": "Buckets By Region",
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
                            "label": "Active Regions",
                            "dp": [],
                            "ds": [{
                                "metric": "aws.s3.bucket.versioning",
                                "uri": "/metrics/aws.s3.bucket.versioning/history",
                                "query": {
                                    "periods": ["last15minutes", "last30minutes", "lasthour"],
                                    "query": {
                                        "aggregation": "sum",
                                        "groupBy": {"tags": ["::fm::region"]},
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"tags": ["::fm::region", "::fm::bucketName"]},
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
                                "unit_name": "Buckets"
                            }
                    },
                    "tags": {
                    }
                },
               /* {
                    "type": "Dashlet",
                    "title": "Buckets by tag",
                    "id": "BF390FBA-CC&A-481D-9911-B498A8008860",
                    "enabled": true,
                    "x": 5,
                    "y": 0,
                    "width": 3,
                    "height": 8,
                    "layout": "horizontal",
                    "style": {},
                    "element": {
                            "type": "BarChart",
                            "id": "1AC2343E-1BE8-4614-B668-C711D0FF74C1",
                            "dp": [],
                            "ds": [{
                                "uri": "/metrics/aws.s3.bucket.versioning/history",
                                "query": {
                                    "periods": ["last15minutes", "last30minutes", "lasthour"],
                                    "query": {
                                        "aggregation": "count",
                                        "groupBy": {"tags": ["env"]},
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"tags": ["env", "::fm::bucketName"]},
                                            "time": "second"
                                        }
                                    }
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": false,
                                    "map": {"time": "date", "env": "name"}
                                }
                            }],
                            "interval": 60,
                            "style": {
                                "chart": {"valueAxes": [{"stackType": "regular"}]},
                                "unit": "%",
                                "color": "#428bca"
                            }
                    },
                    "tags": {
                        "period": "realtime",
                        "filter": "by tag"
                    }
                },*/
                {
                    "type": "Dashlet",
                    "title": "Versioned Buckets",
                    "id": "31130C86-BC0A-499D-823E-E707062B450C",
                    "enabled": true,
                    "x": 6,
                    "y": 0,
                    "width": 3,
                    "height": 8,
                    "layout": "horizontal",
                    "style": {},
                    "element": {
                            "type": "PieChart",
                            "id": "826AB8FC-6FA7-490D-89E6-3376A33621E0",
                            "label": "Total",
                            "dp": [],
                            "ds": [{
                                "metric": "aws.s3.bucket.versioning",
                                "uri": "/metrics/aws.s3.bucket.versioning/history",
                                "query": {
                                    "periods": ["last15minutes", "last30minutes", "lasthour"],
                                    "query": {
                                        "aggregation": "sum",
                                        "groupBy": {"tags": ["::fm::versioning"]},
                                        "query": {
                                            "aggregation": "max",
                                            "groupBy": {"tags": ["::fm::bucketName"]},
                                            "time": "second"
                                        }
                                    }
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": false,
                                    "map": {"::fm::versioning": "name"}
                                }
                            }],
                            "interval": 30,
                            "style": {
                                "unit_name": "Buckets"
                            }
                    },
                    "tags": {

                    }
                }
            ]

        }