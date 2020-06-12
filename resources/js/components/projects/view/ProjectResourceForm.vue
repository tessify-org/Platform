<template>
    <div id="project-resource-form__wrapper">

        <!-- Title -->
        <h3 id="project-resource-form__title">{{ strings.title }}</h3>

        <!-- Form -->
        <div id="project-resource-form">

            <!-- Mutable resources to process -->
            <div id="resources" v-if="mutableResources.length > 0">
                <!-- Resource -->
                <div class="resource elevation-2" v-for="(resource, ri) in mutableResources" :key="ri">
                    <!-- Icon -->
                    <div class="resource-icon">
                        <!-- Image preview -->
                        <div class="resource-preview" v-if="resourceIsImage(resource)" :style="{ backgroundImage: 'url('+resource.formatted_file_url+')' }"></div>
                        <!-- PDF icon -->
                        <div class="pdf-icon" v-if="resourceIsPdf(resource)">
                            <i class="fas fa-file-pdf"></i>
                        </div>
                    </div>
                    <!-- Inputs -->
                    <div class="resource-inputs">
                        <!-- URL -->
                        <div class="form-field">
                            <v-text-field
                                disabled
                                hide-details
                                :label="strings.form_file"
                                v-model="mutableResources[ri].file_url">
                            </v-text-field>
                        </div>
                        <!-- Title -->
                        <div class="form-field">
                            <v-text-field
                                hide-details
                                :label="strings.form_title"
                                v-model="mutableResources[ri].title">
                            </v-text-field>
                        </div>
                        <!-- Description -->
                        <div class="form-field">
                            <v-textarea
                                hide-details
                                :label="strings.form_description"
                                v-model="mutableResources[ri].description">
                            </v-textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload files -->
            <div id="upload-files" class="elevation-2">
                <!-- Hidden input field (top layer) -->
                <input id="upload-files__input" multiple type="file" accept="*" :disabled="uploading" @change="onFilesChanged($event)">
                <!-- Text layer (idle) -->
                <div id="upload-files__text" v-if="!uploading">
                    <div id="upload-files__title">{{ strings.upload_title }}</div>
                    <div id="upload-files__subtitle">{{ strings.upload_subtitle }}</div>
                </div>
                <!-- Text layer (uploading) -->
                <div id="upload-files__text" v-if="uploading">
                    <div id="upload-files__title">{{ strings.uploading_title }}</div>
                    <div id="upload-files__subtitle">{{ uploadingSubtitle }}</div>
                </div>
            </div>

            <!-- Hidden input -->
            <input type="hidden" name="resources" :value="encodedResources">

        </div>

        <!-- Controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn color="white" :href="backHref">
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.back }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn color="success" type="submit" depressed :disabled="mutableResources.length === 0">
                    <i class="fas fa-save"></i>
                    {{ strings.submit }}
                </v-btn>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "project",
            "strings",
            "backHref",
            "apiEndpoints",
        ],
        data: () => ({
            tag: "[project-resource-form]",
            mutableResources: [],
            uploading: false,
            uploadingNumFiles: 0,
        }),
        computed: {
            uploadingSubtitle() {
                if (this.uploadingNumFiles === 1) {
                    return this.uploadingNumFiles + " " + this.strings.uploading_file;
                } else {
                    return this.uploadingNumFiles + " " + this.strings.uploading_files;
                }
            },
            encodedResources() {
                return JSON.stringify(this.mutableResources);
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" project: ", this.project);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
            },
            onFilesChanged(event) {
                console.log(this.tag+" files were selected for upload!");
    
                let files = event.target.files;
                
                this.uploading = true;
                this.uploadingNumFiles = files.length;

                let payload = new FormData();
                Array.from(Array(files.length).keys()).map(x => {
                    payload.append("files[]", files[x], files[x].name);
                });

                let headers = { headers: { 'Content-Type': 'multipart/form-data' } };

                this.axios.post(this.apiEndpoints.upload, payload, headers)
                    
                    .then(function(response) {
                        console.log(this.tag+" upload request succeeded, response: ", response.data);
                        for (let i = 0; i < response.data.resources.length; i++) {
                            this.mutableResources.push(response.data.resources[i]);
                        }
                        this.uploading = false;
                    }.bind(this))

                    .catch(function(error) {
                        console.warn(this.tag+" upload request failed, errors: ", error.data.errors);
                        this.uploading = false;
                    }.bind(this));

            },
            resourceIsImage(resource) {
                return resource.file_type === "jpg" || resource.file_type === "jpeg" || resource.file_type === "png" || resource.file_type === "svg" || resource.file_type === "gif";
            },
            resourceIsPdf(resource) {
                return resource.file_type === 'pdf';
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #project-resource-form__wrapper {
        margin: 0 0 30px 0;
        #project-resource-form__title {
            font-size: 1em;
            font-weight: 300;
            margin: 0 0 5px 0;
        }
        #project-resource-form {
            #resources {
                margin: 0 0 30px 0;
                .resource {
                    display: flex;
                    padding: 25px;
                    margin: 0 0 15px 0;
                    border-radius: 3px;
                    flex-direction: row;
                    box-sizing: border-box;
                    background-color: #fff;
                    &:last-child {
                        margin: 0;
                    }
                    .resource-icon {
                        flex: 0 0 100px;
                        margin: 0 25px 0 0;
                        .resource-preview {
                            width: 100px;
                            height: 100px;
                            border-radius: 2px;
                            background-size: contain;
                            background-repeat: no-repeat;
                            background-position: center center;
                        }
                        .pdf-icon {
                            width: 100px;
                            height: 100px;
                            display: flex;
                            font-size: 2em;
                            align-items: center;
                            flex-direction: column;
                            justify-content: center;
                            background-color: hsl(0, 0%, 90%);
                        }
                    }
                    .resource-inputs {
                        flex: 1;
                    }
                }
            }
            #upload-files {
                height: 120px;
                cursor: pointer;
                position: relative;
                border-radius: 3px;
                background-color: hsl(0, 0%, 100%);
                #upload-files__input {
                    top: 0;
                    left: 0;
                    opacity: 0;
                    z-index: 2;
                    width: 100%;
                    height: 120px;
                    display: flex;
                    cursor: pointer;
                    position: absolute;
                    flex-direction: row;
                    align-items: center;
                    justify-content: center;
                }
                #upload-files__text {
                    z-index: 1;
                    width: 100%;
                    height: 100%;
                    display: flex;
                    position: absolute;
                    align-items: center;
                    flex-direction: column;
                    justify-content: center;
                    #upload-files__title {
                        font-size: 1.1em;
                        font-weight: 500;
                    }
                    #upload-files__subtitle {
                        font-size: .9em;
                        color: hsl(0, 0%, 25%);
                    }
                }
            }
        }
    }
</style>