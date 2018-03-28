<template>
    <div class="admin-area">
        <img width="150px" src="@/assets/lemonade.svg">
        <div class="admin-container admin-card">
            <h1 class="lemonade-header">{{ msg }}</h1>
        </div>
    </div>
</template>

<script>
export default {
    name: 'dashboard',
    mounted () {
        window.EventBus.bodyClass = 'dashboard-screen lemonade-page'
    },
    data () {
        return {
            msg: 'Dashboard',
        }
    },
    methods: {
    }
}
</script>

<style lang="scss" scoped>
img {
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 30px;
    display: block;
}
h1 {
    text-align: center;
    font-size: 60px;
    margin: 0;
    background-color: $lemonade-primary;
    padding: 30px;
    color: #fff;
    border-bottom: 3px solid rgba(0,0,0,.05);
}
.admin-area {
    padding: 30px 0;
    min-height: 100vh;
}
.admin-container form {
    padding: 30px;
    @include row;
}

</style>
