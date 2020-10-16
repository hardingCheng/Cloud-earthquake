<template>
<div class="inter">
<div class="Video">  
  <div class="Videoborder"> 
      <div class="charts">
        <div class = "chart-item chartDiv1">
            <div id="linechart"></div>
            <div class="lineput"> 
                <span class="magtitle">当前震级</span>
                <span class="mag" v-on:click="i=1">一级</span>
                <span class="mag" v-on:click="i=2">二级</span>
                <span class="mag" v-on:click="i=3">三级</span>
                <span class="mag" v-on:click="i=4">四级</span>
                <span class="mag" v-on:click="i=5">五级</span>
                <span class="mag" v-on:click="i=6">六级</span>
                <span class="mag" v-on:click="i=7">七级</span>
                <span class="mag" v-on:click="i=8">八级</span>
            </div>
        </div>
        <div class="chart-item chartDiv2">
          <div id="oa-chart"></div>
          <div class="oa-put">
            <select v-model="year" style="width:200px;" size="1">
              <option v-for="item in yearList":value="item.value":key="item.value" v-bind:year="item.value">{{item.label}}</option>
            </select>
            <el-button @click="addChart">叠加分析</el-button>
            <el-button @click="clearChart">清除</el-button>
          </div>
        </div>
        <div class="chart-item chartDiv3">
          <div id="sd-chart"></div>
        </div>
        <div class="chart-item chartDiv4">
          <div id="bt-chart"></div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
const echarts = require("echarts");
const axios = require("axios");

