import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'
import Aftershock from '@/components/Aftershock'
import charts from '@/components/charts'

Vue.use(Router)

export default new Router({
  routes: [
    {
      path: '/',
      name: 'HelloWorld',
      component: HelloWorld
    },{
      path: '/Aftershock',
      name: 'Aftershock',
      component: Aftershock
    },{
      path: '/charts',
      name: 'charts',
      component: charts
    }
  ]
})
