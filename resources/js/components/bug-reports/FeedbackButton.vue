<template>
    <div id="feedback-button__inner">

        <!-- Button -->
        <div id="feedback-button">
            <v-btn dark fab color="red" @click="onClickButton">
                <i class="far fa-comment-dots"></i>
            </v-btn>
        </div>
    
        <!-- Dialog -->
        <v-dialog v-model="dialogs.view.show" width="700">
            <div class="dialog" v-if="dialogs.view.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Form -->
                <form :action="formAction" method="post">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <!-- Content -->
                    <div class="dialog-content">
                        <!-- Title -->
                        <h3 class="dialog-title no-margin">{{ strings.title }}</h3>
                    </div>
                    <!-- Tabs -->
                    <v-tabs fixed-tabs background-color="#f5f5f5" v-model="dialogs.view.tab">
                        <v-tab>{{ strings.general }}</v-tab>
                        <v-tab>{{ strings.gebruikerspanel }}</v-tab>
                        <v-tab-item>
                            <!-- General formulier -->
                            <div class="tab-content">
                                <!-- Hidden -->
                                <input type="hidden" name="target" value="general">
                                <input type="hidden" name="page_url" :value="dialogs.view.form.page_url">
                                <!-- Type of feedback -->
                                <div class="form-field">
                                    <v-select
                                        :label="strings.type"
                                        :items="typeOptions"
                                        v-model="dialogs.view.form.type"
                                        :errors="hasErrors('type')"
                                        :error-messages="getErrors('type')">
                                    </v-select>
                                    <input type="hidden" name="type" :value="dialogs.view.form.type">
                                </div>
                                <!-- Subject -->
                                <div class="form-field" v-if="dialogs.view.form.type === 'feedback'">
                                    <v-text-field 
                                        name="subject"
                                        :label="strings.subject"
                                        v-model="dialogs.view.form.subject"
                                        :errors="hasErrors('subject')"
                                        :error-messages="getErrors('subject')">
                                    </v-text-field>
                                </div>
                                <!-- Bug severity -->
                                <div class="form-field" v-if="dialogs.view.form.type === 'bug_report'">
                                    <v-select
                                        :label="strings.severity"
                                        v-model="dialogs.view.form.severity"
                                        :items="severityOptions"
                                        :errors="hasErrors('severity')"
                                        :error-messages="getErrors('severity')">
                                    </v-select>
                                    <input type="hidden" name="severity" :value="dialogs.view.form.severity">
                                </div>
                                <!-- Description -->
                                <div class="form-field">
                                    <v-textarea
                                        name="description"
                                        :label="strings.description"
                                        v-model="dialogs.view.form.description"
                                        :errors="hasErrors('description')"
                                        :error-messages="getErrors('description')">
                                    </v-textarea>
                                </div>
                            </div>
                        </v-tab-item>
                        <v-tab-item>
                            <!-- Gebruikerspanel formulier -->
                            <div class="tab-content">
                                <!-- Hidden -->
                                <input type="hidden" name="target" value="gebruikerspanel">
                                <input type="hidden" name="page_url" :value="dialogs.view.form.page_url">
                                <!-- Subject -->
                                <div class="form-field">
                                    <v-text-field 
                                        name="subject"
                                        :label="strings.subject"
                                        v-model="dialogs.view.form.subject"
                                        :errors="hasErrors('subject')"
                                        :error-messages="getErrors('subject')">
                                    </v-text-field>
                                </div>
                                <!-- Description -->
                                <div class="form-field">
                                    <v-textarea
                                        name="description"
                                        :label="strings.description"
                                        v-model="dialogs.view.form.description"
                                        :errors="hasErrors('description')"
                                        :error-messages="getErrors('description')">
                                    </v-textarea>
                                </div>
                            </div>
                        </v-tab-item>
                    </v-tabs>
                    <!-- Actions -->
                    <div class="dialog-controls">
                        <div class="dialog-controls__left">
                            <v-btn @click="dialogs.view.show = false" text>
                                {{ strings.cancel }}
                            </v-btn>
                        </div>
                        <div class="dialog-controls__right">
                            <v-btn type="submit" color="primary" class="icon-right" :disabled="submitDisabled">
                                {{ strings.submit }}
                                <i class="fas fa-paper-plane"></i>
                            </v-btn>
                        </div>
                    </div>
                </form>
            </div>
        </v-dialog>

    </div>
</template>

<script>
    export default {
        props: [
            "url",
            "formAction",
            "csrfToken",
            "errors",
            "strings",
        ],
        data: () => ({
            tag: "[feedback-button]",
            severityOptions: [],
            typeOptions: [],
            dialogs: {
                view: {
                    show: false,
                    tab: 0,
                    form: {
                        type: "feedback",
                        subject: "",
                        page_url: "",
                        severity: 1,
                        description: "",
                    }
                }
            }
        }),
        computed: {
            submitDisabled() {
                if (this.dialogs.view.tab === 0) {
                    if (this.dialogs.view.form.type === "feedback") {
                        return this.dialogs.view.form.subject === "" || this.dialogs.view.form.description === "";
                    } else {
                        return this.dialogs.view.form.description === "";
                    }
                } else {
                    return this.dialogs.view.form.subject === "" || this.dialogs.view.form.description === "";
                }
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" url: ", this.url);
                console.log(this.tag+" form action: ", this.formAction);
                console.log(this.tag+" csrf token: ", this.csrfToken);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" strings: ", this.strings);
                this.generateSeverityOptions();
                this.generateTypeOptions();
                this.initializeData();
            },
            generateSeverityOptions() {
                this.severityOptions.push({
                    text: this.strings.severity_low, 
                    value: 1
                });
                this.severityOptions.push({
                    text: this.strings.severity_medium, 
                    value: 2
                });
                this.severityOptions.push({
                    text: this.strings.severity_high, 
                    value: 3
                });
            },
            generateTypeOptions() {
                this.typeOptions.push({
                    text: this.strings.type_feedback,
                    value: 'feedback'
                });
                this.typeOptions.push({
                    text: this.strings.type_bug,
                    value: 'bug_report'
                });
            },
            initializeData() {
                if (this.url !== undefined && this.url !== null && this.url !== "") this.dialogs.view.form.page_url = this.url;
            },
            onClickButton() {
                this.dialogs.view.show = true;
            },
            hasErrors(field) {
                if (this.errors !== undefined && this.errors !== null && this.errors.length > 0) {
                    if (this.errors[field] !==  undefined &&  this.errors[field] !== "") {
                        return true;
                    }
                }
                return false;
            },
            getErrors(field) {
                if (this.errors !== undefined && this.errors !== null && this.errors[field] !== undefined && this.errors[field] !== null) {
                    return this.errors[field];
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
    #feedback-button__inner {
        width: 100%;
        height: 100%;
        position: relative;
        #feedback-button {
            right: 50px;
            bottom: 30px;
            position: absolute;
            pointer-events: all;
            .v-btn {
                font-size: 1.3em;
                .svg-inline--fa {
                    margin: 0;
                }
            }
        }
    }
    .tab-content {
        padding: 20px 30px;
        box-sizing: border-box;
    }
</style>