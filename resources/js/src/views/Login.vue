<template>
    <v-app id="inspire">
        <v-content>
            <v-container
                class="fill-height"
                fluid
            >
                <v-row
                    align="center"
                    justify="center"
                >
                    <v-col
                        cols="12"
                        sm="8"
                        md="4"
                    >
                        <v-card class="elevation-12">
                            <v-toolbar
                                color="primary"
                                dark
                                flat
                            >
                                <v-toolbar-title>Login form</v-toolbar-title>
                                <v-spacer />
                            </v-toolbar>
                            <v-card-text>
                                <div class="red" v-if="error">
                                    <p>{{error.message}}</p>
                                </div>
                                <v-form>
                                    <v-text-field
                                        label="Login"
                                        name="login"
                                        prepend-icon="person"
                                        type="text"
                                        v-model="loginDetails.username"
                                    />

                                    <v-text-field
                                        id="password"
                                        label="Password"
                                        name="password"
                                        prepend-icon="lock"
                                        type="password"
                                        v-model="loginDetails.password"
                                    />
                                </v-form>
                            </v-card-text>
                            <v-card-actions>
                                <v-spacer />
                                <v-btn color="primary" @click="login">Login</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-content>
    </v-app>
</template>

<script>
    import { mapGetters } from "vuex";
    export default{
        props: {
            source: String,
        },
        data() {
            return {
                loginDetails : {
                    username: "joaquin.gamboaf@gmail.com",
                    password: "password",
                    checkbox_remember_me: false,
                    device_name: "app"
                }
            }
        },
        computed: {
            ...mapGetters("auth", ["authUser", "error", "loading"])
        },
        methods: {
            login() {
                const self = this;
                this.$store.dispatch("auth/login", this.$data.loginDetails).then((data) => {
                    self.$store.dispatch("auth/getUser");
                    setTimeout(function(){
                        self.$router.push({ name: 'index'})
                            .catch((err) => {
                                console.log(err);
                            });

                    }, 1000);


                }).catch((error) =>{
                    //console.log(error);
                });
            }
        },
        mounted() {
        }
    }
</script>

<style lang="scss">
    #page-login {
        .social-login-buttons {
            .bg-facebook { background-color: #1551b1 }
            .bg-twitter { background-color: #00aaff }
            .bg-google { background-color: #4285F4 }
            .bg-github { background-color: #333 }
        }
    }
</style>
