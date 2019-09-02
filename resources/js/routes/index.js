import Vue from 'vue'
import Router from 'vue-router'
Vue.use(Router);


import welcome from '../components/ExampleComponent'
import GoogleMap from '../components/GoogleMap'
import AllWorks from '../components/AllWorks'
import CreateWork from '../components/AddWork'
import EditWork from '../components/EditWork'
import PreviewWorkOnMap from '../components/PreviewWork'
import MyProfile from '../components/MyProfile'

export default new Router({
    mode: 'history',
    routes: [
        // { path: '/', component: welcome, name: 'welcome' },
        { path: '/', component: GoogleMap, name: 'GoogleMap' },
        { path: '/all-works', component: AllWorks, name: 'AllWorks' },
        { path: '/create-works', component: CreateWork, name: 'CreateWork' },
        { path: '/create-works/edit', component: EditWork, name: 'EditWork' },
        { path: '/preview-work/', component: PreviewWorkOnMap, name: 'PreviewWork' },
        { path: '/my-profile', component: MyProfile, name: 'MyProfile' },
    ]
});