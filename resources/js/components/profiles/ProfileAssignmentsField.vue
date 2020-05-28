<template>
    <div id="profile-assignments-field">

        <!-- Label -->
        <div id="profile-assignments-field__label">{{ strings.label }}</div>

        <!-- Assignments -->
        <div id="profile-assignments-field__assignments" v-if="mutableAssignments.length > 0">
            <div class="assignment" v-for="(assignment, ai) in mutableAssignments" :key="ai">
                <div class="assignment-title">
                    <span class="current-assignment" v-if="assignment.current">
                        <i class="fas fa-briefcase"></i>
                    </span>
                    {{ assignment.title }}
                </div>
                <div class="assignment-actions">
                    <div class="assignment-action view" @click="onClickView(ai)">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="assignment-action edit" @click="onClickEdit(ai)">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="assignment-action delete" @click="onClickDelete(ai)">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- No assignments -->
        <div id="profile-assignments-field__no-assignments" v-if="mutableAssignments.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- Actions -->
        <div id="profile-assignments-field__actions">
            <v-btn color="primary" small depressed @click="onClickAdd">
                <i class="fas fa-plus"></i>
                {{ strings.add_button }}
            </v-btn>
        </div>

        <!-- View dialog -->
        <v-dialog v-model="dialogs.view.show" width="800">
            <div class="dialog" v-if="dialogs.view.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.view_dialog_title }}</h3>
                    <!-- Details -->
                    <div class="details compact bordered mb-0">
                        <!-- Ministry -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_ministry }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].organization.ministry.name[this.locale] }}</div>
                        </div>
                        <!-- Ministry department -->
                        <div class="detail" v-if="mutableAssignments[dialogs.view.index].organization.ministry_department">
                            <div class="key">{{ strings.view_dialog_ministry_department }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].organization.ministry_department.name[this.locale] }}</div>
                        </div>
                        <!-- Organization -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_organization }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].organization.name[this.locale] }}</div>
                        </div>
                        <!-- Location -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_location }}</div>
                            <div class="val">
                                <span class="building-name" v-if="mutableAssignments[dialogs.view.index].location.building_name !== null">
                                    {{ mutableAssignments[dialogs.view.index].location.building_name }} - 
                                </span>
                                {{ mutableAssignments[dialogs.view.index].location.address }}
                            </div>
                        </div>
                        <!-- Department -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_department }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].department.name[this.locale] }}</div>
                        </div>
                        <!-- Assignment type -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_assignment_type }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].type.label[this.locale] }}</div>
                        </div>
                        <!-- Function name -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_function }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].title }}</div>
                        </div>
                        <!-- Duration -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_duration }}</div>
                            <div class="val">
                                {{ mutableAssignments[dialogs.view.index].formatted_start_date }}
                                <span class="duration-second-part" v-if="mutableAssignments[dialogs.view.index].current">
                                    - {{ strings.form_present }}
                                </span>
                                <span class="duration-second-part" v-if="!mutableAssignments[dialogs.view.index].current">
                                    - {{ mutableAssignments[dialogs.view.index].formatted_end_date }}
                                </span>
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="detail">
                            <div class="key">{{ strings.view_dialog_description }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].description }}</div>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__right">
                        <!-- Edit -->
                        <v-btn depressed color="warning" @click="onClickEdit(dialogs.view.index)">
                            <i class="fas fa-edit"></i>
                            {{ strings.view_dialog_edit }}
                        </v-btn>
                        <!-- Delete -->
                        <v-btn depressed dark color="red" @click="onClickDelete(dialogs.view.index)">
                            <i class="fas fa-trash-alt"></i>
                            {{ strings.view_dialog_delete }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Create dialog -->
        <v-dialog v-model="dialogs.add.show" width="500">
            <div id="create-dialog" class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.add.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.create_dialog_title }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.add.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.add.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Title -->
                    <div class="form-field">
                        <v-text-field
                            :label="strings.form_title"
                            v-model="dialogs.add.form.title">
                        </v-text-field>
                    </div>
                    <!-- Description -->
                    <div class="form-field">
                        <v-textarea
                            :label="strings.form_description"
                            v-model="dialogs.add.form.description">
                        </v-textarea>
                    </div>
                    <!-- Assignment type -->
                    <div class="form-field">
                        <v-select
                            :label="strings.form_type"
                            v-model="dialogs.add.form.assignment_type_id"
                            :items="assignmentTypeOptions">
                        </v-select>
                    </div>
                    <!-- Organization -->
                    <div class="form-field">
                        <v-combobox
                            :label="strings.form_organization"
                            v-model="dialogs.add.form.organization"
                            :items="organizationOptions">
                        </v-combobox>
                    </div>
                    <!-- Department -->
                    <div class="form-field" v-if="dialogs.add.form.organization !== ''">
                        <v-combobox
                            :label="strings.form_department"
                            v-model="dialogs.add.form.department"
                            :items="dialogs.add.organizationDepartmentOptions">
                        </v-combobox>
                    </div>
                    <!-- Location -->
                    <div class="form-field" v-if="dialogs.add.form.organization !== '' && addSelectedOrganizationHasLocations">
                        <v-combobox
                            :label="strings.form_location"
                            v-model="dialogs.add.form.organization_location"
                            :items="dialogs.add.organizationLocationOptions">
                        </v-combobox>
                    </div>
                    <!-- Current -->
                    <div class="form-field">
                        <v-checkbox
                            :label="strings.form_current_function"
                            v-model="dialogs.add.form.current">
                        </v-checkbox>
                    </div>
                    <!-- Dates -->
                    <div class="form-fields">
                        <div class="form-field">
                            <datepicker
                                name="start_date"
                                :label="strings.form_start_date"
                                v-model="dialogs.add.form.start_date">
                            </datepicker>
                        </div>
                        <div class="form-field" v-if="dialogs.add.form.current">
                            {{ strings.form_present }}
                        </div>
                        <div class="form-field" v-if="!dialogs.add.form.current">
                            <datepicker
                                name="end_date"
                                :label="strings.form_end_date"
                                v-model="dialogs.add.form.end_date"
                                :placeholder="strings.form_until_present">
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
                            {{ strings.create_dialog_cancel }}
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
                            {{ strings.create_dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Update dialog -->
        <v-dialog v-model="dialogs.edit.show" width="500">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.edit.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.update_dialog_title }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.edit.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.edit.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Title -->
                    <div class="form-field">
                        <v-text-field
                            :label="strings.form_title"
                            v-model="dialogs.edit.form.title">
                        </v-text-field>
                    </div>
                    <!-- Description -->
                    <div class="form-field">
                        <v-textarea
                            :label="strings.form_description"
                            v-model="dialogs.edit.form.description">
                        </v-textarea>
                    </div>
                    <!-- Assignment type -->
                    <div class="form-field">
                        <v-select
                            :label="strings.form_type"
                            v-model="dialogs.edit.form.assignment_type_id"
                            :items="assignmentTypeOptions">
                        </v-select>
                    </div>
                    <!-- Organization -->
                    <div class="form-field">
                        <v-combobox
                            :label="strings.form_organization"
                            v-model="dialogs.edit.form.organization"
                            :items="organizationOptions">
                        </v-combobox>
                    </div>
                    <!-- Department -->
                    <div class="form-field" v-if="dialogs.edit.form.organization !== ''">
                        <v-combobox
                            :label="strings.form_department"
                            v-model="dialogs.edit.form.department"
                            :items="dialogs.edit.organizationDepartmentOptions">
                        </v-combobox>
                    </div>
                    <!-- Location -->
                    <div class="form-field" v-if="dialogs.edit.form.organization !== '' && editSelectedOrganizationHasLocations">
                        <v-combobox
                            :label="strings.form_location"
                            v-model="dialogs.edit.form.organization_location"
                            :items="dialogs.edit.organizationLocationOptions">
                        </v-combobox>
                    </div>
                    <!-- Current -->
                    <div class="form-field">
                        <v-checkbox
                            :label="strings.form_current_function"
                            v-model="dialogs.edit.form.current">
                        </v-checkbox>
                    </div>
                    <!-- Dates -->
                    <div class="form-fields">
                        <div class="form-field">
                            <datepicker
                                name="start_date"
                                :label="strings.form_start_date"
                                v-model="dialogs.edit.form.start_date">
                            </datepicker>
                        </div>
                        <div class="form-field" v-if="dialogs.edit.form.current">
                            {{ strings.form_present }}
                        </div>
                        <div class="form-field" v-if="!dialogs.edit.form.current">
                            <datepicker
                                name="end_date"
                                :label="strings.form_end_date"
                                v-model="dialogs.edit.form.end_date"
                                :placeholder="strings.form_until_present">
                            </datepicker>
                        </div>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.edit.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.update_dialog_cancel }}
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn 
                            depressed 
                            color="success"
                            @click="onClickConfirmEdit" 
                            :loading="dialogs.edit.loading" 
                            :dark="!confirmUpdateDisabled"
                            :disabled="confirmUpdateDisabled">
                            <i class="far fa-save"></i>
                            {{ strings.update_dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>
        
        <!-- Delete dialog -->
        <v-dialog v-model="dialogs.delete.show" width="600">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.delete.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.delete_dialog_title }}</h3>
                    <!-- Text -->
                    <div class="dialog-text">{{ strings.delete_dialog_text }}</div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.delete.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.delete_dialog_cancel }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <v-btn 
                            depressed dark color="red"
                            :loading="dialogs.delete.loading"
                            :disabled="dialogs.delete.loading"
                            @click="onClickConfirmDelete">
                            <i class="fas fa-trash-alt"></i>
                            {{ strings.delete_dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

    </div>
</template>

<script>
    export default {
        props: [
            "name",
            "value",
            "user",
            "assignmentTypes",
            "organizations",
            "organizationLocations",
            "departments",
            "apiEndpoints",
            "strings",
            "locale",
        ],
        data: () => ({
            tag: "[profile-assignments-field]",
            loaded: false,
            mutableAssignments: [],
            assignmentOptions: [],
            assignmentTypeOptions: [],
            organizationOptions: [],
            dialogs: {
                view: {
                    show: false,
                    index: null,
                },
                add: {
                    show: false,
                    index: null,
                    errors: [],
                    loading: false,
                    form: {
                        assignment_type_id: 0,
                        organization: "",
                        organization_location: "",
                        department: "",
                        title: "",
                        description: "",
                        current: true,
                        start_date: null,
                        end_date: null,
                    },
                    organizationLocationOptions: [],
                    organizationDepartmentOptions: [],
                },
                edit: {
                    show: false,
                    index: null,
                    errors: [],
                    loading: false,
                    form: {
                        assignment_type_id: 0,
                        organization: "",
                        organization_location: "",
                        department: "",
                        title: "",
                        description: "",
                        current: true,
                        start_date: null,
                        end_date: null,
                    },
                    organizationLocationOptions: [],
                    organizationDepartmentOptions: [],
                },
                delete: {
                    show: false,
                    index: null,
                    errors: [],
                    loading: false,
                },
            }
        }),
        computed: {
            addSelectedOrganization() {
                if (this.dialogs.add.form.organization !== "") {
                    let organization = this.getOrganizationByName(this.dialogs.add.form.organization);
                    if (organization) {
                        return organization;
                    }
                }
                return false;
            },
            addSelectedOrganizationHasLocations() {
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
            editSelectedOrganization() {
                if (this.dialogs.edit.form.organization !== "") {
                    let organization = this.getOrganizationByName(this.dialogs.edit.form.organization);
                    if (organization) {
                        return organization;
                    }
                }
                return false;
            },
            editSelectedOrganizationHasLocations() {
                if (this.dialogs.edit.form.organization !== "") {
                    let organization = this.getOrganizationByName(this.dialogs.edit.form.organization);
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
            confirmUpdateDisabled() {
                return this.dialogs.edit.form.assignment_type_id === 0 ||
                    this.dialogs.edit.form.organization === "" ||
                    this.dialogs.edit.form.department === "" ||
                    this.dialogs.edit.form.title === "" ||
                    this.dialogs.edit.form.start_date === null ||
                    ( !this.dialogs.edit.form.current && this.dialogs.edit.form.end_date === null );
            },
        },
        watch: {
            "value": function() {
                // if (!this.loaded) this.initializeData();
            },
            "dialogs.add.form.organization": function() {
                console.log("add organization changed");
                this.generateOrganizationLocationOptions("add");
                this.generateOrganizationDepartmentOptions("add");
            },
            "dialogs.edit.form.organization": function() {
                console.log("edit organization changed");
                this.generateOrganizationLocationOptions("edit");
                this.generateOrganizationDepartmentOptions("edit");
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" name: ", this.name);
                console.log(this.tag+" value: ", this.value);
                console.log(this.tag+" user: ", this.user);
                console.log(this.tag+" assignment types: ", this.assignmentTypes);
                console.log(this.tag+" organizations: ", this.organizations);
                console.log(this.tag+" organization locations: ", this.organizationLocations);
                console.log(this.tag+" departments: ", this.departments);
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" locale: ", this.locale);
                this.initializeData();
                this.generateOrganizationOptions();
                this.generateAssignmentTypeOptions();
            },
            initializeData() {
                if (this.user !== undefined && this.user !== null && this.user.assignments !== undefined && this.user.assignments !== null && this.user.assignments.length > 0) {
                    for (let i = 0; i < this.user.assignments.length; i++) {
                        this.mutableAssignments.push(this.user.assignments[i]);
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
            generateAssignmentTypeOptions() {
                if (this.assignmentTypes !== undefined && this.assignmentTypes !== null && this.assignmentTypes.length > 0) {
                    for (let i = 0; i < this.assignmentTypes.length; i++) {
                        this.assignmentTypeOptions.push({
                            text: this.assignmentTypes[i].label[this.locale],
                            value: this.assignmentTypes[i].id,
                        });
                    }
                }
            },
            generateOrganizationOptions() {
                if (this.organizations !== undefined && this.organizations !== null && this.organizations.length > 0) {
                    for (let i = 0; i < this.organizations.length; i++) {
                        this.organizationOptions.push(this.organizations[i].name[this.locale]);
                    }
                } else {
                    this.organizationOptions.push({
                        text: this.strings.no_organizations,
                        value: 0,
                    });
                }
            },
            generateOrganizationDepartmentOptions(type) {
                if (this.departments !== undefined && this.departments !== null && this.departments.length > 0) {
                    if (type === "add") {
                        if (this.addSelectedOrganization) {
                            this.dialogs.add.organizationDepartmentOptions = [];
                            for (let i = 0; i < this.departments.length; i++) {
                                if (this.departments[i].organization_id === this.addSelectedOrganization.id) {
                                    this.dialogs.add.organizationDepartmentOptions.push(this.departments[i].name[this.locale]);
                                }
                            }
                        }
                    } else {
                        if (this.editSelectedOrganization) {
                            this.dialogs.edit.organizationDepartmentOptions = [];
                            for (let i = 0; i < this.departments.length; i++) {
                                if (this.departments[i].organization_id === this.editSelectedOrganization.id) {
                                    this.dialogs.edit.organizationDepartmentOptions.push(this.departments[i].name[this.locale]);
                                }
                            }
                        }
                    }
                }
            },
            generateOrganizationLocationOptions(type) {
                if (this.organizationLocations !== undefined && this.organizationLocations !== null && this.organizationLocations.length > 0) {
                    if (type === "add") {
                        this.dialogs.add.organizationLocationOptions = [];
                        if (this.addSelectedOrganization) {
                            this.dialogs.add.organizationLocationOptions = [];
                            for (let i = 0; i < this.organizationLocations.length; i++) {
                                if (this.organizationLocations[i].organization_id === this.addSelectedOrganization.id) {
                                    let entry = this.organizationLocations[i].building_name === null ? this.organizationLocations[i].address : this.organizationLocations[i].building_name+" - "+this.organizationLocations[i].address;
                                    this.dialogs.add.organizationLocationOptions.push(entry);
                                }
                            }
                        }
                    } else {
                        this.dialogs.edit.organizationLocationOptions = [];
                        if (this.editSelectedOrganization) {
                            this.dialogs.edit.organizationLocationOptions = [];
                            for (let i = 0; i < this.organizationLocations.length; i++) {
                                if (this.organizationLocations[i].organization_id === this.editSelectedOrganization.id) {
                                    let entry = this.organizationLocations[i].building_name === null ? this.organizationLocations[i].address : this.organizationLocations[i].building_name+" - "+this.organizationLocations[i].address;
                                    this.dialogs.edit.organizationLocationOptions.push(entry);
                                }
                            }
                        }
                    }
                }
            },
            getOrganizationByName(name) {
                if (this.organizations !== undefined && this.organizations !== null && this.organizations.length > 0) {
                    for (let i = 0; i < this.organizations.length; i++) {
                        if (this.organizations[i].name[this.locale] === name) {
                            return this.organizations[i];
                        }
                    }
                }
                return false;
            },
            onClickAdd() {
                this.dialogs.add.show = true;
            },
            onClickConfirmAdd() {
                this.dialogs.add.loading = true;
                let payload = new FormData();
                payload.append("assignment_type_id", this.dialogs.add.form.assignment_type_id);
                payload.append("organization", this.dialogs.add.form.organization);
                payload.append("organization_location", this.dialogs.add.form.organization_location);
                payload.append("department", this.dialogs.add.form.department);
                payload.append("title", this.dialogs.add.form.title);
                payload.append("description", this.dialogs.add.form.description);
                payload.append("current", this.dialogs.add.form.current);
                payload.append("start_date", this.dialogs.add.form.start_date);
                payload.append("end_date", this.dialogs.add.form.end_date);
                this.axios.post(this.apiEndpoints.create_assignment, payload)
                    .then(function(response) {
                        this.dialogs.add.loading = false;
                        if (response.data.status === "success") {
                            // If the assignment was the current assignment; make other assignment not the current anymore
                            if (response.data.assignment.current) {
                                for (let i = 0; i < this.mutableAssignments.length; i++) {
                                    if (this.mutableAssignments[i].index !== this.dialogs.edit.index && this.mutableAssignments[i].current) {
                                        this.mutableAssignments[i].current = false;
                                    }
                                }
                            }
                            // Add created assignment to mutable assignments
                            this.mutableAssignments.push(response.data.assignment);
                            // Hide the dialog
                            this.dialogs.add.show = false;
                            // Reset the form
                            this.dialogs.add.form.assignment_type_id = 0;
                            this.dialogs.add.form.organization = "";
                            this.dialogs.add.form.organization_location = "";
                            this.dialogs.add.form.department = "";
                            this.dialogs.add.form.title = "";
                            this.dialogs.add.form.current = true;
                            this.dialogs.add.form.start_date = null;
                            this.dialogs.add.form.end_date = null;
                        } else {
                            this.dialogs.add.errors = [response.data.error];
                        }
                    }.bind(this))
                    .catch(function(error) {
                        this.dialogs.add.loading = false;
                        this.dialogs.add.errors = [error];
                    }.bind(this));

            },
            onClickView(index) {
                this.dialogs.view.index = index;
                this.dialogs.view.show = true;
            },
            onClickEdit(index) {
                if (this.dialogs.view.show) this.dialogs.view.show = false;
                this.dialogs.edit.index =  index;
                this.generateOrganizationDepartmentOptions("edit");
                this.generateOrganizationLocationOptions("edit");
                this.dialogs.edit.form.assignment_type_id = this.mutableAssignments[index].assignment_type_id;
                this.dialogs.edit.form.organization = this.mutableAssignments[index].organization.name[this.locale];
                this.dialogs.edit.form.organization_department_id = this.mutableAssignments[index].organization_department_id;
                this.dialogs.edit.form.organization_location = this.mutableAssignments[index].location.address;
                this.dialogs.edit.form.department = this.mutableAssignments[index].department.name[this.locale];
                this.dialogs.edit.form.title = this.mutableAssignments[index].title;
                this.dialogs.edit.form.description = this.mutableAssignments[index].description;
                this.dialogs.edit.form.current = this.mutableAssignments[index].current;
                this.dialogs.edit.form.start_date = this.mutableAssignments[index].formatted_start_date;
                this.dialogs.edit.form.end_date = this.mutableAssignments[index].formatted_end_date;
                this.dialogs.edit.show = true;
            },
            onClickConfirmEdit() {
                this.dialogs.edit.loading = true;
                let payload =  new FormData();
                payload.append("assignment_id", this.mutableAssignments[this.dialogs.edit.index].id);
                payload.append("assignment_type_id", this.dialogs.edit.form.assignment_type_id);
                payload.append("organization", this.dialogs.edit.form.organization);
                payload.append("organization_location", this.dialogs.edit.form.organization_location);
                payload.append("department", this.dialogs.edit.form.department);
                payload.append("title", this.dialogs.edit.form.title);
                payload.append("description", this.dialogs.edit.form.description);
                payload.append("current", this.dialogs.edit.form.current);
                payload.append("start_date", this.dialogs.edit.form.start_date);
                payload.append("end_date", this.dialogs.edit.form.end_date);
                this.axios.post(this.apiEndpoints.update_assignment, payload)
                    .then(function(response) {
                        this.dialogs.add.loading = false;
                        if (response.data.status === "success") {
                            console.log(this.tag+" operation succeeded");
                            // If the assignment was the current assignment; make other assignment not the current anymore
                            if (response.data.assignment.current) {
                                for (let i = 0; i < this.mutableAssignments.length; i++) {
                                    if (this.mutableAssignments[i].index !== this.dialogs.edit.index && this.mutableAssignments[i].current) {
                                        this.mutableAssignments[i].current = false;
                                    }
                                }
                            }
                            // Add created assignment to mutable assignments
                            this.mutableAssignments[this.dialogs.edit.index] = response.data.assignment;
                            // Hide the dialog
                            this.dialogs.edit.show = false;
                            this.dialogs.edit.loading = false;
                        } else {
                            this.dialogs.edit.errors = [response.data.error];
                            this.dialogs.edit.loading = false;
                        }
                    }.bind(this))
                    .catch(function(error) {
                        this.dialogs.edit.loading = false;
                        this.dialogs.edit.errors = [error];
                    }.bind(this));
            },
            onClickDelete(index) {
                if (this.dialogs.view.show) this.dialogs.view.show = false;
                this.dialogs.delete.index = index;
                this.dialogs.delete.show = true;
            },
            onClickConfirmDelete() {
                this.dialogs.delete.loading = true;
                let payload = new FormData();
                payload.append("assignment_id", this.mutableAssignments[this.dialogs.delete.index].id);
                this.axios.post(this.apiEndpoints.delete_assignment, payload)
                    .then(function(response) {
                        this.dialogs.delete.loading = false;
                        this.mutableAssignments.splice(this.dialogs.delete.index, 1);
                        this.dialogs.delete.show = false;
                    }.bind(this))
                    .catch(function(response) {
                        this.dialogs.delete.loading = false;
                        this.dialogs.delete.errors = [error];
                    }.bind(this));
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #profile-assignments-field {
        #profile-assignments-field__label {
            font-size: .85em;
            margin: 0 0 10px 0;
            color: hsl(0, 0%, 45%);
        }
        #profile-assignments-field__assignments {
            margin: 0 0 15px 0;
            border-radius: 3px;
            background-color: hsl(0, 0%, 95%);
            .assignment {
                display: flex;
                padding: 10px;
                flex-direction: row;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                .assignment-title {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    padding: 0 0 0 10px;
                    box-sizing: border-box;
                    .current-assignment {
                        margin: 0 10px 0 0;
                    }
                }
                .assignment-organization {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    padding: 0 0 0 10px;
                    box-sizing: border-box;
                }
                .assignment-department {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    padding: 0 0 0 10px;
                    box-sizing: border-box;
                }
                .assignment-actions {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    justify-content: flex-end;
                    .assignment-action {
                        width: 26px;
                        height: 26px;
                        display: flex;
                        font-size: .8em;
                        cursor: pointer;
                        color: #ffffff;
                        border-radius: 3px;
                        margin: 0 0 0 10px;
                        flex-direction: row;
                        align-items: center;
                        justify-content: center;
                        background-color: #333333;
                        &:first-child {
                            margin: 0;
                        }
                        &.view {
                            background-color: #0088ff;
                            &:hover {
                                background-color: #0072d6;
                            }
                        }
                        &.edit {
                            background-color: #f35900;
                            &:hover {
                                background-color: #e25300;
                            }
                        }
                        &.delete {
                            background-color: #b70000;
                            &:hover {
                                background-color: #990000;
                            }
                        }
                    }
                }
            }
        }
        #profile-assignments-field__no-assignments {
            padding: 10px 15px;
            margin: 0 0 15px 0;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: hsl(0, 0%, 95%);
        }
        #profile-assignments-field__actions {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-end;
            .v-btn {
                margin: 0 0 0 15px;
            }
        }
    }
    .duration-second-part {
        margin: 0 0 0 5px;
    }
    .building-name {
        margin: 0 0 0 10px;
    }
    .v-dialog  {
        max-height: 650px !important;
    }
</style>