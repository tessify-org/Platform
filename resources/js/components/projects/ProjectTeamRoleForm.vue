<template>
    <div id="project-team-role-form__wrapper">
        
        <!-- Form -->
        <div id="project-team-role-form" class="elevation-1">

            <!-- Name -->
            <div class="form-fields">
                <div class="form-field">
                    <v-text-field
                        name="name_nl"
                        :label="strings.name+' ('+strings.nl+')'"
                        v-model="form.name.nl"
                        :errors="hasErrors('name_nl')"
                        :error-messages="getErrors('name_nl')">
                    </v-text-field>
                </div>
                <div class="form-field">
                    <v-text-field
                        name="name_en"
                        :label="strings.name+' ('+strings.en+')'"
                        v-model="form.name.en"
                        :errors="hasErrors('name_en')"
                        :error-messages="getErrors('name_en')">
                    </v-text-field>
                </div>
            </div>

            <!-- Description -->
            <div class="form-fields">
                <div class="form-field">
                    <v-textarea
                        name="description_nl"
                        :label="strings.description+' ('+strings.nl+')'"
                        v-model="form.description.nl"
                        :errors="hasErrors('description_nl')"
                        :error-messages="getErrors('description_nl')">
                    </v-textarea>
                </div>
                <div class="form-field">
                    <v-textarea
                        name="description_en"
                        :label="strings.description+' ('+strings.en+')'"
                        v-model="form.description.en"
                        :errors="hasErrors('description_en')"
                        :error-messages="getErrors('description_en')">
                    </v-textarea>
                </div>
            </div>

            <!-- Number of positions -->
            <div class="form-field">
                <v-text-field
                    type="number"
                    name="positions"
                    :label="strings.positions"
                    v-model="form.positions"
                    :errors="hasErrors('positions')"
                    :error-messages="getErrors('positions')">
                </v-text-field>
            </div>

        </div>

        <!-- Form controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn outlined :href="backHref">
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.back }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn color="success" type="submit" depressed>
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
            "role",
            "errors",
            "oldInput",
            "strings",
            "backHref",
            "locale",
        ],
        data: () => ({
            tag: "[project-team-role-form]",
            form: {
                name: {
                    nl: "",
                    en: "",
                },
                description: {
                    nl: "",
                    en: "",
                },
                positions: 1,
            },
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" role: ", this.role);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" locale: ", this.locale);
                this.initializeData();
            },
            initializeData() {
                if (this.role !== undefined && this.role !== null) {
                    this.form.name.nl = this.role.name.nl;
                    this.form.name.en = this.role.name.en;
                    this.form.description.nl = this.role.description.nl;
                    this.form.description.en = this.role.description.en;
                    this.form.positions = this.role.positions;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.name_nl !== null) this.form.name.nl = this.oldInput.name_nl;
                    if (this.oldInput.name_en !== null) this.form.name.en = this.oldInput.name_en;
                    if (this.oldInput.description_nl !== null) this.form.description.nl = this.oldInput.description_nl;
                    if (this.oldInput.description_en !== null) this.form.description.en = this.oldInput.description_en;
                    if (this.oldInput.positions !== null) this.form.positions = this.oldInput.positions;
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
    #project-team-role-form__wrapper {
        width: 600px;
        margin: 0 auto;
        #project-team-role-form {
            padding: 25px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #ffffff;
        }
    }
</style>