export default {
  data() {
    return {
      SLChart: {},
      BTChart: {},
      BTMyChart: {},
      BTCOption: {},
      SDChart: {},
      SDMyChart: {},
      SDOption: {},
      lineChart: {},
      lineOption: {},
      year: "2017",
      yearList: [
        {
          value: "2017",
          label: "2017"
        },
        {
          value: "2016",
          label: "2016"
        },
        {
          value: "2015",
          label: "2015"
        },
        {
          value: "2014",
          label: "2014"
        },
        {
          value: "2013",
          label: "2013"
        },
        {
          value: "2012",
          label: "2012"
        },
        {
          value: "2011",
          label: "2011"
        },
        {
          value: "2010",
          label: "2010"
        },
        {
          value: "2009",
          label: "2009"
        },
        {
          value: "2008",
          label: "2008"
        },
        {
          value: "2007",
          label: "2007"
        },
        {
          value: "2006",
          label: "2006"
        },
        {
          value: "2005",
          label: "2005"
        },
        {
          value: "2004",
          label: "2004"
        },
        {
          value: "2003",
          label: "2003"
        }
      ],
      chart: {},
      myChart: {},
      chartOption: {},
      dataOfLegend: [],
      index: 0,
      series: [],
      i: 1,
      max: 0
    };
  },
  computed: {
    getLinedata() {
      return this.$store.state.linedata;
    },
    getGradeData() {
      return this.$store.state.gradeData;
    },
    getTimelinedata() {
      return this.$store.state.timelinedata;
    },
    getBTNdata() {
      const array = [];
      this.$store.state.linedata.data.forEach(val => {
        let o = 0;
        val.forEach(value => {
          o += parseInt(value);
        });
        array.push(o);
      });
      return array;
    },
    getBTWdata() {
      const array = [];
      this.$store.state.chartdata.data.forEach(val => {
        let o = 0;
        val.forEach(value => {
          o += parseInt(value);
        });
        array.push(o);
      });
      return array;
    },
    getChartdata() {
      return this.$store.state.chartdata;
    }
  },
  mounted() {
    this.initSLChart();
    this.initChart();
    this.initBTChart();
    this.initSDChart();
  },
  watch: {
    i: "initSLChart"
  },
  methods: {
    initSLChart() {
    	this.max = Math.max.apply(null, this.getLinedata.data[this.i - 1])
	    this.SLChart = document.getElementById("linechart");
	    this.lineChart = echarts.init(this.SLChart);
	    this.lineOption = {
      	visualMap: [{
      		show: false,
      		type: 'continuous',
      		min: -1,
      		max: this.max
      	}],
        title: {
          text: "",
          left: "center"
        },
        toolbox: {
          show: true,
          feature: {
            dataZoom: {
              yAxisIndex: "none"
            },
            dataView: { readOnly: false },
            magicType: { type: ["line", "bar"] },
            restore: {},
            saveAsImage: {}
          }
        },
        tooltip: {
          trigger: "axis",
          axisPointer: {
            // 坐标轴指示器，坐标轴触发有效
            type: "cross", // 默认为直线，可选为：'line' | 'shadow' | 'cross'
            label: {
            	precision: 0
            }
          }
        },
        grid: {
          left: "10%",
          right: "10%",
          bottom: "3%",
          containLabel: true
        },
        xAxis: [
          {
          	name: '年份',
            type: "category",
            boundaryGap: false,
            data: []
          }
        ],
        yAxis: [
          {
          	name: '地震频次',
            type: "value"
          }
        ],
        series: [
          {
            name: "地震发生次数",
            type: "line",
            stack: "总量",
            data: []
          }
        ]
      };
      this.lineOption.title.text = this.i + "级地震频率次数";
      this.getLinedata.data[this.i - 1].forEach((val, index) => {
        this.lineOption.series[0].data.push(val);
        this.lineOption.xAxis[0].data.push(this.getLinedata.title[index]);
      });
      this.lineChart.setOption(this.lineOption);
    },
    initChart() {
      this.chart = document.getElementById("oa-chart");
      this.myChart = echarts.init(this.chart);
      this.chartOption = {
        title: {
          text: "地震趋势分析",
          left: "center"
        },
        toolbox: {
          show: true,
          feature: {
            dataZoom: {
              yAxisIndex: "none"
            },
            dataView: { readOnly: false },
            magicType: { type: ["line", "bar"] },
            restore: {},
            saveAsImage: {}
          }
        },
        legend: {
          data: this.dataOfLegend,
          type: "scroll",
          orient: "vertical",
          right: -5,
          top: '10%'
        },
        grid: {
          left: "5%",
          right: "15%",
          bottom: "3%",
          containLabel: true
        },
        tooltip: {
          trigger: "axis",
          axisPointer: {
            // 坐标轴指示器，坐标轴触发有效
            type: "cross", // 默认为直线，可选为：'line' | 'shadow' | 'cross'
            label: {
            	precision: 0
            }
          }
        },
        xAxis: {
          name: '震级',
          data: ["一级", "二级", "三级", "四级", "五级", "六级", "七级", "八级"],
          axisTick: {
            alignWithLabel: true
          }
        },
        yAxis: {
          name: ["地震次数"]
        },
        series: this.series
      };
      this.dataOfLegend.push('2017年')
      this.series.push({
      	name: '2017年',
      	type: 'line',
      	data: this.getGradeData[14]
      })
      this.myChart.setOption(this.chartOption);
    },
    addChart() {
      // console.log(this.gradeData[0])
      if (this.year == "") {
         this.$message({
              type: 'info',
              message: '请选择年份！'
            });
      } else {
        var newSeries = {
          name: this.year + "年",
          type: "line",
          data: this.getGradeData[this.year - 2003]
        };
        if (this.index < 10) {
          this.dataOfLegend.push(newSeries.name);
          this.series.push(newSeries);
          this.myChart.setOption(this.chartOption);
          this.index++;
        } else {
          this.$message({
              type: 'info',
              message: '不能超过十年！'
            });
        }
      }
    },
    initBTChart() {
      // console.log(this.getBTWdata);
      this.BTChart = document.getElementById("bt-chart");
      this.BTMyChart = echarts.init(this.BTChart);
      this.BTCOption = {
        title: {
          text: "地震数量对比图",
          left: "center"
        },
        tooltip: {
          trigger: "item",
          formatter: "{a} <br/>{b}: {c} ({d}%)"
        },
        legend: {
          type: "scroll",
          orient: "vertical",
          x: "right",
          data: []
        },
        color: ['#c23531','#2f4554', '#61a0a8', '#d48265', '#91c7ae','#749f83',  '#ca8622', '#bda29a','#6e7074', '#546570', '#e29b8c', '#585057', '#B06E93', '#AFB0CB', '#7DD1CA'],
        series: [
          {
            name: "地震分析",
            type: "pie",
            radius: ["0", "55%"],
            data: []
          }
        ]
      };
      this.getBTWdata.forEach((value, index) => {
        this.BTCOption.series[0].data.push({
          value: value,
          name: parseInt(index + 2003) + "年地震总数"
        });
        this.BTCOption.legend.data.push(parseInt(index + 2003) + "年地震总数");
      });
      this.BTMyChart.setOption(this.BTCOption);
    },
    initSDChart() {
      this.SDChart = document.getElementById("sd-chart");
      this.SDMyChart = echarts.init(this.SDChart);
      this.SDOption = {
        title: {
          text: "逐年震级雷达图",
          left:"center"
        },
        tooltip: {},
        legend: {
          type: "scroll",
          orient: "vertical",
          x: "right",
          data: []
        },
        radar: {
          indicator: [
            { name: "1级",max:34000},
            { name: "2级",max:42100},
            { name: "3级",max:10100},
            { name: "4级",max:3000},
            { name: "5级",max:1000},
            { name: "6级",max:200},
            { name: "7级",max:30},
            { name: "8级",max:10}
          ]
        },
        color: ['#c23531','#2f4554', '#61a0a8', '#d48265', '#91c7ae','#749f83',  '#ca8622', '#bda29a','#6e7074', '#546570', '#e29b8c', '#585057', '#B06E93', '#AFB0CB', '#7DD1CA'],
        series: [
          {
            name: "预算 vs 开销（Budget vs spending）",
            type: "radar",
            // areaStyle: {normal: {}},
            data: []
          }
        ]
      };
      // console.log(this.getChartdata)
      this.getChartdata.title.forEach((value,index) => {
        this.SDOption.legend.data.push(value);
        this.SDOption.series[0].data.push({value:this.getChartdata.data[index],name:value})
      });
      this.SDMyChart.setOption(this.SDOption);
    },
    clearChart() {
    	this.dataOfLegend.splice(0,this.dataOfLegend.length)
    	this.series.splice(0,this.series.length)
    	this.index = 0
    	this.year = ""
    	this.myChart.setOption(this.chartOption,true)
    }
  }
};
</script>

