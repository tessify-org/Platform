<template>
    <div id="project-team-role-form__wrapper">
        
        <!-- Form -->
        <div id="project-team-role-form" class="elevation-1">

            <!-- Name -->
            <div class="form-field">
                <v-text-field
                    name="name"
                    :label="nameText"
                    v-model="form.name"
                    :errors="hasErrors('name')"
                    :error-messages="getErrors('name')">
                </v-text-field>
            </div>

            <!-- Description -->
            <div class="form-field">
                <v-textarea
                    name="description"
                    :label="descriptionText"
                    v-model="form.description"
                    :errors="hasErrors('description')"
                    :error-messages="getErrors('description')">
                </v-textarea>
            </div>

            <!-- Number of positions -->
            <div class="form-field">
                <v-text-field
                    type="number"
                    name="positions"
                    :label="positionsText"
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
                    {{ backText }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn color="success" type="submit" depressed>
                    <i class="fas fa-save"></i>
                    {{ submitText }}
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
            "nameText",
            "descriptionText",
            "positionsText",
            "backHref",
            "backText",
            "submitText",
        ],
        data: () => ({
            tag: "[project-team-role-form]",
            form: {
                name: "",
                description: "",
                positions: 1,
            },
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" role: ", this.role);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" name text: ", this.nameText);
                console.log(this.tag+" description text: ", this.descriptionText);
                console.log(this.tag+" positions text: ", this.positionsText);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" back text: ", this.backText);
                console.log(this.tag+" submit text: ", this.submitText);
                this.initializeData();
            },
            initializeData() {
                if (this.role !== undefined && this.role !== null) {
                    this.form.name = this.role.name;
                    this.form.description = this.role.description;
                    this.form.positions = this.role.positions;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.name !== null) this.form.name = this.oldInput.name;
                    if (this.oldInput.description !== null) this.form.description = this.oldInput.description;
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