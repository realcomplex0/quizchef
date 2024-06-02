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
        {{player.nickname}}
    </p>
    
</template>