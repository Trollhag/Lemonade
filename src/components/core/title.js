export default {
  render: function (createElement) {
    return createElement(
      this.tagName,   // tag name
      this.$slots.default // array of children
    )
  },
  props: {
    tag: {
      type: String,
      default: 'h1'
    }
  },
  computed: {
    tagName: () => {
      if (this.tag.length === 2 && this.tag.substr(0, 1) === 'h') {
        return this.tag
      }
      return 'h1';
    }
  }
}