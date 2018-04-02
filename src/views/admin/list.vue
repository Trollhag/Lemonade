<template>
    <div class="admin-area">
        <h1>{{ $route.meta.type }}</h1>
    </div>
</template>

<script>
export default {
    name: 'lemonade-list',
    mounted () {
        window.EventBus.bodyClass = 'lemonade-page'
    },
    data () {
        return {
        }
    },
    methods: {
    }
}
</script>

<style lang="scss" scoped>

</style>
