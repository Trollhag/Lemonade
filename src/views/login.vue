<template>
  <div class="login admin-area">
        <img width="150px" src="@/assets/lemonade.svg">
        <div class="admin-container admin-card">
            <form @submit="login">
                <div class="admin-form-group">
                    <label>Username | Email</label>
                    <input type="text" class="admin-input" v-model="form.username" />
                </div>
                <div class="admin-form-group">
                    <label>Password</label>
                    <input type="password" class="admin-input" v-model="form.password" />
                </div>
                <div class="admin-form-group one-third pull-right">
                    <input type="submit" value="Login" class="button-primary" />
                </div>
            </form>
        </div>
  </div>
</template>

<script>
export default {
  name: 'Login',
  mounted() {
    window.EventBus.bodyClass = 'login-screen lemonade-page'
  },
  data () {
    return {
        form: {
            username: '',
            password: ''
        }
    }
  },
  methods: {
    login() {
      this.$http.post("/api/login/", this.form, {
        emulateJSON: true,
        emulateHTTP: true
      }).then(response => {
        //window.location.reload()
        console.log(response)
      }, response => {
        console.log(response)
      })
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="scss" scoped>
img {
    margin-left: auto;
    margin-right: auto;
    margin-bottom: 30px;
    display: block;
}
.admin-area {
    padding: 30px 0;
}
.admin-container {
  width: 400px;
  form {
    padding: 30px;
    @include row;
  }
}
</style>
