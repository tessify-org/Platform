<template>
    <div id="project-ask-question-button__wrapper">

        <!-- Button -->
        <a href="#" id="project-ask-question-button__link" @click="onClickButton">{{ strings.button }}</a>

        <!-- Question dialog -->
        <v-dialog v-model="dialogs.question.show" width="500">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.question.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.dialog_title }}</h3>
                    <!-- Text -->
                    <div class="dialog-text">{{ strings.dialog_text }}</div>
                    <!-- Question -->
                    <div class="form-field">
                        <v-textarea
                            :label="strings.question"
                            v-model="dialogs.question.form.question"
                            :errors="hasErrors('question')"
                            :error-messages="getErrors('question')">
                        </v-textarea>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.question.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.dialog_cancel }}
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn depressed color="success" @click="onClickConfirm" :dark="!confirmDisabled" :disabled="confirmDisabled" :loading="dialogs.question.loading">
                            <i class="fas fa-paper-plane"></i>
                            {{ strings.dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Success dialog -->
        <v-dialog v-model="dialogs.success.show" width="500">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.success.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.success_dialog_title }}</h3>
                    <!-- Text -->
                    <div class="dialog-text">{{ strings.success_dialog_text }}</div>
                </div>
            </div>
        </v-dialog>

    </div>
</template>

<script>
    export default {
        props: [
            "project",
            "endpoint",
            "strings",
        ],
        data: () => ({
            tag: "[project-ask-question-button]",
            dialogs: {
                question: {
                    show: false,
                    loading: false,
                    errors: [],
                    form: {
                        question: "",
                    },
                },
                success: {
                    show: false,
                },
            }
        }),
        computed: {
            confirmDisabled() {
                return this.dialogs.question.form.question === "";
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" endpoint: ", this.endpoint);
                console.log(this.tag+" strings: ", this.strings);
            },
            onClickButton() {
                this.dialogs.question.show = true;
            },
            onClickConfirm() {
                this.dialogs.question.loading = true;
                let payload = new FormData();
                payload.append("question", this.dialogs.question.form.question);
                this.axios.post(this.endpoint, payload)
                    .then(function(response) {
                        console.log(this.tag+" request succeeded", response.data);
                        this.dialogs.question.loading = false;
                        this.dialogs.question.show = false;
                        this.dialogs.success.show = true;
                    }.bind(this))
                    .catch(function(error) {
                        console.log(this.tag+" request failed", error.data);
                        this.dialogs.question.loading = false;
                    }.bind(this));
            },
            hasErrors(field) {
                if (this.dialogs.question.errors !== undefined && 
                    this.dialogs.question.errors !== null && 
                    this.dialogs.question.errors.length > 0) {
                    if (this.dialogs.question.errors[field] !== undefined && 
                        this.dialogs.question.errors[field] !== "") {
                        return true;
                    }
                }
                return false;
            },
            getErrors(field) {
                if (this.dialogs.question.errors !== undefined && 
                    this.dialogs.question.errors !== null && 
                    this.dialogs.question.errors[field] !== undefined && 
                    this.dialogs.question.errors[field] !== null) {
                    return this.dialogs.question.errors[field];
                }
                return [];
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #project-ask-question-button {

    }
</style>