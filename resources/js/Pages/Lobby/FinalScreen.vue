<template>
    <div class="w-full h-full absolute">
        <!-- question title -->
        <div class="absolute w-3/4 h-1/6 left-[12.5%] text-white">
            <p class="text-4xl text-mid select-none"> {{ $global.lang.game.final_scoreboard }}</p>
        </div>
        <!-- answers -->
        <div class="absolute w-3/4 h-5/6 left-[12.5%] top-[16.66%] overflow-y-auto">
            <div v-for="(entry, index) in results" :key="index">
                <p class="text-center text-2xl " :style="{ 'font-size': calcSize(index+1) + 'px', 'color' : calcColor(index+1)}" :class="{ 'pt-5' : ((index+1) <= 2), 'pt-2' : ((index+1) == 3), 'pt-1' : ((index+1) == 4)}">{{index+1}}. {{ entry[1] }} - {{ entry[0] }}</p>
            </div>
        </div>
        
    </div>
</template>

<script>
export default {
    props: {
        scoreboard: {
            default: null,
        }
    },
    methods: {
        calcSize(index){
            if (index == 1) return 50;
            if (index == 2) return 35;
            if (index == 3) return 25;
            if (index == 4) return 20;
            if (index == 5) return 17;
            return Math.max(20-index, 10);
        },
        calcColor(index){
            if (index == 1) return 'gold';
            if (index == 2) return 'silver';
            if (index == 3) return '#CD7F32';
            return 'white';
        }
    },
    computed: {
        results(){
            let res = [];
            let cnt = 0;
            for (const i in this.scoreboard){
                res.push([]);
                for (const j in this.scoreboard[i]){
                    res[cnt].push(this.scoreboard[i][j]);
                }
                cnt += 1;
            }
            res.sort();
            res.reverse();
            return res;
        }
    }
}
</script>

<style>

</style>