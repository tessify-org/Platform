<template>
    <div id="project-team-member-overview">

        <!-- Header -->
        <h3 id="project-title">{{ strings.title }}</h3>

        <!-- Team members -->
        <div id="project-team-member-overview__team-members" class="elevation-2" v-if="mutableMembers.length > 0">
            <div class="team-member" v-for="(member, mi) in mutableMembers" :key="mi" @click="onClickMember(mi)">
                <div class="team-member__avatar" :style="{ backgroundImage: 'url('+member.user.avatar_url+')' }"></div>
                <div class="team-member__text">
                    <div class="team-member__name">{{ member.user.formatted_name }}</div>
                    <div class="team-member__roles">
                        <div class="role-wrapper" v-for="(role, ri) in member.team_roles" :key="ri">
                            <div class="role">
                                {{ role.name }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- No team members -->
        <div id="project-team-member-overview__no-team-members" class="elevation-2" v-if="mutableMembers.length === 0">
            {{ strings.no_members }}
        </div>

        <!-- View team member dialog -->
        <v-dialog v-model="dialogs.view.show" width="600">
            <div class="dialog" v-if="dialogs.view.index !== null && mutableMembers[dialogs.view.index] !== undefined">
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
                                <a :href="mutableMembers[dialogs.view.index].user.profile_href">
                                    {{ mutableMembers[dialogs.view.index].user.formatted_name }}
                                </a>
                            </div>
                        </div>
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_joined_on }}</div>
                            <div class="val">{{ mutableMembers[dialogs.view.index].created_at }}</div>
                        </div>
                    </div>
                    <!-- Role information -->
                    <div v-if="mutableMembers[dialogs.view.index].team_roles.length > 0">
                        <!-- Subtitle -->
                        <h4 class="dialog-subtitle">{{ strings.view_dialog_roles }}</h4>
                        <!-- Roles -->
                        <div id="dialog-member-roles">
                            <div class="member-role" v-for="(role, ri) in mutableMembers[dialogs.view.index].team_roles" :key="ri">
                                <div class="member-role__title">
                                    {{ role.name }}
                                </div>
                                <div class="member-role__description">
                                    {{ role.description }}
                                </div>
                                <div class="member-role__skills" v-if="role.skills !== undefined && role.skills !== null && role.skills.length > 0">
                                    <div class="member-role__skills-label">{{ strings.view_dialog_required_skills }}</div>
                                    <div class="member-role__skills-list">
                                        <div class="role-skill__wrapper" v-for="(skill, si) in role.skills" :key="si">
                                            <div class="role-skill">
                                                {{ skill.name }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls" v-if="project.is_owner">
                    <div class="dialog-controls__right">
                        <!-- Update -->
                        <v-btn small depressed color="warning" @click="onClickUpdate">
                            <i class="fas fa-edit"></i>
                            {{ strings.view_dialog_update }}
                        </v-btn>
                        <!-- Kick -->
                        <v-btn small depressed color="red" dark @click="onClickKick">
                            <i class="fas fa-trash-alt"></i>
                            {{ strings.view_dialog_kick }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Update roles dialog -->
        <v-dialog v-model="dialogs.update.show" width="600">
            <div class="dialog" v-if="dialogs.update.index !== null && mutableMembers[dialogs.update.index] !== undefined">
                <!-- Close button -->
                <div class="dialog__close-button" @click="onClickCancelUpdate">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.update_dialog_title }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.update.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.update.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- User field (disabled) -->
                    <div class="form-field">
                        <v-text-field
                            disabled
                            :label="strings.update_dialog_user"
                            v-model="mutableMembers[dialogs.update.index].user.formatted_name">
                        </v-text-field>
                    </div>
                    <!-- Roles field -->
                    <div class="form-field">
                        <v-select
                            multiple
                            :label="strings.update_dialog_roles"
                            :items="dialogs.update.role_options"
                            v-model="dialogs.update.form.roles">
                        </v-select>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__left">
                        <v-btn text @click="onClickCancelUpdate">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.update_dialog_cancel }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <v-btn color="success" depressed :loading="dialogs.update.loading" @click="onClickConfirmUpdate">
                            <i class="fas fa-save"></i>
                            {{ strings.update_dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>
        
        <!-- Kick member dialog -->
        <v-dialog v-model="dialogs.kick.show" width="600">
            <div class="dialog" v-if="dialogs.kick.index !== null && mutableMembers[dialogs.kick.index] !== undefined">
                <!-- Close button -->
                <div class="dialog__close-button" @click="onClickCancelKick">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.kick_dialog_title }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.kick.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.kick.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="dialog-text">{{ strings.kick_dialog_text }}</div>
                    <!-- Reason field -->
                    <div class="form-field">
                        <v-textarea
                            :label="strings.kick_dialog_reason"
                            v-model="dialogs.kick.form.reason"
                            :placeholder="strings.kick_dialog_reason_hint">
                        </v-textarea>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__left">
                        <v-btn text @click="onClickCancelKick">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.kick_dialog_cancel }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <v-btn color="red" dark depressed :loading="dialogs.kick.loading" @click="onClickConfirmKick">
                            <i class="fas fa-save"></i>
                            {{ strings.kick_dialog_submit }}
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
            "members",
            "strings",
            "apiEndpoints",
        ],
        data: () => ({
            tag: "[project-team-member-overview]",
            mutableMembers: [],
            dialogs: {
                view: {
                    show: false,
                    index: null,
                },
                update: {
                    show: false,
                    index: null,
                    loading: false,
                    errors: [],
                    role_options: [],
                    form: {
                        roles: [],
                    },
                },
                kick: {
                    show: false,
                    index: null,
                    loading: false,
                    errors: [],
                    form: {
                        reason: "",
                    },
                },
            },
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" members: ", this.members);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
                this.initializeData();
            },
            initializeData() {
                if (this.members !== undefined && this.members !== null && this.members.length > 0) {
                    for (let i = 0; i < this.members.length; i++) {
                        this.mutableMembers.push(this.members[i]);
                    }
                }
                this.generateRoleOptions();
            },
            generateRoleOptions() {
                if (this.project !== undefined && this.project !== null && this.project.team_roles !== undefined && this.project.team_roles !== null && this.project.team_roles.length > 0) {
                    for (let i = 0; i < this.project.team_roles.length; i++) {
                        this.dialogs.update.role_options.push({
                            text: this.project.team_roles[i].name,
                            value: this.project.team_roles[i].id,
                        });
                    }
                }
            },
            onClickMember(index) {
                this.dialogs.view.index = index;
                this.dialogs.view.show = true;
            },
            onClickUpdate() {
                this.dialogs.view.show = false;
                this.dialogs.update.index = this.dialogs.view.index;
                this.dialogs.update.form.roles = [];
                for (let i = 0; i < this.mutableMembers[this.dialogs.update.index].team_roles.length; i++) {
                    this.dialogs.update.form.roles.push(this.mutableMembers[this.dialogs.update.index].team_roles[i].id);
                }
                this.dialogs.update.show = true;
            },
            onClickCancelUpdate() {
                this.dialogs.update.show = false;
                this.dialogs.view.show = true;
            },
            onClickConfirmUpdate() {
                
                this.dialogs.update.loading = true;

                let payload = new FormData();
                payload.append("team_member_id", this.mutableMembers[this.dialogs.update.index].id);
                payload.append("role_ids", JSON.stringify(this.dialogs.update.form.roles));

                this.axios.post(this.apiEndpoints.update, payload)

                    .then(function(response) {
                        console.log(this.tag+" request succeeded", response.data);
                        if (response.data.status === "success") {
                            this.mutableMembers[this.dialogs.update.index].team_roles = response.data.new_roles;
                            this.dialogs.update.show = false;
                        } else {
                            this.dialogs.update.errors.push(response.data.error);
                        }
                        this.dialogs.update.loading = false;
                    }.bind(this))

                    .catch(function(response) {
                        console.warn(this.tag+" request failed", response.data);
                        this.dialogs.update.loading = false;
                    }.bind(this));

            },
            onClickKick() {
                this.dialogs.kick.index = this.dialogs.view.index;
                this.dialogs.view.show = false;
                this.dialogs.kick.show = true;
            },
            onClickCancelKick() {
                this.dialogs.kick.show = false;
                this.dialogs.view.show = false;
            },
            onClickConfirmKick() {

                this.dialogs.kick.loading = true;

                let payload = new FormData();
                payload.append("team_member_id", this.mutableMembers[this.dialogs.kick.index].id);
                payload.append("reason", this.dialogs.kick.form.reason);
                
                this.axios.post(this.apiEndpoints.kick, payload)

                    .then(function(response) {
                        console.log(this.tag+" request succeeded", response.data);
                        if (response.data.status === "success") {
                            location.reload();
                        } else {
                            this.dialogs.kick.errors.push(response.data.error);
                        }
                        this.dialogs.kick.loading = false;
                    }.bind(this))

                    .catch(function(response) {
                        this.dialogs.kick.loading = false;
                    }.bind(this));

            }
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #project-team-member-overview {
        margin: 0 0 30px 0;
        #project-title {
            font-size: 1em;
            font-weight: 300;
            margin: 0 0 5px 0;
        }
        #project-team-member-overview__team-members {
            overflow: hidden;
            border-radius: 3px;
            background-color: #fff;
            .team-member {
                display: flex;
                padding: 15px;
                overflow: hidden;
                border-radius: 3px;
                flex-direction: row;
                align-items: center;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    cursor: pointer;
                    background-color: hsl(0, 0%, 98%);
                }
                .team-member__avatar {
                    height: 40px;
                    flex: 0 0 40px;
                    border-radius: 3px;
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-position: center center;
                }
                .team-member__text {
                    flex: 1;
                    margin: 0 0 0 15px;
                    .team-member__name {
                        font-size: 1.1em;
                        margin: 0 0 3px 0;
                    }
                    .team-member__roles {
                        display: flex;
                        flex-direction: row;
                        margin: 0 -5px -5px -5px;
                        .role-wrapper {
                            box-sizing: border-box;
                            padding: 0 5px 5px 5px;
                            .role {
                                color: #fff;
                                padding: 0 5px;
                                font-size: .8em;
                                border-radius: 2px;
                                box-sizing: border-box;
                                background-color: #111;
                            }
                        }
                    }
                }
            }
        }
        #project-team-member-overview__no-team-members {
            padding: 25px;
            overflow: hidden;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #ffffff;
        }
    }
    #dialog-member-roles {
        border-radius: 3px;
        border: 1px solid rgba(0, 0, 0, 0.1);
        .member-role {
            padding: 15px;
            box-sizing: border-box;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            &:last-child {
                border-bottom: 0;
            }
            .member-role__title {
                font-size: 1.2em;
                font-weight: 500;
                margin: 0 0 5px 0;
            }
            .member-role__description {
                font-size: .9em;
                margin: 0 0 10px 0;
            }
            .member-role__skills {
                .member-role__skills-label {
                    font-weight: 500;
                    margin: 0 0 5px 0;
                }
                .member-role__skills-list {
                    display: flex;
                    flex-wrap: wrap;
                    flex-direction: row;
                    margin: 0 -5px -5px -5px;
                    .role-skill__wrapper {
                        box-sizing: border-box;
                        padding: 0 5px 5px 5px;
                        .role-skill {
                            color: #fff;
                            font-size: .8em;
                            padding: 2px 5px;
                            border-radius: 2px;
                            box-sizing: border-box;
                            background-color: #111;
                        }
                    }
                }
            }
        }
    }
</style>