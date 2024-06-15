<script>
import DeleteButton from '@/Components/DeleteButton.vue';

export default {
    data() {
        return {
            imageUrl: null
        };
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
                this.imageUrl = e.target.result;
            };
            reader.readAsDataURL(file);
        },
        removeImage() {
            this.imageUrl = null
        }
    },
    components: {
        DeleteButton
    }
};
</script>

<template>
<div>
    <div v-if="!imageUrl" class="h-40 w-80 border-4 border-dotted rounded-xl flex items-center justify-center flex-col" @click="openFileInput">
        <p class="text-2xl text-red-500">Upload your image here </p>
        <p class="text-red-500">Max.file size: 5MB</p>
        <input
            id="file-upload"
            type="file"
            class="hidden"
            ref="fileInput"
            @change="handleFileChange"
            accept="image/*"
        />
    </div>
    <div v-else class="relative select-none">
        <img :src="imageUrl" alt="Uploaded Image" class="h-auto max-w-80 outline outline-4 outline-blue-500 rounded select-none"/>
        <DeleteButton @click="removeImage" class="absolute top-2 left-2"/>
    </div>
</div>
</template>