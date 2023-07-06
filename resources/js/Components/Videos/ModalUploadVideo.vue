<template>
    <div class="modal d-block" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Окно загрузки видео</h5>
                </div>
                <div class="modal-body">
                    <div class="mb-3" v-if="errors.length">
                        <p>
                            <b>Пожалуйста исправьте указанные ошибки:</b>
                            <ul>
                                <li v-for="error in errors">{{ error }}</li>
                            </ul>
                        </p>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Выберите или перетащите нужное видео</label>
                        <input
                            id="file"
                            type="file"
                            ref="file"
                            @change="uploadFile"
                            accept="video/*"
                            class="form-control"
                        />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" @click="closeModal">Отмена</button>
                    <button type="button" class="btn btn-primary" :disabled="isBusy" @click="saveVideo()">Загрузить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {toType} from "bootstrap/js/src/util/index.js";
import { useToast } from 'vue-toast-notification';

export default {
    name: 'modal-upload-video',

    data: () => ({
        isBusy: false,
        errors: [],
        file: '',
        toast: useToast(),
    }),
    methods: {
        getExtension(){
            let parts = this.file.name.split('.');
            return parts[parts.length - 1];
        },
        isVideo(){
            switch (this.getExtension().toLowerCase()) {
                case 'ogg':
                case 'mov':
                case 'webm':
                case 'mp4':
                    return true;
            }
            return false;
        },
        validation(){
            this.errors = [];

            if (!this.file) {
                this.errors.push('Поле видео не может быть пустым.');
            }

            if(this.file && !this.isVideo()){
                this.errors.push('Разрешено загружать только видео файлы.');
            }

            if(Number(this.file.size) > 10000000){
                this.errors.push('Загружаемый файл превышает размер 10mb.');
            }

            if(this.errors.length > 0){
                return false;
            }

            if(this.file){
                return true;
            }
        },
        saveVideo(){
            if(this.validation()){
                this.isBusy = true;
                const headers = { 'Content-Type': 'multipart/form-data' };
                const formData = new FormData();
                formData.append('file', this.file);
                axios.post('/api/upload-video', formData, {headers})
                    .then(r => {
                        console.log(r.data);
                        this.toast.success('Загрузка видео начата.');
                        this.closeModal();
                    })
                    .catch(err => {
                        this.toast.error('Произошла ошибка при загрузке.');
                        console.log(err.response.data.message);
                        // if (err.response.status === 422) {
                        //     this.errors.fromResponse(err);
                        // }
                        // this.$toast.error({title: 'Не удалось сохранить видео', message: err.response.data.message});
                    })
                    .finally(() => this.isBusy = false);
            }
        },
        uploadFile() {
            this.file = this.$refs.file.files[0];
            console.log(this.file);
        },
        closeModal(){
            this.$emit('close');
        },
    },
};
</script>

