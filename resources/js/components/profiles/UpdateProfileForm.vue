<template>
    <div id="update-profile-form__wrapper">

        <!-- Form -->
        <div id="update-profile-form" class="elevation-1">

            <!-- Name fields -->
            <div class="form-fields">
                <div class="form-field double">
                    <v-text-field 
                        :label="firstNameText"
                        v-model="form.first_name" 
                        name="first_name"
                        :error="hasErrors('first_name')"
                        :error-messages="getErrors('first_name')">
                    </v-text-field>
                </div>
                <div class="form-field double">
                    <v-text-field 
                        :label="lastNameText"
                        v-model="form.last_name" 
                        name="last_name"
                        :error="hasErrors('last_name')"
                        :error-messages="getErrors('last_name')">
                    </v-text-field>
                </div>
            </div>

            <!-- Email address & publicly display opt-in -->
            <div class="form-fields">
                <div class="form-field">
                    <v-text-field 
                        :label="emailText"
                        v-model="form.email" 
                        name="email"
                        :error="hasErrors('email')"
                        :error-messages="getErrors('email')">
                    </v-text-field>
                </div>
                <div class="form-field">
                    <v-checkbox
                        :label="publiclyDisplayEmailText"
                        v-model="form.publicly_display_email" 
                        :error="hasErrors('publicly_display_email')"
                        :error-messages="getErrors('publicly_display_email')">
                    </v-checkbox>
                    <input type="hidden" name="publicly_display_email" :value="form.publicly_display_email">
                </div>
            </div>

            <!-- Phone number -->
            <div class="form-field">
                <v-text-field 
                    :label="phoneText"
                    v-model="form.phone" 
                    name="phone"
                    :error="hasErrors('phone')"
                    :error-messages="getErrors('phone')">
                </v-text-field>
            </div>

            <!-- Avatar & Header bg-->
            <div class="form-fields">
                <div class="image-field">
                    <div class="image-field__label">{{ avatarText }}</div>
                    <div class="image-field__image-wrapper">
                        <img class="image-field__image" :src="upload_avatar.avatar_url">
                    </div>
                    <div class="image-field__input">
                        <input type="file" ref="avatar" @change="onAvatarUpload">
                    </div>
                </div>
                <div class="image-field">
                    <div class="image-field__label">Header background</div>
                    <div class="image-field__image-wrapper">
                        <img class="image-field__image" :src="upload_header_image.header_image_url">
                    </div>
                    <div class="image-field__input">
                        <input type="file" ref="header" @change="onHeaderImageUpload">
                    </div>
                </div>
            </div>

            <!-- Headline -->
            <div class="form-field">
                <v-text-field
                    :label="headlineText"
                    v-model="form.headline"
                    name="headline"
                    :errors="hasErrors('headline')"
                    :error-messages="getErrors('headline')">
                </v-text-field>
            </div>

            <!-- Interests -->
            <div class="form-field">
                <v-textarea
                    :label="interestsText"
                    v-model="form.interests"
                    name="interests"
                    :errors="hasErrors('interests')"
                    :error-messages="getErrors('interests')">
                </v-textarea>
            </div>

            <!-- Current assignment -->
            <div class="form-field">
                <profile-assignments-field
                    name="assignments"
                    v-model="form.assignments"
                    :user="user"
                    :assignment-types="assignmentTypes"
                    :organizations="organizations"
                    :organization-locations="organizationLocations"
                    :departments="departments"
                    :create-api-endpoint="createAssignmentApiEndpoint"
                    :update-api-endpoint="updateAssignmentApiEndpoint"
                    :delete-api-endpoint="deleteAssignmentApiEndpoint"
                    :label-text="assignmentsLabelText"
                    :no-records-text="assignmentsNoRecordsText"
                    :add-button-text="assignmentsAddButtonText"
                    :form-title-text="assignmentsFormTitleText"
                    :form-description-text="assignmentsFormDescriptionText"
                    :form-type-text="assignmentsFormTypeText"
                    :form-organization-text="assignmentsFormOrganizationText"
                    :form-department-text="assignmentsFormDepartmentText"
                    :form-location-text="assignmentsFormLocationText"
                    :form-current-function-text="assignmentsFormCurrentFunctionText"
                    :form-start-date-text="assignmentsFormStartDateText"
                    :form-end-date-text="assignmentsFormEndDateText"
                    :form-present-text="assignmentsFormPresentText"
                    :form-until-present-text="assignmentsFormUntilPresentText"
                    :create-dialog-title-text="assignmentsCreateDialogTitleText"
                    :create-dialog-cancel-text="assignmentsCreateDialogCancelText"
                    :create-dialog-submit-text="assignmentsCreateDialogSubmitText"
                    :view-dialog-title-text="assignmentsViewDialogTitleText"
                    :view-dialog-ministry-text="assignmentsViewDialogMinistryText"
                    :view-dialog-organization-text="assignmentsViewDialogOrganizationText"
                    :view-dialog-department-text="assignmentsViewDialogDepartmentText"
                    :view-dialog-location-text="assignmentsViewDialogLocationText"
                    :view-dialog-assignment-type-text="assignmentsViewDialogAssignmentTypeText"
                    :view-dialog-function-text="assignmentsViewDialogFunctionText"
                    :view-dialog-duration-text="assignmentsViewDialogDurationText"
                    :view-dialog-description-text="assignmentsViewDialogDescriptionText"
                    :view-dialog-edit-text="assignmentsViewDialogEditText"
                    :view-dialog-delete-text="assignmentsViewDialogDeleteText"
                    :update-dialog-title-text="assignmentsUpdateDialogTitleText"
                    :update-dialog-cancel-text="assignmentsUpdateDialogCancelText"
                    :update-dialog-submit-text="assignmentsUpdateDialogSubmitText"
                    :delete-dialog-title-text="assignmentsDeleteDialogTitleText"
                    :delete-dialog-text="assignmentsDeleteDialogText"
                    :delete-dialog-cancel-text="assignmentsDeleteDialogCancelText"
                    :delete-dialog-submit-text="assignmentsDeleteDialogSubmitText">
                </profile-assignments-field>
            </div>

            <!-- Skills -->
            <div class="form-field">
                <profile-skills-field
                    name="skills"
                    v-model="form.skills"
                    :user="user"
                    :skills="skills"
                    :label-text="skillsLabelText"
                    :no-records-text="skillsNoRecordsText"
                    :add-button-text="skillsAddButtonText"
                    :form-skill-text="skillsFormSkillText"
                    :form-mastery-text="skillsFormMasteryText"
                    :form-description-text="skillsFormDescriptionText"
                    :view-dialog-title-text="skillsViewDialogTitleText"
                    :view-dialog-skill-text="skillsViewDialogSkillText"
                    :view-dialog-mastery-text="skillsViewDialogMasteryText"
                    :view-dialog-description-text="skillsViewDialogDescriptionText"
                    :view-dialog-edit-text="skillsViewDialogEditText"
                    :view-dialog-delete-text="skillsViewDialogDeleteText"
                    :create-dialog-title-text="skillsCreateDialogTitleText"
                    :create-dialog-cancel-text="skillsCreateDialogCancelText"
                    :create-dialog-submit-text="skillsCreateDialogSubmitText"
                    :update-dialog-title-text="skillsUpdateDialogTitleText"
                    :update-dialog-cancel-text="skillsUpdateDialogCancelText"
                    :update-dialog-submit-text="skillsUpdateDialogSubmitText"
                    :delete-dialog-title-text="skillsDeleteDialogTitleText"
                    :delete-dialog-text="skillsDeleteDialogText"
                    :delete-dialog-cancel-text="skillsDeleteDialogCancelText"
                    :delete-dialog-submit-text="skillsDeleteDialogSubmitText">
                </profile-skills-field>
            </div>

        </div>

        <!-- Controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn :href="backHref" outlined>
                    <i class="fas fa-arrow-left"></i>
                    {{ backText }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn color="success" type="submit" depressed>
                    <i class="far fa-save"></i>
                    {{ saveText }}
                </v-btn>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        props: [
            "user",
            "skills",
            "assignmentTypes",
            "organizations",
            "organizationLocations",
            "departments",
            "errors",
            "oldInput",
            "firstNameText",
            "lastNameText",
            "headlineText",
            "emailText",
            "publiclyDisplayEmailText",
            "phoneText",
            "avatarText",
            "interestsText",
            "assignmentsText",
            "backHref",
            "backText",
            "saveText",
            "createAssignmentApiEndpoint",
            "updateAssignmentApiEndpoint",
            "deleteAssignmentApiEndpoint",
            "uploadAvatarApiEndpoint",
            "uploadHeaderImageApiEndpoint",
            "assignmentsLabelText",
            "assignmentsNoRecordsText",
            "assignmentsAddButtonText",
            "assignmentsFormTitleText",
            "assignmentsFormDescriptionText",
            "assignmentsFormTypeText",
            "assignmentsFormOrganizationText",
            "assignmentsFormDepartmentText",
            "assignmentsFormLocationText",
            "assignmentsFormCurrentFunctionText",
            "assignmentsFormStartDateText",
            "assignmentsFormPresentText",
            "assignmentsFormEndDateText",
            "assignmentsFormUntilPresentText",
            "assignmentsCreateDialogTitleText",
            "assignmentsCreateDialogCancelText",
            "assignmentsCreateDialogSubmitText",
            "assignmentsViewDialogTitleText",
            "assignmentsViewDialogMinistryText",
            "assignmentsViewDialogOrganizationText",
            "assignmentsViewDialogDepartmentText",
            "assignmentsViewDialogLocationText",
            "assignmentsViewDialogAssignmentTypeText",
            "assignmentsViewDialogFunctionText",
            "assignmentsViewDialogDurationText",
            "assignmentsViewDialogDescriptionText",
            "assignmentsViewDialogEditText",
            "assignmentsViewDialogDeleteText",
            "assignmentsUpdateDialogTitleText",
            "assignmentsUpdateDialogCancelText",
            "assignmentsUpdateDialogSubmitText",
            "assignmentsDeleteDialogTitleText",
            "assignmentsDeleteDialogText",
            "assignmentsDeleteDialogCancelText",
            "assignmentsDeleteDialogSubmitText",
            "skillsLabelText",
            "skillsNoRecordsText",
            "skillsAddButtonText",
            "skillsFormSkillText",
            "skillsFormMasteryText",
            "skillsFormDescriptionText",
            "skillsViewDialogTitleText",
            "skillsViewDialogSkillText",
            "skillsViewDialogMasteryText",
            "skillsViewDialogDescriptionText",
            "skillsViewDialogEditText",
            "skillsViewDialogDeleteText",
            "skillsCreateDialogTitleText",
            "skillsCreateDialogCancelText",
            "skillsCreateDialogSubmitText",
            "skillsUpdateDialogTitleText",
            "skillsUpdateDialogCancelText",
            "skillsUpdateDialogSubmitText",
            "skillsDeleteDialogTitleText",
            "skillsDeleteDialogText",
            "skillsDeleteDialogCancelText",
            "skillsDeleteDialogSubmitText",
        ],
        data: () => ({
            tag: "[update-profile-form]",
            form: {
                first_name: "",
                last_name: "",
                email: "",
                publicly_display_email: false,
                phone: "",
                headline: "",
                interests: "",
                current_assignment_id: 0,
            },
            upload_avatar: {
                loading: false,
                avatar_url: "",
            },
            upload_header_image: {
                loading: false,
                header_image_url: "",
            }
        }),
        computed: {
            currentAssignmentId() {
                return 0;
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" user: ", this.user);
                console.log(this.tag+" skills: ", this.skills);
                console.log(this.tag+" assignment types: ", this.assignmentTypes);
                console.log(this.tag+" organizations: ", this.organizations);
                console.log(this.tag+" organization locations: ", this.organizationLocations);
                console.log(this.tag+" departments: ", this.departments);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" first name text: ", this.firstNameText);
                console.log(this.tag+" last name text: ", this.lastNameText);
                console.log(this.tag+" headline text: ", this.headlineText);
                console.log(this.tag+" interests text: ", this.interestsText);
                console.log(this.tag+" email text: ", this.emailText);
                console.log(this.tag+" publicly display email text: ", this.publiclyDisplayEmailText);
                console.log(this.tag+" phone text: ", this.phoneText);
                console.log(this.tag+" avatar text: ", this.avatarText);
                console.log(this.tag+" interests text: ", this.interestsText);
                console.log(this.tag+" assignments text: ", this.assignmentsText);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" back text: ", this.backText);
                console.log(this.tag+" save text: ", this.saveText);
                console.log(this.tag+" create assignment api endpoint: ", this.createAssignmentApiEndpoint);
                console.log(this.tag+" update assignment api endpoint: ", this.updateAssignmentApiEndpoint);
                console.log(this.tag+" delete assignment api endpoint: ", this.deleteAssignmentApiEndpoint);
                console.log(this.tag+" assignments label text: ", this.assignmentsLabelText);
                console.log(this.tag+" assignments no records text: ", this.assignmentsNoRecordsText);
                console.log(this.tag+" assignments add button text: ", this.assignmentsAddButtonText);
                console.log(this.tag+" assignments label text: ", this.assignmentsLabelText);
                console.log(this.tag+" assignments no records text: ", this.assignmentsNoRecordsText);
                console.log(this.tag+" assignments add button text: ", this.assignmentsAddButtonText);
                console.log(this.tag+" assignments form title text: ", this.assignmentsFormTitleText);
                console.log(this.tag+" assignments form description text: ", this.assignmentsFormDescriptionText);
                console.log(this.tag+" assignments form type text: ", this.assignmentsFormTypeText);
                console.log(this.tag+" assignments form organization text: ", this.assignmentsFormOrganizationText);
                console.log(this.tag+" assignments form department text: ", this.assignmentsFormDepartmentText);
                console.log(this.tag+" assignments form location text: ", this.assignmentsFormLocationText);
                console.log(this.tag+" assignments form current function text: ", this.assignmentsFormCurrentFunctionText);
                console.log(this.tag+" assignments form start date text: ", this.assignmentsFormStartDateText);
                console.log(this.tag+" assignments form present text: ", this.assignmentsFormPresentText);
                console.log(this.tag+" assignments form end date text: ", this.assignmentsFormEndDateText);
                console.log(this.tag+" assignments form until present text: ", this.assignmentsFormUntilPresentText);
                console.log(this.tag+" assignments create dialog title text: ", this.assignmentsCreateDialogTitleText);
                console.log(this.tag+" assignments create dialog cancel text: ", this.assignmentsCreateDialogCancelText);
                console.log(this.tag+" assignments create dialog submit text: ", this.assignmentsCreateDialogSubmitText);
                console.log(this.tag+" assignments view dialog title text: ", this.assignmentsViewDialogTitleText);
                console.log(this.tag+" assignments view dialog ministry text: ", this.assignmentsViewDialogMinistryText);
                console.log(this.tag+" assignments view dialog organization text: ", this.assignmentsViewDialogOrganizationText);
                console.log(this.tag+" assignments view dialog department text: ", this.assignmentsViewDialogDepartmentText);
                console.log(this.tag+" assignments view dialog location text: ", this.assignmentsViewDialogLocationText);
                console.log(this.tag+" assignments view dialog assignment type text: ", this.assignmentsViewDialogAssignmentTypeText);
                console.log(this.tag+" assignments view dialog function text: ", this.assignmentsViewDialogFunctionText);
                console.log(this.tag+" assignments view dialog duration text: ", this.assignmentsViewDialogDurationText);
                console.log(this.tag+" assignments view dialog descripion text: ", this.assignmentsViewDialogDescriptionText);
                console.log(this.tag+" assignments view dialog edit text: ", this.assignmentsViewDialogEditText);
                console.log(this.tag+" assignments view dialog delete text: ", this.assignmentsViewDialogDeleteText);
                console.log(this.tag+" assignments update dialog title text: ", this.assignmentsUpdateDialogTitleText);
                console.log(this.tag+" assignments update dialog cancel text: ", this.assignmentsUpdateDialogCancelText);
                console.log(this.tag+" assignments update dialog submit text: ", this.assignmentsUpdateDialogSubmitText);
                console.log(this.tag+" assignments delete dialog title text: ", this.assignmentsDeleteDialogTitleText);
                console.log(this.tag+" assignments delete dialog text: ", this.assignmentsDeleteDialogText);
                console.log(this.tag+" assignments delete dialog cancel text: ", this.assignmentsDeleteDialogCancelText);
                console.log(this.tag+" assignments delete dialog submit text: ", this.assignmentsDeleteDialogSubmitText);
                this.initializeData();
            },
            initializeData() {
                if (this.user !== undefined && this.user !== null) {
                    this.form.first_name = this.user.first_name;
                    this.form.last_name = this.user.last_name;
                    this.form.email = this.user.email;
                    this.form.publicly_display_email = this.user.publicly_display_email;
                    this.form.phone = this.user.phone;
                    this.form.headline = this.user.headline;
                    this.form.interests = this.user.interests;
                    if (this.user.assignments !== undefined && this.user.assignments !== null && this.user.assignments.length > 0) {
                        for (let i = 0; i < this.user.assignments.length; i++) {
                            if (this.user.assignments[i].current) {
                                this.form.current_assignment_id = this.user.assignments[i].id;
                            }
                        }
                    }
                    this.upload_avatar.avatar_url = this.user.avatar_url;
                    this.upload_avatar.header_image_url = this.user.header_bg_url;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.first_name !== null) this.form.first_name = this.oldInput.first_name;
                    if (this.oldInput.last_name !== null) this.form.last_name = this.oldInput.last_name;
                    if (this.oldInput.email !== null) this.form.email = this.oldInput.email;
                    if (this.oldInput.publicly_display_email !== null) this.form.publicly_display_email = this.oldInput.publicly_display_email;
                    if (this.oldInput.phone !== null) this.form.phone = this.oldInput.phone;
                    if (this.oldInput.headline !== null) this.form.headline = this.oldInput.headline;
                    if (this.oldInput.interests !== null) this.form.interests = this.oldInput.interests;
                    if (this.oldInput.current_assignment_id !== null) this.form.current_assignment_id = parseInt(this.oldInput.current_assignment_id);
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
            onAvatarUpload(e) {
                console.log(this.tag+" avatar selected", e);

                this.upload_avatar.loading = true;

                let payload = new FormData();
                payload.append("avatar", this.$refs.avatar.files[0]);
                
                let headers = { headers: { 'Content-Type': 'multipart/form-data' } };

                this.axios.post(this.uploadAvatarApiEndpoint, payload, headers)
                    .then(function(response) {
                        console.log(this.tag+" request succeeded, response: ", response);
                        this.upload_avatar.avatar_url = response.data.image_url;
                        this.upload_avatar.loading = false;
                    }.bind(this))
                    .catch(function(error) {
                        console.warn(this.tag+" request failed, error: ", error);
                        this.upload_avatar.loading = false;
                    }.bind(this));

            },
            onHeaderImageUpload(e) {
                console.log(this.tag+" header image selected", e);

                this.upload_header_image.loading = true;

                let payload = new FormData();
                payload.append("header_image", this.$refs.header.files[0]);
                
                let headers = { headers: { 'Content-Type': 'multipart/form-data' } };

                this.axios.post(this.uploadHeaderImageApiEndpoint, payload, headers)
                    .then(function(response) {
                        console.log(this.tag+" request succeeded, response: ", response);
                        this.upload_header_image.header_image_url = response.data.image_url;
                        this.upload_header_image.loading = false;
                    }.bind(this))
                    .catch(function(error) {
                        console.warn(this.tag+" request failed, error: ", error);
                        this.upload_header_image.loading = false;
                    }.bind(this));
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #update-profile-form__wrapper {
        #update-profile-form {
            padding: 25px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #fff;
        }
    }
    @media (max-width: 490px) {
        #update-profile-form__wrapper {
            #update-profile-form {
                .form-fields {
                    flex-direction: column;
                }
            }
        }
    }
</style>