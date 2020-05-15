<template>
    <div id="messages-inbox">

        <!-- Messages -->
        <div id="messages-inbox__list" class="elevation-1" v-if="paginatedMessages.length > 0">
            <a class="message" v-for="(message, mi) in paginatedMessages" :key="mi" :href="message.view_href">
                <span class="message-icon" v-if="message.read">
                    <i class="fas fa-envelope-open"></i>
                </span>
                <span class="message-icon" v-if="!message.read">
                    <i class="fas fa-envelope"></i>
                </span>
                <span class="message-sender">
                    {{ message.sender.formatted_name }}
                </span>
                <span class="message-subject">
                    {{ message.subject }}
                </span>
                <span class="message-date">
                    {{ message.created_at }}
                </span>
            </a>
        </div>

        <!-- No messages -->
        <div id="messages-inbox__empty" class="elevation-1" v-if="paginatedMessages.length === 0">
            {{ noRecordsText }}
        </div>

        <!-- Pagination -->
        <div id="messages-inbox__pagination" v-if="numPaginatedPages > 1">
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
            "messages",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[messages-inbox]",
            mutableMessages: [],
            paginatedMessages: [],
            pagination: {
                perPage: 10,
                currentPage: 1,
            }
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableMessages.length/this.pagination.perPage);
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
                console.log(this.tag+" messages: ", this.messages);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                console.log(this.tag+" ");
                this.initializeData();
            },
            initializeData() {
                if (this.messages !== undefined && this.messages !== null && this.messages.length > 0) {
                    for (let i = 0; i < this.messages.length; i++) {
                        this.mutableMessages.push(this.messages[i]);
                    }
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedMessages = this.mutableMessages.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #messages-inbox {
        #messages-inbox__list {
            overflow: hidden;
            border-radius: 3px;
            background-color: #fff;
            .message {
                display: flex;
                color: #000000;
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
                    cursor: pointer;
                    background-color: hsl(0, 0%, 98%);
                }
                .message-icon, .message-sender, .message-subject, .message-date {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
                .message-icon {
                    flex: 0 0 25px;
                }
                .message-sender {
                    flex: 0 0 200px;
                }
                .message-subject {
                    flex: 1;
                }
                .message-date {

                }
            }
        }
        #messages-inbox__empty {
            border-radius: 3px;
            padding: 15px 25px;
            text-align: center;
            box-sizing: border-box;
            background-color: #ffffff;
        }
        #messages-inbox__pagination {
            display: flex;
            margin: 30px 0 0 0;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }
    }
</style>