<template>
    <div id="group-application-overview">

        <!-- List -->
        <div id="group-application-overview__list" v-if="mutableApplications.length > 0">
            <div class="group-application" v-for="(application, ai) in mutableApplications" :key="ai" @click="onClickView(ai)">
                <div class="group-application__avatar" :style="{ backgroundImage: 'url('+application.user.avatar_url+')' }"></div>
                <div class="group-application__user">
                    {{ application.user.formatted_name }}
                </div>
                <div class="group-application__date">
                    {{ application.created_at }}
                </div>
            </div>
        </div>

        <!-- No records -->
        <div id="group-application-overview__no-records" v-if="mutableApplications.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- View dialog -->
        <v-dialog v-model="dialogs.view.show" width="650">
            <div class="dialog" v-if="dialogs.view.index !== null && mutableApplications[dialogs.view.index] !== undefined">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.view_dialog_title }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.view.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.view.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Details -->
                    <div class="details mb-0 compact bordered">
                        <!-- User -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_user }}</div>
                            <div class="val">{{ mutableApplications[this.dialogs.view.index].user.formatted_name }}</div>
                        </div>
                        <!-- Motivation -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_motivation }}</div>
                            <div class="val">{{ mutableApplications[this.dialogs.view.index].motivation }}</div>
                        </div>
                        <!-- Date -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_date }}</div>
                            <div class="val">{{ mutableApplications[this.dialogs.view.index].formatted_date }}</div>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.view.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.view_dialog_cancel }}
                        </v-btn>
                    </div>
                    <!-- Accept / Reject -->
                    <div class="dialog-controls__right">
                        <v-btn dark depressed color="red" @click="onClickReject" :loading="dialogs.view.loading_reject" :disabled="disableReject">
                            <i class="fas fa-thumbs-down"></i>
                            {{ strings.view_dialog_reject }}
                        </v-btn>
                        <v-btn dark depressed color="success" @click="onClickAccept" :loading="dialogs.view.loading_accept" :disabled="disableAccept">
                            <i class="fas fa-thumbs-up"></i>
                            {{ strings.view_dialog_accept }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Accepted dialog -->
        <v-dialog v-model="dialogs.accepted.show" width="500">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.accepted.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.accepted_dialog_title }}</h3>
                    <!-- Text -->
                    <div class="dialog-text">{{ strings.accepted_dialog_text }}</div>
                </div>
            </div>
        </v-dialog>

        <!-- Rejected dialog -->
        <v-dialog v-model="dialogs.rejected.show" width="500">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.rejected.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.rejected_dialog_title }}</h3>
                    <!-- Text -->
                    <div class="dialog-text">{{ strings.rejected_dialog_text }}</div>
                </div>
            </div>
        </v-dialog>

    </div>
</template>

<script>
    import { EventBus } from './../../event-bus.js';
    export default {
        props: [
            "applications",
            "strings",
            "apiEndpoints",
        ],
        data: () => ({
            tag: "[group-application-overview]",
            mutableApplications: [],
            dialogs: {
                view: {
                    show: false,
                    loading_accept: false,
                    loading_reject: false,
                    errors: [],
                    index: null,
                },
                accepted: {
                    show: false,
                },
                rejected: {
                    show: false,
                },
            },
        }),
        computed: {
            disableAccept() {
                return this.dialogs.view.loading_reject;
            },
            disableReject() {
                return this.dialogs.view.loading_accept;
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" applications: ", this.applications);
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
            onClickView(index) {
                this.dialogs.view.index = index;
                this.dialogs.view.show = true;
            },
            onClickAccept() {
                // Start loading
                this.dialogs.view.loading_accept = true;
                // Create payload
                let payload = new FormData();
                payload.append("uuid", this.mutableApplications[this.dialogs.view.index].uuid);
                // Make HTTP Request    
                this.axios.post(this.apiEndpoints.accept, payload)
                    // Request succeeded
                    .then(function(response) {
                        if (response.data.status === "success") {
                            this.mutableApplications.splice(this.dialogs.view.index, 1);
                            this.dialogs.view.loading_accept = false;
                            this.dialogs.view.show = false;
                            this.dialogs.accepted.show = true;
                            EventBus.$emit("group-application-counter-down");
                        } else {
                            this.dialogs.view.loading_accept = false;
                            this.dialogs.view.errors = [response.data.error];
                        }
                    }.bind(this))
                    // Request failed
                    .catch(function(response) {
                        this.dialogs.view.loading_accept = false;
                        this.dialogs.view.errors = [response.data.error];
                    }.bind(this));
            },
            onClickReject() {
                // Start loading
                this.dialogs.view.loading_reject = true;
                // Create payload
                let payload = new FormData();
                payload.append("uuid", this.mutableApplications[this.dialogs.view.index].uuid);
                // Make HTTP Request
                this.axios.post(this.apiEndpoints.reject, payload)
                    // Request succeeded
                    .then(function(response) {
                        if (response.data.status === "success") {
                            this.mutableApplications.splice(this.dialogs.view.index, 1);
                            this.dialogs.view.loading_reject = false;
                            this.dialogs.view.show = false;
                            this.dialogs.rejected.show = true;
                            EventBus.$emit("group-application-counter-down");
                        } else {
                            this.dialogs.view.loading_reject = false;
                            this.dialogs.view.errors = [response.data.error];
                        }
                    }.bind(this))
                    // Request failed
                    .catch(function(response) {
                        this.dialogs.view.loading_reject = false;
                        this.dialogs.view.errors = [response.data.error];
                    }.bind(this));
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #group-application-overview {
        #group-application-overview__list {
            .group-application {
                display: flex;
                color: #000;
                flex-direction: row;
                align-items: center;
                transition: all .3s;
                box-sizing: border-box;
                padding: 15px 20px 15px 15px;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    cursor: pointer;
                    background-color: hsl(0, 0%, 95%);
                }
                .group-application__avatar {
                    height: 30px;
                    flex: 0 0 30px;
                    border-radius: 15px;
                    background-size: contain;
                    background-repeat: no-repeat;
                    background-position: center center;
                }
                .group-application__user {
                    flex: 1;
                    padding: 0 0 0 15px;
                    box-sizing: border-box;
                }
                .group-application__date {
                    display: flex;
                    padding: 0 0 0 15px;
                    flex-direction: row;
                }
            }
        }
        #group-application-overview__no-records {
            padding: 25px;
            box-sizing: border-box;
        }
    }
</style>