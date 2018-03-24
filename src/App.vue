<template>
  <div>
    <h1>{{ site.title }}</h1>
    <router-view/>
  </div>
</template>

<script>
export default {
  name: 'App',
  mounted () {
  },
  data () {
    return {
      site: {
        title: 'Lemonade'
      }
    }
  }
}
</script>

<style>
</style>
