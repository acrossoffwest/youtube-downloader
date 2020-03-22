<template>
    <div class="videos-download-form-container">
        <form action="" class="form">
            <div class="form-group">
                <label for="url">Url</label>
                <input type="text" id="url" v-model="videoUrl" class="form-control">
            </div>
        </form>
        <div class="form-group">
            <button @click="loadStart" class="btn btn-success">Start downloading</button>
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
                fetch(this.videosLoadStart, {
                    method: 'POST',
                    body: JSON.stringify({
                        url: this.videoUrl
                    }),
                    headers: {
                        'Content-Type': 'application/json'
                    }
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
