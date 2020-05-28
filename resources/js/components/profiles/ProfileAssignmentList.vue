<template>
    <div id="profile-assignment-list">

        <!-- Current assignment -->
        <div id="current-assignment" v-if="currentAssignment !== null">
            <div id="current-assignment__label">{{ strings.current_assignment }}</div>
            <div id="current-assignment__card">
                <div id="card-image__wrapper">
                    <div id="card-image">
                        {{ currentAssignment.organization.ministry.abbreviation[locale] }}
                    </div>
                </div>
                <div id="card-text__wrapper">
                    <div id="job-title">{{ currentAssignment.title }}</div>
                    <div id="job-company">{{ currentAssignment.organization.name[locale] }}</div>
                    <div id="job-description" v-if="currentAssignment.description !== ''">
                        "{{ currentAssignment.description }}"
                    </div>
                </div>
            </div>
        </div>

        <!-- Previous assignments -->
        <div id="previous-assignments" v-if="previousAssignments.length > 0">
            <div id="previous-assignments__label">{{ strings.previous_assignments }}</div>
            <div id="previous-assignments__card">
                <div class="assignment" v-for="(assignment, ai) in previousAssignments" :key="ai" @click="onClickPreviousAssignment(ai)">
                    <div class="assignment-title">{{ assignment.title }}</div>
                    <div class="assignment-company">{{ assignment.organization.name[locale] }}</div>
                </div>
            </div>
        </div>

        <!-- No assignments -->
        <div id="no-assignments" v-if="currentAssignment === null && previousAssignments.length === 0">
            {{ strings.no_assignments }}
        </div>
        
        <!-- View previous assignment dialog -->
        <v-dialog v-model="dialogs.view_prev.show" width="700">
            <div class="dialog" v-if="this.dialogs.view_prev.index !== null">
                <div class="dialog__close-button" @click="dialogs.view_prev.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <div class="dialog-content">
                    <h3 class="dialog-title">{{ strings.assignment }}</h3>
                    <div class="dialog-text">
                        <div class="details compact bordered mb-0">
                            <div class="detail">
                                <div class="key">{{ strings.ministry }}</div>
                                <div class="val">{{ previousAssignments[this.dialogs.view_prev.index].organization.ministry.name[locale] }}</div>
                            </div>
                            <div class="detail">
                                <div class="key">{{ strings.organization }}</div>
                                <div class="val">{{ previousAssignments[this.dialogs.view_prev.index].organization.name[locale] }}</div>
                            </div>
                            <div class="detail">
                                <div class="key">{{ strings.department }}</div>
                                <div class="val">{{ previousAssignments[this.dialogs.view_prev.index].department.name[locale] }}</div>
                            </div>
                            <div class="detail">
                                <div class="key">{{ strings.employment_type }}</div>
                                <div class="val">{{ previousAssignments[this.dialogs.view_prev.index].type.label[locale] }}</div>
                            </div>
                            <div class="detail">
                                <div class="key">{{ strings.function }}</div>
                                <div class="val">{{ previousAssignments[this.dialogs.view_prev.index].title }}</div>
                            </div>
                            <div class="detail">
                                <div class="key">{{ strings.duration }}</div>
                                <div class="val">{{ previousAssignments[this.dialogs.view_prev.index].formatted_start_date+" t/m "+previousAssignments[this.dialogs.view_prev.index].formatted_end_date }}</div>
                            </div>
                            <div class="detail">
                                <div class="key">{{ strings.description }}</div>
                                <div class="val">{{ previousAssignments[this.dialogs.view_prev.index].description }}</div>
                            </div>
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
            "assignments",
            "locale",
            "strings",
        ],
        data: () => ({
            tag: "[profile-assignment-list]",
            currentAssignment: null,
            previousAssignments: [],
            dialogs: {
                view_prev: {
                    show: false,
                    index: null,
                }
            },
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" assignments: ", this.assignments);
                console.log(this.tag+" locale: ", this.locale);
                console.log(this.tag+" strings: ", this.strings);
                this.initializeData();
            },
            initializeData() {
                if (this.assignments !== undefined && this.assignments !== null && this.assignments.length > 0) {
                    for (let i = 0; i < this.assignments.length; i++) {
                        if (this.assignments[i].current === true) {
                            this.currentAssignment = this.assignments[i];
                        } else {
                            this.previousAssignments.push(this.assignments[i]);
                        }
                    }
                }
            },
            onClickPreviousAssignment(index) {
                this.dialogs.view_prev.index = index;
                this.dialogs.view_prev.show = true;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #profile-assignment-list {

        #current-assignment {
            padding: 15px;
            box-sizing: border-box;
            #current-assignment__label {
                margin: 0 0 10px 0;
            }
            #current-assignment__card {
                display: flex;
                padding: 10px;
                border-radius: 3px;
                flex-direction: row;
                box-sizing: border-box;
                background-color: hsl(0, 0%, 95%);
                #card-image__wrapper {
                    flex: 0 0 70px;
                    #card-image {
                        display: block;
                        width: 70px;
                        height: 70px;
                        color: #fff;
                        display: flex;
                        border-radius: 3px;
                        align-items: center;
                        justify-content: center;
                        background-color: #666;
                        text-transform: uppercase;
                    }
                }
                #card-text__wrapper {
                    flex: 1;
                    margin: 0 0 0 15px;
                    #job-title {
                        font-weight: 500;
                        font-size: 1.2em;
                        line-height: 1em;
                        margin: 3px 0 2px 0;
                    }
                    #job-company {
                        font-size: .9em;
                        margin: 0 0 5px 0;
                    }
                    #job-description {
                        font-size: .9em;
                    }
                }
            }
        }
        #previous-assignments {
            padding: 15px;
            box-sizing: border-box;
            #previous-assignments__label {
                margin: 0 0 10px 0;
            }
            #previous-assignments__card {
                overflow: hidden;
                border-radius: 3px;
                background-color: hsl(0, 0%, 95%);
                .assignment {
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
                        background-color: hsl(0, 0%, 90%);
                    }
                    .assignment-title {
                        flex: 1;
                    }   
                    .assignment-company {

                    }
                }
            }
        }
        #no-assignments {
            padding: 10px 15px;
            box-sizing: border-box;
        }
    }
</style>