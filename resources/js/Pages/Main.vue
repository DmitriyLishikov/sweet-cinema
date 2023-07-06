<script>
import ModalUploadVideo from "@/Components/Videos/ModalUploadVideo.vue";
import Pagination from "@/Components/Pagination.vue";

export default {
    name: 'main-page',
    components: {
        ModalUploadVideo,
        Pagination,
    },
    data:() => ({
        videos: [],
        response: [],
        showModal: false,
    }),
    computed:{
        hasVideos(){
            return this.videos.length > 0;
        },
    },
    created() {
        this.load();
        // this.listen();
    },
    methods:{
        load(page = 1){
            axios.get('/api/videos', {params:{page: page}}).then(response => {
                this.response = response.data;
                this.videos = response.data.data;
                console.log(this.videos);
            })
                .catch(e => {
                    // this.$toast.error({title: 'Не удалось загрузить заказы.', message: e.response.data.message});
                    console.log('error load');
                });
        },
        // listen(){
        //     Echo.private('App.Orders')
        //         .listen('.Created',event=> this.orders.unshift(event.order));
        // },
    },
}

</script>

<template>
    <div class="container flex flex-col justify-center">
        <div class="card border-0 m-2">
            <div class="card-body d-flex justify-content-end">
                <button type="button" class="btn btn-success" @click="showModal = true">Загрузить видео</button>
            </div>
        </div>

        <div v-if="hasVideos">
            <table class="table mt-5">
                <thead>
                    <tr>
                        <th scope="col">Название</th>
                        <th scope="col">Превью</th>
                        <th scope="col">Размер</th>
                        <th scope="col">Длительность</th>
                        <th scope="col">Дата загрузки</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="video in videos" :key="video.id">
                        <td>{{ video.id }}</td>
                        <td>{{ video.title }}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
            <pagination :response="response"
                        @load=load></pagination>
        </div>
        <div v-else>
            <p class="text-center fw-bold">Нет доступных материалов</p>
        </div>
    </div>

    <modal-upload-video v-if="showModal" @close="showModal = false"></modal-upload-video>
</template>
