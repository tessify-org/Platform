<template>
    <div id="forum-post-update-form" class="elevation-1">

        <!-- Message -->
        <div class="form-field">
            <v-textarea
                name="message"
                :label="strings.message"
                v-model="form.message">
            </v-textarea>
        </div>

        <!-- Controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn :href="backHref" text>
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.cancel }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn color="success" depressed type="submit" :disabled="submitDisabled">
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
            "post",
            "strings",
            "oldInput",
            "errors",
            "backHref",
        ],
        data: () => ({
            tag: "[forum-post-update-form]",
            form: {
                message: "",
            }
        }),
        computed: {
            submitDisabled() {
                return this.form.message === "";
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" post: ", this.post);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" back href: ", this.backHref);
                this.initializeData();
            },
            initializeData() {
                if (this.post !== undefined && this.post !== null) {
                    this.form.message = this.post.message;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.message !== null) this.form.message = this.oldInput.message;
                }
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #forum-post-update-form {
        padding: 25px;
        overflow: hidden;
        border-radius: 3px;
        box-sizing: border-box;
        background-color: #ffffff;
    }
</style>