<template>
    <div id="task-progress-report-overview">

        <!-- Reviews -->
        <div id="task-progress-report-overview__reports" v-if="paginatedData.length > 0">
            <a class="progress-report" v-for="(report, ri) in paginatedData" :key="ri" :href="report.view_href">
                <span class="progress-report__author">
                    {{ report.user.formatted_name }}
                </span>
                <span class="progress-report__hours">
                    {{ report.hours }} {{ strings.hours }}
                </span>
                <span class="progress-report__date">
                    {{ report.formatted_date }}
                </span>
            </a>
        </div>

        <!-- No reviews -->
        <div id="task-progress-report-overview__no-reports" v-if="paginatedData.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- Pagination -->
        <div id="task-progress-report-overview__pagination" v-if="numPaginatedPages > 1">
            <v-pagination v-model="pagination.currentPage" :length="numPaginatedPages" total-visible="10"></v-pagination>
        </div>
    
    </div>
</template>

<script>
    export default {
        props: [
            "task",
            "reports",
            "strings",
        ],
        data: () => ({
            tag: "[task-progress-report-overview]",
            mutableData: [],
            pagination: {
                perPage: 10,
                currentPage: 1,
            },
            paginatedData: [],
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableData.length/this.pagination.perPage);
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
                console.log(this.tag+" task: ", this.task);
                console.log(this.tag+" reports: ", this.reports);
                this.initializeData();
            },
            initializeData() {
                if (this.reports !== undefined && this.reports !== null && this.reports.length > 0) {
                    for (let i = 0; i < this.reports.length; i++) {
                        this.mutableData.push(this.reports[i]);
                    }
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedData = this.mutableData.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #task-progress-report-overview {
        margin: 30px 0 0 0;
        #task-progress-report-overview__reports {
            border-radius: 3px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            .progress-report {
                display: flex;
                color: #000;
                padding: 10px 15px;
                transition: all .3s;
                flex-direction: row;
                text-decoration: none;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    color: #0a64eb;
                }
                .progress-report__author, .progress-report__hours, .progress-report__date {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
                .progress-report__author {

                }
                .progress-report__hours {

                }
                .progress-report__date {
                    justify-content: flex-end;
                }
            }
        }
    }
</style>