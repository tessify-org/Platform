<template>
    <div id="task-dashboard-sidebar-seniorities" class="task-overview__sidebar-panel elevation-1">
        <div class="sidebar-panel__header">
            <div class="sidebar-panel__header-title">{{ title }}</div>
            <div class="sidebar-panel__header-info" v-if="hasHint">
                <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                        <span v-on="on">
                            <i class="far fa-question-circle"></i>
                        </span>
                    </template>
                    <span>{{ hint }}</span>
                </v-tooltip>
            </div>
        </div>
        <div class="sidebar-panel__content">
            <div id="seniorities" v-if="mutableSeniorities.length > 0">
                <div class="seniority-wrapper" v-for="(seniority, ci) in mutableSeniorities" :key="ci">
                    <div class="seniority">
                        <v-checkbox
                            v-model="mutableSeniorities[ci].selected"
                            :value="seniority.id"
                            :label="seniority.label"
                            class="mt-0" hide-details
                            @change="onCheckboxValueChanged(ci)">
                        </v-checkbox>
                    </div>
                </div>
            </div>
            <div id="no-categories" v-if="mutableSeniorities.length === 0">
                {{ noRecordsText }}
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from './../../../event-bus.js';
    export default {
        props: [
            "seniorities",
            "title",
            "hint",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[task-dashboard-sidebar-seniorities]",
            mutableSeniorities: [],
        }),
        computed: {
            hasHint() {
                return this.hint !== undefined && this.hint !== null && this.hint !== "";
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" seniorities: ", this.seniorities);
                console.log(this.tag+" title: ", this.title);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                this.initializeData();
            },
            initializeData() {
                if (this.seniorities !== undefined && this.seniorities !== null && this.seniorities.length > 0) {
                    for (let i = 0; i < this.seniorities.length; i++) {
                        let seniority = this.seniorities[i];
                        seniority.selected = false;
                        this.mutableSeniorities.push(seniority);
                    }
                }
            },
            onCheckboxValueChanged(index) {
                console.log(this.tag+" checkbox value changed: ", index);
                let selected = [];
                for (let i = 0; i < this.mutableSeniorities.length; i++) {
                    if (this.mutableSeniorities[i].selected) {
                        selected.push(this.mutableSeniorities[i].id);
                    }
                }
                EventBus.$emit("task-dashboard__selected-seniorities", selected);
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #task-dashboard-sidebar-seniorities {

    }
</style>