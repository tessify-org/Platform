<template>
    <div id="group-role-overview">

        <!-- Add button -->
        <div id="group-role-overview__add-button">
            <v-btn dark small depressed color="success" @click="onClickCreate">
                <i class="fas fa-plus"></i>
                {{ strings.add_button }}
            </v-btn>
        </div>

        <!-- List -->
        <div id="group-role-overview__list" v-if="mutableRoles.length > 0">
            <div class="group-role" v-for="(role, ri) in mutableRoles" :key="ri" @click="onClickView(ri)">
                <div class="group-role__name">
                    {{ role.name[locale] }}
                </div>
            </div>
        </div>
        
        <!-- No records -->
        <div id="group-role-overview__no-records" v-if="mutableRoles.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- View dialog -->
        <v-dialog v-model="dialogs.view.show" width="650">
            <div class="dialog" v-if="dialogs.view.index !== null && mutableRoles[this.dialogs.view.index] !== undefined">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.view_dialog_title }}</h3>
                    <!-- Details -->
                    <div class="details mb-0 compact bordered">
                        <!-- User -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_name }}</div>
                            <div class="val">{{ mutableRoles[this.dialogs.view.index].name[locale] }}</div>
                        </div>
                        <!-- Motivation -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_description }}</div>
                            <div class="val">{{ mutableRoles[this.dialogs.view.index].description[locale] }}</div>
                        </div>
                        <!-- Created at -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_date }}</div>
                            <div class="val">{{ mutableRoles[this.dialogs.view.index].created_at }}</div>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__right">
                        <!-- Edit -->
                        <v-btn dark depressed color="warning" @click="onClickUpdate">
                            <i class="fas fa-pencil-alt"></i>
                            {{ strings.view_dialog_update }}
                        </v-btn>
                        <!-- Delete -->
                        <v-btn dark depressed color="red" @click="onClickDelete">
                            <i class="fas fa-trash-alt"></i>
                            {{ strings.view_dialog_delete }}
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
                    <h3 class="dialog-title">{{ strings.create_dialog_title }}</h3>
                    <!-- Name -->
                    <div class="form-fields">
                        <div class="form-field">
                            <v-text-field
                                :label="strings.name+' ('+strings.nl+')'"
                                v-model="dialogs.create.form.name.nl">
                            </v-text-field>
                        </div>
                        <div class="form-field">
                            <v-text-field
                                :label="strings.name+' ('+strings.en+')'"
                                v-model="dialogs.create.form.name.en">
                            </v-text-field>
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="form-fields mb-0">
                        <div class="form-field">
                            <v-textarea
                                :label="strings.description+' ('+strings.nl+')'"
                                v-model="dialogs.create.form.description.nl">
                            </v-textarea>
                        </div>
                        <div class="form-field">
                            <v-textarea
                                :label="strings.description+' ('+strings.en+')'"
                                v-model="dialogs.create.form.description.en">
                            </v-textarea>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialog.create.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.create_dialog_cancel }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <v-btn depressed color="success" @click="onClickConfirmCreate" :loading="dialogs.create.loading" :disabled="createSubmitDisabled">
                            <i class="fas fa-save"></i>
                            {{ strings.create_dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Update dialog -->
        <v-dialog v-model="dialogs.update.show" width="650">
            <div class="dialog" v-if="dialogs.update.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.update.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.update_dialog_title }}</h3>
                    <!-- Name -->
                    <div class="form-fields">
                        <div class="form-field">
                            <v-text-field
                                :label="strings.name+' ('+strings.nl+')'"
                                v-model="dialogs.update.form.name.nl">
                            </v-text-field>
                        </div>
                        <div class="form-field">
                            <v-text-field
                                :label="strings.name+' ('+strings.en+')'"
                                v-model="dialogs.update.form.name.en">
                            </v-text-field>
                        </div>
                    </div>
                    <!-- Description -->
                    <div class="form-fields mb-0">
                        <div class="form-field">
                            <v-textarea
                                :label="strings.description+' ('+strings.nl+')'"
                                v-model="dialogs.update.form.description.nl">
                            </v-textarea>
                        </div>
                        <div class="form-field">
                            <v-textarea
                                :label="strings.description+' ('+strings.en+')'"
                                v-model="dialogs.update.form.description.en">
                            </v-textarea>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialog.update.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.update_dialog_cancel }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <v-btn depressed color="success" @click="onClickConfirmUpdate" :loading="dialogs.update.loading" :disabled="updateSubmitDisabled">
                            <i class="fas fa-save"></i>
                            {{ strings.update_dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Delete dialog -->
        <v-dialog v-model="dialogs.delete.show" width="650">
            <div class="dialog" v-if="dialogs.delete.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.delete.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.delete_dialog_title }}</h3>
                    <!-- Text -->
                    <div class="dialog-text">{{ getDeleteText() }}</div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialog.delete.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.delete_dialog_cancel }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <v-btn depressed dark color="red" @click="onClickConfirmDelete" :loading="dialogs.delete.loading">
                            <i class="fas fa-save"></i>
                            {{ strings.delete_dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

    </div>
</template>

<script>
    import { EventBus } from './../../event-bus.js';
    export default {
        props: [
            "roles",
            "strings",
            "apiEndpoints",
            "canManage",
            "locale",
        ],
        data: () => ({
            tag: "[group-role-overview]",
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
                        name: {
                            nl: "",
                            en: "",
                        },
                        description: {
                            nl: "",
                            en: "",
                        },
                    }
                },
                update: {
                    show: false,
                    loading: false,
                    errors: [],
                    index: null,
                    form: {
                        name: {
                            nl: "",
                            en: "",
                        },
                        description: {
                            nl: "",
                            en: "",
                        },
                    },
                },
                delete: {
                    show: false,
                    loading: false,
                    errors: [],
                    index: null,
                },
            },
        }),
        computed: {
            createSubmitDisabled() {
                return this.dialogs.create.form.name.nl === "";
            },
            updateSubmitDisabled() {
                return this.dialogs.update.form.name.nl === "";
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" roles: ", this.roles);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
                this.initializeData();
            },
            initializeData() {
                if (this.roles !== undefined && this.roles !== null && this.roles.length > 0) {
                    for (let i = 0; i < this.roles.length; i++) {
                        this.mutableRoles.push(this.roles[i]);
                    }
                }
            },
            onClickView(index) {
                this.dialogs.view.index = index;
                this.dialogs.view.show = true;
            },
            onClickCreate() {
                this.dialogs.create.form.name.nl = "";
                this.dialogs.create.form.name.en = "";
                this.dialogs.create.form.description.nl = "";
                this.dialogs.create.form.description.en = "";
                this.dialogs.create.show = true;
            },
            onClickConfirmCreate() {
                // Start loading
                this.dialogs.create.loading = true;
                // Compose a payload
                let payload = new FormData();
                payload.append("name_nl", this.dialogs.create.form.name.nl);
                payload.append("name_en", this.dialogs.create.form.name.en);
                payload.append("description_nl", this.dialogs.create.form.description.nl);
                payload.append("description_en", this.dialogs.create.form.description.en);
                // Make API request
                this.axios.post(this.apiEndpoints.create, payload)
                    // Request succeeded
                    .then(function(response) {
                        if (response.data.status === "success") {
                            this.mutableRoles.push(response.data.role);
                            this.dialogs.create.loading = false;
                            this.dialogs.create.show = false;
                            EventBus.$emit("group-role-counter-up");
                        } else {
                            this.dialogs.create.loading = false;
                            this.dialogs.create.errors = [response.data.error];
                        }
                    }.bind(this))
                    // Request failed
                    .catch(function(response) {
                        this.dialogs.create.loading = false;
                        this.dialogs.create.errors = [response.data.error];
                    }.bind(this));

            },
            onClickUpdate() {
                this.dialogs.update.index = this.dialogs.view.index;
                this.dialogs.update.form.name.nl = this.mutableRoles[this.dialogs.update.index].name.nl;
                this.dialogs.update.form.name.en = this.mutableRoles[this.dialogs.update.index].name.en;
                this.dialogs.update.form.description.nl = this.mutableRoles[this.dialogs.update.index].description.nl;
                this.dialogs.update.form.description.en = this.mutableRoles[this.dialogs.update.index].description.en;
                this.dialogs.view.show = false;
                this.dialogs.update.show = true;
            },
            onClickConfirmUpdate() {
                // Start loading
                this.dialogs.update.loading = true;
                // Compose a payload
                let payload = new FormData();
                payload.append("group_role_id", this.mutableRoles[this.dialogs.update.index].id);
                payload.append("name_nl", this.dialogs.update.form.name.nl);
                payload.append("name_en", this.dialogs.update.form.name.en);
                payload.append("description_nl", this.dialogs.update.form.description.nl);
                payload.append("description_en", this.dialogs.update.form.description.en);
                // Make API request
                this.axios.post(this.apiEndpoints.update, payload)
                    // Request succeeded
                    .then(function(response) {
                        if (response.data.status === "success") {
                            this.mutableRoles[this.dialogs.update.index].name.nl = response.data.role.name.nl;
                            this.mutableRoles[this.dialogs.update.index].name.en = response.data.role.name.en;
                            this.mutableRoles[this.dialogs.update.index].description.nl = response.data.role.description.nl;
                            this.mutableRoles[this.dialogs.update.index].description.en = response.data.role.description.en;
                            this.dialogs.update.loading = false;
                            this.dialogs.update.show = false;
                        } else {
                            this.dialogs.update.loading = false;
                            this.dialogs.update.errors = [response.data.error];
                        }
                    }.bind(this))
                    // Request failed
                    .catch(function(response) {
                        this.dialogs.update.loading = false;
                        this.dialogs.update.errors = [response.data.error];
                    }.bind(this));
            },
            onClickDelete() {
                this.dialogs.delete.index = this.dialogs.view.index;
                this.dialogs.view.show = false;
                this.dialogs.delete.show = true;
            },
            onClickConfirmDelete() {
                // Start loading
                this.dialogs.delete.loading = true;
                // Compose a payload
                let payload = new FormData();
                payload.append("group_role_id", this.mutableRoles[this.dialogs.delete.index].id);
                // Make API request
                this.axios.post(this.apiEndpoints.delete, payload)
                    // Request succeeded
                    .then(function(response) {
                        if (response.data.status === "success") {
                            this.mutableRoles.splice(this.dialogs.delete.index, 1);
                            this.dialogs.delete.loading = false;
                            this.dialogs.delete.show = false;
                            EventBus.$emit("group-role-counter-down");
                        } else {
                            this.dialogs.delete.loading = false;
                            this.dialogs.delete.errors = [response.data.error];
                        }
                    }.bind(this))
                    // Request failed
                    .catch(function(response) {
                        this.dialogs.delete.loading = false;
                        this.dialogs.delete.errors = [response.data.error];
                    }.bind(this));
            },
            getDeleteText() {
                if (this.mutableRoles[this.dialogs.delete.index] !== undefined) {
                    return this.strings.delete_dialog_text.replace(':name', this.mutableRoles[this.dialogs.delete.index].name[this.locale]);
                }
                return "";
            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #group-role-overview {
        position: relative;
        #group-role-overview__add-button {
            left: 0;
            top: -53px;
            width: 100%;
            z-index: 10;
            display: flex;
            position: absolute;
            padding: 0 25px 0 0;
            flex-direction: row;
            box-sizing: border-box;
            justify-content: flex-end;
        }
        #group-role-overview__list {
            .group-role {
                display: flex;
                padding: 15px 20px;
                flex-direction: row;
                align-items: center;
                transition: all .3s;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:hover {
                    cursor: pointer;
                    background-color: hsl(0, 0%, 95%);
                }
                &:last-child {
                    border-bottom: 0;
                }
                .group-role__name {
                    flex: 1;
                }
            }
        }
        #group-role-overview__no-records {
            padding: 25px;  
            box-sizing: border-box;
        }
    }
</style>