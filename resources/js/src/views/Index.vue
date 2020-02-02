<template>
    <v-row no-gutters
           align="center"
           justify="center">
        <h1>Welcome to the php challenge page</h1>
    </v-row>

</template>

<script>
    export default {
        name: "index",
        props: {
            source: String,
        },
        data: () => ({
            drawer: null,
        }),
    }
</script>

<style scoped>

</style>
