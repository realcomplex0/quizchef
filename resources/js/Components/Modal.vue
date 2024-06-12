<!-- :isOpen: true - component is shown; false - component is hidden -->
<!-- @close: emits when modal should be closed -->
<!-- :width and :height determine size of modal -->

<!-- 
Example: 
<Modal :isOpen="settingsOpen" @close="settingsOpen=false" :height="'100px'" :width="'25%'"/> 
    <p> hello </p>
</Modal>
-->

<template>
    <Transition name="fade">
        <div v-if="isOpen" class="absolute w-full h-full z-20">
            <!-- background -->
            <div @click="close" class="absolute w-full h-full opacity-50 bg-black"/>

            <!-- main section -->
            <div class="absolute bg-light border-2 rounded-lg left-1/2 top-1/2 anchor-center text-white" :style="{height : height, width : width}">
                <!-- close button -->
                <button @click="close" class="absolute w-8 h-8 right-2 top-2 btn-red border-2 z-10">
                    <p class="text-mid text-xl select-none font-extrabold">X</p>
                </button>
                <slot></slot>
            </div>
        </div>
    </Transition>
</template>

<script>
export default {
    props:['isOpen', 'height', 'width'],
    emits:['close'],
    methods: {
        close(){
            this.$emit('close');
        },
    },
}
</script>

<style>
    .fade-enter-from, .fade-leave-to {opacity:0%;}
    .fade-enter-to, .fade-leave-from {opacity:100%;}
    .fade-enter-active, .fade-leave-active {transition: all 0.15s;}
</style>