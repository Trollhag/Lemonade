<template>
  <div class="admin-menu">
    <div class="wrapper">
      <div class="admin-controls">
        <router-link to="/">
          <img src="@/assets/lemonade.svg">
        </router-link>
      </div>
    </div>
    <route-menu></route-menu>
  </div>
</template>

<script>
export default {
  name: 'Admin-menu',
  mounted () {
    // Perform admin checks
  },
  data () {
    return {
    }
  }
}
</script>

<style lang="scss" scoped>
.admin-menu {
  float: left;
  height: 100vh;
  overflow-y: auto;
  background-color: $lemonade-bg;
  padding: 5px 0;
}
.wrapper {
  height: 100%;
  float: left;
}
.admin-controls {
  background-color: $lemonade-card;
  margin-left: 5px;
  border-radius: 5px;
}
a {
  color: $lemonade-primary;
  width: 60px;
  padding: 10px;
  display: block;
}
</style>
