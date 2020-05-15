<template>
    <div id="project-team-applications">

        <div id="member-applications">
            <div id="member-applications__list" v-if="mutableApplications.length > 0">
                <div class="member-application" v-for="(application, ai) in mutableApplications" :key="ai" @click="onClickMemberApplication(ai)">
                    <div class="status-wrapper">
                        <div class="status" :class="getApplicationStatusClass(application)">
                            {{ getApplicationStatusLabel(application) }}
                        </div>                                    
                    </div>
                    <div class="user-name">
                        {{ application.user.formatted_name }}
                    </div>
                    <div class="role-name">
                        {{ application.team_role.name }}
                    </div>
                    <div class="created-at">
                        {{ application.formatted_created_at }}
                    </div>
                </div>
            </div>
            <div id="member-applications__empty" v-if="mutableApplications.length === 0">
                Er zijn nog geen aanmeldingen binnengekomen.
            </div>
        </div>

        <!-- Apply for team member position dialog -->
        <v-dialog v-model="dialogs.apply.show" width="500">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.apply.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">Aanmelden als {{ getApplicationJobTitle() }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.apply.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.apply.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Motivation -->
                    <div class="form-field">
                        <v-textarea 
                            label="Motivatie" 
                            placeholder="Waarom zou je deze rol willen vervullen?"
                            :loading="dialogs.apply.loading"
                            v-model="dialogs.apply.form.motivation">
                        </v-textarea>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.apply.show = false">
                            <i class="fas fa-arrow-left"></i>
                            Annuleren
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn 
                            depressed 
                            color="success"
                            @click="onClickConfirmApply" 
                            :loading="dialogs.apply.loading" 
                            :dark="!submitApplicationDisabled"
                            :disabled="submitApplicationDisabled">
                            <i class="far fa-save"></i>
                            Opslaan
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Edit application dialog -->
        <v-dialog v-model="dialogs.edit_application.show" width="500">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.edit_application.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">Aanmelding wijzigingen</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.edit_application.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.edit_application.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Motivation -->
                    <div class="form-field">
                        <v-textarea 
                            label="Motivatie" 
                            placeholder="Waarom zou je deze rol willen vervullen?"
                            :loading="dialogs.edit_application.loading"
                            v-model="dialogs.edit_application.form.motivation">
                        </v-textarea>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.edit_application.show = false">
                            <i class="fas fa-arrow-left"></i>
                            Annuleren
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn 
                            depressed 
                            color="success"
                            @click="onClickConfirmEditApplication" 
                            :loading="dialogs.edit_application.loading" 
                            :dark="!editApplicationDisabled"
                            :disabled="editApplicationDisabled">
                            <i class="far fa-save"></i>
                            Opslaan
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Delete application dialog -->
        <v-dialog v-model="dialogs.delete_application.show" width="500">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.delete_application.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">Aanmelding wijzigingen</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.delete_application.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.delete_application.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="dialog-text">
                        Weet je zeker dat je deze aanmelding wilt verwijderen?
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn 
                            text 
                            @click="dialogs.delete_application.show = false">
                            <i class="fas fa-arrow-left"></i>
                            Nee, annuleren
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn 
                            dark depressed color="red"
                            @click="onClickConfirmDeleteApplication" 
                            :loading="dialogs.delete_application.loading">
                            <i class="fas fa-trash"></i>
                            Ja, verwijderen
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- View team member application dialog -->
        <v-dialog v-model="dialogs.application.show" width="700">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.application.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">Bekijk aanmelding</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.application.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.application.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <div id="application-dialog-text" v-if="mutableApplications[dialogs.application.index] !== undefined">
                        <!-- Role -->
                        <div id="role-name">
                            <div id="role-name__label">Aangemeld voor de rol</div>
                            <div id="role-name__text">
                                {{ mutableApplications[dialogs.application.index].team_role.name }}
                            </div>
                        </div>
                        <!-- User -->
                        <div id="user-wrapper">
                            <user-pill dark :user="mutableApplications[dialogs.application.index].user"></user-pill>
                        </div>
                        <!-- Motivation -->
                        <div id="motivation">
                            <div id="motivation-label">Motivatie</div>
                            <div id="motivation-text">
                                {{ mutableApplications[dialogs.application.index].motivation }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls" v-if="showApplicationDialogControls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <!-- Deny button -->
                        <v-btn 
                            depressed 
                            color="red" 
                            @click="onClickDenyApplication"
                            :dark="!denyApplicationDisabled"
                            :loading="denyApplicationLoading"
                            :disabled="denyApplicationDisabled">
                            <i class="far fa-thumbs-down"></i>
                            Afwijzen
                        </v-btn>
                        <!-- Accept button -->
                        <v-btn 
                            depressed 
                            color="success"
                            @click="onClickAcceptApplication"
                            :loading="acceptApplicationLoading"
                            :disabled="acceptApplicationDisabled">
                            <i class="far fa-thumbs-up"></i>
                            Accepteren
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <!-- Edit button -->
                        <v-btn
                            depressed
                            color="warning"
                            @click="onClickEditApplication(dialogs.application.index)">
                            Edit
                        </v-btn>
                        <!-- Delete button -->
                        <v-btn
                            dark
                            depressed
                            color="red"
                            @click="onClickDeleteApplication(dialogs.application.index)">
                            Delete
                        </v-btn>
                    </div>
                </div>
                <!-- Alternative to controls -->
                <div class="dialog-controls" v-if="!showApplicationDialogControls && mutableApplications[dialogs.application.index] !== undefined">
                    <div class="dialog-controls__left">
                        {{ getApplicationStatusLabel(mutableApplications[dialogs.application.index]) }}
                    </div>
                    <div class="dialog-controls__right">
                        <!-- Edit button -->
                        <v-btn
                            small
                            depressed
                            color="warning"
                            @click="onClickEditApplication(dialogs.application.index)">
                            Edit
                        </v-btn>
                        <!-- Delete button -->
                        <v-btn
                            small
                            dark
                            depressed
                            color="red"
                            @click="onClickDeleteApplication(dialogs.application.index)">
                            Delete
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
        ],
        data: () => ({
            tag: "[project-team-applications]",
            mutableApplications: [],
            dialogs: {
                apply: {
                    show: false,
                    index: null,
                    errors: [],
                    loading: false,
                    form: {
                        motivation: "",
                    }
                },
                application: {
                    show: false,
                    index: null,
                    errors: [],
                    loading: false,
                    operation: null,
                },
                edit_application: {
                    closed_view: false,
                    show: false,
                    index: null,
                    loading: false,
                    errors: [],
                    form: {
                        motivation: "",
                    }
                },
                delete_application: {
                    closed_view: false,
                    show: false,
                    index: null,
                    loading: false,
                    errors: [],
                },
                unassign: {
                    show: false,
                    index: null,
                    loading: false,
                    errors: [],
                },
            },
        }),
        computed: {
            submitApplicationDisabled() {
                return this.dialogs.apply.form.motivation === "";
            },
            acceptApplicationDisabled() {
                return this.dialogs.application.operation === "deny" && this.dialogs.application.loading;
            },
            acceptApplicationLoading() {
                return this.dialogs.application.operation === 'accept' && this.dialogs.application.loading;
            },
            denyApplicationDisabled() {
                return this.dialogs.application.operation === "accept" && this.dialogs.application.loading;
            },
            denyApplicationLoading() {
                return this.dialogs.application.operation === 'deny' && this.dialogs.application.loading;
            },
            showApplicationDialogControls() {
                return this.mutableApplications[this.dialogs.application.index] !== undefined && 
                      !this.mutableApplications[this.dialogs.application.index].processed;
            },
            editApplicationDisabled() {
                return this.dialogs.edit_application.form.motivation === "";
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" applications: ", this.applications);
                console.log(this.tag+" create team member application api endpoint: ", this.createTeamMemberApplicationApiEndpoint);
                console.log(this.tag+" update team member application api endpoint: ", this.updateTeamMemberApplicationApiEndpoint);
                console.log(this.tag+" delete team member application api endpoint: ", this.updateTeamMemberApplicationApiEndpoint);
                console.log(this.tag+" accept team member application api endpoint: ", this.acceptTeamMemberApplicationApiEndpoint);
                console.log(this.tag+" deny team member application api endpoint: ", this.denyTeamMemberApplicationApiEndpoint);
                this.initializeData();
            },
            initializeData() {
                if (this.applications !== undefined && this.applications !== null && this.applications.length > 0) {
                    for (let i = 0; i < this.applications.length; i++) {
                        this.mutableApplications.push(this.applications[i]);
                    }
                }
            },
            onClickApplyForRole(index) {
                console.log(this.tag+" clicked apply for role button", index);
                this.dialogs.apply.index = index;
                this.dialogs.apply.show = true;
            },
            onClickConfirmApply() {
                console.log(this.tag+" clicked confirm apply button");

                this.dialogs.apply.loading = true;
                this.dialogs.apply.errors = [];
                
                let payload = new FormData();
                payload.append("project_id", this.project.id);
                payload.append("team_role_id", this.project.team_roles[this.dialogs.apply.index].id);
                payload.append("motivation", this.dialogs.apply.form.motivation);
                
                console.log(this.tag+" sending API request");
                this.axios.post(this.createTeamMemberApplicationApiEndpoint, payload)
                    .then(function(response) {
                        console.log(this.tag+" request succeeded");
                        if (response.data.status === "success") {
                            console.log(this.tag+" operation succeeded");
                            this.mutableApplications.push(response.data.application);
                            this.dialogs.apply.loading = false;
                            this.dialogs.apply.show = false;
                            this.dialogs.apply.form.description = "";
                        } else {
                            console.warn(this.tag+" operation failed", response.data.error);
                            this.dialogs.apply.loading = false;
                            this.dialogs.apply.errors = [response.data.error];
                        }
                    }.bind(this))
                    .catch(function(error) {
                        console.log(this.tag+" request failed", error);
                        this.dialogs.apply.loading = false;
                        this.dialogs.apply.errors = [error];
                    }.bind(this));

            },
            getApplicationJobTitle() {
                if (this.dialogs.apply.index !== null && this.project.team_roles[this.dialogs.apply.index] !== undefined) {
                    return this.project.team_roles[this.dialogs.apply.index].name;
                }
                return "..";
            },
            onClickMemberApplication(index) {
                console.log(this.tag+" clicked member application", index);
                console.log("-- aids", this.mutableApplications[index]);
                this.dialogs.application.index = index;
                this.dialogs.application.show = true;
            },
            onClickDenyApplication() {
                console.log(this.tag+" clicked deny application button");
                
                this.dialogs.application.operation = "deny";
                this.dialogs.application.loading = true;
                
                let payload = new FormData();
                payload.append("team_member_application_id", this.mutableApplications[this.dialogs.application.index].id);
                
                console.log(this.tag+" sending API request");
                this.axios.post(this.denyTeamMemberApplicationApiEndpoint, payload)
                    .then(function(response) {
                        console.log(this.tag+" request succeeded", response.data);
                        if (response.data.status === "success") {
                            console.log(this.tag+" operation succeeded");
                            this.mutableApplications[this.dialogs.application.index].accepted = false;
                            this.mutableApplications[this.dialogs.application.index].processed = true;
                            this.dialogs.application.loading = false;
                            this.dialogs.application.show = false;
                        } else {
                            console.warn(this.tag+" operation failed", response.data.error);
                            this.dialogs.application.errors = [response.data.error];
                            this.dialogs.application.loading = false;
                        }
                    }.bind(this))
                    .catch(function(error) {
                        console.warn(this.tag+" request failed", error);
                        this.dialogs.application.loading = false;
                        this.dialogs.application.errors = [error];
                    }.bind(this));
            },
            onClickAcceptApplication() {
                console.log(this.tag+" clicked accept application button");
                
                this.dialogs.application.operation = "accept";
                this.dialogs.application.loading = true;
                
                let payload = new FormData();
                payload.append("team_member_application_id", this.mutableApplications[this.dialogs.application.index].id);

                console.log(this.tag+" sending API request");
                this.axios.post(this.acceptTeamMemberApplicationApiEndpoint, payload)
                    .then(function(response) {
                        console.log(this.tag+" request succeeded", response.data);
                        if (response.data.status === "success") {
                            console.log(this.tag+" operation succeeded");
                            this.mutableApplications[this.dialogs.application.index].accepted = true;
                            this.mutableApplications[this.dialogs.application.index].processed = true;
                            this.dialogs.application.loading = false;
                            this.dialogs.application.show = false;
                        } else {
                            console.warn(this.tag+" operation failed", response.data.error);
                            this.dialogs.application.errors = [response.data.error];
                            this.dialogs.application.loading = false;
                        }
                    }.bind(this))
                    .catch(function(error) {
                        console.warn(this.tag+" request failed", error);
                        this.dialogs.application.loading = false;
                        this.dialogs.application.errors = [error];
                    }.bind(this));
            },
            getApplicationStatusClass(application) {
                if (application.processed) {
                    if (application.accepted) {
                        return "accepted";
                    } else {
                        return "denied";
                    }
                }
                return "open";
            },
            getApplicationStatusLabel(application) {
                if (application.processed) {
                    if (application.accepted) {
                        return "Geaccepteerd";
                    } else {
                        return "Afgewezen";
                    }
                }
                return "Open";
            },
            onClickEditApplication(index) {
                console.log(this.tag+" clicked edit button");

                if (this.dialogs.application.show) {
                    this.dialogs.application.show = false;
                    this.dialogs.edit_application.closed_view = true;
                }
                
                this.dialogs.edit_application.index = index;
                this.dialogs.edit_application.form.motivation = this.mutableApplications[index].motivation;
                this.dialogs.edit_application.show = true;

            },
            onClickConfirmEditApplication() {
                console.log(this.tag+" clicked confirm edit button");

                this.dialogs.edit_application.loading = true;
                this.dialogs.edit_application.errors = [];

                let payload = new FormData();
                payload.append("team_member_application_id", this.mutableApplications[this.dialogs.edit_application.index].id);
                payload.append("motivation", this.dialogs.edit_application.form.motivation);

                this.axios.post(this.updateTeamMemberApplicationApiEndpoint, payload)
                    .then(function(response) {
                        if (response.data.status === "success") {
                            this.mutableApplications[this.dialogs.edit_application.index].motivation = this.dialogs.edit_application.form.motivation;
                            this.dialogs.edit_application.show = false;
                            this.dialogs.edit_application.loading = false;
                            if (this.dialogs.edit_application.closed_view) {
                                this.dialogs.application.show = true;
                            }
                        } else {
                            this.dialogs.edit_application.loading = false;
                            this.dialogs.edit_application.errors [response.data.error];
                        }
                    }.bind(this))
                    .catch(function(error) {
                        this.dialogs.edit_application.loading = false;
                        this.dialogs.edit_application.errors = [error];
                    }.bind(this));

            },
            onClickDeleteApplication(index) {
                console.log(this.tag+" clicked delete button", index);

                if (this.dialogs.application.show) this.dialogs.application.show = false;
                
                this.dialogs.delete_application.index = index;
                this.dialogs.delete_application.show = true;
                
            },
            onClickConfirmDeleteApplication() {
                console.log(this.tag+" clicked confirm delete button");

                this.dialogs.delete_application.loading = true;
                this.dialogs.delete_application.errors = [];

                let payload = new FormData();
                payload.append("team_member_application_id", this.mutableApplications[this.dialogs.delete_application.index].id);

                this.axios.post(this.deleteTeamMemberApplicationApiEndpoint, payload)
                    .then(function(response) {
                        if (response.data.status === "success") {
                            this.mutableApplications.splice(this.dialogs.delete_application.index, 1);
                            this.dialogs.delete_application.loading = false;
                            this.dialogs.delete_application.show = false;
                        } else {
                            this.dialogs.delete_application.loading = false;
                            this.dialogs.delete_application.errors = [response.data.error];
                        }
                    }.bind(this))
                    .catch(function(error) {
                        this.dialogs.delete_application.errors = [error];
                        this.dialogs.delete_application.loading = true;
                    }.bind(this));
            },
            onClickUnassignMember(index) {
                console.log(this.tag+" clicked unassign member button", index);
                this.dialogs.unassign.index = index;
                this.dialogs.unassign.show = true;
            },
            onClickConfirmUnassignMember() {
                console.log(this.tag+" clicked confirm unassign member button");

                this.dialogs.unassign.loading = true;
                this.dialogs.unassign.errors = [];

                let payload = new FormData();
                payload.append("team_role_id", this.mutableRoles[this.dialogs.unassign.index].id);

                this.axios.post()


            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #project-team-applications {

    }
</style>