<!-- 模态框 -->
<script src="//unpkg.com/vue/dist/vue.js"></script>
<script src="//unpkg.com/element-ui@2.0.7/lib/index.js"></script>
<template>
  <div class="modal-mask" v-show="value" transition="modal">
    <div class="modal-wrapper">
      <div class="modal-container">
        <!-- 头部 -->
        <div class="modal-header">
          <slot name="header"></slot>
        </div>
        <!-- 内容部分 -->
        <!-- 如果不需要保留用户填写信息，可以添加关闭时清除input框中内容的代码 -->
        <div class="modal-body">
           <span>发震震级&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          <input type="Number" v-model="in1" id="input1">
       
        </div>
       <div class="modal-body">
         <span>起震时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          <input type="date" v-model="in2" id="input2">
          
        </div>
        <div class="modal-body">
          <span>预测震级&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          <input type="Number" v-model="in3" id="input3">
          
        </div>
        <div class="modal-body">
          <span>预测时间&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
          <input type="date" v-model="in4" id="input3">
          
        </div>
        <!-- 底部 -->
        <div class="modal-footer">
            <div class="a" >
                <button type="primary" @click="confirm"><div class="footer-a"><h5>提交</h5></div></button>
              </div>
              <div class="b" >
               <button type="danger" @click="cancel"><div class="footer-a"><h5>取消</h5></div></button>                   
              </div>
        </div>
      </div>
    </div>
  </div>
</template>
 
<script>
const axios = require("axios");
export default {
  name:"Modal",
  props: {
    show: {
      type: Boolean,
      default: false
    }
  },
  data(){
    return{
      in1:"",
      in2:"",
      in3:"",
      in4:"",
      value: false
    }
  },
  mounted(){
      this.value = this.show;
  },
  // 监听show数值的变化
  watch:{
    show:{
      handler:function(val,oldval){
        this.value = val;
      }
    }
  },
  verify:{
   earthLevel:[
      "required",
      {
        test:function(val){
          if(val.length>3){
            return false;
          }
          return true;
        },
        message:"姓名不得小于2位"
      }
    ]
  },
   mounted() {
    this.getEarthPreData();
  },
  methods:{
    getEarthPreData(){
      const getEarthPreData = "http://172.17.130.212:8082/ceServer/public/earthquakePrediction/20170101/5/6/7/";
      axios
        .get(getEarthPreData)
        .then(
          function(response) {
            console.log(response)
            if (response.data.reason === "Success") {
              this.preValue = response.data.result;
             
            } else {
              throw new Error("数据请求失败");
            }
          }.bind(this)
        )
        .catch(function(error) {
          console.log(error);
        });
    },
    cancel(){
      
      this.value = false;
     
      // 向父组件传值
      this.$emit('listenToChildEvent',this.value);
    },
    confirm(){
      this.in4=Date.parse(this.in4)/1000;
      this.in2=Date.parse(this.in2)/1000;
      this.in2=this.in4-this.in2;
      this.in2=this.in2/86400;
      console.log(this.in2);
      this.value = false;
      if((this.in1)>10||(this.in1)<=0||(this.in3)>10||(this.in3)<=0||(this.in2)>(this.in4)||! /^(?:0|[1-9]\d*)(?:\.\d{1,2})?$/.test(this.in1)){
          
         this.$message({
              type: 'info',
              message: '您填写的数据有误！'
            });
     
          this.value=true;//点击确定虽然值不传入，但是窗口仍传入
      }
      //填写正确后跳转
       this.$alert('未来'+this.in2+'天发生'+this.in3+'级地震的概率是'+this.preValue, '余震算法分析参考结果', {
          confirmButtonText: '确定',
        });
      // 向父组件传值
      this.$emit('listenToChildEvent',this.value);
    },
  }
}
</script>
 
<style scoped>
.a {
    float: left;
    width: 50%;
   
}
span{
    color: black;
}
h5{
    color: black;
    position: relative;
    top: -12px;
    font-size: 14px;
}

button{
  width: 96px;
  height: 45px;
}
button:hover{
  background-color:rgba(0, 72, 129, 0.33);
  border: solid 1px;
  box-shadow:2px 2px 3px #aaaaaa;
}
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}
 
.modal-wrapper {
  display: table-cell;
  vertical-align: middle;
}
 
.modal-container {
  width: 45%;
  margin: 0px auto;
  padding: 20px 30px;
  background-color: #fff;
  border-radius: 2px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, .33);
  transition: all .3s ease;
  font-family: Helvetica, Arial, sans-serif;
}
 
.modal-header h3 {
  margin-top: 0;
  color: #5690ad;
}
 
.modal-body {
  margin: 20px 0;
  border-bottom: 1px solid #ddd;
  
}
  .label{
    width: 30%;
    display: inline-block;
    vertical-align: middle;
  }

.modal-footer{
  text-align: center;
}
 
.modal-default-button {
  float: right;
}
 
.modal-enter, .modal-leave {
  opacity: 0;
}
 
.modal-enter .modal-container,
.modal-leave .modal-container {
  -webkit-transform: scale(1.1);
  transform: scale(1.1);
}
#input1,#input2,#input3,#input4 {
    width: 188px;
    height: 45px;
}
input#input1,input#input2,input#input3,input#input4 {
    position: relative;
    top: -8px;
    left: 31%;
    /* float: left; */
}

span {
    position: relative;
    float: left;
    top: 10px;
    left: 24%;
}
</style>