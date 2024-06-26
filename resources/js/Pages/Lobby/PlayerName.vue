<script>
export default {
    props : {
        player : {
            type: Object
        },
        is_selected : {
            default: false
        },
        is_host: {
            default: false
        }
    },
    computed: {
        nameSuffix(){
            if (this.is_host) return '👑';
            return '';
        },
        nameMain(){
            if (this.player.nickname.length >= 18) { // if name longer than 17 symbols, truncate it
                let ret = '';
                for (let i = 0; i < 17; i++) ret += this.player.nickname[i];
                ret += '...';
                return ret;
            }
            return this.player.nickname;
        }
    }
}
</script>

<template>
    <p class="text-xl text-center" :class="{ 'text-yellow-500': !is_selected, 'text-white' : is_selected, 'underline' : is_host}">
        {{nameMain}}{{nameSuffix}}
    </p>
</template>