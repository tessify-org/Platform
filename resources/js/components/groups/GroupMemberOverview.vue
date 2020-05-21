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
                <div class="group-member__role">{{ member.role.name }}</div>
                <div class="group-member__actions" v-if="canManage && member.role.name === 'Founder'">
                    <div class="action kick" @click="onClickKick(mi)">
                        {{ strings.kick }}
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
            <div class="dialog" v-if="dialogs.kick.index !== null">
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

    </div>
</template>

<script>
    export default {
        props: [
            "members",
            "strings",
            "apiEndpoints",
            "canManage",
        ],
        data: () => ({
            tag: "[group-member-overview]",
            mutableMembers: [],
            dialogs: {
                kick: {
                    show: false,
                    loading: false,
                    index: null,
                    errors: [],
                },
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" members: ", this.members);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
                console.log(this.tag+" can manage: ", this.canManage);
                this.initializeData();
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
                this.dialogs.kick.loading = true;
                let payload = new FormData();
                payload.append("team_member_id", this.mutableMembers[this.dialogs.kick.index].id);
                this.axios.post(this.apiEndpoints.kick, payload)
                    .then(function(response) {
                        this.dialogs.kick.loading = false;
                        if (response.data.status === "success") {
                            this.mutableMembers.splice(this.dialogs.kick.index, 1);
                            this.dialogs.kick.loading = false;
                            this.dialogs.kick.show = false;
                        } else {
                            this.dialogs.kick.loading = false;
                            this.dialogs.kick.errors = [response.data.error];
                        }
                    }.bind(this))
                    .catch(function(response) {
                        this.dialogs.kick.loading = false;
                        this.dialogs.kick.errors = [response.data.error];
                    }.bind(this));
            },
            getKickDialogText() {
                return this.strings.kick_dialog_text.replace(':name', this.mutableMembers[this.dialogs.kick.index].user.formatted_name);
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
                        .action-icon {}
                        .action-text {
                            padding: 0 0 0 5px;
                        }
                    }
                }
            }
        }
        #group-member-overview__no-records {
            padding: 25px;
            text-align: center;
            box-sizing: border-box;
        }
    }
</style>