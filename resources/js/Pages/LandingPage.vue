<template>
    <div class="absolute w-full h-full">
        <!-- title -->
        <p class="absolute text-6xl text-white font-bold anchor-center left-1/2 select-none" style="top:10%">QuizChef</p>
        
        <!-- Settings -->
        <button @click="settingsOpen=true" class="absolute w-16 h-16 right-5 top-5">
            <img class="select-none" src="../../../public/assets/img/SettingsCog.png" draggable="false">
        </button>
        <SettingsModal :isOpen="settingsOpen" @close="settingsOpen=false"/>
        
        <!-- About Us -->
        <div class="absolute w-1/2 about-us-closed about-us-transition left-1/4 pointer-events-none" style="height:35%" :class="{'about-us-open':aboutUsOpen}">
            <button @click="aboutUsOpen=!aboutUsOpen" class="absolute pointer-events-auto" style="height:28.5%;width:20%;left:40%">
                <p class="text-mid text-white text-nowrap text-xl select-none">{{ $global.lang.landingPage.aboutUs }}</p>
            </button>
            <div class="absolute bg-light border-2 rounded-lg w-full pointer-events-auto overflow-hidden" style="height:71.5%;top:28.5%">
                <p class="absolute text-white left-1/2 anchor-center" style="top:40%;width:90%">
                    {{ $global.lang.landingPage.aboutUsText }}
                </p>
            </div>
        </div>

        <!-- Login/register box -->
        <div v-if="canLogin" class="absolute w-1/5 h-1/2 bg-light border-2 rounded-lg anchor-center" style="left:30%;top:50%">
            <!-- Title -->
            <div class="absolute left-1/2 anchor-center" style="top:10%">
                <p class="absolute text-2xl text-white font-bold anchor-center left-1/2 top-1/2 select-none text-nowrap">{{ $global.lang.landingPage.ifYoureHosting }}</p>
            </div>
            
            <!-- Login Button -->
            <Link
                :href="route('login')"
                class="btn-green absolute landing-page-btn" style="top:35%;height:20%;width:75%"
            >
                <p class="text-mid">{{ $global.lang.generic.logIn }}</p>
            </Link>

            <p class="absolute text-3xl text-white font-bold anchor-center left-1/2 select-none w-full text-center" style="top:55%">{{ $global.lang.landingPage.or }}</p>

            <!-- Register Button -->
            <Link
                :href="route('register')"
                class="btn-blue absolute landing-page-btn" style="top:75%;height:20%;width:75%"
            >
                <p class="text-mid">{{ $global.lang.generic.register }}</p>
            </Link>
        </div>
        <div v-else class="text-white">canLogin == false; implement a thing that takes you to dashboard</div>

        <!-- Join room box -->
        <div class="absolute w-1/5 h-1/2 bg-light border-2 rounded-lg anchor-center" style="left:70%;top:50%">
            <!-- Title -->
            <!-- <div class="absolute left-1/2 anchor-center" style="top:10%">
                <p class="absolute text-2xl text-white font-bold anchor-center left-1/2 top-1/2 select-none text-nowrap">{{ $global.lang.landingPage.ifYourePlaying }}</p>
            </div> -->

            <!-- TODO: room code text box -->

            <!-- Go to room button "Cook" -->
            <input id="code" placeholder="Code" v-model="join_code" class="absolute bg-light text-white border-white border-2 landing-page-btn rounded-lg" style="top:15%;height:20%;width:75%"/>
            <input id="code" placeholder="Name" v-model="join_name" class="absolute bg-light text-white border-white border-2 landing-page-btn rounded-lg" style="top:40%;height:20%;width:75%"/>
            <button @click="joinGame"
                class="btn-green absolute landing-page-btn" style="top:75%;height:20%;width:75%"
            >
                <p class="text-mid">{{ $global.lang.landingPage.cook }}! 👨‍🍳</p>
            </button>
        </div>
    </div>
</template>

<script>
import { Link, router } from '@inertiajs/vue3';
import SettingsModal from '../Components/Settings/SettingsModal.vue';

export default {
    components: {
        Link,
        SettingsModal,
    },

    props: {
        canLogin: {
            type: Boolean,
        },
        canRegister: {
            type: Boolean,
        },
        laravelVersion: {
            type: String,
            required: true,
        },
        phpVersion: {
            type: String,
            required: true,
        },
    },

    data (){
        return {
            join_code: '',
            join_name: '',
            aboutUsOpen: false,
            settingsOpen: false,
        }
    },

    methods: {
        joinGame() {
            console.log(this.join_code)
            console.log(this.join_name)
            router.post('/join-lobby', {
                code : this.join_code,
                name: this.join_name
            })
        }
    }
    // mounted(){
    //     console.log(this.$global);
    // }
}
</script>

<style>
    
</style>