<template>
    <div id="project-form__wrapper">

        <!-- Ownership & Current state -->
        <div id="top-sections">
            <div class="top-section">

                <!-- Ownership fields -->
                <h2 class="content-card__title">{{ strings.ownership_title }}</h2>
                <h3 class="content-card__description">{{ strings.ownership_description }}</h3>
                <div class="content-card elevation-1 mb">
                    <div class="content-card__content">
                        
                        <!-- Ministry -->
                        <div class="form-field">
                            <v-select
                                :label="strings.ministry"
                                v-model="form.ministry_id"
                                :items="ministryOptions"
                                :errors="hasErrors('ministry_id')"
                                :error-messages="getErrors('ministry_id')">
                            </v-select>
                            <input type="hidden" name="ministry_id" :value="form.ministry_id">
                        </div>

                        <!-- Organization -->
                        <div class="form-field" v-if="form.ministry_id > 0">
                            <v-select
                                v-model="form.organization_id"
                                :label="strings.organization"
                                :items="organizationOptions"
                                :errors="hasErrors('organization_id')"
                                :error-messages="getErrors('organization_id')">
                            </v-select>
                        </div>
                        <input type="hidden" name="organization_id" :value="form.organization_id">

                        <!-- Department -->
                        <div class="form-field mb-0" v-if="form.organization_id > 0">
                            <v-combobox
                                v-model="form.department"
                                :label="strings.department"
                                :items="departmentOptions"
                                :placeholder="strings.optional"
                                :errors="hasErrors('department')"
                                :error-messages="getErrors('department')">
                            </v-combobox>
                        </div>
                        <input type="hidden" name="department" :value="form.department">
                        
                    </div>
                </div>

            </div>
            <div class="top-section">

                <!-- Current state fields -->
                <h2 class="content-card__title">{{ strings.status_title }}</h2>
                <h3 class="content-card__description">{{ strings.status_description }}</h3>
                <div class="content-card elevation-1 mb">
                    <div class="content-card__content">

                        <!-- Status -->
                        <div class="form-field mb-0">
                            <v-select
                                :label="strings.status"
                                :items="statusOptions"
                                v-model="form.project_status_id"
                                :errors="hasErrors('project_status_id')"
                                :error-messages="getErrors('project_status_id')">
                            </v-select>
                            <input type="hidden" name="project_status_id" :value="form.project_status_id">
                        </div>

                        <!-- Phase -->
                        <div class="form-field mb-0">
                            <v-combobox
                                :label="strings.project_phase"
                                :items="phaseOptions"
                                v-model="form.project_phase"
                                :errors="hasErrors('project_phase')"
                                :error-messages="getErrors('project_phase')">
                            </v-combobox>
                            <input type="hidden" name="project_phase" :value="form.project_phase">
                        </div>
                        
                    </div>
                </div>

            </div>
        </div>

        <!-- General properties -->
        <h2 class="content-card__title">{{ strings.general_title }}</h2>
        <h3 class="content-card__description">{{ strings.general_description }}</h3>
        <div class="content-card elevation-1 mb">
            <div class="content-card__content">

                <!-- Category -->
                <div class="form-field">
                    <v-combobox
                        :label="strings.category+'*'"
                        :items="categoryOptions"
                        v-model="form.project_category"
                        :errors="hasErrors('project_category')"
                        :error-messages="getErrors('project_category')">
                    </v-combobox>
                    <input type="hidden" name="project_category" :value="form.project_category">
                </div> 

                <!-- Title -->
                <div class="form-field">
                    <v-text-field 
                        name="title" 
                        :label="strings.title+'*'"
                        :placeholder="strings.title_hint"
                        v-model="form.title" 
                        :errors="hasErrors('title')" 
                        :error-messages="getErrors('title')">
                    </v-text-field>
                </div>

                <!-- Slogan -->
                <div class="form-fields">
                    <div class="form-field">
                        <v-text-field
                            name="slogan_nl"
                            :label="strings.slogan+' ('+strings.nl+')'"
                            :placeholder="strings.slogan_hint"
                            v-model="form.slogan.nl"
                            :errors="hasErrors('slogan_nl')"
                            :error-messages="getErrors('slogan_nl')">
                        </v-text-field>
                    </div>
                    <div class="form-field">
                        <v-text-field
                            name="slogan_en"
                            :label="strings.slogan+' ('+strings.en+')'"
                            :placeholder="strings.slogan_hint"
                            v-model="form.slogan.en"
                            :errors="hasErrors('slogan_en')"
                            :error-messages="getErrors('slogan_en')">
                        </v-text-field>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-fields">
                    <div class="form-field">
                        <v-textarea
                            name="description_nl" 
                            :label="strings.description+' ('+strings.nl+')*'"
                            :placeholder="strings.description_hint"
                            v-model="form.description.nl" 
                            :errors="hasErrors('description_nl')" 
                            :error-messages="getErrors('description_nl')">
                        </v-textarea>
                    </div>
                    <div class="form-field">
                        <v-textarea
                            name="description_en" 
                            :label="strings.description+' ('+strings.en+')*'"
                            :placeholder="strings.description_hint"
                            v-model="form.description.en" 
                            :errors="hasErrors('description_en')" 
                            :error-messages="getErrors('description_en')">
                        </v-textarea>
                    </div>
                </div>

                <!-- Tags -->
                <div class="form-field">
                    <v-combobox
                        multiple
                        :label="strings.tags"
                        :placeholder="strings.tags_hint"
                        v-model="form.tags"
                        :items="tagOptions"
                        :errors="hasErrors('tags')"
                        :error-messages="getErrors('tags')">
                    </v-combobox>
                    <input type="hidden" name="tags" :value="encodedTags">
                </div>

                <!-- Has budget -->
                <div class="form-field checkbox">
                    <v-checkbox
                        hide-details
                        :label="strings.has_budget"
                        v-model="form.has_budget">
                    </v-checkbox>
                </div>

                <!-- Budget -->
                <div class="form-field" v-if="form.has_budget">
                    <v-text-field
                        prefix="€"
                        v-model="form.budget"
                        :label="strings.budget"
                        :errors="hasErrors('budget')"
                        :error-messages="getErrors('budget')">
                    </v-text-field>
                </div>
                <input type="hidden" name="budget" :value="form.budget">

                <!-- Has deadline -->
                <div class="form-field checkbox">
                    <v-checkbox
                        hide-details
                        :label="strings.has_deadline"
                        v-model="form.has_deadline">
                    </v-checkbox>
                    <input type="hidden" name="has_deadline" :value="form.has_deadline">
                </div>

                <!-- Start & End date -->
                <div class="form-fields">
                    <!-- Starts at -->
                    <div class="form-field">
                        <datepicker
                            name="starts_at"
                            :label="strings.start_date+'*'"
                            v-model="form.starts_at"
                            :errors="hasErrors('starts_at')"
                            :error-messages="getErrors('starts_at')">
                        </datepicker>
                    </div>
                    <!-- Ends at -->
                    <div class="form-field" v-if="form.has_deadline">
                        <datepicker
                            name="ends_at"
                            :label="strings.deadline+'*'"
                            v-model="form.ends_at"
                            :errors="hasErrors('ends_at')"
                            :error-messages="getErrors('ends_at')">
                        </datepicker>
                    </div>
                </div>

                <!-- Project code -->
                <div class="form-field mb-0 mt-10">
                    <v-text-field
                        name="project_code"
                        :label="strings.project_code"
                        v-model="form.project_code"
                        :placeholder="strings.optional"
                        :errors="hasErrors('project_code')"
                        :error-messages="getErrors('project_code')">
                    </v-text-field>
                </div>

            </div>
        </div>

        <!-- Design -->
        <h2 class="content-card__title">{{ strings.formatting_title }}</h2>
        <h3 class="content-card__description">{{ strings.formatting_description }}</h3>
        <div class="content-card elevation-1 mb">
            <div class="content-card__content">

                <!-- Header image -->
                <div class="image-field" :class="{ 'has-errors': hasErrors('header_image') }">
                    <div class="image-field__label">{{ strings.header_image }}</div>
                    <div class="image-field__image-wrapper" v-if="hasProject && projectHasImage">
                        <img class="image-field__image" :src="project.header_image_url">
                    </div>
                    <div class="image-field__image-wrapper" v-if="!hasProject && defaultHeaderImageUrl !== undefined && defaultHeaderImageUrl !== null">
                        <img class="image-field__image" :src="defaultHeaderImageUrl">
                    </div>
                    <div class="image-field__input">
                        <input type="file" name="header_image">
                    </div>
                    <div class="image-field__errors" v-if="hasErrors('header_image')">
                        <div class="image-field__error" v-for="(error, ei) in getErrors('header_image')" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        <!-- Controls -->
        <div class="page-controls">
            <div class="page-controls__left">
                <v-btn :href="backHref" outlined>
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.back }}
                </v-btn>
            </div>
            <div class="page-controls__right">
                <v-btn type="submit" color="success" large>
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
            "project",
            "projectPhases",
            "projectStatuses",
            "projectCategories",
            "ministries",
            "organizations",
            "departments",
            "skills",
            "tags",
            "errors",
            "oldInput",
            "strings",
            "backHref",
            "locale",
            "createResourceApiEndpoint",
            "updateResourceApiEndpoint",
            "deleteResourceApiEndpoint",
            "defaultHeaderImageUrl",
        ],
        data: () => ({
            tag: "[project-form]",
            categoryOptions: [],
            ministryOptions: [],
            organizationOptions: [],
            departmentOptions: [],
            statusOptions: [],
            phaseOptions: [],
            tagOptions: [],
            form: {
                project_status_id: 0,
                project_category: "",
                project_phase: "",
                ministry_id: 0,
                organization_id: 0,
                department: "",
                title: "",
                slogan: {
                    nl: "",
                    en: "",
                },
                description: {
                    nl: "",
                    en: "",
                },
                tags: [],
                starts_at: "",
                ends_at: "",
                has_deadline: false,
                has_budget: false,
                budget: 0,
            },
        }),
        computed: {
            hasProject() {
                return this.project !== undefined && this.project !== null && this.project !== "";
            },
            projectHasImage() {
                return this.hasProject && this.project.header_image_url !== null && this.project.header_image_url !== '';
            },
            encodedTags() {
                return JSON.stringify(this.form.tags);
            },
        },
        watch: {
            "form.ministry_id": {
                deep: true,
                handler: function() {
                    this.generateOrganizationOptions();
                }
            },
            "form.organization_id": {
                deep: true,
                handler: function() {
                    this.generateDepartmentOptions();
                }
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" project phases: ", this.projectPhases);
                console.log(this.tag+" project statuses: ", this.projectStatuses);
                console.log(this.tag+" project categories: ", this.projectCategories);
                console.log(this.tag+" ministries: ", this.ministries);
                console.log(this.tag+" organizations: ", this.organizations);
                console.log(this.tag+" departments: ", this.departments);
                console.log(this.tag+" skills: ", this.skills);
                console.log(this.tag+" tags: ", this.tags);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" create resource api endpoint: ", this.createResourceApiEndpoint);
                console.log(this.tag+" update resource api endpoint: ", this.updateResourceApiEndpoint);
                console.log(this.tag+" delete resource api endpoint: ", this.deleteResourceApiEndpoint);
                console.log(this.tag+" back href: ", this.backHref);
                this.generateCategoryOptions();
                this.generateMinistryOptions();
                this.generateStatusOptions();
                this.generatePhaseOptions();
                this.generateTagOptions();
                this.initializeData();
            },
            initializeData() {
                
                // Set default status (to the first available option)
                this.form.project_status_id = this.statusOptions[0].value;

                // If we received a project, load it's data
                if (this.project !== undefined && this.project !== null) {
                    this.form.project_status_id = parseInt(this.project.project_status_id);
                    this.form.project_category = this.project.category.label[this.locale];
                    if (this.project.phase) this.form.project_phase = this.project.phase.label[this.locale];
                    this.form.ministry_id = this.project.ministry_id;
                    this.form.organization_id = this.project.organization_id;
                    if (this.project.department) this.form.department = this.project.department.name[this.locale];
                    this.form.title = this.project.title;
                    this.form.slogan.en = this.project.slogan.en;
                    this.form.slogan.nl = this.project.slogan.nl;
                    this.form.description.en = this.project.description.en;
                    this.form.description.nl = this.project.description.nl;
                    this.form.starts_at = this.project.starts_at;
                    if (this.project.ends_at !== null) {
                        this.form.has_deadline = true;
                        this.form.ends_at = this.project.ends_at;
                    }
                    this.form.has_tasks = this.project.has_tasks;
                    if (this.project.budget !== null && this.project.budget > 0) {
                        this.form.has_budget = true;
                        this.form.budget = this.project.budget;
                    }
                    this.form.project_code = this.project.project_code;
                    if (this.project.tags !== undefined && this.project.tags !== null && this.project.tags.length > 0) {
                        for (let i = 0; i < this.project.tags.length; i++) {
                            this.form.tags.push(this.project.tags[i].name);
                        }
                    }
                }

                // If we received old input, load that data (overwriting whatever is in there)
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.project_phase_id !== null) this.form.project_phase = this.oldInput.project_phase;
                    if (this.oldInput.project_status_id !== null) this.form.project_status_id = parseInt(this.oldInput.project_status_id);
                    if (this.oldInput.project_category !== null) this.form.project_category = this.oldInput.project_category;
                    if (this.oldInput.ministry_id !== null) this.form.ministry_id = parseInt(this.oldInput.ministry_id);
                    if (this.oldInput.organization_id !== null) this.form.organization_id = parseInt(this.oldInput.organization_id);
                    if (this.oldInput.department !== null) this.form.department = this.oldInput.department;
                    if (this.oldInput.title !== null) this.form.title = this.oldInput.title;
                    if (this.oldInput.slogan_nl !== null) this.form.slogan.nl = this.oldInput.slogan_nl;
                    if (this.oldInput.slogan_en !== null) this.form.slogan.en = this.oldInput.slogan_en;
                    if (this.oldInput.description_nl !== null) this.form.description.nl = this.oldInput.description_nl;
                    if (this.oldInput.description_en !== null) this.form.description.en = this.oldInput.description_en;
                    if (this.oldInput.starts_at !== null) this.form.starts_at = this.oldInput.starts_at;
                    if (this.oldInput.ends_at !== null) this.form.ends_at = this.oldInput.ends_at;
                    if (this.oldInput.has_tasks !== null) this.form.has_tasks = this.oldInput.has_tasks === "true" ? true : false;
                    if (this.oldInput.budget !== null) this.form.budget = parseInt(this.oldInput.budget);
                    if (this.oldInput.project_code !== null) this.form.project_code = this.oldInput.project_code;
                    if (this.oldInput.tags !== null) this.form.tags = JSON.parse(this.oldInput.tags);
                }

            },
            generateStatusOptions() {
                if (this.projectStatuses !== undefined && this.projectStatuses !== null && this.projectStatuses.length > 0) {
                    for (let i = 0; i < this.projectStatuses.length; i++) {
                        this.statusOptions.push({
                            text: this.projectStatuses[i].label[this.locale],
                            value: this.projectStatuses[i].id,
                        });
                    }
                } else {
                    this.statusOptions.push({ text: this.strings.no_statuses, value: 0 });
                }
            },
            generateCategoryOptions() {
                if (this.projectCategories !== undefined && this.projectCategories !== null && this.projectCategories.length > 0) {
                    for (let i = 0; i < this.projectCategories.length; i++) {
                        this.categoryOptions.push(this.projectCategories[i].label[this.locale]);
                    }
                }
            },
            generateMinistryOptions() {
                if (this.ministries !== undefined && this.ministries !== null && this.ministries.length > 0) {
                    for (let i = 0; i < this.ministries.length; i++) {
                        this.ministryOptions.push({
                            text: this.ministries[i].name[this.locale],
                            value: this.ministries[i].id,
                        });
                    }
                } else {
                    this.ministryOptions.push({ text: this.strings.no_ministries, value: 0 });
                }
            },
            generateOrganizationOptions() {
                this.organizationOptions = [];
                if (this.form.ministry_id > 0 && this.organizations !== undefined && this.organizations !== null && this.organizations.length > 0) {
                    for (let i = 0; i < this.organizations.length; i++) {
                        if (this.organizations[i].ministry_id === this.form.ministry_id) {
                            this.organizationOptions.push({
                                text: this.organizations[i].name[this.locale],
                                value: this.organizations[i].id,
                            });
                        }
                    }
                }
            },
            generateDepartmentOptions() {
                this.departmentOptions = [];
                if (this.form.organization_id > 0 && this.departments !== undefined && this.departments !== null && this.departments.length > 0) {
                    for (let i = 0; i < this.departments.length; i++) {
                        if (this.departments[i].organization_id === this.form.organization_id) {
                            this.departmentOptions.push(this.departments[i].name[this.locale]);
                        }
                    }
                }
            },
            generatePhaseOptions() {
                if (this.projectPhases !== undefined && this.projectPhases !== null && this.projectPhases.length > 0) {
                    for (let i = 0; i < this.projectPhases.length; i++) {
                        this.phaseOptions.push(this.projectPhases[i].label[this.locale]);
                    }
                }
            },
            generateTagOptions() {
                if (this.tags !== undefined && this.tags !== null && this.tags.length > 0) {
                    for (let i = 0; i < this.tags.length; i++) {
                        this.tagOptions.push(this.tags[i].name);
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
        },
    }
</script>

<style lang="scss">
    #project-form__wrapper {
        #top-sections {
            display: flex;
            flex-direction: row;
            margin: 0 -25px 0 -25px;
            .top-section {
                flex: 1;
                padding: 0 25px;
                box-sizing: border-box;
            }
        }
    }
    #project-form {
        display: flex;
        flex-direction: row;
        #project-form__left {
            flex: 2;
        }
        #project-form__right {
            flex: 0 0 400px;
            margin: 0 0 0 50px;
            .form-field {
                width: 350px;
            }
            .page-controls {
                .page-controls__left {
                    display: none;
                }
            }
        }
    }
    @media (max-width: 760px) {
        #project-form {
            flex-wrap: wrap;
            #project-form__left, #project-form__right {
                flex: 0 0 100%;
            }
            #project-form__left {
                .page-controls {
                    display: none;
                }
            }
            #project-form__right {
                margin: 30px 0 0 0;
                .page-controls {
                    .page-controls__left {
                        display: block;
                    }
                }
            }
        }
    }
</style>