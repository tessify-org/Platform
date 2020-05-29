<template>
    <div id="my-poll-overview" class="elevation-1">
        <div id="my-poll-overview__poll-list" v-if="mutablePolls.length > 0">
            <div class="my-poll-overview__poll header">
                <div class="my-poll-overview__poll-title">{{ strings.title }}</div>
                <div class="my-poll-overview__poll-status">{{ strings.status }}</div>
                <div class="my-poll-overview__poll-public">{{ strings.privacy }}</div>
                <div class="my-poll-overview__poll-votes">{{ strings.num_votes }}</div>
            </div>
            <a class="my-poll-overview__poll" v-for="(poll, pi) in mutablePolls" :key="pi" :href="poll.view_href">
                <span class="my-poll-overview__poll-title">
                    {{ poll.title }}
                </span>
                <span class="my-poll-overview__poll-status">
                    {{ poll.status.name[locale] }}
                </span>
                <span class="my-poll-overview__poll-public">
                    {{ poll.public ? strings.public : strings.private }}
                </span>
                <span class="my-poll-overview__poll-votes">
                    {{ poll.num_votes }} {{ poll.num_votes === 1 ? strings.vote : strings.votes }}
                </span>
            </a>
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            "polls",
            "strings",
            "locale",
        ],
        data: () => ({
            tag: "[my-poll-overview]",
            mutablePolls: [], 
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" polls: ", this.polls);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" locale: ", this.locale);
                this.initializeData();
            },
            initializeData() {
                if (this.polls !== undefined && this.polls !== null && this.polls.length > 0) {
                    for (let i = 0; i < this.polls.length; i++) {
                        this.mutablePolls.push(this.polls[i]);
                    }
                }
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #my-poll-overview {
        border-radius: 3px;
        margin: 25px 0 50px 0;
        background-color: #fff;
        #my-poll-overview__poll-list {
            .my-poll-overview__poll {
                display: flex;
                color: #000;
                padding: 10px 15px;
                flex-direction: row;
                align-items: center;
                transition: all .3s;
                text-decoration: none;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &.header {
                    font-weight: 600;
                    &:hover {
                        background-color: transparent;
                    }
                }
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    background-color: hsl(0, 0%, 95%);
                }
                .my-poll-overview__poll-title {
                    flex: 2;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
                .my-poll-overview__poll-status {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
                .my-poll-overview__poll-public {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
                .my-poll-overview__poll-votes {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    justify-content: flex-end;
                }
            }
        }
        #my-poll-overview__ {

        }
        #my-poll-overview__ {

        }
    }
</style>