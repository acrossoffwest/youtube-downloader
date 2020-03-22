/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
window.Pusher = require('pusher-js');

import VueCoreVideoPlayer from 'vue-core-video-player'
import VuePlyr from 'vue-plyr'
import Echo from "laravel-echo";
import ProgressBar from 'vuejs-progress-bar'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.use(ProgressBar)
Vue.use(VueCoreVideoPlayer);
// The second argument is optional and sets the default config values for every player.
Vue.use(VuePlyr, {
    plyr: {
        fullscreen: { enabled: false }
    },
    emit: ['ended']
})
Vue.component('video-download-form-component', require('./components/VideoDownloadFormComponent').default);
Vue.component('videos-list-component', require('./components/VideosListComponent').default);
Vue.component('video-player-component', require('./components/VideoPlayerComponent').default);
Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    created () {
        window.Echo = new Echo({
                broadcaster: 'pusher',
                key: PUSHER_APP_KEY,
                cluster: PUSHER_APP_CLUSTER,
            });
    },
    mounted () {
        window.Echo.channel('uploading-file.id')
            .listen('.progress', (e) => {
                this.value = e.progressData.percent
            })
    },
    data () {
        return {
            value: 50,
            options: DEFAULT_PROGRESS_BAR_OPTIONS
        }
    }
});
