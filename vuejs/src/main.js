import {createApp} from 'vue'
import store from './store'

import router from './router'
import App from './App.vue'
import {library} from '@fortawesome/fontawesome-svg-core'
import {fas} from '@fortawesome/free-solid-svg-icons'
// import { fab } from '@fortawesome/free-brands-svg-icons'
import {dom} from '@fortawesome/fontawesome-svg-core'
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

library.add(fas)
dom.watch();
// library.add(fab)

const app = createApp(App);
app.use(router).use(store).component('font-awesome-icon', FontAwesomeIcon).mount('#app')
