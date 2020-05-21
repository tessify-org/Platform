<template>
    <div id="group-role-overview">

        <div id="group-role-overview__list" v-if="mutableRoles.length > 0">
            <div class="group-role" v-for="(role, ri) in mutableRoles" :key="ri">
                <div class="group-role__name">
                    {{ role.name }}
                </div>
                <div class="group-role__actions" v-if="canManage">
                    <div class="action view" @click="onClickView(ri)">
                        <div class="action-icon"></div>
                    </div>
                    <div class="action update" @click="onClickUpdate(ri)">
                        <div class="action-icon"></div>
                    </div>
                    <div class="action delete" @click="onClickDelete(ri)">
                        <div class="action-icon">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="group-role-overview__no-records" v-if="mutableRoles.length === 0">
            {{ strings.no_records }}
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "roles",
            "strings",
            "apiEndpoints",
        ],
        data: () => ({
            tag: "[group-role-overview]",
            mutableRoles: [],
            dialogs: {
                create: {
                    show: false,
                    loading: false,
                    errors: [],
                    form: {
                        name: "",
                        description: {
                            en: "",
                            nl: "",
                        },
                    }
                },
                update: {
                    show: false,
                    loading: false,
                    errors: [],
                    index: null,
                    form: {
                        name: "",
                        description: {
                            en: "",
                            nl: "",
                        },
                    },
                },
                delete: {
                    show: false,
                    loading: false,
                    errors: [],
                    index: null,
                },
            },
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" roles: ", this.roles);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
                this.initializeData();
            },
            initializeData() {
                if (this.roles !== undefined && this.roles !== null && this.roles.length > 0) {
                    for (let i = 0; i < this.roles.length; i++) {
                        this.mutableRoles.push(this.roles[i]);
                    }
                }
            },
            onClickCreate() {
                this.dialogs.create.form.name = "";
                this.dialogs.create.form.description.nl = "";
                this.dialogs.create.form.description.en = "";
                this.dialogs.create.show = true;
            },
            onClickConfirmCreate() {

            },
            onClickView(index) {
                this.dialogs.view.index = index;
                this.dialogs.view.show = true;
            },
            onClickUpdate(index) {
                this.dialogs.update.index = index;
                this.dialogs.update.form.name = this.mutableRoles[index].name;
                this.dialogs.update.form.description.nl = this.mutableRoles[index].description.nl;
                this.dialogs.update.form.description.en = this.mutableRoles[index].description.en;
                this.dialogs.update.show = true;
            },
            onClickConfirmUpdate() {

            },
            onClickDelete(index) {
                this.dialogs.delete.index = index;
                this.dialogs.delete.show = true;
            },
            onClickConfirmDelete() {

            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #group-role-overview {
        #group-role-overview__list {
            .group-role {
                display: flex;
                padding: 15px 20px;
                flex-direction: row;
                align-items: center;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                .group-role__name {
                    flex: 1;
                }
                .group-role__actions {
                    display: flex;
                    padding: 0 0 0 15px;
                    flex-direction: row;
                    .action {
                        margin: 0 0 0 15px;
                        &.view {
                            background-color: #0081f1;
                            &:hover {
                                background-color: #0064bb;
                            }
                        }
                        &.update {
                            background-color: #f16000;
                            &:hover {
                                background-color: #c34e00;
                            }
                        }
                        &.delete {
                            background-color: #cf0202;
                            &:hover {
                                background-color: #970000;
                            }
                        }
                        &:first-child {
                            margin: 0;
                        }
                    }
                }
            }
        }
        #group-role-overview__no-records {
            padding: 25px;
            text-align: center;
            box-sizing: border-box;
        }
    }
</style>