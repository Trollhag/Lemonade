<template>
  <div id="admin-app">
      <admin-menu v-if="!$route.meta.installing"></admin-menu>
      <div id="main-content">
        <router-view/>
      </div>
  </div>
</template>

<script>
export default {
  mounted () {
  },
  data () {
    return {
    }
  }
}
</script>

<style lang="scss">
@import '../lemonade/styles/main'; 
body {
  margin: 0;
  padding: 0;
  overflow: hidden;
}
body, *, *:before, *:after {
  box-sizing: border-box;
}
#main-content {
  overflow: auto;
  max-height: 100vh;
}
</style>
