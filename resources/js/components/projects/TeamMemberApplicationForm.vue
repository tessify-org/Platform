<template>
    <div id="team-member-application-form__wrapper">

        <!-- Form -->
        <div id="team-member-application-form" class="elevation-1">
            
            <!-- Team role -->
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
                    name="motivation"
                    :label="motivationText"
                    v-model="form.motivation"
                    :errors="hasErrors('motivation')"
                    :error-messages="getErrors('motivation')">
                </v-textarea>
            </div>

        </div>

        <!-- Actions -->
        <div id="team-member-application-form__controls">
            <div id="team-member-application-form__controls-left">
                <v-btn outlined :href="backHref">
                    <i class="fas fa-arrow-left"></i>
                    {{ backText }}
                </v-btn>
            </div>
            <div id="team-member-application-form__controls-right">
                <v-btn depressed color="success" type="submit">
                    {{ submitText }}
                </v-btn>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "errors",
            "oldInput",
            "application",
            "roles",
            "backText",
            "backHref",
            "submitText",
            "roleText",
            "motivationText",
        ],
        data: () => ({
            tag: "[team-member-applications-form]",
            roleOptions: [],
            form: {
                team_role_id: 0,
                motivation: "",
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" application: ", this.application);
                console.log(this.tag+" roles: ", this.roles);
                console.log(this.tag+" back text: ", this.backText);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" subimt text: ", this.submitText);
                this.generateRoleOptions();
                this.initializeData();
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
            initializeData() {
                if (this.application !== undefined && this.application !== null) {
                    this.form.team_role_id = this.application.team_role_id;
                    this.form.motivation = this.application.motivation;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.team_role_id !== null) this.form.team_role_id = parseInt(this.oldInput.team_role_id);
                    if (this.oldInput.motivation !== null) this.form.motivation = this.oldInput.motivation;
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
    #team-member-application-form__wrapper {
        width: 800px;
        margin: 0 auto;
        #team-member-application-form {
            padding: 25px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #ffffff;
        }
        #team-member-application-form__controls {
            display: flex;
            margin: 30px 0 0 0;
            flex-direction: row;
            #team-member-application-form__controls-left {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
            }
            #team-member-application-form__controls-right {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
            }
        }
    }
</style>