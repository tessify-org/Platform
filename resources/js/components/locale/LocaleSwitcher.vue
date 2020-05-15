<template>
    <div id="locale-switcher" :class="{ light: isLight }">
        <v-select
            hide-details
            :items="localeOptions"
            :loading="form.loading"
            v-model="form.activeLocale"
            :color="selectColor"
            :dark="isLight"
            @change="onSelectionChange">
        </v-select>
    </div>
</template>

<script>
    export default {
        props: [
            "locales",
            "activeLocale",
            "endpoint",
            "light",
        ],
        data: () => ({
            tag: "[locale-switcher]",
            localeOptions: [],
            form: {
                loading: false,
                activeLocale: "",
            }
        }),
        computed: {
            isLight() {
                return this.light === true;
            },
            selectColor() {
                return this.isLight ? '#fff' : '#000';
            }
        },
        methods: {
            initialize() {
                // console.log(this.tag+" initializing");
                // console.log(this.tag+" locales: ", this.locales);
                // console.log(this.tag+" active locale: ", this.activeLocale);
                // console.log(this.tag+" endpoint: ", this.endpoint);
                this.initializeData();
            },
            initializeData() {
                if (this.activeLocale !== undefined && this.activeLocale !== null && this.activeLocale !== "") {
                    this.form.activeLocale = this.activeLocale;
                }
                if (this.locales !== undefined && this.locales !== null && this.locales.length > 0) {
                    for (let i = 0; i < this.locales.length; i++) {
                        this.localeOptions.push({
                            text: this.locales[i].toUpperCase(),
                            value: this.locales[i],
                        });
                    }
                }
            },
            onSelectionChange() {
                // console.log(this.tag+" selection changed!");

                this.form.loading = true;
                
                let payload = new FormData();
                payload.append("locale", this.form.activeLocale);

                this.axios.post(this.endpoint, payload)
                    .then(function(response) {
                        // console.log(this.tag+" request succeeded", response.data);
                        if (response.data.status === "success") {
                            // console.log(this.tag+" operation succeeded", response.data.success);
                            this.form.loading = false;
                            window.location.reload();
                        } else {
                            console.warn(this.tag+" operation failed", response.data.error);
                            this.form.loading = false;
                        }
                    }.bind(this))
                    .catch(function(error) {
                        console.warn(this.tag+" request failed: ", error);
                        this.form.loading = false;
                    }.bind(this));

            }
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #locale-switcher {
        width: 60px;
        &.light {

        }
        .v-input {
            margin-top: 0;
            padding-top: 0;
        }
        .v-select__selections {
            color: #fff;
        }
    }
</style>
