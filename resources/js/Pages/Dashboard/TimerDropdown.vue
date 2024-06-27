<script>

export default {
    data () {
        return {
            isOpen: false,
            selectedOption: null,
            options : [
                { value: 10, text: '10s'},
                { value: 15, text: '15s'},
                { value: 20, text: '20s'},
                { value: 25, text: '25s'},
                { value: 30, text: '30s'},
                { value: 40, text: '40s'},
                { value: 50, text: '50s'},
                { value: 60, text: '60s'}
            ]
        }
    },
    props : {
        time_limit: {
            type: Number
        }
    },
    methods : {
        changeOption () {
            this.$emit('update', this.selectedOption)
        }
    },
    watch: {
        time_limit(newTimeLimit) {
            this.selectedOption = newTimeLimit
        }
    },
    mounted () {
        this.selectedOption = this.time_limit
    },
}

</script>
<template>
    <div>
        <label for="selectTimer" class="text-red-500 text-xl"> 🕒Time Limit: </label>
        <select id="selectTimer" v-model="selectedOption" @change="changeOption">
            <option v-for="option in options" :key="option.value" :value="option.value">
                {{ option.text }}
            </option>
        </select>
    </div>
</template>