<template>
    <div id="dashboard-my-tasks">

        <div class="content-card elevation-1">

            <!-- Tasks -->
            <div id="my-tasks__list" v-if="paginatedTasks.length > 0">
                <a class="my-task" v-for="(task, ti) in paginatedTasks" :key="ti" :href="task.view_href">
                    <span class="my-task__title">
                        {{ task.title[locale] }}
                    </span>
                    <span class="my-task__status">
                        {{ task.status.label[locale] }}
                    </span>
                </a>
            </div>

            <!-- No records -->
            <div id="my-tasks__empty" v-if="paginatedTasks.length === 0">
                {{ noRecordsText }}
            </div>

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
    export default {
        props: [
            "tasks",
            "locale",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[dashboard-my-tasks]",
            mutableTasks: [],
            paginatedTasks: [],
            pagination: {
                perPage: 5,
                currentPage: 1,
            },
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableTasks.length/this.pagination.perPage);
            },
        },
        watch: {
            "pagination.currentPage": function() {
                this.paginate();
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" tasks: ", this.tasks);
                console.log(this.tag+" locale: ", this.locale);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                this.initializeData();
            },
            initializeData() {
                if (this.tasks !== undefined && this.tasks !== null && this.tasks.length > 0) {
                    for (let i = 0; i < this.tasks.length; i++) {
                        this.mutableTasks.push(this.tasks[i]);
                    }
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedTasks = this.mutableTasks.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #dashboard-my-tasks {
        margin: 0 0 30px 0;
        #my-tasks__list {
            .my-task {
                display: flex;
                color: #000;
                padding: 10px 15px;
                flex-direction: row;
                align-items: center;
                transition: all .3s;
                text-decoration: none;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    background-color: hsl(0, 0%, 98%);
                }
                .my-task__title {
                    flex: 1;
                }
                .my-task__status {

                }
            }
        }
        #my-tasks__empty {
            padding: 15px;
            box-sizing: border-box;
        }
        #pagination-wrapper {
            margin-top: 10px;
        }
    }
</style>