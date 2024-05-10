<script>
import PlayerName from './PlayerName.vue';

export default {
    props : {
        lobbyCode: Number,
        title: String,
        players: Array
    },
    methods : {
        print () {
            console.log(this.players)
        }
    },
    components: {
        PlayerName
    }
}
</script>

<template>
    <div class="absolute w-full h-full">
        <div class="flex items-center justify-between">
            <p class="none"></p>
            <div>
                <p class= "pl-20 text-white text-2xl"> Title: {{ title }}</p>
                <p class= "pl-20 text-red-500 text-2xl"> Code: {{ lobbyCode }}</p>
            </div>
            <button @click="print" class="btn-red"> Test </button>
            <p class="p-8 text-white text-xl">QuizChef</p>
        </div>
        <p class="p-10 text-white text-xl">{{ players.length }} players</p>
        <div class="grid grid-cols-6 gap-4 p-4">
    
            <div v-for="player in players">
                <PlayerName :player="player" />
            </div>
        </div>
    </div>
</template>