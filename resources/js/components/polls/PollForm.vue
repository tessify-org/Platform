<template>
    <div id="poll-form">

        <!-- General information -->
        <h2 class="content-card__title">{{ strings.general }}</h2>
        <div class="content-card elevation-1 mb">
            <div class="content-card__content">
                
                <!-- Title -->
                <div class="form-field">
                    <v-text-field
                        name="title"
                        :label="strings.title"
                        v-model="form.title"
                        :errors="hasErrors('title')"
                        :error-messages="getErrors('title')">
                    </v-text-field>    
                </div>
                
                <!-- Description -->
                <div class="form-fields">
                    <div class="form-field">
                        <v-textarea
                            name="description_nl"
                            :label="strings.description+' ('+strings.nl+')'"
                            v-model="form.description.nl"
                            :errors="hasErrors('description_nl')"
                            :error-messages="getErrors('description_nl')">
                        </v-textarea>
                    </div>
                    <div class="form-field">
                        <v-textarea
                            name="description_en"
                            :label="strings.description+' ('+strings.en+')'"
                            v-model="form.description.en"
                            :errors="hasErrors('description_en')"
                            :error-messages="getErrors('description_en')">
                        </v-textarea>
                    </div>
                </div>

            </div>
        </div>

        <!-- Questions -->
        <h2 class="content-card__title">{{ strings.questions }}</h2>
        <div class="content-card elevation-1 mb">
            <div class="content-card__content">
                
                <!-- Question list -->
                <div id="question-list" v-if="form.questions.length > 0">
                    <div class="question" v-for="(question, qi) in form.questions" :key="qi">

                        <!-- Question -->
                        <div class="question-inputs">
                            <div class="question-input">
                                <v-text-field
                                    :label="strings.question+' '+(qi+1)+'.'"
                                    v-model="form.questions[qi].question.nl">
                                </v-text-field>
                            </div>
                            <div class="question-input">
                                <v-text-field
                                    :label="strings.question"
                                    v-model="form.questions[qi].question.en">
                                </v-text-field>
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="question-type">
                            <v-select
                                :items="questionTypeOptions"
                                :label="strings.question_type"
                                v-model="form.questions[qi].type">
                            </v-select>
                        </div>

                        <!-- Multiple answers allowed -->
                        <div class="question-multiple" v-if="form.questions[qi].type === 'closed'">
                            <v-checkbox
                                :label="strings.question_multiple"
                                v-model="form.questions[qi].multiple_answers_allowed">
                            </v-checkbox>
                        </div>

                        <!-- Answers -->
                        <div class="question-answers" v-if="form.questions[qi].type === 'closed'">
                            <div class="question-answers__list" v-if="form.questions[qi].answers.length > 0">
                                <div class="question-answer" v-for="(answer, ai) in form.questions[qi].answers" :key="ai">
                                    <div class="question-answer__field">
                                        <v-text-field
                                            hide-details
                                            :label="strings.answer+' ('+strings.nl+')'"
                                            v-model="form.questions[qi].answers[ai].value.nl">
                                        </v-text-field>
                                    </div>
                                    <div class="question-answer__field">
                                        <v-text-field
                                            hide-details
                                            :label="strings.answer+' ('+strings.en+')'"
                                            v-model="form.questions[qi].answers[ai].value.en">
                                        </v-text-field>
                                    </div>
                                </div>
                            </div>
                            <div class="question-answers__no-answers" v-if="form.questions[qi].answers.length === 0">
                                {{ strings.no_answers }}
                            </div>
                            <div class="question-answers__add-button">
                                <v-btn text small color="primary" @click="onClickAddAnswer(qi)">
                                    <i class="fas fa-plus"></i>
                                    {{ strings.add_answer }}
                                </v-btn>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- No questions -->
                <div id="no-questions" v-if="form.questions.length === 0">
                    {{ strings.no_questions }}
                </div>

                <!-- Add question button -->
                <div id="question-list-actions">
                    <v-btn text color="success" @click="onClickAddQuestion">
                        <i class="fas fa-plus"></i>
                        {{ strings.add_question }}
                    </v-btn>
                </div>

            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "poll",
            "strings",
            "oldInput",
            "errors",
        ],
        data: () => ({
            tag: "[poll-form]",
            questionTypeOptions: [],
            form: {
                title: "",
                description: {
                    nl: "",
                    en: "",
                },
                questions: [],
            },
        }),
        computed: {
            encodedQuestions() {
                return JSON.stringify(this.form.questions);
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" poll: ", this.poll);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" errors: ", this.errors);
                this.generateQuestionTypeOptions();
                this.initializeData();
            },
            generateQuestionTypeOptions() {
                this.questionTypeOptions.push({ text: this.strings.question_type_open, value: "open" });
                this.questionTypeOptions.push({ text: this.strings.question_type_closed, value: "closed" });
            },
            initializeData() {
                if (this.poll !== undefined && this.poll !== null) {
                    this.form.title = this.poll.title;
                    this.form.description.nl = this.poll.description.nl;
                    this.form.description.en = this.poll.description.en;
                    if (this.poll.questions !== undefined && this.poll.questions !== null && this.poll.questions.length > 0) {
                        for (let i = 0; i < this.poll.questions.length; i++) {
                            let answers = [];
                            for (let j = 0; j < this.poll.questions[i].answers.length; j++) {
                                answers.push({
                                    value: {
                                        nl: this.poll.questions[i].answers[j].value.nl,
                                        en: this.poll.questions[i].answers[j].value.en,
                                    }
                                });
                            }
                            this.form.questions.push({
                                type: this.poll.questions[i].open_question ? "open" : "closed",
                                multiple_answers_allowed: this.poll.questions[i].allow_multiple_answers,
                                question: {
                                    nl: this.poll.questions[i].question.nl,
                                    en: this.poll.questions[i].question.en,
                                },
                                answers: answers,
                            });
                        }
                    }
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.title !== null) this.form.title = this.oldInput.title;
                    if (this.oldInput.description_nl !== null) this.form.description.nl = this.oldInput.description_nl;
                    if (this.oldInput.description_en !== null) this.form.description.en = this.oldInput.description_en;
                    if (this.oldInput.questions !== null) this.form.questions = JSON.parse(this.oldInput.questions);
                }
            },
            hasErrors(field) {
                if (this.errors !== undefined && this.errors !== null && this.errors.length > 0) {
                    if (this.errors[field] !==  undefined &&  this.errors[field] !== "") {
                        return true;
                    }
                }
                return false;
            },
            getErrors(field) {
                if (this.errors !== undefined && this.errors !== null && this.errors[field] !== undefined && this.errors[field] !== null) {
                    return this.errors[field];
                }
                return [];
            },
            onClickAddQuestion() {
                this.form.questions.push({
                    type: "open",
                    question: {
                        nl: "",
                        en: "",
                    },
                    multiple_answers_allowed: false,
                    answers: [],
                });
            },
            onClickDeleteQuestion(index) {
                this.form.questions.splice(index, 1);
            },
            onClickAddAnswer(index) {
                this.form.questions[index].answers.push({
                    value: {
                        nl: "",
                        en: "",
                    }
                });
            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #poll-form {
        #question-list {
            .question {
                padding: 20px;
                margin: 0 0 25px 0;
                border-radius: 3px;
                box-sizing: border-box;
                background-color: hsl(0, 0%, 98%);
                &:last-child {
                    margin: 0;
                }
                .question-number {
                    font-size: .8em;
                    margin: 0 0 10px 0;
                    color: hsl(0, 0%, 30%);
                }
                .question-inputs {
                    display: flex;
                    margin: 0 -10px;
                    flex-direction: row;
                    .question-input {
                        flex: 1;
                        padding: 0 10px;
                        box-sizing: border-box;
                    }
                }
                .question-input {
                }
                .question-multiple {
                    .v-input--selection-controls {
                        margin-top: 0;
                        margin-bottom: 0;
                    }
                }
                .question-answers {
                    .question-answers__list {
                        .question-answer {
                            display: flex;
                            padding: 15px;
                            margin: 0 0 15px 0;
                            flex-direction: row;
                            box-sizing: border-box;
                            background-color: hsl(0, 0%, 100%);
                            &:last-child {
                                margin: 0;
                            }
                            .question-answer__field {
                                flex: 1;
                                box-sizing: border-box;
                                padding: 0 10px 10px 10px;
                            }
                            .question-answer__delete {
                                
                            }
                        }
                    }
                    .question-answers__no-answers {
                        padding: 20px;
                        box-sizing: border-box;
                    }
                    .question-answers__add-button {
                        display: flex;
                        margin: 15px 0 0 0;
                        flex-direction: row;
                        justify-content: flex-end;
                    }
                }
            }
        }
        #no-questions {
            padding: 20px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: hsl(0, 0%, 98%);
        }
        #question-list-actions {
            display: flex;
            margin: 15px 0 0 0;
            flex-direction: row;
            justify-content: flex-end;
        }
    }
</style>