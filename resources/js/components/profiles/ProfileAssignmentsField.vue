<template>
    <div id="profile-assignments-field">

        <!-- Label -->
        <div id="profile-assignments-field__label">{{ labelText }}</div>

        <!-- Assignments -->
        <div id="profile-assignments-field__assignments" v-if="mutableAssignments.length > 0">
            <div class="assignment" v-for="(assignment, ai) in mutableAssignments" :key="ai">
                <div class="assignment-title">
                    <span class="current-assignment" v-if="assignment.current">
                        <i class="fas fa-briefcase"></i>
                    </span>
                    {{ assignment.title }}
                </div>
                <div class="assignment-organization">{{ assignment.organization.abbreviation }}</div>
                <div class="assignment-department">{{ assignment.department.name }}</div>
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
            {{ noRecordsText }}
        </div>

        <!-- Actions -->
        <div id="profile-assignments-field__actions">
            <v-btn color="primary" small depressed @click="onClickAdd">
                <i class="fas fa-plus"></i>
                {{ addButtonText }}
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
                    <h3 class="dialog-title">{{ viewDialogTitleText }}</h3>
                    <!-- Details -->
                    <div class="details compact bordered mb-0">
                        <!-- Ministry -->
                        <div class="detail">
                            <div class="key">{{ viewDialogMinistryText }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].organization.ministry.name }}</div>
                        </div>
                        <!-- Organization -->
                        <div class="detail">
                            <div class="key">{{ viewDialogOrganizationText }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].organization.name }}</div>
                        </div>
                        <!-- Location -->
                        <div class="detail">
                            <div class="key">{{ viewDialogLocationText }}</div>
                            <div class="key">
                                <span class="building-name" v-if="mutableAssignments[dialogs.view.index].location.building_name !== null">
                                    {{ mutableAssignments[dialogs.view.index].location.building_name }} - 
                                </span>
                                {{ mutableAssignments[dialogs.view.index].location.address }}
                            </div>
                        </div>
                        <!-- Department -->
                        <div class="detail">
                            <div class="key">{{ viewDialogDepartmentText }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].department.name }}</div>
                        </div>
                        <!-- Assignment type -->
                        <div class="detail">
                            <div class="key">{{ viewDialogAssignmentTypeText }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].type.label }}</div>
                        </div>
                        <!-- Function name -->
                        <div class="detail">
                            <div class="key">{{ viewDialogFunctionText }}</div>
                            <div class="val">{{ mutableAssignments[dialogs.view.index].title }}</div>
                        </div>
                        <!-- Duration -->
                        <div class="detail">
                            <div class="key">{{ viewDialogDurationText }}</div>
                            <div class="val">
                                {{ mutableAssignments[dialogs.view.index].start_date }}
                                <span class="duration-second-part" v-if="mutableAssignments[dialogs.view.index].current">
                                    - {{ formPresentText }}
                                </span>
                                <span class="duration-second-part" v-if="!mutableAssignments[dialogs.view.index].current">
                                    - {{ mutableAssignments[dialogs.view.index].end_date }}
                                </span>
                            </div>
                        </div>
                        <!-- Description -->
                        <div class="detail">
                            <div class="key">{{ viewDialogDescriptionText }}</div>
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
                            {{ viewDialogEditText }}
                        </v-btn>
                        <!-- Delete -->
                        <v-btn depressed dark color="red" @click="onClickDelete(dialogs.view.index)">
                            <i class="fas fa-trash-alt"></i>
                            {{ viewDialogDeleteText }}
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
                    <h3 class="dialog-title">{{ createDialogTitleText }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.add.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.add.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Title -->
                    <div class="form-field">
                        <v-text-field
                            :label="formTitleText"
                            v-model="dialogs.add.form.title">
                        </v-text-field>
                    </div>
                    <!-- Description -->
                    <div class="form-field">
                        <v-textarea
                            :label="formDescriptionText"
                            v-model="dialogs.add.form.description">
                        </v-textarea>
                    </div>
                    <!-- Assignment type -->
                    <div class="form-field">
                        <v-select
                            :label="formTypeText"
                            v-model="dialogs.add.form.assignment_type_id"
                            :items="assignmentTypeOptions">
                        </v-select>
                    </div>
                    <!-- Organization -->
                    <div class="form-field">
                        <v-combobox
                            :label="formOrganizationText"
                            v-model="dialogs.add.form.organization"
                            :items="organizationOptions">
                        </v-combobox>
                    </div>
                    <!-- Department -->
                    <div class="form-field" v-if="dialogs.add.form.organization !== ''">
                        <v-combobox
                            :label="formDepartmentText"
                            v-model="dialogs.add.form.department"
                            :items="dialogs.add.organizationDepartmentOptions">
                        </v-combobox>
                    </div>
                    <!-- Location -->
                    <div class="form-field" v-if="dialogs.add.form.organization !== '' && addSelectedOrganizationHasLocations">
                        <v-select
                            :label="formLocationText"
                            v-model="dialogs.add.form.organization_location_id"
                            :items="dialogs.add.organizationLocationOptions">
                        </v-select>
                    </div>
                    <!-- Current -->
                    <div class="form-field">
                        <v-checkbox
                            :label="formCurrentFunctionText"
                            v-model="dialogs.add.form.current">
                        </v-checkbox>
                    </div>
                    <!-- Dates -->
                    <div class="form-fields">
                        <div class="form-field">
                            <datepicker
                                name="start_date"
                                :label="formStartDateText"
                                v-model="dialogs.add.form.start_date">
                            </datepicker>
                        </div>
                        <div class="form-field" v-if="dialogs.add.form.current">
                            {{ formPresentText }}
                        </div>
                        <div class="form-field" v-if="!dialogs.add.form.current">
                            <datepicker
                                name="end_date"
                                :label="formEndDateText"
                                v-model="dialogs.add.form.end_date"
                                :placeholder="formUntilPresentText">
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
                            {{ createDialogCancelText }}
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
                            {{ createDialogSubmitText }}
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
                    <h3 class="dialog-title">{{ updateDialogTitleText }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.edit.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.edit.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Title -->
                    <div class="form-field">
                        <v-text-field
                            :label="formTitleText"
                            v-model="dialogs.edit.form.title">
                        </v-text-field>
                    </div>
                    <!-- Description -->
                    <div class="form-field">
                        <v-textarea
                            :label="formDescriptionText"
                            v-model="dialogs.edit.form.description">
                        </v-textarea>
                    </div>
                    <!-- Assignment type -->
                    <div class="form-field">
                        <v-select
                            :label="formTypeText"
                            v-model="dialogs.edit.form.assignment_type_id"
                            :items="assignmentTypeOptions">
                        </v-select>
                    </div>
                    <!-- Organization -->
                    <div class="form-field">
                        <v-combobox
                            :label="formOrganizationText"
                            v-model="dialogs.edit.form.organization"
                            :items="organizationOptions">
                        </v-combobox>
                    </div>
                    <!-- Department -->
                    <div class="form-field" v-if="dialogs.edit.form.organization !== ''">
                        <v-combobox
                            :label="formDepartmentText"
                            v-model="dialogs.edit.form.department"
                            :items="dialogs.edit.organizationDepartmentOptions">
                        </v-combobox>
                    </div>
                    <!-- Location -->
                    <div class="form-field" v-if="dialogs.edit.form.organization !== '' && editSelectedOrganizationHasLocations">
                        <v-select
                            :label="formLocationText"
                            v-model="dialogs.edit.form.organization_location_id"
                            :items="dialogs.edit.organizationLocationOptions">
                        </v-select>
                    </div>
                    <!-- Current -->
                    <div class="form-field">
                        <v-checkbox
                            :label="formCurrentFunctionText"
                            v-model="dialogs.edit.form.current">
                        </v-checkbox>
                    </div>
                    <!-- Dates -->
                    <div class="form-fields">
                        <div class="form-field">
                            <datepicker
                                name="start_date"
                                :label="formStartDateText"
                                v-model="dialogs.edit.form.start_date">
                            </datepicker>
                        </div>
                        <div class="form-field" v-if="dialogs.edit.form.current">
                            {{ formPresentText }}
                        </div>
                        <div class="form-field" v-if="!dialogs.edit.form.current">
                            <datepicker
                                name="end_date"
                                :label="formEndDateText"
                                v-model="dialogs.edit.form.end_date"
                                :placeholder="formUntilPresentText">
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
                            {{ updateDialogCancelText }}
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
                            {{ updateDialogSubmitText }}
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
                    <h3 class="dialog-title">{{ deleteDialogTitleText }}</h3>
                    <!-- Text -->
                    <div class="dialog-text">{{ deleteDialogText }}</div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.delete.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ deleteDialogCancelText }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <v-btn 
                            depressed dark color="red"
                            :loading="dialogs.delete.loading"
                            :disabled="dialogs.delete.loading"
                            @click="onClickConfirmDelete">
                            <i class="fas fa-trash-alt"></i>
                            {{ deleteDialogSubmitText }}
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
            "createApiEndpoint",
            "updateApiEndpoint",
            "deleteApiEndpoint",
            "labelText",
            "noRecordsText",
            "addButtonText",
            "formTitleText",
            "formDescriptionText",
            "formTypeText",
            "formOrganizationText",
            "formDepartmentText",
            "formLocationText",
            "formCurrentFunctionText",
            "formStartDateText",
            "formPresentText",
            "formEndDateText",
            "formUntilPresentText",
            "createDialogTitleText",
            "createDialogCancelText",
            "createDialogSubmitText",
            "viewDialogTitleText",
            "viewDialogMinistryText",
            "viewDialogOrganizationText",
            "viewDialogDepartmentText",
            "viewDialogLocationText",
            "viewDialogAssignmentTypeText",
            "viewDialogFunctionText",
            "viewDialogDurationText",
            "viewDialogDescriptionText",
            "viewDialogEditText",
            "viewDialogDeleteText",
            "updateDialogTitleText",
            "updateDialogCancelText",
            "updateDialogSubmitText",
            "deleteDialogTitleText",
            "deleteDialogText",
            "deleteDialogCancelText",
            "deleteDialogSubmitText",
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
                        organization_location_id: 0,
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
                        organization_location_id: 0,
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
                console.log(this.tag+" create api endpoint: ", this.createApiEndpoint);
                console.log(this.tag+" update api endpoint: ", this.updateApiEndpoint);
                console.log(this.tag+" delete api endpoint: ", this.deleteApiEndpoint);
                console.log(this.tag+" label text: ", this.labelText);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                console.log(this.tag+" add button text: ", this.addButtonText);
                console.log(this.tag+" form title text: ", this.formTitleText);
                console.log(this.tag+" form description text: ", this.formDescriptionText);
                console.log(this.tag+" form type text: ", this.formTypeText);
                console.log(this.tag+" form organization text: ", this.formOrganizationText);
                console.log(this.tag+" form department text: ", this.formDepartmentText);
                console.log(this.tag+" form location text: ", this.formLocationText);
                console.log(this.tag+" form current function text: ", this.formCurrentFunctionText);
                console.log(this.tag+" form start date text: ", this.formStartDateText);
                console.log(this.tag+" form present text: ", this.formPresentText);
                console.log(this.tag+" form end date text: ", this.formEndDateText);
                console.log(this.tag+" form until present text: ", this.formUntilPresentText);
                console.log(this.tag+" create dialog title text: ", this.createDialogTitle);
                console.log(this.tag+" create dialog cancel text: ", this.createDialogCancelText);
                console.log(this.tag+" create dialog submit text: ", this.createDialogSubmitText);
                console.log(this.tag+" view dialog title text: ", this.viewDialogTitleText);
                console.log(this.tag+" view dialog ministry text: ", this.viewDialogMinistryText);
                console.log(this.tag+" view dialog organization text: ", this.viewDialogOrganizationText);
                console.log(this.tag+" view dialog department text: ", this.viewDialogDepartmentText);
                console.log(this.tag+" view dialog location text: ", this.viewDialogLocationText);
                console.log(this.tag+" view dialog assignment type text: ", this.viewDialogAssignmentTypeText);
                console.log(this.tag+" view dialog function text: ", this.viewDialogFunctionText);
                console.log(this.tag+" view dialog duration text: ", this.viewDialogDurationText);
                console.log(this.tag+" view dialog description text: ", this.viewDialogDescriptionText);
                console.log(this.tag+" view dialog edit text: ", this.viewDialogEditText);
                console.log(this.tag+" view dialog delete text: ", this.viewDialogDeleteText);
                console.log(this.tag+" update dialog title text: ", this.updateDialogTitle);
                console.log(this.tag+" update dialog cancel text: ", this.updateDialogCancelText);
                console.log(this.tag+" update dialog submit text: ", this.updateDialogSubmitText);
                console.log(this.tag+" delete dialog title text: ", this.deleteDialogTitle);
                console.log(this.tag+" delete dialog text: ", this.deleteDialogText);
                console.log(this.tag+" delete dialog cancel text: ", this.deleteDialogCancelText);
                console.log(this.tag+" delete dialog submit text: ", this.deleteDialogSubmitText);
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
            generateOrganizationDepartmentOptions(type) {
                console.log("GENERATING ORG DEPARTMENT OPTIONS");
                if (this.departments !== undefined && this.departments !== null && this.departments.length > 0) {
                    if (type === "add") {
                        console.log("ADD");
                        if (this.addSelectedOrganization) {
                            console.log(this.addSelectedOrganization);
                            this.dialogs.add.organizationDepartmentOptions = [];
                            for (let i = 0; i < this.departments.length; i++) {
                                if (this.departments[i].organization_id === this.addSelectedOrganization.id) {
                                    this.dialogs.add.organizationDepartmentOptions.push(this.departments[i].name);
                                }
                            }
                            console.log(this.dialogs.add.organizationDepartmentOptions);
                        }
                    } else {
                        console.log("EDIT");
                        if (this.editSelectedOrganization) {
                            console.log(this.editSelectedOrganization);
                            this.dialogs.edit.organizationDepartmentOptions = [];
                            for (let i = 0; i < this.departments.length; i++) {
                                if (this.departments[i].organization_id === this.editSelectedOrganization.id) {
                                    this.dialogs.edit.organizationDepartmentOptions.push(this.departments[i].name);
                                }
                            }
                            console.log(this.dialogs.edit.organizationDepartmentOptions);
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
                                    this.dialogs.add.organizationLocationOptions.push({
                                        text: this.organizationLocations[i].building_name === null ? this.organizationLocations[i].address : this.organizationLocations[i].building_name+" - "+this.organizationLocations[i].address,
                                        value: this.organizationLocations[i].id,
                                    });
                                }
                            }
                        }
                    } else {
                        this.dialogs.edit.organizationLocationOptions = [];
                        if (this.editSelectedOrganization) {
                            this.dialogs.edit.organizationLocationOptions = [];
                            for (let i = 0; i < this.organizationLocations.length; i++) {
                                if (this.organizationLocations[i].organization_id === this.editSelectedOrganization.id) {
                                    this.dialogs.edit.organizationLocationOptions.push({
                                        text: this.organizationLocations[i].building_name === null ? this.organizationLocations[i].address : this.organizationLocations[i].building_name+" - "+this.organizationLocations[i].address,
                                        value: this.organizationLocations[i].id,
                                    });
                                }
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
                this.dialogs.add.show = true;
            },
            onClickConfirmAdd() {
                this.dialogs.add.loading = true;
                let payload = new FormData();
                payload.append("assignment_type_id", this.dialogs.add.form.assignment_type_id);
                payload.append("organization", this.dialogs.add.form.organization);
                payload.append("organization_location_id", this.dialogs.add.form.organization_location_id);
                payload.append("department", this.dialogs.add.form.department);
                payload.append("title", this.dialogs.add.form.title);
                payload.append("description", this.dialogs.add.form.description);
                payload.append("current", this.dialogs.add.form.current);
                payload.append("start_date", this.dialogs.add.form.start_date);
                payload.append("end_date", this.dialogs.add.form.end_date);
                this.axios.post(this.createApiEndpoint, payload)
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
                            this.dialogs.add.form.organization_location_id = 0;
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
                this.dialogs.edit.show = true;
                this.dialogs.edit.form.assignment_type_id = this.mutableAssignments[index].assignment_type_id;
                this.dialogs.edit.form.organization = this.mutableAssignments[index].organization.name;
                this.dialogs.edit.form.organization_department_id = this.mutableAssignments[index].organization_department_id;
                this.dialogs.edit.form.organization_location_id = this.mutableAssignments[index].organization_location_id;
                this.dialogs.edit.form.department = this.mutableAssignments[index].department.name;
                this.dialogs.edit.form.title = this.mutableAssignments[index].title;
                this.dialogs.edit.form.description = this.mutableAssignments[index].description;
                this.dialogs.edit.form.current = this.mutableAssignments[index].current;
                this.dialogs.edit.form.start_date = this.mutableAssignments[index].start_date;
                this.dialogs.edit.form.end_date = this.mutableAssignments[index].end_date;
                this.generateOrganizationDepartmentOptions("edit");
                this.generateOrganizationLocationOptions("edit");
            },
            onClickConfirmEdit() {
                this.dialogs.edit.loading = true;
                let payload =  new FormData();
                payload.append("assignment_id", this.mutableAssignments[this.dialogs.edit.index].id);
                payload.append("assignment_type_id", this.dialogs.edit.form.assignment_type_id);
                payload.append("organization", this.dialogs.edit.form.organization);
                payload.append("organization_location_id", this.dialogs.edit.form.organization_location_id);
                payload.append("department", this.dialogs.edit.form.department);
                payload.append("title", this.dialogs.edit.form.title);
                payload.append("description", this.dialogs.edit.form.description);
                payload.append("current", this.dialogs.edit.form.current);
                payload.append("start_date", this.dialogs.edit.form.start_date);
                payload.append("end_date", this.dialogs.edit.form.end_date);
                this.axios.post(this.updateApiEndpoint, payload)
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
                this.axios.post(this.deleteApiEndpoint, payload)
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