<template>
    <div id="project-resource-overview">

        <!-- Title -->
        <h3 id="project-resource-overview__title">{{ strings.title }}</h3>

        <!-- Resources -->
        <div id="project-resource-overview__resources" class="elevation-1" v-if="paginatedResources.length > 0">
            <div class="resource" v-for="(resource, ri) in paginatedResources" :key="ri" @click="onClickResource(ri)">
                <div class="resource-icon" v-if="resource.file_type === 'pdf'">
                    <i class="fas fa-file-pdf"></i>
                </div>
                <div class="resource-icon" v-if="resource.file_type === 'jpg' || resource.file_type === 'jpeg' || resource.file_type === 'png' || resource.file_type === 'gif' || resource.file_type === 'svg'">
                    <i class="fas fa-file-image"></i>
                </div>
                <div class="resource-title" v-html="getResourceTitle(resource)"></div>
                <div class="resource-size">
                    {{ resource.file_size }} kb
                </div>
            </div>
        </div>

        <!-- No resources -->
        <div id="project-resource-overview__no-resources" class="elevation-1" v-if="paginatedResources.length === 0">
            {{ strings.no_records }}
        </div>

        <!-- Pagination -->
        <div id="project-resource-overview__pagination" v-if="numPaginatedPages > 1">
            <v-pagination v-model="pagination.currentPage" :length="numPaginatedPages" total-visible="10"></v-pagination>
        </div>

        <!-- View resource dialog -->
        <v-dialog v-model="dialogs.view.show" width="800">
            <div class="dialog" v-if="dialogs.view.index !== null">

                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.view.show = false">
                    <i class="fas fa-times"></i>
                </div>
                
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Dialog title -->
                    <h3 class="dialog-title">{{ strings.view_dialog_title }}</h3>
                    <!-- Resource info -->
                    <div id="resource-info">
                        <!-- Preview -->
                        <div id="resource-info__left">
                            <!-- Image preview -->
                            <div id="resource-info__preview" v-if="viewDialogHasImage" :style="{ backgroundImage: 'url(' + viewDialogImageUrl + ')' }"></div>
                            <!-- Icon -->
                            <div id="resource-info__icon" v-if="!viewDialogHasImage">
                                <!-- PDF icon -->
                                <span v-if="mutableResources[dialogs.view.index].file_type === 'pdf'">
                                    <i class="fas fa-file-pdf"></i>
                                </span>
                            </div>
                        </div>
                        <div id="resource-info__right">
                            <!-- Title -->
                            <div id="resource-info__title">{{ viewDialogResourceTitle }}</div>
                            <!-- Description -->
                            <div id="resource-info__description" v-if="mutableResources[dialogs.view.index].description !== null">
                                {{ mutableResources[dialogs.view.index].description }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Controls -->
                <div class="dialog-controls">
                    <div class="dialog-controls__left">
                        <v-btn depressed color="success" :href="mutableResources[dialogs.view.index].file_url" target="_blank">
                            <i class="fas fa-download"></i>
                            {{ strings.view_dialog_download }}
                        </v-btn>
                    </div>
                    <div class="dialog-controls__right">
                        <v-btn text color="warning" @click="onClickEditResource(dialogs.view.index)">
                            <i class="fas fa-edit"></i>
                            {{ strings.view_dialog_edit }}
                        </v-btn>
                        <v-btn text color="red" dark @click="onClickDeleteResource(dialogs.view.index)">
                            <i class="fas fa-trash-alt"></i>
                            {{ strings.view_dialog_delete }}
                        </v-btn>
                    </div>
                </div>

            </div>
        </v-dialog>

        <!-- Edit resource dialog -->
        <v-dialog v-model="dialogs.edit.show" width="800">
            <div class="dialog" v-if="dialogs.edit.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="dialogs.edit.show = false">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.edit_dialog_title }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.edit.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.edit.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Form -->
                    <div class="dialog-text">
                        <!-- Title -->
                        <v-text-field label="Title" v-model="dialogs.edit.form.title"></v-text-field>
                        <!-- Description -->
                        <v-textarea label="Description" v-model="dialogs.edit.form.description"></v-textarea>
                    </div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text small @click="onClickCancelEditResource">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.edit_dialog_cancel }}
                        </v-btn>
                    </div>
                    <!-- Save changes -->
                    <div class="dialog-controls__right">
                        <v-btn small color="success" @click="onClickConfirmEditResource" :loading="dialogs.edit.loading">
                            <i class="fas fa-save"></i>
                            {{ strings.edit_dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

        <!-- Delete resource dialog -->
        <v-dialog v-model="dialogs.delete.show" width="500">
            <div class="dialog" v-if="dialogs.delete.index !== null">
                <!-- Close button -->
                <div class="dialog__close-button" @click="onClickCancelDeleteResource">
                    <i class="fas fa-times"></i>
                </div>
                <!-- Content -->
                <div class="dialog-content">
                    <!-- Title -->
                    <h3 class="dialog-title">{{ strings.delete_dialog_title }}</h3>
                    <!-- Errors -->
                    <div class="dialog-errors" v-if="dialogs.delete.errors.length > 0">
                        <div class="dialog-error" v-for="(error, ei) in dialogs.delete.errors" :key="ei">
                            {{ error }}
                        </div>
                    </div>
                    <!-- Text -->
                    <div class="dialog-text">{{ strings.delete_dialog_text }}</div>
                </div>
                <!-- Controls -->
                <div class="dialog-controls">
                    <!-- Cancel -->
                    <div class="dialog-controls__left">
                        <v-btn text small @click="onClickCancelDeleteResource">
                            <i class="fas fa-arrow-left"></i>
                            {{ strings.delete_dialog_cancel }}
                        </v-btn>
                    </div>
                    <!-- Confirm delete -->
                    <div class="dialog-controls__right">
                        <v-btn small color="red" dark @click="onClickConfirmDeleteResource" :loading="dialogs.delete.loading">
                            <i class="fas fa-trash-alt"></i>
                            {{ strings.delete_dialog_submit }}
                        </v-btn>
                    </div>
                </div>
            </div>
        </v-dialog>

    </div>
</template>

<script>
    export default {
        props: [
            "project",
            "resources",
            "strings",
            "errors",
            "apiEndpoints",
        ],
        data: () => ({
            tag: "[project-resource-overview]",
            mutableResources: [],
            pagination: {
                perPage: 10,
                currentPage: 1,
            },
            paginatedResources: [],
            dialogs: {
                view: {
                    show: false,
                    index: null,
                },
                edit: {
                    show: false,
                    loading: false,
                    index: null,
                    errors: [],
                    form: {
                        title: "",
                        description: "",
                    }
                },
                delete: {
                    show: false,
                    loading: false,
                    index: null,
                    errors: [],
                },
            }
        }),
        computed: {            
            numPaginatedPages() {
                return Math.ceil(this.mutableResources.length/this.pagination.perPage);
            },
            viewDialogHasImage() {
                if (this.dialogs.view.index !== null) {
                    return this.mutableResources[this.dialogs.view.index] !== undefined &&
                           ( this.mutableResources[this.dialogs.view.index].file_type === "jpg" || 
                             this.mutableResources[this.dialogs.view.index].file_type === "jpeg" || 
                             this.mutableResources[this.dialogs.view.index].file_type === "png" ||
                             this.mutableResources[this.dialogs.view.index].file_type === "gif"
                           );
                }
                return false;
            },
            viewDialogImageUrl() {
                if (this.dialogs.view.index !== null && this.mutableResources[this.dialogs.view.index] !== undefined) {
                    return this.mutableResources[this.dialogs.view.index].file_url;
                }
                return "";
            },
            viewDialogResourceTitle() {
                if (this.dialogs.view.index !== null && this.mutableResources[this.dialogs.view.index] !== undefined && this.mutableResources[this.dialogs.view.index].title !== "") {
                    return this.mutableResources[this.dialogs.view.index].title;
                }
                return "Untitled";
            },
        },
        watch: {
            "pagination.currentPage": function() {
                this.paginate();
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" resources: ", this.resources);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" errors: ", this.errors);
                this.initializeData();
            },
            initializeData() {
                if (this.resources !== undefined && this.resources !== null && this.resources.length > 0) {
                    for (let i = 0; i < this.resources.length; i++) {
                        this.mutableResources.push(this.resources[i]);
                    }
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedResources = this.mutableResources.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
            getResourceTitle(resource) {
                if (resource.title === null) {
                    return "<span class='italic'>Title missing</span>";
                } else {
                    return resource.title;
                }
            },
            onClickResource(index) {
                this.dialogs.view.index = index;
                this.dialogs.view.show = true;
            },
            onClickEditResource() {
                this.dialogs.edit.index = this.dialogs.view.index;
                this.dialogs.edit.form.title = this.mutableResources[this.dialogs.edit.index].title;
                this.dialogs.edit.form.description = this.mutableResources[this.dialogs.edit.index].description;
                this.dialogs.view.show = false;
                this.dialogs.edit.show = true;
            },
            onClickCancelEditResource() {
                this.dialogs.edit.show = false;
                this.dialogs.view.show = true;
            },
            onClickConfirmEditResource() {
                this.dialogs.edit.loading = true;

                let payload = new FormData();
                payload.append("project_resource_id", this.mutableResources[this.dialogs.view.index].id);
                payload.append("title", this.dialogs.edit.form.title);
                payload.append("description", this.dialogs.edit.form.description);

                this.axios.post(this.apiEndpoints.update_resource, payload)

                    .then(function(response) {
                        if (response.data.status === "success") {
                            this.mutableResources[this.dialogs.view.index].title = response.data.resource.title;
                            this.mutableResources[this.dialogs.view.index].description = response.data.resource.description;
                            this.dialogs.edit.loading = false;
                            this.dialogs.edit.show = false;
                        } else {
                            console.warn(this.tag+" update operation failed", response.data.errors);
                            this.dialogs.edit.loading = false;
                            this.dialogs.edit.errors = response.data.errors;
                        }
                        
                    }.bind(this))

                    .catch(function(error) {
                        this.dialogs.edit.loading = false;
                            this.dialogs.edit.errors = error.data.errors;
                    }.bind(this));

            },
            onClickDeleteResource() {
                this.dialogs.delete.index = this.dialogs.view.index;
                this.dialogs.view.show = false;
                this.dialogs.delete.show = true;
            },
            onClickCancelDeleteResource() {
                this.dialogs.delete.show = false;
                this.dialogs.view.show = true;
            },
            onClickConfirmDeleteResource() {

                this.dialogs.delete.loading = true;

                let payload = new FormData();
                payload.append("project_resource_id", this.mutableResources[this.dialogs.delete.index].id);
                
                this.axios.post(this.apiEndpoints.delete_resource, payload)

                    .then(function(response) {
                        this.mutableResources.splice(this.dialogs.delete.index, 1);
                        this.paginate();
                        this.dialogs.delete.loading = false;
                        this.dialogs.delete.show = false;
                    }.bind(this))

                    .catch(function(error) {
                        console.warn(this.tag+" request failed", error.response);
                        this.dialogs.delete.loading = false;
                        this.dialogs.delete.show = false;
                    }.bind(this));

            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #project-resource-overview {
        margin: 0 0 30px 0;
        #project-resource-overview__title {
            font-size: 1em;
            font-weight: 300;
            margin: 0 0 5px 0;
        }
        #project-resource-overview__resources {
            border-radius: 3px;
            background-color: #fff;
            .resource {
                display: flex;
                color: #000;
                padding: 15px 15px;
                transition: all .3s;
                flex-direction: row;
                text-decoration: none;
                box-sizing: border-box;
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                &:hover {
                    cursor: pointer;
                    background-color: hsl(0, 0%, 98%);
                }
                .resource-icon {
                    flex: 0 0 25px;
                }
                .resource-title {
                    flex: 2;
                }
                .resource-size {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    justify-content: flex-end;
                }
            }
        }
    }
    #project-resource-overview__no-resources {
        padding: 15px 25px;
        overflow: hidden;
        border-radius: 3px;
        box-sizing: border-box;
        background-color: #ffffff;
    }
    #resource-info {
        display: flex;
        flex-direction: row;
        #resource-info__left {
            flex: 0 0 100px;
            margin: 0 25px 0 0;
            #resource-info__preview {
                width: 100px;
                height: 100px;
                border-radius: 2px;
                background-size: contain;
                background-repeat: no-repeat;
                background-position: center center;
            }
            #resource-info__icon {
                width: 100px;
                height: 100px;
                display: flex;
                font-size: 2em;
                border-radius: 2px;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                background-color: hsl(0, 0%, 90%);
            }
        }
        #resource-info__right {
            flex: 1;
            #resource-info__title {
                font-size: 1.2em;
            }
            #resource-info__description {

            }
        }
    }
</style>