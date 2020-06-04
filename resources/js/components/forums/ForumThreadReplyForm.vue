<template>
    <div id="forum-thread-reply-form" class="elevation-1">

        <div class="form-field">
            <v-textarea
                name="message"
                :label="strings.message"
                v-model="form.message"
                hide-details>
            </v-textarea>
        </div>

        <div class="form-controls">
            <div class="form-controls__right">
                <v-btn color="primary" type="submit" :disabled="submitDisabled">
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
            "thread",
            "strings",
        ],
        data: () => ({
            tag: "[forum-thread-reply-form]",
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
                console.log(this.tag+" thread: ", this.threads);
                console.log(this.tag+" strings: ", this.strings);
                this.initializeData();
            },
            initializeData() {

            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #forum-thread-reply-form {
        background-color: #fff;
        padding: 25px;
        box-sizing: border-box;
    }
</style>