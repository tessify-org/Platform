<template>
    <div id="task-dashboard-overview">

        <!-- Tasks -->
        <div id="tasks" v-if="paginatedTasks.length > 0">
            <div class="task-wrapper" v-for="(task, ti) in paginatedTasks" :key="ti">
                <div class="task elevation-1">
                    <div class="task-content">
                        <!-- Category -->
                        <div class="task-category">{{ task.category.name[locale] }}</div>
                        <!-- Title -->
                        <h3 class="task-title">{{ task.title[locale] }}</h3>
                        <!-- Description -->
                        <div class="task-description">
                            <div class="task-description__label">{{ descriptionText }}</div>
                            <div class="task-description__text">{{ task.description[locale].substring(0,250)+".." }}</div>
                        </div>
                        <!-- Skills -->
                        <div class="task-skills" v-if="task.skills.length > 0">
                            <div class="task-skills__label">{{ skillsText }}</div>
                            <div class="task-skills__list">
                                <div class="task-skill" v-for="(skill, si) in task.skills" :key="si">
                                    {{ skill.name[locale] }}
                                </div>
                            </div>
                        </div>
                        <!-- Complexity -->
                        <div class="task-complexity">
                            <div class="task-complexity__label">{{ complexityText }}</div>
                            <div class="task-complexity__text">
                                {{ task.complexity }}/10
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="task-footer">
                        <div class="task-footer__left">
                            <!-- Status -->
                            <div class="task-status" :class="task.status.name">
                                {{ task.status.label[locale] }}
                            </div>
                        </div>
                        <div class="task-footer__right">
                            <!-- View task -->
                            <v-btn color="primary" depressed :href="task.view_href">
                                {{ viewText }}
                            </v-btn>                                
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- No tasks -->
        <div id="no-records" class="elevation-1" v-if="paginatedTasks.length === 0">
            {{ noTasksText }}
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
    import { EventBus } from './../../../event-bus.js';
    export default {
        props: [
            "tasks",
            "descriptionText",
            "skillsText",
            "complexityText",
            "viewText",
            "noTasksText",
            "locale",
        ],
        data: () => ({
            tag: "[task-dashboard-overview]",
            mutableTasks: [],
            filteredTasks: [],
            paginatedTasks: [],
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
            }
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.filteredTasks.length/this.pagination.perPage);
            },
        },
        watch: {
            "pagination.currentPage": function() {
                this.paginateTasks();
            },
            "filters": {
                deep: true,
                handler: function() {
                    this.filterTasks();
                }
            }
        },
        methods: {
            initialize() {
                
                console.log(this.tag+" initializing");
                console.log(this.tag+" tasks: ", this.tasks);
                console.log(this.tag+" description text: ", this.descriptionText);
                console.log(this.tag+" skills text: ", this.skillsText);
                console.log(this.tag+" complexity text: ", this.complexityText);
                console.log(this.tag+" view text: ", this.viewText);
                console.log(this.tag+" no tasks text: ", this.noTasksText);
                console.log(this.tag+" locale: ", this.locale);

                this.initializeData();
                this.startListening();

            },
            initializeData() {
                
                if (this.tasks !== undefined && this.tasks !== null && this.tasks.length > 0) {
                    for (let i = 0; i < this.tasks.length; i++) {
                        this.mutableTasks.push(this.tasks[i]);
                    }
                }
                
                this.filterTasks();

            },
            startListening() {
                
                EventBus.$on("task-dashboard__search-query", function(searchQuery) {
                    this.filters.search_query = searchQuery;
                }.bind(this));

                EventBus.$on("task-dashboard__selected-statuses", function(selectedStatuses) {
                    console.log("waaaat", selectedStatuses);
                    this.filters.selected_statuses = selectedStatuses;
                }.bind(this));

                EventBus.$on("task-dashboard__selected-categories", function(selectedCategories) {
                    this.filters.selected_categories = selectedCategories;
                }.bind(this));

                EventBus.$on("task-dashboard__selected-skills", function(selectedSkills) {
                    this.filters.selected_skills = selectedSkills;
                }.bind(this));

                EventBus.$on("task-dashboard__selected-seniorities", function(selectedSeniorities) {
                    this.filters.selected_seniorities = selectedSeniorities;
                }.bind(this));

                EventBus.$on("task-dashboard__duration-range", function(range) {
                    this.filters.time_range.min = range[0];
                    this.filters.time_range.max = range[1];
                }.bind(this));

            },
            filterTasks() {

                this.filteredTasks = [];

                if (this.mutableTasks.length > 0) {
                    for (let i = 0; i < this.mutableTasks.length; i++) {

                        let task = this.mutableTasks[i];

                        // Filter on search query
                        if (this.filters.search_query !== "") {
                            let in_title = task.title.toLowerCase().includes(this.filters.search_query);
                            let in_desc = task.description.toLowerCase().includes(this.filters.search_query);
                            if (!in_title && !in_desc) continue;
                        }

                        // Filter on selected status
                        if (this.filters.selected_statuses.length > 0) {
                            let matches = false;
                            for (let i = 0; i < this.filters.selected_statuses.length; i++) {
                                if (task.task_status_id === this.filters.selected_statuses[i]) {
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
                                if (task.task_category_id === this.filters.selected_categories[i]) {
                                    matches = true;
                                    break;
                                }
                            }
                            if (!matches) continue;
                        }

                        // Filter on selected skills
                        if (this.filters.selected_skills.length > 0) {
                            let matches = false;
                            for (let i = 0; i < this.filters.selected_skills.length; i++) {
                                if (task.skills.length > 0) {
                                    for (let j = 0; j < task.skills.length; j++) {
                                        if (task.skills[j].id === this.filters.selected_skills[i]) {
                                            matches = true;
                                            break;
                                        }
                                    }    
                                }
                                if (matches) break;
                            }
                            if (!matches) continue;
                        }

                        // Filter on selected seniorities
                        if (this.filters.selected_seniorities.length > 0) {
                            let matches = false;
                            for (let i = 0; i < this.filters.selected_seniorities.length; i++) {
                                if (task.task_seniority_id === this.filters.selected_seniorities[i]) {
                                    matches = true;
                                    break;
                                }
                            }
                            if (!matches) continue;
                        }

                        // Filter on selected time range
                        if (this.filters.time_range.min !== null && task.estimated_hours < this.filters.time_range.min) continue;
                        if (this.filters.time_range.max !== null && task.estimated_hours > this.filters.time_range.max) continue;

                        this.filteredTasks.push(task);

                    }
                }

                this.paginateTasks();

            },
            paginateTasks() {

                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                
                this.paginatedTasks = this.filteredTasks.slice(start_slicing_at, stop_slicing_at);
                
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
                
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #task-dashboard-overview {
        #tasks {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            .task-wrapper {
                flex: 0 0 100%;
                box-sizing: border-box;
                padding: 0 15px 30px 15px;
                .task {
                    border-radius: 3px;
                    background-color: #fff;
                    .task-content {
                        position: relative;
                        box-sizing: border-box;
                        padding: 60px 20px 15px 20px;
                        .task-category {
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
                        .task-title {
                            font-size: 1.8em;
                            margin: 0 0 5px 0;
                        }
                        .task-description {
                            margin: 0 0 15px 0;
                            .task-description__label {
                                font-size: .8em;
                                margin: 0 0 1px 0;
                                color: hsl(0, 0%, 45%);
                            }
                            .task-description__text {

                            }
                        }
                        .task-skills {
                            margin: 0 0 15px 0;
                            .task-skills__label {
                                font-size: .8em;
                                margin: 0 0 5px 0;
                                color: hsl(0, 0%, 45%);
                            }
                            .task-skills__list {
                                display: flex;
                                flex-wrap: wrap;
                                flex-direction: row;
                                margin: 0 0 -10px 0;
                                .task-skill {
                                    color: #fff;
                                    font-size: .7em;
                                    border-radius: 3px;
                                    margin: 0 5px 5px 0;
                                    padding: 1px 7px 3px 7px;
                                    background-color: #333;
                                }
                            }
                        }
                        .task-complexity {
                            // margin: 0 0 15px 0;
                            .task-complexity__label {
                                font-size: .8em;
                                margin: 0 0 0 0;
                                color: hsl(0, 0%, 45%);
                            }
                            .task-complexity__text {}
                        }
                    }
                    .task-footer {
                        display: flex;
                        padding: 15px 20px;
                        flex-direction: row;
                        box-sizing: border-box;
                        background-color: hsl(0, 0%, 95%);
                        .task-footer__left {
                            flex: 1;
                            display: flex;
                            flex-direction: row;
                            align-items: center;
                            .task-status {
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
                        .task-footer__right {
                            flex: 1;
                            display: flex;
                            flex-direction: row;
                            align-items: center;
                            justify-content: flex-end;
                        }
                    }
                    .task-tags {
                        .task-tag {
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