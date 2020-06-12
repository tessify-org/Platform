<template>
    <div id="change-password-form__wrapper">

        <!-- Form -->
        <div id="change-password-form" class="elevation-1">

            <!-- Current password -->
            <div class="form-field">
                <v-text-field
                    type="password"
                    name="password"
                    :label="strings.current_password"
                    v-model="form.password"
                    :errors="hasErrors('password')"
                    :error-messages="getErrors('password')">
                </v-text-field>
            </div>

            <!-- New password -->
            <div class="form-field">
                <v-text-field
                    type="password"
                    name="new_password"
                    :label="strings.new_password"
                    v-model="form.new_password"
                    :errors="hasErrors('new_password')"
                    :error-messages="getErrors('new_password')">
                </v-text-field>
            </div>

            <!-- Confirm new password -->
            <div class="form-field">
                <v-text-field
                    type="password"
                    name="new_password_confirmation"
                    v-model="form.confirm_new_password"
                    :label="strings.confirm_new_password"
                    :errors="hasErrors('new_password_confirmation')"
                    :error-messages="getErrors('new_password_confirmation')">
                </v-text-field>
            </div>

        </div>

        <!-- Controls -->
        <div class="form-controls">
            <div class="form-controls__left">

                <!-- Back button -->
                <v-btn color="white" :href="backHref">
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.cancel }}
                </v-btn>

            </div>
            <div class="form-controls__right">

                <!-- Submit button -->
                <v-btn color="success" type="submit">
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
            "errors",
            "strings",
            "backHref",
        ],
        data: () => ({
            tag: "[change-password-form]",
            form: {
                current_password: "",
                new_password: "",
                confirm_new_password: "",
            },
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" back href: ", this.backHref);
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
        },
    }
</script>

<style lang="scss">
    #change-password-form {
        padding: 30px;
        border-radius: 3px;
        box-sizing: border-box;
        background-color: #ffffff;
    }
</style>