<template>
  <div>
    <label>{{ label }}</label>
    <input type="text" v-bind:value="value" v-on:input="updateValue($event.target.value)" />
  </div>
</template>

<script>
export default {
  name: 'Install',
  mounted () {
  },
  props: ['value', 'label'],
  data () {
    return {
    }
  },
  methods: {
    updateValue: function (value) {
      this.$emit('input', value)
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
