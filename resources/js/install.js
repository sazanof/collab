import '../css/app.scss'
import i18n from './i18n'
console.log('INSTALL INIT')
import { createApp } from 'vue'
import Install from '../components/install/Install.vue'

    const app = createApp(Install)
    //app.config.globalProperties.emitter = emitter
    app.use(i18n)
    //app.use(createRouter)
    //app.use(store)
    //app.use(Toast, options)
    app.mount('#install')
//createApp(Install).mount('#install')