<template>
    <div id="task-overview">

        <!-- Filters -->
        <div id="task-overview__filters">
            <div id="task-overview__filters-left">
                <!-- Search bar -->
                <div id="task-overview__search-bar" class="task-overview__filter">
                    <v-text-field
                        solo 
                        dense 
                        hide-details
                        :label="strings.search" 
                        v-model="filters.searchQuery">
                    </v-text-field>
                </div>
            </div>
            <div id="task-overview__filters-right">
                <!-- Filters button -->
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <div id="filters-button" class="elevation-2" v-on="on" @click="onClickFilterButton">
                            <div id="filters-button__text">
                                {{ numFiltersEnabled }}
                            </div>
                            <div id="filters-button__icon">
                                <i class="fas fa-filter"></i>
                            </div>
                        </div>
                    </template>
                    <span>{{ strings.filters }}</span>
                </v-tooltip>
            </div>
        </div>

        <!-- Tasks -->
        <div id="task-overview__tasks" v-if="paginatedTasks.length > 0">
            <div class="task-wrapper" v-for="(task, ti) in paginatedTasks" :key="ti">
                <a class="task elevation-1" :href="task.view_href">
                    <span class="task-bg" :style="{ backgroundImage: 'url('+task.header_image_url+')' }"></span>
                    <span class="task-bg-overlay"></span>
                    <span class="task-text">
                        <h3 class="task-title">{{ task.title[locale] }}</h3>
                        <h4 class="task-category">{{ task.category.name[locale] }}</h4>
                    </span>
                    <span class="task-indicators">
                        <span class="task-indicators__left">
                            <!-- Num users -->
                            <span class="indicator">
                                <span class="indicator-text">{{ task.users.length }}</span>
                                <span class="indicator-icon">
                                    <i class="fas fa-users"></i>
                                </span>
                            </span>
                        </span>
                        <span class="task-indicators__right">
                            <!-- Status -->
                            <span class="indicator">
                                <span class="indicator-text">{{ task.status.label[locale] }}</span>
                                <span class="indicator-icon" v-if="task.status.name === 'open'">
                                    <i class="far fa-hand-point-right"></i>
                                </span>
                                <span class="indicator-icon" v-if="task.status.name === 'in_progress'">
                                    <i class="fas fa-hourglass-half"></i>
                                </span>
                                <span class="indicator-icon" v-if="task.status.name === 'completed'">
                                    <i class="fas fa-check"></i>
                                </span>
                            </span>
                        </span>
                    </span>
                </a>
            </div>
        </div>

        <!-- No tasks -->
        <div id="task-overview__no-tasks" v-if="paginatedTasks.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- Pagination -->
        <div id="task-overview__pagination" v-if="numPaginatedPages > 1">
            <v-pagination
                v-model="pagination.currentPage"
                :length="numPaginatedPages"
                total-visible="10">
            </v-pagination>
        </div>

        <!-- Filters dialog -->
        <v-dialog v-model="dialogs.filters.show" width="600">
            <div class="dialog">
                <div class="dialog__close-button" @click="dialogs.filters.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <div class="dialog-content">
                    <h3 class="dialog-title">{{ strings.filters }}</h3>
                    <div class="dialog-text">
                        <!-- Description -->
                        <p>
                            {{ strings.filters_description }}
                        </p>
                        <!-- Status -->
                        <div class="form-field">
                            <v-select
                                multiple
                                hide-details
                                :label="strings.status"
                                :items="statusOptions"
                                v-model="filters.selectedStatuses">
                            </v-select>
                        </div>
                        <!-- Category -->
                        <div class="form-field">
                            <v-combobox
                                multiple
                                hide-details
                                :label="strings.categories"
                                :items="categoryOptions"
                                v-model="filters.selectedCategories">
                            </v-combobox>
                        </div>
                        <!-- Seniority -->
                        <div class="form-field">
                            <v-select
                                multiple
                                hide-details
                                :label="strings.seniorities"
                                :items="seniorityOptions"
                                v-model="filters.selectedSeniorities">
                            </v-select>
                        </div>
                        <!-- Skills -->
                        <div class="form-field">
                            <v-combobox
                                multiple
                                hide-details
                                :label="strings.skills"
                                :items="skillOptions"
                                v-model="filters.selectedSkills">
                            </v-combobox>
                        </div>
                    </div>
                </div>
                <div class="dialog-controls">
                    <div class="dialog-controls__right">
                        <v-btn depressed color="white" @click="dialogs.filters.show = false">
                            {{ strings.filters_view_results }}
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
            "locale",
            "tasks",
            "statuses",
            "categories",
            "skills",
            "seniorities",
            "strings",
            "noRecordsImageUrl",
        ],
        data: () => ({
            tag: "[task-overview]",
            seniorityOptions: [],
            categoryOptions: [],
            statusOptions: [],
            skillOptions: [],
            mutableTasks: [],
            filters: {
                searchQuery: "",
                selectedSkills: [],
                selectedStatuses: [],
                selectedCategories: [],
                selectedSeniorities: [],
            },
            filteredTasks: [],
            pagination: {
                perPage: 12,
                currentPage: 1,
            },
            paginatedTasks: [],
            dialogs: {
                filters: {
                    show: false,
                }
            }
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.filteredTasks.length/this.pagination.perPage);
            },
            numFiltersEnabled() {
                let out = 0;
                if (this.filters.searchQuery !== "") out += 1;
                if (this.filters.selectedSkills.length > 0) out += this.filters.selectedSkills.length;
                if (this.filters.selectedStatuses.length > 0) out += this.filters.selectedStatuses.length;
                if (this.filters.selectedCategories.length > 0) out += this.filters.selectedCategories.length;
                if (this.filters.selectedSeniorities.length > 0) out += this.filters.selectedSeniorities.length;
                return out;
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
                },
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" locale: ", this.locale);
                console.log(this.tag+" tasks: ", this.tasks);
                console.log(this.tag+" statuses: ", this.statuses);
                console.log(this.tag+" categories: ", this.categories);
                console.log(this.tag+" skills: ", this.skills);
                console.log(this.tag+" seniorities: ", this.seniorities);
                console.log(this.tag+" strings: ", this.strings);
                this.generateSeniorityOptions();
                this.generateCategoryOptions();
                this.generateStatusOptions();
                this.generateSkillOptions();
                this.initializeData();
            },
            initializeData() {
                if (this.tasks !== undefined && this.tasks !== null && this.tasks.length > 0) {
                    for (let i = 0; i < this.tasks.length; i++) {
                        this.mutableTasks.push(this.tasks[i]);
                    }
                }
                this.filter();
            },
            filter() {
                this.filteredTasks = [];
                for (let i = 0; i < this.mutableTasks.length; i++) {
                    let task = this.mutableTasks[i];
                    if (this.filters.searchQuery !== "") {
                        if (!task.title[this.locale].toLowerCase().includes(this.filters.searchQuery)) continue;
                    }
                    if (this.filters.selectedStatuses.length > 0) {
                        let matches = false;
                        for (let i = 0; i < this.filters.selectedStatuses.length; i++) {
                            if (task.task_status_id === this.filters.selectedStatuses[i]) {
                                matches = true;
                                break;
                            }
                        }
                        if (!matches) continue;
                    }
                    if (this.filters.selectedCategories.length > 0) {
                        let matches = false;
                        for (let i = 0; i < this.filters.selectedCategories.length; i++) {
                            if (task.category.name[this.locale] === this.filters.selectedCategories[i]) {
                                matches = true;
                                break;
                            }
                        }
                        if (!matches) continue;
                    }
                    if (this.filters.selectedSkills.length > 0) {
                        let matches = false;
                        for (let i = 0; i < this.filters.selectedSkills.length; i++) {
                            if (task.skills.length > 0) {
                                for (let j = 0; j < task.skills.length; j++) {
                                    if (task.skills[j].name[this.locale] === this.filters.selectedSkills[i]) {
                                        matches = true;
                                        break;
                                    }
                                }    
                            }
                            if (matches) break;
                        }
                        if (!matches) continue;
                    }
                    if (this.filters.selectedSeniorities.length > 0) {
                        let matches = false;
                        for (let i = 0; i < this.filters.selectedSeniorities.length; i++) {
                            if (task.task_seniority_id === this.filters.selectedSeniorities[i]) {
                                matches = true;
                                break;
                            }
                        }
                        if (!matches) continue;
                    }
                    this.filteredTasks.push(task);
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedTasks = this.filteredTasks.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
            generateSeniorityOptions() {
                if (this.seniorities !== undefined && this.seniorities !== null && this.seniorities.length > 0) {
                    for (let i = 0; i < this.seniorities.length; i++) {
                        this.seniorityOptions.push({
                            text: this.seniorities[i].label[this.locale],
                            value: this.seniorities[i].id,
                        });
                    }
                }
            },
            generateCategoryOptions() {
                if (this.categories !== undefined && this.categories !== null && this.categories.length > 0) {
                    for (let i = 0; i < this.categories.length; i++) {
                        this.categoryOptions.push(this.categories[i].name[this.locale]);
                    }
                }
            },
            generateStatusOptions() {
                if (this.statuses !== undefined && this.statuses !== null && this.statuses.length > 0) {
                    for (let i = 0; i < this.statuses.length; i++) {
                        this.statusOptions.push({
                            text: this.statuses[i].label[this.locale],
                            value: this.statuses[i].id,
                        });
                    }
                }
            },
            generateSkillOptions() {
                if (this.skills !== undefined && this.skills !== null && this.skills.length > 0) {
                    for (let i = 0; i < this.skills.length; i++) {
                        this.skillOptions.push(this.skills[i].name[this.locale]);
                    }
                }
            },
            onClickFilterButton() {
                this.dialogs.filters.show = true;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #task-overview {
        #task-overview__filters {
            display: flex;
            flex-direction: row;
            margin: 0 0 50px 0;
            #task-overview__filters-left {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
                #task-overview__search-bar {
                    flex: 0 0 250px;
                }
            }
            #task-overview__filters-right {
                display: flex;
                flex-direction: row;
                align-items: center;
                #filters-button {
                    display: flex;
                    padding: 5px 10px;
                    border-radius: 3px;
                    flex-direction: row;
                    box-sizing: border-box;
                    background-color: #ffffff;
                    transition: all .3s;
                    &:hover {
                        cursor: pointer;
                        background-color: hsl(0, 0%, 95%);
                    }
                    #filters-button__text {
                        padding: 0 10px 0 0;
                        box-sizing: border-box;
                    }
                    #filters-button__icon {

                    }
                }
            }
        }
        #task-overview__tasks {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            margin: 0 -15px -30px -15px;
            .task-wrapper {
                flex: 0 0 33.33%;
                box-sizing: border-box;
                padding: 0 15px 30px 15px;
                .task {
                    width: 100%;
                    height: 230px;
                    display: block;
                    overflow: hidden;
                    border-radius: 3px;
                    position: relative;
                    &:hover {
                        cursor: pointer;
                        .task-bg-overlay {
                            background-color: rgba(0, 0, 0, 0.35);
                        }
                    }
                    .task-bg {
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
                    .task-bg-overlay {
                        top: 0;
                        left: 0;
                        z-index: 2;
                        width: 100%;
                        height: 100%;
                        position: absolute;
                        transition: all .3s;
                        background-color: rgba(0, 0, 0, 0.5);
                    }
                    .task-text {
                        top: 0;
                        left: 0;
                        z-index: 3;
                        width: 100%;
                        height: 100%;
                        padding: 25px;
                        color: #ffffff;
                        position: absolute;
                        box-sizing: border-box;
                        .task-title {
                            font-size: 2em;
                            font-weight: 400;
                            line-height: 1.1em;
                            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
                        }
                        .task-category {
                            font-size: .9em;
                            font-weight: 300;
                            margin: 5px 0 0 0;
                            text-shadow: 1px 1px 1px rgba(0, 0, 0, 0.5);
                        }
                    }
                    .task-indicators {
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
                        .task-indicators__left {
                            flex: 1;
                            display: flex;
                            margin: 0 -10px;
                            flex-direction: row;
                            align-items: center;
                        }
                        .task-indicators__right {
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
        #task-overview__no-tasks {}
        #task-overview__pagination {
            margin: 50px 0 0 0;
        }
    }
</style>