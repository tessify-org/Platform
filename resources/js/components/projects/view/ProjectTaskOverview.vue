<template>
    <div id="project-task-overview">

        <!-- Title -->
        <h3 id="project-title">{{ strings.title }}</h3>

        <!-- Tasks -->
        <div id="project-task-overview__tasks" v-if="paginatedTasks.length > 0">
            <div class="task-wrapper" v-for="(task, ti) in paginatedTasks" :key="ti">
                <a class="task elevation-2" :href="task.view_href">
                    <div class="task__bg" :style="{ backgroundImage: 'url('+task.header_image_url+')' }"></div>
                    <div class="task__overlay"></div>
                    <div class="task__text">
                        <h2 class="task-title">{{ task.title[locale] }}</h2>
                        <div class="task-spacer"></div>
                        <div class="task-status">{{ task.status.label[locale] }}</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- No tasks -->
        <div id="project-task-overview__no-records" class="elevation-2" v-if="paginatedTasks.length === 0">
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
            "locale",
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
        margin: 0 0 30px 0;
        #project-title {
            font-size: 1em;
            font-weight: 300;
            margin: 0 0 5px 0;
        }
        #project-task-overview__tasks {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            margin: 0 -15px -30px -15px;
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
            margin: 0;
        }
        #project-task-overview__pagination {
            margin: 30px 0 0 0;
        }
    }
</style>