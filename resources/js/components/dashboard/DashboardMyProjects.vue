<template>
    <div id="dashboard-my-projects">

        <div class="content-card elevation-1">

            <!-- Projects -->
            <div id="my-projects__list" v-if="paginatedProjects.length > 0">
                <a class="my-project" v-for="(project, ti) in paginatedProjects" :key="ti" :href="project.view_href">
                    <span class="my-project__title">
                        {{ project.title }}
                    </span>
                    <span class="my-project__status">
                        {{ project.status.label[locale] }}
                    </span>
                </a>
            </div>

            <!-- No records -->
            <div id="my-projects__empty" v-if="paginatedProjects.length === 0">
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
            "locale",
            "projects",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[dashboard-my-projects]",
            mutableProjects: [],
            paginatedProjects: [],
            pagination: {
                perPage: 5,
                currentPage: 1,
            },
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableProjects.length/this.pagination.perPage);
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
                console.log(this.tag+" locale: ", this.locale);
                console.log(this.tag+" projects: ", this.projects);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                this.initializeData();
            },
            initializeData() {
                if (this.projects !== undefined && this.projects !== null && this.projects.length > 0) {
                    for (let i = 0; i < this.projects.length; i++) {
                        this.mutableProjects.push(this.projects[i]);
                    }
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedProjects = this.mutableProjects.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #dashboard-my-projects {
        margin: 0 0 30px 0;
        #my-projects__list {
            .my-project {
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
                .my-project__title {
                    flex: 1;
                }
                .my-project__status {

                }
            }
        }
        #my-projects__empty {
            padding: 15px;
            box-sizing: border-box;
        }
        #pagination-wrapper {
            margin-top: 10px;
        }
    }
</style>