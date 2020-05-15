<template>
    <div id="newsletter-signup-form">

        <!-- Error -->
        <div id="signup-form__error" v-if="hasError">{{ error }}</div>

        <!-- Signup form -->
        <div id="signup-form" v-if="!finished">
            <div id="signup-form__input">
                <input type="text" id="signup-form__input-field" v-model="email">
            </div>
            <div id="signup-form__submit" v-if="!loading">
                <button @click="onClickSubmit" :disabled="email === ''">
                    {{ submitText }}
                </button>
            </div>
            <div id="signup-form__loading" v-if="loading">
                <span>
                    <i class="fas fa-spinner fa-spin"></i>
                </span>
            </div>
        </div>

        <!-- Signed up message -->
        <div id="signed-up" v-if="finished">{{ signedUpText }}</div>

    </div>
</template>

<script>
    export default {
        props: [
            "submitText",
            "signedUpText",
            "apiEndpoint",
        ],
        data: () => ({
            tag: "[newsletter-signup-form]",
            email: "",
            error: "",
            loading: false,
            finished: false,
        }),
        computed: {
            hasError() {
                return this.error !== "";
            },
        },
        methods: {
            initialize() {
                // console.log(this.tag+" initializing");
                // console.log(this.tag+" submit text: ", this.submitText);
                // console.log(this.tag+" signed up text: ", this.signedUpText);
                // console.log(this.tag+" api endpoint: ", this.apiEndpoint);
            },
            onClickSubmit() {
                console.log(this.tag+" clicked submit button");
                this.loading = true;
                let payload = new FormData();
                payload.append("email", this.email);
                this.axios.post(this.apiEndpoint, payload)
                    .then(function(response) {
                        this.error = "";
                        this.finished = true;
                        this.loading = false;
                    }.bind(this))
                    .catch(function(error) {
                        this.error = error.response.data.errors.email[0];
                        this.loading = false;
                    }.bind(this));
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #newsletter-signup-form {
        margin: 15px 0 0 0;
        #signup-form__error {
            margin: 0 0 10px 0;
            color: #d40000;
        }
        #signup-form {
            display: flex;
            flex-direction: row;
            align-items: center;
            #signup-form__input {
                flex: 1;
                height: 40px;
                padding: 0 10px;
                line-height: 40px;
                margin: 0 10px 0 0;
                box-sizing: border-box;
                background-color: hsl(0, 0%, 98%);
            }
            #signup-form__submit {
                height: 40px;
                padding: 0 10px;
                line-height: 40px;
                box-sizing: border-box;
            }
            #signup-form__loading {
                height: 40px;
                line-height: 40px;
            }
        }
        #signed-up {
            font-weight: 500;
        }
    }
</style>