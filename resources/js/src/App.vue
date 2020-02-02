<template>
	<div id="app">
        <v-layout v-if="loading" row justify-center>
            <v-dialog v-model="loading" persistent content content-class="centered-dialog">
                <v-container fill-height>
                    <v-layout column justify-center align-center>
                        <v-progress-circular indeterminate :size="70" :width="7" color="#06c5ac"></v-progress-circular>
                        <h1>Cargando</h1>
                    </v-layout>
                </v-container>
            </v-dialog>
        </v-layout>
        <router-view />
	</div>
</template>

<script>

import {mapGetters} from "vuex";

export default {
    name: 'app',
    created () {
        this.$vuetify.theme.dark = true
    },
    computed: {
        ...mapGetters("auth", ["authUser", "error", "loading"])
    },
}

</script>
