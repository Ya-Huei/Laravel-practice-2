import 'core-js/stable'
import Vue from 'vue'
import App from './App'
import router from './router'
import CoreuiVue from '@coreui/vue'
import { iconsSet as icons } from './assets/icons/icons.js'
import store from './store'

import VueI18n from 'vue-i18n'  // 引入 Vue I18n
import zh from './i18n/zh'      // 存放中文語系檔
import en from './i18n/en'      // 存放英文語系檔

Vue.config.performance = true
Vue.use(VueI18n)
Vue.use(CoreuiVue)

let locale = 'zh';

const i18n = new VueI18n({
  locale: locale,
  messages: {
    'zh': zh,
    'en': en
  }
});

new Vue({
  el: '#app',
  router,
  store,  
  icons,
  i18n,
  template: '<App/>',
  components: {
    App
  },
})
