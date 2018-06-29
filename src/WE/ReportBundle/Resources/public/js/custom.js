$(document).ready(function () {
    if ($('#mainb').length) {

        if (typeof (echarts) === 'undefined') {
            return;
        }

        var theme = {
            color: [
                brand_color, '#34495E', '#BDC3C7', '#3498DB',
                '#9B59B6', '#8abb6f', '#759c6a', '#bfd3b7'
            ],
            title: {
                itemGap: 8,
                textStyle: {
                    fontWeight: 'normal',
                    color: brand_color
                }
            },
            dataRange: {
                color: ['#1f610a', '#97b58d']
            },
            toolbox: {
                color: ['#408829', '#408829', '#408829', '#408829']
            },
            tooltip: {
                backgroundColor: 'rgba(0,0,0,0.5)',
                axisPointer: {
                    type: 'line',
                    lineStyle: {
                        color: '#408829',
                        type: 'dashed'
                    },
                    crossStyle: {
                        color: '#408829'
                    },
                    shadowStyle: {
                        color: 'rgba(200,200,200,0.3)'
                    }
                }
            },
            dataZoom: {
                dataBackgroundColor: '#eee',
                fillerColor: 'rgba(64,136,41,0.2)',
                handleColor: '#408829'
            },
            grid: {
                borderWidth: 0
            },
            categoryAxis: {
                axisLine: {
                    lineStyle: {
                        color: '#408829'
                    }
                },
                splitLine: {
                    lineStyle: {
                        color: ['#eee']
                    }
                }
            },
            valueAxis: {
                axisLine: {
                    lineStyle: {
                        color: '#408829'
                    }
                },
                splitArea: {
                    show: true,
                    areaStyle: {
                        color: ['rgba(250,250,250,0.1)', 'rgba(200,200,200,0.1)']
                    }
                },
                splitLine: {
                    lineStyle: {
                        color: ['#eee']
                    }
                }
            },
            timeline: {
                lineStyle: {
                    color: '#408829'
                },
                controlStyle: {
                    normal: {color: '#408829'},
                    emphasis: {color: '#408829'}
                }
            },
            k: {
                itemStyle: {
                    normal: {
                        color: '#68a54a',
                        color0: '#a9cba2',
                        lineStyle: {
                            width: 1,
                            color: '#408829',
                            color0: '#86b379'
                        }
                    }
                }
            },
            map: {
                itemStyle: {
                    normal: {
                        areaStyle: {
                            color: '#ddd'
                        },
                        label: {
                            textStyle: {
                                color: '#c12e34'
                            }
                        }
                    },
                    emphasis: {
                        areaStyle: {
                            color: '#99d2dd'
                        },
                        label: {
                            textStyle: {
                                color: '#c12e34'
                            }
                        }
                    }
                }
            },
            force: {
                itemStyle: {
                    normal: {
                        linkStyle: {
                            strokeColor: '#408829'
                        }
                    }
                }
            },
            chord: {
                padding: 4,
                itemStyle: {
                    normal: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        },
                        chordStyle: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            }
                        }
                    },
                    emphasis: {
                        lineStyle: {
                            width: 1,
                            color: 'rgba(128, 128, 128, 0.5)'
                        },
                        chordStyle: {
                            lineStyle: {
                                width: 1,
                                color: 'rgba(128, 128, 128, 0.5)'
                            }
                        }
                    }
                }
            },
            gauge: {
                startAngle: 225,
                endAngle: -45,
                axisLine: {
                    show: true,
                    lineStyle: {
                        color: [[0.2, '#86b379'], [0.8, '#68a54a'], [1, '#408829']],
                        width: 8
                    }
                },
                axisTick: {
                    splitNumber: 10,
                    length: 12,
                    lineStyle: {
                        color: 'auto'
                    }
                },
                axisLabel: {
                    textStyle: {
                        color: 'auto'
                    }
                },
                splitLine: {
                    length: 18,
                    lineStyle: {
                        color: 'auto'
                    }
                },
                pointer: {
                    length: '90%',
                    color: 'auto'
                },
                title: {
                    textStyle: {
                        color: '#333'
                    }
                },
                detail: {
                    textStyle: {
                        color: 'auto'
                    }
                }
            },
            textStyle: {
                fontFamily: 'Arial, Verdana, sans-serif'
            }
        };
        var data_object = JSON.parse(data_chart);
        var echartBar = echarts.init(document.getElementById('mainb'), theme);

        option = {
            title: {
                text: brand_name
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['Ventas', 'Activaciones', 'Objetivos']
            },
            grid: {
                left: '3%',
                right: '4%',
                bottom: '3%',
                containLabel: true
            },
            toolbox: {
                feature: {
                    saveAsImage: {
                        title: 'Guardar imagén'
                    }
                }
            },
            xAxis: {
                type: 'category',
                boundaryGap: false,
                data: data_object.semanas
            },
            yAxis: {
                type: 'value'
            },
            series: [
                {
                    name: 'Ventas',
                    type: 'line',
                    stack: 'Ventas',
                    data: data_object.ventas
                },
                {
                    name: 'Objetivos',
                    type: 'line',
                    stack: 'Objetivos',
                    data: data_object.objetivos
                }
            ]
        };

        echartBar.setOption(option);
        var data_share_object = JSON.parse(data_share_chart);

        var chart_doughnut_settings = {
            type: 'pie',
            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
            data: {
                labels: data_share_object.labels,
                datasets: [{
                        data: data_share_object.values,
                        backgroundColor: [
                            brand_color,
                            "#9B59B6",
                            "#E74C3C",
                            "#26B99A",
                            "#3498DB"
                        ],
                        hoverBackgroundColor: [
                            "#CFD4D8",
                            "#B370CF",
                            "#E95E4F",
                            "#36CAAB",
                            "#49A9EA"
                        ]
                    }]
            },
            options: {
                legend: false,
                responsive: false,
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var allData = data.datasets[tooltipItem.datasetIndex].data;
                            var tooltipLabel = data.labels[tooltipItem.index];
                            var tooltipData = allData[tooltipItem.index];
                            var total = 0;
                            for (var i in allData) {
                                total += parseFloat(allData[i]);
                            }

                            var tooltipPercentage = Math.round((tooltipData / total) * 100);
                            return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
                        }
                    }
                }
            }
        };

        if (typeof (Chart) === 'undefined') {
            return;
        }
        if (document.getElementById("graphshare")) {
            var chart_element = document.getElementById("graphshare").getContext('2d');


            var chart_doughnut = new Chart(chart_element, chart_doughnut_settings);
        }

        if ($('#cdc_chart').length) {


            var data_object = JSON.parse(cdc_chart);

            var ctx = document.getElementById("cdc_chart");
            var mybarChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data_object.labels,
                    datasets: [{
                            label: 'Ventas',
                            backgroundColor: brand_color,
                            data: data_object.values
                        }]
                },

                options: {
                    scales: {
                        yAxes: [{
                                ticks: {
                                    beginAtZero: true
                                }
                            }]
                    }
                }
            });

        }

        if ($('#comentarios_chart').length) {
            var data_object = JSON.parse(comentarios_chart);

            if (data_object) {
                var ctx = document.getElementById("comentarios_chart");
                var comentariosChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data_object.labels,
                        datasets: [{
                                label: 'Comentarios',
                                backgroundColor: brand_color,
                                data: data_object.values
                            }]
                    },

                    options: {
                        scales: {
                            yAxes: [{
                                    ticks: {
                                        beginAtZero: true
                                    }
                                }]
                        }
                    }
                });

            }

        }


        if (precios_options) {
            var echartBar = echarts.init(document.getElementById('precios_chart'), theme);
            echartBar.setOption(precios_options);
        }

        var kpi_data = JSON.parse(kpis_object);

        var chart_doughnut_settings2 = {
            type: 'pie',
            tooltipFillColor: "rgba(51, 51, 51, 0.55)",
            data: {
                labels: kpi_data.labels,
                datasets: [{
                        data: kpi_data.values,
                        backgroundColor: [
                            "#BDC3C7",
                            "#9B59B6",
                            "#E74C3C",
                            "#26B99A",
                            "#3498DB"
                        ],
                        hoverBackgroundColor: [
                            "#CFD4D8",
                            "#B370CF",
                            "#E95E4F",
                            "#36CAAB",
                            "#49A9EA"
                        ]
                    }]
            },
            options: {
                legend: false,
                responsive: false,
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            var allData = data.datasets[tooltipItem.datasetIndex].data;
                            var tooltipLabel = data.labels[tooltipItem.index];
                            var tooltipData = allData[tooltipItem.index];
                            var total = 0;
                            for (var i in allData) {
                                total += parseFloat(allData[i]);
                            }

                            var tooltipPercentage = Math.round((tooltipData / total) * 100);
                            return tooltipLabel + ': ' + tooltipData + ' (' + tooltipPercentage + '%)';
                        }
                    }
                }
            }
        };

        if (typeof (Chart) === 'undefined') {
            return;
        }
        var chart_element2 = document.getElementById("kpisgraph").getContext('2d');
        var chart_doughnut2 = new Chart(chart_element2, chart_doughnut_settings2);


    }

    if ($('#graphsharebycdc').length) {

        var barChartData = {
            labels: JSON.parse(dataShareByCDCLabels),
            datasets: JSON.parse(dataShareByCDCData),
        };

        var ctx = document.getElementById('graphsharebycdc').getContext('2d');
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                title: {
                    display: false,
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                responsive: true,
                scales: {
                    xAxes: [{
                            stacked: true,
                        }],
                    yAxes: [{
                            stacked: true
                        }]
                }
            }
        });
    }


});