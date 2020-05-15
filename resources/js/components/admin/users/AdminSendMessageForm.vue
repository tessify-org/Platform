<template>
    <div id="admin-send-message-form">

        <!-- User -->
        <div class="form-field">
            <v-text-field
                disabled
                :label="strings.user"
                v-model="user.formatted_name">
            </v-text-field>
        </div>

        <!-- Subject -->
        <div class="form-field">
            <v-text-field
                name="subject"
                v-model="form.subject"
                :label="strings.subject"
                :errors="getErrors('subject')"
                :error-messages="getErrors('subject')">
            </v-text-field>
        </div>

        <!-- Message -->
        <div class="form-field">
            <v-textarea
                name="message"
                v-model="form.message"
                :label="strings.message"
                :errors="hasErrors('message')"
                :error-messages="getErrors('message')">
            </v-textarea>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "user",
            "strings",
            "errors",
            "oldInput",
        ],
        data: () => ({
            tag: "[admin-send-message-form]",
            form: {
                subject: "",
                message: "",
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" user: ", this.user);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                this.initializeData();
            },
            initializeData() {
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.subject !== null) this.form.subject = this.oldInput.subject;
                    if (this.oldInput.message !== null) this.form.message = this.oldInput.message;
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
    #admin-send-message-form {

    }
</style>