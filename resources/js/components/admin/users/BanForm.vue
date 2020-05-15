<template>
    <div id="ban-form">
        
        <!-- User -->
        <div class="form-field">
            <v-text-field
                disabled
                :label="userText"
                v-model="user.formatted_name">
            </v-text-field>
        </div>

        <!-- Type -->
        <div class="form-field">
            <v-select
                :label="typeText"
                v-model="form.type"
                :items="typeOptions"
                :errors="hasErrors('type')"
                :error-messages="getErrors('type')">
            </v-select>
            <input type="hidden" name="type" :value="form.type">
        </div>

        <!-- Duration -->
        <div class="form-field" v-if="form.type === 'temporary'">
            <v-text-field
                type="number"
                :label="durationText"
                v-model="form.duration"
                :errors="hasErrors('duration')"
                :error-messages="getErrors('duration')">
            </v-text-field>
        </div>
        <input type="hidden" name="duration" :value="form.duration">

    </div>
</template>

<script>
    export default {
        props: [
            "user",
            "errors",
            "oldInput",
            "userText",
            "typeText",
            "durationText",
            "temporaryText",
            "permanentText",
        ],
        data: () => ({
            tag: "[ban-form]",
            typeOptions: [],
            form: {
                type: "temporary",
                duration: 1,
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" user: ", this.user);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" user text: ", this.userText);
                console.log(this.tag+" type text: ", this.typeText);
                console.log(this.tag+" duration text: ", this.durationText);
                console.log(this.tag+" temporary text: ", this.temporaryText);
                console.log(this.tag+" permanent text: ", this.permanentText);
                // console.log(this.tag+" ");
                this.generateTypeOptions();
                this.initializeData();
            },
            initializeData() {
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.type !== null) this.form.type = this.oldInput.type;
                    if (this.oldInput.duration !== null) this.form.duration = this.oldInput.duration;
                }
            },
            generateTypeOptions() {
                this.typeOptions.push({ text: this.temporaryText, value: "temporary" });
                this.typeOptions.push({ text: this.permanentText, value: "permanent" });
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
    #ban-form {

    }
</style>