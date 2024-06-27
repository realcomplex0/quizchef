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
        
    </div>

</template>

<script>
export default {
    props: {
        scoreboard: {
            default: null,
        }
    },
    data() {
        return {
            results: null
        }
    },
    mounted() {
        // process results here
        console.log(this.scoreboard);
        this.results = [];
        for (const i in this.scoreboard){
            this.results.push(this.scoreboard[i]);
        }
        this.results.sort();
        this.results.reverse();
    }
}
</script>

<style>

</style>