<template>
    <div>
    <v-row class="mb-6" no-gutters>
        <v-data-table
            :headers="headers"
            :items="items"
            sort-by="command"
            class="elevation-1 col-12 pa-2"
            :options.sync="options"
            :loading="loading"
            :server-items-length="pagination.totalItems"
            :items-per-page="options.itemsPerPage"
        >
            <template v-slot:top>
                <v-toolbar dark flat>
                    <v-toolbar-title>Jobs</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="dialog" max-width="50%">
                        <template v-slot:activator="{ on }">
                            <v-btn color="primary" dark class="mb-2" v-on="on">New</v-btn>
                        </template>
                        <v-card>
                            <v-card-title class="modal-header">
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <v-row>
                                        <v-col cols="12" sm="12" md="12">
                                            <v-text-field outlined v-model="editedItem.command" label="Command"></v-text-field>
                                        </v-col>

                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions class="modal-footer">
                                <v-spacer></v-spacer>
                                <v-btn class="btn btn-danger" text @click="close">Close</v-btn>
                                <v-btn class="btn btn-primary" text @click="save">Save</v-btn>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:no-data>
                <v-btn color="primary" @click="initialize">Reset</v-btn>
            </template>
            <template v-slot:footer>
                <div class="text-center pt-2" v-if="options.itemsPerPage !== -1">
                    <v-pagination v-model="options.page" :length="pagination.lastPage"></v-pagination>
                </div>
            </template>
        </v-data-table>
    </v-row>

    <v-row no-gutters>
        <v-data-table
            :headers="headers"
            :items="items_realtime"
            sort-by="command"
            class="elevation-1 col-12 pa-2"
            :items-per-page="-1"
        >
            <template v-slot:top>
                <v-toolbar dark flat>
                    <v-toolbar-title>Last Jobs, state changes in real time</v-toolbar-title>
                    <v-divider class="mx-4" inset vertical></v-divider>
                    <v-spacer></v-spacer>
                </v-toolbar>
            </template>
        </v-data-table>
    </v-row>
    </div>
</template>

<script>
    import { mapGetters } from "vuex";
    export default {
        name: 'Jobs',
        created () {
            Echo.private(`App.User.${this.authUser.id}`)
                .listen('JobStateChanged', (e) => {
                    const items_no_realtime_filtered = this.items.filter(x => x.id === e.job.id);
                    this.items_realtime.unshift(e.job);
                    if (items_no_realtime_filtered.length >= 1) {
                        items_no_realtime_filtered[0].state = e.job.state;
                        items_no_realtime_filtered[0].queue_job_id = e.job.queue_job_id;
                        items_no_realtime_filtered[0].processor_id = e.job.processor_id;
                        items_no_realtime_filtered[0].started_at = e.job.started_at;
                        items_no_realtime_filtered[0].finished_at = e.job.finished_at;
                    }
                })
        },
        computed: {
            ...mapGetters("auth", ["authUser"])
        },
        data() {
            return {
                formTitle: 'Create Job',
                errors: [],
                showModal: false,
                loading: true,
                options: { itemsPerPage: 5},
                pagination: {
                    totalItems: 0,
                    lastPage: 0,
                },
                item: {
                    id: null,
                    queue_job_id: null,
                    submitter_id: null,
                    processor_id: null,
                    command: '',
                    state: '',
                    started_at: '',
                    finished_at: '',
                    created_at: ''
                },
                items: [],
                items_realtime: [],
                dialog: false,
                headers: [
                    { text: 'Job Id', value: 'id' },
                    { text: 'Redis Job Id', value: 'queue_job_id' },
                    { text: 'Submitter Id', value: 'submitter_id' },
                    { text: 'Processor Id', value: 'processor_id' },
                    { text: 'Command', value: 'command' },
                    { text: 'State', value: 'state' },
                    { text: 'Started at', value: 'started_at' },
                    { text: 'Finished at', value: 'finished_at' },
                    { text: 'Created at', value: 'created_at' },
                    { text: 'Actions', value: 'action', sortable: false },
                ],
                editedIndex: -1,
                editedItem: {
                    id: null,
                    queue_job_id: null,
                    submitter_id: null,
                    processor_id: null,
                    command: '',
                    state: '',
                    started_at: '',
                    finished_at: '',
                    created_at: ''
                },
            }
        },
        watch: {
            dialog (val) {
                val || this.close()
            },
            options: {
                handler() {
                    this.getItems();
                },
                deep: true,
            },
        },
        methods: {
            initialize(){
                //this.getItems();
            },
            getItems(){
                this.loading = true;
                return new Promise((resolve, reject) => {
                    const { sortBy, sortDesc, page, itemsPerPage } = this.options;

                    this.$http.get('/jobs', { params: this.options } ).then(response => {
                        if(this.options.itemsPerPage === -1){
                            this.options.page = 1;
                            this.pagination.totalItems = this.items.length;
                            this.items = response.data ? response.data.data : [];
                        }else{
                            this.options.page = response.data.current_page;
                            this.options.itemsPerPage = parseInt(response.data.per_page);
                            this.pagination.totalItems = response.data.total;
                            this.pagination.lastPage = response.data.last_page;
                            this.items = response.data ? response.data.data : [];
                        }
                    });

                    if (sortBy.length === 1 && sortDesc.length === 1) {
                        this.items = this.items.sort((a, b) => {
                            const sortA = a[sortBy[0]]
                            const sortB = b[sortBy[0]]

                            if (sortDesc[0]) {
                                if (sortA < sortB) return 1
                                if (sortA > sortB) return -1
                                return 0
                            } else {
                                if (sortA < sortB) return -1
                                if (sortA > sortB) return 1
                                return 0
                            }
                        })
                    }

                    if (itemsPerPage > 0) {
                        this.items = this.items.slice((page - 1) * itemsPerPage, page * itemsPerPage)
                    }

                    this.loading = false;
                })
            },
            editItem (item) {
                this.editedIndex = this.items.indexOf(item);
                this.$http.get('/jobs/' + item.id)
                    .then(response => {
                        this.initItem();
                        this.item = response.data.data;
                        this.editedItem = Object.assign({}, this.item);
                    })
                    .catch( error => {
                        //this.$wait.end('loading');
                        this.close();
                        /*this.$notify({
                            group: 'messages',
                            title: 'Error',
                            type: 'error',
                            text: error.response.data.message
                        });*/
                    });

                this.dialog = true;
            },
            close () {
                this.dialog = false;
                setTimeout(() => {
                    this.initItem();
                    this.editedItem = Object.assign({}, this.item);
                    this.editedIndex = -1;
                }, 300)
            },
            save () {
                //this.$wait.start('loading');
                this.$http.post('/jobs', this.editedItem)
                    .then(response => {
                        this.editedItem = response.data;
                        this.items.unshift(this.editedItem);
                        //this.$wait.end('loading');
                        this.close();
                        /*this.$notify({
                            group: 'messages',
                            title: 'Sucursal creada exitosamente',
                            type: 'success',
                            text: ''
                        });*/
                    })
                    .catch( error => {
                        /*this.$wait.end('loading');
                        this.$notify({
                            group: 'messages',
                            title: 'Error al crear Sucursal',
                            type: 'error',
                            text: ''
                        });*/
                    });
            },
            initItem(){
                this.item = {
                    id: null,
                    queue_job_id: null,
                    submitter_id: null,
                    processor_id: null,
                    command: '',
                    state: '',
                    started_at: '',
                    finished_at: '',
                    created_at: ''
                }
            },
        }
    }
</script>

<style scoped>
    .row {
        margin-right: 0;
        margin-left: 0;
    }
</style>
