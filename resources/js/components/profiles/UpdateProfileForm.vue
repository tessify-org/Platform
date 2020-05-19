<template>
    <div id="update-profile-form__wrapper">

        <!-- Form -->
        <div id="update-profile-form" class="elevation-1">

            <!-- Name fields -->
            <div class="form-fields">
                <div class="form-field double">
                    <v-text-field 
                        :label="strings.first_name"
                        v-model="form.first_name" 
                        name="first_name"
                        :error="hasErrors('first_name')"
                        :error-messages="getErrors('first_name')">
                    </v-text-field>
                </div>
                <div class="form-field double">
                    <v-text-field 
                        :label="strings.last_name"
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
                        :label="strings.email"
                        v-model="form.email" 
                        name="email"
                        :error="hasErrors('email')"
                        :error-messages="getErrors('email')">
                    </v-text-field>
                </div>
                <div class="form-field">
                    <v-checkbox
                        :label="strings.publicly_display_email"
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
                    :label="strings.phone"
                    v-model="form.phone" 
                    name="phone"
                    :error="hasErrors('phone')"
                    :error-messages="getErrors('phone')">
                </v-text-field>
            </div>

            <!-- Avatar & Header bg-->
            <div class="form-fields">
                <div class="image-field">
                    <div class="image-field__label">{{ strings.avatar }}</div>
                    <div class="image-field__image-wrapper">
                        <img class="image-field__image" :src="upload_avatar.avatar_url">
                    </div>
                    <div class="image-field__input">
                        <input type="file" ref="avatar" @change="onAvatarUpload">
                    </div>
                </div>
                <div class="image-field">
                    <div class="image-field__label">{{ strings.header_bg }}</div>
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
                    :label="strings.headline"
                    v-model="form.headline"
                    name="headline"
                    :errors="hasErrors('headline')"
                    :error-messages="getErrors('headline')">
                </v-text-field>
            </div>

            <!-- Interests -->
            <div class="form-field">
                <v-textarea
                    :label="strings.interests"
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
                    :api-endpoints="apiEndpoints"
                    :strings="strings.assignments"
                    :locale="locale">
                </profile-assignments-field>
            </div>

            <!-- Skills -->
            <div class="form-field">
                <profile-skills-field
                    name="skills"
                    v-model="form.skills"
                    :user="user"
                    :skills="skills"
                    :strings="strings.skills"
                    :locale="locale">
                </profile-skills-field>
            </div>

        </div>

        <!-- Controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn :href="backHref" outlined>
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.back }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn color="success" type="submit" depressed>
                    <i class="far fa-save"></i>
                    {{ strings.save }}
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
            "strings",
            "apiEndpoints",
            "locale",
            "backHref",
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
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" locale: ", this.locale);
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

                this.axios.post(this.apiEndpoints.upload_avatar, payload, headers)
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

                this.axios.post(this.apiEndpoints.upload_header, payload, headers)
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