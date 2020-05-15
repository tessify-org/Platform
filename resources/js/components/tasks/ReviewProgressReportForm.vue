<template>
    <div id="review-progress-report-form">

        <!-- Review form -->
        <div id="review-form" class="elevation-1">

            <!-- Message -->
            <div class="form-field mb-0">
                <v-textarea
                    name="message"
                    :label="messageText"
                    v-model="form.message"
                    :errors="hasErrors('message')"
                    :error-messages="getErrors('message')">
                </v-textarea>
            </div>

        </div>

        <!-- Controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn :href="backHref" outlined>
                    <i class="fas fa-arrow-left"></i>
                    {{ backText }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn type="submit" color="success" :disabled="submitDisabled">
                    <i class="fas fa-save"></i>
                    {{ submitText }}
                </v-btn>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "errors",
            "oldInput",
            "messageText",
            "backHref",
            "backText",
            "submitText",
        ],
        data: () => ({
            tag: "[review-progress-report-form]",
            form: {
                message: "",
            }
        }),
        computed: {
            submitDisabled() {
                return this.form.message === "";
            }
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" message text: ", this.messageText);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" back text: ", this.backText);
                console.log(this.tag+" submit text: ", this.submitText);
                this.initializeData();
            },
            initializeData() {
                if (this.oldInput !== undefined && this.oldInput !== null) {

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
    #review-progress-report-form {
        #review-form {
            padding: 25px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #ffffff;
        }
    }
</style>