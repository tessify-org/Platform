<template>
    <div id="notification-overview">

        <!-- List -->
        <div id="notification-list" class="elevation-1" v-if="paginatedNotifications.length > 0">
            <div class="notification" v-for="(notification, ni) in paginatedNotifications" :key="ni" @click="onClickView(ni)">
                <div class="notification-title">{{ notification.title }}</div>
                <div class="notification-date">{{ notification.created_at }}</div>
            </div>
        </div>

        <!-- No notifications -->
        <div id="no-notifications" class="elevation-1" v-if="paginatedNotifications.length === 0">
            {{ noRecordsText }}
        </div>

        <!-- Footer -->
        <div id="notification-overview__footer">
            <div id="notification-overview__footer-left">

                <!-- Pagination -->
                <div id="notification-pagination" v-if="numPaginatedPages > 1">
                    <v-pagination
                        v-model="pagination.currentPage"
                        :length="numPaginatedPages"
                        total-visible="10">
                    </v-pagination>
                </div>

            </div>
            <div id="notification-overview__footer-right" v-if="hasNotifications">

                <!-- Clear -->
                <v-btn color="red" dark :href="clearHref">
                    <i class="fas fa-trash-alt"></i>
                    {{ clearText }}
                </v-btn>

            </div>
        </div>
        
        <!-- Dialog -->
        <v-dialog v-model="dialogs.view.show" width="650">
            <div class="dialog" v-if="dialogs.view.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ dialogTitleText }}</h3>
                    <!-- Notification details -->
                    <div id="notification-details">
                        <!-- Title -->
                        <div class="notification-detail">
                            <div class="notification-detail__label">{{ titleText }}</div>
                            <div class="notification-detail__value">{{ paginatedNotifications[dialogs.view.index].title }}</div>
                        </div>
                        <!-- Description -->
                        <div class="notification-detail" v-if="paginatedNotifications[dialogs.view.index].description !== null">
                            <div class="notification-detail__label">{{ descriptionText }}</div>
                            <div class="notification-detail__value">{{ paginatedNotifications[dialogs.view.index].description }}</div>
                        </div>
                        <!-- Received on -->
                        <div class="notification-detail">
                            <div class="notification-detail__label">{{ receivedOnText }}</div>
                            <div class="notification-detail__value">{{ paginatedNotifications[dialogs.view.index].created_at }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </v-dialog>

    </div>
</template>

<script>
    export default {
        props: [
            "notifications",
            "noRecordsText",
            "clearText",
            "clearHref",
            "dialogTitleText",
            "titleText",
            "descriptionText",
            "receivedOnText",
        ],
        data: () => ({
            tag: "[notification-overview]",
            mutableNotifications: [],
            paginatedNotifications: [],
            pagination: {
                perPage: 10,
                currentPage: 1,
            },
            dialogs: {
                view: {
                    index: null,
                    show: false,
                }
            }
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableNotifications.length/this.pagination.perPage);
            },
            hasNotifications() {
                return this.mutableNotifications.length > 0;
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
                console.log(this.tag+" notifications: ", this.notifications);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                console.log(this.tag+" clear text: ", this.clearText);
                console.log(this.tag+" clear href: ", this.clearHref);
                console.log(this.tag+" dialog title text: ", this.dialogTitleText);
                console.log(this.tag+" title text: ", this.titleText);
                console.log(this.tag+" description text: ", this.descriptionText);
                console.log(this.tag+" received on text: ", this.receivedOnText);
                this.initializeData();
            },
            initializeData() {
                if (this.notifications !== undefined && this.notifications !== null && this.notifications.length > 0) {
                    for (let i = 0; i < this.notifications.length; i++) {
                        this.mutableNotifications.push(this.notifications[i]);
                    }
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedNotifications = this.mutableNotifications.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
            onClickView(index) {
                this.dialogs.view.index = index;
                this.dialogs.view.show = true;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #notification-overview {
        #notification-list {
            overflow: hidden;
            border-radius: 3px;
            background-color: #fff;
            .notification {
                display: flex;
                padding: 10px 15px;
                flex-direction: row;
                align-items: center;
                transition: all .3s;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    cursor: pointer;
                    background-color: hsl(0, 0%, 98%);
                }
                .notification-title {
                    flex: 1;
                }
                .notification-date {

                }
            }
        }
        #no-notifications {
            border-radius: 3px;
            padding: 15px 25px;
            text-align: center;
            box-sizing: border-box;
            background-color: #ffffff;
        }
        #notification-overview__footer {
            display: flex;
            margin: 30px 0 0 0;
            flex-direction: row;
            #notification-overview__footer-left {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
                #notification-pagination {
                    display: flex;
                    margin: 30px 0 0 0;
                    flex-direction: row;
                    align-items: center;
                    justify-content: center;
                }
            }
            #notification-overview__footer-right {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
            }
        }
    }
    #notification-details {
        .notification-detail {
            margin: 0 0 15px 0;
            &:last-child {
                margin: 0;
            }
            .notification-detail__label {
                font-size: .9em;
                color: hsl(0, 0%, 45%);
                margin: 0 0 5px 0;
            }
            .notification-detail__value {
                
            }
        }
    }
</style>