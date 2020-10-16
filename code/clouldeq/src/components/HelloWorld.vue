<template>
  <div class="hello">
      <div id="dataview"></div>
      <div id="datachart"></div>
  </div>
</template>

<script>
const echarts = require("echarts");
const bmap = require("echarts/extension/bmap/bmap");
const jquery = require("jquery");
const fault = require("../assets/fault");
import store from "@/stores/stores";

export default {
  name: "HelloWorld",
  data() {
    return {
      dataView: {},
      datachart: {},
      mapChart: {},
      chartChart: {},
      mapOption: {},
      mapcontrol: {},
      chartOption: {}
    };
  },
  computed: {
    getTimelinedata() {
      return this.$store.state.timelinedata;
    },
    getChartdata() {
      return this.$store.state.chartdata;
    }
  },
  watch:{
    getTimelinedata: function () {
      this.initChart();
      this.changeChart();
      this.initlineChart();
    }
  },
  mounted () {
      this.initChart();
      this.changeChart();
      this.initlineChart();
  },
  methods: {
    initChart() {
      this.dataView = document.getElementById("dataview");
      this.mapChart = echarts.init(this.dataView);
      this.mapChart.showLoading();
    },
    changeChart() {
      const title = [
        2003,
        2004,
        2005,
        2006,
        2007,
        2008,
        2009,
        2010,
        2011,
        2012,
        2013,
        2014,
        2015,
        2016,
        2017
      ];
      function option() {
        (this.bmap = {
          // 百度地图中心经纬度
          center: [108.5525, 34.3227],
          // 百度地图缩放
          zoom: 5,
          // 是否开启拖拽缩放，可以只设置 'scale' 或者 'move'
          roam: false,
          // 百度地图的自定义样式，见 http://developer.baidu.com/map/jsdevelop-11.htm
          mapStyle: {
            styleJson: [
              {
                featureType: "water",
                elementType: "all",
                stylers: {
                  color: "#ffffff"
                }
              },
              {
                featureType: "land",
                elementType: "all",
                stylers: {
                  color: "#004981"
                }
              },
              {
                featureType: "boundary",
                elementType: "geometry",
                stylers: {
                  color: "#064f85"
                }
              },
              {
                featureType: "railway",
                elementType: "all",
                stylers: {
                  visibility: "off"
                }
              },
              {
                featureType: "highway",
                elementType: "geometry",
                stylers: {
                  color: "#004981"
                }
              },
              {
                featureType: "highway",
                elementType: "geometry.fill",
                stylers: {
                  color: "#005b96",
                  lightness: 1
                }
              },
              {
                featureType: "highway",
                elementType: "labels",
                stylers: {
                  visibility: "off"
                }
              },
              {
                featureType: "arterial",
                elementType: "geometry",
                stylers: {
                  color: "#004981"
                }
              },
              {
                featureType: "arterial",
                elementType: "geometry.fill",
                stylers: {
                  color: "#00508b"
                }
              },
              {
                featureType: "poi",
                elementType: "all",
                stylers: {
                  visibility: "off"
                }
              },
              {
                featureType: "green",
                elementType: "all",
                stylers: {
                  color: "#056197",
                  visibility: "off"
                }
              },
              {
                featureType: "subway",
                elementType: "all",
                stylers: {
                  visibility: "off"
                }
              },
              {
                featureType: "manmade",
                elementType: "all",
                stylers: {
                  visibility: "off"
                }
              },
              {
                featureType: "local",
                elementType: "all",
                stylers: {
                  visibility: "off"
                }
              },
              {
                featureType: "arterial",
                elementType: "labels",
                stylers: {
                  visibility: "off"
                }
              },
              {
                featureType: "boundary",
                elementType: "geometry.fill",
                stylers: {
                  color: "#029fd4"
                }
              },
              {
                featureType: "building",
                elementType: "all",
                stylers: {
                  color: "#1a5787"
                }
              },
              {
                featureType: "label",
                elementType: "all",
                stylers: {
                  visibility: "on"
                }
              }
            ]
          }
        }),
          (this.title = {
            right: '6%',
            textStyle: {
              align: "center",
              fontSize: 36,
              fontWeight: "normal",
            }
          });
        (this.tooltip = {
          trigger: "item",
          formatter: ((params) => {
            if(params.componentSubType == "scatter"){
              return "经度："+params.data[0]+"<br>纬度："+params.data[1]+"<br>震级："+params.data[2];
            }
            else{
              return "名称："+params.data.name+"<br>经度："+params.data.value[0]+"<br>纬度："+params.data.value[1]+"<br>震级："+params.data.value[2];
            }
          })
        }),
          (this.series = [
            {
              name: null,
              type: "scatter",
              // 使用百度地图坐标系
              coordinateSystem: "bmap",
              // 数据格式跟在 geo 坐标系上一样，每一项都是 [经度，纬度，数值大小，其它维度...]
              data: [],
              label: {
                normal: {
                  formatter: "{c}",
                  position: "right",
                  show: false
                }
              },
              itemStyle: {
                normal: {
                  color: ""
                }
              }
            },
            {
              type: "effectScatter",
              coordinateSystem: "bmap",
              data: [
                { name: "1976年7月28日唐山大地震", value: [118.2, 39.6, 7.8] },
                { name: "2008年5月12日汶川地震", value: [103.42, 31.01, 8.0] },
                { name: "2010年4月14日玉树地震", value: [96.6, 33.1, 7.1] },
                { name: "2017年8月8日九寨沟地震", value: [103.82, 33.2, 7.0] },
                { name: "2013年4月20日雅安地震", value: [103.0, 30.3, 7.0] }
              ],
              symbolSize: function(val) {
                return val[2] * 3;
              },
              showEffectOn: "render",
              rippleEffect: {
                brushType: "fill"
              },
              hoverAnimation: true,
              label: {
                normal: {
                  formatter: "{b}",
                  position: "right",
                  show: true
                }
              },
              itemStyle: {
                normal: {
                  color: "#c23531",
                  shadowBlur: 10,
                  shadowColor: "#c23531"
                }
              }
            }
          ]);
      }

      this.mapOption = {
        timeline: {
          axisType: "category",
          orient: "vertical",
          autoPlay: true,
          show: true,
          inverse: true,
          playInterval: 3000,
          right: -50,
          top: 70,
          bottom: 20,
          width: 200,
          height: 500,
          label: {
            normal: {
              position: 50,
              textStyle: {
                color: "#000"
              }
            },
            emphasis: {
              position: 50,
              textStyle: {
                color: "#fff"
              }
            }
          },
          symbol: "none",
          lineStyle: {
            color: "#004981"
          },
          checkpointStyle: {
            color: "#bbb",
            borderColor: "#777",
            borderWidth: 2
          },
          controlStyle: {
            showNextBtn: false,
            showPrevBtn: false,
            normal: {
              color: "#666",
              borderColor: "#666"
            },
            emphasis: {
              color: "#aaa",
              borderColor: "#aaa"
            }
          },
          data: title
        },
        legend: {
          type: "scroll",
          show: true
        },
        options: []
      };
      const that = this;
      const colorArray = [
        "#f7d30f",
        "#f5c011",
        "#f1ae13",
        "#f0aa13",
        "#e8971e",
        "#d6752f",
        "#f3632b",
        "#a7675d",
        "#e1c072",
        "#e9b437",
        "#cc7958",
        "#f66e37",
        "#eca851",
        "#a7675d",
        "#f1ae13"
      ];
      this.getTimelinedata.forEach((val, index) => {
        const opt = new option();
        opt.series[0].name = title[index];
        opt.series[0].itemStyle.normal.color = colorArray[index];
        opt.series[0].data = val;
        opt.title.text = title[index];
        that.mapOption.options.push(opt);
      });
      this.mapChart.setOption(this.mapOption);
      this.mapChart.hideLoading();
      const bmap = this.mapChart.getModel().getComponent("bmap").getBMap();
      this.setFault(bmap);
    },
    initlineChart() {
      function option(data, title) {
        (this.xAxis = [
          {
            data: ["一级", "二级", "三级", "四级", "五级", "六级", "七级", "八级"]
          }
        ]),
          (this.title = {
            text: title,
            subtext: "单位:次"
          }),
          (this.series = [
            {
              data: data
            }
          ]);
      }
      this.datachart = document.getElementById("datachart");
      this.chartChart = echarts.init(this.datachart);
      this.chartOption = {
        //timeline基本配置都写在baseoption 中
        baseOption: {
          timeline: {
            //loop: false,
            axisType: "category",
            show: true,
            autoPlay: true,
            playInterval: 3000,
            data: []
          },
          grid: { containLabel: true },
          xAxis: [
            {
              type: "category",
              name: "震级"
            }
          ],
          yAxis: { type: "value", name: "次" },
          series: [
            {
              itemStyle: {
                normal: {
                  color: "#19273b"
                }
              },
              type: "bar"
            }
          ],
          barWidth: "20px",
          tooltip: {}
        },
        //变量则写在options中
        options: []
      };
      this.chartOption.baseOption.timeline.data = this.getChartdata.title;
      this.getChartdata.data.forEach((value, index) => {
        this.chartOption.options.push(
          new option(value, this.getChartdata.title[index] + "地震频率")
        );
      });
      this.chartChart.setOption(this.chartOption);
    },
    setFault(bmap) {
      const myobj = eval(fault);
      let t = 0,
        t1 = 1;
      let result = [];
      for (var i = 0; i < myobj.length; i++) {
        t = myobj[i].line;
        if (t > t1) {
          let polyline = new BMap.Polyline(result, {
            strokeColor: "yellow",
            strokeWeight: 2,
            strokeOpacity: 0.5
          });
          bmap.addOverlay(polyline);
          result = [];
        }
        t1 = t;
        const points = new BMap.Point(myobj[i].baidu_X, myobj[i].baidu_Y);
        result.push(points);
      }
      let polyline = new BMap.Polyline(result, {
        strokeColor: "yellow",
        strokeWeight: 2,
        strokeOpacity: 0.5
      });
      bmap.addOverlay(polyline);
    }
  }
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.hello {
  height: 94%;
}
#dataview {
  height: 94%;
  width: 100%;
  position: absolute;
  bottom: 0;
  left: 0;
}
#datachart {
  position: absolute;
  bottom: 0;
  right: 0;
  height: 35%;
  width: 35%;
}
</style>
