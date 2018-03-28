import AdminApp from './AdminApp'
import * as Mixins from './mixins'

import EditTypeText from '@/components/admin/edittype-text'
import AdminMenu from '@/components/admin/admin-menu'
import RouteMenu from '@/components/admin/route-menu'
import Install from '@/views/admin/install'


const Lemonade = {
  install(Vue, options) {
    window.EventBus = new Vue()

    EventBus.$on('body-class', cssClass => {
      let c;
      if (typeof cssClass === "Array") {
        c = cssClass.join(' ')
      }
      else c = cssClass;
      document.getElementsByTagName('body')[0].className = c;
    })
    const opts = options || {} 
    const init = (App) => {  
      var _routes = []
      window.routes.forEach((route) => {
      route.component = Vue.component(route.component)
        if (route.children) {
          route.children.forEach((child) => {
            child.component = Vue.component(child.component)
          })
        }
        _routes.push(route)
      })
      typeof opts.init === "function" && opts.init(Vue.component('App'), _routes);
    }
    let user = Mixins.lemon.status.currentUser;
    if (user && user.role != 0) {
      if (!Vue.http) {
        console.warn(`Lemonade: 'vue-resource' is not installed. Lemonade requires vue resource to enter admin mode!
        To install 'vue-resrouce' run $ npm i vue-resource --save
        Then do Vue.use(VueResource) in your main file.`)
        init()
      }
      else {
        Vue.http.get('/api/admin/').then((response) => {
          if (response.body === true) {
            console.log('Admin check: ')

            Vue.mixin(Mixins)
            // Save App as UserApp, we'll need it later
            Vue.component('UserApp', Vue.component('App'))
            // Init AdminApp instad of regular App
            Vue.component('App', AdminApp)
            // Get admin views
            Vue.component('Install', Install)
            // Get admin components
            Vue.component('admin-menu', AdminMenu)
            Vue.component('route-menu', RouteMenu)
            Vue.component('edittype-text', EditTypeText)
            init()
            return;
          }
        }, () => { init() })
      }
    }
    else {
      Vue.component('Login', Login)
      init();
  }
}

export default Lemonade;

// Automatic installation if Vue has been added to the global scope.
if (typeof window !== 'undefined' && window.Vue) {
  window.Vue.use(Lemonade)
}