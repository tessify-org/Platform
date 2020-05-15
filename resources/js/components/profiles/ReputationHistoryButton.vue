<template>
    <div class="reputation-history-button__wrapper">

        <!-- Button -->
        <v-btn small outlined color="primary" @click="onClickButton">
            {{ buttonText }}
        </v-btn>

        <!-- Dialog -->
        <v-dialog v-model="dialogs.view.show" width="400">
            <div class="dialog" v-if="dialogs.view.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ dialogTitleText }}</h3>
                    <!-- Transactions -->
                    <div class="transaction-list" v-if="paginatedTransactions.length > 0">
                        <div class="transaction" v-for="(transaction, ti) in paginatedTransactions" :key="ti">
                            <span class="transaction-operation" v-if="transaction.type === 'give'">+</span>
                            <span class="transaction-operation" v-if="transaction.type === 'take'">-</span>
                            <span class="transaction-label">{{ transaction.points }}</span>
                            <span class="transaction-text">{{ transaction.text }}</span>
                        </div>
                    </div>
                    <!-- No transactions -->
                    <div class="dialog-text no-transactions" v-if="paginatedTransactions.length === 0">{{ noTransactionsText }}</div>
                    <!-- Pagination -->
                    <div class="dialog-pagination" v-if="numPaginatedPages > 1"> 
                        <v-pagination v-model="pagination.currentPage" :length="numPaginatedPages" total-visible="10"></v-pagination>
                    </div>
                </div>
            </div>
        </v-dialog>

    </div>
</template>

<script>
    export default {
        props: [
            "transactions",
            "buttonText",
            "dialogTitleText",
            "noTransactionsText",
        ],
        data: () => ({
            tag: "[reputation-history-button]",
            mutableTransactions: [],
            paginatedTransactions: [],
            dialogs: {
                view: {
                    show: false,
                },
            },
            pagination: {
                currentPage: 1,
                perPage: 10,
            },
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableTransactions.length/this.pagination.perPage);
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
                console.log(this.tag+" transactions: ", this.transactions);
                console.log(this.tag+" button text: ", this.buttonText);
                console.log(this.tag+" dialog title text: ", this.dialogTitleText);
                console.log(this.tag+" no transactions text: ", this.noTransactionsText);
                this.initializeData();
            },
            initializeData() {
                if (this.transactions !== undefined && this.transactions !== null && this.transactions.length > 0) {
                    for (let i = 0; i < this.transactions.length; i++) {
                        this.mutableTransactions.push(this.transactions[i]);
                    }
                }
                this.paginate();
            },
            onClickButton() {
                this.dialogs.view.show = true;
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedTransactions = this.mutableTransactions.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    .reputation-history-button__wrapper {
        margin: 15px 0 0 0;
    }
    .transaction-list {
        .transaction {
            padding: 10px 0;
            box-sizing: border-box;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            &:last-child {
                border-bottom: 0;
            }
            .transaction-operation {
                margin-right: 5px;
            }
            .transaction-label {
                color: #fff;
                font-size: .8em;
                padding: 2px 5px;
                border-radius: 3px;
                box-sizing: border-box;
                background-color: #333;
            }
            .transaction-text {
                margin-left: 5px;
            }
        }
    }
    .dialog-pagination {
        margin-top: 25px;
    }
</style>