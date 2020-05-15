<template>
    <div id="profile-current-assignment-field">

        <!-- Current assignment -->
        <div id="current-assignment">
            <v-select
                label="Current assignment"
                v-model="form.current_assignment_id"
                :items="assignmentOptions">
            </v-select>
        </div>

        <!-- Add assignment action -->
        <div id="current-assignment__actions">
            <v-btn color="primary" small depressed @click="onClickAdd">
                <i class="fas fa-plus"></i>
                Functie toevoegen
            </v-btn>
        </div>

        <!-- Add assignment dialog -->
        <v-dialog v-model="dialogs.add.show" width="500">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.add.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">Functie toevoegen</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.add.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.add.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Title -->
                    <div class="form-field">
                        <v-text-field
                            label="Functietitel"
                            v-model="dialogs.add.form.title">
                        </v-text-field>
                    </div>
                    <!-- Assignment type -->
                    <div class="form-field">
                        <v-select
                            label="Type aanstelling"
                            v-model="dialogs.add.form.assignment_type_id"
                            :items="assignmentTypeOptions">
                        </v-select>
                    </div>
                    <!-- Organization -->
                    <div class="form-field">
                        <v-combobox
                            label="Organisatie"
                            v-model="dialogs.add.form.organization"
                            :items="organizationOptions">
                        </v-combobox>
                    </div>
                    <!-- Department -->
                    <div class="form-field" v-if="dialogs.add.form.organization !== ''">
                        <v-combobox
                            label="Departement"
                            v-model="dialogs.add.form.department"
                            :items="organizationDepartmentOptions">
                        </v-combobox>
                    </div>
                    <!-- Location -->
                    <div class="form-field" v-if="dialogs.add.form.organization !== '' && selectedOrganizationHasLocations">
                        <v-select
                            label="Locatie"
                            v-model="dialogs.add.form.organization_location_id"
                            :items="organizationLocationOptions">
                        </v-select>

                    </div>
                    <!-- Current -->
                    <div class="form-field">
                        <v-checkbox
                            label="Dit is mijn huidige functie"
                            v-model="dialogs.add.form.current">
                        </v-checkbox>
                    </div>
                    <!-- Dates -->
                    <div class="form-fields">
                        <div class="form-field">
                            <datepicker
                                name="start_date"
                                label="Start datum"
                                v-model="dialogs.add.form.start_date">
                            </datepicker>
                        </div>
                        <div class="form-field" v-if="dialogs.add.form.current">
                            Heden
                        </div>
                        <div class="form-field" v-if="!dialogs.add.form.current">
                            <datepicker
                                name="end_date"
                                label="Eind datum"
                                v-model="dialogs.add.form.end_date"
                                placeholder="Tot heden">
                            </datepicker>
                        </div>
                    </div>
                </div>
                
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.add.show = false">
                            <i class="fas fa-arrow-left"></i>
                            Annuleren
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn 
                            depressed 
                            color="success"
                            @click="onClickConfirmAdd" 
                            :loading="dialogs.add.loading" 
                            :dark="!confirmAddDisabled"
                            :disabled="confirmAddDisabled">
                            <i class="far fa-save"></i>
                            Opslaan
                        </v-btn>
                    </div>
                </div>

            </div>
        </v-dialog>

        <!-- Hidden input -->
        <input type="hidden" :name="name" :value="form.current_assignment_id">

    </div>
</template>

