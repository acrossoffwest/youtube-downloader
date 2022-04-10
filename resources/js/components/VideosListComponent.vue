<template>
    <div class="videos-list-container">
        <div v-if="videos" class="">
            <div class="row">
                <div class="col-3">
                    <b>Video</b>
                </div>
                <div class="col-3">
                    <b>Status</b>
                </div>
                <div class="col-3">
                    <b>Progress bar</b>
                </div>
                <div class="col-3">
                    <b>Left time</b>
                </div>
            </div>
            <div class="row" v-for="video in videos">
                <div class="col-3">
                    <p>
                        {{ video.title || video.youtube_id }} <span v-if="video.uploaded || video.percent === 100"></span><br>
                        <a :href="`/videos/${video.youtube_id}`">Watch</a> - <a :href="`${video.video_url}`">Video</a></span> - <a :href="`${video.audio_url}`">Audio</a></span>
                    </p>
                </div>
                <div class="col-3">
                    {{ video.status ? video.status : (video.uploaded ? 'Done' : 'Unknown') }}
                </div>
                <div class="col-3">
                    <progress-bar
                        v-if="video.percent"
                        style="width: 140px"
                        :options="options"
                        :value="video.percent"
                    />
                </div>
                <div class="col-3" v-if="video.left_days">
                    {{ video.left_days }} days left
                </div>
                <div class="col-3" v-else>
                    {{ video.left_minutes }} minutes left
                </div>
                <div class="col-12" v-else>
                    <hr>
                </div>
            </div>
            <div class="row">
                <div class="col-3">
                    <b>Video</b>
                </div>
                <div class="col-3">
                    <b>Status</b>
                </div>
                <div class="col-3">
                    <b>Progress bar</b>
                </div>
                <div class="col-3">
                    <b>Left time</b>
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
            axios.get(this.videosListUrl).then(({data}) => {
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
