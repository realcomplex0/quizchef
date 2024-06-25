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
        }
    }
}
</script>

<template>
    <p v-if="!is_selected" class="text-yellow-700">
        {{player.nickname}}{{nameSuffix}}
    </p>
    <p v-else class="text-white">
        {{player.nickname}}{{nameSuffix}}
    </p>
    
</template>