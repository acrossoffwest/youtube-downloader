<template>
    <div class="videos-list-container">
        <div v-if="videos" class="">
            <div class="row">
                <div class="col-4">
                    <b>Video</b>
                </div>
                <div class="col-4">
                    <b>Status</b>
                </div>
                <div class="col-4">
                    <b>Progress bar</b>
                </div>
            </div>
            <div class="row" v-for="video in videos">
                <div class="col-4">
                    <a :href="`/videos/${video.youtube_id}`">{{ video.youtube_id }}</a>
                </div>
                <div class="col-4">
                    {{ video.status ? video.status : (video.uploaded ? 'Done' : 'Unknown') }}
                </div>
                <div class="col-4">
                    <progress-bar
                        v-if="video.percent"
                        style="width: 140px"
                        :options="options"
                        :value="video.percent"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-4">
                    <b>Video</b>
                </div>
                <div class="col-4">
                    <b>Status</b>
                </div>
                <div class="col-4">
                    <b>Progress bar</b>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                videosListUrl: '/api/videos',
                videos: null,
                options: DEFAULT_PROGRESS_BAR_OPTIONS
            }
        },
        mounted() {
            fetch(this.videosListUrl).then((r) => {
                return r.json();
            }).then((data) => {
                this.videos = data

                this.videos.forEach((item, index) => {
                    if (!item.uploaded || !item.isListening) {
                        this.listenLoadingOfFile(item, index)
                        this.videos.isListening = true
                    }
                })
            }).then(() => {
                window.Echo.channel('uploading-file')
                    .listen('.new', (e) => {
                        this.videos.push(e.file)
                        const index = this.videos.length

                        setTimeout(() => {
                            this.videos.forEach((item, index) => {
                                if (!item.uploaded || !item.isListening) {
                                    this.listenLoadingOfFile(item, index)
                                    this.videos.isListening = true
                                }
                            })
                        }, 1000)
                    })
            })
        },
        methods: {
            listenLoadingOfFile (v, index) {
                window.Echo.channel('uploading-file.' + v.youtube_id)
                    .listen('.progress', (e) => {
                        let item = this.videos[index]
                        item.percent = e.progressData.percent
                        item.status = e.progressData.status
                        this.videos.splice(index, 1, item)
                    })
            }
        }
    }
</script>
