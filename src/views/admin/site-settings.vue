<template>
  <div>
    <div v-for="(field, id) in install" v-bind:key="id">
      <p>{{ field.value }}</p>
      <div :is="`edittype-${field.edittype}`" v-model="field.value" :label="field.label"></div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Install',
  mounted () {
    this.$http.get('/api/install.php').then(response => {
      this.install = JSON.parse(response.body)
    })
  },
  data () {
    return {
      install: {}
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style>
</style>
