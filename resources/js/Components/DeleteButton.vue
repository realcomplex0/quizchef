<script>
export default {
    methods: {
        clickButton() {
            this.$emit('click')
        }
    }
}
</script>
<template>
    <button @click="clickButton" class="inline-flex items-center justify-center w-6 h-6 bg-gray-200 rounded-full hover:bg-gray-500 focus:outline-none select-none">
        <svg class="w-4 h-4 text-gray-600" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
            <path d="M6 18L18 6M6 6l12 12"></path>
        </svg>
    </button>
</template>