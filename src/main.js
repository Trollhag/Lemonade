// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import VueRouter from 'vue-router'
import VueResource from 'vue-resource'
import AdminApp from './AdminApp'
import AdminMenu from '@/components/admin/admin-menu'
import RouteMenu from '@/components/admin/route-menu'

import Install from '@/views/admin/install'
import Login from '@/views/login'
import Dashboard from '@/views/admin/dashboard'
import List from '@/views/admin/list'

import Title from '@/components/core/title.js';
import '../lemonade/styles/main.scss';


//Vue.mixin(Mixins)
Vue.use(VueRouter)
Vue.use(VueResource)

Vue.component('AdminApp', AdminApp)
Vue.component('login', Login)
Vue.component('the-title', Title)

if (!window.routes) {
  window.routes = []
}

Vue.config.productionTip = false

window.EventBus = new Vue({
  data() {
    return {
      bodyClass: []
    }
  },
  watch: {
    bodyClass(val) {
      document.getElementsByTagName('body')[0].className = val;
    } 
  }
})

const init = function() {
  window.routes.forEach((route) => {
    route.component = Vue.component(route.component)
    if (route.children) {
      route.children.forEach((child) => {
        child.component = Vue.component(child.component)
      })
    }
  })
  var router = new VueRouter({
    routes: window.routes
  })
  
  new Vue({
    el: '#app',
    router,
    components: { AdminApp },
    template: '<AdminApp/>'
  })
}
Vue.http.get('/api/admin/').then((response) => {
  if (response.body === true) {
    Vue.component('Install', Install)
    Vue.component('admin-menu', AdminMenu)
    Vue.component('route-menu', RouteMenu)
    Vue.component('dashboard', Dashboard)
    Vue.component('lemonade-list', List)
  }
  init()
}, () => { init() })
