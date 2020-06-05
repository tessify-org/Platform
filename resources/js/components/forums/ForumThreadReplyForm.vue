<template>
    <div id="forum-thread-reply-form" :class="{ 'elevation-1 padded': doesNotHaveGroup }">

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
            "group",
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
            },
            hasGroup() {
                return this.group !== undefined;
            },
            doesNotHaveGroup() {
                return this.group === undefined;
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" group: ", this.group);
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
        box-sizing: border-box;
        background-color: #ffffff;
        &.padded {
            padding: 25px;
        }
    }
</style>