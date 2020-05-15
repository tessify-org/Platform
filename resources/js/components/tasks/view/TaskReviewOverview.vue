<template>
    <div id="task-review-overview">

        <!-- Reviews -->
        <div id="task-review-overview__reviews" v-if="paginatedData.length > 0">
            <a class="task-review" v-for="(review, ri) in paginatedData" :key="ri" :href="review.view_href">
                <span class="task-review__author">
                    {{ review.user.formatted_name }}
                </span>
                <span class="task-review__rating">
                    {{ review.rating }}/10
                </span>
                <span class="task-review__date">
                    {{ review.formatted_date }}
                </span>
            </a>
        </div>

        <!-- No reviews -->
        <div id="task-review-overview__no-reviews" v-if="paginatedData.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- Pagination -->
        <div id="task-review-overview__pagination" v-if="numPaginatedPages > 1">
            <v-pagination v-model="pagination.currentPage" :length="numPaginatedPages" total-visible="10"></v-pagination>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "task",
            "reviews",
            "strings",
        ],
        data: () => ({
            tag: "[task-review-overview]",
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
                console.log(this.tag+" reviews: ", this.reviews);
                this.initializeData();
            },
            initializeData() {
                if (this.reviews !== undefined && this.reviews !== null && this.reviews.length > 0) {
                    for (let i = 0; i < this.reviews.length; i++) {
                        this.mutableData.push(this.reviews[i]);
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
    #task-review-overview {
        margin: 30px 0 0 0;
        #task-review-overview__reviews {
            border-radius: 3px;
            border: 1px solid rgba(0, 0, 0, 0.1);
            .task-review {
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
                .task-review__author, .task-review__rating, .task-review__date {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
                .task-review__author {

                }
                .task-review__rating {

                }
                .task-review__date {
                    justify-content: flex-end;
                }
            }
        }
    }
</style>