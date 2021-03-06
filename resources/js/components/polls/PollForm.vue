<template>
    <div id="poll-form">

        <!-- Publication information -->
        <h2 class="content-card__title">{{ strings.publishing }}</h2>
        <div class="content-card elevation-1 mb">
            <div class="content-card__content">
                
                <!-- Parent type -->
                <div class="form-field">
                    <v-select
                        :label="strings.parent"
                        v-model="form.parent_type"
                        :items="parentOptions">
                    </v-select>
                    <input type="hidden" name="parent_type" :value="form.parent_type">
                </div>

                <!-- Parent group -->
                <div class="form-field" v-if="form.parent_type === 'group'">
                    <v-select
                        :label="strings.parent_group"
                        v-model="form.parent_id"
                        :items="groupOptions"
                        :error="!hasSelectedGroup">
                    </v-select>
                </div>
                <input type="hidden" name="parent_id" :value="form.parent_id">

                <!-- Draft version -->
                <div class="form-field checkbox">
                    <div id="draft-checkbox">
                        <div id="draft-checkbox__input">
                            <v-checkbox
                                :label="strings.draft"
                                v-model="form.draft"
                                hide-details>
                            </v-checkbox>
                        </div>
                        <div id="draft-checkbox__info">
                            <v-tooltip right>
                                <template v-slot:activator="{ on }">
                                    <span v-on="on">
                                        <i class="fas fa-info-circle"></i>
                                    </span>
                                </template>
                                <span>{{ strings.draft_help }}</span>
                            </v-tooltip>
                        </div>
                    </div>
                    <input type="hidden" name="draft" :value="form.draft">
                </div>

            </div>
        </div>

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

                <!-- Header bg image -->
                <div class="image-field no-margin">
                    <div class="image-field__label">{{ strings.header_image }}</div>
                    <div class="image-field__image-wrapper">
                        <div class="image-field__image" :style="{ backgroundImage: 'url('+headerImageUrl+')' }"></div>
                    </div>
                    <div class="image-field__input">
                        <input type="file" name="header_image">
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
                                    :label="strings.question+' '+(qi+1)+'. '+strings.nl"
                                    v-model="form.questions[qi].question.nl">
                                </v-text-field>
                            </div>
                            <div class="question-input">
                                <v-text-field
                                    :label="strings.question+' '+(qi+1)+'. '+strings.en"
                                    v-model="form.questions[qi].question.en">
                                </v-text-field>
                            </div>
                        </div>

                        <!-- Type -->
                        <div class="question-type">
                            <v-select
                                :items="questionTypeOptions"
                                :label="strings.question_type"
                                v-model="form.questions[qi].type"   >
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
                            <div class="question-answers__label">{{ strings.question_answers }}</div>
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
                                    <div class="question-answer__remove">
                                        <div class="remove-button" @click="onClickRemoveAnswer(qi, ai)">
                                            <i class="fas fa-times"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="question-answers__no-answers" v-if="form.questions[qi].answers.length === 0">
                                {{ strings.no_answers }}
                            </div>
                            <div class="question-answers__add-button">
                                <v-btn text small color="primary" @click="onClickAddAnswer(qi)">
                                    <i class="fas fa-plus"></i>
                                    {{ strings.question_add_answer }}
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
        
        <!-- Hidden inputs -->
        <input type="hidden" name="questions" :value="encodedQuestions">
        
        <!-- Form controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn text :href="backHref">
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.cancel }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn color="success" type="submit" :disabled="submitDisabled">
                    <i class="fas fa-save"></i>
                    {{ strings.submit }}
                </v-btn>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "poll",
            "groups",
            "strings",
            "oldInput",
            "errors",
            "backHref",
            "defaultHeaderImageUrl",
        ],
        data: () => ({
            tag: "[poll-form]",
            parentOptions: [],
            groupOptions: [],
            questionTypeOptions: [],
            form: {
                parent_type: "",
                parent_id: 0,
                title: "",
                description: {
                    nl: "",
                    en: "",
                },
                draft: false,
                private: false,
                questions: [],
            },
        }),
        computed: {
            encodedQuestions() {
                return JSON.stringify(this.form.questions);
            },
            headerImageUrl() {
                if (this.poll !== undefined && this.poll !== null) {
                    return this.poll.header_image_url;
                } else {
                    return this.defaultHeaderImageUrl;
                }
            },
            hasSelectedGroup() {
                return this.form.parent_type !== "" && this.form.parent_id > 0;
            },
            submitDisabled() {
                return (this.form.parent_type !== "" && this.form.parent_id === 0) || this.form.questions.length === 0;
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" poll: ", this.poll);
                console.log(this.tag+" groups: ", this.groups);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" back href: ", this.backHref);
                this.generateQuestionTypeOptions();
                this.generateParentOptions();
                this.generateGroupOptions();
                this.initializeData();
            },
            initializeData() {
                if (this.poll !== undefined && this.poll !== null) {
                    this.form.title = this.poll.title;
                    this.form.description.nl = this.poll.description.nl;
                    this.form.description.en = this.poll.description.en;
                    this.form.private = this.poll.public === false ? true : false;
                    this.form.draft = this.poll.published === false ? true : false;
                    if (this.poll.parent !== undefined && this.poll.parent !== null) {
                        if (this.poll.pollable_type === "App\\Models\\Group") {
                            this.form.parent_type = "group";
                            this.form.parent_id = this.poll.parent.id;
                        }
                    }
                    if (this.poll.questions !== undefined && this.poll.questions !== null && this.poll.questions.length > 0) {
                        for (let i = 0; i < this.poll.questions.length; i++) {
                            let answers = [];
                            for (let j = 0; j < this.poll.questions[i].answers.length; j++) {
                                answers.push({
                                    id: this.poll.questions[i].answers[j].id,
                                    value: {
                                        nl: this.poll.questions[i].answers[j].value.nl,
                                        en: this.poll.questions[i].answers[j].value.en,
                                    }
                                });
                            }
                            this.form.questions.push({
                                id: this.poll.questions[i].id,
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
            generateParentOptions() {
                this.parentOptions.push({ text: this.strings.no_parent, value: "" });
                this.parentOptions.push({ text: this.strings.group, value: "group" });
            },
            generateGroupOptions() {
                if (this.groups !== undefined && this.groups !== null && this.groups.length > 0) {
                    this.groupOptions.push({
                        text: this.strings.select_parent_group,
                        value: 0,
                    });
                    for (let i = 0; i < this.groups.length; i++) {
                        this.groupOptions.push({
                            text: this.groups[i].name,
                            value: this.groups[i].id,
                        });
                    }
                } else {
                    this.groupOptions.push({
                        text: this.strings.no_parent_groups,
                        value: 0
                    });
                }
            },
            generateQuestionTypeOptions() {
                this.questionTypeOptions.push({ text: this.strings.question_type_open, value: "open" });
                this.questionTypeOptions.push({ text: this.strings.question_type_closed, value: "closed" });
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
                    id: null,
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
                    id: null,
                    value: {
                        nl: "",
                        en: "",
                    }
                });
            },
            onClickRemoveAnswer(questionIndex, answerIndex) {
                this.form.questions[questionIndex].answers.splice(answerIndex, 1);
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
                    .question-answers__label {
                        font-size: .9em;
                        margin: 0 0 5px 0;
                        color: hsl(0, 0%, 45);
                    }
                    .question-answers__list {
                        .question-answer {
                            display: flex;
                            padding: 15px;
                            margin: 0 0 15px 0;
                            flex-direction: row;
                            align-items: center;
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
                            .question-answer__remove {
                                display: flex;
                                flex-direction: row;
                                align-items: center;
                                .remove-button {
                                    cursor: pointer;
                                    padding: 0 10px;
                                    box-sizing: border-box;
                                }
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
    #draft-checkbox {
        display: flex;
        flex-direction: row;
        align-items: center;
        #draft-checkbox__input {
            margin: 0 10px 0 0;
        }
        #draft-checkbox__info {
            padding: 1px 0 0 0;
        }
    }
</style>