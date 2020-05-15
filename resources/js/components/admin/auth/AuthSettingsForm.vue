<template>
    <div id="auth-settings-form">

        <!-- Whitelisted domains -->
        <div class="content-card elevation-1 mb">
            <div class="content-card__content">

                <div id="whitelisted-domains">
                    <div id="whitelisted-domains__header">
                        <div id="whitelisted-domains__header-title">
                            <h3>Whitelisted domains</h3>
                        </div>
                        <div id="whitelisted-domains__header-actions">
                            <v-tooltip bottom>
                                <template v-slot:activator="{ on }">
                                    <div id="add-button" @click="onClickAddDomain" v-on="on">
                                        <i class="fas fa-plus"></i>
                                    </div>
                                </template>
                                <span>Add a whitelisted domain</span>
                            </v-tooltip>
                        </div>
                    </div>
                    <div id="whitelisted-domains__list" v-if="paginatedWhitelistedDomains.length > 0">
                        <div class="whitelisted-domain__wrapper" v-for="(domain, di) in paginatedWhitelistedDomains" :key="di">
                            <div class="whitelisted-domain">
                                <div class="whitelisted-domain__title">
                                    <input type="text" v-model="paginatedWhitelistedDomains[di].domain">
                                </div>
                                <div class="whitelisted-domain__actions">
                                    <v-tooltip bottom>
                                        <template v-slot:activator="{ on }">
                                            <div class="delete-button" @click="onClickDeleteDomain(di)" v-on="on">
                                                <i class="fas fa-trash-alt"></i>
                                            </div>
                                        </template>
                                        <span>Delete whitelisted domain</span>
                                    </v-tooltip>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="whitelisted-domains__empty" v-if="paginatedWhitelistedDomains.length === 0">
                        No domains have been whitelisted yet
                    </div>
                    <div id="whitelisted-domains__pagination" v-if="numPaginatedPages > 1">
                        <v-pagination v-model="pagination.currentPage" :length="numPaginatedPages" total-visible="10"></v-pagination>
                    </div>
                </div>
                <input type="hidden" name="whitelisted_domains" :value="encodedWhitelistedDomains">

            </div>
        </div>

        <!-- Flags -->
        <div class="content-card elevation-1">
            <div class="content-card__content">

                <!-- Whitelisted domains enabled -->
                <div class="form-field checkbox">
                    <v-checkbox
                        hide-details
                        label="Whitelisted domains enabled"
                        v-model="form.whitelisted_domains_enabled">
                    </v-checkbox>
                    <input type="hidden" name="whitelisted_domains_enabled" :value="form.whitelisted_domains_enabled">
                </div>

                <!-- Email activation required -->
                <div class="form-field checkbox">
                    <v-checkbox 
                        hide-details
                        label="Email activation required"
                        v-model="form.email_activation_required">
                    </v-checkbox>
                    <input type="hidden" name="email_activation_required" :value="form.email_activation_required">
                </div>

            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "whitelistedDomains",
            "settings",
            "oldInput",
            "errors",
        ],
        data: () => ({
            tag: "[auth-settings-form]",
            mutableWhitelistedDomains: [],
            paginatedWhitelistedDomains: [],
            pagination: {
                perPage: 30,
                currentPage: 1,
            },
            form: {
                email_activation_required: false,
                whitelisted_domains_enabled: false,
            },
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.mutableWhitelistedDomains.length / this.pagination.perPage);
            },
            encodedWhitelistedDomains() {
                let out = [];
                for (let i = 0; i < this.mutableWhitelistedDomains.length; i++) {
                    out.push(this.mutableWhitelistedDomains[i].domain);
                }
                return JSON.stringify(out);
            }
        },
        watch: {
            pagination: {
                deep: true,
                handler: function() {
                    console.log(this.tag+" pagination changed");
                    this.paginateWhitelistedDomains();
                },
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" whitelisted domains: ", this.whitelistedDomains);
                console.log(this.tag+" settings: ", this.settings);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" errors: ", this.errors);
                this.initializeData();
            },
            initializeData() {
                if (this.whitelistedDomains !== undefined && this.whitelistedDomains !== null && this.whitelistedDomains.length > 0) {
                    for (let i = 0; i < this.whitelistedDomains.length; i++) {
                        this.mutableWhitelistedDomains.push({ domain: this.whitelistedDomains[i].domain });
                    }
                    this.paginateWhitelistedDomains();
                }
                if (this.settings !== undefined && this.settings !== null) {
                    this.form.email_activation_required = this.settings.email_activation_required;
                    this.form.whitelisted_domains_enabled = this.settings.whitelisted_domains_enabled;
                }
            },
            paginateWhitelistedDomains() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedWhitelistedDomains = this.mutableWhitelistedDomains.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
            onClickAddDomain() {
                this.mutableWhitelistedDomains.push({ domain: "" });
                this.paginateWhitelistedDomains();
                if (this.pagination.currentPage !== this.numPaginatedPages) {
                    this.pagination.currentPage = this.numPaginatedPages;
                }
            },
            onClickDeleteDomain(index) {
                console.log(this.tag+" clicked delete button; index: ", index);
                let sum = ( this.pagination.perPage * ( this.pagination.currentPage - 1 ) );
                console.log(this.tag+" sum: ", sum);
                let realIndex = sum + index;
                console.log(this.tag+" real index: ", realIndex);
                this.mutableWhitelistedDomains.splice(realIndex, 1);
                this.paginateWhitelistedDomains();
            },
        },
        mounted() {
            this.initialize();
        },
    }
</script>

<style lang="scss">
    #auth-settings-form {
        
        #whitelisted-domains {
            #whitelisted-domains__header {
                display: flex;
                margin: 0 0 20px 0;
                flex-direction: row;
                #whitelisted-domains__header-title {
                    flex: 1;
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    h3 {
                        margin: 0;
                        line-height: 1em;
                    }
                }
                #whitelisted-domains__header-actions {
                    display: flex;
                    flex-direction: row;
                    align-items: center;
                    #add-button {
                        cursor: pointer;
                    }
                }
            }
            #whitelisted-domains__list {
                display: flex;
                margin: 0 -15px;
                flex-wrap: wrap;
                flex-direction: row;
                .whitelisted-domain__wrapper {
                    padding: 0 15px;
                    flex: 0 0 33.33%;
                    box-sizing: border-box;
                    .whitelisted-domain {
                        display: flex;
                        padding: 5px 0;
                        flex-direction: row;
                        align-items: center;
                        box-sizing: border-box;
                        &:last-child {
                            border-bottom: 0;
                        }
                        .whitelisted-domain__title {
                            flex: 1;
                            input[type=text] {
                                width: 100%;
                                font-size: .8em;
                                padding: 3px 5px;
                                box-sizing: border-box;
                                background-color: hsl(0, 0%, 98%);
                            }
                        }
                        .whitelisted-domain__actions {
                            margin: 0 0 0 15px;
                            .delete-button {
                                cursor: pointer;
                            }
                        }
                    }
                }
            }
            #whitelisted-domains__empty {
                
            }
            #whitelisted-domains__pagination {
                margin: 25px 0 0 0;
            }
        }

    }
</style>