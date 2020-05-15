<template>
    <div id="task-dashboard-sidebar-skills" class="task-overview__sidebar-panel elevation-1">
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
            <div id="skills" v-if="mutableSkills.length > 0">
                <div class="skill-wrapper" v-for="(skill, si) in mutableSkills" :key="si">
                    <div class="skill">
                        <v-checkbox
                            v-model="mutableSkills[si].selected"
                            :value="skill.id"
                            :label="skill.name"
                            class="mt-0" hide-details
                            @change="onCheckboxValueChanged(si)">
                        </v-checkbox>
                    </div>
                </div>
            </div>
            <div id="no-categories" v-if="mutableSkills.length === 0">
                {{ noRecordsText }}
            </div>
        </div>
    </div>
</template>

<script>
    import { EventBus } from './../../../event-bus.js';
    export default {
        props: [
            "skills",
            "title",
            "hint",
            "noRecordsText",
        ],
        data: () => ({
            tag: "[task-dashboard-sidebar-skills]",
            mutableSkills: [],
        }),
        computed: {
            hasHint() {
                return this.hint !== undefined && this.hint !== null && this.hint !== "";
            },
        },
        watch: {
            mutableSkills: {
                deep: true,
                handler: function() {
                    this.emitSelectedSkills();
                }
            }
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" skills: ", this.skills);
                console.log(this.tag+" title: ", this.title);
                console.log(this.tag+" hint: ", this.hint);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                this.initializeData();
            },
            initializeData() {
                if (this.skills !== undefined && this.skills !== null && this.skills.length > 0) {
                    for (let i = 0; i < this.skills.length; i++) {
                        let skill = this.skills[i];
                        skill.selected = false;
                        this.mutableSkills.push(skill);
                    }
                }
            },
            emitSelectedSkills() {
                let selected = [];
                for (let i = 0; i < this.mutableSkills.length; i++) {
                    if (this.mutableSkills[i].selected) {
                        selected.push(this.mutableSkills[i].id);
                    }
                }
                EventBus.$emit("task-dashboard__selected-skills", selected);
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #task-dashboard-sidebar-skills {

    }
</style>