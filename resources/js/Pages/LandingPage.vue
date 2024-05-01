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
                <p class="text-mid text-white text-nowrap text-xl select-none">About Us</p>
            </button>
            <div class="absolute bg-light border-2 rounded-lg w-full pointer-events-auto overflow-hidden" style="height:71.5%;top:28.5%">
                <p class="absolute text-white left-1/2 anchor-center" style="top:40%;width:90%">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, 
                    sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
                    Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
                    Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. 
                    Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </p>
            </div>
        </div>

        <!-- Login/register box -->
        <div v-if="canLogin" class="absolute w-1/5 h-1/2 bg-light border-2 rounded-lg anchor-center" style="left:30%;top:50%">
            <!-- Title -->
            <div class="absolute left-1/2 anchor-center" style="top:10%">
                <p class="absolute text-2xl text-white font-bold anchor-center left-1/2 top-1/2 select-none text-nowrap">If you're hosting:</p>
            </div>
            
            <!-- Login Button -->
            <Link
                :href="route('_login')"
                class="btn-green absolute landing-page-btn" style="top:35%;height:20%;width:75%"
            >
                <p class="text-mid">Log In</p>
            </Link>

            <p class="absolute text-3xl text-white font-bold anchor-center left-1/2 select-none w-full text-center" style="top:55%">OR</p>

            <!-- Register Button -->
            <Link
                :href="route('_register')"
                class="btn-blue absolute landing-page-btn" style="top:75%;height:20%;width:75%"
            >
                <p class="text-mid">Register</p>
            </Link>
        </div>
        <div v-else class="text-white">canLogin == false; implement a thing that takes you to dashboard</div>

        <!-- Join room box -->
        <div class="absolute w-1/5 h-1/2 bg-light border-2 rounded-lg anchor-center" style="left:70%;top:50%">
            <!-- Title -->
            <div class="absolute left-1/2 anchor-center" style="top:10%">
                <p class="absolute text-2xl text-white font-bold anchor-center left-1/2 top-1/2 select-none text-nowrap">If you're playing:</p>
            </div>

            <!-- TODO: room code text box -->

            <!-- Go to room button "Cook" -->
            <Link
                class="btn-green absolute landing-page-btn" style="top:75%;height:20%;width:75%"
            >
                <p class="text-mid">Cook! 👨‍🍳</p>
            </Link>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import SettingsModal from '../Components/SettingsModal.vue';

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
            aboutUsOpen: false,
            settingsOpen: false,
        }
    },
}
</script>

<style>
    
</style>