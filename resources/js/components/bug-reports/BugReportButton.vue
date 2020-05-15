<template>
    <div id="bug-report-button__inner">

        <!-- Button -->
        <div id="bug-report-button">
            <v-btn dark fab color="red" @click="onClickButton">
                <i class="fas fa-bug"></i>
            </v-btn>
        </div>
    
        <!-- Dialog -->
        <v-dialog v-model="dialogs.view.show" width="700">
            <div class="dialog" v-if="dialogs.view.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Open form -->
                <form :action="formAction" method="post">
                    <input type="hidden" name="_token" :value="csrfToken">
                    <!-- Content -->
                    <div class="dialog-content">
                        <!-- Title -->
                        <h3 class="dialog-title">{{ dialogTitleText }}</h3>
                        <!-- Form -->
                        <div class="form-field">
                            <v-text-field
                                name="url"
                                :label="urlLabelText"
                                v-model="dialogs.view.form.url"
                                :errors="hasErrors('url')"
                                :error-messages="getErrors('url')">
                            </v-text-field>
                        </div>
                        <div class="form-field">
                            <v-select
                                :label="severityLabelText"
                                v-model="dialogs.view.form.severity"
                                :items="severityOptions"
                                :errors="hasErrors('severity')"
                                :error-messages="getErrors('severity')">
                            </v-select>
                            <input type="hidden" name="severity" :value="dialogs.view.form.severity">
                        </div>
                        <div class="form-field">
                            <v-textarea
                                name="report"
                                :label="reportLabelText"
                                v-model="dialogs.view.form.report"
                                :errors="hasErrors('report')"
                                :error-messages="getErrors('report')">
                            </v-textarea>
                        </div>
                    </div>
                    <!-- Actions -->
                    <div class="dialog-controls">
                        <div class="dialog-controls__left">
                            <v-btn @click="dialogs.view.show = false" text>
                                {{ cancelText }}
                            </v-btn>
                        </div>
                        <div class="dialog-controls__right">
                            <v-btn type="submit" color="primary" text class="icon-right">
                                {{ submitText }}
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
            "dialogTitleText",
            "formAction",
            "csrfToken",
            "urlLabelText",
            "reportLabelText",
            "severityLabelText",
            "lowSeverityText",
            "mediumSeverityText",
            "highSeverityText",
            "cancelText",
            "submitText",
            "errors",
        ],
        data: () => ({
            tag: "[bug-report-button]",
            severityOptions: [],
            dialogs: {
                view: {
                    show: false,
                    form: {
                        url: "",
                        severity: 1,
                        report: "",
                    }
                }
            }
        }),
        methods: {
            initialize() {
                // console.log(this.tag+" initializing");
                // console.log(this.tag+" url: ", this.url);
                // console.log(this.tag+" dialog title text: ", this.dialogTitleText);
                // console.log(this.tag+" form action: ", this.formAction);
                // console.log(this.tag+" csrf token: ", this.csrfToken);
                // console.log(this.tag+" url label text: ", this.urlLabelText);
                // console.log(this.tag+" report label text: ", this.reportLabelText);
                // console.log(this.tag+" severity label text: ", this.severityLabelText);
                // console.log(this.tag+" low severity text: ", this.lowSeverityText);
                // console.log(this.tag+" medium severity text: ", this.mediumSeverityText);
                // console.log(this.tag+" high severity text: ", this.highSeverityText);
                // console.log(this.tag+" cancel text: ", this.cancelText);
                // console.log(this.tag+" submit text: ", this.submitText);
                // console.log(this.tag+" errors: ", this.errors);
                this.generateSeverityOptions();
                this.initializeData();
            },
            generateSeverityOptions() {
                this.severityOptions.push({ text: this.lowSeverityText, value: 1 });
                this.severityOptions.push({ text: this.mediumSeverityText, value: 2 });
                this.severityOptions.push({ text: this.highSeverityText, value: 3 });
            },
            initializeData() {
                if (this.url !== undefined && this.url !== null && this.url !== "") this.dialogs.view.form.url = this.url;
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
    #bug-report-button__inner {
        width: 100%;
        height: 100%;
        position: relative;
        #bug-report-button {
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
</style>