<template>
    <div id="task-required-skills-accordeon">

        <div id="skill-accordeon" v-if="mutableSkills.length > 0">
            <div class="skill-accordeon__item" v-for="(skill, si) in mutableSkills" :key="si" @click="onClickItem(si)">
                <div class="item-header">
                    <div class="item-title">{{ skill.name[locale] }}</div>
                    <div class="item-indicators">
                        <div class="item-indicator" v-if="!skill.visible">
                            <span>
                                <i class="fas fa-chevron-down"></i>
                            </span>
                        </div>
                        <div class="item-indicator" v-if="skill.visible">
                            <span>
                                <i class="fas fa-chevron-up"></i>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="item-content" v-if="skill.visible">
                    <div class="item-description">
                        <div class="item-description__label">{{ strings.description }}</div>
                        <div class="item-description__text" v-if="skill.pivot.description !== null && skill.pivot.description[locale] !== ''">
                            {{ skill.pivot.description[locale] }}
                        </div>
                        <div class="item-description__text" v-if="skill.pivot.description === null || skill.pivot.description[locale] !== ''">
                            {{ strings.missing_description }}
                        </div>
                    </div>
                    <div class="item-mastery">
                        <div class="item-mastery__label">{{ strings.mastery }}</div>
                        <div class="item-mastery__text">
                            {{ skill.pivot.required_mastery }}/10
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "skills",
            "strings",
            "locale",
        ],
        data: () => ({
            tag: "[task-required-skills-accordeon]",
            mutableSkills: [],
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" skills: ", this.skills);
                console.log(this.tag+" strings: ", this.strings);
                this.initializeData();
            },
            initializeData() {
                if (this.skills !== undefined && this.skills !== null && this.skills.length > 0) {
                    for (let i = 0; i < this.skills.length; i++) {
                        let skill = this.skills[i];
                        skill.visible = false;
                        this.mutableSkills.push(skill);
                    }
                }
            },
            onClickItem(index) {
                this.mutableSkills[index].visible = !this.mutableSkills[index].visible;
            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #task-required-skills-accordeon {
        #skill-accordeon {
            overflow: hidden;
            border-radius: 3px;
            background-color: #fff;
            .skill-accordeon__item {
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                .item-header {
                    height: 40px;
                    display: flex;
                    padding: 0 15px;
                    transition: all .3s;
                    flex-direction: row;
                    align-items: center;
                    box-sizing: border-box;
                    &:hover {
                        cursor: pointer;
                        background-color: hsl(0, 0%, 98%);
                    }
                    .item-title {   
                        flex: 1;
                        font-size: 1.1em;
                        font-weight: 500;
                    }
                    .item-indicators {
                        .item-indicator {

                        }
                    }
                }
                .item-content {
                    padding: 15px;
                    box-sizing: border-box;
                    border-top: 1px solid rgba(0, 0, 0, 0.1);
                    .item-description {
                        margin: 0 0 15px 0;
                        .item-description__label {
                            font-size: .9em;
                            color: hsl(0, 0%, 10%);
                        }   
                        .item-description__text {

                        }
                    }
                    .item-mastery {
                        .item-mastery__label {
                            font-size: .9em;
                            color: hsl(0, 0%, 10%);

                        }
                        .item-mastery__text {

                        }
                    }
                }
            }
        }

    }
</style>