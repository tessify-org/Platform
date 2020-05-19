<template>
    <div id="project-overview">

        <!-- Filters -->
        <div id="project-overview__filters">
            <div id="project-overview__filters-left">
                <!-- Search bar -->
                <div class="project-overview__filter">
                    <v-text-field
                        solo dense 
                        :label="strings.search_title" 
                        v-model="filters.search_query">
                    </v-text-field>
                </div>
            </div>
            <div id="project-overview__filters-right">
                <!-- Status -->
                <div class="project-overview__filter">
                    <v-combobox
                        multiple solo dense
                        :label="strings.status_title"
                        :items="statusOptions"
                        v-model="filters.selected_statuses">
                    </v-combobox>
                </div>
                <!-- Category -->
                <div class="project-overview__filter">
                    <v-combobox
                        multiple solo dense
                        :label="strings.category_title"
                        :items="categoryOptions"
                        v-model="filters.selected_categories">
                    </v-combobox>
                </div>
            </div>
        </div>
        
        <!-- Projects -->
        <div id="project-overview__projects" v-if="paginatedProjects.length > 0">
            <div class="project-wrapper" v-for="(project, pi) in paginatedProjects" :key="pi">
                <a class="project elevation-1" :href="project.view_href">
                    <span class="project-bg" :style="{ backgroundImage: 'url(' + project.header_image_url + ')' }"></span>
                    <span class="project-overlay"></span>
                    <span class="project-text">
                        <h3 class="project-title">{{ project.title }}</h3>
                        <h4 class="project-category">{{ project.category.label[locale] }}</h4>
                    </span>
                    <span class="project-indicators">
                        <span class="project-indicators__left">
                            <!-- Number of team members -->
                            <span class="indicator">
                                <span class="indicator-text">
                                    {{ project.team_members.length }}
                                </span>
                                <span class="indicator-icon">
                                    <i class="fas fa-users"></i>
                                </span>
                            </span>
                            <!-- Number of tasks -->
                            <span class="indicator">
                                <span class="indicator-text">
                                    {{ project.num_tasks }}
                                </span>
                                <span class="indicator-icon">
                                    <i class="fas fa-rocket"></i>
                                </span>
                            </span>
                        </span>
                        <span class="project-indicators__right">
                            <!-- Open -->
                            <span class="indicator green-icon" v-if="project.status.name === 'open'">
                                <span class="indicator-icon">
                                    <i class="fas fa-seedling"></i>
                                </span>
                            </span>
                            <!-- Closed -->
                            <span class="indicator green-icon" v-if="project.status.name === 'closed'">
                                <span class="indicator-icon">
                                    <i class="far fa-check-circle"></i>
                                </span>
                            </span>
                        </span>
                    </span>
                </a>
            </div>
        </div>

        <!-- No projects -->
        <div id="project-overview__no-projects" v-if="paginatedProjects.length === 0">{{ strings.no_records }}</div>

        <!-- Pagination -->
        <div id="project-overview__pagination" v-if="numPaginatedPages > 1">
            <v-pagination
                v-model="pagination.currentPage"
                :length="numPaginatedPages"
                total-visible="10">
            </v-pagination>
        </div>

    </div>
</template>

