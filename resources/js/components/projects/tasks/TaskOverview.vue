<template>
    <div id="task-overview">

        <!-- Tasks -->
        <div class="tasks-box">
            <h3 class="content-title centered">{{ titleOpenText }}</h3>
            <div class="tasks elevation-1" v-if="tasksOpen.length > 0">
                <div class="task header">
                    <div class="task-title">{{ titleText }}</div>
                    <div class="task-category">{{ categoryText }}</div>
                    <div class="task-complexity">{{ complexityText }}</div>
                </div>
                <a class="task" v-for="(task, ti) in tasksOpen" :key="ti" :href="task.view_href">
                    <div class="task-title">
                        {{ task.title }}
                    </div>
                    <div class="task-category">
                        {{ task.category.name }}
                    </div>
                    <div class="task-complexity">
                        {{ complexityText }} {{ task.complexity }}
                    </div>
                </a>
            </div>
            <div class="no-tasks elevation-1" v-if="tasksOpen.length === 0">
                {{ noOpenTasksText }}
            </div>
        </div>

        <!-- Tasks in progress -->
        <div class="tasks-box">
            <h3 class="content-title centered">{{ titleInProgressText }}</h3>
            <div class="tasks elevation-1" v-if="tasksInProgress.length > 0">
                <div class="task header">
                    <div class="task-title">{{ titleText }}</div>
                    <div class="task-category">{{ categoryText }}</div>
                    <div class="task-complexity">{{ complexityText }}</div>
                </div>
                <a class="task" v-for="(task, ti) in tasksInProgress" :key="ti" :href="task.view_href">
                    <div class="task-title">
                        {{ task.title }}
                    </div>
                    <div class="task-category">
                        {{ task.category.name }}
                    </div>
                    <div class="task-complexity">
                        {{ complexityText }} {{ task.complexity }}
                    </div>
                </a>
            </div>
            <div class="no-tasks elevation-1" v-if="tasksInProgress.length === 0">
                {{ noInProgressTasksText }}
            </div>
        </div>

        <!-- Tasks completed -->
        <div class="tasks-box">
            <h3 class="content-title centered">{{ titleCompletedText }}</h3>
            <div class="tasks elevation-1" v-if="tasksCompleted.length > 0">
                <div class="task header">
                    <div class="task-title">{{ titleText }}</div>
                    <div class="task-category">{{ categoryText }}</div>
                    <div class="task-complexity">{{ complexityText }}</div>
                </div>
                <a class="task" v-for="(task, ti) in tasksCompleted" :key="ti" :href="task.view_href">
                    <div class="task-title">
                        {{ task.title }}
                    </div>
                    <div class="task-category">
                        {{ task.category.name }}
                    </div>
                    <div class="task-complexity">
                        {{ complexityText }} {{ task.complexity }}/10
                    </div>
                </a>
            </div>
            <div class="no-tasks elevation-1" v-if="tasksCompleted.length === 0">
                {{ noCompletedTasksText }}
            </div>
        </div>

        <!-- Actions -->
        <div id="task-overview__actions">
            <div id="task-overview__actions-right">
                <v-btn color="primary" :href="createTaskHref">
                    <i class="fas fa-plus"></i>
                    {{ submitText }}
                </v-btn>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "tasks",
            "createTaskHref",
            "titleOpenText",
            "titleInProgressText",
            "titleCompletedText",
            "titleText",
            "categoryText",
            "complexityText",
            "noOpenTasksText",
            "noInProgressTasksText",
            "noCompletedTasksText",
            "submitText",
        ],
        data: () => ({
            tag: "[task-overview]",
            tasksOpen: [],
            tasksInProgress: [],
            tasksCompleted: [],
        }),
        computed: {
            
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" tasks: ", this.tasks);
                console.log(this.tag+" create task href: ", this.createTaskHref);
                // console.log(this.tag+" ");
                this.initializeData();
            },
            initializeData() {
                if (this.tasks !== undefined && this.tasks !== null && this.tasks.length > 0) {
                    for (let i = 0; i < this.tasks.length; i++) {
                        if (this.tasks[i].status.name === "open") {
                            this.tasksOpen.push(this.tasks[i]);
                        } else if (this.tasks[i].status.name === "in_progress") {
                            this.tasksInProgress.push(this.tasks[i]);
                        } else if (this.tasks[i].status.completed === "completed") {
                            this.tasksCompleted.push(this.tasks[i]);
                        }
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
    #task-overview {
        .tasks-box {
            margin: 0 0 50px 0;
            &:last-child {
                margin: 0;
            }
            .content-title {
                margin: 0 0 25px 0;
            }
            .tasks {
                border-radius: 3px;
                background-color: #fff;
                .task {
                    display: flex;
                    color: #000000;
                    padding: 15px;
                    flex-direction: row;
                    transition: all .3s;
                    text-decoration: none;
                    box-sizing: border-box;
                    &:hover {
                        background-color: hsl(0, 0%, 95%);
                    }
                    &.header {
                        font-weight: 600;
                        border-bottom: 2px solid rgba(0, 0, 0, 0.1);
                        &:hover {
                            background-color: transparent;
                        }
                    }
                    .task-title, .task-category, .task-complexity {
                        display: flex;
                        flex-direction: row;
                        align-items: center;
                    }
                    .task-title {
                        flex: 3;
                    }
                    .task-category {
                        flex: 1;
                    }
                    .task-complexity {
                        flex: 1;
                        justify-content: flex-end;
                    }
                }
            }
            .no-tasks {
                // width: 500px;
                padding: 15px;
                // margin: 0 auto;
                text-align: center;
                border-radius: 3px;
                box-sizing: border-box;
                background-color: #ffffff;
            }
        }
        #task-overview__actions {
            display: flex;
            flex-direction: row;
            #task-overview__actions-left {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
            }
            #task-overview__actions-right {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
            }
        }
    }
</style>