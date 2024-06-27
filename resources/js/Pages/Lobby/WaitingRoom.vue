<script>
import PlayerName from './PlayerName.vue';
import {Link, router} from '@inertiajs/vue3';
import QuizAnswer from './QuizAnswer.vue';
import Scoreboard from './Scoreboard.vue';
import SettingsModal from '@/Components/SettingsModal.vue';
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
            status : 0, // 0 - lobby, 1 - question, 2 - see answers, 3 - scoreboard, 4 - end of game
            questionData: {
                currentQuestion : null,
                currentOptions : null,
                optionsAnswer : null,
                confirmedAnswer : null,
                timeRemaining: 1,
                playerCount: 1,
                answerCount: 0,
            },
            timerInterval: null,
            scoreboard: null,
            settingsOpen: false,
        }
    },
    mounted() {
        this.questionData.playerCount = this.players.length;
        window.Echo.channel(`lobby.${this.lobbyCode}`)
            .listen('JoinLobby', (event) => {
                this.questionData.answerCount -= event["had_answered"];
                this.displayPlayers = event["player_list"];
            });
        window.Echo.channel(`lobby.${this.lobbyCode}`)
            .listen('StartGame', (event) => {
                // this.questionData.playerCount = this.players.length;
                this.questionData.answerCount = 0;
                this.questionData.optionsAnswer = null;
                this.questionData.confirmedAnswer = null;
                this.questionData.timeRemaining = 1;
                this.questionData.currentQuestion = event["question"];
                this.questionData.currentOptions = event["options"];
                this.questionData.timeRemaining = this.questionData.currentQuestion["timer"];
                this.status = event["status"];
                this.startTimer();
                // this.displayPlayers = event["player_list"];
            });
        window.Echo.channel(`lobby.${this.lobbyCode}`)
            .listen('UpdateLobby', (event) => {
                if (event['op'] == 0){ // question ended, recieve correct answers
                    this.questionData.optionsAnswer = event['info']['optionsAnswer'];
                    this.questionData.timeRemaining = 0;
                    this.status = 2;
                } else if (event['op'] == 1){ // update answer count
                    this.questionData.answerCount = event['info']['answer_count'];
                } else if (event['op'] == 2){ // go to scoreboard
                    this.scoreboard = event['info']['scoreboard'];
                    this.status = 3;
                }
            });
        window.Echo.channel(`player.${this.selected_player}`)
            .listen('UpdatePlayer', (event) => {
                this.questionData.confirmedAnswer = event['info']['answer_index'];
            })
    },
    unmounted(){
        window.Echo.leave(`lobby.${this.lobbyCode}`);
        window.Echo.leave(`player.${this.selected_player}`);
    },
    methods: {
        startGame() {
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
        },
        startTimer(){
            this.timerInterval = setInterval(() => {
                this.questionData.timeRemaining -= 1;
            }, 1000)
        },
        stopTimer(){
            window.clearInterval(this.timerInterval);
            
        },
        endQuestion(){
            if (this.is_host == 0 || this.status != 1) return;
            router.post('/end-question', {
                code : this.lobbyCode,
            })
        },
        goScoreboard(){
            if (this.is_host == 0 || this.status != 2) return;
            router.post('/go-scoreboard', {
                code: this.lobbyCode,
            })
        }
    },
    watch: {
        questionData : {
            handler(newVal, oldVal){
                if (newVal.timeRemaining <= 0) {
                    this.stopTimer();
                    this.endQuestion();
                }
                if (newVal.answerCount >= newVal.playerCount) this.endQuestion();
            }, 
            deep: true,
        },
        displayPlayers : {
            handler(newVal, oldVal){
                this.questionData.playerCount = newVal.length;
            },
            deep: true,
        }
    },
    computed: {
        playerCount(){
            if (this.displayPlayers.length == 0){
                this.leaveLobby();
            }
            return this.displayPlayers.length;
        }
    },
    components: {
        Link,
        PlayerName,
        QuizAnswer,
        Scoreboard,
        SettingsModal,
    }
}
</script>

<template>
    <div class="absolute w-full h-full">
        <SettingsModal :isOpen="settingsOpen" @close="settingsOpen=false"/>
        <button @click="settingsOpen=true" class="fixed bottom-4 left-4 h-16 w-16 z-10">
            <img class="select-none" src="../../../../public/assets/img/SettingsCog.png" draggable="false">
        </button>
        <div class="p-6 bg-light flex space-between items-center justify-between border-b-2 border-white">
            <p class="text-5xl text-white font-bold select-none">
                <button @click="leaveLobby">
                    QuizChef
                </button>
            </p>
            <div class="absolute left-1/2" style="transform:translateX(-50%)">
                <p class= "text-white text-2xl"> {{ title }}</p>
                <p class= "text-red-500 text-2xl"> {{$global.lang.game.code}}: {{ lobbyCode }}</p>
            </div>
            <button @click="leaveLobby" class="btn-red text-white border-2 p-3">
                <p class="select-none">{{ $global.lang.game.leave_lobby }}</p>
            </button>
        </div>

        <!-- Game lobby -->
        <div v-if="status==0" class="absolute w-full" style="height:80%">
            <div class="w-full h-16 absolute ">
                <p class="text-white text-3xl text-mid">{{ playerCount }} {{$global.lang.game.players}}</p>
            </div>
            <button v-if="is_host" @click="startGame" class="absolute btn-green left-1/2 bottom-0 border-white border-2 text-3xl" style="height:100px;width:200px;transform:translateX(-50%)">
                <p class="select-none">{{$global.lang.game.start_game}} </p>
            </button>
            <div class="grid grid-cols-4 gap-4 p-4 m-7 mt-20">
                <div v-for="player in displayPlayers" :key="player">
                    <PlayerName :player="player" :is_selected="(player.id == selected_player)" :is_host="player.is_host" />
                </div>
            </div>
        </div>

        <!-- Question -->
        <QuizAnswer v-if="status==1||status==2" class="absolute w-full" style="height:80%" :questionData="questionData" @selected="processAnswer"></QuizAnswer>

        <button v-if="is_host&&status==2" @click="goScoreboard" class="absolute btn-green right-[1.25%] bottom-[2.5%] border-white border-2 h-[10%] w-[10%]">
            <p class="select-none text-xl">{{$global.lang.game.continue}}</p>
        </button>

        <!-- Scoreboard -->
        <Scoreboard v-if="status==3" class="absolute w-full" style="height:80%" :scoreboard="scoreboard"></Scoreboard>

        <button v-if="is_host&&status==3" @click="startGame" class="absolute btn-green right-[1.25%] bottom-[2.5%] border-white border-2 h-[10%] w-[10%]">
            <p class="select-none text-xl">{{$global.lang.game.continue}}</p>
        </button>

        <!-- Final standings -->

    </div>
</template>