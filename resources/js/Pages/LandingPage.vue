<template>
    <div class="absolute w-full h-full">
        <!-- title -->
        <p class="absolute text-6xl text-white font-bold anchor-center left-1/2 select-none" style="top:10%">QuizChef</p>
        
        <!-- Settings -->
        <button class="absolute w-16 h-16 right-5 top-5">
            <img class="select-none" src="../../../public/assets/img/SettingsCog.png" draggable="false">
        </button>

        <!-- About Us -->
        <button class="absolute left-1/2 anchor-center" style="top:95%;height:10%;width:10%">
            <p class="absolute anchor-center top-1/2 left-1/2 text-white text-nowrap text-xl select-none">About Us</p>
        </button>

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
                <p class="absolute anchor-center top-1/2 left-1/2">Log In</p>
            </Link>

            <p class="absolute text-3xl text-white font-bold anchor-center left-1/2 select-none w-full text-center" style="top:55%">OR</p>

            <!-- Register Button -->
            <Link
                :href="route('_register')"
                class="btn-blue absolute landing-page-btn" style="top:75%;height:20%;width:75%"
            >
                <p class="absolute anchor-center top-1/2 left-1/2">Register</p>
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
                <p class="absolute anchor-center top-1/2 left-1/2">Cook! 👨‍🍳</p>
            </Link>
        </div>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';

export default {
    components: {
        Link,
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
    }
}
</script>

<style>

</style>