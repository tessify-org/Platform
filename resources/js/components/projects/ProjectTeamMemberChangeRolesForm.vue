<template>
    <div id="change-roles-form__wrapper">

        <!-- Form -->
        <div id="change-roles-form" class="elevation-1">

            <!-- Project -->
            <div class="form-field">
                <v-text-field
                    disabled
                    :label="projectText"
                    v-model="project.title">
                </v-text-field>
            </div>

            <!-- User -->
            <div class="form-field">
                <v-text-field
                    disabled
                    :label="userText"
                    v-model="member.user.formatted_name">
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

        </div>

        <!-- Controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn outlined :href="backHref">
                    {{ backText }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn depressed color="success" type="submit">
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
            "member",
            "roles",
            "project",
            "errors",
            "oldInput",
            "userText",
            "projectText",
            "roleText",
            "selectRoleText",
            "noRolesText",
            "backHref",
            "backText",
            "submitText",
        ],
        data: () => ({
            tag: "[project-team-member-change-roles-form]",
            roleOptions: [],
            form: {
                team_role_id: 0,
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" member: ", this.member);
                console.log(this.tag+" roles: ", this.roles);
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" project text: ", this.projectText);
                console.log(this.tag+" user text: ", this.userText);
                console.log(this.tag+" role text: ", this.roleText);
                console.log(this.tag+" select role text: ", this.selectRoleText);
                console.log(this.tag+" no roles text: ", this.noRolesText);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" back text: ", this.backText);
                console.log(this.tag+" submit text: ", this.submitText);
                this.generateRoleOptions();
                this.initializeData();
            },
            generateRoleOptions() {
                if (this.roles !== undefined && this.roles !== null && this.roles.length > 0) {
                    this.roleOptions.push({
                        text: this.selectRoleText,
                        value: 0
                    });
                    for (let i = 0; i < this.roles.length; i++) {
                        this.roleOptions.push({
                            text: this.roles[i].name,
                            value: this.roles[i].id,
                        });
                    }
                } else {
                    this.roleOptions.push({
                        text: this.noRolesText,
                        value: 0,
                    });
                }
            },
            initializeData() {
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.team_role_id !== null) this.form.team_role_id = parseInt(this.oldInput.team_role_id);
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
    #change-roles-form__wrapper {
        width: 800px;
        margin: 0 auto;
        #change-roles-form {
            padding: 25px;
            margin: 0 0 30px 0;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #ffffff;
        }
    }
</style>