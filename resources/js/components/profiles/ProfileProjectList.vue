<template>
    <div id="profile-project-list">
        <div id="projects" v-if="mutableProjects.length > 0">
            <a class="project" v-for="(project, pi) in mutableProjects" :key="pi" :href="project.view_href">
                <span class="project-title">{{ project.title[locale] }}</span>
                <span class="project-role">-</span>
            </a>
        </div>
        <div id="no-projects" v-if="mutableProjects.length === 0">
            {{ noRecordsText }}
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            "projects",
            "locale",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[profile-project-list]",
            mutableProjects: [],
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" projects: ", this.projects);
                console.log(this.tag+" locale: ", this.locale);
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
    #profile-project-list {
        #projects {
            .project {
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
                .project-title {
                    flex: 1;
                }
                .project-role {

                }
            }
        }
        #no-projects {
            padding: 10px 15px;
            box-sizing: border-box;
        }
    }
</style>