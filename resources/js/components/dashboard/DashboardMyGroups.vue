<template>
    <div id="dashboard-my-groups">
        
        <div class="content-card elevation-1">

            <!-- Groups -->
            <div id="my-groups__list" v-if="paginatedGroups.length > 0">
                <a class="my-group" v-for="(group, gi) in paginatedGroups" :key="gi" :href="group.view_href">
                    <span class="my-group__title">
                        {{ group.name }}
                    </span>
                </a>
            </div>
            
            <!-- No records -->
            <div id="my-groups__empty" v-if="paginatedGroups.length === 0">
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
            "groups",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[dashboard-my-groups]",
            mutableGroups: [],
            paginatedGroups: [],
            pagination: {
                perPage: 5,
                currentPage: 1,
            },
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableGroups.length/this.pagination.perPage);
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
                console.log(this.tag+" groups: ", this.groups);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                this.initializeData();
            },
            initializeData() {
                if (this.groups !== undefined && this.groups !== null && this.groups.length > 0) {
                    for (let i = 0; i < this.groups.length; i++) {
                        this.mutableGroups.push(this.groups[i]);
                    }
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedGroups = this.mutableGroups.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #dashboard-my-groups {
        margin: 0 0 30px 0;
        #my-groups__list {
            .my-group {
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
                .my-group__title {
                    flex: 1;
                }
                .my-group__status {

                }
            }
        }
        #my-groups__empty {
            padding: 15px;
            box-sizing: border-box;
        }
        #pagination-wrapper {
            margin-top: 10px;
        }
    }
</style>