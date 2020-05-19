<template>
    <div id="profile-skill-list">
        <div id="skills" v-if="mutableSkills.length > 0">
            <div class="skill" v-for="(skill, si) in mutableSkills" :key="si">
                <div class="skill-name">{{ skill.name[locale] }}</div>
                <div class="skill-mastery">{{ skill.pivot.mastery }}</div>
            </div>
        </div>
        <div id="no-skills" v-if="mutableSkills.length === 0">
            {{ noSkillsText }}
        </div>
    </div>
</template>

<script>
    export default {
        props: [
            "skills",
            "noSkillsText",
            "locale",
        ],
        data: () => ({
            tag: "[profile-skill-list]",
            mutableSkills: [],
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" skills: ", this.skills);
                console.log(this.tag+" no skills text: ", this.noSkillsText);
                console.log(this.tag+" locale: ", this.locale);
                this.initializeData();
            },
            initializeData() {
                if (this.skills !== undefined && this.skills !== null && this.skills.length > 0) {
                    for (let i = 0; i < this.skills.length; i++) {
                        this.mutableSkills.push(this.skills[i]);
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
    #profile-skill-list {
        #skills {
            .skill {
                display: flex;
                padding: 10px 15px;
                flex-direction: row;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                .skill-name {
                    flex: 1;
                }
                .skill-mastery {
                    
                }
            }
        }
        #no-skills {
            padding: 10px 15px;
            box-sizing: border-box;
        }
    }
</style>