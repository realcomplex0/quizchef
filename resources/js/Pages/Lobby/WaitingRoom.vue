<script>
import PlayerName from './PlayerName.vue';
import {Link, router} from '@inertiajs/vue3';

export default {
    props : {
        lobbyCode: Number,
        title: String,
        players: Array,
        selected_player: Number,
        is_host: Boolean,
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
    unmounted(){
        Echo.leave(`lobby.${this.lobbyCode}`);
    },
    methods: {
        startgame() {
            router.post('/start-game', {
                code : this.lobbyCode
            })
        },
        leaveLobby(){
            router.post('/leave-lobby', {
                code : this.lobbyCode,
            })
        }
    },
    computed: {
        playerCount(){
            if (this.displayPlayers.length == 0){
                console.log(1);
                this.leaveLobby();
            }
            return this.displayPlayers.length;
        }
    },
    components: {
        Link,
        PlayerName
    }
}
</script>

<template>
    <div class="absolute w-full h-full">
        <div class="p-6 bg-light flex space-between items-center justify-between border-b-2 border-white">
            <p class="text-5xl text-white font-bold select-none">
                <button @click="leaveLobby">
                    QuizChef
                </button>
            </p>
            <div class="absolute left-1/2" style="transform:translateX(-50%)">
                <p class= "text-white text-2xl"> Title: {{ title }}</p>
                <p class= "text-red-500 text-2xl"> Code: {{ lobbyCode }}</p>
            </div>
            <button @click="leaveLobby" class="btn-red text-white border-2 p-3">
                <p class="select-none">Leave lobby</p>
            </button>
        </div>

        <div class="w-full p-2 flex space-between items-center justify-between">
            <p class="p-10 text-white text-3xl">{{ playerCount }} players</p>
            
            <!-- <p class="select-none"></p> -->
        </div>
        <button v-if="is_host" @click="startgame" class="absolute btn-green w-1/2 left-1/2 bottom-10 border-white border-2 text-3xl" style="right:0%;height:100px;width:200px;transform:translateX(-50%)">
            <p class="select-none">Start game</p>
        </button>
        <div class="grid grid-cols-6 gap-4 p-4">
            <div v-for="player in displayPlayers" :key="player">
                <PlayerName :player="player" :is_selected="(player.id == selected_player)" :is_host="player.is_host" />
            </div>
        </div>
    </div>
</template>