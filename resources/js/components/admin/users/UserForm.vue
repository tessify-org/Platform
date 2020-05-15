<template>
    <div id="user-form">

        <!-- Is admin -->
        <div class="form-field">
            <v-select
                :label="isAdminText"
                v-model="form.is_admin"
                :items="booleanOptions"
                :errors="hasErrors('is_admin')"
                :error-messages="getErrors('is_admin')">
            </v-select>
            <input type="hidden" name="is_admin" :value="form.is_admin">
        </div>  

        <!-- Name fields -->
        <div class="form-fields">
            <div class="form-field">
                <v-text-field
                    name="first_name"
                    :label="firstNameText"
                    v-model="form.first_name"
                    :errors="hasErrors('first_name')"
                    :error-messages="getErrors('first_name')">
                </v-text-field>
            </div>
            <div class="form-field">
                <v-text-field
                    name="last_name"
                    :label="lastNameText"
                    v-model="form.last_name"
                    :errors="hasErrors('last_name')"
                    :error-messages="getErrors('last_name')">
                </v-text-field>
            </div>
        </div>

        <!-- Email -->
        <div class="form-field">
            <v-text-field
                name="email"
                :label="emailText"
                v-model="form.email"
                :errors="hasErrors('email')"
                :error-messages="getErrors('email')">
            </v-text-field>
        </div>

        <!-- Password fields -->
        <div class="form-fields">
            <div class="form-field">
                <v-text-field
                    type="password"
                    name="password"
                    :label="passwordText"
                    v-model="form.password"
                    :errors="hasErrors('password')"
                    :error-messages="getErrors('password')">
                </v-text-field>
            </div>
            <div class="form-field">
                <v-text-field
                    type="password"
                    name="password_confirmation"
                    :label="confirmPasswordText"
                    v-model="form.password_confirmation"
                    :errors="hasErrors('password_confirmation')"
                    :error-messages="getErrors('password_confirmation')">
                </v-text-field>
            </div>
        </div>

        <!-- Headline -->
        <div class="form-field">
            <v-text-field
                name="headline"
                :label="headlineText"
                v-model="form.headline"
                :errors="hasErrors('headline')"
                :error-messages="getErrors('headline')">
            </v-text-field>
        </div>

        <!-- Interests -->
        <div class="form-field">
            <v-textarea
                name="interests"
                :label="interestsText"
                v-model="form.interests"
                :errors="hasErrors('interests')"
                :error-messages="getErrors('interests')">
            </v-textarea>
        </div>

        <!-- Assignments -->

    </div>
</template>

<script>
    export default {
        props: [
            "user",
            "errors",
            "oldInput",
            "isAdminText",
            "firstNameText",
            "lastNameText",
            "emailText",
            "passwordText",
            "confirmPasswordText",
            "headlineText",
            "interestsText",
        ],
        data: () => ({
            tag: "[user-form]",
            booleanOptions: [],
            annotationOptions: [],
            form: {
                is_admin: false,
                first_name: "",
                last_name: "",
                email: "",
                password: "",
                password_confirmation: "",
                headline: "",
                interests: "",
            },
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" user: ", this.user);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" is admin text: ", this.isAdminText);
                console.log(this.tag+" first name text: ", this.firstNameText);
                console.log(this.tag+" last name text: ", this.lastNameText);
                console.log(this.tag+" email text: ", this.emailText);
                console.log(this.tag+" password text: ", this.passwordText);
                console.log(this.tag+" confirm password text: ", this.confirmPasswordText);
                console.log(this.tag+" headline text: ", this.headlineText);
                console.log(this.tag+" interests text: ", this.interestsText);
                this.initializeData();
                this.generateBooleanOptions();
            },
            initializeData() {
                if (this.user !== undefined && this.user !== null) {
                    this.form.annotation = this.user.annotation;
                    this.form.first_name = this.user.first_name;
                    this.form.last_name = this.user.last_name;
                    this.form.email = this.user.email;
                    this.form.headline = this.user.headline;
                    this.form.interests = this.user.interests;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.annotation !== null) this.form.annotation = this.oldInput.annotation;
                    if (this.oldInput.first_name !== null) this.form.first_name = this.oldInput.first_name;
                    if (this.oldInput.last_name !== null) this.form.last_name = this.oldInput.last_name;
                    if (this.oldInput.email !== null) this.form.email = this.oldInput.email;
                    if (this.oldInput.headline !== null) this.form.headline = this.oldInput.headline;
                    if (this.oldInput.interests !== null) this.form.interests = this.oldInput.interests;
                }
            },
            generateBooleanOptions() {
                this.booleanOptions.push({ text: "Yes", value: true });
                this.booleanOptions.push({ text: "No", value: false });
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
    #user-form {

    }
</style>