<template>
    <div id="poll-questions-form__wrapper">
        <div id="poll-questions-form" class="elevation-1">

            <!-- Title -->
            <div id="questions-title">{{ strings.questions }}</div>

            <!-- Questions -->
            <div id="questions" v-if="mutableQuestions.length > 0">
                <div class="question__wrapper" v-for="(question, qi) in mutableQuestions" :key="qi">

                    <!-- Question -->
                    <div class="question">
                        <div class="question-title">{{ strings.question+" "+(qi+1)+". "+question.question[locale] }}</div>
                    </div>

                    <!-- Open question answer -->
                    <div class="answer" v-if="question.open_question">
                        <v-textarea 
                            hide-details
                            :label="strings.answer" 
                            v-model="mutableQuestions[qi].answer">
                        </v-textarea>
                    </div>

                    <!-- Closed (multiselect) question -->
                    <div class="answers" v-if="!question.open_question">
                        <div class="answer-option__wrapper" v-for="(answer, ai) in question.answers" :key="ai">
                            <div class="answer-option" :class="{ 'selected': answerIsSelected(question, answer) }" @click="onClickAnswer(qi, ai)">
                                {{ answer.value[locale] }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- No questions -->
            <div id="no-questions" v-if="mutableQuestions.length === 0">
                {{ strings.no_questions }}
            </div>

            <!-- Form controls -->
            <div class="form-controls">
                <div class="form-controls__left">
                    <v-btn :href="backHref" outlined>
                        <i class="fas fa-arrow-left"></i>
                        {{ strings.cancel }}
                    </v-btn>
                </div>
                <div class="form-controls__right">
                    <v-btn color="success" type="submit" depressed :disabled="submitDisabled">
                        <i class="far fa-save"></i>
                        {{ strings.submit }}
                    </v-btn>
                </div>
            </div>

            <!-- Hidden inputs -->
            <input type="hidden" name="answers" :value="encodedAnswers">

        </div>
    </div>
</template>

<script>
    export default {
        props: [
            "poll",
            "strings",
            "errors",
            "oldInput",
            "locale",
            "backHref",
        ],
        data: () => ({
            tag: "[poll-questions-form]",
            mutableQuestions: [],
        }),
        computed: {
            submitDisabled() {
                for (let i = 0; i < this.mutableQuestions.length; i++) {
                    if (this.mutableQuestions[i].answer === "" || this.mutableQuestions[i].answer.length === 0) {
                        return true;
                    }
                }
                return false;
            },
            encodedAnswers() {
                let out = [];
                for (let i = 0; i < this.mutableQuestions.length; i++) {
                    out.push({
                        question_id: this.mutableQuestions[i].id,
                        answer: this.mutableQuestions[i].answer
                    });
                }
                return JSON.stringify(out);
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" poll: ", this.poll);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" locale: ", this.locale);
                this.initializeData();
            },
            initializeData() {
                if (this.poll !== undefined && this.poll !== null && this.poll.questions !== undefined && this.poll.questions.length > 0) {
                    for (let i = 0; i < this.poll.questions.length; i++) {
                        let question = this.poll.questions[i];
                        if (question.open_question || (!question.open_question && !question.allow_multiple_answers)) {
                            question.answer = "";
                        } else {
                            question.answer = [];
                        }
                        this.mutableQuestions.push(question);
                    }
                }
            },
            answerIsSelected(question, answer) {
                if (question.open_question || !question.allow_multiple_answers) {
                    return question.answer === answer.id;
                } else {
                    return question.answer.includes(answer.id);
                }
            },
            onClickAnswer(questionIndex, answerIndex) {
                let question = this.mutableQuestions[questionIndex];
                let answer = this.mutableQuestions[questionIndex].answers[answerIndex];
                if (question.open_question || !question.allow_multiple_answers) {
                    if (question.answer === answer.id) {
                        this.mutableQuestions[questionIndex].answer = "";
                    } else {
                        this.mutableQuestions[questionIndex].answer = answer.id;
                    }
                } else {
                    if (question.answer.includes(answer.id)) {
                        let index = this.mutableQuestions[questionIndex].answer.indexOf(answer.id);
                        this.mutableQuestions[questionIndex].answer.splice(index, 1);
                    } else {
                        if (!question.allow_multiple_answers) {
                            this.mutableQuestions[questionIndex].answer = [];
                        }
                        this.mutableQuestions[questionIndex].answer.push(answer.id);
                    }
                }
            }
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #poll-questions-form__wrapper {
        margin: 0 0 30px 0;
        #poll-questions-form {
            padding: 30px;
            overflow: hidden;
            border-radius: 3px;
            background-color: #fff;
            #questions-title {
                font-weight: 600;
                font-size: 2em;
            }
            #questions {
                .question__wrapper {
                    padding: 25px;
                    margin: 0 0 30px 0;
                    box-sizing: border-box;
                    background-color: hsl(0, 0%, 98%);
                    &:last-child {
                        margin: 0;
                    }
                    .question {
                        .question-title {
                            font-weight: 600;
                            font-size: 1.2em;
                            margin: 0 0 10px 0;
                        }
                        .question-text {

                        }
                    }
                    .answer {
                        
                    }
                    .answers {
                        display: flex;
                        margin: 0 -10px;
                        flex-direction: row;
                        .answer-option__wrapper {
                            padding: 0 10px;
                            .answer-option {
                                color: #fff;
                                border-radius: 3px;
                                padding: 10px 15px;
                                transition: all .3s;
                                box-sizing: border-box;
                                background-color: hsl(0, 0%, 25%);
                                &:hover {
                                    cursor: pointer;
                                    background-color: hsl(0, 0%, 10%);
                                }
                                &.selected {
                                    background-color: #154273;
                                }
                            }
                        }
                    }
                }
            }
            #no-questions {
                
            }
        }
    }
</style>