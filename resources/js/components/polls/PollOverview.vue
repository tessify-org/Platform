<template>
    <div id="poll-overview">

        <!-- List -->
        <div id="poll-overview__list" v-if="mutablePolls.length > 0">
            <div class="poll-wrapper" v-for="(poll, pi) in mutablePolls" :key="pi">
                <a class="poll elevation-1" :href="poll.view_href">
                    <span class="poll-image__wrapper" :style="{ backgroundImage: 'url('+poll.header_image_url+')' }"></span>
                    <span class="poll-text">
                        <span class="poll-title">{{ poll.title }}</span>
                        <span class="poll-description">{{ poll.description !== null ? poll.description[locale] : '-' }}</span>
                    </span>
                </a>
            </div>
        </div>

        <!-- No records -->
        <div id="poll-overview__no-records" v-if="mutablePolls.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- Pagination -->
        <!-- <div id="poll-overview__pagination"></div> -->

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
            tag: "[poll-overview]",
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
    #poll-overview {
        margin-top: 25px;
        #poll-overview__list {
            display: flex;
            flex-wrap: wrap;
            flex-direction: row;
            margin: 0 -25px -50px -25px;
            .poll-wrapper {
                flex: 0 0 33.33%;
                box-sizing: border-box;
                padding: 0 25px 50px 25px;
                .poll {
                    display: block;
                    color: #000000;
                    overflow: hidden;
                    border-radius: 3px;
                    text-decoration: none;
                    background-color: #fff;
                    .poll-image__wrapper {
                        display: block;
                        height: 150px;
                        background-size: cover;
                        background-color: #333;
                        background-repeat: no-repeat;
                        background-position: center center;
                    }
                    .poll-text {
                        padding: 25px;
                        display: block;
                        box-sizing: border-box;
                        .poll-title {
                            display: block;
                            font-size: 1.2em;
                            font-weight: 600;
                        }
                        .poll-description {
                            display: block;
                            font-size: .9em;
                        }
                    }
                }
            }
        }
        #poll-overview__no-records {

        }
        #poll-overview__pagination {

        }
    }
</style>