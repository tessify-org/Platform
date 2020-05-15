<template>
    <div id="project-dashboard-overview">

        <!-- Tasks -->
        <div id="projects" v-if="paginatedProjects.length > 0">
            <div class="project-wrapper" v-for="(project, pi) in paginatedProjects" :key="pi">
                <div class="project elevation-1">
                    <div class="project-content">
                        <!-- Category -->
                        <div class="project-category">{{ project.category.label }}</div>
                        <!-- Title -->
                        <h3 class="project-title">{{ project.title }}</h3>
                        <!-- Description -->
                        <div class="project-description">
                            <div class="project-description__label">{{ descriptionText }}</div>
                            <div class="project-description__text">{{ project.description.substring(0,250)+".." }}</div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="project-footer">
                        <div class="project-footer__left">
                            <!-- Status -->
                            <div class="project-status" :class="project.status.name">{{ project.status.label }}</div>
                        </div>
                        <div class="project-footer__right">
                            <!-- View task -->
                            <v-btn color="primary" depressed :href="project.view_href">
                                {{ viewText }}
                            </v-btn>                                
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- No tasks -->
        <div id="no-records" class="elevation-1" v-if="paginatedProjects.length === 0">
            {{ noProjectsText }}
        </div>

        <!-- Pagination -->
        <div id="pagination-wrapper" v-if="numPaginatedPages > 1">
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
            "descriptionText",
            "viewText",
            "noProjectsText",
        ],
        data: () => ({
            tag: "[task-dashboard-overview]",
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
                perPage: 5,
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
                this.paginateProjects();
            },
            "filters": {
                deep: true,
                handler: function() {
                    this.filterProjects();
                }
            }
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" projects: ", this.projects);
                console.log(this.tag+" description text: ", this.descriptionText);
                console.log(this.tag+" view text: ", this.viewText);
                console.log(this.tag+" no projects text: ", this.noTasksText);
                this.initializeData();
                this.startListening();
            },
            initializeData() {
                if (this.projects !== undefined && this.projects !== null && this.projects.length > 0) {
                    for (let i = 0; i < this.projects.length; i++) {
                        this.mutableProjects.push(this.projects[i]);
                    }
                }
                this.filterProjects();
            },
            startListening() {
                EventBus.$on("project-dashboard__search-query", function(searchQuery) {
                    this.filters.search_query = searchQuery;
                }.bind(this));
                EventBus.$on("project-dashboard__selected-statuses", function(selectedStatuses) {
                    this.filters.selected_statuses = selectedStatuses;
                }.bind(this));
                EventBus.$on("project-dashboard__selected-categories", function(selectedCategories) {
                    this.filters.selected_categories = selectedCategories;
                }.bind(this));
            },
            filterProjects() {

                this.filteredProjects = [];

                if (this.mutableProjects.length > 0) {
                    for (let i = 0; i < this.mutableProjects.length; i++) {

                        let project = this.mutableProjects[i];

                        // Filter on search query
                        if (this.filters.search_query !== "") {
                            let in_title = project.title.toLowerCase().includes(this.filters.search_query);
                            let in_desc = project.description.toLowerCase().includes(this.filters.search_query);
                            if (!in_title && !in_desc) continue;
                        }

                        // Filter on selected status
                        if (this.filters.selected_statuses.length > 0) {
                            let matches = false;
                            for (let i = 0; i < this.filters.selected_statuses.length; i++) {
                                if (project.project_status_id === this.filters.selected_statuses[i]) {
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
                                if (project.project_category_id === this.filters.selected_categories[i]) {
                                    matches = true;
                                    break;
                                }
                            }
                            if (!matches) continue;
                        }
                        
                        this.filteredProjects.push(project);

                    }
                }

                this.paginateProjects();
                
            },
            paginateProjects() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedProjects = this.filteredProjects.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #project-dashboard-overview {
        #projects {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            .project-wrapper {
                flex: 0 0 100%;
                box-sizing: border-box;
                padding: 0 15px 30px 15px;
                .project {
                    overflow: hidden;
                    border-radius: 3px;
                    background-color: #fff;
                    .project-content {
                        position: relative;
                        box-sizing: border-box;
                        padding: 60px 20px 15px 20px;
                        .project-category {
                            left: 0;
                            top: 15px;
                            color: #ffffff;
                            font-size: .9em;
                            padding: 5px 10px;
                            position: absolute;
                            box-sizing: border-box;
                            background-color: #333333;
                            border-top-right-radius: 3px;
                            border-bottom-right-radius: 3px;
                        }
                        .project-title {
                            font-size: 1.8em;
                            margin: 0 0 5px 0;
                        }
                        .project-description {
                            margin: 0 0 15px 0;
                            .project-description__label {
                                font-size: .8em;
                                margin: 0 0 1px 0;
                                color: hsl(0, 0%, 45%);
                            }
                            .project-description__text {

                            }
                        }
                    }
                    .project-footer {
                        display: flex;
                        padding: 15px 20px;
                        flex-direction: row;
                        box-sizing: border-box;
                        background-color: hsl(0, 0%, 95%);
                        .project-footer__left {
                            flex: 1;
                            display: flex;
                            flex-direction: row;
                            align-items: center;
                            .project-status {
                                color: #000;
                                border-radius: 3px;
                                padding: 2px 10px 3px 10px;
                                background-color: hsl(0, 0%, 80%);
                                &.open {
                                    color: #fff;
                                    background-color: #2db201;
                                }
                                &.in_progress {
                                    color: #fff;
                                    background-color: #df5200;
                                }
                                &.completed {
                                    color: #fff;
                                    background-color: #ea0000;
                                }
                            }
                        }
                        .project-footer__right {
                            flex: 1;
                            display: flex;
                            flex-direction: row;
                            align-items: center;
                            justify-content: flex-end;
                        }
                    }
                    .project-tags {
                        .project-tag {
                            color: #fff;
                            font-size: .85em;
                            border-radius: 3px;
                            margin: 0 10px 0 0;
                            display: inline-block;
                            box-sizing: border-box;
                            padding: 4px 10px 4px 10px;
                            background-color: #333333;
                            &:last-child {
                                margin: 0;
                            }
                        }
                    }
                }
            }
        }
        #no-records {
            padding: 15px;
            border-radius: 3px;
            margin: 0 0 30px 0;
            text-align: center;
            box-sizing: border-box;
            background-color: #ffffff;
        }
        #pagination-wrapper {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }
    }
</style>