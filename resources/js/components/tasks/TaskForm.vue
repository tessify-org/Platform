<template>
    <div id="task-form__wrapper">

        <!-- Parent sections -->
        <div id="parent-sections">
            <div class="parent-section">

                <!-- Ownership -->
                <h2 class="content-card__title">{{ strings.ownership_title }}</h2>
                <h3 class="content-card__description">{{ strings.ownership_description }}</h3>
                <div class="content-card elevation-1 mb">
                    <div class="content-card__content">

                        <!-- Parent type -->
                        <div class="form-field" :class="{ 'mb-0': form.parent_type === '' }">
                            <v-select
                                v-model="form.parent_type"
                                :label="strings.parent_type"
                                :items="parentTypeOptions"
                                :errors="hasErrors('parent_type')"
                                :error-messages="getErrors('parent_type')">
                            </v-select>
                            <input type="hidden" name="parent_type" :value="form.parent_type">
                        </div>

                        <!-- Parent organization -->
                        <div class="form-field" v-if="form.parent_type === 'organization'">
                            <v-combobox
                                v-model="form.organization"
                                :label="strings.organization"
                                :items="organizationOptions"
                                :errors="hasErrors('organization')"
                                :error-messages="getErrors('organization')">
                            </v-combobox>
                        </div>
                        <input type="hidden" name="organization" :value="form.organization">
                        
                        <!-- Parent organization department -->
                        <div class="form-field mb-0" v-if="form.parent_type === 'organization' && form.organization !== ''">
                            <v-combobox
                                v-model="form.department"
                                :label="strings.department"
                                :items="departmentOptions"
                                :errors="hasErrors('department')"
                                :error-messages="getErrors('department')">
                            </v-combobox>
                        </div>
                        <input type="hidden" name="department" :value="form.department">

                        <!-- Parent group -->
                        <div class="form-field mb-0" v-if="form.parent_type === 'group'">
                            <v-combobox
                                v-model="form.group"
                                :label="strings.group"
                                :items="groupOptions"
                                :errors="hasErrors('group')"
                                :error-messages="getErrors('group')">
                            </v-combobox>
                        </div>
                        <input type="hidden" name="group" :value="form.group">

                    </div>
                </div>
                
            </div>
            <div class="parent-section">

                <!-- Ownership -->
                <h2 class="content-card__title">{{ strings.parent_project_title }}</h2>
                <h3 class="content-card__description">{{ strings.parent_project_description }}</h3>
                <div class="content-card elevation-1 mb">
                    <div class="content-card__content">

                        <!-- Project -->
                        <div class="form-field mb-0">
                            <v-select
                                :label="strings.project"
                                :items="projectOptions"
                                v-model="form.project_id"
                                :errors="hasErrors('project_id')"
                                :error-messages="getErrors('project_id')">
                            </v-select>
                            <input type="hidden" name="project_id" :value="form.project_id > 0 ? form.project_id : null">
                        </div>

                    </div>
                </div>
                
            </div>
        </div>

        <!-- General info -->
        <h2 class="content-card__title">{{ strings.general_title }}</h2>
        <h3 class="content-card__description">{{ strings.general_description }}</h3>
        <div class="content-card elevation-1 mb">
            <div class="content-card__content">

                <!-- Title -->
                <div class="form-fields">
                    <div class="form-field">
                        <v-text-field
                            name="title_nl"
                            :label="strings.title+'* '+strings.nl"
                            v-model="form.title.nl"
                            :errors="hasErrors('title_nl')"
                            :error-messages="getErrors('title_nl')">
                        </v-text-field>
                    </div>
                    <div class="form-field">
                        <v-text-field
                            name="title_en"
                            :label="strings.title+'* '+strings.en"
                            v-model="form.title.en"
                            :errors="hasErrors('title_en')"
                            :error-messages="getErrors('title_nl')">
                        </v-text-field>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-fields">
                    <div class="form-field">
                        <v-textarea
                            name="description_nl"
                            :label="strings.description+' '+strings.nl+'*'"
                            v-model="form.description.nl"
                            :errors="hasErrors('description_nl')"
                            :error-messages="getErrors('description_nl')">
                        </v-textarea>
                    </div>
                    <div class="form-field">
                        <v-textarea
                            name="description_en"
                            :label="strings.description+' '+strings.en+'*'"
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
                        v-model="form.tags"
                        :items="tagOptions"
                        :errors="hasErrors('tags')"
                        :error-messages="getErrors('tags')">
                    </v-combobox>
                    <input type="hidden" name="tags" :value="encodedTags">
                </div>

                <!-- Status, Category & Urgency -->
                <div class="form-fields">

                    <!-- Status -->
                    <div class="form-field" v-if="editing">
                        <v-select
                            :label="strings.status+'*'"
                            :items="statusOptions"
                            v-model="form.task_status_id"
                            :errors="hasErrors('task_status_id')"
                            :error-messages="getErrors('task_status_id')">
                        </v-select>
                        <input type="hidden" name="task_status_id" :value="form.task_status_id">
                    </div>

                    <!-- Category -->
                    <div class="form-field">
                        <v-combobox
                            :label="strings.category+'*'"
                            :items="categoryOptions"
                            v-model="form.task_category"
                            :errors="hasErrors('task_category')"
                            :error-messages="getErrors('task_category')">
                        </v-combobox> 
                        <input type="hidden" name="task_category" :value="form.task_category">
                    </div>

                    
                    <!-- Urgency -->
                    <div class="form-field">
                        <v-select
                            :label="strings.urgency+'*'"
                            v-model="form.urgency"
                            :items="urgencyOptions"
                            :errors="hasErrors('urgency')"
                            :error-messages="getErrors('urgency')">
                        </v-select>
                        <input type="hidden" name="urgency" :value="form.urgency">
                    </div>

                </div>

                <!-- Complexity, Seniority & Estimated hours -->
                <div class="form-fields">

                    <!-- Complexity -->
                    <div class="form-field">
                        <v-select
                            :label="strings.complexity+'*'"
                            :items="complexityOptions"
                            v-model="form.complexity"
                            :errors="hasErrors('complexity')"
                            :error-messages="getErrors('complexity')">
                        </v-select>
                        <input type="hidden" name="complexity" :value="form.complexity">
                    </div>

                    <!-- Seniority -->
                    <div class="form-field">
                        <v-select
                            :label="strings.seniority+'*'"
                            :items="seniorityOptions"
                            v-model="form.task_seniority_id"
                            :errors="hasErrors('task_seniority_id')"
                            :error-messages="getErrors('task_seniority_id')">
                        </v-select>
                        <input type="hidden" name="task_seniority_id" :value="form.task_seniority_id">
                    </div>
                        
                    <!-- Estimated hours -->
                    <div class="form-field">
                        <v-text-field
                            name="estimated_hours"
                            :label="strings.estimated_hours+'*'"
                            v-model="form.estimated_hours"
                            :errors="hasErrors('estimated_hours')"
                            :error-messages="getErrors('estimated_hours')">
                        </v-text-field>
                    </div>

                </div>

                <!-- Realized hours -->
                <div class="form-field" v-if="editing && isCompleted">
                    <v-text-field
                        name="realized_hours"
                        :label="strings.realized_hours"
                        v-model="form.realized_hours"
                        :errors="hasErrors('realized_hours')"
                        :error-messages="getErrors('realized_hours')">
                    </v-text-field>
                </div>

                <!-- Deadline -->
                <div class="form-fields">
                    <div class="form-field checkbox">
                        <v-checkbox
                            hide-details
                            :label="strings.has_deadline"
                            v-model="form.has_deadline">
                        </v-checkbox>
                        <input type="hidden" name="has_deadline" :value="form.has_deadline">
                    </div>
                    <div class="form-field" v-if="form.has_deadline">
                        <datepicker
                            name="ends_at"
                            :label="strings.ends_at+'*'"
                            v-model="form.ends_at"
                            :errors="hasErrors('ends_at')"
                            :error-messages="getErrors('ends_at')">
                        </datepicker>
                    </div>
                </div>

            </div>
        </div>

        <!-- Required skills -->
        <h2 class="content-card__title">{{ strings.skills_title }}</h2>
        <h3 class="content-card__description">{{ strings.skills_description }}</h3>
        <div class="content-card elevation-1 mb">
            <div class="content-card__content">

                <!-- Required skills field -->
                <div class="form-field">
                    <required-skills-field
                        :task="task"
                        :skills="skills"
                        name="required_skills"
                        v-model="form.required_skills"
                        :label-text="strings.required_skills"
                        :strings="strings.required_skills_strings"
                        :locale="locale">
                    </required-skills-field>
                </div>

            </div>
        </div>

        <!-- Design -->
        <h2 class="content-card__title">{{ strings.formatting_title }}</h2>
        <h3 class="content-card__description">{{ strings.formatting_description }}</h3>
        <div class="content-card elevation-1 mb">
            <div class="content-card__content">

                <!-- Header image -->
                <div class="image-field no-margin" :class="{ 'has-errors': hasErrors('header_image') }">
                    <div class="image-field__label">{{ strings.header_image }}</div>
                    <div class="image-field__image-wrapper" v-if="hasTask && taskHasImage">
                        <img class="image-field__image" :src="task.header_image_url">
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
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn :href="backHref" outlined>
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.back }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn type="submit" color="success" class="elevation-1" depressed>
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
            "task",
            "project",
            "projects",
            "skills",
            "organizations",
            "departments",
            "groups",
            "errors",
            "oldInput",
            "statuses",
            "categories",
            "seniorities",
            "tags",
            "backHref",
            "strings",
            "locale",
        ],
        data: () => ({
            tag: "[task-form]",
            parentTypeOptions: [],
            projectOptions: [],
            complexityOptions: [],
            seniorityOptions: [],
            categoryOptions: [],
            urgencyOptions: [],
            statusOptions: [],
            tagOptions: [],
            organizationOptions: [],
            departmentOptions: [],
            groupOptions: [],
            form: {
                project_id: 0,
                parent_type: null,
                task_status_id: 0,
                task_category: "",
                task_seniority_id: 1,
                organization: "",
                department: "",
                title: {
                    nl: "",
                    en: "",
                },
                description: {
                    nl: "",
                    en: "",
                },
                complexity: 1,
                estimated_hours: 0,
                realized_hours: 0,
                required_skills: [],
                urgency: 2,
                tags: [],
                ends_at: "",
                has_deadline: false,
            }
        }),
        computed: {
            editing() {
                return this.task !== undefined && this.task !== null && this.task !== "";
            },
            isCompleted() {
                let status = this.getStatusById(this.form.task_status_id);
                return status && status.name.en === "completed";
            },
            encodedTags() {
                return JSON.stringify(this.form.tags);
            },
            hasTask() {
                return this.task !== undefined && this.task !== null && this.task !== "";
            },
            taskHasImage() {
                return this.hasTask && this.task.header_image_url !== null && this.task.header_image_url !== '';
            },
        },
        watch: {
            "form.organization": {
                deep: true,
                handler: function() {
                    this.generateDepartmentOptions();
                }
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" task: ", this.task);
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" projects: ", this.projects);
                console.log(this.tag+" skills: ", this.skills);
                console.log(this.tag+" ministries: ", this.ministries);
                console.log(this.tag+" organizations: ", this.organizations);
                console.log(this.tag+" departments: ", this.departments);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" categories: ", this.categories);
                console.log(this.tag+" seniorities: ", this.seniorities);
                console.log(this.tag+" tags: ", this.tags);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" locale: ", this.locale);
                this.generateProjectOptions();
                this.generateOrganizationOptions();
                this.generateParentTypeOptions();
                this.generateComplexityOptions();
                this.generateSeniorityOptions();
                this.generateCategoryOptions();
                this.generateUrgencyOptions();
                this.generateStatusOptions();
                this.generateGroupOptions();
                this.generateTagOptions();
                this.initializeData();
            },
            generateParentTypeOptions() {
                this.parentTypeOptions.push({ text: this.strings.parent_type_me, value: null });
                this.parentTypeOptions.push({ text: this.strings.parent_type_organization, value: "organization" });
                this.parentTypeOptions.push({ text: this.strings.parent_type_group, value: "group" });
            },
            generateGroupOptions() {
                if (this.groups !== undefined && this.groups !== null && this.groups.length > 0) {
                    for (let i = 0; i < this.groups.length; i++) {
                        this.groupOptions.push(this.groups[i].name);
                    }
                } else {
                    this.groupOptions.push(this.strings.group_no_groups);
                }
            },
            generateOrganizationOptions() {
                this.organizationOptions = [];
                if (this.organizations !== undefined && this.organizations !== null && this.organizations.length > 0) {
                    for (let i = 0; i < this.organizations.length; i++) {
                        this.organizationOptions.push(this.organizations[i].name[this.locale]);
                    }
                }
            },
            generateDepartmentOptions() {
                this.departmentOptions = [];
                if (this.form.organization !== "") {
                    let organizationId = this.findOrganizationIdByName(this.form.organization);
                    if (organizationId && this.departments !== undefined && this.departments !== null && this.departments.length > 0) {
                        for (let i = 0; i < this.departments.length; i++) {
                            if (this.departments[i].organization_id === organizationId) {
                                this.departmentOptions.push(this.departments[i].name[this.locale]);
                            }
                        }
                    }
                }
            },
            generateProjectOptions() {
                if (this.projects !== undefined && this.projects !== null && this.projects.length > 0) {
                    this.projectOptions.push({ 
                        text: this.strings.dont_associate_with_project, 
                        value: 0
                    });
                    for (let i = 0; i < this.projects.length; i++) {
                        this.projectOptions.push({
                            text: this.projects[i].title,
                            value: this.projects[i].id,
                        });
                    }
                } else {
                    this.projectOptions.push({
                        text: this.strings.no_projects, 
                        value: 0
                    });
                }
            },
            generateComplexityOptions() {
                for (let i = 1; i <= 10; i++) {
                    this.complexityOptions.push({
                        text: i+"/10",
                        value: i
                    });
                }
            },
            generateSeniorityOptions() {
                if (this.seniorities !== undefined && this.seniorities !== null && this.seniorities.length > 0) {
                    for (let i = 0; i < this.seniorities.length; i++) {
                        this.seniorityOptions.push({
                            text: this.seniorities[i].label[this.locale],
                            value: this.seniorities[i].id,
                        });
                    }
                } else {
                    this.seniorityOptions.push({
                        text: this.noSenioritiesText,
                        value: 0,
                    });
                }
            },
            generateCategoryOptions() {
                if (this.categories !== undefined && this.categories !== null && this.categories.length > 0) {
                    for (let i = 0; i < this.categories.length; i++) {
                        this.categoryOptions.push(this.categories[i].name[this.locale]);
                    }
                }
            },
            generateUrgencyOptions() {
                this.urgencyOptions.push({ text: this.strings.urgency_low, value: 1 });
                this.urgencyOptions.push({ text: this.strings.urgency_normal, value: 2 });
                this.urgencyOptions.push({ text: this.strings.urgency_high, value: 3 });
            },
            generateStatusOptions() {
                if (this.statuses !== undefined && this.statuses !== null && this.statuses.length > 0) {
                    for (let i = 0; i < this.statuses.length; i++) {
                        this.statusOptions.push({
                            text: this.statuses[i].label[this.locale],
                            value: this.statuses[i].id,
                        });
                    }
                } else {
                    this.statusOptions.push({
                        text: this.noStatusesText,
                        value: 0
                    });
                }
            },
            generateTagOptions() {
                if (this.tags !== undefined && this.tags !== null && this.tags.length > 0) {
                    for (let i = 0; i < this.tags.length; i++) {
                        this.tagOptions.push(this.tags[i].name);
                    }
                }
            },
            initializeData() {
                if (this.project !== undefined && this.project !== null) {
                    this.form.project_id = this.project.id;
                }
                if (this.task !== undefined && this.task !== null) {
                    this.form.project_id = this.task.project_id;
                    if (this.task.organization_id > 0) {
                        this.form.parent_type = "organization";
                        this.form.organization = this.task.organization.name[this.locale];
                    }
                    if (this.task.department) this.form.department = this.task.department.name[this.locale];
                    if (this.task.group) {
                        this.form.parent_type = "group";
                        this.form.group = this.task.group.name;
                    }
                    this.form.task_status_id = this.task.task_status_id;
                    this.form.task_category = this.task.category.name[this.locale];
                    this.form.task_seniority_id = this.task.task_seniority_id;
                    this.form.title.nl = this.task.title.nl;
                    this.form.title.en = this.task.title.en;
                    this.form.description.nl = this.task.description.nl;
                    this.form.description.en = this.task.description.en;
                    this.form.complexity = this.task.complexity;
                    this.form.estimated_hours = this.task.estimated_hours;
                    this.form.realized_hours = this.task.realized_hours;
                    this.form.urgency = this.task.urgency;
                    // if (this.task.skills !== undefined && this.task.skills !== null && this.task.skills.length > 0) {
                    //     for (let i = 0; i < this.task.skills.length; i++) {

                    //     }
                    // }
                    if (this.task.tags !== undefined && this.task.tags !== null && this.tags.length > 0) {
                        for (let i = 0; i < this.task.tags.length; i++) {
                            this.form.tags.push(this.task.tags[i].name);
                        }
                    }
                    this.form.has_deadline = this.task.has_deadline;
                    if (this.task.ends_at !== null) {
                        this.form.ends_at = this.task.ends_at;
                    }
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.project_id !== null) this.form.project_id = parseInt(this.oldInput.project_id);
                    if (this.oldInput.organization !== null) this.form.organization = this.oldInput.organization_id;
                    if (this.oldInput.department !== null) this.form.department = this.oldInput.department;
                    if (this.oldInput.task_status_id !== null) this.form.task_status_id = parseInt(this.oldInput.task_status_id);
                    if (this.oldInput.task_category !== null) this.form.task_category = this.oldInput.task_category;
                    if (this.oldInput.task_seniority_id !== null) this.form.task_seniority_id = parseInt(this.oldInput.task_seniority_id);
                    if (this.oldInput.title_en !== null) this.form.title.en = this.oldInput.title_en;
                    if (this.oldInput.title_nl !== null) this.form.title.nl = this.oldInput.title_nl;
                    if (this.oldInput.description_en !== null) this.form.description.en = this.oldInput.description_en;
                    if (this.oldInput.description_nl !== null) this.form.description.nl = this.oldInput.description_nl;
                    if (this.oldInput.complexity !== null) this.form.complexity = parseInt(this.oldInput.complexity);
                    if (this.oldInput.estimated_hours !== null) this.form.estimated_hours = this.oldInput.estimated_hours;
                    if (this.oldInput.realized_hours !== null) this.form.realized_hours = this.oldInput.realized_hours;
                    if (this.oldInput.urgency !== null) this.form.urgency = parseInt(this.oldInput.urgency);
                    if (this.oldInput.tags !== null) this.form.tags = JSON.parse(this.oldInput.tags);
                    if (this.oldInput.has_deadline !== null) this.form.has_deadline = this.oldInput.has_deadline === "true" ? true : false;
                    if (this.oldInput.ends_at !== null) this.form.ends_at = this.oldInput.ends_at;
                }
            },
            findOrganizationIdByName(name) {
                if (this.organizations.length > 0) {
                    for (let i = 0; i < this.organizations.length; i++) {
                        if (this.organizations[i].name[this.locale] === name) {
                            return this.organizations[i].id;
                        }
                    }
                }
                return false;
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
            getStatusById(id) {
                if (this.statuses !== undefined && this.statuses !== null && this.statuses.length > 0) {
                    for (let i = 0; i < this.statuses.length; i++) {
                        if (this.statuses[i].id === id) {
                            return this.statuses[i];
                        }
                    }
                }
                return false;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #task-form__wrapper {
        #parent-sections {
            display: flex;
            flex-direction: row;
            margin: 0 -25px 0 -25px;
            .parent-section {
                flex: 1;
                padding: 0 25px;
                box-sizing: border-box;
            }
        }



        #task-form {
            display: flex;
            flex-direction: row;
            #task-form__left {
                flex: 1;
            }
            #task-form__right {
                flex: 0 0 400px;
                margin: 0 0 0 50px;
                .form-field {
                    width: 400px;
                }
            }
        }
    }
</style>