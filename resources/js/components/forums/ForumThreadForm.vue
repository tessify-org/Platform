<template>
    <div id="forum-thread-form__wrapper">
        <div id="forum-thread-form" :class="{ 'elevation-1 padded': doesNotHaveGroup }">

            <!-- Title -->
            <div class="form-field">
                <v-text-field
                    name="title"
                    v-model="form.title"
                    :label="strings.title"
                    :errors="hasErrors('title')"
                    :error-messages="getErrors('title')">
                </v-text-field>
            </div>

            <!-- Description -->
            <div class="form-field">
                <v-text-field
                    name="description"
                    v-model="form.description"
                    :label="strings.description"
                    :hint="strings.description_hint"
                    :errors="hasErrors('description')"
                    :error-messages="getErrors('description')">
                </v-text-field>
            </div>

            <!-- Message -->
            <div class="form-field">
                <v-textarea
                    name="message"
                    v-model="form.message"
                    :label="strings.message"
                    :errors="hasErrors('message')"
                    :error-messages="getErrors('message')">
                </v-textarea>
            </div>

            <!-- Sticky -->
            <div class="form-field checkbox">
                <v-checkbox
                    :label="strings.sticky"
                    v-model="form.sticky"
                    hide-details>
                </v-checkbox>
                <input type="hidden" name="sticky" :value="form.sticky">
            </div>

            <!-- Closed -->
            <div class="form-field checkbox">
                <v-checkbox
                    :label="strings.closed"
                    v-model="form.closed"
                    hide-details>
                </v-checkbox>
                <input type="hidden" name="closed" :value="form.closed">
            </div>

        </div>
        
        <!-- Page controls -->
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
            "group",
            "thread",
            "strings",
            "oldInput",
            "errors",
            "backHref",
        ],
        data: () => ({
            tag: "[forum-thread-form]",
            form: {
                title: "",
                description: "",
                message: "",
                sticky: false,
                closed: false,
            }
        }),
        computed: {
            doesNotHaveGroup() {
                return this.group === undefined;
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" thread: ", this.thread);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" back href: ", this.backHref);
                this.initializeData();
            },
            initializeData() {
                if (this.thread !== undefined && this.thread !== null) {
                    this.form.title = this.thread.title;
                    this.form.description = this.thread.description;
                    this.form.message = this.thread.message;
                    this.form.sticky = this.thread.sticky;
                    this.form.closed = this.thread.closed;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.title !== null) this.form.title = this.oldInput.title;
                    if (this.oldInput.description !== null) this.form.description = this.oldInput.description;
                    if (this.oldInput.message !== null) this.form.message = this.oldInput.message;
                    if (this.oldInput.sticky !== null) this.form.sticky = this.oldInput.sticky === "true" ? true : false;
                    if (this.oldInput.closed !== null) this.form.closed = this.oldInput.closed === "true" ? true : false;
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
    #forum-thread-form {
        border-radius: 3px;
        box-sizing: border-box;
        background-color: #fff;
        &.padded {
            padding: 25px;
        }
    }
</style>