<template>
    <div id="report-task-progress-form">

        <!-- Form -->
        <div id="progress-form" class="elevation-1">

            <!-- Message -->
            <div class="form-field">
                <v-textarea
                    name="message"
                    :label="strings.message"
                    v-model="form.message"
                    :placeholder="strings.message_placeholder"
                    :errors="hasErrors('message')"
                    :error-messages="getErrors('message')">
                </v-textarea>
            </div>

            <!-- Attachments -->
            <div class="file-field" :class="{ 'has-errors': hasErrors('attachment') }">
                <div class="file-field__label">{{ strings.attachment }}</div>
                <div class="file-field__input">
                    <input type="file" name="attachment">
                </div>
                <div class="file-field__errors" v-if="hasErrors('attachment')">
                    <div class="file-field__error" v-for="(error, ei) in getErrors('attachment')" :key="ei">
                        {{ error }}
                    </div>
                </div>
            </div>

            <!-- Hours & Completed -->
            <div class="form-fields mb-0">
                <!-- Hours -->
                <div class="form-field">
                    <v-text-field
                        name="hours"
                        type="number"
                        :label="strings.hours"
                        v-model="form.hours"
                        :errors="hasErrors('hours')"
                        :error-messages="getErrors('hours')">
                    </v-text-field>
                </div>
                <!-- Flag as completed -->
                <div class="form-field">
                    <v-checkbox
                        :label="strings.completed"
                        v-model="form.completed"
                        :errors="hasErrors('completed')"
                        :error-messages="getErrors('completed')">
                    </v-checkbox>
                    <input type="hidden" name="completed" :value="form.completed">
                </div>
            </div>

        </div>

        <!-- Controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn :href="backHref" outlined>
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.back }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn type="submit" color="success">
                    <i class="fas fa-save"></i>
                    {{ strings.submit }}
                </v-btn>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "report",
            "errors",
            "oldInput",
            "backHref",
            "strings",
        ],
        data: () => ({
            tag: "[report-task-progress-form]",
            form: {
                message: "",
                hours: 1,
                completed: false,
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" report: ", this.report);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" strings: ", this.strings);
                // console.log(this.tag+" ");
                this.initializeData();
            },
            initializeData() {
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.message !== null) this.form.message = this.oldInput.message;
                    if (this.oldInput.hours !== null) this.form.hours = parseInt(this.oldInput.hours);
                    if (this.oldInput.completed !== null) this.form.completed = this.oldInput.completed;
                }
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
    #report-task-progress-form {
        #progress-form {
            padding: 25px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #ffffff;
        }
    }
</style>