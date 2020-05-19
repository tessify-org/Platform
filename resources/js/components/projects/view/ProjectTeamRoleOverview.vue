<template>
    <div id="project-team-roles-overview">

        <!-- Header -->
        <div id="project-team-roles-overview__header">
            <div id="project-team-roles-overview__header-left">
                <h1 id="project-title">{{ strings.title }}</h1>
            </div>
            <div id="project-team-roles-overview__header-right">
                <v-btn small depressed color="primary" @click="onClickCreate">
                    <i class="fas fa-plus"></i>
                    {{ strings.add_button }}
                </v-btn>
            </div>
        </div>

        <!-- Team roles -->
        <div id="project-team-roles-overview__team-roles" v-if="mutableRoles.length > 0">
            <div class="team-role" v-for="(role, ri) in mutableRoles" :key="ri" @click="onClickView(ri)">
                <div class="team-role__name">{{ role.name }}</div>
            </div>
        </div>

        <!-- No team roles -->
        <div id="project-team-roles-overview__no-roles" v-if="mutableRoles.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- View dialog -->
        <v-dialog v-model="dialogs.view.show" width="800">
            <div class="dialog" v-if="dialogs.view.index !== null">

                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Dialog title -->
                    <h3 class="dialog-title">{{ strings.view_title }}</h3>
                    <!-- Details -->
                    <div class="details bordered mb-0">
                        <div class="detail">
                            <div class="key">{{ strings.form_name }}</div>
                            <div class="val">{{ mutableRoles[dialogs.view.index].name }}</div>
                        </div>
                        <div class="detail">
                            <div class="key">{{ strings.form_description+" "+strings.nl }}</div>
                            <div class="val">{{ mutableRoles[dialogs.view.index].description.nl }}</div>
                        </div>
                        <div class="detail">
                            <div class="key">{{ strings.form_description+" "+strings.en }}</div>
                            <div class="val">{{ mutableRoles[dialogs.view.index].description.en }}</div>
                        </div>
                        <div class="detail">
                            <div class="key">{{ strings.form_positions }}</div>
                            <div class="val">{{ mutableRoles[dialogs.view.index].positions }}</div>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__right">
                        <v-btn depressed text color="warning" @click="onClickEdit(dialogs.view.index)">
                            <i class="fas fa-edit"></i>
                            {{ strings.view_edit }}
                        </v-btn>
                        <v-btn depressed text color="red" dark @click="onClickDelete(dialogs.view.index)">
                            <i class="fas fa-trash-alt"></i>
                            {{ strings.view_delete }}
                        </v-btn>
                    </div>
                </div>

            </div>
        </v-dialog>

        <!-- Create dialog -->
        <v-dialog v-model="dialogs.create.show" width="800">
            <div class="dialog" v-if="dialogs.create.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.create.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.create_title }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.create.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.create.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Form -->
                    <div class="dialog-text">
                        <!-- Name fields -->
                        <div class="form-field">
                            <v-text-field 
                                :label="strings.form_name" 
                                v-model="dialogs.create.form.name">
                            </v-text-field>
                        </div>
                        <!-- Description -->
                        <div class="form-fields">
                            <div class="form-field">
                                <v-textarea 
                                    :label="strings.form_description + '('+strings.nl+')'" 
                                    v-model="dialogs.create.form.description.nl">
                                </v-textarea>
                            </div>
                            <div class="form-field">
                                <v-textarea 
                                    :label="strings.form_description+' ('+strings.en+')'" 
                                    v-model="dialogs.create.form.description.en">
                                </v-textarea>
                            </div>
                        </div>
                        <!-- Positions -->
                        <v-text-field type="number" :label="strings.form_positions" v-model="dialogs.create.form.positions"></v-text-field>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn depressed text @click="onClickCancelCreate">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.create_cancel }}
                        </v-btn>
                    </div>
                    <!-- Save changes -->
                    <div class="dialog-controls__right">
                        <v-btn depressed color="success" @click="onClickConfirmCreate" :loading="dialogs.create.loading">
                            <i class="fas fa-save"></i>
                            {{ strings.create_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Edit dialog -->
        <v-dialog v-model="dialogs.edit.show" width="800">
            <div class="dialog" v-if="dialogs.edit.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.edit.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.update_title }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.edit.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.edit.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Form -->
                    <div class="dialog-text">
                        <!-- Name fields -->
                        <div class="form-field">
                            <v-text-field 
                                :label="strings.form_name" 
                                v-model="dialogs.edit.form.name">
                            </v-text-field>
                        </div>
                        <!-- Description -->
                        <div class="form-fields">
                            <div class="form-field">
                                <v-textarea 
                                    :label="strings.form_description + '('+strings.nl+')'" 
                                    v-model="dialogs.edit.form.description.nl">
                                </v-textarea>
                            </div>
                            <div class="form-field">
                                <v-textarea 
                                    :label="strings.form_description+' ('+strings.en+')'" 
                                    v-model="dialogs.edit.form.description.en">
                                </v-textarea>
                            </div>
                        </div>
                        <!-- Positions -->
                        <v-text-field type="number" :label="strings.form_positions" v-model="dialogs.edit.form.positions"></v-text-field>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn depressed text @click="onClickCancelEdit">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.update_cancel }}
                        </v-btn>
                    </div>
                    <!-- Save changes -->
                    <div class="dialog-controls__right">
                        <v-btn depressed color="success" @click="onClickConfirmEdit" :loading="dialogs.edit.loading">
                            <i class="fas fa-save"></i>
                            {{ strings.update_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Delete dialog -->
        <v-dialog v-model="dialogs.delete.show" width="500">
            <div class="dialog" v-if="dialogs.delete.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="onClickCancelDelete">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.delete_title }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.delete.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.delete.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="dialog-text">{{ strings.delete_text }}</div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn depressed text @click="onClickCancelDelete">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.delete_cancel }}
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn depressed color="red" dark @click="onClickConfirmDelete" :loading="dialogs.delete.loading">
                            <i class="fas fa-trash-alt"></i>
                            {{ strings.delete_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

    </div>
</template>

<script>
    export default {
        props: [
            "project",
            "roles",
            "strings",
            "apiEndpoints",
            "locale",
        ],
        data: () => ({
            tag: "[project-team-role-overview]",
            mutableRoles: [],
            dialogs: {
                view: {
                    show: false,
                    index: null,
                },
                create: {
                    show: false,
                    loading: false,
                    errors: [],
                    form: {
                        name: "",
                        description: {
                            nl: "",
                            en: "",
                        },
                        positions: 1,
                    },
                },
                edit: {
                    show: false,
                    index: null,
                    errors: [],
                    loading: false,
                    form: {
                        name: "",
                        description: {
                            nl: "",
                            en: "",
                        },
                        positions: 1,
                    },
                },
                delete: {
                    show: false,
                    index: null,
                    errors: [],
                    loading: false,
                },
            },
        }),
        computed: {
            
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" roles: ", this.roles);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
                console.log(this.tag+" locale: ", this.locale);
                this.initializeData();
            },
            initializeData() {
                if (this.roles !== undefined && this.roles !== null && this.roles.length > 0) {
                    for (let i = 0; i < this.roles.length; i++) {
                        this.mutableRoles.push(this.roles[i]);
                    }
                }
            },
            onClickCreate() {
                this.dialogs.create.show = true;
            },
            onClickConfirmCreate() {

                this.dialogs.create.loading = true;

                let payload = new FormData();
                payload.append("project_id", this.project.id);
                payload.append("name", this.dialogs.create.form.name);
                payload.append("description_nl", this.dialogs.create.form.description.nl);
                payload.append("description_en", this.dialogs.create.form.description.en);
                payload.append("positions", this.dialogs.create.form.positions);

                this.axios.post(this.apiEndpoints.create, payload)

                    .then(function(response) {
                        console.log("request succeeded", response.data);
                        this.dialogs.create.loading = false;
                        if (response.data.status === "succcess") {
                            this.mutableRoles.push(response.data.role);
                            this.dialogs.create.show = false;
                            this.dialogs.create.form.name = "";
                            this.dialogs.create.form.description.nl = "";
                            this.dialogs.create.form.description.en = "";
                            this.dialogs.create.form.positions = 1;
                        }
                    }.bind(this))

                    .catch(function(response) {
                        console.warn("request failed", response.data);
                        this.dialogs.create.loading = false;
                    }.bind(this));

            },
            onClickView(index) {
                this.dialogs.view.index = index;
                this.dialogs.view.show = true;
            },
            onClickCreate() {
                this.dialogs.create.show = true;
            },
            onClickCancelCreate() {
                this.dialogs.create.show = false;
                this.dialogs.create.form.name = "";
                this.dialogs.create.form.description = "";
                this.dialogs.create.form.positions = 1;
            },
            onClickConfirmCreate() {

                this.dialogs.create.loading = true;

                let payload = new FormData();
                payload.append("project_id", this.project.id);
                payload.append("name", this.dialogs.create.form.name);
                payload.append("description_nl", this.dialogs.create.form.description.nl);
                payload.append("description_en", this.dialogs.create.form.description.en);
                payload.append("positions", this.dialogs.create.form.positions);

                this.axios.post(this.apiEndpoints.create, payload)

                    .then(function(response) {
                        console.log(this.tag+" request succeeded", response.data);
                        this.dialogs.create.loading = false;
                        if (response.data.status === "success") {
                            this.mutableRoles.push(response.data.role);
                            this.dialogs.create.show = false;
                            this.dialogs.create.form.name.nl = "";
                            this.dialogs.create.form.name.en = "";
                            this.dialogs.create.form.description.nl = "";
                            this.dialogs.create.form.description.en = "";
                            this.dialogs.create.form.positions = 1;
                        }
                    }.bind(this))

                    .catch(function(response) {
                        console.warn(this.tag+" request failed", response.data);
                        this.dialogs.create.loading = false;
                    }.bind(this));

            },
            onClickEdit(index) {
                if (this.dialogs.view.show) this.dialogs.view.show = false;
                this.dialogs.edit.index = index;
                this.dialogs.edit.form.name = this.mutableRoles[index].name;
                this.dialogs.edit.form.description.nl = this.mutableRoles[index].description.nl;
                this.dialogs.edit.form.description.en = this.mutableRoles[index].description.en;
                this.dialogs.edit.form.positions = this.mutableRoles[index].positions;
                this.dialogs.edit.show = true;
            },
            onClickCancelEdit() {
                this.dialogs.edit.show = false;
            },
            onClickConfirmEdit() {

                this.dialogs.edit.loading = true;

                let payload = new FormData();
                payload.append("team_role_id", this.mutableRoles[this.dialogs.edit.index].id);
                payload.append("name", this.dialogs.edit.form.name);
                payload.append("description_nl", this.dialogs.edit.form.description.nl);
                payload.append("description_en", this.dialogs.edit.form.description.en);
                payload.append("positions", this.dialogs.edit.form.positions);

                this.axios.post(this.apiEndpoints.update, payload)

                    .then(function(response) {
                        console.log("request succeeded", response.data);
                        this.dialogs.edit.loading = false;
                        if (response.data.status === "success") {
                            this.dialogs.edit.show = false;
                            this.mutableRoles[this.dialogs.edit.index].name = this.dialogs.edit.form.name;
                            this.mutableRoles[this.dialogs.edit.index].description.nl = this.dialogs.edit.form.description.nl;
                            this.mutableRoles[this.dialogs.edit.index].description.en = this.dialogs.edit.form.description.en;
                            this.mutableRoles[this.dialogs.edit.index].positions = this.dialogs.edit.form.positions;
                        }
                    }.bind(this))

                    .catch(function(response) {
                        console.warn("request failed", response.data);
                        this.dialogs.edit.loading = false;
                    }.bind(this));

            },
            onClickDelete(index) {
                if (this.dialogs.view.show) this.dialogs.view.show = false;
                this.dialogs.delete.index = index;
                this.dialogs.delete.show = true;
            },
            onClickCancelDelete() {
                this.dialogs.delete.show = false;
            },
            onClickConfirmDelete() {

                this.dialogs.delete.loading = true;

                let payload = new FormData();
                payload.append("team_role_id", this.mutableRoles[this.dialogs.delete.index].id);

                this.axios.post(this.apiEndpoints.delete, payload)
                    
                    .then(function(response) {
                        console.log(this.tag+" request succeeded", response.data);
                        this.dialogs.delete.loading = false;
                        if (response.data.status === "success") {
                            this.mutableRoles.splice(this.dialogs.delete.index, 1);
                            this.dialogs.delete.show = false;
                        }
                    }.bind(this))

                    .catch(function(response) {
                        console.warn(this.tag+" request failed", response.data);
                        this.dialogs.delete.loading = false;
                    }.bind(this));

            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #project-team-roles-overview {
        #project-team-roles-overview__header {
            display: flex;
            margin: 0 0 30px 0;
            flex-direction: row;
            align-items: center;
            #project-team-roles-overview__header-left {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
                #project-title {
                    margin: 0 !important;
                }
            }
            #project-team-roles-overview__header-right {

            }
        }
        #project-team-roles-overview__team-roles {
            border-radius: 3px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            .team-role {
                display: flex;
                color: #000;
                padding: 10px 15px;
                transition: all .3s;
                flex-direction: row;
                text-decoration: none;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    color: #0a64eb;
                    cursor: pointer;
                }
                .team-role__name {
                    flex: 1;
                }
            }
        }
        #project-team-roles-overview__no-roles {
               
        }
    }
</style>