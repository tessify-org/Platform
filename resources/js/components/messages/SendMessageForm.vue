<template>
    <div id="send-message-form__wrapper">

        <!-- Form -->
        <div id="send-message-form">

            <!-- Receiving user -->
            <div class="form-field">
                <v-combobox
                    :label="userText+'*'"
                    :items="userOptions"
                    v-model="form.user"
                    :errors="hasErrors('user')"
                    :error-messages="getErrors('user')">
                </v-combobox>
                <input type="hidden" name="user" :value="form.user">
            </div>

            <!-- Subject -->
            <div class="form-field">
                <v-text-field  
                    name="subject"
                    :label="subjectText"
                    v-model="form.subject"
                    :errors="hasErrors('subject')"
                    :error-messages="getErrors('subject')">
                </v-text-field>
            </div>
            
            <!-- Message -->
            <div class="form-field">
                <v-textarea
                    name="message"
                    :label="messageText+'*'"
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
                <v-btn type="submit" color="success" depressed>
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
            "users",
            "replyTo",
            "userText",
            "subjectText",
            "messageText",
            "backHref",
            "backText",
            "submitText",
        ],
        data: () => ({
            tag: "[send-message-form]",
            userOptions: [],
            form: {
                user: "",
                subject: "",
                message: "",
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" reply to: ", this.replyTo);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" user text: ", this.userText);
                console.log(this.tag+" subject text: ", this.subjectText);
                console.log(this.tag+" message text: ", this.messageText);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" back text: ", this.backText);
                console.log(this.tag+" submit text: ", this.submitText);
                this.generateUserOptions();
                this.initializeData();
            },
            initializeData() {
                if (this.replyTo !== undefined && this.replyTo !== null) {
                    if (this.replyTo.sender !== undefined && this.replyTo.sender !== null) {
                        this.form.user = this.replyTo.sender.formatted_name;
                    }
                    this.form.subject = "RE: "+this.replyTo.subject;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.user !== null) this.form.user = this.oldInput.user;
                    if (this.oldInput.subject !== null) this.form.subject = this.oldInput.subject;
                    if (this.oldInput.message !== null) this.form.message = this.oldInput.message;
                }
            },
            generateUserOptions() {
                if (this.users !== undefined && this.users !== null && this.users.length > 0) {
                    for (let i = 0; i < this.users.length; i++) {
                        this.userOptions.push(this.users[i].formatted_name);
                    }
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
    #send-message-form__wrapper {
        #send-message-form {

        }
    }
</style>