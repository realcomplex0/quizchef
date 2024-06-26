<script>
import PlayerName from './PlayerName.vue';
import {Link, router} from '@inertiajs/vue3';
import QuizAnswer from './QuizAnswer.vue';
import Echo from 'laravel-echo';

export default {
    props : {
        lobbyCode: Number,
        title: String,
        players: Array,
        selected_player: Number,
        is_host: Number,
    },
    data() {
        return {
            displayPlayers : this.players,
            status : 0, // 0 - lobby, 1 - question, 2 - scoreboard, 3 - waiting for others,
            currentQuestion : null,
            currentOptions : null,
            optionsAnswer : null,
        }
    },
    mounted() {
        window.Echo.channel(`lobby.${this.lobbyCode}`)
            .listen('JoinLobby', (event) => {
                this.displayPlayers = event["player_list"];
            });
        window.Echo.channel(`lobby.${this.lobbyCode}`)
            .listen('StartGame', (event) => {
                this.status = event["status"];
                this.currentQuestion = event["question"];
                this.currentOptions = event["options"];
                this.optionsAnswers = null;
                // this.displayPlayers = event["player_list"];
            });
        window.Echo.channel(`player.${this.selected_player}`)
            .listen('UpdatePlayer', (event) => {
                this.optionsAnswer = event['optionsAnswer'];
                // console.log(this.optionsAnswer);
            });
    },
    unmounted(){
        window.Echo.leave(`lobby.${this.lobbyCode}`);
        window.Echo.leave(`player.${this.selected_player}`);
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
        },
        processAnswer(index){
            router.post('/submit-answer', {
                code : this.lobbyCode,
                answer_index : index
            })
        }
    },
    computed: {
        playerCount(){
            if (this.displayPlayers.length == 0){
                this.leaveLobby();
            }
            let ret = '';
            ret += this.displayPlayers.length;
            ret += ' player'
            if (this.displayPlayers.length != 1) ret += 's';
            return ret;
        }
    },
    components: {
        Link,
        PlayerName,
        QuizAnswer,
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

        <!-- Game lobby -->
        <div v-if="status==0" class="absolute w-full" style="height:80%">
            <div class="w-full h-16 absolute ">
                <p class="text-white text-3xl text-mid">{{ playerCount }}</p>
            </div>
            <button v-if="is_host" @click="startgame" class="absolute btn-green w-1/2 left-1/2 bottom-0 border-white border-2 text-3xl" style="right:0%;height:100px;width:200px;transform:translateX(-50%)">
                <p class="select-none">Start game</p>
            </button>
            <div class="grid grid-cols-4 gap-4 p-4 m-7 mt-20">
                <div v-for="player in displayPlayers" :key="player">
                    <PlayerName :player="player" :is_selected="(player.id == selected_player)" :is_host="player.is_host" />
                </div>
            </div>
        </div>

        <!-- Question -->
        <QuizAnswer v-if="status==1" class="absolute w-full" style="height:80%" :question="currentQuestion" :options="currentOptions" :optionsAnswer="optionsAnswer" @selected="processAnswer"></QuizAnswer>

        <!-- Scoreboard -->


        <!-- Waiting for others -->


    </div>
</template>