// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import Lemonade from './admin.js'
import App from './App'
import Start from '@/views/start'

import Title from '@/components/core/title.js';


//Vue.mixin(Mixins)
Vue.use(VueRouter)
Vue.use(VueResource)

Vue.component('App', App)
Vue.component('the-title', Title)
Vue.component('Start', Start)

if (!window.routes) {
  window.routes = []
}

Vue.config.productionTip = false

Vue.use(Lemonade, {
  init: (App, routes) => {
    var router = new VueRouter({
      routes: routes
    })

    new Vue({
      el: '#app',
      router,
      components: { App },
      template: '<App/>'
    })
  }
})
