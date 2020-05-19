<template>
    <div id="dashboard-my-projects">

        <div id="my-projects__list" v-if="mutableProjects.length > 0">
            <a class="my-project" v-for="(project, ti) in mutableProjects" :key="ti" :href="project.view_href">
                <span class="my-project__title">
                    {{ project.title[locale] }}
                </span>
                <span class="my-project__status">
                    {{ project.status.label[locale] }}
                </span>
            </a>
        </div>

        <div id="my-projects__empty" v-if="mutableProjects.length === 0">
            {{ noRecordsText }}
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "locale",
            "projects",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[dashboard-my-projects]",
            mutableProjects: [],
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" locale: ", this.locale);
                console.log(this.tag+" projects: ", this.projects);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                this.initializeData();
            },
            initializeData() {
                if (this.projects !== undefined && this.projects !== null && this.projects.length > 0) {
                    for (let i = 0; i < this.projects.length; i++) {
                        this.mutableProjects.push(this.projects[i]);
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
    #dashboard-my-projects {
        #my-projects__list {
            .my-project {
                display: flex;
                color: #000;
                padding: 10px 15px;
                flex-direction: row;
                align-items: center;
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
                .my-project__title {
                    flex: 1;
                }
                .my-project__status {

                }
            }
        }
        #my-projects__empty {
            padding: 15px;
            box-sizing: border-box;
        }
    }
</style>