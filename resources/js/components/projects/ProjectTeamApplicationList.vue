<template>
    <div class="project-team-application-list">

        <!-- List -->
        <div class="application-list" v-if="mutableApplications.length > 0">
            <a class="application" v-for="(application, ai) in mutableApplications" :key="ai" :href="application.view_href">
                <div class="application-status">
                    <div class="application-status__pill">
                        {{ getApplicationStatus(application) }}
                    </div>
                </div>
                <div class="application-role">
                    {{ application.team_role.name }}
                </div>
                <div class="application-date">
                    {{ application.created_at }}
                </div>
            </a>
        </div>

        <!-- Empty -->
        <div class="no-applications" v-if="mutableApplications.length === 0">
            {{ noApplicationsText }}
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "applications",
            "noApplicationsText",
            "acceptedText",
            "rejectedText",
            "pendingText",
        ],
        data: () => ({
            tag: "[project-team-application-list]",
            mutableApplications: [],
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" applications: ", this.applications);
                console.log(this.tag+" accepted text: ", this.acceptedText);
                console.log(this.tag+" rejected text: ", this.rejectedText);
                console.log(this.tag+" pending text: ", this.pendingText);
                console.log(this.tag+" no applications text: ", this.noApplicationsText);
                this.initializeData();
            },
            initializeData() {
                if (this.applications !== undefined && this.applications !== null && this.applications.length > 0) {
                    for (let i = 0; i < this.applications.length; i++) {
                        this.mutableApplications.push(this.applications[i]);
                    }
                }
            },
            getApplicationStatus(application) {
                if (application.processed) {
                    return application.accepted ? this.acceptedText : this.rejectedText;
                } else {
                    return this.pendingText;
                }
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    .project-team-application-list {
        .application-list {
            overflow: hidden;
            border-radius: 3px;
            .application {
                display: flex;
                color: #000;
                padding: 10px 15px;
                flex-direction: row;
                transition: all .3s;
                box-sizing: border-box;
                text-decoration: none;
                background-color: hsl(0, 0%, 95%);
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    background-color: hsl(0, 0%, 90%);
                }
                .application-status, .application-role, .application-date {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                }
                .application-status {
                    flex: 0 0 125px;
                    .application-status__pill {
                        color: #fff;
                        font-size: .9em;
                        padding: 3px 10px;
                        border-radius: 3px;
                        display: inline-block;
                        box-sizing: border-box;
                        background-color: #333;
                    }
                }
                .application-role {
                    flex: 1;
                }
                .application-date {

                }
            }
        }
        .no-applications {

        }
    }
</style>