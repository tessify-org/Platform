<template>
    <div id="project-invite-button__wrapper">

        <!-- Invite button -->
        <div id="project-invite-button" :class="{ 'border-bottom': hasBorderBottom, 'border-radius-bottom': isStandalone }" @click="onClickButton" v-ripple>
            <div id="project-invite-button__icon">
                <i class="fas fa-user-plus"></i>
            </div>
            <div id="project-invite-button__text">
                {{ strings.button }}
            </div>
        </div>
        
        <!-- Dialog -->
        <v-dialog v-model="dialog.show" width="600">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialog.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.dialog_title }}</h3>
                    <!-- Text -->
                    <div class="dialog-text">{{ strings.dialog_text }}</div>
                    <!-- User -->
                    <div class="form-field">
                        <v-combobox
                            :label="strings.form_user"
                            :items="userOptions"
                            v-model="dialog.form.user_slug">
                        </v-combobox>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialog.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.dialog_cancel }}
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn depressed color="success" @click="onClickConfirm" :dark="!confirmDisabled" :disabled="confirmDisabled" :loading="dialog.loading">
                            <i class="fas fa-paper-plane"></i>
                            {{ strings.dialog_submit }}
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
            "users",
            "endpoint",
            "strings",
            "standalone",
            "borderBottom",
        ],
        data: () => ({
            tag: "[project-invite-button]",
            userOptions: [],
            dialog: {
                show: false,
                loading: false,
                form: {
                    user_slug: "",
                }
            },
        }),
        computed: {
            confirmDisabled() {
                return this.dialog.form.user_slug === "";
            },
            isStandalone() {
                return this.standalone !== undefined;
            },
            hasBorderBottom() {
                return this.borderBottom !== undefined;
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" users: ", this.users);
                console.log(this.tag+" endpoint: ", this.endpoint);
                console.log(this.tag+" strings: ", this.strings);
                this.generateUserOptions();
            },
            generateUserOptions() {
                if (this.users !== undefined && this.users !== null && this.users.length > 0) {
                    for (let i = 0; i < this.users.length; i++) {
                        this.userOptions.push({
                            text: this.users[i].formatted_name,
                            value: this.users[i].slug,
                        });
                    }
                }
            },
            onClickButton() {
                this.dialog.show = true;
            },
            onClickConfirm() {
                this.dialog.loading = true;
                let slug = this.dialog.form.user_slug.value;
                let url = this.endpoint + "/" + slug;
                window.location.href = url;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #project-invite-button__wrapper {
        #project-invite-button {
            display: flex;
            color: #000000;
            padding: 10px 15px;
            transition: all .3s;
            align-items: center;
            flex-direction: row;
            text-decoration: none;
            box-sizing: border-box;
            background-color: #fff;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
            &.border-bottom {
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            }
            &.border-radius-bottom {
                border-bottom-left-radius: 3px;
                border-bottom-right-radius: 3px;
            }
            &:hover {
                color: #0a64eb;
                cursor: pointer;
            }
            #project-invite-button__icon {
                flex: 0 0 30px;
            }
            #project-invite-button__text {
                flex: 1;
            }
        }
    }
</style>