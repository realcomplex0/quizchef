<script>
export default {
    props : {
        player : {
            type: Object
        }
    }
}
</script>

<template>
    <p class="text-yellow-700">
        {{player.player_name}}
    </p>
    
</template>