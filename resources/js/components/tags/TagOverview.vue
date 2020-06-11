<template>
    <div id="tag-overview">

        <!-- Filters -->
        <div id="tag-overview__filters">
            <div id="tag-overview__filters-search">

                <!-- Search -->
                <v-text-field
                    solo
                    :label="strings.search"
                    v-model="filters.searchQuery">
                </v-text-field>

            </div>
        </div>

        <!-- Tags -->
        <div id="tag-overview__list" class="elevation-2" v-if="paginatedTags.length > 0">
            <a class="tag-overview__list-item" v-for="(tag, ti) in paginatedTags" :key="ti" :href="tag.view_href">
                <span class="list-item__icon">
                    <i class="fas fa-tag"></i>
                </span>
                <span class="list-item__text">
                    {{ tag.name }}
                </span>
            </a>
        </div>

        <!-- Records -->
        <div id="tag-overview__no-records" class="elevation-2" v-if="paginatedTags.length === 0">
            {{ strings.no_records }}
        </div>
        
        <!-- Pagination -->
        <div id="tag-overview__pagination" v-if="numPaginatedPages > 1">
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
            "tags",
            "strings",
        ],
        data: () => ({
            tag: "[tag-overview]",
            mutableTags: [],
            filters: {
                searchQuery: "",
            },
            filteredTags: [],
            pagination: {
                perPage: 30,
                currentPage: 1,
            },
            paginatedTags: [],
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.filteredTags.length/this.pagination.perPage);
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
                console.log(this.tag+" tags: ", this.tags);
                console.log(this.tag+" strings: ", this.strings);
                this.initializeData();
            },
            initializeData() {
                if (this.tags !== undefined && this.tags !== null && this.tags.length > 0) {
                    for (let i = 0; i < this.tags.length; i++) {
                        this.mutableTags.push(this.tags[i]);
                    }
                }
                this.filter();
            },
            filter() {
                this.filteredTags = [];
                for (let i = 0; i < this.mutableTags.length; i++) {
                    let tag = this.mutableTags[i];
                    if (this.filters.searchQuery !== "") {
                        if (!tag.name.toLowerCase().includes(this.filters.searchQuery.toLowerCase())) continue;
                    }
                    this.filteredTags.push(tag);
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedTags = this.filteredTags.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #tag-overview {
        #tag-overview__filters {
            display: flex;
            align-items: center;
            flex-direction: row;
            #tag-overview__filters-search {
                flex: 0 0 300px;
            }
        }
        #tag-overview__list {
            overflow: hidden;
            border-radius: 3px;
            background-color: #fff;
            .tag-overview__list-item {
                display: flex;
                color: #000000;
                padding: 15px 20px;
                transition: all .3s;
                flex-direction: row;
                text-decoration: none;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    background-color: hsl(0, 0%, 98%);
                }
                .list-item__icon {
                    flex: 0 0 30px;
                }
                .list-item__text {
                    flex: 1;
                }
            }
        }
        #tag-overview__no-records {
            padding: 15px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #fff;
        }
        #tag-overview__pagination {
            margin: 50px 0 0 0;
        }
    }
</style>