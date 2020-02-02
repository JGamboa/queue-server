<template>
    <div class="h-screen flex w-full bg-img vx-row no-gutter items-center justify-center" id="page-login">
        <div class="vx-col sm:w-1/2 md:w-1/2 lg:w-3/4 xl:w-3/5 sm:m-0 m-4">
            <vx-card>
                <div slot="no-body" class="full-page-bg-color">

                    <div class="vx-row no-gutter justify-center items-center">

                        <div class="vx-col hidden lg:block lg:w-1/2">

                        </div>

                        <div class="vx-col sm:w-full md:w-full lg:w-1/2 d-theme-dark-bg">
                            <div class="p-8 login-tabs-container">

                                <div class="vx-card__title mb-4">
                                    <h4 class="mb-4">Login</h4>
                                    <p>Bienvenido, porfavor inicie sesión en su cuenta.</p>
                                </div>

                                <div>
                                    <vs-input
                                        name="email"
                                        icon-no-border
                                        icon="icon icon-user"
                                        icon-pack="feather"
                                        label-placeholder="Email"
                                        v-model="loginDetails.email"
                                        class="w-full"/>

                                    <vs-input
                                        type="password"
                                        name="password"
                                        icon-no-border
                                        icon="icon icon-lock"
                                        icon-pack="feather"
                                        label-placeholder="Password"
                                        v-model="loginDetails.password"
                                        class="w-full mt-6" />

                                    <div class="flex flex-wrap justify-between my-5">
                                        <vs-checkbox v-model="loginDetails.checkbox_remember_me" class="mb-3">Recordarme</vs-checkbox>
                                        <router-link to="">Olvido su contraseña?</router-link>
                                    </div>

                                    <vs-button class="float-right" @click="login">Iniciar sesión</vs-button>

                                    <vs-divider>&nbsp;</vs-divider>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </vx-card>
        </div>
    </div>
</template>

<script>
    import axios from "axios";
    import { mapGetters } from "vuex";
    export default{
        data() {
            return {
                loginDetails : {
                    email: "",
                    password: "",
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
                this.$store.dispatch("auth/login", this.$data.loginDetails).then((data) => {
                    this.$store.dispatch("auth/getUser");
                    this.$router.push(this.$route.query.redirect || "/");
                }).catch((error) =>{
                    console.log(error);
                    console.log(this.$store.state.auth);
                });
            }
        },
        mounted() {
            //axios.get("airlock/csrf-cookie");
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
