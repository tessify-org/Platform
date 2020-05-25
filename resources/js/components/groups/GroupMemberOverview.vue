<template>
    <div id="group-member-overview">

        <!-- Members list -->
        <div id="group-member-overview__list" v-if="mutableMembers.length > 0">
            <div class="group-member" v-for="(member, mi) in mutableMembers" :key="mi">
                <div class="group-member__avatar" :style="{ backgroundImage: 'url('+member.user.avatar_url+')' }"></div>
                <div class="group-member__name">
                    <a :href="member.user.profile_href">
                        {{ member.user.formatted_name }}
                    </a>        
                </div>
                <div class="group-member__role">{{ member.role.name[locale] }}</div>
                <div class="group-member__actions" v-if="canManage">
                    <div class="action kick" @click="onClickKick(mi)" v-if="member.role.name.en !== 'Founder'">
                        {{ strings.kick }}
                    </div>
                    <div class="action update" @click="onClickUpdate(mi)" v-if="member.role.name.en !== 'Founder'">
                        {{ strings.update }}
                    </div>
                </div>
            </div>
        </div>

        <!-- No members -->
        <div id="group-member-overview__no-records" v-if="mutableMembers.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- Kick dialog -->
        <v-dialog v-model="dialogs.kick.show" width="500">
            <div class="dialog" v-if="dialogs.kick.index !== null && mutableMembers[dialogs.kick.index] !== undefined">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.kick.show = false">
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
                    <div class="dialog-text no-margin">{{ getKickDialogText() }}</div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.kick.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.kick_dialog_cancel }}
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn dark depressed color="red" @click="onClickConfirmKick" :loading="dialogs.kick.loading">
                            <i class="fas fa-trash-alt"></i>
                            {{ strings.kick_dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Update dialog -->
        <v-dialog v-model="dialogs.update.show" width="500">
            <div class="dialog" v-if="dialogs.update.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.update.show = false">
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
                    <!-- Text -->
                    <div class="dialog-text no-margin">
                        <!-- Role -->
                        <v-select
                            :items="roleOptions"
                            :label="strings.update_dialog_role"
                            v-model="dialogs.update.form.group_role_id">
                        </v-select>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.update.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.update_dialog_cancel }}
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn dark depressed color="success" @click="onClickConfirmUpdate" :loading="dialogs.update.loading">
                            <i class="fas fa-save"></i>
                            {{ strings.update_dialog_submit }}
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
            "members",
            "roles",
            "strings",
            "apiEndpoints",
            "canManage",
            "locale",
        ],
        data: () => ({
            tag: "[group-member-overview]",
            mutableMembers: [],
            roleOptions: [],
            dialogs: {
                kick: {
                    show: false,
                    loading: false,
                    index: null,
                    errors: [],
                },
                update: {
                    show: false,
                    loading: false,
                    index: null,
                    errors: [],
                    form: {
                        group_role_id: 0,
                    },
                },
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" members: ", this.members);
                console.log(this.tag+" roles: ", this.roles);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
                console.log(this.tag+" can manage: ", this.canManage);
                this.generateRoleOptions();
                this.initializeData();
            },
            generateRoleOptions() {
                if (this.roles !== undefined && this.roles !== null && this.roles.length > 0) {
                    for (let i = 0; i < this.roles.length; i++) {
                        this.roleOptions.push({
                            text: this.roles[i].name[this.locale],
                            value: this.roles[i].id,
                        });
                    }
                }
            },
            initializeData() {
                if (this.members !== undefined && this.members !== null && this.members.length > 0) {
                    for (let i = 0; i < this.members.length; i++) {
                        this.mutableMembers.push(this.members[i]);
                    }
                }
            },
            onClickKick(index) {
                console.log(this.tag+" clicked kick button", index);
                this.dialogs.kick.index = index;
                this.dialogs.kick.show = true;
            },
            onClickConfirmKick() {
                // Start loading
                this.dialogs.kick.loading = true;
                // Create payload
                let payload = new FormData();
                payload.append("group_member_id", this.mutableMembers[this.dialogs.kick.index].id);
                // Make API request
                this.axios.post(this.apiEndpoints.kick, payload)
                    // Request succeeded
                    .then(function(response) {
                        console.log(this.tag+" request succeeded");
                        this.dialogs.kick.loading = false;
                        if (response.data.status === "success") {
                            console.log(this.tag+" operation succeeded");
                            this.mutableMembers.splice(this.dialogs.kick.index, 1);
                            this.dialogs.kick.loading = false;
                            this.dialogs.kick.show = false;
                        } else {
                            console.log(this.tag+" operation failed");
                            this.dialogs.kick.loading = false;
                            this.dialogs.kick.errors = [response.data.error];
                        }
                    }.bind(this))
                    // Request failed
                    .catch(function(response) {
                        console.warn(this.tag+" request failed", response.data);
                        this.dialogs.kick.loading = false;
                        this.dialogs.kick.errors = [response.data.error];
                    }.bind(this));
            },
            getKickDialogText() {
                return this.strings.kick_dialog_text.replace(':name', this.mutableMembers[this.dialogs.kick.index].user.formatted_name);
            },
            onClickUpdate(index) {
                this.dialogs.update.form.group_role_id = this.mutableMembers[index].group_role_id;
                this.dialogs.update.index = index;
                this.dialogs.update.show = true;
            },
            onClickConfirmUpdate() {
                // Start loading
                this.dialogs.update.loading = true;
                // Create payload
                let payload = new FormData();
                payload.append("group_role_id", this.dialogs.update.form.group_role_id);
                payload.append("group_member_id", this.mutableMembers[this.dialogs.update.index].id);
                // Make API request
                this.axios.post(this.apiEndpoints.update, payload)
                    // Request succeeded
                    .then(function(response) {
                        console.log(this.tag+" request succeeded");
                        if (response.data.status === "success") {
                            console.log(this.tag+" operation succeeded");
                            let role = this.getRoleById(this.dialogs.update.form.group_role_id);
                            this.mutableMembers[this.dialogs.update.index].role = role;
                            this.dialogs.update.loading = false;
                            this.dialogs.update.show = false;
                            this.$forceUpdate();
                        } else {
                            console.log(this.tag+" operation failed");
                            this.dialogs.update.errors = [response.data.error];
                            this.dialogs.update.loading = false;
                        }
                    }.bind(this))
                    // Request failed
                    .catch(function(response) {
                        console.warn(this.tag+" request failed", response.data);
                        this.dialogs.update.errors = [response.data.error];
                        this.dialogs.update.loading = false;
                    }.bind(this));
            },
            getRoleById(id) {
                for (let i = 0; i < this.roles.length; i++) {
                    if (this.roles[i].id === id) {
                        return this.roles[i];
                    }
                }
                return false;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #group-member-overview {
        #group-member-overview__list {
            .group-member {
                display: flex;
                color: #000;
                flex-direction: row;
                align-items: center;
                box-sizing: border-box;
                padding: 15px 20px 15px 15px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                .group-member__avatar {
                    flex: 0 0 30px;
                    height: 30px;
                    border-radius: 15px;
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center center;
                }
                .group-member__name {
                    flex: 1;
                    padding: 0 0 0 15px;
                    a, a:active, a:visited {
                        color: #000;
                        text-decoration: none;
                        &:hover {
                            text-decoration: underline;
                        }
                    }
                }
                .group-member__role {
                    flex: 1;
                    text-align: right;
                    padding: 0 0 0 15px;
                }
                .group-member__actions {
                    display: flex;
                    flex-direction: row;
                    padding: 0 0 0 15px;
                    .action {
                        color: #fff;
                        cursor: pointer;
                        padding: 2px 8px;
                        border-radius: 3px;
                        margin: 0 0 0 10px;
                        transition: all .3s;
                        box-sizing: border-box;
                        background-color: #111111;
                        &.kick {
                            background-color: #cf0202;
                            &:hover {
                                background-color: #970000;
                            }
                        }
                        &:first-child {
                            margin: 0;
                        }
                        .action-icon {
                            
                        }
                        .action-text {
                            padding: 0 0 0 5px;
                        }
                    }
                }
            }
        }
        #group-member-overview__no-records {
            padding: 25px;
            box-sizing: border-box;
        }
    }
</style>