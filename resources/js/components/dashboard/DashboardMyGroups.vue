<template>
    <div id="dashboard-my-groups">

        <div id="my-groups__list" v-if="mutableGroups.length > 0">
            <a class="my-group" v-for="(group, gi) in mutableGroups" :key="gi" :href="group.view_href">
                <span class="my-group__title">
                    {{ group.name }}
                </span>
            </a>
        </div>

        <div id="my-groups__empty" v-if="mutableGroups.length === 0">
            {{ noRecordsText }}
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "locale",
            "groups",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[dashboard-my-groups]",
            mutableGroups: [],
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" locale: ", this.locale);
                console.log(this.tag+" groups: ", this.groups);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                this.initializeData();
            },
            initializeData() {
                if (this.groups !== undefined && this.groups !== null && this.groups.length > 0) {
                    for (let i = 0; i < this.groups.length; i++) {
                        this.mutableGroups.push(this.groups[i]);
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
    #dashboard-my-groups {
        #my-groups__list {
            .my-group {
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
                .my-group__title {
                    flex: 1;
                }
                .my-group__status {

                }
            }
        }
        #my-groups__empty {
            padding: 15px;
            box-sizing: border-box;
        }
    }
</style>