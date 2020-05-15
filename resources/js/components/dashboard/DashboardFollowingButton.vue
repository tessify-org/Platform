<template>
    <div class="dashboard-following-button__wrapper">
        
        <!-- Button -->
        <div class="dashboard-following-button elevation-1" @click="onClickView">
            <div class="following-button__icon" v-html="icon"></div>
            <div class="following-button__text">{{ count+" "+text }}</div>
        </div>

        <!-- Dialog -->
        <v-dialog v-model="dialogs.view.show" width="500">
            <div class="dialog" v-if="this.dialogs.view.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ dialogTitleText }}</h3>
                    <!-- Paginated list of whatever we're showing -->
                    <div class="dialog-list" v-if="paginatedData.length > 0">
                        <a class="dialog-list-item" v-for="(entry, ei) in paginatedData" :key="ei" :href="entry.view_href">
                            {{ entry.text }}
                        </a>
                    </div>
                    <!-- No records -->
                    <div class="dialog-text" v-if="paginatedData.length === 0">
                        {{ dialogNoRecordsText }}
                    </div>
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
            "icon",
            "text",
            "data",
            "dialogTitleText",
            "dialogNoRecordsText",
        ],
        data: () => ({
            tag: "[dashboard-following-button]",
            mutableData: [],
            paginatedData: [],
            dialogs: {
                view: {
                    show: false,
                },
            },
            pagination: {
                perPage: 10,
                currentPage: 1,
            },
        }),
        computed: {
            count() {
                return this.data !== undefined && this.data !== null ? this.data.length : 0;
            },
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
                console.log(this.tag+" icon: ", this.icon);
                console.log(this.tag+" text: ", this.text);
                console.log(this.tag+" data: ", this.data);
                console.log(this.tag+" dialog title text: ", this.dialogTitleText);
                console.log(this.tag+" dialog no records text: ", this.dialogNoRecordsText);
                this.initializeData();
            },
            initializeData() {
                if (this.data !== undefined && this.data !== null && this.data.length > 0) {
                    for (let i = 0; i < this.data.length; i++) {
                        this.mutableData.push(this.data[i]);
                    }
                }
                this.paginate();
            },
            onClickView() {
                this.dialogs.view.show = true;
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
    .dashboard-following-button__wrapper {
        .dashboard-following-button {
            display: flex;
            padding: 10px 13px;
            border-radius: 3px;
            flex-direction: row;
            box-sizing: border-box;
            background-color: #fff;
            transition: all .3s;
            &:hover {
                cursor: pointer;
                background-color: hsl(0, 0%, 95%);
            }
            .following-button__icon {
                display: flex;
                font-size: 1em;
                align-items: center;
                justify-content: center;
            }
            .following-button__text {
                display: flex;
                font-size: .9em;
                margin: 0 0 0 10px;
                flex-direction: row;
                align-items: center;
            }
        }
    }
</style>