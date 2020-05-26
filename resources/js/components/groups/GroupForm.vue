<template>
    <div id="group-form" class="elevation-1">

        <!-- Name -->
        <div class="form-field">
            <v-text-field
                name="name"
                v-model="form.name"
                :label="strings.name"
                :errors="hasErrors('name')"
                :error-messages="getErrors('name')">
            </v-text-field>
        </div>

        <!-- Slogan -->
        <div class="form-fields">
            <div class="form-field">
                <v-text-field
                    name="slogan_nl"
                    v-model="form.slogan.nl"
                    :label="strings.slogan+' ('+strings.nl+')'"
                    :errors="hasErrors('slogan_nl')"
                    :error-messages="getErrors('slogan_nl')">
                </v-text-field>
            </div>
            <div class="form-field">
                <v-text-field
                    name="slogan_en"
                    v-model="form.slogan.en"
                    :label="strings.slogan+' ('+strings.en+')'"
                    :errors="hasErrors('slogan_en')"
                    :error-messages="getErrors('slogan_en')">
                </v-text-field>
            </div>
        </div>

        <!-- Description -->
        <div class="form-fields">
            <div class="form-field">
                <v-textarea
                    name="description_nl"
                    v-model="form.description.nl"
                    :label="strings.description+' ('+strings.nl+')'"
                    :errors="hasErrors('description_nl')"
                    :error-messages="getErrors('description_nl')">
                </v-textarea>
            </div>
            <div class="form-field"><v-textarea
                    name="description_en"
                    v-model="form.description.en"
                    :label="strings.description+' ('+strings.en+')'"
                    :errors="hasErrors('description_en')"
                    :error-messages="getErrors('description_en')">
                </v-textarea>
            </div>
        </div>

        <!-- Tags -->
        <div class="form-field">
            <v-combobox
                multiple
                v-model="form.tags"
                :items="tagOptions"
                :label="strings.tags"
                :errors="hasErrors('tags')"
                :error-messages="getErrors('tags')">
            </v-combobox>
            <input type="hidden" name="tags" :value="encodedTags">
        </div>

        <!-- Header & avatar image -->
        <div class="form-fields">
            <div class="image-field">
                <div class="image-field__label">{{ strings.header_image }}</div>
                <div class="image-field__image-wrapper">
                    <img class="image-field__image" :src="form.header_image_url">
                </div>
                <div class="image-field__input">
                    <input type="file" name="header_image">
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn text :href="backHref">
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.cancel }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn color="success" type="submit" depressed>
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
            "group",
            "errors",
            "strings",
            "oldInput",
            "backHref",
            "tags",
            "apiEndpoints",
            "defaultImages",
        ],
        data: () => ({
            tag: "[group-form]",
            tagOptions: [],
            form: {
                name: "",
                slogan: {
                    nl: "",
                    en: "",
                },
                description: {
                    nl: "",
                    en: "",
                },
                header_image_url: "",
                tags: [],
            },
            upload_header: {
                loading: false,
            }
        }),
        computed: {
            encodedTags() {
                return JSON.stringify(this.form.tags);
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" group: ", this.group);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" tags: ", this.tags);
                console.log(this.tag+" api endpoints: ", this.apiEndpoints);
                console.log(this.tag+" default images: ", this.defaultImages);
                this.generateTagOptions();
                this.initializeData();
            },
            generateTagOptions() {
                if (this.tags !== undefined && this.tags !== null && this.tags.length > 0) {
                    for (let i = 0; i < this.tags.length; i++) {
                        this.tagOptions.push(this.tags[i].name);
                    }
                }
            },
            initializeData() {
                if (this.defaultImages !== undefined && this.defaultImages.header !== undefined && this.defaultImages.header !== null && this.defaultImages.header !== "") {
                    this.form.header_image_url = this.defaultImages.header;
                }
                if (this.group !== undefined && this.group !== null) {
                    this.form.name = this.group.name;
                    if (this.group.slogan !== null) {
                        this.form.slogan.nl = this.group.slogan.nl;
                        this.form.slogan.en = this.group.slogan.en;
                    }
                    this.form.description.en = this.group.description.en;
                    this.form.description.nl = this.group.description.nl;
                    this.form.header_image_url = this.group.header_image_url;
                    if (this.group.tags !== undefined && this.group.tags !== null && this.group.tags.length > 0) {
                        for (let i = 0; i < this.group.tags.length; i++) {
                            this.form.tags.push(this.group.tags[i].name);
                        }
                    }
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.name !== null) this.form.name = this.oldInput.name;
                    if (this.oldInput.slogan_nl !== null) this.form.slogan.nl = this.oldInput.slogan_nl;
                    if (this.oldInput.slogan_en !== null) this.form.slogan.en = this.oldInput.slogan_en;
                    if (this.oldInput.description_nl !== null) this.form.description.nl = this.oldInput.description_nl;
                    if (this.oldInput.description_en !== null) this.form.description.en = this.oldInput.description_en;
                    if (this.oldInput.tags !== null) this.form.tags = JSON.parse(this.oldInput.tags);
                }
            },
            hasErrors(field) {
                if (this.errors !== undefined && this.errors !== null && this.errors.length > 0) {
                    if (this.errors[field] !==  undefined &&  this.errors[field] !== "") {
                        return true;
                    }
                }
                return false;
            },
            getErrors(field) {
                if (this.errors !== undefined && this.errors !== null && this.errors[field] !== undefined && this.errors[field] !== null) {
                    return this.errors[field];
                }
                return [];
            },
            onAvatarUpload(e) {
                console.log(this.tag+" avatar selected", e);

                // Start loading
                this.upload_avatar.loading = true;

                // Compose payload
                let payload = new FormData();
                payload.append("image", this.$refs.avatar.files[0]);

                // Compose headers
                let headers = { headers: { 'Content-Type': 'multipart/form-data' } };

                // Send API request
                this.axios.post(this.apiEndpoints.upload_avatar, payload, headers)

                    // Request succeeded
                    .then(function(response) {
                        console.log(this.tag+" request succeeded, response: ", response);
                        this.upload_avatar.avatar_url = response.data.image_url;
                        this.upload_avatar.loading = false;
                    }.bind(this))

                    // Request failed
                    .catch(function(error) {
                        console.warn(this.tag+" request failed, error: ", error);
                        this.upload_avatar.loading = false;
                    }.bind(this));
                
            },
            onHeaderImageUpload(e) {
                console.log(this.tag+" avatar selected", e);

                // Start loading
                this.upload_header.loading = true;

                // Compose payload
                let payload = new FormData();
                payload.append("image", this.$refs.header.files[0]);

                // Compose headers
                let headers = { headers: { 'Content-Type': 'multipart/form-data' } };

                // Send API request
                this.axios.post(this.apiEndpoints.upload_avatar, payload, headers)

                    // Request succeeded
                    .then(function(response) {
                        console.log(this.tag+" request succeeded, response: ", response);
                        this.upload_avatar.avatar_url = response.data.image_url;
                        this.upload_header.loading = false;
                    }.bind(this))

                    // Request failed
                    .catch(function(error) {
                        console.warn(this.tag+" request failed, error: ", error);
                        this.upload_header.loading = false;
                    }.bind(this));
                
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #group-form {
        padding: 30px;
        border-radius: 3px;
        box-sizing: border-box;
        background-color: #ffffff;
    }
</style>