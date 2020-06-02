<template>
    <div id="group-poll-overview">

        <!-- Skills -->
        <div id="poll-list" v-if="mutablePolls.length > 0">
            <a class="poll-list-item" v-for="(poll, pi) in mutablePolls" :key="pi" :href="poll.view_href">
                <span class="poll-list-item__title">{{ poll.title }}</span>
                <span class="poll-list-item__author">{{ poll.user.formatted_name }}</span>
                <span class="poll-list-item__answered">
                    <span class="answered-pill" v-if="!poll.has_voted">
                        {{ strings.not_voted }}
                    </span>
                    <span class="answered-pill" v-if="poll.has_voted">
                        {{ strings.voted }}
                    </span>
                </span>
            </a>
        </div>

        <!-- No skills -->
        <div id="no-polls" v-if="mutablePolls.length === 0">
            {{ strings.no_records }}
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "polls",
            "strings",
        ],
        data: () => ({
            tag: "[group-poll-overview]",
            mutablePolls: [],
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" polls: ", this.polls);
                console.log(this.tag+" strings: ", this.strings);
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
    #group-poll-overview {
        #poll-list {
            .poll-list-item {
                color: #000;
                display: flex;
                padding: 15px 20px;
                flex-direction: row;
                transition: all .3s;
                text-decoration: none;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                .poll-list-item__title {
                    flex: 1;
                }
                .poll-list-item__author {
                    margin: 0 15px 0 0;
                }
                .poll-list-item__answered {
                    .answered-pill {
                        color: #fff;
                        font-size: .8em;
                        padding: 3px 5px;
                        border-radius: 3px;
                        box-sizing: border-box;
                        background-color: #660000;
                    }
                }
                &:hover {
                    background-color: hsl(0, 0%, 95%);
                }
                &:last-child {
                    border-bottom: 0;
                }
            }
        }
        #no-polls {
            padding: 15px 20px;
            box-sizing: border-box;
        }
    }
</style>