<!-- :isOpen: true - component is shown; false - component is hidden -->
<!-- @close: emits when settings tab should be closed -->
<!-- Example: <SettingsModal :isOpen="settingsOpen" @close="settingsOpen=false"/> -->

<template>
    <Transition name="fade">
        <div v-if="isOpen" class="absolute w-full h-full z-20">
            <!-- background -->
            <div @click="close" class="absolute w-full h-full opacity-50 bg-black"/>

            <!-- Main Section -->
            <div class="absolute bg-light border-2 rounded-lg left-1/2 top-1/2 anchor-center text-white" style="height:90%;width:55%">
                <!-- close button -->
                <button @click="close" class="absolute w-16 h-16 right-4 top-4 btn-red border-2">
                    <p class="text-mid text-4xl select-none font-extrabold">X</p>
                </button>

                <!-- Title -->
                <div class="absolute h-24 w-full pointer-events-none border-b ">
                    <p class="text-mid text-4xl font-bold">{{ $global.lang.settingsModal.settings }}</p>
                </div>

                <!-- TODO: Main Section Main Section :) -->
                <button @click="$global.settings.language = 'lv'" class="btn-green p-10">lv</button>
                <button @click="$global.settings.language = 'en'" class="btn-green p-10">en</button>
            </div>
        </div>
    </Transition>
</template>

<script>
export default {
    props:['isOpen'],
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