<script>
import PlayerName from './PlayerName.vue';
import {Link, router} from '@inertiajs/vue3';

export default {
    props : {
        lobbyCode: Number,
        title: String,
        players: Array
    },
    data() {
        return {
            displayPlayers : this.players
        }
    },
    mounted() {
        Echo.channel(`lobby.${this.lobbyCode}`)
            .listen('JoinLobby', (event) => {
                this.displayPlayers = event["player_list"];
            });
        Echo.channel(`lobby.${this.lobbyCode}`)
            .listen('StartGame', (event) => {
                router.get('/game');
            });
    },
    methods: {
        startgame() {
            router.post('/start-game', {
                code : this.lobbyCode
            })
        }
    },
    watch: {
        displayPlayers(newPlayerList) {
            
        },
    },
    components: {
        Link,
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
            <p class="p-8 text-white text-xl">
                <Link href='/'>
                    Quizchef
                </Link>
            </p>
        </div>

        <button @click="startgame" class="btn-green absolute" style="right:0%;height:100px;width:200px">Start game</button>

        <p class="p-10 text-white text-xl">{{ displayPlayers.length }} players</p>
        <div class="grid grid-cols-6 gap-4 p-4">
            <div v-for="player in displayPlayers" :key="player">
                <PlayerName :player="player" />
            </div>
        </div>
    </div>
</template>