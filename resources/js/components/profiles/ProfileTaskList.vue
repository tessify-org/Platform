<template>
    <div id="profile-task-list">
        <div id="tasks" v-if="mutableTasks.length > 0">
            <a class="task" v-for="(task, ti) in mutableTasks" :key="ti" :href="task.view_href">
                <span class="task-title">{{ task.title[locale] }}</span>
            </a>
        </div>
        <div id="no-tasks" v-if="mutableTasks.length === 0">
            {{ noRecordsText }}
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            "tasks",
            "locale",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[profile-task-list]",
            mutableTasks: [],
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" tasks: ", this.tasks);
                console.log(this.tag+" locale: ", this.locale);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                this.initializeData();
            },
            initializeData() {
                if (this.tasks !== undefined && this.tasks !== null && this.tasks.length > 0) {
                    for (let i = 0; i < this.tasks.length; i++) {
                        this.mutableTasks.push(this.tasks[i]);
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
    #profile-task-list {
        #tasks {
            .task {
                display: flex;
                color: #000000;
                padding: 10px 15px;
                flex-direction: row;
                transition: all .3s;
                text-decoration: none;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    background-color: hsl(0, 0%, 98%);
                }
                .task-title {
                    flex: 1;
                }
            }
        }
        #no-tasks {
            padding: 10px 15px;
            box-sizing: border-box;
        }
    }
</style>