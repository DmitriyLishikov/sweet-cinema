<script>
import ModalUploadVideo from "@/Components/Videos/ModalUploadVideo.vue";
import Pagination from "@/Components/Pagination.vue";
import { useToast } from 'vue-toast-notification';

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
        toast: useToast(),
    }),
    computed:{
        hasVideos(){
            return this.videos.length > 0;
        },
    },
    created() {
        this.load();
        this.listen();
    },
    methods:{
        load(page = 10){
            axios.get('/api/videos', {params:{page: page}}).then(response => {
                this.response = response.data;
                this.videos = response.data.data;
            })
                .catch(e => {
                    this.toast.error('Не удается загрузить данные.');
                });
        },
        listen(){
            Echo.channel('video.create')
                .listen('CreateVideo', (e) => {
                    this.toast.success('Видео успешно загружено на сервер.');
                });
        },
        roundUp(duration){
            return (duration % 1) === 0 ? duration.toFixed(0) : duration.toFixed(2);
        },
        deleteVideo(id){
            const formData = new FormData();
            formData.append('id', id);
            axios.post('/api/delete-video', formData).then(response => {
                const index = this.videos.findIndex(item => item.id === id);
                if (index !== -1) {
                    this.videos.splice(index, 1);
                }
                this.toast.success('Видео удалено.');
            })
                .catch(e => {
                    this.$toast.error('Произошла ошибка при удалении.');
                });
        }
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

        <div v-if="hasVideos" class="overflow-x-auto">
            <table class="table mt-5">
                <thead>
                    <tr>
                        <th scope="col">Название</th>
                        <th scope="col">Превью</th>
                        <th scope="col">Ссылки</th>
                        <th scope="col">Размер</th>
                        <th scope="col">Длительность</th>
                        <th scope="col">Дата загрузки</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="video in videos" :key="video.id" class="">
                        <td>{{ video.title }}</td>
                        <td>
                            <div style="width: 200px; height: 140px;">
                                <img :src="'/storage/' +video.settings.preview" class="img-thumbnail">
                            </div>
                        </td>
                        <td>
                            <ul class="p-0">
                                <li class="list-unstyled"><a :href="video.settings.path_size_1080">Разрешение 1920x1080</a></li>
                                <li class="list-unstyled"><a :href="video.settings.path_size_480">Разрешение 854 x 480</a></li>
                            </ul>
                        </td>
                        <td>
                            <ul class="p-0">
                                <li class="list-unstyled">{{ video.settings.size_file_1080 }}</li>
                                <li class="list-unstyled">{{ video.settings.size_file_480 }}</li>
                            </ul>
                        </td>
                        <td>{{ roundUp(Number(video.settings.duration)) + 'c' }}</td>
                        <td>{{ video.created_at }}</td>
                        <td><button type="button" class="btn btn-danger" @click="deleteVideo(video.id)">Удалить ролик</button></td>
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
