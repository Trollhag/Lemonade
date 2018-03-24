<template>
    <div class="admin-area">
        <img width="150px" src="@/assets/lemonade.svg">
        <div class="admin-container admin-card">
            <h1 class="lemonade-header">{{ msg }}</h1>
            <form @submit="onSubmit">
                <div v-show="page === 0">
                    <div class="admin-form-group">
                        <label>Site title</label>
                        <input type="text" class="admin-input" v-model="install_data.title" />
                    </div>
                    <div class="admin-form-group">
                        <label>Base url</label>
                        <input type="text" class="admin-input" v-model="install_data.base_url" />
                        <p class="admin-field-desc">If your home url is www.domain.com/base/, then your base url is <u>/base/</u>.</p>
                    </div>
                </div>
                <div v-show="page === 1">
                    <div class="admin-form-group two-thirds">
                        <label>Database host</label>
                        <input type="text" class="admin-input" v-model="install_data.db_host" />
                    </div>
                    <div class="admin-form-group one-third">
                        <label>Port</label>
                        <input type="text" class="admin-input" v-model="install_data.db_port" />
                    </div>
                    <div class="admin-form-group one-half">
                        <label>Database user</label>
                        <input type="text" class="admin-input" v-model="install_data.db_user" />
                    </div>
                    <div class="admin-form-group one-half">
                        <label>Database password</label>
                        <input type="text" class="admin-input" v-model="install_data.db_password" />
                    </div>
                    <div class="admin-form-group one-third">
                        <label>Database prefix</label>
                        <input type="text" class="admin-input" v-model="install_data.db_prefix" placeholder="lmn_" />
                    </div>
                    <div class="admin-form-group two-thirds">
                        <label>Database name</label>
                        <input type="text" class="admin-input" v-model="install_data.db_name" />
                    </div>
                    <div class="admin-form-group one-half center-block">
                        <input type="button" value="Test database" @click="testDB" class="button-secondary" />
                    </div>
                </div>
                <div v-show="page === 2">
                    <div class="admin-form-group">
                        <label>Username</label>
                        <input type="text" class="admin-input" v-model="install_data.username" />
                    </div>
                    <div class="admin-form-group">
                        <label>Password</label>
                        <input type="password" class="admin-input" v-model="install_data.password" />
                    </div>
                    <div class="admin-form-group">
                        <label>Email</label>
                        <input type="text" class="admin-input" v-model="install_data.email" />
                    </div>
                </div>
                <div class="admin-form-group one-third">
                    <input v-show="page > 0" @click="page--" type="button" value="Back" class="button-primary-outline" />
                </div>
                <div class="admin-form-group one-third pull-right">
                    <input v-show="page < 2" @click="page++" type="button" value="Next" class="button-primary" />
                    <input v-show="page === 2" type="submit" value="Install" class="button-primary" />
                </div>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Install',
    mounted () {
    },
    data () {
        return {
            msg: 'Welcome to your Lemonade App!',
            install_error: '',
            page: 0,
            install_data: {
                title: '',
                base_url: window.location.pathname,
                db_host: 'localhost',
                db_port: '3306',
                db_user: '',
                db_password: '',
                db_name: '',
                db_prefix: '',
                username: '',
                password: '',
                email: ''
            },
        }
    },
    methods: {
        testDB() {
            let data = {
                test_db: true
            } 
            Object.assign(data, this.install_data);
            console.log(data)
            this.$http.post('/api/install/', data, {
                emulateJSON: true,
                emulateHTTP: true
            }).then(response => {
                console.log('Success!')
                console.log(response)

            }, response => {
                console.log('Fail!')
                console.log(response)
                this.install_error = response.body
            })
        },
        onSubmit() {
            this.$http.post('/api/install/', this.install_data, {
                emulateJSON: true,
                emulateHTTP: true
            }).then(response => {
                console.log('Success!')
                console.log(response)

            }, response => {
                console.log('Fail!')
                console.log(response)
                this.errormsg = response.body
                this.install_error = response.body
            })
        }
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
.admin-container {
    width: 600px;
    max-width: 100%;
    margin-left: auto;
    margin-right: auto;
    overflow: hidden;
    form {
        padding: 30px;
        @include row;
    }
}
.admin-form-group {
    @include one-whole;
}
.admin-input {
    background-color: rgba(0,0,0,.05);
}
.button-primary {
    @include button-primary;
    &-outline {
        @include button-style(#f5f5f5, #333);
    }
}
.button-secondary {
    @include button-secondary;
}

.one-third {
    @include one-third;
}
.one-half {
    @include one-half;
}
.two-thirds {
    @include two-thirds;
}
.pull-right {
    @include pull-right;
}
.center-block {
    @include center-block;
}
</style>
