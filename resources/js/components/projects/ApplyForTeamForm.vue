<template>
    <div id="apply-for-team-form__wrapper">
        
        <!-- Form -->
        <div id="apply-for-team-form" class="elevation-1">
            <!-- Project -->
            <div class="form-field">
                <v-text-field
                    disabled
                    :label="projectText"
                    v-model="project.title">
                </v-text-field>
            </div>
            <!-- Roles -->
            <div class="form-field">
                <v-select
                    :label="roleText"
                    :items="roleOptions"
                    v-model="form.team_role_id"
                    :errors="hasErrors('team_role_id')"
                    :error-messages="getErrors('team_role_id')">
                </v-select>
                <input type="hidden" name="team_role_id" :value="form.team_role_id">
            </div>
            <!-- Motivation -->
            <div class="form-field mb-0">
                <v-textarea
                    rows="10"
                    name="motivation"
                    :label="motivationText"
                    v-model="form.motivation"
                    :errors="hasErrors('motivation')"
                    :error-messages="getErrors('motivation')">
                </v-textarea>
            </div>
        </div>

        <!-- Controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn outlined :href="backHref">
                    <i class="fas fa-arrow-left"></i>
                    {{ backText }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn type="submit" color="success" depressed>
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
            "project",
            "roles",
            "errors",
            "oldInput",
            "backHref",
            "backText",
            "submitText",
            "projectText",
            "roleText",
            "motivationText",
        ],
        data: () => ({
            tag: "[apply-for-team-form]",
            roleOptions: [],
            form: {
                team_role_id: 0,
                motivation: "",
            },
        }),
        computed: {
            encodedTeamRoleIds() {
                return JSON.stringify(this.form.team_role_ids);
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" project; ", this.project);
                console.log(this.tag+" roles: ", this.roles);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" back text: ", this.backText);
                console.log(this.tag+" submit text: ", this.submitText);
                console.log(this.tag+" project text: ", this.projectText);
                console.log(this.tag+" role text: ", this.roleText);
                console.log(this.tag+" motivation text: ", this.motivationText);
                // console.log(this.tag+" ");
                this.generateRoleOptions();
                this.initializeData();
            },
            initializeData() {
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.team_role_id !== null) this.form.team_role_id = this.oldInput.team_role_id;
                    if (this.oldInput.motivation !== null) this.form.motivation = this.oldInput.motivation;
                }
            },
            generateRoleOptions() {
                if (this.roles !== undefined && this.roles !== null && this.roles.length > 0) {
                    for (let i = 0; i < this.roles.length; i++) {
                        this.roleOptions.push({
                            text: this.roles[i].name,
                            value: this.roles[i].id,
                        });
                    }
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
    #apply-for-team-form__wrapper {
        width: 800px;
        margin: 0 auto 30px auto;
        #apply-for-team-form {
            padding: 25px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #ffffff;
        }

    }
</style>