<script>
    import { EventBus } from './../../event-bus.js';
    export default {
        props: [
            "projects",
            "statuses",
            "categories",
            "strings",
            "locale",
        ],
        data: () => ({
            tag: "[project-overview]",
            statusOptions: [],
            categoryOptions: [],
            mutableProjects: [],
            filteredProjects: [],
            paginatedProjects: [],
            filters: {
                search_query: "",
                selected_skills: [],
                selected_statuses: [],
                selected_categories: [],
                selected_seniorities: [],
                time_range: {
                    min: 0,
                    max: 40
                }
            },
            pagination: {
                perPage: 16,
                currentPage: 1,
            },
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.filteredProjects.length / this.pagination.perPage);
            },
        },
        watch: {
            "pagination.currentPage": function() {
                this.paginate();
            },
            "filters": {
                deep: true,
                handler: function() {
                    this.filter();
                }
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" projects: ", this.projects);
                console.log(this.tag+" statuses: ", this.statuses);
                console.log(this.tag+" categories: ", this.categories);
                this.generateCategoryOptions();
                this.generateStatusOptions();
                this.initializeData();
            },
            generateCategoryOptions() {
                if (this.categories !== undefined && this.categories !== null && this.categories.length > 0) {
                    for (let i = 0; i < this.categories.length; i++) {
                        this.categoryOptions.push(this.categories[i].label[this.locale]);
                    }
                }
            },
            generateStatusOptions() {
                if (this.statuses !== undefined && this.statuses !== null && this.statuses.length > 0) {
                    for (let i = 0; i < this.statuses.length; i++) {
                        this.statusOptions.push(this.statuses[i].label[this.locale]);
                    }
                }
            },
            initializeData() {
                if (this.projects !== undefined && this.projects !== null && this.projects.length > 0) {
                    for (let i = 0; i < this.projects.length; i++) {
                        this.mutableProjects.push(this.projects[i]);
                    }
                }
                this.filter();
            },
            filter() {
                // Reset filtered projects
                this.filteredProjects = [];
                // If there are mutable projects to process; process each individually
                if (this.mutableProjects.length > 0) {
                    for (let i = 0; i < this.mutableProjects.length; i++) {
                        // Grab the project
                        let project = this.mutableProjects[i];
                        // Filter on search query
                        if (this.filters.search_query !== "") {
                            let in_title = project.title[this.locale].toLowerCase().includes(this.filters.search_query);
                            let in_desc = project.description[this.locale].toLowerCase().includes(this.filters.search_query);
                            if (!in_title && !in_desc) continue;
                        }
                        // Filter on selected status
                        if (this.filters.selected_statuses.length > 0) {
                            let matches = false;
                            for (let i = 0; i < this.filters.selected_statuses.length; i++) {
                                if (project.status.label[this.locale] === this.filters.selected_statuses[i]) {
                                    matches = true;
                                    break;
                                }
                            }
                            if (!matches) continue;
                        }
                        // Filter on selected category
                        if (this.filters.selected_categories.length > 0) {
                            let matches = false;
                            for (let i = 0; i < this.filters.selected_categories.length; i++) {
                                if (project.category.label[this.locale] === this.filters.selected_categories[i]) {
                                    matches = true;
                                    break;
                                }
                            }
                            if (!matches) continue;
                        }
                        // If we've reached this point the project passes all filters; so add it to the list
                        this.filteredProjects.push(project);
                    }
                }
                // Paginate the filtered projects
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedProjects = this.filteredProjects.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #project-overview {
        #project-overview__filters {
            display: flex;
            margin: 0 0 50px 0;
            flex-direction: row;
            #project-overview__filters-left {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
            }
            #project-overview__filters-right {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
                margin: 0 -15px;
                .project-overview__filter {
                    padding: 0 15px;
                    box-sizing: border-box;
                }
            }
        }
        #project-overview__projects {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            justify-content: center;
            margin: 0 -15px -30px -15px;
            .project-wrapper {
                flex: 0 0 25%;
                box-sizing: border-box;
                padding: 0 15px 30px 15px;
                .project {
                    width: 100%;
                    height: 200px;
                    display: block;
                    overflow: hidden;
                    border-radius: 3px;
                    position: relative;
                    &:hover {
                        cursor: pointer;
                        .project-overlay {
                            background-color: rgba(0, 0, 0, 0.25);
                        }
                    }
                    .project-bg {
                        top: 0;
                        left: 0;
                        z-index: 1;
                        width: 100%;
                        height: 100%;
                        position: absolute;
                        background-size: cover;
                        background-repeat: no-repeat;
                        background-position: center center;
                    }
                    .project-overlay {
                        top: 0;
                        left: 0;
                        z-index: 2;
                        width: 100%;
                        height: 100%;
                        position: absolute;
                        transition: all .3s;
                        background-color: rgba(0, 0, 0, 0.5);
                    }
                    .project-text {
                        top: 0;
                        left: 0;
                        z-index: 3;
                        width: 100%;
                        height: 100%;
                        padding: 25px;
                        color: #ffffff;
                        position: absolute;
                        box-sizing: border-box;
                        .project-title {
                            font-size: 2em;
                            font-weight: 400;
                            line-height: 1.1em;
                            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
                        }
                        .project-category {
                            font-size: .9em;
                            font-weight: 300;
                            margin: 5px 0 0 0;
                            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
                        }
                    }
                    .project-indicators {
                        left: 0;
                        bottom: 0;
                        z-index: 3;
                        width: 100%;
                        padding: 25px;
                        display: flex;
                        color: #ffffff;
                        position: absolute;
                        flex-direction: row;
                        box-sizing: border-box;
                        .project-indicators__left {
                            flex: 1;
                            display: flex;
                            margin: 0 -10px;
                            flex-direction: row;
                            align-items: center;
                        }
                        .project-indicators__right {
                            flex: 1;
                            display: flex;
                            margin: 0 -10px;
                            flex-direction: row;
                            align-items: center;
                            justify-content: flex-end;
                        }
                        .indicator {
                            padding: 0 10px;
                            box-sizing: border-box;
                            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
                            &.green-icon {
                                .indicator-icon {
                                    color: #08ac1b;
                                }
                            }
                            &.orange-icon {
                                .indicator-icon {
                                    color: #f35900;
                                }
                            }
                            .indicator-text {
                                margin-right: 5px;
                            }
                            .indicator-icon {

                            }
                        }
                    }
                }
            }
        }
        #project-overview__no-projects {

        }
        #project-overview__pagination {
            margin: 50px 0 0 0;
        }
    }
</style>