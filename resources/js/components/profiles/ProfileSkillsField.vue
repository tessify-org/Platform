<template>
    <div id="profile-skills-field">
        
        <!-- Label -->
        <div id="profile-skills-field__label">{{ labelText }}</div>

        <!-- Skills -->
        <div id="profile-skills-field__skills" v-if="mutableSkills.length > 0">
            <div class="profile-skills-field__skill" v-for="(skill, si) in mutableSkills" :key="si">
                <div class="skill-name">
                    {{ skill.skill }}
                </div>
                <div class="skill-mastery">
                    {{ skill.mastery }}/10
                </div>
                <div class="skill-actions">
                    <div class="skill-action view" @click="onClickView(si)">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="skill-action edit" @click="onClickEdit(si)">
                        <i class="fas fa-edit"></i>
                    </div>
                    <div class="skill-action delete" @click="onClickDelete(si)">
                        <i class="fas fa-trash-alt"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- No skills -->
        <div id="profile-skills-field__no-records" v-if="mutableSkills.length === 0">
            {{ noRecordsText }}
        </div>

        <!-- Actions -->
        <div id="profile-skills-field__actions">
            <v-btn color="primary" depressed small @click="onClickAdd">
                <i class="fas fa-plus"></i>
                {{ addButtonText }}
            </v-btn>
        </div>

        <!-- View skill dialog -->
        <v-dialog v-model="dialogs.view.show" width="600">
            <div class="dialog" v-if="this.dialogs.view.index !== null">
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <div class="dialog-content">
                    <h3 class="dialog-title">{{ viewDialogTitleText }}</h3>
                    <div class="dialog-text">
                        <div class="details bordered mb-0">
                            <div class="detail">
                                <div class="key">{{ viewDialogSkillText }}</div>
                                <div class="val">{{ mutableSkills[this.dialogs.view.index].skill }}</div>
                            </div>
                            <div class="detail">
                                <div class="key">{{ viewDialogMasteryText }}</div>
                                <div class="val">{{ mutableSkills[this.dialogs.view.index].mastery }} / 10</div>
                            </div>
                            <div class="detail" v-if="mutableSkills[this.dialogs.view.index].description !== '' && mutableSkills[this.dialogs.view.index].description !== null">
                                <div class="key">{{ viewDialogDescriptionText }}</div>
                                <div class="val">{{ mutableSkills[this.dialogs.view.index].description }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dialog-controls">
                    <div class="dialog-controls__right">
                        <v-btn depressed color="warning" @click="onClickEdit(dialogs.view.index)">
                            <i class="fas fa-edit"></i>
                            {{ viewDialogEditText }}
                        </v-btn>
                        <v-btn depressed dark color="red" @click="onClickDelete(dialogs.view.index)">
                            <i class="fas fa-trash-alt"></i>
                            {{ viewDialogDeleteText }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Add skill dialog -->
        <v-dialog v-model="dialogs.add.show" width="500">
            <div class="dialog">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.add.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ createDialogTitleText }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.add.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.add.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Skill -->
                    <div class="form-field">
                        <v-combobox
                            :label="formSkillText"
                            :items="skillOptions"
                            v-model="dialogs.add.form.skill">
                        </v-combobox>
                    </div>
                    <!-- Mastery -->
                    <div class="form-field">
                        <v-select
                            :label="formMasteryText"
                            :items="masteryOptions"
                            v-model="dialogs.add.form.mastery">
                        </v-select>
                    </div>
                    <!-- Description -->
                    <div class="form-field">
                        <v-textarea
                            :label="formDescriptionText"
                            placeholder="Beschrijf je ervaring met deze vaardigheid."
                            v-model="dialogs.add.form.description">
                        </v-textarea>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.add.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ createDialogCancelText }}
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn 
                            depressed 
                            color="success"
                            @click="onClickConfirmAdd" 
                            :loading="dialogs.add.loading" 
                            :dark="!confirmAddDisabled"
                            :disabled="confirmAddDisabled">
                            <i class="far fa-save"></i>
                            {{ createDialogSubmitText }}
                        </v-btn>
                    </div>
                </div>

            </div>
        </v-dialog>

        <!-- Edit skill dialog -->
        <v-dialog v-model="dialogs.edit.show" width="500">
            <div class="dialog">
                <div class="dialog__close-button" @click="dialogs.edit.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ updateDialogTitleText }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.edit.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.edit.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Skill -->
                    <div class="form-field">
                        <v-combobox
                            :label="formSkillText"
                            :items="skillOptions"
                            v-model="dialogs.edit.form.skill">
                        </v-combobox>
                    </div>
                    <!-- Mastery -->
                    <div class="form-field">
                        <v-select
                            :label="formMasteryText"
                            :items="masteryOptions"
                            v-model="dialogs.edit.form.mastery">
                        </v-select>
                    </div>
                    <!-- Description -->
                    <div class="form-field">
                        <v-textarea
                            :label="formDescriptionText"
                            placeholder="Beschrijf je ervaring met deze vaardigheid."
                            v-model="dialogs.edit.form.description">
                        </v-textarea>
                    </div>
                </div>
                <div class="dialog-controls">
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.edit.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ updateDialogCancelText }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <v-btn 
                            depressed 
                            color="success"
                            @click="onClickConfirmEdit" 
                            :dark="!confirmEditDisabled"
                            :disabled="confirmEditDisabled">
                            <i class="far fa-save"></i>
                            {{ updateDialogSubmitText }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Delete skill dialog -->
        <v-dialog v-model="dialogs.delete.show" width="500">
            <div class="dialog">
                <div class="dialog__close-button" @click="dialogs.delete.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ deleteDialogTitleText }}</h3>
                    <!-- Text -->
                    <div class="dialog-text">{{ deleteDialogText }}</div>
                </div>
                <div class="dialog-controls">
                    <div class="dialog-controls__left">
                        <v-btn text @click="dialogs.delete.show = false">
                            <i class="fas fa-arrow-left"></i>
                            {{ deleteDialogCancelText }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <v-btn 
                            depressed dark color="danger"
                            @click="onClickConfirmDelete">
                            <i class="fas fa-trash-alt"></i>
                            {{ deleteDialogSubmitText }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Hidden input -->
        <input type="hidden" :name="name" :value="encodedSkills">
        
    </div>
</template>

<script>
    export default {
        props: [
            "name",
            "value",
            "user",
            "skills",
            "oldInput",
            "labelText",
            "noRecordsText",
            "addButtonText",
            "formSkillText",
            "formMasteryText",
            "formDescriptionText",
            "viewDialogTitleText",
            "viewDialogEditText",
            "viewDialogDeleteText",
            "viewDialogSkillText",
            "viewDialogMasteryText",
            "viewDialogDescriptionText",
            "createDialogTitleText",
            "createDialogCancelText",
            "createDialogSubmitText",
            "updateDialogTitleText",
            "updateDialogCancelText",
            "updateDialogSubmitText",
            "deleteDialogTitleText",
            "deleteDialogText",
            "deleteDialogCancelText",
            "deleteDialogSubmitText",
        ],
        data: () => ({
            tag: "[profile-skills-field]",
            masteryOptions: [],
            skillOptions: [],
            mutableSkills: [],
            dialogs: {
                view: {
                    show: false,
                    index: null,
                },
                add: {
                    show: false,
                    loading: false,
                    errors: [],
                    form: {
                        skill: "",
                        mastery: 1,
                        description: "",
                    }
                },
                edit: {
                    show: false,
                    loading: false,
                    errors: [],
                    form: {
                        skill: "",
                        mastery: 1,
                        description: "",
                    }
                },
                delete: {
                    show: false,
                    index: null,
                },
            }
        }),
        computed: {
            confirmAddDisabled() {
                return this.dialogs.add.form.skill === "";
            },
            confirmEditDisabled() {
                return this.dialogs.edit.form.skill === "";
            },
            encodedSkills() {
                return JSON.stringify(this.mutableSkills);
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" name: ", this.name);
                console.log(this.tag+" value: ", this.value);
                console.log(this.tag+" user: ", this.user);
                console.log(this.tag+" skills: ", this.skills);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" label text: ", this.labelText);
                console.log(this.tag+" no records text: ", this.noRecordsText);
                console.log(this.tag+" add button text: ", this.addButtonText);
                console.log(this.tag+" form skill text: ", this.formSkillText);
                console.log(this.tag+" form mastery text: ", this.formMasteryText);
                console.log(this.tag+" form description text: ", this.formDescriptionText);
                console.log(this.tag+" view dialog title text: ", this.viewDialogTitleText);
                console.log(this.tag+" view dialog skill text: ", this.viewDialogSkillText);
                console.log(this.tag+" view dialog mastery text: ", this.viewDialogMasteryText);
                console.log(this.tag+" view dialog description text: ", this.viewDialogDescriptionText);
                console.log(this.tag+" view dialog edit text: ", this.viewDialogEditText);
                console.log(this.tag+" view dialog delete text: ", this.viewDialogDeleteText);
                console.log(this.tag+" create dialog title text: ", this.createDialogTitleText);
                console.log(this.tag+" create dialog cancel text: ", this.createDialogCancelText);
                console.log(this.tag+" create dialog submit text: ", this.createDialogSubmitText);
                console.log(this.tag+" update dialog title text: ", this.updateDialogTitleText);
                console.log(this.tag+" update dialog cancel text: ", this.updateDialogCancelText);
                console.log(this.tag+" update dialog submit text: ", this.updateDialogSubmitText);
                console.log(this.tag+" delete dialog title text: ", this.deleteDialogTitleText);
                console.log(this.tag+" delete dialog text: ", this.deleteDialogText);
                console.log(this.tag+" delete dialog cancel text: ", this.deleteDialogCancelText);
                console.log(this.tag+" delete dialog submit text: ", this.deleteDialogSubmitText);
                this.initializeData();
                this.generateSkillOptions();
                this.generateMasteryOptions();
            },
            initializeData() {
                if (this.user !== undefined && this.user !== null && this.user.skills !== undefined && this.user.skills !== null && this.user.skills.length > 0) {
                    for (let i = 0; i < this.user.skills.length; i++) {
                        this.mutableSkills.push({
                            skill: this.user.skills[i].name,
                            mastery: this.user.skills[i].pivot.mastery,
                            description: this.user.skills[i].pivot.description,
                        });
                    }
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    
                }
            },
            generateMasteryOptions() {
                for (let i = 1; i <= 10; i++) {
                    this.masteryOptions.push({
                        text: i+"/10",
                        value: i,
                    });
                }
            },
            generateSkillOptions() {
                if (this.skills !== undefined && this.skills !== null && this.skills.length > 0) {
                    for (let i = 0; i < this.skills.length; i++) {
                        this.skillOptions.push(this.skills[i].name);
                    }
                }
            },
            onClickAdd() {
                this.dialogs.add.show = true;
            },
            onClickConfirmAdd() {
                this.mutableSkills.push({
                    skill: this.dialogs.add.form.skill,
                    mastery: this.dialogs.add.form.mastery,
                    description: this.dialogs.add.form.description,
                });
                this.dialogs.add.show = false;
            },
            onClickView(index) {
                this.dialogs.view.index = index;
                this.dialogs.view.show = true;
            },
            onClickEdit(index) {
                if (this.dialogs.view.show) this.dialogs.view.show = false;
                this.dialogs.edit.index = index;
                this.dialogs.edit.form.skill = this.mutableSkills[index].skill;
                this.dialogs.edit.form.mastery = this.mutableSkills[index].mastery; 
                this.dialogs.edit.form.description = this.mutableSkills[index].description;
                this.dialogs.edit.show = true;
            },
            onClickConfirmEdit() {
                this.mutableSkills[this.dialogs.edit.index].skill = this.dialogs.edit.form.skill;
                this.mutableSkills[this.dialogs.edit.index].mastery = this.dialogs.edit.form.mastery;
                this.mutableSkills[this.dialogs.edit.index].description = this.dialogs.edit.form.description;
                this.dialogs.edit.show = false;
            },
            onClickDelete(index) {
                if (this.dialogs.view.show) this.dialogs.view.show = false;
                this.dialogs.delete.index = index;
                this.dialogs.delete.show = true;
            },
            onClickConfirmDelete() {
                this.mutableSkills.splice(this.dialogs.delete.index, 1);
                this.dialogs.delete.show = false;
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #profile-skills-field {
        #profile-skills-field__label {
            font-size: .85em;
            margin: 0 0 10px 0;
            color: hsl(0, 0%, 45%);
        }
        #profile-skills-field__skills {
            margin: 0 0 15px 0;
            border-radius: 3px;
            background-color: hsl(0, 0%, 95%);
            .profile-skills-field__skill {
                display: flex;
                flex-direction: row;
                padding: 10px;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                .skill-name {
                    flex: 1;
                    display: flex;
                    padding: 0 0 0 10px;
                    flex-direction: row;
                    align-items: center;
                }
                .skill-mastery {
                    flex: 1;
                    display: flex;
                    padding: 0 0 0 10px;
                    flex-direction: row;
                    align-items: center;
                }
                .skill-actions {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    .skill-action {
                        width: 26px;
                        height: 26px;
                        display: flex;
                        font-size: .8em;
                        cursor: pointer;
                        color: #ffffff;
                        border-radius: 3px;
                        margin: 0 0 0 10px;
                        flex-direction: row;
                        align-items: center;
                        justify-content: center;
                        background-color: #333333;
                        &:first-child {
                            margin: 0;
                        }
                        &.view {
                            background-color: #0088ff;
                            &:hover {
                                background-color: #0072d6;
                            }
                        }
                        &.edit {
                            background-color: #f35900;
                            &:hover {
                                background-color: #e25300;
                            }
                        }
                        &.delete {
                            background-color: #b70000;
                            &:hover {
                                background-color: #990000;
                            }
                        }
                    }
                }
            }
        }
        #profile-skills-field__no-records {
            padding: 10px 15px;
            margin: 0 0 15px 0;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: hsl(0, 0%, 95%);
        }
        #profile-skills-field__actions {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-end;
        }
    }
</style>