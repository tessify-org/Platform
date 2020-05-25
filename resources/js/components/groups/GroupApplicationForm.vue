<template>
    <div id="group-application-form">

        <div class="form-field">
            <v-textarea
                name="motivation"
                :label="strings.motivation"
                v-model="form.motivation"
                :errors="hasErrors('motivation')"
                :error-messages="getErrors('motivation')">
            </v-textarea>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "oldInput",
            "errors",
            "strings",
        ],
        data: () => ({
            tag: "[group-application-form]",
            form: {
                motivation: "",
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" old input: ", this.oldInput);
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
    #group-application-form {

    }
</style>