<template>
    <div class="videos-download-form-container">
        <div class="row">
            <div class="col-md-10">
                    <div class="form-group">
                        <input type="text" id="url" v-model="videoUrl" class="form-control" placeholder="URL">
                    </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <button @click="loadStart" class="btn btn-success">Download</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                videosLoadStart: '/api/videos',
                videoUrl: null,
                options: DEFAULT_PROGRESS_BAR_OPTIONS
            }
        },
        mounted() {

        },
        methods: {
            loadStart () {
                if (!this.videoUrl) {
                    return alert('You have to fill URL input.');
                }
                axios.post(this.videosLoadStart, {
                    url: this.videoUrl
                }).then((r) => {
                    if (r.status !== 200) {
                        return alert('Something went wrong.');
                    }
                    this.videoUrl = null
                })
            }
        }
    }
</script>
