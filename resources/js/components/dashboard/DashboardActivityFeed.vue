<template>
    <div id="dashboard-activity-feed__wrapper">

        <!-- Entries -->
        <div id="dashboard-activity-feed" v-if="paginatedEntries.length > 0">
            <div class="feed-activity" v-for="(activity, ai) in paginatedEntries" :key="ai">
                <div class="feed-activity__avatar-wrapper">
                    <div class="avatar"></div>
                </div>
                <div class="feed-activity__text-wrapper">
                    <div class="activity-text">{{ activity.text }}</div>
                    <div class="activity-date">{{ activity.formatted_date }}</div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div id="dashboard-activity-feed__pagination" v-if="numPaginatedPages > 1">
            <v-pagination
                v-model="pagination.currentPage"
                :length="numPaginatedPages"
                total-visible="10">
            </v-pagination>
        </div>

        <!-- No entries -->
        <div id="dashboard-activity-feed__empty" v-if="mutableEntries.length === 0">
            {{ noRecordsText }}
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "entries",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[dashboard-activity-feed]",
            mutableEntries: [],
            paginatedEntries: [],
            pagination: {
                perPage: 5,
                currentPage: 1,
            }
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableEntries.length/this.pagination.perPage);
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
                console.log(this.tag+" entries: ", this.entries);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                this.initializeData();
            },
            initializeData() {
                if (this.entries !== undefined && this.entries !== null && this.entries.length > 0) {
                    for (let i = 0; i < this.entries.length; i++) {
                        this.mutableEntries.push(this.entries[i]);
                    }
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedEntries = this.mutableEntries.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },  
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #dashboard-activity-feed__wrapper {

        #dashboard-activity-feed {
            .feed-activity {
                display: flex;
                flex-direction: row;
                position: relative;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                .feed-activity__avatar-wrapper {
                    flex: 0 0 40px;
                    .avatar {
                        width: 40px;
                        height: 40px;
                        border-radius: 20px;
                        background-size: contain;
                        background-repeat: no-repeat;
                        background-position: center center;
                        background-color: #f5f5f5;
                    }
                }
                .feed-activity__text-wrapper {
                    flex: 1;
                    display: flex;
                    margin: 0 0 0 30px;
                    flex-direction: column;
                    justify-content: center;
                    .activity-text {

                    }
                    .activity-date {
                        font-size: .9em;
                        color: rgba(0, 0, 0, 0.25);
                    }
                }
            }
        }
        #dashboard-activity-feed__pagination {
            display: flex;
            margin: 30px 0 0 0;
            flex-direction: row;
            align-items: center;
            // justify-content: center;
        }
        #dashboard-activity-feed__empty {
            
        }
    }
</style>