<style>
.charts {
  width: 100%;
  height: 100%;
  margin: 7px auto;
}
.chart-item {
  width: 48%;
  height: 48%;
  display: inline-block;
}
#linechart {
  height: 80%;
  width: 100%;
}
#lineRange {
  width: 60%;
}
.lineput {
  width: 498px;
  margin: 0 auto;
  height: 23px;
  text-align: center;
}
.magtitle {
  border: 1px solid #000;
  width: 80px;
  height: 23px;
  display: block;
  float: left;
}
.mag {
  border: 1px solid #000;
  width: 50px;
  height: 23px;
  display: block;
  float: left;
  cursor: pointer;
}
.mag:hover {
  background-color: #f08080;
}
#oa-chart {
  height: 80%;
  width: 100%;
}
.oa-put {
  text-align: center;
}
#bt-chart {
  height: 100%;
  width: 100%;
}
#sd-chart {
  height: 100%;
  width: 100%;
}
.Video {
  height: -webkit-fill-available;
 width: 100%;
 border: 0.2px;
 margin: auto;
 background-color: #f3f3f3;
 position: absolute;
 height: 100%;

}
.Videoborder {
  width: 90%;
height: 84%;
background-color: white;
border: solid 0.02px;
border-color: #dde3e8;
margin:7px auto;
padding: 20px 20px 40px;
top: 2px;
}
</style>
