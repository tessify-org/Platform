<template>
    <div id="login-form">
        
        <!-- Email --> 
        <div class="form-field">
            <v-text-field 
                name="email" 
                v-model="form.email" 
                :label="strings.email" 
                :error="hasErrors('email')" 
                :error-messages="getErrors('email')">
            </v-text-field>
        </div>
        
        <!-- Password -->
        <div class="form-field">
            <v-text-field 
                type="password" 
                name="password" 
                :label="strings.password"
                v-model="form.password" 
                :error="hasErrors('password')" 
                :error-messages="getErrors('password')">
            </v-text-field>
        </div>
        
        <!-- Remember me -->
        <div class="form-field">
            <remember-me 
                name="remember_me" 
                v-model="form.rememberMe" 
                :remember-me-text="strings.remember_me">
            </remember-me>
        </div>
        
        <!-- Controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <div class="login-link">
                    <a :href="forgotPasswordHref">
                        {{ strings.forgot_password }}
                    </a>
                </div>
                <div class="login-link">
                    <a :href="registerHref">
                        {{ strings.register }}
                    </a>
                </div>
            </div>
            <div class="form-controls__right">
                <v-btn type="submit" color="primary">
                    <i class="fas fa-sign-in-alt"></i>
                    {{ strings.submit }}
                </v-btn>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "oldInput",
            "errors",
            "prefillEmail",
            "strings",
            "registerHref",
            "forgotPasswordHref",
        ],
        data: () => ({
            tag: "[login-form]",
            form: {
                email: "",
                password: "",
                rememberMe: true,
            },
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" prefill email: ", this.prefillEmail);
                this.initializeData();
            },
            initializeData() {
                if (this.prefillEmail !== undefined && this.prefillEmail !== null && this.prefillEmail !== "") {
                    this.form.email = this.prefillEmail;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.email !== null) this.form.email = this.oldInput.email;
                    if (this.oldInput.rememberMe !== null) this.form.rememberMe = this.oldInput.rememberMe ===  "true" ? true : false;
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
        },
    }
</script>

<style lang="scss">
    #login-form {
        .form-controls {
            .form-controls__left {
                display: flex;
                flex-direction: column;
                .login-link {
                    width: 100%;
                }
            }
            .form-controls__right {
                flex: 0;
            }
        }
    }
</style>
