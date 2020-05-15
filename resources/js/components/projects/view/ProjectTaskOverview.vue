<template>
    <div id="project-task-overview">

        <!-- Header -->
        <div id="project-task-overview__header">
            <div id="project-task-overview__header-left">
                <h1 id="project-title">{{ strings.title }}</h1>
            </div>
            <div id="project-task-overview__header-right">
                <v-btn small depressed color="primary" :href="createHref">
                    <i class="fas fa-plus"></i>
                    {{ strings.create }}
                </v-btn>
            </div>
        </div>

        <!-- Tasks -->
        <div id="project-task-overview__tasks" v-if="paginatedTasks.length > 0">
            <div class="task-wrapper" v-for="(task, ti) in paginatedTasks" :key="ti">
                <a class="task" :href="task.view_href">
                    <div class="task__bg" :style="{ backgroundImage: 'url('+task.header_image_url+')' }"></div>
                    <div class="task__overlay"></div>
                    <div class="task__text">
                        <h2 class="task-title">{{ task.title }}</h2>
                        <div class="task-spacer"></div>
                        <div class="task-status">{{ task.status.label }}</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- No tasks -->
        <div id="project-task-overview__no-records" v-if="paginatedTasks.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- Pagination -->
        <div id="project-task-overview__pagination" v-if="numPaginatedPages > 1">
            <v-pagination v-model="pagination.currentPage" :length="numPaginatedPages" total-visible="10"></v-pagination>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "project",
            "tasks",
            "strings",
            "createHref",
        ],
        data: () => ({
            tag: "[project-task-overview]",
            mutableTasks: [],
            pagination: {
                perPage: 10,
                currentPage: 1,
            },
            paginatedTasks: [],
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
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" tasks: ", this.tasks);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" create href: ", this.createHref);
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
        }
    }
</script>

<style lang="scss">
    #project-task-overview {
        #project-task-overview__header {
            display: flex;
            flex-direction: row;
            align-items: center;
            #project-task-overview__header-left {
                flex: 1;
                #project-title {
                    margin: 0 !important;
                }
            }
            #project-task-overview__header-right {
                
            }
        }
        #project-task-overview__tasks {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            margin: 30px -15px -30px -15px;
            .task-wrapper {
                flex: 0 0 50%;
                padding: 0 15px 30px 15px;
                .task {
                    width: 100%;
                    height: 150px;
                    display: block;
                    overflow: hidden;
                    border-radius: 3px;
                    position: relative;
                    border-radius: 3px;
                    background-color: #fff;
                    &:hover {
                        cursor: pointer;
                        .task__overlay {
                            background-color: rgba(0, 0, 0, 0.35);
                        }
                    }
                    .task__bg {
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
                    .task__overlay { 
                        top: 0;
                        left: 0;
                        z-index: 2;
                        width: 100%;
                        height: 100%;
                        position: absolute;
                        transition: all .3s;
                        background-color: rgba(0, 0, 0, .5);
                    }
                    .task__text {
                        top: 0;
                        left: 0;
                        z-index: 3;
                        width: 100%;
                        height: 100%;
                        padding: 20px;
                        color: #fff;
                        display: flex;
                        position: absolute;
                        flex-direction: column;
                        box-sizing: border-box;
                        .task-title {
                            line-height: 1.1em;
                        }
                        .task-spacer {
                            flex: 1;
                        }
                        .task-status {

                        }
                    }
                }
            }
        }
        #project-task-overview__no-records {
            margin: 15px 0 0 0;
        }
        #project-task-overview__pagination {
            margin: 15px 0 0 0;
        }
    }
</style>