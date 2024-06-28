<template>
    <div class="w-full h-full absolute">
        <!-- question title -->
        <div class="absolute w-3/4 h-1/6 left-[12.5%] text-white">
            <p class="text-4xl text-mid select-none"> {{ $global.lang.game.scoreboard }}</p>
        </div>
        <!-- answers -->
        <div class="absolute w-3/4 h-5/6 left-[12.5%] top-[16.66%] overflow-y-auto">
            <div v-for="(entry, index) in results" :key="index" class="">    
                <p v-if="entry[2]==0" class="text-center text-2xl text-red-500">{{ entry[1] }} - {{ entry[0] }}</p>
                <p v-else class="text-center text-2xl text-green-500">{{ entry[1] }} - {{ entry[0] }} (+{{ entry[2] }})</p>
            </div>
        </div>
        <div v-if="!is_host" class="fixed w-full h-[10%] bottom-0 text-white text-response">
            <p class="text-mid">{{ $global.lang.game.host_wait }}</p>
        </div>
    </div>

</template>

<script>
export default {
    props: {
        scoreboard: {
            default: null,
        },
        is_host: {
            default : false,
        }
    },
    computed: {
        results(){
            let res = [];
            for (const i in this.scoreboard){
                res.push([this.scoreboard[i][0], this.scoreboard[i][1], this.scoreboard[i][2]]);
            }
            return res.sort((x, y) => {
                return y[0]-x[0];
            });
        }
    }
}
</script>

<style>

</style>