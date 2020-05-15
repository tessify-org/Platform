<template>
    <div id="admin-change-password-form">

        <div class="form-field">
            <v-text-field
                type="password"
                name="password"
                :label="strings.new_password"
                :errors="hasErrors('password')"
                :error-messages="getErrors('password')">
            </v-text-field>
        </div>

        <div class="form-field">
            <v-text-field
                type="password"
                name="password_confirmation"
                :label="strings.confirm_new_password"
                :errors="hasErrors('password_confirmation')"
                :error-messages="getErrors('password_confirmation')">
            </v-text-field>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "errors",
            "strings",
        ],
        data: () => ({
            tag: "[admin-change-password-form]",
            form: {
                new_password: "",
                confirm_new_password: "",
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" errors: ", this.errors);
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
    #admin-change-password-form {
        
    }
</style>