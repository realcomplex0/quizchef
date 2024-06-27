<script>
import DeleteButton from '@/Components/DeleteButton.vue';

export default {
    data() {
        return {
            currentImageUrl: null
        };
    },
    props : ['imgUrl'],
    watch : {
        imgUrl(newImageUrl) {
            this.currentImageUrl = newImageUrl
        }
    },
    mounted () {
        this.currentImageUrl = this.imgUrl
    },
    methods: {
        openFileInput() {
            this.$refs.fileInput.click();
        },
        handleFileChange(event) {
            const file = event.target.files[0];
            if (file) {
                this.previewImage(file);
            }
        },
        previewImage(file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                this.currentImageUrl = e.target.result;
                this.$emit('img-save', file, e.target.result)
            };
            reader.readAsDataURL(file);
        },
        removeImage() {
            this.currentImageUrl = null
            this.$emit('img-delete')
        },
    },
    components: {
        DeleteButton
    }
};
</script>

<template>
<div>
    <button v-if="!currentImageUrl" class="h-40 w-80 border-4 border-dotted rounded-xl flex items-center justify-center flex-col" @click="openFileInput">
        <p class="text-2xl text-red-500">{{ $global.lang.editQuiz.uploadImage}} </p>
        <p class="text-red-500">{{ $global.lang.editQuiz.maxSize}}</p>
        <input
            id="file-upload"
            type="file"
            class="hidden"
            ref="fileInput"
            @change="handleFileChange"
            accept="image/*"
        />
    </button>
    <div v-else class="relative select-none">
        <img @click="logger" :src="currentImageUrl" alt="Uploaded Image" class="h-auto max-w-80 outline outline-4 outline-blue-500 rounded select-none"/>
        <DeleteButton @clicked="removeImage" class="absolute top-2 left-2"/>
    </div>
</div>
</template>