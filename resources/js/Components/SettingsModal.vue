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
                <div class="absolute h-24 w-full pointer-events-none border-b-2 ">
                    <p class="text-mid text-4xl font-bold">{{ $global.lang.settingsModal.settings }}</p>
                </div>

                <!-- TODO: Main Section Main Section :) -->
                <div class="absolute top-24 w-full h-24 border-b">
                    <div class="absolute w-3/4 h-full">
                        <p class="text-white text-2xl pl-10 absolute top-1/2" style="transform: translateY(-50%);">Language: </p>
                    </div>
                    <div class="absolute w-1/4 h-full left-3/4 flex justify-evenly items-center border-l">
                        <button @click="$global.settings.language = 'lv';upd()" class="w-2/5 h-16 border-2 text-xl" :class="{ 'btn-gray' : ($global.settings.language != 'lv'), 'btn-green' : ($global.settings.language == 'lv')}">LV</button>
                        <button @click="$global.settings.language = 'en';upd()" class="w-2/5 h-16 border-2 text-xl" :class="{ 'btn-gray' : ($global.settings.language != 'en'), 'btn-green' : ($global.settings.language == 'en')}">EN</button>
                    </div>
                </div>
                
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
        upd(){
            for (let i in this.$global.settings){
                localStorage.setItem(i, this.$global.settings[i]);
            }
        }
    },
    
}
</script>

<style>
    .fade-enter-from, .fade-leave-to {opacity:0%;}
    .fade-enter-to, .fade-leave-from {opacity:100%;}
    .fade-enter-active, .fade-leave-active {transition: all 0.15s;}
</style>