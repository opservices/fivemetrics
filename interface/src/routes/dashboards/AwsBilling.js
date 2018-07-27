export default  {
            "type": "Dashboard",
            "title": "AWS Billing Overview",
            "description": "AWS Manager",
            "id": "7E030E53-09A0-4921-B9CC-916D7931454C",
            "icon": "aws/aws",
            "refresh": "300s",
            "style": {},
            "enableTags": false,
            "dashlets": [
                {
                    "type": "Dashlet",
                    "title": "Average Estimated Monthly Cost",
                    "id": "69AC6A87-1679-4525-99D6-ABF6BB0DD7EF",
                    "enabled": true,
                    "x": 0,
                    "y": 0,
                    "width": 4,
                    "height": 5,
                    "layout": "vertical",
                    "selectableperiod": false,
                    "style": {},
                    "element": {
                        "type": "NumericLabel",
                        "id": "68419BBD-80BF-4AF7-B295-6A2DD628FC53",
                        "dp": {
                            "value": null,
                            "status": null,
                            "max": 0
                        },
                        "label": "USD",
                        "labelmax": "Total",
                        "icon": "aws/cloudwatch",
                        "style": {
                            "number_format": "currency"
                        },
                        "ds": [
                            {
                                "metric": "aws.ce.explorer.forecast",
                                "uri": "/metrics/ce.explorer/realtime",
                                "query": {
                                    "groupBy": {"time": "day"}
                                },
                                "model": {
                                    "reduce_points": true,
                                    "reduce_series": true,
                                    "map": {"forecast": "value"}
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
                    "title": "Maximum Daily Cost",
                    "id": "E7837278-568F-4178-82A5-486B37F02E5C",
                    "enabled": true,
                    "x": 4,
                    "y": 0,
                    "width": 5,
                    "height": 5,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "ColumnChart",
                            "id": "BF27660D-B6CE-4ADF-BBC4-782C5767E2D9",
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ce.explorer.groupbyday",
                                "uri": "/metrics/ce.explorer/realtime",
                                "query": {
                                    "groupBy": {"time": "day"}
                                },
                                "model": {"reduce_points": false, "reduce_series": true, "map": {"time": "date", "amount": "value", "period": "last30days"}}
                            }
                            ],
                            "interval": 30,
                            "style": {
                                "number_format": 'currency',
                                "precision": 2,
                                "type": "stacked",
                                "show_labels": false
                            }
                        }
                },
                {
                    "type": "Dashlet",
                    "title": "Cost By Service",
                    "id": "0ec6e856-65df-4f4f-b402-558cdce5a9a1",
                    "enabled": true,
                    "x": 9,
                    "y": 0,
                    "width": 3,
                    "height": 10,
                    "layout": "horizontal",
                    "selectableperiod": false,
                    "style": {},
                    "element":
                        {
                            "type": "PieChart",
                            "id": "4a131d97-28f7-471c-a557-6f367ad8a6ab",
                            "dp": [],
                            "ds": [{
                                "metric": "aws.ce.explorer.groupbyservices",
                                "uri": "/metrics/ce.explorer/realtime",
                                "query": {},
                                "model": {
                                    "reduce_points": false,
                                    "reduce_series": true,
                                    "map": {"service": "name","amount": "value", "currency": "unit"}
                                }
                            }],
                            "interval": 30,
                            "style": {
                                "createMax": true,
                                "unit": "$",
                                "unit_name": "USD",
                                "color": "#428bca",
                                "precision": 2,
                                "number_format": 'currency'
                            }
                        }

                }
            ]

        }