<script>
    export default {
        props: [
            "name",
            "user",
            "assignmentTypes",
            "organizations",
            "organizationLocations",
            "departments",
            "value",
            "createApiEndpoint",
        ],
        data: () => ({
            tag: "[profile-current-assignment-field]",
            loaded: false,
            assignmentOptions: [],
            assignmentTypeOptions: [],
            organizationOptions: [],
            organizationLocationOptions: [],
            organizationDepartmentOptions: [],
            form: {
                current_assignment_id: 0,
            },
            dialogs: {
                add: {
                    show: false,
                    loading: false,
                    errors: [],
                    form: {
                        assignment_type_id: 0,
                        organization: "",
                        organization_location_id: 0,
                        department: "",
                        title: "",
                        current: true,
                        start_date: null,
                        end_date: null,
                    }
                }
            }
        }),
        computed: {
            selectedOrganization() {
                if (this.dialogs.add.form.organization !== "") {
                    let organization = this.getOrganizationByName(this.dialogs.add.form.organization);
                    if (organization) {
                        return organization;
                    }
                }
                return false;
            },
            selectedOrganizationHasLocations() {
                if (this.dialogs.add.form.organization !== "") {
                    let organization = this.getOrganizationByName(this.dialogs.add.form.organization);
                    if (organization) {
                        for (let i = 0; i < this.organizationLocations.length; i++) {
                            if (this.organizationLocations[i].organization_id === organization.id) {
                                return true;
                            }
                        }
                    }
                }
                return false;
            },
            confirmAddDisabled() {
                return this.dialogs.add.form.assignment_type_id === 0 ||
                    this.dialogs.add.form.organization === "" ||
                    this.dialogs.add.form.department === "" ||
                    this.dialogs.add.form.title === "" ||
                    this.dialogs.add.form.start_date === null ||
                    ( !this.dialogs.add.form.current && this.dialogs.add.form.end_date === null );
            },
        },
        watch: {
            "value": function() {
                if (!this.loaded) this.initializeData();
            },
            "dialogs.add.form.organization": function() {
                this.generateOrganizationDepartmentOptions();
                this.generateOrganizationLocationOptions();
            },
            "form.current_assignment_id": function() {
                this.$emit("input", this.form.current_assignment_id);
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" user: ", this.user);
                console.log(this.tag+" organizations: ", this.organizations);
                console.log(this.tag+" departments: ", this.departments);
                console.log(this.tag+" value: ", this.value);
                console.log(this.tag+" create api endpoint: ", this.createApiEndpoint);
                this.initializeData();
                this.generateAssignmentOptions();
                this.generateOrganizationOptions();
                this.generateAssignmentTypeOptions();
            },
            initializeData() {
                if (this.value !== undefined && this.value !== null && this.value > 0) {
                    this.form.current_assignment_id = this.value;
                    this.loaded = true;
                }
            },
            generateAssignmentOptions() {
                if (this.user !== undefined && this.user !== null && this.user.assignments !== undefined && this.user.assignments !== null && this.user.assignments.length > 0) {
                    for (let i = 0; i < this.user.assignments.length; i++) {
                        this.assignmentOptions.push({
                            text: this.user.assignments[i].title+" bij "+this.user.assignments[i].organization.name+ " ("+this.user.assignments[i].department.name+")",
                            value: this.user.assignments[i].id,
                        });
                    }
                } else {
                    this.assignmentOptions.push({
                        text: "Je hebt nog geen functies toegevoegd",
                        value: 0
                    });
                }
            },
            generateAssignmentTypeOptions() {
                if (this.assignmentTypes !== undefined && this.assignmentTypes !== null && this.assignmentTypes.length > 0) {
                    for (let i = 0; i < this.assignmentTypes.length; i++) {
                        this.assignmentTypeOptions.push({
                            text: this.assignmentTypes[i].label,
                            value: this.assignmentTypes[i].id,
                        });
                    }
                }
            },
            generateOrganizationOptions() {
                if (this.organizations !== undefined && this.organizations !== null && this.organizations.length > 0) {
                    for (let i = 0; i < this.organizations.length; i++) {
                        this.organizationOptions.push(this.organizations[i].name);
                    }
                } else {
                    this.organizationOptions.push({
                        text: "No organizations found",
                        value: 0,
                    });
                }
            },
            generateOrganizationDepartmentOptions() {
                console.log(this.tag+" generating organization department options");
                this.organizationDepartmentOptions = [];
                if (this.selectedOrganization && this.departments !== undefined && this.departments !== null && this.departments.length > 0) {
                    for (let i = 0; i < this.departments.length; i++) {
                        if (this.departments[i].organization_id === this.selectedOrganization.id) {
                            this.organizationDepartmentOptions.push(this.departments[i].name);
                        }
                    }
                }
            },
            generateOrganizationLocationOptions() {
                console.log(this.tag+" generating organization location options");
                this.organizationLocationOptions = [];
                if (this.dialogs.add.form.organization !== "" && this.organizationLocations !== undefined && this.organizationLocations !== null && this.organizationLocations.length > 0) {
                    if (this.selectedOrganization) {
                        for (let i = 0; i < this.organizationLocations.length; i++) {
                            if (this.organizationLocations[i].organization_id === this.selectedOrganization.id) {
                                this.organizationLocationOptions.push({
                                    text: this.organizationLocations[i].building_name === null ? this.organizationLocations[i].address : this.organizationLocations[i].building_name+" - "+this.organizationLocations[i].address,
                                    value: this.organizationLocations[i].id,
                                });
                            }
                        }
                    }
                }
            },
            getOrganizationByName(name) {
                if (this.organizations !== undefined && this.organizations !== null && this.organizations.length > 0) {
                    for (let i = 0; i < this.organizations.length; i++) {
                        if (this.organizations[i].name === name) {
                            return this.organizations[i];
                        }
                    }
                }
                return false;
            },
            onClickAdd() {
                console.log(this.tag+" clicked add button");
                this.dialogs.add.show = true;
            },
            onClickConfirmAdd() {
                console.log(this.tag+" clicked confirm add button");
                this.dialogs.add.loading = true;
                let payload = new FormData();
                payload.append("assignment_type_id", this.dialogs.add.form.assignment_type_id);
                payload.append("organization", this.dialogs.add.form.organization);
                payload.append("organization_location_id", this.dialogs.add.form.organization_location_id);
                payload.append("department", this.dialogs.add.form.department);
                payload.append("title", this.dialogs.add.form.title);
                payload.append("current", this.dialogs.add.form.current);
                payload.append("start_date", this.dialogs.add.form.start_date);
                payload.append("end_date", this.dialogs.add.form.end_date);
                this.axios.post(this.createApiEndpoint, payload)
                    .then(function(response) {
                        console.log(this.tag+" request succeeded", response.data);
                        this.dialogs.add.loading = false;
                        if (response.data.status === "success") {
                            console.log(this.tag+" operation succeeded");
                            // Add assignmment option for the created assignment
                            this.assignmentOptions.push({
                                text: response.data.assignment.title+" bij "+response.data.assignment.organization.name+" ("+response.data.assignment.department.name+")",
                                value: response.data.assignment.id,
                            });
                            // Set current assignment to the created assignment
                            this.form.current_assignment_id = response.data.assignment.id;
                            // Hide the dialog
                            this.dialogs.add.show = false;
                            // Reset the form
                            this.dialogs.add.form.assignment_type_id = 0;
                            this.dialogs.add.form.organization = "";
                            this.dialogs.add.form.organization_location_id = 0;
                            this.dialogs.add.form.department = "";
                            this.dialogs.add.form.title = "";
                            this.dialogs.add.form.current = true;
                            this.dialogs.add.form.start_date = null;
                            this.dialogs.add.form.end_date = null;
                        } else {
                            console.warn(this.tag+" operation failed");
                            this.dialogs.add.errors = [response.data.error];
                        }
                    }.bind(this))
                    .catch(function(error) {
                        console.warn(this.tag+" request failed: ", error);
                        this.dialogs.add.loading = false;
                        this.dialogs.add.errors = [error];
                    }.bind(this));
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #profile-current-assignment-field {
        #current-assignment {

        }
        #current-assignment__actions {
            display: flex;
            margin: 0 0 0 0;
            flex-direction: row;
            align-items: center;
            justify-content: flex-end;
            .v-btn {
                margin: 0 0 0 15px;
            }
        }
    }
</style>