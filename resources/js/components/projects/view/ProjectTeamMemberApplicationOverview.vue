<template>
    <div id="project-team-member-application-overview">
        
        <!-- Title -->
        <h3 id="project-title">{{ strings.title }}</h3>

        <!-- Application list -->
        <div id="applications" class="elevation-2" v-if="mutableApplications.length > 0">
            <div class="application" v-for="(application, ai) in mutableApplications" :key="ai" @click="onClickApplication(ai)">
                <div class="application-user">
                    {{ application.user.formatted_name }}
                </div>
                <div class="application-role">
                    {{ application.team_role.name }}
                </div>
                <div class="application-date">
                    {{ application.formatted_created_at }}
                </div>
            </div>
        </div>

        <!-- No applications -->
        <div id="no-applications" class="elevation-2" v-if="mutableApplications.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- View application dialog -->
        <v-dialog v-model="dialogs.view.show" width="600">
            <div class="dialog" v-if="dialogs.view.index !== null && mutableApplications[dialogs.view.index] !== undefined">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.view_dialog_title }}</h3>
                    <!-- Details -->
                    <div class="details bordered compact mb-0">
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_user }}</div>
                            <div class="val">
                                <a :href="mutableApplications[dialogs.view.index].user.profile_href">
                                    {{ mutableApplications[dialogs.view.index].user.formatted_name }}
                                </a>
                            </div>
                        </div>
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_role }}</div>
                            <div class="val">{{ mutableApplications[dialogs.view.index].team_role.name }}</div>
                        </div>
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_motivation }}</div>
                            <div class="val">{{ mutableApplications[dialogs.view.index].motivation }}</div>
                        </div>
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_date }}</div>
                            <div class="val">{{ mutableApplications[dialogs.view.index].formatted_created_at }}</div>
                        </div>
                    </div>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.view.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.view.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls" v-if="project.is_owner">
                    <div class="dialog-controls__left">
                        <!-- Back -->
                        <v-btn small text @click="dialogs.view.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.view_dialog_back }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <!-- Deny -->
                        <v-btn small depressed color="red" dark @click="onClickDeny" :loading="dialogs.view.deny_loading" :disabled="viewDialogDenyDisabled">
                            <i class="fas fa-ban"></i>
                            {{ strings.view_dialog_deny }}
                        </v-btn>
                        <!-- Accept -->
                        <v-btn small depressed color="green" dark @click="onClickAccept" :loading="dialogs.view.accept_loading" :disabled="viewDialogAcceptDisabled">
                            <i class="fas fa-check"></i>
                            {{ strings.view_dialog_accept }}
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
            "applications",
            "strings",
            "apiEndpoints",
        ],
        data: () => ({
            tag: "[project-team-member-application-overview]",
            mutableApplications: [],
            dialogs: {
                view: {
                    show: false,
                    index: null,
                    errors: [],
                    deny_loading: false,
                    accept_loading: false,
                },
            },
        }),
        computed: {
            viewDialogDenyDisabled() {
                return this.dialogs.view.accept_loading;
            },
            viewDialogAcceptDisabled() {
                return this.dialogs.view.deny_loading;
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" applications: ", this.applications);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
                this.initializeData();
            },
            initializeData() {
                if (this.applications !== undefined && this.applications !== null && this.applications.length > 0) {
                    for (let i = 0; i < this.applications.length; i++) {
                        this.mutableApplications.push(this.applications[i]);
                    }
                }
            },
            onClickApplication(index) {
                this.dialogs.view.index = index;
                this.dialogs.view.show = true;
            },
            onClickDeny() {
                
                this.dialogs.view.deny_loading = true;

                let payload = new FormData();
                payload.append("team_member_application_id", this.mutableApplications[this.dialogs.view.index].id);

                this.axios.post(this.apiEndpoints.deny, payload)

                    .then(function(response) {
                        console.log(this.tag+" request succeeded", response.data);
                        if (response.data.status === "success") {
                            location.reload();
                        } else {
                            this.dialogs.view.deny_loading = false;
                            this.dialogs.view.errors.push(reponse.data.error);
                        }
                    }.bind(this))

                    .catch(function(response) {
                        console.warn(this.tag+" request failed", response.data);
                        this.dialogs.view.deny_loading = false;
                        this.dialogs.view.errors.push(response.data.error);
                    }.bind(this));

            },
            onClickAccept() {

                this.dialogs.view.accept_loading = true;

                let payload = new FormData();
                payload.append("team_member_application_id", this.mutableApplications[this.dialogs.view.index].id);

                this.axios.post(this.apiEndpoints.accept, payload)

                    .then(function(response) {
                        console.log(this.tag+" request succeeded", response.data);
                        if (response.data.status === "success") {
                            location.reload();
                        } else {
                            this.dialogs.view.errors.push(reponse.data.error);
                        }
                        this.dialogs.view.accept_loading = false;
                    }.bind(this))

                    .catch(function(response) {
                        console.warn(this.tag+" request failed", response.data);
                        this.dialogs.view.accept_loading = false;
                        this.dialogs.view.errors.push(response.data.error);
                    }.bind(this));

            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #project-team-member-application-overview {
        margin: 0 0 30px 0;
        #project-title {
            font-size: 1em;
            font-weight: 300;
            margin: 0 0 5px 0;
        }
        #applications {
            border-radius: 3px;
            background-color: #fff;
            .application {
                padding: 15px;
                display: flex;
                flex-direction: row;
                transition: all .3s;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    cursor: pointer;
                    background-color: hsl(0, 0%, 98%);
                }
                .application-user {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
                .application-role {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
                .application-date {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    justify-content: flex-end;
                }
            }
        }
        #no-applications {
            padding: 25px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #fff;
        }
    }
</style>