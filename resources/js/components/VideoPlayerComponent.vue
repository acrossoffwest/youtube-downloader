<template>
    <div class="player-container">
<!--        <vue-core-video-player src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4" ></vue-core-video-player>-->
        <vue-plyr>
            <video :poster="poster" :src="items[0].url">
<!--                http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4-->
                <source v-for="item in items" :src="item.url" :type="item.type" :size="item.size">
            </video>
        </vue-plyr>
    </div>
</template>

<script>
    export default {
        props: {
            poster: {
                required: false,
                type: String,
                default: () => ''
            },
            type: {
                required: false,
                type: String,
                default: () => 'video'
            },
            items: {
                required: true,
                type: Array
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
