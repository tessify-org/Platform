<template>
    <div id="poll-results-comp">

        <div class="poll-result" v-for="(result, ri) in mutableResults" :key="ri">
            <div class="poll-result__title">{{ strings.question+" #"+(ri+1)+". "+result.question.question[locale] }}</div>
            <div class="poll-result__answers" v-if="!result.question.open_question">
                <div class="poll-result__answer" v-for="(answer, ai) in result.answers" :key="ai">
                    <div class="answer-text">
                        {{ answer.answer.value[locale] }}
                    </div>
                    <div class="answer-count">
                        {{ answer.num_votes }} {{ voteText(answer.num_votes) }}
                    </div>
                </div>
            </div>
            <div class="poll-result__answers" v-if="result.question.open_question">
                <div class="poll-result__answer" v-for="(answer, ai) in result.answers" :key="ai">
                    {{ answer }}
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "results",
            "strings",
            "locale",
        ],
        data: () => ({
            tag: "[poll-results]",
            mutableResults: [],
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" results: ", this.results);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" locale: ", this.locale);
                this.initializeData();
            },
            initializeData() {
                if (this.results !== undefined && this.results !== null && this.results.length > 0) {
                    for (let i = 0; i < this.results.length; i++) {
                        this.mutableResults.push(this.results[i]);
                    }
                }
            },
            voteText(num_votes) {
                return num_votes === 1 ? this.strings.vote : this.strings.votes;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #poll-results-comp {
        .poll-result {
            padding: 20px 25px;
            border-radius: 3px;
            margin: 0 0 30px 0;
            box-sizing: border-box;
            background-color: hsl(0, 0%, 98%);
            &:last-child {
                margin: 0;
            }
            .poll-result__title {
                font-size: 1.2em;
                font-weight: 600;
                margin: 0 0 10px 0;
            }
            .poll-result__answers {
                background-color: hsl(0, 0%, 100%);
                .poll-result__answer {
                    padding: 15px;
                    display: flex;
                    flex-direction: row;
                    box-sizing: border-box;
                    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                    &:last-child {
                        border-bottom: 0;
                    }
                    .answer-text {
                        flex: 1;
                    }
                    .answer-count {
                        padding: 0 0 0 15px;
                    }
                }
            }
        }
    }
</style>