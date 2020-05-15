<template>
    <div id="review-overview">

        <!-- Title -->
        <h2 class="content-title">{{ strings.title }}</h2>

        <!-- List -->
        <div id="review-overview__list" class="elevation-1" v-if="paginatedReviews.length > 0">
            <a class="review" v-for="(review, ri) in paginatedReviews" :key="ri" :href="review.view_href">
                <span class="review-entity">
                    <span class="review-entity__type">{{ review.formatted_type }}</span>
                    <span class="review-entity__name">{{ review.formatted_name }}</span>
                </span>
                <span class="review-date">
                    {{ review.formatted_date }}
                </span>
            </a>
        </div>

        <!-- No reviews -->
        <div id="review-overview__no-records" class="elevation-1" v-if="paginatedReviews.length === 0">{{ strings.no_records }}</div>
​
        <!-- Pagination -->
        <div id="review-overview__pagination" v-if="numPaginatedPages > 1">
            <v-pagination v-model="pagination.currentPage" :length="numPaginatedPages" total-visible="10"></v-pagination>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "reviews",
            "strings",
        ],
        data: () => ({
            tag: "[review-overview]",
            mutableReviews: [],
            pagination: {
                perPage: 10,
                currentPage: 1,
            },
            paginatedReviews: [],
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableReviews.length/this.pagination.perPage);
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
                console.log(this.tag+" reviews: ", this.reviews);
                console.log(this.tag+" strings: ", this.strings);
                this.initializeData();
            },
            initializeData() {
                if (this.reviews !== undefined && this.reviews !== null && this.reviews.length > 0) {
                    for (let i = 0; i < this.reviews.length; i++) {
                        this.mutableReviews.push(this.reviews[i]);
                    }
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedReviews = this.mutableReviews.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #review-overview {
        #review-overview__list {
            border-radius: 3px;
            background-color: #fff;
            .review {
                display: flex;
                color: #000;
                padding: 15px 25px;
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
                    background-color: hsl(0, 0%, 95%);
                }
                .review-entity {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    .review-entity__type {
                        font-size: .8em;
                        color: hsl(0, 0%, 50%);
                        text-transform: uppercase;
                    }
                    .review-entity__name {
                        font-size: 1.1em;
                    }
                }
                .review-data {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
            }

        }
        #review-overview__no-records {
            padding: 25px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #fff;
        }
        #review-overview__pagination {
            
        }
    }
</style>