<template>
    <div id="group-overview">

        <!-- List -->
        <div id="group-overview__list" v-if="paginatedGroups.length > 0">
            <div class="group-wrapper" v-for="(group, gi) in paginatedGroups" :key="gi">
                <a class="group elevation-1" :href="group.view_href">
                    <span class="group-image" :style="{ backgroundImage: 'url('+group.header_image_url+')' }">
                        <span class="group-image-overlay"></span>
                        <span class="group-indicators">
                            <span class="group-indicator">
                                <span class="group-indicator__icon">
                                    <i class="fas fa-users"></i>
                                </span>
                                <span class="group-indicator__count">
                                    {{ group.members.length }}
                                </span>
                            </span>
                        </span>
                    </span>
                    <span class="group-text">
                        <h3 class="group-name">{{ group.name }}</h3>
                        <p class="group-description">{{ getGroupDescription(group) }}</p>
                    </span>
                </a>
            </div>
        </div>

        <!-- Pagination -->
        <div id="group-overview__pagination" v-if="numPaginatedPages > 1">
            <v-pagination v-model="pagination.currentPage" :length="numPaginatedPages" total-visible="10"></v-pagination>
        </div>

        <!-- No records -->
        <div id="group-overview__no-records" v-if="paginatedGroups.length === 0">
            {{ strings.no_records }}
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "groups",
            "strings",
            "locale",
        ],
        data: () => ({
            tag: "[group-overview]",
            mutableGroups: [],
            paginatedGroups: [],
            pagination: {
                perPage: 1,
                currentPage: 1,
            },
            descLimit: 100,
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableGroups.length / this.pagination.perPage);
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
                console.log(this.tag+" groups: ", this.groups);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" locale: ", this.locale);
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
            getGroupDescription(group) {
                let desc = group.description[this.locale];
                if (desc.length > this.descLimit) {
                    return desc.substr(0, this.descLimit)+"...";
                } else {
                    return desc;
                }
            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #group-overview {
        #group-overview__list {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            justify-content: center;
            margin: 0 -25px -50px -25px;
            .group-wrapper {
                flex: 0 0 33.33%;
                box-sizing: border-box;
                padding: 0 25px 50px 25px;
                .group {
                    display: block;
                    overflow: hidden;
                    color: #000000;
                    border-radius: 3px;
                    position: relative;
                    text-decoration: none;
                    background-color: #fff;
                    &:hover {
                        .group-bg-overlay {
                            background-color: rgba(255, 255, 255, 0.6);
                        }
                    }
                    .group-image {
                        width: 100%;
                        height: 150px;
                        display: block;
                        position: relative;
                        background-size: cover;
                        background-color: #fff;
                        background-repeat: no-repeat;
                        background-position: center center;
                        .group-image-overlay {
                            top: 0;
                            left: 0;
                            z-index: 1;
                            width: 100%;
                            height: 100%;
                            position: absolute;
                            transition: all .3s;
                            background-color: rgba(0, 0, 0, 0.5);
                        }
                        .group-indicators {
                            left: 0;
                            bottom: 0;
                            z-index: 2;
                            width: 100%;
                            padding: 25px 25px;
                            position: absolute;
                            box-sizing: border-box;
                            .group-indicator {
                                display: flex;
                                flex-direction: row;
                                color: #fff;
                                .group-indicator__icon {
                                    margin: 0 8px 0 0;
                                }
                                .group-indicator__text {

                                }
                                &:last-child {
                                    margin: 0;
                                }
                            }
                        }
                    }
                    .group-text {
                        height: 130px;
                        padding: 30px;
                        display: block;
                        box-sizing: border-box;
                        .group-name {
                            font-size: 1.4em;
                        }
                    }
                }
            }
        }
        #group-overview__pagination {
            margin: 50px 0 0 0;
        }
        #group-overview__no-records {
            text-align: center;
            font-size: 1.5em;
        }
    }
</style>