<template>
    <div id="review-request-overview">

        <!-- Title -->
        <h2 class="content-title">{{ strings.title }}</h2>

        <!-- Review list -->
        <div id="review-request-overview__list" class="elevation-1" v-if="paginatedRequests.length > 0">
            <div class="review-request" v-for="(request, ri) in paginatedRequests" :key="ri" :href="request.view_href">
                <div class="review-request__entity">
                    <div class="entity-type">{{ request.formatted_type }}</div>
                    <a class="entity-name" :href="request.target_href">{{ request.formatted_name }}</a>
                </div>
                <div class="review-request__actions">
                    <a class="accept-button" :href="request.accept_href">
                        {{ strings.accept }}
                    </a>
                    <a class="reject-button" :href="request.reject_href">
                        {{ strings.reject }}
                    </a>
                </div>
            </div>
        </div>

        <!-- No records -->
        <div id="review-request-overview__no-records" class="elevation-1" v-if="paginatedRequests.length === 0">{{ strings.no_records }}</div>

        <!-- Pagination -->
        <div id="review-request-overview__pagination" v-if="numPaginatedPages > 1">
            <v-pagination v-model="pagination.currentPage" :length="numPaginatedPages" total-visible="10"></v-pagination>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "requests",
            "strings",
        ],
        data: () => ({
            tag: "[review-request-overview]",
            mutableRequests: [],
            pagination: {
                perPage: 10,
                currentPage: 1,
            },
            paginatedRequests: [],
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableRequests.length/this.pagination.perPage);
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
                console.log(this.tag+" requests: ", this.requests);
                console.log(this.tag+" strings: ", this.strings);
                this.initializeData();
            },
            initializeData() {
                if (this.requests !== undefined && this.requests !== null && this.requests.length > 0) {
                    for (let i = 0; i < this.requests.length; i++) {
                        this.mutableRequests.push(this.requests[i]);
                    }
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedRequests = this.mutableRequests.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #review-request-overview {
        margin: 0 0 30px 0;
        #review-request-overview__list {
            border-radius: 3px;
            background-color: #fff;
            .review-request {
                display: flex;
                padding: 15px 25px;
                flex-direction: row;
                align-items: center;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                .review-request__entity {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    .entity-type {
                        font-size: .8em;
                        color: hsl(0, 0%, 50%);
                        text-transform: uppercase;
                    }
                    .entity-name {
                        color: #000;
                        font-size: 1.1em;
                        transition: all .3s;
                        text-decoration: none;
                        &:hover {
                            text-decoration: underline;
                        }
                    }
                }
                .review-request__actions {
                    display: flex;
                    flex-direction: row;
                    .accept-button, .reject-button {
                        color: #fff;
                        display: flex;
                        font-size: .9em;
                        padding: 4px 8px;
                        border-radius: 3px;
                        flex-direction: row;
                        transition: all .3s;
                        box-sizing: border-box;
                        text-decoration: none;
                        &:hover {
                            cursor: pointer;
                        }
                    }
                    .accept-button {
                        background-color: #328815;
                        &:hover {
                            background-color: #259200;
                        }
                    }
                    .reject-button {
                        margin: 0 0 0 15px;
                        background-color: #a81717;
                        &:hover {
                            background-color: #ce0000;
                        }
                    }
                }
            }
        }
        #review-request-overview__no-records {
            padding: 25px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #fff;
        }
        #review-request-overview__pagination {

        }
    }
</style>