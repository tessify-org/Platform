<template>
    <div id="forum-subforum-form__wrapper">
        <div id="forum-subforum-form" class="elevation-1">

            <div class="form-field">
                <v-text-field
                    name="title"
                    v-model="form.title"
                    :label="strings.title"
                    :errors="hasErrors('title')"
                    :error-messages="getErrors('title')">
                </v-text-field>
            </div>

            <div class="form-field">
                <v-text-field
                    name="description"
                    v-model="form.description"
                    :label="strings.description"
                    :errors="hasErrors('description')"
                    :error-messages="getErrors('description')">
                </v-text-field>
            </div>

        </div>
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn :href="backHref" text>
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.cancel }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn type="submit" color="success">
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
            "forum",
            "strings",
            "oldInput", 
            "errors",
            "backHref",
        ],
        data: () => ({
            tag: "[forum-subforum-form]",
            form: {
                title: "",
                description: "",
            },
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" forum: ", this.forum);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" back href: ", this.backHref);
                this.initializeData();
            },
            initializeData() {
                if (this.forum !== undefined && this.forum !== null) {
                    this.form.title = this.forum.title;
                    this.form.description = this.forum.description;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.title !== null) this.form.title = this.oldInput.title;
                    if (this.oldInput.description !== null) this.form.description = this.oldInput.description;
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
    #forum-subforum-form {
        padding: 25px;
        border-radius: 3px;
        box-sizing: border-box;
        background-color: #fff;
    }
</style>